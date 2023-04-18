<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Concrete\AuthController;
use App\Http\Controllers\Concrete\UserController;

Route::group(['prefix' => 'auth'], function () {
    Route::post('/oauth2', [AuthController::class, 'oauth2'])->name('login');
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::get('/checkToken', [AuthController::class, 'checkToken']);
});
