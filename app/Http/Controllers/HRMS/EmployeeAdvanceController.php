<?php

namespace App\Http\Controllers\HRMS;

use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\UserRole;
use App\Models\HRMS\Advance;
use Illuminate\Http\Request;
use App\Models\HRMS\Employee;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\HRMS\DeductionSchedule;
use Illuminate\Validation\ValidationException;

class EmployeeAdvanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkPermission:employee-advances.index')->only('index');
        $this->middleware('checkPermission:employee-advances.create')->only('create', 'store');
        $this->middleware('checkPermission:employee-advances.show')->only('show');
        $this->middleware('checkPermission:employee-advances.edit')->only('edit', 'update');
        $this->middleware('checkPermission:employee-advances.destroy')->only('destroy');
    }
    public function index()
    {
        $employeeAdvances = Advance::with('employee')->paginate(100);
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();
        return Inertia::render('HRMS/EmployeeAdvances/Index', [
            'employeeAdvances' => $employeeAdvances,
            'role' => $role,
        ]);
    }

    public function create()
    {
        $employees = Employee::select('id', 'name')->get();
        return Inertia::render('HRMS/EmployeeAdvances/Create', [
            'employees' => $employees,
        ]);
    }

    public function store(Request $request)
    {

        Log::info('Request data:', $request->all());

        $validated = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'advance_amount' => 'required|numeric|min:0',
            'start_deduction_from' => 'required|date',
            'deduction_type' => 'required|in:fixed_amount,by_month',
            'deduction_amount' => 'required_if:deduction_type,fixed_amount|nullable|numeric|min:0',
            'deduction_months' => 'required_if:deduction_type,by_month|nullable|integer|min:1',
            'deduction_schedule' => 'required|array',
            'deduction_schedule.*.month' => 'required|string',
            'deduction_schedule.*.amount' => 'required|numeric|min:0',
            'deduction_schedule.*.status' => 'required|string|in:paid,stop,pending', // Add this line
            'deduction_schedule.*.stop_reason' => 'nullable|string', // Add this line
        ]);


        Log::info('Validated data:', $validated);

        $advance = Advance::create([
            'employee_id' => $validated['employee_id'],
            'advance_amount' => $validated['advance_amount'],
            'deduction_amount' => $validated['deduction_amount'],
            'deduction_months' => $validated['deduction_months'],
            'start_deduction_from' => $validated['start_deduction_from'],
            'deduction_type' => $validated['deduction_type'],
        ]);

        // Create related records in the child table
        foreach ($validated['deduction_schedule'] as $scheduleItem) {
            DeductionSchedule::create([
                'advance_id' => $advance->id,
                'month_year' => \Carbon\Carbon::parse($scheduleItem['month'])->format('Y-m'),
                'amount' => $scheduleItem['amount'],
                'status' => $scheduleItem['status'] ?? 'pending', // Provide a default value when the key is not present
                'stop_reason' => $scheduleItem['stop_reason'] ?? '', // Provide a default value when the key is not present
            ]);
        }

        return redirect()->route('employee-advances.index');
    }


    public function show(Advance $advance)
    {
        //
    }

    public function edit($id)
    {
        $advance = Advance::with('deductionSchedules')->findOrFail($id);
        $employees = Employee::select('id', 'name')->get();

        return Inertia::render('HRMS/EmployeeAdvances/Edit', [
            'advance' => $advance,
            'employees' => $employees,
        ]);
    }


    public function update(Request $request, $id)
    {
        Log::info($request->all());
        // Fetch the existing advance and its deduction schedules
        $advance = Advance::with('deductionSchedules')->findOrFail($id);

        try {
            // Perform validation (similar to the store function)
            $validated = $request->validate([
                'employee_id' => 'required|exists:employees,id',
                'advance_amount' => 'required|numeric|min:0',
                'start_deduction_from' => 'required|date',
                'deduction_type' => 'required|in:fixed_amount,by_month',
                'deduction_amount' => 'required_if:deduction_type,fixed_amount|nullable|numeric|min:0',
                'deduction_months' => 'required_if:deduction_type,by_month|nullable|integer|min:1',
                'deduction_schedule' => 'required|array',
                'deduction_schedule.*.month' => 'required|string',
                'deduction_schedule.*.amount' => 'required|numeric|min:0',
                'deduction_schedule.*.status' => 'required|string|in:paid,stop,pending',
                'deduction_schedule.*.stop_reason' => 'nullable|string',
                'deletedSchedules' => 'sometimes|array',
                'deletedSchedules.*.id' => 'required|exists:deduction_schedules,id',

            ]);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 409);
        }
        // Perform validation (similar to the store function)
        // $validated = $request->validate([

        // ]);

        // Update the advance data
        $advance->update($request->only([
            'employee_id' => $validated['employee_id'],
            'advance_amount' => $validated['advance_amount'],
            'deduction_amount' => $validated['deduction_amount'],
            'deduction_months' => $validated['deduction_months'],
            'start_deduction_from' => $validated['start_deduction_from'],
            'deduction_type' => $validated['deduction_type'],
        ]));


        // $advance->deductionSchedules[$index]->update([
        //     'month_year' => \Carbon\Carbon::parse($scheduleItem['month'])->format('Y-m'),
        //     'amount' => $scheduleItem['amount'],
        //     'status' => $scheduleItem['status'],
        //     'stop_reason' => $scheduleItem['stop_reason'],
        // ]);

        // Update the related deduction schedules
        foreach ($request->deduction_schedule as $index => $schedule) {
            if (isset($advance->deductionSchedules[$index])) {
                $advance->deductionSchedules[$index]->update([
                    'month_year' => \Carbon\Carbon::parse($schedule['month'])->format('Y-m'),
                    'amount' => $schedule['amount'],
                    'status' => $schedule['status'],
                    'stop_reason' => $schedule['stop_reason'] ?? null,
                ]);
            } else {
                $advance->deductionSchedules()->create([
                    'month_year' => \Carbon\Carbon::parse($schedule['month'])->format('Y-m'),
                    'amount' => $schedule['amount'],
                    'status' => $schedule['status'],
                    'stop_reason' => $schedule['stop_reason'] ?? null,
                ]);
            }
        }

        // Delete the removed deduction schedules
        if ($request->has('deletedSchedules')) {
            foreach ($request->deletedSchedules as $deletedSchedule) {
                $advance->deductionSchedules()->where('id', $deletedSchedule['id'])->delete();
            }
        }



        return redirect()->route('employee-advances.index');
    }


    public function destroy( $id)
    {
        // return $id;
        $advances = Advance::findOrFail($id);
        $advances->delete();
        return Inertia::location(route('employee-advances.index'));
    }
}
