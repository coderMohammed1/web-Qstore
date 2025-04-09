<?php

namespace App\Http\Controllers;

use PDO;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Hash;

class profile extends BaseController
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
        if(isset($_SESSION["info"])){
            return view("profile");
        }else{
            return redirect("/signin");
        }
    }

    public function update(){
        session_start();

        if(isset($_SESSION["info"])){
            if(empty($_POST["password"])){
                $updatep = self::$database->prepare("UPDATE users SET First_Name = :fname, Last_name=:lname, birthdate=:bdate WHERE ID = :id");
                $updatep->bindValue("fname",htmlspecialchars($_POST['fname'], ENT_QUOTES, 'UTF-8'));

                $updatep->bindValue("lname",htmlspecialchars($_POST['lname'], ENT_QUOTES, 'UTF-8'));
                $updatep->bindValue("bdate",htmlspecialchars($_POST['bdate'], ENT_QUOTES, 'UTF-8'));
                $updatep->bindParam("id",$_SESSION["info"]->ID);

                if($updatep->execute()){
                    $newData = self::$database->prepare("SELECT * FROM USERS WHERE ID = :id"); //updating the session data
                    $newData->bindParam("id",$_SESSION["info"]->ID);

                    if($newData->execute()){
                        $_SESSION["info"] = $newData->fetchObject();
                        return view("profile")->with("succsess","Your data have been updated!");
                    }else{
                        return redirect("/logout");
                    }

                }else{
                    return view("error")->with("error","Somthing went wrong!");
                }

            }else{
                // if our guy wants to change his password as well!
                $paslen = strlen($_POST["password"]);

                $up = false;
                $low = false;
                $num = false;

                if($paslen <= 80){
                    for ($i = 0; $i < $paslen; $i++) {

                        if (ctype_upper($_POST["password"][$i])) {
                            $up = true;
                        } elseif (ctype_lower($_POST["password"][$i])) {
                            $low = true;
                        } elseif (ctype_digit($_POST["password"][$i])) {
                            $num = true;
                        }
                    }

                }else{
                    return view("error")->with("error","Max password length is 80!");
                }

                if($low and $up and $num and $paslen >= 8){
                    $password = Hash::make($_POST["password"]);
                    $updatep = self::$database->prepare("UPDATE users SET First_Name = :fname, Last_name=:lname, birthdate=:bdate, password=:pass WHERE ID = :id");
                    $updatep->bindValue("fname",htmlspecialchars($_POST['fname'], ENT_QUOTES, 'UTF-8'));

                    $updatep->bindValue("lname",htmlspecialchars($_POST['lname'], ENT_QUOTES, 'UTF-8'));
                    $updatep->bindValue("bdate",htmlspecialchars($_POST['bdate'], ENT_QUOTES, 'UTF-8'));
                    $updatep->bindParam("id",$_SESSION["info"]->ID);
                    $updatep->bindParam("pass",$password);

                    if($updatep->execute()){
                        $newData = self::$database->prepare("SELECT * FROM USERS WHERE ID = :id"); //updating the session data
                        $newData->bindParam("id",$_SESSION["info"]->ID);

                        if($newData->execute()){
                            $_SESSION["info"] = $newData->fetchObject();
                            return view("profile")->with("succsess","Your data have been updated!");
                        }else{
                            return redirect("/logout");
                        }

                }else{
                    return view("error")->with("error","Somthing went wrong!");
                }

                }else{
                    return view("profile")->with("error","Review password policy!");
                }

            }

        }else{
            return redirect("/signin");
        }

    }
  
}