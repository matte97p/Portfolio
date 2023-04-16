<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Real\PermissionController;

/*
|--------------------------------------------------------------------------
| Permissions Routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'permission'], function () {
    Route::post('/create', [PermissionController::class, 'create']);
    Route::post('/', [PermissionController::class, 'read']);
    Route::post('/update', [PermissionController::class, 'update']);
    Route::post('/delete', [PermissionController::class, 'delete']);

    Route::post('/list', [PermissionController::class, 'list']);
    Route::post('/give', [PermissionController::class, 'give']);
    Route::post('/revoke', [PermissionController::class, 'revoke']);
});
