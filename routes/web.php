<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MonitoringController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\KnowledgeController;
use App\Http\Controllers\WeightController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\CartController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/knowledge', [KnowledgeController::class, 'index'])->name('knowledge');
Route::get('/knowledge/{knowledge}', [KnowledgeController::class, 'show'])
    ->whereNumber('knowledge')
    ->name('knowledge.show');
Route::get('/knowledge/{knowledge}/image', [KnowledgeController::class, 'showImage'])
    ->whereNumber('knowledge')
    ->name('knowledge.image');

Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/shop/{product}', [ShopController::class, 'show'])->name('shop.show');
Route::get('/products/{product}/image', [ShopController::class, 'showProductImage'])
    ->whereNumber('product')
    ->name('products.image');

// Payment Proof Image Route (Public access for authenticated users)
Route::get('/payment-proof/{transaction}', [ShopController::class, 'showPaymentProof'])
    ->name('payment.proof')
    ->middleware('auth');

// Cart Routes (Public - bisa diakses guest dan user)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart/count', [CartController::class, 'getCount'])->name('cart.count');
Route::put('/cart/{productId}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{productId}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Password Reset Routes (Simplified)
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

/*
|--------------------------------------------------------------------------
| Protected Routes (Require Auth)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    /*
    |---------------------------------------------------------
    | MONITORING (semua role bisa masuk, tampilan beda)
    |---------------------------------------------------------
    */
    Route::get('/monitoring', [MonitoringController::class, 'index'])
        ->name('monitoring');


    /*
    |--------------------------------------------------------------------------
    | SUPERADMIN + PEGAWAI (Kecuali Account Management)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:superadmin,pegawai'])->group(function () {

        // Add measurement / feeding
        Route::post('/monitoring/add-measurement', 
            [MonitoringController::class, 'addMeasurement']
        )->name('monitoring.add');

        Route::get('/monitoring/live', [MonitoringController::class, 'liveData'])
            ->name('monitoring.live');

        Route::post('/products', [ShopController::class, 'store'])->name('products.store');
        Route::put('/products/{product}', [ShopController::class, 'update'])->name('products.update');
        Route::delete('/products/{product}', [ShopController::class, 'destroy'])->name('products.destroy');
        Route::post('/products/{product}/quick-update-stock', [ShopController::class, 'quickUpdateStock'])->name('products.quick-update-stock');

        // Knowledge Management
        Route::post('/knowledge', [KnowledgeController::class, 'store'])->name('knowledge.store');
        Route::put('/knowledge/{knowledge}', [KnowledgeController::class, 'update'])
            ->whereNumber('knowledge')
            ->name('knowledge.update');
        Route::delete('/knowledge/{knowledge}', [KnowledgeController::class, 'destroy'])
            ->whereNumber('knowledge')
            ->name('knowledge.destroy');

        // Sales Reports
        Route::get('/sales/reports', [MonitoringController::class, 'salesReports'])->name('sales.reports');

        // Weight Management
        Route::get('/weight', [WeightController::class, 'index'])->name('weight.index');
        Route::post('/weight', [WeightController::class, 'store'])->name('weight.store');
        Route::get('/weight/{weight}/edit', [WeightController::class, 'edit'])->name('weight.edit');
        Route::put('/weight/{weight}', [WeightController::class, 'update'])->name('weight.update');
        Route::delete('/weight/{weight}', [WeightController::class, 'destroy'])->name('weight.destroy');
        Route::get('/weight/data', [WeightController::class, 'getWeightData'])->name('weight.data');
        Route::get('/weight/clear-all', [WeightController::class, 'clearAll'])->name('weight.clear-all');
    });

    /*
    |--------------------------------------------------------------------------
    | SUPERADMIN ONLY (Account Management)
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:superadmin'])->group(function () {
        // Account Management - Hanya superadmin
        Route::get('/account', [AccountController::class, 'index'])->name('account.index');
        Route::post('/account', [AccountController::class, 'store'])->name('account.store');
        Route::put('/account/{user}', [AccountController::class, 'update'])->name('account.update');
        Route::delete('/account/{user}', [AccountController::class, 'destroy'])->name('account.destroy');
    });


    /*
    |--------------------------------------------------------------------------
    | PEGAWAI + SUPERADMIN
    |--------------------------------------------------------------------------
    */
    Route::middleware(['role:pembeli,pegawai,superadmin'])->group(function () {
        // Checkout & Transaction (Hanya untuk yang sudah login)
        Route::get('/checkout', [ShopController::class, 'checkout'])->name('checkout');
        Route::post('/checkout', [ShopController::class, 'processCheckout'])->name('checkout.process');
        Route::get('/transactions', [ShopController::class, 'transactions'])->name('transactions');
        Route::get('/transactions/{transaction}', [ShopController::class, 'showTransaction'])->name('transactions.show');
        Route::post('/transactions/{transaction}/upload-payment', [ShopController::class, 'uploadPayment'])->name('transactions.upload-payment');
        
        // Admin payment approval
        Route::get('/payment-verification', [ShopController::class, 'paymentVerification'])->name('payment.verification');
        Route::post('/transactions/{transaction}/approve-payment', [ShopController::class, 'approvePayment'])->name('transactions.approve-payment');
        Route::post('/transactions/{transaction}/reject-payment', [ShopController::class, 'rejectPayment'])->name('transactions.reject-payment');
    });

    /* MODEL MECHINE LEARNING */
     Route::post('/predict-bk', [WeightController::class, 'predictBK']);

});

