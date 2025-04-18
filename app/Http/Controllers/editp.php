<?php

namespace App\Http\Controllers;

use Exception;
use PDO;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

// TODO: add soldout and warning for the delte

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

            if(isset($_POST["equant"])){
                // edit product's quantity

               $pq = self::$database->prepare("UPDATE product SET quantity = :q WHERE seller = :seller AND ID = :id");
               $pq->bindValue("q",htmlspecialchars($_POST['equant'], ENT_QUOTES, 'UTF-8'));
               $pq->bindParam("seller",$_SESSION["info"]->ID); // no IDOR with this guy
               $pq->bindParam("id",$_POST["editquant"]);

               if($pq->execute()){
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

           if(isset($_FILES["img"])){
            // edit product's image
            $allowedExtensions = array("jpg", "jpeg", "png", "gif","bmp", "tiff", "tif", "webp", "ico", "heic", "heif", "jfif", "psd", "raw", "eps", "ai", "cdr");

           if(in_array(strtolower(pathinfo($_FILES["img"]['name'], PATHINFO_EXTENSION)), $allowedExtensions)){
                $pimg = self::$database->prepare("UPDATE product SET img = :img2, type = :ty WHERE seller = :seller AND id = :id");

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

                    $pimg->bindValue("img2",$relativePath);
                    $pimg->bindParam("ty",$_FILES["img"]["type"]);
                    $pimg->bindParam("seller",$_SESSION["info"]->ID); // no IDOR with this guy
                    $pimg->bindParam("id",$_POST["pimg"]);

                if($pimg->execute()){
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

    function delete(){
        session_start();
        if(isset($_SESSION['info']) and isset($_POST["delp"])){

            try{
                self::$database->beginTransaction(); // start of trasction

                $check = self::$database->prepare("SELECT ID FROM product WHERE seller = :seller AND ID = :id"); // for security 
                $check->bindParam("seller", $_SESSION["info"]->ID);
                $check->bindParam("id", $_POST["delp"]);

                if ($check->execute()) {
                   
                    if ($check->rowCount() > 0) {
                       
                        // Cascade deletion in all tables
                        $cascadep = self::$database->prepare("DELETE FROM cart_products WHERE product = :pid");
                        $cascadep->bindParam("pid", $_POST["delp"]);
                        $cascadep->execute();

                        $cascadep2 = self::$database->prepare("DELETE FROM order_product WHERE product = :pid");
                        $cascadep2->bindParam("pid", $_POST["delp"]);
                        $cascadep2->execute();

                        $delete = self::$database->prepare("DELETE FROM product WHERE ID = :id");
                        $delete->bindParam("id", $_POST["delp"]);
                        $delete->execute();

                        self::$database->commit(); // end of trasction
                        return redirect("/editProducts")->with("success", "Your product has been deleted successfully!");
                    } else {
                        
                        self::$database->rollBack();
                        return redirect("/editProducts")->with("error", "Product not found or you are not authorized to delete it.");
                    }
                } else {
                    self::$database->rollBack();
                    return redirect("/editProducts")->with("error", "Something went wrong!");
                }

            }catch(Exception $err){
                self::$database->rollBack();
                return redirect("/editProducts")->with("error", "Something went wrong! Error: ");
            }

        }else{
            return redirect("/login");
        }

    }

  
}