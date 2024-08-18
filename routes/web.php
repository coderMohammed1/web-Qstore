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
Route::post("/customers","\App\Http\Controllers\\customer@search");

// seller routes
Route::get("/seller","\App\Http\Controllers\\seller@main");
Route::post("/seller","\App\Http\Controllers\\seller@add");

// test
// Route::get("/test",function(){
//     return view("test");
// });


// product/description
Route::post("/product/description","\App\Http\Controllers\\description@read");
Route::get("/product/description","\App\Http\Controllers\\description@main");

//logout
Route::get("/logout",function(){
    session_start();
    session_destroy();

    session_unset();
    return redirect("/signin");
});
