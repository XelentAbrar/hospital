<?php

namespace App\Http\Controllers\HRMS;

use Inertia\Inertia;
use App\Models\UserRole;
use Illuminate\Http\Request;
use App\Models\HRMS\Employee;
use App\Models\HRMS\Designation;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\HRMS\EmployeePromotion;
use App\Http\Requests\HRMS\EmployeePromotionRequest;

class EmployeePromotionController extends Controller
{

    public function __construct()
    {
        $this->middleware('checkPermission:employee-promotions.index')->only('index');
        $this->middleware('checkPermission:employee-promotions.create')->only('create', 'store');
        $this->middleware('checkPermission:employee-promotions.show')->only('show');
        $this->middleware('checkPermission:employee-promotions.edit')->only('edit', 'update');
        $this->middleware('checkPermission:employee-promotions.destroy')->only('destroy');
    }

    public function index()
    {
        $employeePromotions = EmployeePromotion::with(['employee', 'fromDesignation', 'toDesignation'])->paginate(100);

        // return $employeePromotions;
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();
        return Inertia::render('HRMS/EmployeePromotions/Index', [
            'employeePromotions' => $employeePromotions,
            'role' => $role,
        ]);
    }


    public function create()
    {
        $employees = Employee::get(['id', 'name']);
        $designations = Designation::get(['id', 'name']);

        return Inertia::render('HRMS/EmployeePromotions/Create', [
            'employees' => $employees,
            'designations' => $designations,
        ]);
    }

    public function store(EmployeePromotionRequest $request)
    {
        DB::beginTransaction();

        $employeePromotion = new EmployeePromotion();
        $formData = $request->only($employeePromotion->getFillable());

        EmployeePromotion::create($formData);

        DB::commit();

        return redirect()->route('employee-promotions.index');
    }


    public function edit(EmployeePromotion $employeePromotion)
    {
        $employees = Employee::get(['id', 'name']);
        $designations = Designation::get(['id', 'name']);

        return Inertia::render('HRMS/EmployeePromotions/Create', [
            'employeePromotion' => $employeePromotion,
            'employees' => $employees,
            'designations' => $designations,
        ]);
    }


    public function update(EmployeePromotionRequest $request, EmployeePromotion $employeePromotion)
    {
        DB::beginTransaction();

        $formData = $request->only($employeePromotion->getFillable());
        $employeePromotion->update($formData);

        DB::commit();

        return redirect()->route('employee-promotions.index');
    }


    public function destroy($id)
    {
        $employeePromotion = EmployeePromotion::findOrFail($id);
        $employeePromotion->delete();

        return redirect()->route('employee-promotions.index')->with('success', 'Employee promotion deleted successfully.');
    }

}
