<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Real\AuthController;
use App\Http\Controllers\Real\UserController;

Route::group(['prefix' => 'auth'], function () {
    Route::post('/oauth2', [AuthController::class, 'oauth2'])->name('login');
    Route::get('/logout', [AuthController::class, 'logout']);
    Route::get('/checkToken', [AuthController::class, 'checkToken']);
});

Route::post('user/create', [UserController::class, 'create']); /* @todo usare quello in users.php ora serve per annulare e rifare migration */
