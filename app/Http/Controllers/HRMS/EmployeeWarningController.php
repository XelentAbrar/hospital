<?php

namespace App\Http\Controllers\HRMS;

use App\Models\User;
use Inertia\Inertia;
use App\Models\UserRole;
use App\Models\HRMS\Employee;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\HRMS\EmployeeWarning;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\HRMS\EmployeeWarningRequest;

class EmployeeWarningController extends Controller
{

    public function __construct()
    {
        $this->middleware('checkPermission:employee-warnings.index')->only('index');
        $this->middleware('checkPermission:employee-warnings.create')->only('create', 'store');
        $this->middleware('checkPermission:employee-warnings.show')->only('show');
        $this->middleware('checkPermission:employee-warnings.edit')->only('edit', 'update');
        $this->middleware('checkPermission:employee-warnings.destroy')->only('destroy');
    }

    public function index()
    {
        $employeeWarnings = EmployeeWarning::with(['employee', 'issuedBy:id,name'])
            ->orderBy('created_at', 'desc')
            ->paginate(100);
            $user = Auth::user();
            $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();
        return Inertia::render('HRMS/EmployeeWarnings/Index', [
            'employeeWarnings' => $employeeWarnings,
            'role' => $role,
        ]);
    }


    public function create()
    {
        $employees = Employee::select('id', 'name')->get();
        $users = User::select('id', 'name')->get();
        return Inertia::render('HRMS/EmployeeWarnings/Create', [
            'employees' => $employees,
            'users' => $users,
        ]);
    }

    public function store(EmployeeWarningRequest $request)
    {
        DB::beginTransaction();
        $employeeWarning = new EmployeeWarning();
        $employeeWarningData = $request->only($employeeWarning->getFillable());
        $employeeWarningData['issued_by'] = auth()->id();
        EmployeeWarning::create($employeeWarningData);

        DB::commit();

        return redirect()->route('employee-warnings.index');
    }


    public function edit(EmployeeWarning $employeeWarning)
    {
        return Inertia::render('HRMS/EmployeeWarnings/Create', [
            'employeeWarning' => $employeeWarning,
            'employees' => Employee::get(['id', 'name']),
        ]);
    }


    public function update(EmployeeWarningRequest $request, EmployeeWarning $employeeWarning)
    {
        DB::beginTransaction();

        $employeeWarningData = $request->only($employeeWarning->getFillable());

        $employeeWarning->update($employeeWarningData);

        DB::commit();

        return redirect()->route('employee-warnings.index')->with('message', 'Employee warning updated successfully.');
    }


    public function destroy($id)
    {
        $employeeWarning = EmployeeWarning::findOrFail($id);
        $employeeWarning->delete();

        return redirect()->route('employee-warnings.index')->with('success', 'Employee warning deleted successfully.');
    }
}
