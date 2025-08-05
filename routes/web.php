<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\CourtController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DistrictController;
use App\Http\Controllers\PermissionController;

Route::get('/', function () {


    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    return view('auth.login');
});

Auth::routes();

Route::group(['middleware' => ['auth','menu.component']], function () {
    Route::get('/logout', [LoginController::class, 'logout']);
    Route::resource('groups', GroupController::class);
    Route::resource('permissions', PermissionController::class);
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('roles', RoleController::class);
    Route::get('/roles-export', [RoleController::class, 'RoleExport'])->name('roles.export');
    Route::post('/roles-export-save', [RoleController::class, 'RoleExportSave'])->name('roles.export-save');
    Route::get('/roles/manage-export-columns/{role}', [RoleController::class, 'manageExportColumns'])->name('roles.manage-export-columns');
    Route::post('get_roles', [UserController::class, 'get_roles'])->name('get_roles');
    Route::post('get_permission_with_group_id', [RoleController::class, 'get_permission_with_group_id'])->name('get_permission_with_group_id');

    Route::get('users/export', [UserController::class, 'export'])->name('users.export');
    Route::resource('users', UserController::class);
    Route::post('/users/status-update', [UserController::class, 'UserStatusUpdate'])->name('users.status-update');
    Route::post('get_districts', [UserController::class, 'get_districts'])->name('get_districts');

    Route::resource('district', DistrictController::class);
    Route::get('talukas/{district_id}', [DistrictController::class, 'getTalukasByDistrict']);
    Route::get('district/delete/{id}', [DistrictController::class, 'delete'])->name('district.delete');

    //court routes
    Route::resource('court', CourtController::class);

    //Profile routes
    Route::group(['prefix' => 'Profile'], function () {
        Route::resource('/profile', ProfileController::class);
        Route::get('/ChangePassword', [ProfileController::class, 'ChangePassword'])->name('Profile.ChangePassword');
        Route::get('/update_user_password/{id}', [ProfileController::class, 'update_user_password'])->name('Profile.update_user_password');
        Route::post('/ChangePassword', [ProfileController::class, 'UpdatePassword'])->name('UpdatePassword');
    });
});
