<?php

use App\Http\Controllers\Api\OneToOneController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ReferralController;
use App\Http\Controllers\Api\ThankYouController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::post('/login', [AuthController::class, 'login'])->name('login');

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/user', function (Request $request) {
        return $request->user();
    })->name('user');

    Route::get('/users', [UserController::class, 'index'])->name('users');
    Route::get('/home', [UserController::class, 'home'])->name('user.details');

    // Referral Routes
    Route::prefix('referral')->name('referral.')->group(function () {
        Route::get('/', [ReferralController::class, 'index'])->name('index');
        Route::post('store', [ReferralController::class, 'store'])->name('store');
        Route::post('update', [ReferralController::class, 'update'])->name('update');
        Route::post('delete', [ReferralController::class, 'delete'])->name('delete');
    });

    // Thank You FCB Routes
    Route::prefix('tyfcb-requests')->name('tyfcb.')->group(function () {
        Route::get('/', [ThankYouController::class, 'index'])->name('index');
        Route::post('store', [ThankYouController::class, 'store'])->name('store');
        Route::post('update', [ThankYouController::class, 'update'])->name('update');
        Route::post('delete', [ThankYouController::class, 'delete'])->name('delete');
    });

    // One-to-One Routes
    Route::prefix('oto-requests')->name('oto.')->group(function () {
        Route::get('/', [OneToOneController::class, 'index'])->name('index');
        Route::post('store', [OneToOneController::class, 'store'])->name('store');
        Route::post('update', [OneToOneController::class, 'update'])->name('update');
        Route::post('delete', [OneToOneController::class, 'delete'])->name('delete');
    });
    Route::get('/members', [UserController::class, 'index'])->name('members.index');
});

// Route::prefix('v1')->group(function () {
//     require __DIR__.'/api/v1.php';
// });          