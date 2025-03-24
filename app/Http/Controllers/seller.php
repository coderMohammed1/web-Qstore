<?php

namespace App\Http\Controllers;

use PDO;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Support\Facades\Log;
use Illuminate\Routing\Controller as BaseController;
use phpDocumentor\Reflection\PseudoTypes\True_;
use Exception;

include("helpers.php");

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

            $recap = $_POST["g-recaptcha-response"];
            $google_response = recaptcha($recap); // google recaptcha
                    
            if(!$google_response->json('success')){
                return view("error")->with("error","we think you are a robot sir!");
            }

             $allowedExtensions = array("jpg", "jpeg", "png", "gif","bmp", "tiff", "tif", "webp", "ico", "heic", "heif", "jfif", "psd", "raw", "eps", "ai", "cdr");

             $prod = self::$database->prepare("INSERT INTO product(p_name,price,Manfacturer,seller,description,img,type,quantity) VALUES(:pname,:price,:manufact,:seller,:dsc,:img,:ty,:q)");
             $prod->bindValue("pname",htmlspecialchars($_POST['pname'], ENT_QUOTES, 'UTF-8'));

             $prod->bindValue("price",htmlspecialchars($_POST['price'], ENT_QUOTES, 'UTF-8'));
             $prod->bindValue("manufact",htmlspecialchars($_POST['Manu'], ENT_QUOTES, 'UTF-8'));

             $prod->bindValue("seller",htmlspecialchars($_POST['sell'], ENT_QUOTES, 'UTF-8'));
             $prod->bindValue("dsc",htmlspecialchars($_POST['desc'], ENT_QUOTES, 'UTF-8'));

             $prod->bindValue("q",htmlspecialchars($_POST['pquant'], ENT_QUOTES, 'UTF-8')); // DB constraint chck is applied as well

             if (in_array(strtolower(pathinfo($_FILES["img"]['name'], PATHINFO_EXTENSION)), $allowedExtensions)){
                    $img = $_FILES['img'];
                    $fileSize = $img['size']; 
                    $maxSize = 10 * 1024 * 1024; // 10MB
                    if($fileSize > $maxSize){
                        return view("seller")->with("error","File size should not be more then 10 MB!");
                    }

                    try{
                        $imageInfo = getimagesize($img['tmp_name']); // MIME check
                        if ($imageInfo === false) {
                            return view("seller")->with("error","Invalid image!");
                        }
                        
                    }catch(Exception $err){
                        return view("seller")->with("error","Somthing wen wrong!");
                    }

                    if (preg_match('/\.(php[0-9]?|phtml|pht|phar)$/i', $img['name'])) { // to prevent double extention (may get executed on some misconfigured servers)
                        return view("seller")->with("error", "Invalid file type!");
                    }

                    $imgName = htmlspecialchars($img['name'], ENT_QUOTES, 'UTF-8');
                    $fileExtension = pathinfo($imgName, PATHINFO_EXTENSION);
                    $uniqueName = uniqid('', true) . '.' . $fileExtension;
                    $relativePath = 'uploads/' . $uniqueName; // so, only the relativePath is stored in the db
                    $absolutePath = public_path($relativePath); 
                    
                    if (!move_uploaded_file($img['tmp_name'], $absolutePath)) {
                        return view("seller")->with("error","somthing went wrong!");
                    }

                    $prod->bindValue("img",$relativePath);
                    $prod->bindParam("ty",$_FILES["img"]["type"]);

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