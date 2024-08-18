<?php

namespace App\Http\Controllers;

use PDO;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class customer extends BaseController
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

    function main(){
        session_start();

        if(isset($_SESSION["info"]) and $_SESSION["info"]->roles == "c"){ 

            $data = self::$database->prepare("SELECT img,type,p_name,price,ID FROM product ORDER BY ID DESC LIMIT 30");
            
            if($data->execute()){
                $results = $data->fetchAll(PDO::FETCH_ASSOC);
                return view("customer")->with("data",$results);
            }else{
                return view("error")->with("error","somthing went wrong!");
            }
        }else{
            return redirect("/signin");
        }
        
    }

    function search(){
        session_start();
        if(isset($_SESSION["info"]) and $_SESSION["info"]->roles == "c"){
            // add an index
            if(isset($_POST["send01"])){
                $data = self::$database->prepare("SELECT img,type,p_name,price,ID FROM product WHERE p_name LIKE :pname OR Manfacturer LIKE :man ORDER BY ID DESC LIMIT 30");
                $data->bindParam("pname",$_POST["search"]);
                $data->bindParam("man",$_POST["search"]);

                if($data->execute()){
                    $results = $data->fetchAll(PDO::FETCH_ASSOC);
                    return view("customer")->with("data",$results);
                }else{
                    return view("error")->with("error","somthing went wrong!");
                }
            }
        }else{
            return redirect("/signin");
        }
    }
  
}