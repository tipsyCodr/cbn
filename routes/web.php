<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
Route::get('/', function () {
    return view('welcome');
});
Route::get('privacy', function (){
    return view('privacy');
});
Route::get('csae', function (){
    return view('csae');
});
