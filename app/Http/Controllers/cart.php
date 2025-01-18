<?php

namespace App\Http\Controllers;

use Exception;
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
    function main() {
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
    function checkout(){
        session_start();

        if (isset($_SESSION["info"]) && isset($_SESSION["cust"]) && isset($_SESSION["prods"])) {
            try{
                self::$database->beginTransaction(); // start trasction
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
                                
                                if(!$isItthere->execute()){
                                    self::$database->rollBack();
                                    return view("error")->with("error","somthing went wrong!");
                                }
            
                                if($isItthere->rowCount() == 0){
                                    $link = self::$database->prepare("INSERT INTO mycustomers(cust_id,sid) VALUES(:cid,:sid)");
                                    $link->bindParam("cid",$_SESSION["info"]->ID);
                                    $link->bindParam("sid",$seller);
            
                                    if(!$link->execute()){
                                        self::$database->rollBack();
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

                                self::$database->commit(); // end of the trasction
                                return view("cart")->with("Done", "Your items will be delivered soon!");
                            } else {
                                self::$database->rollBack();
                                return view("cart")->with("Done", "Something went wrong!");
                            }
                        } else {
                            self::$database->rollBack();
                            return view("cart")->with("Done", "Something went wrong!");
                        }
                    }
                }else{
                    self::$database->rollBack();
                    return view("error")->with("error","somthing went wrong!");
                }

            }catch(Exception $err){
                self::$database->rollBack();
                return view("error")->with("error","somthing went wrong!");
            }

        } else {
            return view("cart")->with("error", "The cart is already empty!");
        }
    }

    function delete(){
        session_start();

        if(isset($_SESSION["cust"]) and isset($_POST["delc"])){
            self::$database->beginTransaction(); // start a transction

            try{
                $delp = self::$database->prepare("DELETE FROM cart_products WHERE ID = :cpid AND cart = :cartID");
                $delp->bindParam("cpid",$_POST["delc"]);
                $delp->bindParam("cartID",$_SESSION["cust"]->cart);

                if($delp->execute()){

                    // update the total as the items got deleted
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
                            self::$database->commit(); // end of trasction
                        }else{
                            self::$database->rollBack();
                            return "error";
                        }
                        
                    }else{
                        self::$database->rollBack();
                        return "error";
                    }

                    return redirect("/cart");
                }else{
                    self::$database->rollBack();
                    return view("error")->with("error","somthing went wrong!");
                }
            }catch(Exception $err){
                self::$database->rollBack();
                return view("error")->with("error","somthing went wrong");
            }
        }else{
            return redirect("/signin");
        }

    }

    function quantity(){
        session_start();
        if(isset($_SESSION["cust"])){
            // add rules
            if(isset($_POST["number"]) and $_POST["number"] > 0){ //TODO:edit this when we implement the soldout
                self::$database->beginTransaction(); // start trasction

                try{
                    $controlQ = self::$database->prepare("UPDATE cart_products SET quantity = :quant WHERE ID = :pid AND cart = :cart");
                    $controlQ->bindValue("quant",htmlspecialchars($_POST['number'], ENT_QUOTES, 'UTF-8'));
                    $controlQ->bindParam("cart",$_SESSION["cust"]->cart);
                    $controlQ->bindParam("pid",$_POST["prod"]);

                    if($controlQ->execute()){
                        // update the total as the quanity got updated
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
                                self::$database->commit(); // end of trasction
                            }else{
                                self::$database->rollBack();
                                return "error";
                            }
                            
                        }else{
                            self::$database->rollBack();
                            return "error";
                        }

                        return "done";
                    }else{
                        self::$database->rollBack();
                        return "error";
                    }
                }catch(Exception $erer){
                    self::$database->rollBack();
                    return "error";
                }

            }

        }else{
            return redirect("/signin");
        }
    }

}
