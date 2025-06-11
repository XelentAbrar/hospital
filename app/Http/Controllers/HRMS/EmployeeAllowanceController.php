<?php

namespace App\Http\Controllers\HRMS;

use Inertia\Inertia;
use App\Models\UserRole;
use App\Models\HRMS\Employee;
use App\Models\HRMS\Allowance;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\HRMS\EmployeeAllowance;
use App\Http\Requests\HRMS\EmployeeAllowanceRequest;

class EmployeeAllowanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkPermission:employee-allowances.index')->only('index');
        $this->middleware('checkPermission:employee-allowances.create')->only('create', 'store');
        $this->middleware('checkPermission:employee-allowances.show')->only('show');
        $this->middleware('checkPermission:employee-allowances.edit')->only('edit', 'update');
        $this->middleware('checkPermission:employee-allowances.destroy')->only('destroy');
    }
    public function index()
    {
        $employeeAllowances = EmployeeAllowance::with(['employee', 'allowance'])->orderBy('created_at', 'desc')->paginate(100);
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();
        return Inertia::render('HRMS/EmployeeAllowances/Index', [
            'employeeAllowances' => $employeeAllowances,
            'role' => $role,
        ]);
    }

    public function create()
    {
        $employees = Employee::select('id', 'name')->get();
        $allowances = Allowance::select('id', 'name')->get();
        return Inertia::render('HRMS/EmployeeAllowances/Create', [
            'employees' => $employees,
            'allowances' => $allowances,
        ]);
    }

    public function store(EmployeeAllowanceRequest $request)
    {
        DB::beginTransaction();

        $employeeAllowance = new EmployeeAllowance();
        $employeeAllowanceData = $request->only($employeeAllowance->getFillable());

        EmployeeAllowance::create($employeeAllowanceData);

        DB::commit();

        return redirect()->route('employee-allowances.index');
    }

    public function edit(EmployeeAllowance $employee_allowance)
    {
        $employees = Employee::all();
        $allowances = Allowance::all();
        return Inertia::render('HRMS/EmployeeAllowances/Create', [
            'employeeAllowance' => $employee_allowance,
            'employees' => $employees,
            'allowances' => $allowances,
        ]);
    }

    public function update(EmployeeAllowanceRequest $request, EmployeeAllowance $employee_allowance)
    {
        DB::beginTransaction();

        $employeeAllowanceData = $request->only($employee_allowance->getFillable());
        $employee_allowance->update($employeeAllowanceData);

        DB::commit();

        return redirect()->route('employee-allowances.index');
    }
}
