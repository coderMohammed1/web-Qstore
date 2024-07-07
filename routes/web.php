<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    

    // $statment = $database->prepare("INSERT INTO users(First_Name,last_Name,roles,userName,age,shipping_info,password) values('mohammed','mohammed','c','mohammed','1990-05-15','mohammed','mohammed')");
    // if($statment->execute()){
    //     return "done";
    // }else{
    //     return "none";
    // }

    return view("login")->with("name",$_GET["name"]);
});

