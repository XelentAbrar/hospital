<?php

use App\Http\Controllers\Payroll\PayrollReportController;
use Illuminate\Support\Facades\Route;

//reports
Route::get('daily-attendance-report', [PayrollReportController::class, 'dailyAttendanceindex'])->name('daily-attendance-report.index');
Route::get('employee-attendance-report', [PayrollReportController::class, 'employeeAttendanceindex'])->name('employee-attendance-report.index');
Route::get('monthly-attendance-report', [PayrollReportController::class, 'monthlyAttendanceindex'])->name('monthly-attendance-report.index');
Route::get('department-attendance-report', [PayrollReportController::class, 'departmentAttendanceindex'])->name('department-attendance-report.index');
Route::get('daily-absent-report', [PayrollReportController::class, 'dailyAbsentindex'])->name('daily-absent-report.index');
Route::get('monthly-absent-report', [PayrollReportController::class, 'monthlyAbsentindex'])->name('monthly-absent-report.index');
