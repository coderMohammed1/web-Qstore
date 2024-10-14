<?php

namespace App\Http\Controllers;

use App\Mail\forgotp;
use PDO;
use Illuminate\Support\Facades\Hash;
use \Illuminate\Support\Facades\Mail;
use \Illuminate\Support\Facades\Queue;
use App\Mail\mailer;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class reset extends BaseController
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

    static function generateRandomString($length = 16) {
        return bin2hex(random_bytes($length / 2));
    }

    function main(){
        if(isset($_GET["token"])){
            session_start();
            $check_token = self::$database->prepare("SELECT ID FROM users WHERE token = :token AND email=:mail");
            $check_token->bindParam("token",$_GET["token"]);
            $check_token->bindParam("mail",$_GET["email"]);

            if($check_token->execute() && $check_token->rowCount() > 0){
                $_SESSION["rmail"] = $_GET["email"];
                return view("change_password");
            }else{
                return view("error")->with("error","Invalid token!");    
            }
        }else{
            return view("forgot");
        }

    }

    function sendmail(){
        session_start();
        //senmail part
        if(isset($_POST["reset"])){
            $email = htmlspecialchars($_POST['remail'], ENT_QUOTES, 'UTF-8');

            $check_email = self::$database->prepare("SELECT token FROM users WHERE email = :email AND isactive = 1");
            $check_email->bindParam("email",$email);

            if($check_email->execute() and $check_email->rowCount() > 0){
                $token = $check_email->fetchObject();
                $token = $token->token;
                
                Mail::to($email)->send(new forgotp($token,$email));
                return view("forgot")->with("Done","Check your email!");
            }else{
                return view("forgot")->with("Done","Check your email!");
            }
        }

        //password change part
        if(isset($_POST["change"])){
            if(isset($_SESSION["rmail"])){
                //enforce password policy
                $paslen = strlen($_POST["newpass"]);

                $up = false;
                $low = false;
                $num = false;

                if($paslen <= 40){
                    for ($i = 0; $i < $paslen; $i++) {

                        if (ctype_upper($_POST["newpass"][$i])) {
                            $up = true;
                        } elseif (ctype_lower($_POST["newpass"][$i])) {
                            $low = true;
                        } elseif (ctype_digit($_POST["newpass"][$i])) {
                            $num = true;
                        }

                    }
                }else{
                    return view("error")->with("error","Max password length is 40");
                }

                if($low and $up and $num and $paslen >= 8){
                    $ntoken =  Hash::make(reg::generateRandomString(35).$_SESSION["rmail"]);

                    $password = Hash::make($_POST["newpass"]);
                    $changep = self::$database->prepare("UPDATE users SET password = :pass, token = :token WHERE email = :email");
                    
                    $changep->bindParam("pass",$password);
                    $changep->bindParam("email",$_SESSION["rmail"]);
                    $changep->bindParam("token",$ntoken);

                    if($changep->execute()){
                        session_destroy();
                        session_unset();

                        return view("change_password")->with("Done","Your password has been updated!");    
                    }else{
                        return view("change_password")->with("error","somthing went wrong!");    
                    }

                }else{
                    return view("change_password")->with("error","review password policy!");
                }

            }else{
                return redirect("/");
            }
        }
    }
  
   
}
