<?php

use App\Http\Controllers\AppController;
use App\Http\Controllers\OPD\CategoryController;
use Illuminate\Support\Facades\Route;

Route::post('/upload-file', [CategoryController::class, 'uploadFile']);

Route::post('/upload-files', [AppController::class, 'uploadFiles']);
