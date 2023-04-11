<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
/*
|--------------------------------------------------------------------------
| Users Routes
|--------------------------------------------------------------------------
*/

Route::group([
        'prefix' => 'user'
    ],
function () {
    Route::get('/', [UserController::class, 'index']);
});

