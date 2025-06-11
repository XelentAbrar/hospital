<?php

use App\Http\Controllers\HRMS\AllowanceController;
use App\Http\Controllers\HRMS\AttendanceController;
use App\Http\Controllers\HRMS\AttendanceMachineController;
use App\Http\Controllers\HRMS\DailyAttendanceController;
use App\Http\Controllers\HRMS\DepartmentController;
use App\Http\Controllers\HRMS\DesignationController;
use App\Http\Controllers\HRMS\EmployeeAdvanceController;
use App\Http\Controllers\HRMS\EmployeeAllowanceController;
use App\Http\Controllers\HRMS\EmployeeController;
use App\Http\Controllers\HRMS\EmployeeDeductionController;
use App\Http\Controllers\HRMS\EmployeePromotionController;
use App\Http\Controllers\HRMS\EmployeeShiftRosterController;
use App\Http\Controllers\HRMS\EmployeeTerminationController;
use App\Http\Controllers\HRMS\EmployeeWarningController;
use App\Http\Controllers\HRMS\GradeController;
use App\Http\Controllers\HRMS\HolidayController;
use App\Http\Controllers\HRMS\LeaveTypeController;
use App\Http\Controllers\HRMS\RosterTypeController;
use App\Http\Controllers\HRMS\ShiftController;
use App\Http\Controllers\HRMS\UserCashSummaryController;
use App\Http\Controllers\SearchController;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Carbon\Carbon;




Route::resource('allowances', AllowanceController::class)->names('allowances');

Route::resource('daily-attendances', DailyAttendanceController::class)->names('daily-attendances');
Route::get('/attendance-calender-report', [DailyAttendanceController::class, 'MonthlyAttendance'])->name('attendance-calender-report.index');

Route::post('/update-attendance', [DailyAttendanceController::class, 'update']);

Route::resource('departments', DepartmentController::class)->names('departments');

Route::resource('designations', DesignationController::class)->names('designations');

Route::resource('employee-advances', EmployeeAdvanceController::class)->names('employee-advances');

Route::resource('employee-allowances', EmployeeAllowanceController::class)->names('employee-allowances');

Route::resource('employee-deductions', EmployeeDeductionController::class)->names('employee-deductions');

Route::resource('employee-promotions', EmployeePromotionController::class)->names('employee-promotions');

Route::resource('employees', EmployeeController::class)->names('employees');
Route::post('/upload-employee', [EmployeeController::class, 'upload']);

Route::post('/quick-search', [SearchController::class, 'index'])->name('quick-search');

Route::resource('employee-terminations', EmployeeTerminationController::class)->names('employee-terminations');

Route::resource('employee-warnings', EmployeeWarningController::class)->names('employee-warnings');

Route::resource('holidays', HolidayController::class)->names('holidays');

Route::resource('leave-types', LeaveTypeController::class)->names('leave-types');

Route::resource('shifts', ShiftController::class)->names('shifts');

Route::resource('attendance-machines', AttendanceMachineController::class)->names('attendance-machines');

Route::resource('roster-types', RosterTypeController::class)->names('roster-types');

Route::resource('grades', GradeController::class)->names('grades');

Route::resource('employee-shift-roster', EmployeeShiftRosterController::class)->names('employee-shift-roster');

Route::get('get-machine-attendance/{id}', [AttendanceController::class, 'index'])->name('get-machine-attendance');


Route::get('/user-cash-summary', [UserCashSummaryController::class, 'index'])->name('user-cash-summaries.index');
Route::get('/user-cash-summaries', [UserCashSummaryController::class, 'CashIndex'])->name('user-cash-summaries.index');
Route::put('/user-cash-summaries/{id}', [UserCashSummaryController::class, 'update'])->name('user-cash-summaries.update');
Route::get('/user-cash-details', [UserCashSummaryController::class, 'userDetails'])->name('user-cash-details');
Route::get('/user-cash-summary', [UserCashSummaryController::class, 'index']);

// Route::get('activity-logs', function () {
//     $activityLogs = ActivityLog::with('user:id,name')
//         ->latest();

//     if (isset($_GET['search']) && $_GET['search'] != '') {
//         $activityLogs = $activityLogs->where(function ($query) {
//             $query->whereHas('user', function ($q) {
//                 $q->where('name', 'LIKE', '%' . $_GET['search'] . '%');
//             });
//         });
//     }

//     $activityLogs = $activityLogs->paginate(100);
//     $activityLogs = $activityLogs->appends(['search' => request('search')]);

//     return Inertia::render('HRMS/Logs/UserLog', [
//         'activityLogs' => $activityLogs,
//         'filters' => request()->only(['search']),
//     ]);
// })->name('activity-log');

Route::get('activity-logs', function () {
    $activityLogs = ActivityLog::with('user:id,name')
        ->latest();

    if (isset($_GET['search']) && $_GET['search'] != '') {
        $activityLogs = $activityLogs->where(function ($query) {
            $query->whereHas('user', function ($q) {
                $q->where('name', 'LIKE', '%' . $_GET['search'] . '%');
            });
        });
    }

    $from_date = request('from_date', date('d-m-Y'));
    $to_date = request('to_date', date('d-m-Y'));

    // Convert dates to Carbon instances
    try {
        $from_date_carbon = \Carbon\Carbon::createFromFormat('d-m-Y', $from_date);
        $to_date_carbon = \Carbon\Carbon::createFromFormat('d-m-Y', $to_date);
    } catch (\Exception $e) {
        throw new \Exception('Invalid date format. Expected d-m-Y.');
    }

    $activityLogs = $activityLogs->whereBetween('activity_time', [
        $from_date_carbon->startOfDay(),
        $to_date_carbon->endOfDay()
    ]);
    $activityLogs = $activityLogs->paginate(100);

    return Inertia::render('HRMS/Logs/UserLog', [
        'activityLogs' => $activityLogs,
        'filters' => request()->only(['search', 'from_date', 'to_date']),
        'from_date' => $from_date,
        'to_date' => $to_date,
    ]);
})->name('activity-log');
