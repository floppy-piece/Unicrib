<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Landlord\PropertyController;
Route::view('/', 'welcome');


Route::view('messages', 'messages')
    ->middleware(['auth', 'verified'])
    ->name('messages');

Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
Route::middleware(['auth','verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/student/dashboard', [DashboardController::class, 'studentDashboard'])->name('student.dashboard');
    Route::get('/landlord/dashboard', [DashboardController::class, 'landlordDashboard'])->name('landlord.dashboard');
    Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::get('/api/properties/{area}', [PropertyController::class, 'getPropertiesByArea']);
    Route::get('/student/dashboard', [PropertyController::class, 'studentIndex'])->name('student.dashboard');
    // Student-facing property details
    Route::get('/properties/{property}', [PropertyController::class, 'show'])->name('properties.show');
});
Route::middleware(['auth', 'category:landlord'])
    ->prefix('landlord')
    ->name('landlord.')
    ->group(function () {

        Route::prefix('properties')->name('properties.')->group(function () {
            Route::get('/', [PropertyController::class, 'index'])->name('myProperties');
            Route::get('/create', [PropertyController::class, 'create'])->name('add');
            Route::post('/', [PropertyController::class, 'store'])->name('store');
            Route::get('/{property}/edit', [PropertyController::class, 'edit'])->name('edit');
            Route::put('/{property}', [PropertyController::class, 'update'])->name('update');
            Route::delete('/{property}', [PropertyController::class, 'destroy'])->name('destroy');
        });
    });

