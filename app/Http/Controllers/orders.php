<?php

namespace App\Http\Controllers;

use PDO;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class orders extends BaseController
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

        if(isset($_SESSION["info"]) and $_SESSION["info"]->roles = "s"){
            // think about huge sellers so u may need to limit and add search
        //     $orders = self::$database->prepare("
        //     SELECT 
        //         cart_products.product AS pid, 
        //         cart_products.quantity AS quant,
        //         city AS city,
        //         street AS street,
        //         country AS country,
        //         product.p_name AS pname,
        //         price AS price,
        //         cusers.email AS email,
        //         cusers.First_Name AS ufname,
        //         cusers.Last_Name AS ulname
        //     FROM 
        //         cart_products
        //     JOIN 
        //         cart ON cart.ID = cart_products.cart 
        //     JOIN 
        //         users AS cusers ON cusers.ID = cart.user_id 
        //     JOIN 
        //         customer ON customer.cust_id = cart.user_id 
        //     JOIN 
        //         product ON product.ID = cart_products.product
             
        //     WHERE 
        //         cart_products.product IN (
        //             SELECT product.ID 
        //             FROM product 
        //             WHERE product.seller = :se
        //         )
        // ");
        $orders = self::$database->prepare("SELECT 
        mycustomers.sid as sellerid,
        mycustomers.ID as mid,
        mycustomers.cust_id as cust,
        users.First_Name as fname,
        users.Last_name as lname,
        users.email as email
        FROM mycustomers
        JOIN users ON mycustomers.cust_id = users.ID
        WHERE mycustomers.sid = :se");


            $orders->bindParam("se",$_SESSION["info"]->ID);
            if($orders->execute()){
                $result = $orders->fetchAll(PDO::FETCH_ASSOC);
                return view("orders")->with("orders",$result);

            }

        }else{
            return redirect("/signin");
        }
    }
}