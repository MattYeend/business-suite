<?php

use App\Http\Controllers\CompanyIndustryController;
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
        Route::post(
            '/bulk/delete',
            [UserController::class, 'bulkDelete']
        )->name('bulk.delete');
        Route::post(
            '/bulk/restore',
            [UserController::class, 'bulkRestore']
        )->name('bulk.restore');

        Route::get(
            '/',
            [UserController::class, 'index']
        )->name('index');
        Route::post(
            '/',
            [UserController::class, 'store']
        )->name('store');

        Route::get(
            '/{user}',
            [UserController::class, 'show']
        )->name('show');
        Route::put(
            '/{user}',
            [UserController::class, 'update']
        )->name('update');
        Route::patch(
            '/{user}',
            [UserController::class, 'update']
        )->name('patch');
        Route::delete(
            '/{user}',
            [UserController::class, 'destroy']
        )->name('destroy');

        Route::post(
            '/{id}/restore',
            [UserController::class, 'restore']
        )->name('restore');
        Route::delete(
            '/{id}/force',
            [UserController::class, 'forceDelete']
        )->name('force-delete');
    });

    // Company Industry management routes
    Route::prefix('company-industries')->name(
        'company-industries.'
    )->group(function () {
        Route::post(
            '/bulk/delete',
            [CompanyIndustryController::class, 'bulkDelete']
        )->name('bulk.delete');

        Route::post(
            '/bulk/restore',
            [CompanyIndustryController::class, 'bulkRestore']
        )->name('bulk.restore');

        // Standard CRUD
        Route::get(
            '/',
            [CompanyIndustryController::class, 'index']
        )->name('index');
        Route::post(
            '/',
            [CompanyIndustryController::class, 'store']
        )->name('store');
        Route::get(
            '/{company_industry}',
            [CompanyIndustryController::class, 'show']
        )->name('show');
        Route::put(
            '/{company_industry}',
            [CompanyIndustryController::class, 'update']
        )->name('update');
        Route::patch(
            '/{company_industry}',
            [CompanyIndustryController::class, 'update']
        )->name('patch');
        Route::delete(
            '/{company_industry}',
            [CompanyIndustryController::class, 'destroy']
        )->name('destroy');

        Route::post(
            '/{id}/restore',
            [CompanyIndustryController::class, 'restore']
        )->name('restore');

        Route::delete(
            '/{id}/force',
            [CompanyIndustryController::class, 'forceDelete']
        )->name('force-delete');
    });
});
