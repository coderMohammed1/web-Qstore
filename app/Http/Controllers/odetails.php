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
            $check = self::$database->prepare("SELECT cust_id,sid FROM mycustomers WHERE cust_id = :cid AND sid = :sid");
            $check->bindParam("cid",$_GET["cid"]);
            $check->bindParam("sid",$_SESSION["info"]->ID);
            
            if($check->execute() and $check->rowCount() > 0){
                return view("details");
            }else{
                return view("error")->with("error","You are not authorized!");
            }
        }else{
            return redirect("signin");
        }
        
    }
  
}
