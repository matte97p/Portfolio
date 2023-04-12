<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::group(['prefix' => 'auth'], function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/oauth2', [AuthController::class, 'oauth2'])->name('login');
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::get('/checkToken', [AuthController::class, 'checkToken']);
});
