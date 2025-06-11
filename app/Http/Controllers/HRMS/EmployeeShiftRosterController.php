<?php

namespace App\Http\Controllers\HRMS;

use Inertia\Inertia;
use App\Models\UserRole;
use App\Models\HRMS\Grade;
use App\Models\HRMS\Shift;
use App\Models\HRMS\Employee;
use App\Models\HRMS\Department;
use App\Models\HRMS\RosterType;
use App\Models\HRMS\Designation;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\HRMS\EmployeeShiftRoster;
use App\Http\Requests\HRMS\EmployeeShiftRosterRequest;


class EmployeeShiftRosterController extends Controller
{
    private $indexFilePath;

    public function __construct()
    {
        $this->indexFilePath = 'HRMS/EmployeeShiftRoster/Index';

        // $this->middleware('checkPermission:employees.index')->only('index');
    }

    public function index()
    {
        $employees = Employee::with(['department:id,name', 'designation:id,name'])->orderBy('name')->select('id', 'name', 'department_id', 'designation_id');
        if (request('department_id') != '') {
            $employees = $employees->where('department_id', request('department_id'));
        }
        if (request('designation_id') != '') {
            $employees = $employees->where('designation_id', request('designation_id'));
        }
        if (request('grade_id') != '') {
            $employees = $employees->where('grade_id', request('grade_id'));
        }
        $employees = $employees->paginate(100);
        $departments = Department::select('id', 'name')->orderBy('name')->get();
        $designations = Designation::select('id', 'name')->orderBy('name')->get();
        $rosterTypes = RosterType::select('id', 'name')->orderBy('name')->get();
        $grades = Grade::select('id', 'name')->orderBy('name')->get();
        $shifts = Shift::select('id', 'name')->orderBy('name')->get();
        $user = Auth::user();
        $role = UserRole::where('user_id', $user->id)->where('role_id', 1)->first();
        return Inertia::render($this->indexFilePath, [
            'employees' => $employees,
            'departments' => $departments,
            'designations' => $designations,
            'rosterTypes' => $rosterTypes,
            'grades' => $grades,
            'shifts' => $shifts,
            'role' => $role,
        ]);
    }

    public function store(EmployeeShiftRosterRequest $request)
    {
        foreach ($request->employee as $key => $employee) {
            $dateRanges = $this->getDatesBetweenDates($request->from_date, $request->to_date);
            foreach ($dateRanges as $key => $dateRange) {

                $isRosterExist = EmployeeShiftRoster::whereDate('roster_date', $dateRange)->where('employee_id', $employee['employee_id'])->first();
                $restDay = date('l', strtotime($dateRange));
                $formData = [
                    'roster_date' => $dateRange,
                    'shift_id' => $request->shift_id,
                    'rest_day' => $restDay,
                    'employee_id' => $employee['employee_id'],
                    'duty_department_id' => $employee['department_id'],
                    // 'roster_type_id' => $employee['roster_type_id'],
                    'holiday_status' => isset($employee['rest_day']) && $restDay == $employee['rest_day'] ? true : false,
                ];
                if ($isRosterExist) {
                    $isRosterExist->update($formData);
                } else {
                    EmployeeShiftRoster::create($formData);
                }
            }
        }
    }

    protected function getDatesBetweenDates($start, $end)
    {
        $dates = array();
        $current = strtotime($start);
        $endDate = strtotime($end);

        while ($current <= $endDate) {
            $dates[] = date('Y-m-d', $current);
            $current = strtotime('+1 day', $current);
        }

        return $dates;
    }
}
