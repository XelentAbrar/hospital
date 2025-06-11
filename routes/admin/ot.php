<?php

use App\Http\Controllers\OT\OperationController;
use App\Http\Controllers\OT\OtReportController;
use Illuminate\Support\Facades\Route;








Route::resource('operations', OperationController::class)->names('operations');
Route::get('/admissions_data', [OperationController::class, 'getAdmissions']);
Route::get('/report-anesthesia-doctor', [OtReportController::class, 'AnesthesiaDoctorReport'])->name('ot.report-anesthesia-doctor');
Route::get('/report-procedure-type', [OtReportController::class, 'ProcedureReport'])->name('ot.report-procedure-type');
Route::get('/ot/reports/patient-list', [OtReportController::class,'PatientList'])->name('ot.reports-patient-list');
