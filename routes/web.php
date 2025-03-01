<?php

use App\Http\Controllers\Konfigurasi\AksesRoleController;
use App\Http\Controllers\Konfigurasi\MenuController;
use App\Http\Controllers\Konfigurasi\PermissionController;
use App\Http\Controllers\Konfigurasi\RoleController;
use App\Http\Controllers\ProfileController;
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
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
