<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\PasswordResetController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('v1/auth/login', LoginController::class);

Route::group([
    'prefix' => '/v1/password/'
], function () {
    Route::get('find/{token}', [PasswordResetController::class, 'find']);
    Route::post('create', [PasswordResetController::class, 'create']);
    Route::post('reset', [PasswordResetController::class, 'reset']);
});


Route::group(['middleware' => ['auth:sanctum', 'auth_admin'], 'prefix' => 'v1'], function () {

    Route::group(['middleware' => 'role:super-admin', 'prefix' => 'management'], function () {
        Route::get('users', [UserController::class, 'index']);
        Route::post('users', [UserController::class, 'store']);
        Route::post('users/{user_id}/update', [UserController::class, 'update']);
        Route::delete('users/{user_id}/delete', [UserController::class, 'delete']);
    });
});
