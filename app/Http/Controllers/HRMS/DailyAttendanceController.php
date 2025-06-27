<?php

namespace App\Http\Controllers\HRMS;

use Inertia\Inertia;
use App\Models\UserRole;
use Illuminate\Http\Request;
use App\Models\HRMS\Employee;
use App\Models\HRMS\Department;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\HRMS\DailyAttendance;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class DailyAttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkPermission:daily-attendances.index')->only('index');
        $this->middleware('checkPermission:daily-attendances.create')->only('create', 'store');
        $this->middleware('checkPermission:daily-attendances.show')->only('show');
        $this->middleware('checkPermission:daily-attendances.edit')->only('edit', 'update');
        $this->middleware('checkPermission:daily-attendances.destroy')->only('destroy');
    }

    public function index(Request $request)
    {
        $date = $request->input('date') ? $request->input('date') : now()->format('Y-m-d');
        $departmentId = $request->input('department_id');
        $employeeId = $request->input('employee_id');

        $employeesQuery = Employee::query();

        // Apply department filter
        if ($departmentId) {
            $employeesQuery->where('department_id', $departmentId);
        }

        // Apply employee filter
        if ($employeeId) {
            $employeesQuery->where('id', $employeeId);
        }
        $employeesQuery->orderBy('name', 'asc');
        // Fetch employees with their daily attendances for the specific date
        $employees = $employeesQuery->with(['department', 'designation', 'dailyAttendances' => function ($query) use ($date) {
            $query->where('date', $date);
        }])->paginate(100);

        $departments = Department::get(['id', 'name']);
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();
        return Inertia::render('HRMS/DailyAttendances/Index', [
            'employees_data' => Employee::select('id','name')->orderBy('name', 'asc')->get(),
            'employees' => $employees,
            'role' => $role,
            'departments' => $departments,
            'department_id' => $departmentId,
            'employee_id' => $employeeId,
        ]);
    }


    // public function index(Request $request)
    // {
    //     $date = $request->input('date') ? $request->input('date') : now()->format('Y-m-d');
    //     $departmentId = $request->input('department_id');
    //     $employeeId = $request->input('employee_id');

    //     $query = DailyAttendance::query();

    //     if ($date) {
    //         $query->where('date', $date);
    //     }

    //     if ($departmentId) {
    //         $query->whereHas('employee', function ($query) use ($departmentId) {
    //             $query->where('department_id', $departmentId);
    //         });
    //     }

    //     if ($employeeId) {
    //         $query->where('employee_id', $employeeId);
    //     }

    //      $dailyAttendances = $query->with(['employee:id,name,department_id,designation_id', 'employee.department:id,name', 'employee.designation:id,name'])->get();

    //     Log::info($dailyAttendances);
    //     $departments = Department::get(['id', 'name']);
    //     $employees = Employee::get(['id', 'name']);

    //     return Inertia::render('HRMS/DailyAttendances/Index', [
    //         'dailyAttendances' => $dailyAttendances,
    //         'departments' => $departments,
    //         'employees' => $employees,
    //     ]);

    // }

    public function dailyAttendance(Request $request)
    {
        $date = $request->input('date') ? $request->input('date') : now()->format('Y-m-d');
        $departmentId = $request->input('department_id');
        $employeeId = $request->input('employee_id');

        $query = DailyAttendance::query();

        if ($date) {
            $query->where('date', $date);
        }

        if ($departmentId) {
            $query->whereHas('employee', function ($query) use ($departmentId) {
                $query->where('department_id', $departmentId);
            });
        }

        if ($employeeId) {
            $query->where('employee_id', $employeeId);
        }

        $dailyAttendances = $query->with(['employee:id,name,department_id,designation_id', 'employee.department:id,name', 'employee.designation:id,name'])->get();

        Log::info($dailyAttendances);
        $departments = Department::get(['id', 'name']);
        $employees = Employee::get(['id', 'name']);

        return Inertia::render('HRMS/DailyAttendances/Index', [
            'dailyAttendances' => $dailyAttendances,
            'departments' => $departments,
            'employees' => $employees,
        ]);
    }

    public function create()
    {
        $departments = Department::all();
        $employees = Employee::all();

        Log::debug('Departments:', $departments->toArray());
        Log::debug('Employees:', $employees->toArray());

        return Inertia::render('HRMS/DailyAttendances/Create', [
            'departments' => $departments,
            'employees' => $employees,
        ]);
    }



    public function getEmployeesByFilters(Request $request)
    {
        $date = $request->input('date');
        $departmentId = $request->input('department_id');

        $query = Employee::with(['attendances' => function ($query) use ($date) {
            $query->where('date', $date);
        }]);

        if ($departmentId) {
            $query->where('department_id', $departmentId);
        }

        $employees = $query->get(['id', 'name']);

        return Inertia::render('HRMS/DailyAttendances/Create', [
            'employees' => $employees,
        ]);
    }




    public function store(Request $request)
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'date' => 'required|date',
            'check_in' => 'nullable|date_format:H:i',
            'check_out' => 'nullable|date_format:H:i',
            'attendance_type' => 'required|in:machine,manual,admin,slack',
        ]);

        DailyAttendance::create($data);

        // redirect back to the index view
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    public function update(Request $request)
    {
        // Validate the request data
        $data = $request->validate([
            'employee_id' => 'required',
            'date' => 'required',
            'check_in' => 'nullable',
            'check_out' => 'nullable',
            'remarks' => 'nullable',
        ]);

        // Find or create a DailyAttendance record for the employee and date
        $attendance = DailyAttendance::firstOrNew([
            'employee_id' => $data['employee_id'],
            'date' => $data['date'],
        ]);

        // Update the attendance record
        $attendance->check_in = $data['check_in'];
        $attendance->check_out = $data['check_out'];
        $attendance->remarks = $data['remarks'];
        $attendance->save();

        return redirect()->route('daily-attendances.index');

        // return Redirect::back()->with('message', 'Attendance updated successfully');

        // return Inertia::render('HRMS/DailyAttendances/Index', [
        //     'message' => 'Attendance updated successfully',
        //     // Include any other data that you need...
        // ]);

        // return response()->json(['message' => 'Attendance updated successfully']);
    }

    // public function update(Request $request, DailyAttendance $dailyAttendance)
    // {
    //     $data = $request->validate([
    //         'employee_id' => 'required|exists:employees,id',
    //         'date' => 'required|date',
    //         'check_in' => 'nullable|date_format:H:i',
    //         'check_out' => 'nullable|date_format:H:i',
    //         'attendance_type' => 'required|in:machine,manual,admin,slack',
    //     ]);

    //     $dailyAttendance->update($data);

    //     // redirect back to the index view
    // }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function MonthlyAttendance(Request $request)
    {
            $month = $request->input('month', now()->format('Y-m'));

            $employees = Employee::all();

            $attendances = DailyAttendance::whereYear('date', '=', date('Y', strtotime($month)))
                                           ->whereMonth('date', '=', date('m', strtotime($month)))
                                           ->get();

            $reportData = [];
            foreach ($employees as $employee) {
                $employeeData = [
                    'name' => $employee->name,
                    'attendances' => []
                ];

                for ($day = 1; $day <= 31; $day++) {
                    $date = date('Y-m-d', strtotime($month . '-' . $day));
                    $attendance = $attendances->where('employee_id', $employee->id)
                                              ->where('date', $date)
                                              ->first();

                    if ($attendance) {
                        $employeeData['attendances'][$date] = [
                            'check_in' => $attendance->check_in,
                            'check_out' => $attendance->check_out,
                            'late' => $attendance->late
                        ];
                    } else {
                        $employeeData['attendances'][$date] = null;
                    }
                }

                $reportData[] = $employeeData;
            }

        return Inertia::render('Payroll/AttendanceCalenderReport/Index', [
            'reportData' => $reportData,
            'month' => $month
        ]);
    }
}
