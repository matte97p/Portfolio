<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Real\UserController;

/*
|--------------------------------------------------------------------------
| Users Routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'user'], function () {
    // Route::post('/create', [UserController::class, 'create']);
    Route::post('/', [UserController::class, 'read']);
    Route::post('/update', [UserController::class, 'update']);
    Route::post('/delete', [UserController::class, 'delete']);
});

