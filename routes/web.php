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
Route::post("/customers/add","\App\Http\Controllers\\customer@addTocart");

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

//cart
Route::get("/cart","\App\Http\Controllers\\cart@main");
Route::post("/cart","\App\Http\Controllers\\cart@checkout");
Route::post("/cart/delete","\App\Http\Controllers\\cart@delete");
Route::post("/cart/quantity","\App\Http\Controllers\\cart@quantity");

// orders (for seller)
Route::get("/orders","\App\Http\Controllers\\orders@main");
Route::get("/details","\App\Http\Controllers\\odetails@main");
Route::post("/orders","\App\Http\Controllers\\orders@search");

//Forgot password
Route::get("/resetp","\App\Http\Controllers\\reset@main");
Route::post("/resetp","\App\Http\Controllers\\reset@sendmail");

//edit products
Route::get("/editProducts","\App\Http\Controllers\\editp@main");
Route::post("/editProducts","\App\Http\Controllers\\editp@edit");
Route::post("/editProducts/delete","\App\Http\Controllers\\editp@delete");