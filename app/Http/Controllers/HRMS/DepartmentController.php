<?php

namespace App\Http\Controllers\HRMS;

use Inertia\Inertia;
use App\Models\UserRole;
use App\Models\HRMS\Department;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use XelentAbrar\HospitalAccounts\Models\Accounts\ChartOfAccount;
use App\Http\Requests\HRMS\DepartmentRequest;

class DepartmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkPermission:departments.index')->only('index');
        $this->middleware('checkPermission:departments.create')->only('create', 'store');
        $this->middleware('checkPermission:departments.show')->only('show');
        $this->middleware('checkPermission:departments.edit')->only('edit', 'update');
        $this->middleware('checkPermission:departments.destroy')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $departments = Department::orderBy('created_at', 'desc')->whereType('payroll')->paginate(100);
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();
        return Inertia::render('HRMS/Department/Index', [
            'departments' => $departments,
            'role' => $role,
            'type' => 'payroll',
        ]);
    }

    public function inventoryIndex()
    {
        $departments = Department::orderBy('created_at', 'desc')->whereType('inventory')->paginate(100);
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();
        return Inertia::render('HRMS/Department/Index', [
            'departments' => $departments,
            'type' => 'inventory',
            'role' => $role,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if(file_exists(base_path('config/accounts.php'))){
            $coa = ChartOfAccount::select('id','acc_desc')->get();
        }
        return Inertia::render('HRMS/Department/Create', [
            'csrf_token' => csrf_token(),
            'type' => 'payroll',
            'coa' => $coa ?? null,
        ]);
    }

    public function inventoryCreate()
    {
        if(file_exists(base_path('config/accounts.php'))){
            $coa = ChartOfAccount::select('id','acc_desc')->get();
        }
        return Inertia::render('HRMS/Department/Create', [
            'csrf_token' => csrf_token(),
            'type' => 'inventory',
            'coa' => $coa ?? null,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DepartmentRequest $request)
    {
        DB::beginTransaction();

        $department = new Department();
        $formData = $request->only($department->getFillable());

        Department::create($formData);

        DB::commit();

        if($request->type == 'inventory')
            return redirect()->route('inventory-departments.index');

        return redirect()->route('departments.index');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Department $department)
    {
        if(file_exists(base_path('config/accounts.php'))){
            $coa = ChartOfAccount::select('id','acc_desc')->get();
        }
        return Inertia::render('HRMS/Department/Create', [
            'department' => $department,
            'coa' => $coa ?? null,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DepartmentRequest $request, Department $department)
    {
        DB::beginTransaction();

        $formData = $request->only($department->getFillable());
        $department->update($formData);

        DB::commit();

        if($request->type == 'inventory')
            return redirect()->route('inventory-departments.index');

        return redirect()->route('departments.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Department $department)
    {
        $type = $department->type;
        $department->delete();
        if($type == 'inventory')
            return redirect()->route('inventory-departments.index');

        return Inertia::location(route('departments.index'));
    }
}
