<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Models\HRMS\DailyAttendance;
use App\Models\HRMS\Department;
use App\Models\HRMS\Employee;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PayrollReportController extends Controller
{
    public function dailyAttendanceindex()
    {
        $date = request('date') ?: now()->toDateString(); // Use today's date if no date is provided

        $attendances = DailyAttendance::with(['employee:id,name'])
            ->where('date', $date)
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Payroll/DailyAttendanceReport/Index', [
            'attendances' => $attendances,
        ]);
    }

    public function employeeAttendanceindex()
    {
        $attendances = DailyAttendance::with(['employee:id,name'])->orderBy('created_at', 'desc');
        if (request('employee_id') != '') {
            $attendances = $attendances->whereHas('employee', function ($query) {
                $query->where('id', request('employee_id'));
            });
        }
        $attendances = $attendances->get();
        $employees = Employee::select('id', 'name')->orderBy('name')->get();
        return Inertia::render('Payroll/EmployeeAttendanceReport/Index', [
            'attendances' => $attendances,
            'employees' => $employees,
        ]);
    }

    public function monthlyAttendanceindex()
    {
        $month = request('month') ?? now()->month; // Get the current month if not provided
        $attendances = DailyAttendance::with(['employee:id,name'])
            ->orderBy('created_at', 'desc')
            ->whereRaw('MONTH(date) = ?', [$month])
            ->get();

        return Inertia::render('Payroll/MonthlyAttendanceReport/Index', [
            'attendances' => $attendances,
        ]);
    }

    public function departmentAttendanceindex()
    {
        $attendances = DailyAttendance::with(['employee:id,name,department_id'])->orderBy('created_at', 'desc');
        if (request('department_id') != '') {
            $attendances = $attendances->whereHas('employee', function ($query) {
                $query->where('department_id', request('department_id'));
            });
        }
        $attendances = $attendances->get();
        $departments = Department::select('id', 'name')->orderBy('name')->get();
        return Inertia::render('Payroll/DepartmentAttendanceReport/Index', [
            'attendances' => $attendances,
            'departments' => $departments,
        ]);
    }

    public function dailyAbsentindex()
    {
        $date = request('date') ?: now()->toDateString(); // Use today's date if no date is provided

        // Fetch all employees
        $employees = Employee::select('id', 'name', 'department_id')->with(['department:id,name'])->get();

        // Fetch the IDs of employees who have attendance records for the given date
        $presentEmployeeIds = DailyAttendance::where('date', $date)
            ->pluck('employee_id')
            ->toArray();

        // Filter out employees who do not have an attendance record for the given date
        $absentEmployees = $employees->filter(function ($employee) use ($presentEmployeeIds) {
            return !in_array($employee->id, $presentEmployeeIds);
        });

        return Inertia::render('Payroll/DailyAbsentReport/Index', [
            'absentEmployees' => $absentEmployees,
            'date' => $date,
        ]);
    }

    public function monthlyAbsentindex()
    {
        $month = request('month') ?? now()->month; // Get the current month if not provided
        $year = request('year') ?? now()->year; // Get the current year if not provided

        // Fetch all employees
        $employees = Employee::select('id', 'name')->get();

        // Fetch the dates of the specified month
        $dates = collect();
        $numberOfDays = cal_days_in_month(CAL_GREGORIAN, $month, $year);
        for ($day = 1; $day <= $numberOfDays; $day++) {
            $dates->push(sprintf('%04d-%02d-%02d', $year, $month, $day)); // Format date as YYYY-MM-DD
        }

        // Fetch attendance records for the month
        $attendances = DailyAttendance::whereYear('date', $year)
            ->whereMonth('date', $month)
            ->get()
            ->groupBy('employee_id');

        // Determine absent dates for each employee
        $absenceData = $employees->map(function ($employee) use ($dates, $attendances) {
            $employeeAttendances = $attendances->get($employee->id, collect());
            $attendedDates = $employeeAttendances->pluck('date')->map(function ($date) {
                return date('Y-m-d', strtotime($date)); // Ensure format YYYY-MM-DD
            })->toArray();
            $absentDates = $dates->filter(function ($date) use ($attendedDates) {
                return !in_array($date, $attendedDates);
            })->values()->toArray(); // Convert to array and remove keys

            return [
                'attendedDates' => $attendedDates,
                'employee' => $employee,
                'absent_dates' => $absentDates,
            ];
        });

        return Inertia::render('Payroll/MonthlyAbsentReport/Index', [
            'absentEmployees' => $absenceData,
            'month' => $month,
            'year' => $year,
        ]);
    }
}
