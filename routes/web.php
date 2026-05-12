<?php

use Illuminate\Support\Facades\Route;

Route::inertia('/', 'Welcome', [
    'canRegister' => false,
])->name('home');

Route::inertia('/crm', 'Crm', [
    'canRegister' => false,
])->name('crm');
Route::inertia('/erp', 'Erp', [
    'canRegister' => false,
])->name('erp');
Route::inertia('hr', 'Hr', [
    'canRegister' => false,
])->name('hr');
Route::inertia('/lms', 'Lms', [
    'canRegister' => false,
])->name('lms');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
