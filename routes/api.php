<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Cms\AuthController;
use App\Http\Controllers\Cms\RoleController;
use App\Http\Controllers\Cms\UserController;

Route::prefix('/auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::middleware('auth:sanctum')->get('/check', [AuthController::class, 'check'])->name('auth.check');
});

Route::middleware('auth:sanctum')->prefix('/usermanagement')->group(function () {
    Route::prefix('/role')->group(function () {
        Route::post('/', [RoleController::class, 'addRole'])->name('usermanagement.role.add');
        Route::get('/', [RoleController::class, 'getRole'])->name('usermanagement.role');
        Route::get('/{id}/detail', [RoleController::class, 'roleDetail'])->name('usermanagement.role.detail');
        Route::put('/{id}/update', [RoleController::class, 'updateRole'])->name('usermanagement.role.update');
        Route::delete('/{id}/delete', [RoleController::class, 'deleteRole'])->name('usermanagement.role.delete');
    });

    Route::prefix('/user')->group(function () {
        Route::post('/', [UserController::class, 'addUser'])->name('usermanagement.user.add');
        Route::get('/', [UserController::class, 'getUser'])->name('usermanagement.user');
    });

    Route::prefix('/permission')->group(function () {
        Route::get('/', [RoleController::class, 'getPermission'])->name('usermanagement.permission');
    });
});


