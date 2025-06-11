<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;
use App\Models\HRMS\Department;
use App\Models\HRMS\Employee;
use Inertia\Inertia;

class DoctorReportController extends Controller
{
    public function index()
    {
        $employees = Employee::with(['department:id,name', 'designation:id,name'])->whereHas('designation', function ($query) {
            $query->where('is_doctor', 1);
        });
        if (request('department_id') != '') {
            $employees = $employees->where('department_id', request('department_id'));
        }
        $employees = $employees->get();
        $departments = Department::select('id', 'name')->orderBy('name')->get();
        return Inertia::render('Reports/DoctorReports/Index', [
            'employees' => $employees,
            'departments' => $departments,
        ]);
    }
}
