<?php

use App\Http\Controllers\ZipController;
use Illuminate\Support\Facades\Route;

Route::get('/zip/download/', [ZipController::class, 'downloadFile']);
