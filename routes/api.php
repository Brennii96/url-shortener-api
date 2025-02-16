<?php

use App\Http\Controllers\Api\UrlEncoderController;
use Illuminate\Support\Facades\Route;

Route::post('/encode', [UrlEncoderController::class, 'encode'])->name('encode');
Route::post('/decode', [UrlEncoderController::class, 'decode'])->name('decode');
