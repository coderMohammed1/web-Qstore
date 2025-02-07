<?php

namespace App\Http\Controllers;

use PDO;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class odetails extends BaseController
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

        if(isset($_SESSION["info"])){
            $check = self::$database->prepare("SELECT First_Name,email,street,city,country FROM mycustomers 
            JOIN users ON users.ID = mycustomers.cust_id
            JOIN customer ON customer.cust_id = :cid
             WHERE mycustomers.cust_id = :cid AND sid = :sid");
            $check->bindParam("cid",$_GET["cid"]);
            $check->bindParam("sid",$_SESSION["info"]->ID);
            
            if($check->execute() and $check->rowCount() > 0){

                $details = self::$database->prepare("
                SELECT 
                    product.ID AS pid, 
                    product.p_name AS pname, 
                    product.price AS price, 
                    product.Manfacturer AS manufacturer,
                    order_product.quantity as oq 
                FROM 
                    order_product 
                JOIN 
                    product ON product.ID = order_product.product 
                JOIN 
                    orders ON order_product.order_id = orders.ID 
                WHERE 
                    orders.user_id = :cid 
                    AND product.seller = :sid
              ");
                
                $details->bindParam("cid",$_GET["cid"]);
                $details->bindParam("sid",$_SESSION["info"]->ID);

                if($details->execute()){
                    return view("details")->with("data",$details->fetchAll(PDO::FETCH_ASSOC))->with("customerdet",$check->fetchAll(PDO::FETCH_ASSOC));
                }

            }else{
                return view("error")->with("error","You are not authorized!");
            }
        }else{
            return redirect("signin");
        }
        
    }
  
}
