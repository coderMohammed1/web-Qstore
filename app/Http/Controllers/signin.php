<?php

namespace App\Http\Controllers;

use PDO;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class signin extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected static $database;

    public function __construct()
    {
        if (self::$database === null) {
            self::$database = new PDO(
                "mysql:host=" . config("dbenv.dbhost") . ";dbname=" . config("dbenv.dbname"),
                config("dbenv.dbuname"),
                config("dbenv.dbpass")
            );
            self::$database->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
    }

    // GET
    function sign(){
        return view("signin");
    }

    //POST
    function signuser(){
        session_start();
        if (isset($_POST["login"])){
            $user = self::$database->prepare("SELECT * FROM users where email = :email");
            $user->bindParam("email",$_POST["email"]);
           
            if($user->execute() and $user->rowCount() > 0){
                
                $user = $user->fetchObject();

                if (Hash::check($_POST["password"], $user->password)) {
                    
                    if($user->isactive == 1){
                        $_SESSION["info"] = $user; // logs the user in
                        if($user->roles == "c"){
                            return redirect("/customers");
                        }else{
                            return redirect("/seller");
                        }
                        
                    }else{
                        return view("signin")->with("error","this account is not activated yet!");    
                    }

                }else{
                    return view("signin")->with("error","Email or password are not correct!");
                }

                
            }else{
                return view("signin")->with("error","Email or password are not correct!");
            }

        }
    }
  
}
