<?php

namespace App\Http\Controllers;

use PDO;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class editp extends BaseController
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

    //GET
    function main(){
        session_start();

        if(isset($_SESSION['info'])){
            $myproducts = self::$database->prepare("SELECT * FROM product where seller = :selleriId");
            $myproducts->bindParam("selleriId",$_SESSION["info"]->ID);

            if($myproducts->execute()){
                $results = $myproducts->fetchAll(PDO::FETCH_ASSOC);
                return view("editP")->with("data",$results);
            }else{
                return view("editP")->with("error","Somthing went wrong!");
            }

        }else{
            return redirect("/login");
        }

    }

    //POST
    function edit(){
        session_start();

        if(isset($_SESSION['info'])){

            if(isset($_POST["pname2"])){
                 // edit product's name

                $pnaem = self::$database->prepare("UPDATE product SET p_name = :pname WHERE seller = :seller AND ID = :id");
                $pnaem->bindValue("pname",htmlspecialchars($_POST['pname2'], ENT_QUOTES, 'UTF-8'));
                $pnaem->bindParam("seller",$_SESSION["info"]->ID); // no IDOR with this guy
                $pnaem->bindParam("id",$_POST["editName"]);

                if($pnaem->execute()){
                    return redirect("/editProducts")->with("succsess","Your product has been updated succsessfully!");
                }else{
                    return redirect("/editProducts")->with("error","Somthing went wrong!");
                }

            }

            if(isset($_POST["price2"])){
                // edit product's price

               $pnaem = self::$database->prepare("UPDATE product SET price = :nprice WHERE seller = :seller AND id = :id");
               $pnaem->bindValue("nprice",htmlspecialchars(substr($_POST['price2'], 0, -1), ENT_QUOTES, 'UTF-8'));
               $pnaem->bindParam("seller",$_SESSION["info"]->ID); // no IDOR with this guy
               $pnaem->bindParam("id",$_POST["editPrice"]);

               if($pnaem->execute()){
                   return redirect("/editProducts")->with("succsess","Your product has been updated succsessfully!");
               }else{
                   return redirect("/editProducts")->with("error","Somthing went wrong!");
               }

           }

           if(isset($_FILES["img2"])){
            // edit product's image
            $allowedExtensions = array("jpg", "jpeg", "png", "gif","bmp", "tiff", "tif", "webp", "svg", "ico", "heic", "heif", "jfif", "psd", "raw", "eps", "ai", "cdr");

           if(in_array(strtolower(pathinfo($_FILES["img2"]['name'], PATHINFO_EXTENSION)), $allowedExtensions)){
                $pnaem = self::$database->prepare("UPDATE product SET img = :img2, type = :ty WHERE seller = :seller AND id = :id");

                $pnaem->bindValue("img2",file_get_contents($_FILES["img2"]['tmp_name']));
                $pnaem->bindParam("seller",$_SESSION["info"]->ID); // no IDOR with this guy
                $pnaem->bindParam("ty",$_FILES["img2"]["type"]);
                $pnaem->bindParam("id",$_POST["pimg"]);

                if($pnaem->execute()){
                    return redirect("/editProducts")->with("succsess","Your product has been updated succsessfully!");
                }else{
                    return redirect("/editProducts")->with("error","Somthing went wrong!");
                }
            }else{
                return view("error")->with("error","this extention is not allowed!");
            }
       }

        }else{
            return redirect("/login");
        }

    }

  
}