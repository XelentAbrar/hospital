<?php

namespace App\Http\Controllers\HRMS;

use Inertia\Inertia;
use App\Models\UserRole;
use App\Models\HRMS\Employee;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\HRMS\EmployeeDeduction;
use App\Http\Requests\HRMS\EmployeeDeductionRequest;

class EmployeeDeductionController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkPermission:employee-deductions.index')->only('index');
        $this->middleware('checkPermission:employee-deductions.create')->only('create', 'store');
        $this->middleware('checkPermission:employee-deductions.show')->only('show');
        $this->middleware('checkPermission:employee-deductions.edit')->only('edit', 'update');
        $this->middleware('checkPermission:employee-deductions.destroy')->only('destroy');
    }
    public function index()
    {
        $employeeDeductions = EmployeeDeduction::with(['employee'])->orderBy('created_at', 'desc')->paginate(100);
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();
        return Inertia::render('HRMS/EmployeeDeductions/Index', [
            'employeeDeductions' => $employeeDeductions,
            'role' => $role,
        ]);
    }

    public function create()
    {
        $employees = Employee::select('id', 'name')->get();
        return Inertia::render('HRMS/EmployeeDeductions/Create', [
            'employees' => $employees,
        ]);
    }

    public function store(EmployeeDeductionRequest $request)
    {
        DB::beginTransaction();

        $employeeDeduction = new EmployeeDeduction();
        $employeeDeductionData = $request->only($employeeDeduction->getFillable());

        EmployeeDeduction::create($employeeDeductionData);

        DB::commit();

        return redirect()->route('employee-deductions.index');
    }


    public function edit(EmployeeDeduction $employeeDeduction)
    {
        return Inertia::render('HRMS/EmployeeDeductions/Create', [
            'employeeDeduction' => $employeeDeduction->only('id', 'employee_id', 'deduction_type', 'deduction_nature', 'recurring_type', 'amount', 'deduction_month'),
            'employees' => Employee::get(['id', 'name']),
        ]);
    }


    public function update(EmployeeDeductionRequest $request, EmployeeDeduction $employeeDeduction)
    {
        DB::beginTransaction();

        $employeeDeductionData = $request->only($employeeDeduction->getFillable());
        $employeeDeduction->update($employeeDeductionData);

        DB::commit();

        return redirect()->route('employee-deductions.index')->with('message', 'Employee Deduction updated successfully.');
    }


    public function destroy($id)
    {
        $employeeDeduction = EmployeeDeduction::findOrFail($id);
        $employeeDeduction->delete();

        return redirect()->route('employee-deductions.index')->with('success', 'Employee deduction deleted successfully.');
    }
}
