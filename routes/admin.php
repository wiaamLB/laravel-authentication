<?php

use App\Http\Controllers\Admin\Auth\LoginController;
use App\Http\Controllers\Admin\Auth\PasswordResetController;
use App\Http\Controllers\Admin\PagesController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\UsersAdminController;
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
    Route::group(['middleware' => 'role:moderator|admin'], function () {
        Route::get('users', [UserController::class, 'show'])->name('admin.users.show');
        Route::get('pages/{id}', [PagesController::class, 'show'])->name('admin.pages.show');

    });

    Route::group(['middleware' => 'role:editor|moderator|admin'], function () {
        Route::get('pages', [PagesController::class, 'index'])->name('admin.pages.index');
        Route::get('settings', [SettingsController::class, 'index'])->name('admin.settings.index');
    });
    Route::group(['middleware' => 'role:editor|admin|super-admin'], function () {
        Route::post('pages', [PagesController::class, 'store'])->name('admin.pages.store');
        Route::post('settings', [SettingsController::class, 'store'])->name('admin.settings.store');
    });
    Route::group(['middleware' => 'role:editor|admin'], function () {
        Route::delete('pages/{id}/delete', [PagesController::class, 'delete'])->name('admin.pages.delete');
    });


    Route::group(['middleware' => 'role:super-admin', 'prefix' => 'management'], function () {
        Route::get('users', [UsersAdminController::class, 'index'])->name('admin.users.index');
        Route::post('users', [UsersAdminController::class, 'store'])->name('admin.users.create');
        Route::post('users/{user_id}/update', [UsersAdminController::class, 'update'])->name('admin.users.update');
        Route::delete('users/{user_id}/delete', [UsersAdminController::class, 'delete'])->name('admin.users.delete');
    });
});
