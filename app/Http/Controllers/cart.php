<?php

namespace App\Http\Controllers;

use PDO;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class cart extends BaseController
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

    //get
    function main(){
        session_start();

        if(isset($_SESSION["cust"]) and isset($_SESSION["info"])){
            $prods = self::$database->prepare("SELECT product.ID as pid,product.p_name as pname,product.price as price
            ,product.seller as sellerid,product.img as img,cart.user_id as cuid,cart.total as tot,cart_products.ID as cpid,
            cart_products.quantity as quant,product.type as type
            FROM cart_products JOIN cart ON cart_products.cart = :cart JOIN product ON cart_products.product = product.ID
             where cart.user_id = :user");

            $prods->bindParam("user",$_SESSION["info"]->ID);
            $prods->bindParam("cart",$_SESSION["cust"]->cart);

            if($prods->execute()){
                return view("cart")->with("data",$prods->fetchAll(PDO::FETCH_ASSOC));
            }else{
                return view("error")->with("error","somthing went wrong!");
            }

        }else{
            return redirect("/signin");
        }
    }
  
}