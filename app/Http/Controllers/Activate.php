<?php

namespace App\Http\Controllers;

use PDO;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Hash;

class Activate extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    
    static function generateRandomString($length = 16) {
        return bin2hex(random_bytes($length / 2));
    }

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

    function activeuser(){
        if(isset($_GET["token"])){
       
            $token = self::$database->prepare("SELECT token,roles FROM users WHERE token = :token");
            $token->bindParam("token",$_GET["token"]);

            if($token->execute() and $token->rowCount()>0){
                $token = $token->fetchObject();
                return view("activate")->with("token",$token);
                // $activate = $database->prepare("UPDATE users SET activa");
            }else{
                return view("error")->with("error","Invalid token!");
            }
        }
    }

    //post
    function complete(){
        //cudtomer
        if(isset($_POST["complete"])){
            $comp = self::$database->prepare("SELECT ID,email FROM users WHERE token=:token");
            $comp->bindParam("token",$_POST["complete"]);

            if($comp->execute()){
                $comp = $comp->fetchObject();

                $city = htmlspecialchars($_POST['city'], ENT_QUOTES, 'UTF-8');
                $country = htmlspecialchars($_POST['country'], ENT_QUOTES, 'UTF-8');

                $street = htmlspecialchars($_POST['street'], ENT_QUOTES, 'UTF-8');
                $complete2 = self::$database->prepare("INSERT INTO customer(cust_id,city,street,country) VALUES(:id,:city,:street,:country)");

                $complete2->bindParam("id",$comp->ID);
                $complete2->bindParam("city",$city);

                $complete2->bindParam("street",$street);
                $complete2->bindParam("country",$country);

                if($complete2->execute()){
                    $act = self::$database->prepare("UPDATE users SET isactive = 1,token = :newtoken WHERE token = :token");
                    $act->bindValue("newtoken",Hash::make(reg::generateRandomString(35).$comp->email));
                    $act->bindValue("token",$_POST["complete"]);

                    if(!$act->execute()){
                        return view("error")->with("error","somthing went wrong!");
                    }

                    return view("activate")->with("succsess","Your account has been activated!, you can login now!");
                }

            }else{
                return view("error")->with("error","somthing went wrong!");
            }

        }

        //seller
        if(isset($_POST["seller"])){
            $comp = self::$database->prepare("SELECT ID,email FROM users WHERE token=:token");
            $comp->bindParam("token",$_POST["seller"]);

            if($comp->execute()){
                $comp = $comp->fetchObject();
                $ins = self::$database->prepare("INSERT INTO seller(seller_id) VALUES(:id)");

                $ins->bindParam("id",$comp->ID);
                if($ins->execute()){
                    $act = self::$database->prepare("UPDATE users SET isactive = 1,token = :newtoken WHERE token = :token");
                    $act->bindValue("newtoken",Hash::make(reg::generateRandomString(35).$comp->email));
                    $act->bindValue("token",$_POST["seller"]);

                    if(!$act->execute()){
                        return view("error")->with("error","somthing went wrong!");
                    }

                    return view("activate")->with("succsess","Your account has been activated!, you can login now!");
                }
            }
        }else{
            return view("error")->with("error","somthing went wrong!");
        }

        return view("error")->with("error","somthing went wrong!");
    }
  
}