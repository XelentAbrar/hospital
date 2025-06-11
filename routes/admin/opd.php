<?php

use App\Http\Controllers\OPD\AppointmentController;
use App\Http\Controllers\OPD\DonationController;
use App\Http\Controllers\OPD\FollowUpController;
use App\Http\Controllers\OPD\PatientController;
use App\Http\Controllers\OPD\PatientFeedbackController;
use App\Http\Controllers\OPD\ServiceController;
use App\Http\Controllers\OPD\CategoryController;
use App\Http\Controllers\OPD\OpdReportController;
use Illuminate\Support\Facades\Route;

Route::resource('services', ServiceController::class)->names('services');
Route::resource('categories', CategoryController::class)->names('categories');
Route::resource('donations', DonationController::class)->names('donations');
Route::get('donation/print/{donation}', [DonationController::class, 'print'])->name('donations.print');
Route::resource('patients', PatientController::class)->names('patients');
Route::resource('appointments', AppointmentController::class)->names('appointments');
Route::get('appointments/print/{appointment}', [AppointmentController::class, 'print'])->name('appointments.print');
Route::resource('follow-ups', FollowUpController::class)->names('follow-ups');
Route::resource('patient-feedbacks', PatientFeedbackController::class)->names('patient-feedbacks');

Route::post('/appointments/cancel-receipt/{appointment}', [AppointmentController::class,'cancelReceipt'])->name('opd.cancelReceipt');
Route::get('/patients-phone/{phone}', [PatientController::class,'getPatientByPhone'])->name('opd.get-patient-by-phone');
Route::get('/opd/reports/doc-detail', [OpdReportController::class,'docDetailReport'])->name('opd.reports-doctor-detail');

Route::get('/opd/reports/summary-by-date', [OpdReportController::class,'summaryByDateReport'])->name('opd.reports-summary-by-date');
Route::get('/opd/reports/summary-by-shift', [OpdReportController::class,'summaryByShiftReport'])->name('opd.reports-summary-by-shift');
Route::get('/opd/reports/summary-detail-by-date', [OpdReportController::class,'summaryDetailByDateReport'])->name('opd.reports-summary-detail-by-date');
Route::get('/opd/reports/specialist-by-date', [OpdReportController::class,'specialistByDateReport'])->name('opd.reports-specialist-by-date');
Route::get('/opd/reports/general-by-date', [OpdReportController::class,'generalByDateReport'])->name('opd.reports-general-by-date');
Route::get('/opd/reports/specialist-detail-by-doctor', [OpdReportController::class,'specialistDetailByDoctorReport'])->name('opd.reports-specialist-detail-by-doctor');
Route::get('/opd/reports/general-detail-by-doctor', [OpdReportController::class,'generalDetailByDoctorReport'])->name('opd.reports-general-detail-by-doctor');
Route::get('/opd/reports/specialist-detail', [OpdReportController::class,'specialistDetailReport'])->name('opd.reports-specialist-detail');
Route::get('/opd/reports/general-detail', [OpdReportController::class,'generalDetailReport'])->name('opd.reports-general-detail');
Route::get('/opd/reports/consultant-tax', [OpdReportController::class,'consultantTaxReport'])->name('opd.reports-consultant-tax');
Route::get('/opd/reports/detail-by-doctor', [OpdReportController::class,'detailByDoctorReport'])->name('opd.reports-detail-by-doctor');
Route::get('/opd/reports/detail-by-amount', [OpdReportController::class,'detailByAmountReport'])->name('opd.reports-detail-by-amount');
Route::get('/opd/reports/detail-by-doctor-amount', [OpdReportController::class,'detailByDoctorAmountReport'])->name('opd.reports-detail-by-doctor-amount');
Route::get('/doctor-dashboard', [OpdReportController::class, 'doctorDashboard']);
Route::post('/appointments/{id}/update', [OpdReportController::class, 'updateDoctorCheck']);
Route::post('/appointments/update-bulk', [OpdReportController::class, 'updateBulkDoctorCheck']);
Route::get('/opd/reports/mr-summary', [OpdReportController::class,'MRReport'])->name('opd.reports-mr-summary');
