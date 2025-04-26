<?php

namespace App\Http\Controllers;

use PDO;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
include("helpers.php");

class review extends BaseController
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

    public function main(){
        session_start();
        if (isset($_SESSION["info"]) and $_SESSION["info"]->roles === "c" and isset($_GET["pid"])){
            $ratingData = self::$database->prepare("SELECT First_Name,review,rate FROM reviews JOIN users
            ON users.ID = reviews.user_id JOIN product ON reviews.product_id = product.ID  WHERE product_id = :pid");

            $ratingData->bindParam("pid",$_GET["pid"]);
            if($ratingData->execute()){
                return view("reviews")->with("data",$ratingData->fetchAll(PDO::FETCH_ASSOC));
            }else{
                return view("error")->with("error","something went wrong!");
            }
        }else{
            return redirect("/signin");
        }

    }
    //post
    public function rate(){
        session_start();
        if(isset($_SESSION["info"]) and $_SESSION["info"]->roles === "c" and isset($_GET["pid"])){
            if (isset($_POST["post"])){
                if($_POST["rating"] > 5 or $_POST["rating"] < 0){return view("error")->with("error","Rating must be between 0 and 5");}

                $recap = $_POST["g-recaptcha-response"];
                $google_response = recaptcha($recap); // google recaptcha
                            
                if(!$google_response->json('success')){
                    return view("error")->with("error","we think you are a robot sir!");
                }

                try{
                    self::$database->beginTransaction(); // start transaction
                    $is_rated = self::$database->prepare("SELECT 'rated' FROM reviews WHERE product_id = :pid and user_id = :id"); //to prevent the customer from adding multiple rating to the same product
                    $is_rated->bindParam("pid",$_GET["pid"]);
                    $is_rated->bindParam("id",$_SESSION["info"]->ID);

                    if($is_rated->execute()){
                        if($is_rated->rowCount() > 0){return redirect("/review?pid=".$_GET["pid"]);}
                    }else{
                        self::$database->rollBack();
                        return view("error")->with("error","something went wrong!");
                    }
                    
                    $comment = self::$database->prepare("INSERT INTO reviews(review,rate,user_id,product_id) values(:r,:rate,:id,:pid)");
                    $comment->bindValue("r",htmlspecialchars($_POST["post"], ENT_QUOTES, 'UTF-8'));

                    $comment->bindValue("rate",htmlspecialchars($_POST["rating"], ENT_QUOTES, 'UTF-8'));
                    $comment->bindParam("id",$_SESSION["info"]->ID);
                    $comment->bindParam("pid",$_GET["pid"]); // query parameter 

                    if($comment->execute()){
                        self::$database->commit(); //end transaction
                        return redirect("/review?pid=".$_GET["pid"]);
                    }else{
                        self::$database->rollBack();
                        return view("error")->with("error","something went wrong!");
                    }
                }catch(\Exception){
                    self::$database->rollBack();
                }

            }
        }
    }

}