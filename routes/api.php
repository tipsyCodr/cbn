<?php

use App\Http\Controllers\Api\OneToOneController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ReferralController;
use App\Http\Controllers\Api\ThankYouController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('login',function(){
    return "You are not Logged in!";
})->name('logins');

Route::post('login', [AuthController::class, 'login'])->name('login');

// Protected Routes
Route::middleware('auth:sanctum')->group(function () {
    // Auth Routes
    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    
    // User Routes
    Route::get('user', function (Request $request) {
        return $request->user();
    })->name('user');
    Route::get('home', [UserController::class, 'home'])->name('user.home');
    Route::get('users', [UserController::class, 'index'])->name('users.index');
    Route::get('requests', [UserController::class, 'getAllRequests'])->name('user.requests');

    
    Route::get('profile', [UserController::class, 'profile'])->name('profile');
    Route::post('update-profile', [App\Http\Controllers\Api\UserController::class, 'updateProfile'])->name('update.profile');
    Route::post('update-profile-photo', [App\Http\Controllers\Api\UserController::class, 'updateProfilePic'])->name('update.profile');
    
    Route::post('get-profile', [App\Http\Controllers\Api\UserController::class, 'getProfile'])->name('get.profile');
    
    // Notification Routes
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        Route::get('/unread-count', [NotificationController::class, 'getUnreadCount'])->name('unread-count');
        Route::post('/mark-as-read/{id}', [NotificationController::class, 'markAsRead'])->name('mark-as-read');
        Route::post('/mark-all-as-read', [NotificationController::class, 'markAllAsRead'])->name('mark-all-as-read');
        Route::post('/clear/{id}', [NotificationController::class, 'clearNotification'])->name('clear');
    });
    
    // Referral Routes
    Route::prefix('referrals')->name('referrals.')->group(function () {
        Route::get('/', [ReferralController::class, 'index'])->name('index');
        Route::get('/{id}', [ReferralController::class, 'show'])->name('show');
        Route::post('store', [ReferralController::class, 'store'])->name('store');
        Route::post('update', [ReferralController::class, 'update'])->name('update');
        Route::post('delete', [ReferralController::class, 'destroy'])->name('delete');
    });

    // Thank You FCB Routes
    Route::prefix('tyfcb-requests')->name('tyfcb.')->group(function () {
        Route::get('/', [ThankYouController::class, 'index'])->name('index');
        Route::get('/{id}', [ThankYouController::class, 'show'])->name('show');
        Route::post('store', [ThankYouController::class, 'store'])->name('store');
        Route::post('update', [ThankYouController::class, 'update'])->name('update');
        Route::post('delete', [ThankYouController::class, 'destroy'])->name('delete');
    });

    // One-to-One Routes
    Route::prefix('oto-requests')->name('oto.')->group(function () {
        Route::get('/', [OneToOneController::class, 'index'])->name('index');
        Route::get('/{id}', [OneToOneController::class, 'show'])->name('show');
        Route::post('store', [OneToOneController::class, 'store'])->name('store');
        Route::post('update', [OneToOneController::class, 'update'])->name('update');
        Route::post('delete', [OneToOneController::class, 'delete'])->name('delete');
    });
    Route::post('members', [UserController::class, 'index'])->name('get.members');
});

// Route::prefix('v1')->group(function () {
//     require __DIR__.'/api/v1.php';
// });
