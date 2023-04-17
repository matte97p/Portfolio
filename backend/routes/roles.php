<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Concrete\RoleController;

/*
|--------------------------------------------------------------------------
| Roles Routes
|--------------------------------------------------------------------------
*/

Route::group(['prefix' => 'role'], function () {
    Route::post('/create', [RoleController::class, 'create']);
    Route::post('/', [RoleController::class, 'read']);
    Route::post('/update', [RoleController::class, 'update']);
    Route::post('/delete', [RoleController::class, 'delete']);

    Route::post('/list', [RoleController::class, 'list']);

    Route::post('/give', [RoleController::class, 'give']);
    Route::post('/revoke', [RoleController::class, 'revoke']);
    Route::post('/givePermission', [RoleController::class, 'givePermission']);
    Route::post('/revokePermission', [RoleController::class, 'revokePermission']);
});
