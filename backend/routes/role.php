<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RoleController;

/*
|--------------------------------------------------------------------------
| Roles Routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'role'], function () {
    Route::post('/create', [RoleController::class, 'create']);
    Route::post('/', [RoleController::class, 'index']);
    Route::post('/update', [RoleController::class, 'update']);
    Route::post('/delete', [RoleController::class, 'delete']);
});
