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
    private $prods;

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

    // get
    function main()
    {
        session_start();

        if (isset($_SESSION["cust"]) && isset($_SESSION["info"])) {
            $prods = $this->prods = self::$database->prepare("
                SELECT 
                    product.ID as pid,
                    product.p_name as pname,
                    product.price as price,
                    product.seller as sellerid,
                    product.img as img,
                    cart.user_id as cuid,
                    cart.total as tot,
                    cart_products.ID as cpid,
                    cart_products.quantity as quant,
                    product.type as type
                FROM 
                    cart_products 
                JOIN 
                    cart ON cart_products.cart = :cart 
                JOIN 
                    product ON cart_products.product = product.ID
                WHERE 
                    cart.user_id = :user
            ");

            $prods->bindParam("user", $_SESSION["info"]->ID);
            $prods->bindParam("cart", $_SESSION["cust"]->cart);

            if ($prods->execute() && $prods->rowCount() > 0) {
                $prods = $prods->fetchAll(PDO::FETCH_ASSOC);
                $_SESSION["prods"] = $prods; // to be used in the checkout method
                return view("cart")->with("data", $prods);
            } else {
                return view("cart")->with("error", "The cart is empty!"); // edit this
            }
        } else {
            return redirect("/signin");
        }
    }

    // post
    function checkout()
    {
        session_start();

        if (isset($_SESSION["info"]) && isset($_SESSION["cust"]) && isset($_SESSION["prods"])) {
            $total = $_SESSION["prods"][0]["tot"];

            $MakeOrder = self::$database->prepare("INSERT INTO orders(price, user_id) VALUES(:total, :id)");
            $MakeOrder->bindParam("total", $total);
            $MakeOrder->bindParam("id", $_SESSION["info"]->ID);

            if ($MakeOrder->execute()) {
                $currentOrder = self::$database->prepare("SELECT ID FROM orders WHERE user_id = :id ORDER BY ID DESC LIMIT 1");
                $currentOrder->bindParam("id", $_SESSION["info"]->ID);

                if ($currentOrder->execute()) {
                    $oid = $currentOrder->fetchObject()->ID;
                    $coreect = true;

                    $same_seller = -1; //just to check if the current product share the same seller with las one
                    foreach ($_SESSION["prods"] as $prod) {
                        $copy = self::$database->prepare("INSERT INTO Order_Product(order_id, product) VALUES(:order, :product)");
                        $copy->bindParam("order", $oid);
                        $copy->bindParam("product", $prod["pid"]);

                        //mycustomers table
                      
                        $seller = $prod["sellerid"];
                        
                        if($same_seller != $seller){
                            $isItthere = self::$database->prepare("SELECT 'a' FROM mycustomers WHERE cust_id = :cid AND sid = :sid");
                            $isItthere->bindParam("cid",$_SESSION["info"]->ID);
                            $isItthere->bindParam("sid",$seller);
                            
        
                            if($isItthere->execute() and $isItthere->rowCount() == 0){
                                $link = self::$database->prepare("INSERT INTO mycustomers(cust_id,sid) VALUES(:cid,:sid)");
                                $link->bindParam("cid",$_SESSION["info"]->ID);
                                $link->bindParam("sid",$seller);
        
                                if(!$link->execute()){
                                return view("error")->with("error","somthing went wrong!");
                                }
                            }

                        }

                        if (!$copy->execute()) {
                            $coreect = false;
                            break;
                        }
                        $same_seller = $seller;

                    }

                    if ($coreect) {
                        $checkit = self::$database->prepare("DELETE FROM cart_products WHERE cart = :cid"); // making the cart empty again
                        $checkit->bindParam("cid", $_SESSION["cust"]->cart);

                        if ($checkit->execute()) {
                            unset($_SESSION["prods"]);
                            
                            return view("cart")->with("Done", "Your items will be delivered soon!");
                        } else {
                            return view("cart")->with("Done", "Something went wrong!");
                        }
                    } else {
                        return view("cart")->with("Done", "Something went wrong!");
                    }
                }
            }
        } else {
            return view("cart")->with("error", "The cart is already empty!");
        }
    }
}
