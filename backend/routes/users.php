<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Concrete\UserController;

/*
|--------------------------------------------------------------------------
| Users Routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'user'], function () {
    Route::post('/create', [UserController::class, 'create']);
    Route::post('/', [UserController::class, 'read']);
    Route::post('/update', [UserController::class, 'update']);
    Route::post('/delete', [UserController::class, 'delete']);

    Route::post('/list', [UserController::class, 'list']);
});

