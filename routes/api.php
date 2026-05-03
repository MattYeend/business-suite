<?php

use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->group(function () {
    // Authenticated user endpoint
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    // User management routes
    Route::prefix('users')->name('users.')->group(function () {
        // Standard CRUD
        Route::get(
            '/',
            [UserController::class,'index']
        )->name('index');
        Route::post(
            '/',
            [UserController::class,'store']
        )->name('store');
        Route::get(
            '/{user}',
            [UserController::class,'show']
        )->name('show');
        Route::put(
            '/{user}',
            [UserController::class,'update']
        )->name('update');
        Route::patch(
            '/{user}',
            [UserController::class,'update']
        )->name('patch');
        Route::delete(
            '/{user}',
            [UserController::class,'destroy']
        )->name('destroy');

        // Restoration
        Route::post(
            '/{id}/restore',
            [UserController::class, 'restore']
        )->name('restore');

        // Force delete
        Route::delete(
            '/{id}/force',
            [UserController::class, 'forceDelete']
        )->name('force-delete');

        // Bulk operations
        Route::post(
            '/bulk/delete',
            [UserController::class,'bulkDelete']
        )->name('bulk.delete');
        Route::post(
            '/bulk/restore',
            [UserController::class,'bulkRestore']
        )->name('bulk.restore');
    });
});
