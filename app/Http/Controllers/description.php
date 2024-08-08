<?php

namespace App\Http\Controllers;

use PDO;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class description extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public static $database;
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
        return view("error")->with("error","sorry but you can not view this data!");
    }
    
    function read(){
        session_start();

        if(isset($_POST["descr"]) and isset($_SESSION["info"]) and $_SESSION["info"]->roles == "c"){
            $product = self::$database->prepare("SELECT * FROM product JOIN users ON product.seller = users.ID AND product.ID = :id");
            $product->bindParam(":id",$_POST["descr"]);

            if($product->execute()){
                return view("description")->with("product",$product->fetchObject());
            }else{
                return view("error")->with("error","Somthing went wrong!");
            }
        }else{
            return redirect("/signin");
        }
        
    }
  
}
