<?php

use App\Http\Controllers\Konfigurasi\AksesRoleController;
use App\Http\Controllers\Konfigurasi\MenuController;
use App\Http\Controllers\Konfigurasi\PermissionController;
use App\Http\Controllers\Konfigurasi\RoleController;
use App\Http\Controllers\Konfigurasi\UserController;
use App\Http\Controllers\Setting\ProfileController;
use App\Http\Middleware\CekUserLogin;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware([CekUserLogin::class])->group(function () {
    Route::group(['prefix' => 'konfigurasi', 'as' => 'konfigurasi.'], function () {

        // menu
        Route::put('menu/sort', [MenuController::class, 'sort'])->name('menu.sort');
        Route::post('menu/data', [MenuController::class, 'getData'])->name('menu.data');
        Route::resource('menu', MenuController::class);

        // roles
        Route::post('roles/data', [RoleController::class, 'getData'])->name('roles.data');
        Route::resource('roles', RoleController::class);

        // permission
        Route::post('permissions/data', [PermissionController::class, 'getData'])->name('permissions.data');
        Route::resource('permissions', PermissionController::class);

        // akses-role
        Route::post('akses-role/data', [AksesRoleController::class, 'getData'])->name('akses-role.data');
        Route::get('akses-role/{role}/role', [AksesRoleController::class, 'getPermissionByRole']);
        Route::resource('akses-role', AksesRoleController::class)->except('create', 'store', 'delete')->parameters(['akses-role' => 'role']);

        // users
        Route::get('user/{id}', [UserController::class, 'aktivasiUser'])->name('aktivasi.user');
        Route::post('users/data', [UserController::class, 'getData'])->name('users.data');
        Route::resource('users', UserController::class);
    });
});

Route::group(['prefix' => 'setting', 'as' => 'setting.'], function () {

    // profile
    Route::post('profil/ubah-password/{id}', [ProfileController::class, 'ubahPassword'])->name('update-password.user');
    Route::post('profil/update/{id}', [ProfileController::class, 'updateData'])->name('update-data.user');
    Route::resource('profil', ProfileController::class);
});

require __DIR__ . '/auth.php';
