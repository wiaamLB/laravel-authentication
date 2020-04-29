<?php

use App\Http\Controllers\Admin\Auth\LoginController;
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
    Route::get('find/{token}', [App\Http\Controllers\Admin\PasswordResetController::class, 'find']);
    Route::post('create', [App\Http\Controllers\Admin\PasswordResetController::class, 'create']);
    Route::post('reset', [App\Http\Controllers\Admin\PasswordResetController::class, 'reset']);
});

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return 'logged in';
});
