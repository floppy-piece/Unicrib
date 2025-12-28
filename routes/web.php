<?php

use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Landlord\PropertyController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\PropertyController as AdminPropertyController;
use App\Http\Controllers\Admin\BookingController as AdminBookingController;

Route::middleware(['auth', 'category:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // Users
        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::post('/users/{user}/status', [AdminUserController::class, 'updateStatus'])->name('users.updateStatus');

        // Properties
        Route::get('/properties', [AdminPropertyController::class, 'index'])->name('properties.index');
        Route::post('/properties/{property}/status', [AdminPropertyController::class, 'updateStatus'])->name('properties.updateStatus');

        // Bookings
        Route::get('/bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
        Route::post('/bookings/{booking}/status', [AdminBookingController::class, 'updateStatus'])->name('bookings.updateStatus');

        Route::get('/users', [AdminUserController::class, 'index'])->name('users.index');
        Route::post('/users/{user}/status', [AdminUserController::class, 'updateStatus'])->name('users.updateStatus');
        Route::delete('/users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');
    });


Route::view('/', 'welcome');



Route::view('profile', 'profile')
    ->middleware(['auth'])
    ->name('profile');

require __DIR__.'/auth.php';
Route::middleware(['auth','verified'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/student/dashboard', [DashboardController::class, 'studentDashboard'])->name('student.dashboard');
    Route::get('/landlord/dashboard', [DashboardController::class, 'landlordDashboard'])->name('landlord.dashboard');
    //Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::get('/api/properties/{area}', [PropertyController::class, 'getPropertiesByArea']);
    Route::get('/student/dashboard', [PropertyController::class, 'studentIndex'])->name('student.dashboard');
    // Student-facing property details
    //Route::get('/properties/{property}', [PropertyController::class, 'show'])->name('properties.show');
});



// Student makes a booking on a property
Route::middleware(['auth', 'category:student'])->group(function () {
Route::post('/properties/{property}/book', [BookingController::class, 'store'])
    ->name('student.bookings.store');
Route::post('/properties/{property}/book', [BookingController::class, 'store'])
    ->name('student.bookings.store');

Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])
    ->name('student.bookings.cancel');
});

// Landlord handles bookings (approve / reject)
Route::middleware(['auth', 'category:landlord'])
->prefix('landlord')
->name('landlord.')
->group(function () {
    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::post('{booking}/approve', [BookingController::class, 'approve'])->name('approve');
        Route::post('{booking}/reject', [BookingController::class, 'reject'])->name('reject');
    });
});
    
       

// replace your old Route::view('messages', ...) with:
Route::middleware(['auth', 'category:landlord'])
    ->get('/messages', [MessageController::class, 'index'])
    ->name('messages');
        
          

// Student: pay KES 50 to view details for 6 hours
Route::middleware(['auth','category:student'])->group(function () {
    Route::get('/payments/student', [PaymentController::class, 'showStudentForm'])->name('payments.student.form');
    Route::post('/payments/student', [PaymentController::class, 'handleStudentPayment'])->name('payments.student.pay');
});

// Landlord: pay KES 500 per semester to verify account and property
Route::middleware(['auth','category:landlord'])->group(function () {
    Route::get('/payments/landlord', [PaymentController::class, 'showLandlordForm'])->name('payments.landlord.form');
    Route::post('/payments/landlord', [PaymentController::class, 'handleLandlordPayment'])->name('payments.landlord.pay');
});
//show students property i.e accessible to only students that paid
Route::get('/properties/{property}', [PropertyController::class, 'show'])
    ->middleware(['auth', 'category:student', 'student.access'])
    ->name('properties.show');

    Route::middleware(['auth', 'category:landlord'])
    ->prefix('landlord')
    ->name('landlord.')
    ->group(function () {
        Route::prefix('properties')->name('properties.')->group(function () {
            Route::get('/', [PropertyController::class, 'index'])->name('myProperties');
            Route::get('/create', [PropertyController::class, 'create'])->name('add');
            Route::post('/', [PropertyController::class, 'store'])->name('store');

            // Only verified properties can be edited/deleted/published:
            Route::middleware('landlord.verified')->group(function () {
                Route::get('/{property}/edit', [PropertyController::class, 'edit'])->name('edit');
                Route::put('/{property}', [PropertyController::class, 'update'])->name('update');
                Route::delete('/{property}', [PropertyController::class, 'destroy'])->name('destroy');
            });
        });
    });

