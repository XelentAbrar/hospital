<?php

use App\Http\Controllers\Reports\DoctorReportController;
use App\Http\Controllers\Reports\IndoorPatientReportController;
use App\Http\Controllers\Reports\PatientReportController;
use App\Http\Controllers\Reports\ServiceReportController;
use Illuminate\Support\Facades\Route;

Route::get('patient-reports', [PatientReportController::class, 'index'])->name('patient-reports.index');
Route::get('IPD-reports-by-admission-date', [IndoorPatientReportController::class, 'indexByAdmissionDate'])->name('IPD-reports-by-admission-date.index');
Route::get('IPD-reports-by-discharge-date', [IndoorPatientReportController::class, 'indexByDischargeDate'])->name('IPD-reports-by-discharge-date.index');
Route::get('IPD-reports-by-file-no', [IndoorPatientReportController::class, 'indexByFileNo'])->name('IPD-reports-by-file-no.index');
Route::get('indoor-billing-reports', [IndoorPatientReportController::class, 'indoorbillingIndex'])->name('indoor-billing-reports.index');
Route::get('doctor-reports', [DoctorReportController::class, 'index'])->name('doctor-reports.index');
Route::get('service-reports-by-type', [ServiceReportController::class, 'indexByType'])->name('service-reports-by-type.index');
Route::get('service-billing-reports', [ServiceReportController::class, 'serviceBillingIndex'])->name('service-billing-reports.index');
