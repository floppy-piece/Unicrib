<?php

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
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
use App\Http\Controllers\ContactController;
use App\Http\Controllers\TransportController;
use App\Http\Controllers\Admin\TransportAdminController;
use App\Http\Controllers\StorageHousingController;
use App\Http\Controllers\Admin\StorageAdminController;
use App\Http\Controllers\RefundController;
use App\Http\Controllers\Landlord\LandlordController;

Route::middleware('auth'/*, 'verified'*/)->group(function () {
    // Properties
    Route::post('/properties/{property}/pay-and-book', [BookingController::class, 'payAndBook'])->name('properties.pay-and-book');
    
    // Paystack
    Route::get('/paystack/verify', [BookingController::class, 'paystackVerify'])->name('paystack.verify');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    
    // Landlord
    Route::post('/landlord/subaccount', [LandlordController::class, 'createSubaccount'])->name('landlord.subaccount');
});





Route::middleware(['auth'/*, 'verified'*/])->group(function () {
    Route::get('/pay', [PaymentController::class, 'showForm'])->name('pay.form');
    Route::post('/pay', [PaymentController::class, 'redirectToGateway'])->name('pay');
    Route::get('/payment/callback', [PaymentController::class, 'handleGatewayCallback'])
        //->middleware('paystack.ip')
        ->name('payment.callback');

    Route::post('/payment/{payment}/refund', [RefundController::class, 'refund'])
        ->name('payment.refund');
});



/*
to do: 
        add feature admin only can create landlord accounts
*/





Route::middleware(['auth', 'category:admin', /*'verified'*/])
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

        // routes/web.php
        Route::get('/transports', [TransportAdminController::class, 'index'])->name('transports.index');

        Route::patch('/transports/{transport}', [TransportAdminController::class, 'update'])->name('transports.update');

        //storehousing for admin
        Route::get('/storage', [StorageAdminController::class, 'index'])->name('storage.index');
        Route::patch('/storage/{storageBooking}', [StorageAdminController::class, 'update'])->name('storage.update');

    });


//Route::view('/', 'welcome');
Route::get('/', [PropertyController::class, 'welcome'])->name('welcome');
Route::view('/about', 'about')->name('about');
Route::view('/services', 'services')->name('services');
//contacts
Route::get('/contact', [ContactController::class, 'show'])
    ->name('contact.show');

Route::post('/contact', [ContactController::class, 'submit'])
    ->name('contact.submit');    
//profile
Route::view('profile', 'profile')
    ->middleware(['auth', 'verified'])
    ->name('profile');

require __DIR__.'/auth.php';

//dashboard redirect
Route::middleware(['auth'/*,'verified'*/])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/student/dashboard', [DashboardController::class, 'studentDashboard'])->name('student.dashboard');
    Route::get('/landlord/dashboard', [DashboardController::class, 'landlordDashboard'])->name('landlord.dashboard');
    //Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])->name('admin.dashboard');
    Route::get('/api/properties/{area}', [PropertyController::class, 'getPropertiesByArea']);
    Route::get('/student/dashboard', [PropertyController::class, 'studentIndex'])->name('student.dashboard');
    // Student-facing property details
    //Route::get('/properties/{property}', [PropertyController::class, 'show'])->name('properties.show');

    //store housing for students
    Route::get('/store-housing', [StorageHousingController::class, 'index'])
        ->name('store-housing');

    Route::post('/storage/book', [StorageHousingController::class, 'store'])
        ->name('storage.store');
});
        

// Student: pay KES 50 to view details for 6 hours
Route::middleware(['auth','category:student'/*,'verified'*/])->group(function () {
    Route::get('/payments/student', [PaymentController::class, 'showStudentForm'])->name('payments.student.form');
    Route::post('/payments/student', [PaymentController::class, 'handleStudentPayment'])->name('payments.student.pay');
    //show notifications
    Route::get('/student/notifications', [MessageController::class, 'notify'])->name('student.notifications');
    //store booking
    Route::post('/properties/{property}/book', [BookingController::class, 'payAndBook'])->name('student.bookings.store');
    //cancel booking
    Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel'])->name('student.bookings.cancel');
    //show students property i.e accessible to only students that paid
    Route::middleware('studentAccess')->group(function (){
        Route::get('/properties/{property}', [PropertyController::class, 'show'])->name('properties.show');
    });
       
});

// Landlord: pay KES 500 per semester to verify account and property
Route::middleware(['auth','category:landlord'/*,'verified'*/])->group(function () {
    //view requests from students
    Route::get('/messages', [MessageController::class, 'index'])->name('messages');
    Route::get('/payments/landlord', [PaymentController::class, 'showLandlordForm'])->name('payments.landlord.form');
    Route::post('/payments/landlord', [PaymentController::class, 'handleLandlordPayment'])->name('payments.landlord.pay');
    Route::post('/mpesa/callback', [PaymentController::class, 'mpesaCallback'])
    ->name('mpesa.callback');
    Route::get('/landlord/payment/verified', [PaymentController::class, 'afterVerificationRedirect'])
    ->name('payments.landlord.verified');
});

Route::middleware(['auth', 'category:landlord'/*, 'verified'*/])
->prefix('landlord')
->name('landlord.')
->group(function () {
    Route::get('/landlord/dashboard', [DashboardController::class, 'index2'])
        ->name('ashboard');
    Route::prefix('properties')->name('properties.')->group(function () {
        Route::get('/', [PropertyController::class, 'index'])->name('myProperties');
        Route::get('/create', [PropertyController::class, 'create'])->name('add');
        Route::post('/', [PropertyController::class, 'store'])->name('store');
        Route::get('/{property}', [PropertyController::class, 'showProperty'])->name('show'); 

        // Only verified properties can be edited/deleted/published:
        Route::middleware('landlordVerified')->group(function () {
            Route::get('/{property}/edit', [PropertyController::class, 'edit'])->name('edit');
            Route::put('/{property}', [PropertyController::class, 'update'])->name('update');
            Route::delete('/{property}', [PropertyController::class, 'destroy'])->name('destroy');
        });
    });               

    Route::prefix('bookings')->name('bookings.')->group(function () {
        Route::post('{booking}/approve', [BookingController::class, 'approve'])->name('approve');
        Route::post('{booking}/reject', [BookingController::class, 'reject'])->name('reject');
        Route::get('/{booking}', [BookingController::class, 'showBooking'])->name('show');
        Route::post('/{booking}/cancel', [BookingController::class, 'cancelStudent'])->name('cancelStudent');
        Route::post('/{booking}/pay', [BookingController::class, 'pay'])->name('bookings.pay');
        Route::get('/{booking}/paystack/callback', [BookingController::class, 'paystackCallback'])->name('bookings.paystack.callback');
        //Route::post('/{booking}/reject', [BookingController::class, 'reject'])->name('bookings.reject');
    });
    
});

//transport routes
Route::middleware(['auth','verified'])->group(function () {
    Route::get('/bookings/{booking}/transport/request', [TransportController::class, 'create'])->name('transport.request.create');

    Route::post('/bookings/{booking}/transport/request', [TransportController::class, 'store'])->name('transport.request.store');

    Route::post('/paystack/webhook', [PaystackWebhookController::class, 'handle'])->name('paystack.webhook');
    
});

    

