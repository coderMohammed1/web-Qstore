<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// /api/v1

//docs
Route::get("/v1/docs","\App\Http\Controllers\\api\\Docs@main");

//products
Route::post("/v1/products","\App\Http\Controllers\\api\\products@search");
