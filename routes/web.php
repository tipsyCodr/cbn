<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\UserController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('membership',['UserController::form']);
Route::get('membership/register',['UserController::store']);

Route::get('privacy', function (){
    return view('privacy');
});
Route::get('csae', function (){
    return view('csae');
});
