<?php

use App\Http\Controllers\BillOfMaterialController;
use App\Http\Controllers\CompanyAddressController;
use App\Http\Controllers\CompanyContactController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CompanyIndustryController;
use App\Http\Controllers\CompanyPhoneController;
use App\Http\Controllers\PartController;
use App\Http\Controllers\PipelineController;
use App\Http\Controllers\PipelineStageController;
use App\Http\Controllers\ProductController;
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

    // User Management routes
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

    // Company Industry Management routes
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

    // Company Management routes
    Route::prefix('companies')->name(
        'companies.'
    )->group(function () {
        Route::post(
            '/bulk/delete',
            [CompanyController::class, 'bulkDelete']
        )->name('bulk.delete');

        Route::post(
            '/bulk/restore',
            [CompanyController::class, 'bulkRestore']
        )->name('bulk.restore');

        Route::get(
            '/',
            [CompanyController::class, 'index']
        )->name('index');
        Route::post(
            '/',
            [CompanyController::class, 'store']
        )->name('store');
        Route::get(
            '/{company}',
            [CompanyController::class, 'show']
        )->name('show');
        Route::put(
            '/{company}',
            [CompanyController::class, 'update']
        )->name('update');
        Route::patch(
            '/{company}',
            [CompanyController::class, 'update']
        )->name('patch');
        Route::delete(
            '/{company}',
            [CompanyController::class, 'destroy']
        )->name('destroy');

        Route::post(
            '/{id}/restore',
            [CompanyController::class, 'restore']
        )->name('restore');

        Route::delete(
            '/{id}/force',
            [CompanyController::class, 'forceDelete']
        )->name('force-delete');
    });

    // Company Contact Management routes
    Route::prefix('company-contacts')->name(
        'company-contacts.'
    )->group(function () {
        Route::post(
            '/bulk/delete',
            [CompanyContactController::class, 'bulkDelete']
        )->name('bulk.delete');

        Route::post(
            '/bulk/restore',
            [CompanyContactController::class, 'bulkRestore']
        )->name('bulk.restore');

        Route::get(
            '/',
            [CompanyContactController::class, 'index']
        )->name('index');
        Route::post(
            '/',
            [CompanyContactController::class, 'store']
        )->name('store');
        Route::get(
            '/{company_contact}',
            [CompanyContactController::class, 'show']
        )->name('show');
        Route::put(
            '/{company_contact}',
            [CompanyContactController::class, 'update']
        )->name('update');
        Route::patch(
            '/{company_contact}',
            [CompanyContactController::class, 'update']
        )->name('patch');
        Route::delete(
            '/{company_contact}',
            [CompanyContactController::class, 'destroy']
        )->name('destroy');

        Route::post(
            '/{id}/restore',
            [CompanyContactController::class, 'restore']
        )->name('restore');

        Route::delete(
            '/{id}/force',
            [CompanyContactController::class, 'forceDelete']
        )->name('force-delete');
    });

    // Company Phone Management routes
    Route::prefix('company-phones')->name(
        'company-phones.'
    )->group(function () {
        Route::post(
            '/bulk/delete',
            [CompanyPhoneController::class, 'bulkDelete']
        )->name('bulk.delete');

        Route::post(
            '/bulk/restore',
            [CompanyPhoneController::class, 'bulkRestore']
        )->name('bulk.restore');

        Route::get(
            '/',
            [CompanyPhoneController::class, 'index']
        )->name('index');
        Route::post(
            '/',
            [CompanyPhoneController::class, 'store']
        )->name('store');
        Route::get(
            '/{company_phone}',
            [CompanyPhoneController::class, 'show']
        )->name('show');
        Route::put(
            '/{company_phone}',
            [CompanyPhoneController::class, 'update']
        )->name('update');
        Route::patch(
            '/{company_phone}',
            [CompanyPhoneController::class, 'update']
        )->name('patch');
        Route::delete(
            '/{company_phone}',
            [CompanyPhoneController::class, 'destroy']
        )->name('destroy');

        Route::post(
            '/{id}/restore',
            [CompanyPhoneController::class, 'restore']
        )->name('restore');

        Route::delete(
            '/{id}/force',
            [CompanyPhoneController::class, 'forceDelete']
        )->name('force-delete');
    });

    // Company Address Management routes
    Route::prefix('company-addresses')->name(
        'company-addresses.'
    )->group(function () {
        Route::post(
            '/bulk/delete',
            [CompanyAddressController::class, 'bulkDelete']
        )->name('bulk.delete');

        Route::post(
            '/bulk/restore',
            [CompanyAddressController::class, 'bulkRestore']
        )->name('bulk.restore');

        Route::get(
            '/',
            [CompanyAddressController::class, 'index']
        )->name('index');
        Route::post(
            '/',
            [CompanyAddressController::class, 'store']
        )->name('store');
        Route::get(
            '/{company_address}',
            [CompanyAddressController::class, 'show']
        )->name('show');
        Route::put(
            '/{company_address}',
            [CompanyAddressController::class, 'update']
        )->name('update');
        Route::patch(
            '/{company_address}',
            [CompanyAddressController::class, 'update']
        )->name('patch');
        Route::delete(
            '/{company_address}',
            [CompanyAddressController::class, 'destroy']
        )->name('destroy');

        Route::post(
            '/{id}/restore',
            [CompanyAddressController::class, 'restore']
        )->name('restore');

        Route::delete(
            '/{id}/force',
            [CompanyAddressController::class, 'forceDelete']
        )->name('force-delete');
    });

    // Pipeline Management routes
    Route::prefix('pipelines')->name(
        'pipelines.'
    )->group(function () {
        Route::post(
            '/bulk/delete',
            [PipelineController::class, 'bulkDelete']
        )->name('bulk.delete');

        Route::post(
            '/bulk/restore',
            [PipelineController::class, 'bulkRestore']
        )->name('bulk.restore');

        Route::get(
            '/',
            [PipelineController::class, 'index']
        )->name('index');
        Route::post(
            '/',
            [PipelineController::class, 'store']
        )->name('store');
        Route::get(
            '/{pipeline}',
            [PipelineController::class, 'show']
        )->name('show');
        Route::put(
            '/{pipeline}',
            [PipelineController::class, 'update']
        )->name('update');
        Route::patch(
            '/{pipeline}',
            [PipelineController::class, 'update']
        )->name('patch');
        Route::delete(
            '/{pipeline}',
            [PipelineController::class, 'destroy']
        )->name('destroy');

        Route::post(
            '/{id}/restore',
            [PipelineController::class, 'restore']
        )->name('restore');

        Route::delete(
            '/{id}/force',
            [PipelineController::class, 'forceDelete']
        )->name('force-delete');
    });

    // Pipeline Stage Management routes
    Route::prefix('pipeline-stages')->name(
        'pipeline-stages.'
    )->group(function () {
        Route::post(
            '/bulk/delete',
            [PipelineStageController::class, 'bulkDelete']
        )->name('bulk.delete');

        Route::post(
            '/bulk/restore',
            [PipelineStageController::class, 'bulkRestore']
        )->name('bulk.restore');

        Route::get(
            '/',
            [PipelineStageController::class, 'index']
        )->name('index');
        Route::post(
            '/',
            [PipelineStageController::class, 'store']
        )->name('store');
        Route::get(
            '/{pipeline_stage}',
            [PipelineStageController::class, 'show']
        )->name('show');
        Route::put(
            '/{pipeline_stage}',
            [PipelineStageController::class, 'update']
        )->name('update');
        Route::patch(
            '/{pipeline_stage}',
            [PipelineStageController::class, 'update']
        )->name('patch');
        Route::delete(
            '/{pipeline_stage}',
            [PipelineStageController::class, 'destroy']
        )->name('destroy');

        Route::post(
            '/{id}/restore',
            [PipelineStageController::class, 'restore']
        )->name('restore');

        Route::delete(
            '/{id}/force',
            [PipelineStageController::class, 'forceDelete']
        )->name('force-delete');
    });

    // Part Management routes
    Route::prefix('parts')->name(
        'parts.'
    )->group(function () {
        Route::post(
            '/bulk/delete',
            [PartController::class, 'bulkDelete']
        )->name('bulk.delete');

        Route::post(
            '/bulk/restore',
            [PartController::class, 'bulkRestore']
        )->name('bulk.restore');

        Route::get(
            '/',
            [PartController::class, 'index']
        )->name('index');
        Route::post(
            '/',
            [PartController::class, 'store']
        )->name('store');
        Route::get(
            '/{part}',
            [PartController::class, 'show']
        )->name('show');
        Route::put(
            '/{part}',
            [PartController::class, 'update']
        )->name('update');
        Route::patch(
            '/{part}',
            [PartController::class, 'update']
        )->name('patch');
        Route::delete(
            '/{part}',
            [PartController::class, 'destroy']
        )->name('destroy');

        Route::post(
            '/{id}/restore',
            [PartController::class, 'restore']
        )->name('restore');

        Route::delete(
            '/{id}/force',
            [PartController::class, 'forceDelete']
        )->name('force-delete');
    });

    // Product Management routes
    Route::prefix('products')->name(
        'products.'
    )->group(function () {
        Route::post(
            '/bulk/delete',
            [ProductController::class, 'bulkDelete']
        )->name('bulk.delete');

        Route::post(
            '/bulk/restore',
            [ProductController::class, 'bulkRestore']
        )->name('bulk.restore');

        Route::get(
            '/',
            [ProductController::class, 'index']
        )->name('index');
        Route::post(
            '/',
            [ProductController::class, 'store']
        )->name('store');
        Route::get(
            '/{product}',
            [ProductController::class, 'show']
        )->name('show');
        Route::put(
            '/{product}',
            [ProductController::class, 'update']
        )->name('update');
        Route::patch(
            '/{product}',
            [ProductController::class, 'update']
        )->name('patch');
        Route::delete(
            '/{product}',
            [ProductController::class, 'destroy']
        )->name('destroy');

        Route::post(
            '/{id}/restore',
            [ProductController::class, 'restore']
        )->name('restore');

        Route::delete(
            '/{id}/force',
            [ProductController::class, 'forceDelete']
        )->name('force-delete');
    });

    // BIll Of Material Management routes
    Route::prefix('bill-of-materials')->name(
        'bill-of-materials.'
    )->group(function () {
        Route::post(
            '/bulk/delete',
            [BillOfMaterialController::class, 'bulkDelete']
        )->name('bulk.delete');

        Route::post(
            '/bulk/restore',
            [BillOfMaterialController::class, 'bulkRestore']
        )->name('bulk.restore');

        Route::get(
            '/',
            [BillOfMaterialController::class, 'index']
        )->name('index');
        Route::post(
            '/',
            [BillOfMaterialController::class, 'store']
        )->name('store');
        Route::get(
            '/{billOfMaterial}',
            [BillOfMaterialController::class, 'show']
        )->name('show');
        Route::put(
            '/{billOfMaterial}',
            [BillOfMaterialController::class, 'update']
        )->name('update');
        Route::patch(
            '/{billOfMaterial}',
            [BillOfMaterialController::class, 'update']
        )->name('patch');
        Route::delete(
            '/{billOfMaterial}',
            [BillOfMaterialController::class, 'destroy']
        )->name('destroy');

        Route::post(
            '/{id}/restore',
            [BillOfMaterialController::class, 'restore']
        )->name('restore');

        Route::delete(
            '/{id}/force',
            [BillOfMaterialController::class, 'forceDelete']
        )->name('force-delete');
    });
});
