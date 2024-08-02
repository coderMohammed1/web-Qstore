<?php

use Illuminate\Support\Facades\Route;


Route::get('/', function () {
    return view("index");
});

//sign up routes
Route::get('/register',"\App\Http\Controllers\\reg@signUser");
Route::post('/register',"\App\Http\Controllers\\reg@register");

//activation routes
Route::get("/activate","\App\Http\Controllers\\Activate@activeuser");
Route::post("/activate","\App\Http\Controllers\\Activate@complete");

// signin
Route::get("/signin","\App\Http\Controllers\\signin@sign");
Route::post("/signin","\App\Http\Controllers\\signin@signuser");

//customers routes
Route::get("/customers","\App\Http\Controllers\\customer@main");


// seller routes
Route::get("/seller","\App\Http\Controllers\\seller@main");
Route::post("/seller","\App\Http\Controllers\\seller@add");