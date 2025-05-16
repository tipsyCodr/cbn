<?php

use App\Http\Controllers\VisitorController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
})->name('home');
Route::get('membership',[UserController::class,'form'])->name('membership');
Route::post('membership/register',[UserController::class, 'store'])->name('membership.register');

Route::get('visitor',[VisitorController::class,'index'])->name('visitor');
Route::post('visitor/register',[VisitorController::class, 'store'])->name('visitor.register');

Route::get('privacy', function (){
    return view('privacy');
})->name('privacy');
Route::get('csae', function (){
    return view('csae');
})->name('csae');

Route::get('app-info', function (){
    return view('app-info');
})->name('app.info');
