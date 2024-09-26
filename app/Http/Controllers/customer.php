<?php

namespace App\Http\Controllers;

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
                $data = self::$database->prepare("SELECT img,type,p_name,price,ID FROM product WHERE p_name LIKE :pname OR Manfacturer LIKE :man ORDER BY ID DESC LIMIT 30");
                $data->bindParam("pname",$_POST["search"]);
                $data->bindParam("man",$_POST["search"]);

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

                $add = self::$database->prepare("INSERT INTO cart_products(cart,product,quantity) values(:cart,:prod,1)");
                $add->bindParam("cart",$_SESSION["cust"]->cart);
                $add->bindParam("prod",$_POST["buy"]);

                $seller = self::$database->prepare("SELECT seller FROM product WHERE ID = :pid LIMIT 1");
                $seller->bindParam("pid",$_POST["buy"]);

                if($seller->execute()){
                    $seller = $seller->fetchObject();

                    $isItthere = self::$database->prepare("SELECT sid,cust_id FROM mycustomers WHERE cust_id = :cid AND sid = :sid");
                    $isItthere->bindParam("cid",$_SESSION["info"]->ID);
                    $isItthere->bindParam("sid",$seller->seller);
                    

                    if($isItthere->execute() and $isItthere->rowCount() == 0){
                        $link = self::$database->prepare("INSERT INTO mycustomers(cust_id,sid) VALUES(:cid,:sid)");
                        $link->bindParam("cid",$_SESSION["info"]->ID);
                        $link->bindParam("sid",$seller->seller);

                       if(!$link->execute()){
                        return view("error")->with("error","somthing went wrong!");
                       }
                    }

                }

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
                            return redirect("/customers");
                        }
                        
                    }else{
                        return view("error")->with("error","somthing went wrong");
                    }

                }
            }
        }
    }
  
}