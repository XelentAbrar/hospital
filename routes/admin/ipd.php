<?php

use App\Http\Controllers\IPD\AdmissionController;
use App\Http\Controllers\IPD\PatientCaseStudyController;
use App\Http\Controllers\IPD\RoomController;
use App\Http\Controllers\IPD\RoomTypeController;
use App\Http\Controllers\IPD\RoomBedController;
use App\Http\Controllers\IPD\IpdReportController;
use Illuminate\Support\Facades\Route;

Route::resource('room-types', RoomTypeController::class)->names('room-types');
Route::resource('rooms', RoomController::class)->names('rooms');
Route::resource('room-beds', RoomBedController::class)->names('room-beds');
Route::resource('admissions', AdmissionController::class)->names('admissions');
Route::get('/admissions-file/{file_no}/edit', [AdmissionController::class, 'editFile'])->name('admissions-file');
Route::get('admissions/report/discharge', [AdmissionController::class, 'dischargeIndex'])->name('admissions.discharge');
Route::get('admissions/print/{admission}', [AdmissionController::class, 'print'])->name('admissions.print');
Route::get('admissions/only-print/discharge/{admission}/{discharge}', [AdmissionController::class, 'print'])->name('admissions.print.discharge');
Route::resource('patient-case-studies', PatientCaseStudyController::class)->names('patient-case-studies');


Route::post('/admissions/cancel-receipt/{admission}', [AdmissionController::class,'cancelReceipt'])->name('ipd.cancelReceipt');


//reports
Route::get('/ipd/reports/admit-patient-list', [IpdReportController::class,'admitPatientList'])->name('ipd.reports-admit-patient-list');
Route::get('/ipd/reports/patient-list', [IpdReportController::class,'PatientList'])->name('ipd.reports-patient-list');
Route::get('/ipd/reports/admission-advance-refered-by', [IpdReportController::class,'admissionAdvanceReferedBy'])->name('ipd.reports-admission-advance-refered-by');
Route::get('/ipd/reports/admission-advance-dept-by', [IpdReportController::class,'admissionAdvanceDeptBy'])->name('ipd.reports-admission-advance-dept-by');
Route::get('/ipd/reports/admission-advance-service-by', [IpdReportController::class,'admissionAdvanceServiceBy'])->name('ipd.reports-admission-advance-service-by');
Route::get('/ipd/reports/indoor-billing-detail', [IpdReportController::class,'indoorBillingDetail'])->name('ipd.reports-indoor-billing-detail');
Route::get('/ipd/reports/indoor-billing-by-careoff', [IpdReportController::class,'indoorBillingByCareoff'])->name('ipd.reports-indoor-billing-by-careoff');
Route::get('/ipd/reports/indoor-billing-by-careoff-authority', [IpdReportController::class,'indoorBillingByCareoffAuthority'])->name('ipd.reports-indoor-billing-by-careoff-authority');
Route::get('/ipd/reports/indoor-billing-detail-by-doctor', [IpdReportController::class,'IndoorBillingDetailByDoctor'])->name('ipd.reports-indoor-billing-detail-by-doctor');
Route::get('/ipd/reports/billing-file-no', [IpdReportController::class,'BillingFileNo'])->name('ipd.reports-billing-file-no');
Route::get('/ipd/reports/indoor-summary-detail', [IpdReportController::class,'IndoorSummaryDetail'])->name('ipd.reports-indoor-summary-detail');
Route::get('/ipd/reports/indoor-summary-detail-by-file-no', [IpdReportController::class,'IndoorSummaryDetailByFileNo'])->name('ipd.reports-indoor-summary-detail-by-file-no');
