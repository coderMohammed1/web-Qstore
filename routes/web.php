<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return "<h1>Welcome to the Qstore!</h1>";
});

//sign up route
Route::get('/register',"\App\Http\Controllers\\reg@signUser");
Route::post('/register',"\App\Http\Controllers\\reg@register");

