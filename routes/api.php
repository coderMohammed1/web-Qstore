<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Authorization; // auth middleware

// /api/v1

//docs
Route::get("/v1/docs","\App\Http\Controllers\\api\\Docs@main")->middleware(Authorization::class);

//products
Route::post("/v1/products","\App\Http\Controllers\\api\\products@search");

// auth
Route::post("/v1/auth","\App\Http\Controllers\\api\\auth@login");
Route::get("/v1/verifie","\App\Http\Controllers\\api\\auth@isAuth");