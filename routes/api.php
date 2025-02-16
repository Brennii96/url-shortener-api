<?php

use App\Http\Controllers\Api\UrlEncoderController;
use Illuminate\Support\Facades\Route;

Route::post('/shorten', [UrlEncoderController::class, 'shorten'])->name('shorten');
Route::get('/decode', [UrlEncoderController::class, 'decode'])->name('decode');
