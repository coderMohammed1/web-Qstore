<?php

namespace App\Http\Controllers;

use PDO;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class customer extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    function main(){
        session_start();

        if(isset($_SESSION["info"])){ //TODO:make sure he is a customer
            return "WELCOME:" . $_SESSION["info"]->First_Name;
        }else{
            return redirect("/signin");
        }
        
    }
  
}