<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return "<h1>Welcome to the Qstore!</h1>";
});

//sign up routes
Route::get('/register',"\App\Http\Controllers\\reg@signUser");
Route::post('/register',"\App\Http\Controllers\\reg@register");

//activation routes
Route::get("/activate","\App\Http\Controllers\\Activate@activeuser");
Route::post("/activate","\App\Http\Controllers\\Activate@complete");