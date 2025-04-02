<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Cms\AuthController;
use App\Http\Controllers\Cms\RoleController;

Route::prefix('/auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
    Route::middleware('auth:sanctum')->get('/check', [AuthController::class, 'check'])->name('auth.check');
});


Route::middleware('auth:sanctum')->prefix('/role-management')->group(function () {
    Route::prefix('/role')->group(function () {
        Route::post('/', [RoleController::class, 'addRole'])->name('role-management.role.add');
        Route::get('/', [RoleController::class, 'role'])->name('role-management.role');
        Route::get('/{id}/detail', [RoleController::class, 'roleDetail'])->name('role-management.role.detail');
        Route::put('/{id}/update', [RoleController::class, 'updateRole'])->name('role-management.role.update');
        Route::delete('/{id}/delete', [RoleController::class, 'deleteRole'])->name('role-management.role.delete');
    });

    Route::prefix('/permission')->group(function () {
        Route::get('/', [RoleController::class, 'permission'])->name('role-management.permission');
    });
});

