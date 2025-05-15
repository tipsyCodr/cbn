<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
})->name('home');
Route::get('membership',[UserController::class,'form'])->name('membership');
Route::post('membership/register',[UserController::class, 'store'])->name('membership.register');

Route::get('privacy', function (){
    return view('privacy');
});
Route::get('csae', function (){
    return view('csae');
});
