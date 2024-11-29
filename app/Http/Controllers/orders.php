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
            
            $orders = self::$database->prepare("SELECT 
            mycustomers.sid as sellerid,
            mycustomers.ID as mid,
            mycustomers.cust_id as cust,
            users.First_Name as fname,
            users.email as email
            FROM mycustomers
            JOIN users ON mycustomers.cust_id = users.ID
            WHERE mycustomers.sid = :se LIMIT 30");


            $orders->bindParam("se",$_SESSION["info"]->ID);
            if($orders->execute()){
                $result = $orders->fetchAll(PDO::FETCH_ASSOC);
                return view("orders")->with("orders",$result);

            }

        }else{
            return redirect("/signin");
        }
    }

    function search(){
        session_start();

        if(isset($_SESSION["info"]) and $_SESSION["info"]->roles = "s"){
            $orders = self::$database->prepare("SELECT 
            mycustomers.sid as sellerid,
            mycustomers.ID as mid,
            mycustomers.cust_id as cust,
            users.First_Name as fname,
            users.email as email
            FROM mycustomers
            JOIN users ON mycustomers.cust_id = users.ID
            WHERE mycustomers.sid = :se and (users.First_Name LIKE :name OR users.email LIKE :em) LIMIT 30");

            $orders->bindParam("se",$_SESSION["info"]->ID);
            $orders->bindValue("em","%".$_POST["search"]."%");
            $orders->bindValue("name","%".$_POST["search"]."%");

            if($orders->execute()){
                $result = $orders->fetchAll(PDO::FETCH_ASSOC);
                return view("orders")->with("orders",$result);

            }

        }
    }
}