<?php

namespace App\Http\Controllers;

use PDO;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;


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

        if(isset($_SESSION["info"])){

            // edit Description
            if(isset($_POST["Ndescription"])){

                $updatepd = self::$database->prepare("UPDATE product SET description = :newd WHERE ID = :pid AND seller = :selid");  // AND for IDOR protection
                $updatepd->bindParam("selid",$_SESSION["info"]->ID);
                $updatepd->bindParam("pid",$_POST["update"]);
                $updatepd->bindValue("newd",htmlspecialchars($_POST["Ndescription"], ENT_QUOTES, 'UTF-8'));

                if($updatepd->execute()){
                    return redirect("/editProducts");
                }else{
                    return view("error")->with("error","Somthing went wrong!");
                }
                
            }
            
            if(isset($_POST["descr"])){
                $product = self::$database->prepare("SELECT *, product.ID as pid FROM product JOIN users ON product.seller = users.ID AND product.ID = :id");
                $product->bindParam(":id",$_POST["descr"]);

                $rating = self::$database->prepare("SELECT AVG(rate) as rating FROM reviews WHERE product_id = :pid");
                $rating->bindParam("pid",$_POST["descr"]);

                if($product->execute() and $rating->execute()){
                    return view("description")->with("product",$product->fetchObject())->with("avg",$rating->fetchObject());
               }else{
                   return view("error")->with("error","Somthing went wrong!");
               }

            }

        
    }else{
        return redirect("/signin");
    }

 }
    
}
