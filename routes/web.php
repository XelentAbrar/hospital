<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HRMS\SlackController;
use App\Models\OPD\Patient;
use Illuminate\Support\Facades\Route;


    // "repositories": [
    //     {
    //         "type": "vcs",
    //         "url": "https://github.com/XelentAbrar/hospital-donation"
    //     },
    //     {
    //         "type": "vcs",
    //         "url": "https://github.com/XelentAbrar/hospital-expense"
    //     }
    // ]

// Route::get('/update-query', function () {
//     $patiesnts = Patient::get();
//     foreach ($patiesnts as $key => $value) {
//         $lastMRNumber = Patient::max('mr_number');
//         $newMRNumber = intval($lastMRNumber) ? intval($lastMRNumber) + 1 : 1;
//         $value->update(['mr_number' => str_pad($newMRNumber, 5, '0', STR_PAD_LEFT)]);
//     }

//     // AdmissionDetail::whereNull('doctor_id')->whereNull('service_id')->update(['service_id' => Service::where('is_days','1')->value('id')]);
// });
Route::get('/update-query', function () {
    $patients = Patient::get();
    $lastMRNumber = Patient::max('mr_number');
    $newMRNumber = intval($lastMRNumber) ? intval($lastMRNumber) + 1 : 1;

    foreach ($patients as $patient) {
        $patient->update([
            'mr_number' => str_pad($newMRNumber, 5, '0', STR_PAD_LEFT)
        ]);
        $newMRNumber++;
    }

    return "MR Numbers updated successfully.";
});

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    $routes = glob(__DIR__ . "/admin/*.php");
    foreach ($routes as $route) require($route);

});
Route::post('/backup', [DashboardController::class, 'createBackup'])->name('backup');

    Route::post('/slack/checkin', [SlackController::class, 'checkIn']);
    Route::post('/slack/checkout', [SlackController::class, 'checkOut']);
