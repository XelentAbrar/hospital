<?php

use App\Http\Controllers\HRMS\UserCashSummaryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Lab\LabReportController;
use App\Http\Controllers\OPD\AppointmentController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/get-patient/{id}', [LabReportController::class, 'searchByTestId']);
Route::get('/get-patient', [LabReportController::class, 'TestResult']);

