<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PermissionsController;
use App\Http\Controllers\SensorController;
use App\Http\Controllers\DeviceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');


Route::middleware(['role:admin'])->group(function () {
    Route::prefix('users')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('user.index')->middleware('permission:user-list');;
        Route::match(['get', 'put'], '/create', [UserController::class, 'create'])->name('user.create')->middleware('permission:user-create');
        Route::match(['get', 'patch'], '/{id}/edit', [UserController::class, 'edit'])->name('user.edit')->middleware('permission:user-edit');
        Route::delete('/{id}/delete', [UserController::class, 'destroy'])->name('user.delete')->middleware('permission:user-delete');
        Route::match(['get'], '/{id}/view', [UserController::class, 'view'])->name('user.view');
    });
    Route::prefix('device')->group(function () {
        Route::get('/', [DeviceController::class, 'index'])->name('device.index')->middleware('permission:user-list');;
        Route::match(['get', 'put'], '/create', [DeviceController::class, 'create'])->name('device.create')->middleware('permission:user-list');
        Route::match(['get', 'patch'], '/{id}/edit', [DeviceController::class, 'edit'])->name('device.edit')->middleware('permission:user-list');
        Route::delete('/{id}/delete', [DeviceController::class, 'destroy'])->name('device.delete')->middleware('permission:user-list');
        Route::match(['get'], '/{id}/view', [DeviceController::class, 'view'])->name('device.view');
    });
    Route::prefix('permissions')->group(function () {
        Route::get('/', [PermissionsController::class, 'index'])->name('permission.index')->middleware('permission:permission-list');
        Route::match(['get', 'put'], '/create', [PermissionsController::class, 'create'])->name('permission.create');
        Route::match(['get', 'patch'], '/{id}/edit', [PermissionsController::class, 'edit'])->name('permission.edit');
        Route::delete('/{id}/delete', [PermissionsController::class, 'destroy'])->name('permission.delete');
        Route::match(['get'], '/{id}/view', [PermissionsController::class, 'view'])->name('permission.view');
    });

    Route::prefix('sensors')->group(function () {
        Route::get('/', [SensorController::class, 'index'])->name('monitoring.index')->middleware('permission:monitoring-list');
    });
});



Route::post('get-user-permissions', [RoleController::class, 'getUserPermissions'])->name('get.user.permissions');
Route::post('assign-permissions-ajax', [RoleController::class, 'assignPermissionsAjax'])->name('permissions.ajax.assign');
Route::get('/roles', [RoleController::class, 'index'])->name('roles.index')->middleware('permission:permission user-list');;
Route::get('/roles/data', [RoleController::class, 'data'])->name('roles.data');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
Route::post('generate-json/select2', [MainController::class, 'select2Response'])->name('generate.json.select2');

require __DIR__ . '/auth.php';
