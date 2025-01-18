<?php

namespace App\Http\Controllers;

use Exception;
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

            if(isset($_POST["send01"])){
                $searchv = "%".$_POST["search"]."%";
              
                $data = self::$database->prepare("SELECT img,type,p_name,price,ID FROM product WHERE p_name LIKE :pname OR Manfacturer LIKE :man ORDER BY ID DESC LIMIT 30");
                $data->bindParam("pname",$searchv);
                $data->bindParam("man",$searchv);

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

    function addTocart(){
        session_start();

        if(isset($_SESSION["cust"])){
            if(isset($_POST["buy"])){
                self::$database->beginTransaction(); // start transction

                try{
                    $add = self::$database->prepare("INSERT INTO cart_products(cart,product,quantity) values(:cart,:prod,1)");
                    $add->bindParam("cart",$_SESSION["cust"]->cart);
                    $add->bindParam("prod",$_POST["buy"]);

                    if($add->execute()){

                        $total = self::$database->prepare("SELECT product.price,quantity
                        FROM cart_products JOIN product ON product.ID = cart_products.product 
                        WHERE cart = :id");

                        $total->bindParam("id",$_SESSION["cust"]->cart);

                        if($total->execute()){
                            $sum = 0;
                            foreach($total as $tot){
                                $sum += $tot["price"]*$tot["quantity"];
                            }

                            $cartTotal = self::$database->prepare("UPDATE cart SET total = :total WHERE ID = :cart");
                            $cartTotal->bindParam("total",$sum);
                            $cartTotal->bindParam("cart",$_SESSION["cust"]->cart);

                            if($cartTotal->execute()){
                                self::$database->commit(); //end transction
                                return redirect("/customers");
                            }else{
                                self::$database->rollBack();
                                return view("error")->with("error","somthing went wrong");
                            }
                            
                        }else{
                            self::$database->rollBack();
                            return view("error")->with("error","somthing went wrong");
                        }
                    }else{
                        self::$database->rollBack();
                        return view("error")->with("error","somthing went wrong");
                    }
                }catch(Exception $ex){
                    self::$database->rollBack();
                    return view("error")->with("error","somthing went wrong");
                }
            }
        }
    }
  
}