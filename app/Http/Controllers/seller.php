<?php

namespace App\Http\Controllers;

use PDO;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class seller extends BaseController
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

        if(isset($_SESSION["info"]) and $_SESSION["info"]->roles == "s"){ 
            return view("seller");
        }else{
            return redirect("/signin");
        }
        
    }

    //POST
    function add(){
        session_start();

        if(isset($_SESSION["info"]) and $_SESSION["info"]->roles == "s"){

            if($_SESSION["info"]->ID != $_POST["sell"]){
                return view("error")->with("error","You are not authriezed!");
            }

             $allowedExtensions = array("jpg", "jpeg", "png", "gif","bmp", "tiff", "tif", "webp", "svg", "ico", "heic", "heif", "jfif", "psd", "raw", "eps", "ai", "cdr");

             $prod = self::$database->prepare("INSERT INTO product(p_name,price,Manfacturer,seller,description,img) VALUES(:pname,:price,:manufact,:seller,:dsc,:img)");
             $prod->bindValue("pname",htmlspecialchars($_POST['pname'], ENT_QUOTES, 'UTF-8'));

             $prod->bindValue("price",htmlspecialchars($_POST['price'], ENT_QUOTES, 'UTF-8'));
             $prod->bindValue("manufact",htmlspecialchars($_POST['Manu'], ENT_QUOTES, 'UTF-8'));

             $prod->bindValue("seller",htmlspecialchars($_POST['sell'], ENT_QUOTES, 'UTF-8'));
             $prod->bindValue("dsc",htmlspecialchars($_POST['desc'], ENT_QUOTES, 'UTF-8'));

             if (in_array(strtolower(pathinfo($_FILES["img"]['name'], PATHINFO_EXTENSION)), $allowedExtensions)){
                $prod->bindValue("img",file_get_contents($_FILES["img"]['tmp_name']));

                if($prod->execute()){
                    return view("seller")->with("succsess","Your product has been added!");
                }else{
                    return view("seller")->with("error","Somthing went wrong!");
                }
             }else{
                return view("error")->with("error","You are not authriezed!");
             }
             
        }else{
            return redirect("/signin");
        }
    }
}