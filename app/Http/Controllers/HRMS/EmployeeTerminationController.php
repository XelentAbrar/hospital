<?php

namespace App\Http\Controllers\HRMS;

use Inertia\Inertia;
use App\Models\UserRole;
use App\Models\HRMS\Employee;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\HRMS\EmployeeTermination;
use App\Http\Requests\HRMS\EmployeeTerminationRequest;

class EmployeeTerminationController extends Controller
{

    public function __construct()
    {
        $this->middleware('checkPermission:employee-terminations.index')->only('index');
        $this->middleware('checkPermission:employee-terminations.create')->only('create', 'store');
        $this->middleware('checkPermission:employee-terminations.show')->only('show');
        $this->middleware('checkPermission:employee-terminations.edit')->only('edit', 'update');
        $this->middleware('checkPermission:employee-terminations.destroy')->only('destroy');
    }

    public function index()
    {
        $employeeTerminations = EmployeeTermination::with(['employee', 'approvedBy:id,name'])
            ->orderBy('created_at', 'desc')
            ->paginate(100);
            $user = Auth::user();
            $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();
        return Inertia::render('HRMS/EmployeeTerminations/Index', [
            'employeeTerminations' => $employeeTerminations,
            'role' => $role,
        ]);
    }


    public function create()
    {
        $employees = Employee::select('id', 'name')->get();
        return Inertia::render('HRMS/EmployeeTerminations/Create', [
            'employees' => $employees,
        ]);
    }

    public function store(EmployeeTerminationRequest $request)
    {
        DB::beginTransaction();

        $employeeTermination = new EmployeeTermination();
        $formData = $request->only($employeeTermination->getFillable());

        EmployeeTermination::create($formData);

        DB::commit();

        return redirect()->route('employee-terminations.index');
    }


    public function edit(EmployeeTermination $employeeTermination)
    {
        return Inertia::render('HRMS/EmployeeTerminations/Create', [
            'employeeTermination' => $employeeTermination,
            'employees' => Employee::get(['id', 'name']),
        ]);
    }


    public function update(EmployeeTerminationRequest $request, EmployeeTermination $employeeTermination)
    {
        DB::beginTransaction();

        $formData = $request->only($employeeTermination->getFillable());
        $employeeTermination->update($formData);

        DB::commit();

        return redirect()->route('employee-terminations.index')->with('message', 'Employee termination updated successfully.');
    }


    public function destroy($id)
    {
        $employeeTermination = EmployeeTermination::findOrFail($id);
        $employeeTermination->delete();

        return redirect()->route('employee-terminations.index')->with('success', 'Employee termination deleted successfully.');
    }
}
