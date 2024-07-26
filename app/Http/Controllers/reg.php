<?php
namespace App\Http\Controllers;

use PDO;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use \Illuminate\Support\Facades\Mail;
use \Illuminate\Support\Facades\Queue;
use App\Mail\mailer;

class reg extends BaseController
{

    use AuthorizesRequests, ValidatesRequests;
    static function generateRandomString($length = 16) {
        return bin2hex(random_bytes($length / 2));
    }

    //GET
    function signUser(){
        return view("signup");
    }

    //POST
    function register(){
        if(isset($_POST["reg_sub"])){
            $database = new PDO("mysql:host=".config("dbenv.dbhost")."; dbname=".config("dbenv.dbname").";", config("dbenv.dbuname"), config("dbenv.dbpass"));

            $pattern = '/^[0-9a-zA-Z_@#]+$/';
            $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
            $name =  htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
            
            $lname = htmlspecialchars($_POST['lname'], ENT_QUOTES, 'UTF-8');;
            $password2 = $_POST["password"];

            $age = $_POST["age"];
            $role = $_POST["role"];
            $paslen = strlen($password2);

            $check4 = $database->prepare("SELECT * FROM users WHERE email=:email");
            $check4->bindParam('email', $email);

            $check4->execute();
            $isact2 = $check4->fetchObject();

            $up = false;
            $low = false;
            $num = false;

            for ($i = 0; $i < $paslen; $i++) {
                if (ctype_upper($password2[$i])) {
                    $up = true;
                } elseif (ctype_lower($password2[$i])) {
                    $low = true;
                } elseif (ctype_digit($password2[$i])) {
                    $num = true;
                }
            }

            if (
                !$email or !$name or empty($_POST["name"]) or empty($_POST['age']) or !isset($_POST["name"]) 
                 or !isset($_POST["email"]) or !isset($_POST['age']) or !isset($_POST['password']) or empty($password2)
                 or !preg_match($pattern, $password2) or empty($_POST["lname"]) or !isset($_POST["lname"]) or
                !$num or !$up or !$low or $paslen < 8 or ($role != "s" && $role != "c")
            ) {

                return view("signup")->with("error","Invalid data!");
            }else{
                $token = Hash::make(reg::generateRandomString(35).$email);
                $AddUser = $database->prepare("INSERT INTO users(First_Name,Last_name,roles,birthdate,password,email,token) VALUES(:name,:lname,:role,:age,:pass,:em,:token)");
                $AddUser->bindParam("name",$name);

                $AddUser->bindParam("lname",$lname);
                $AddUser->bindParam("role",$role);
                $AddUser->bindParam("token",$token);

                $AddUser->bindParam("age",$age);
                $AddUser->bindValue("pass",Hash::make($password2));
                $AddUser->bindParam("em",$email);

                $checkActi = $database->prepare("SELECT isactive FROM users WHERE email = :em"); // this to check if the email is already there but not activated!
                $checkActi->bindParam("em",$email);

                if($checkActi->execute()){       
                    $checkActio = $checkActi->fetchObject();
                    if($checkActio){
                        if($checkActi->rowCount() != 0 and $checkActio->isactive == 0){
                            $delem = $database->prepare("DELETE FROM users WHERE email = :em");
                            $delem->bindParam("em",$email);
    
                            if(!$delem->execute()){
                                return view("signup")->with("error","somthing went wrong!");
                            }
                            
                        }
                    }
                    
                    if($checkActi->rowCount() != 0){
                        if($checkActio and $checkActio->isactive == 1){
                            return view("signup")->with("error","this email is already active!");
                        }
                    }

                    if($AddUser->execute()){
                        Mail::to($email)->send(new mailer($token,$email));
                        return view("signup")->with("succsess","Pleas check your email!");
                    }else{
                        return view("signup")->with("error","somthing went wrong!");
                    }
                }
                }
                // now I will make a page for the user to complete his data after the activation!
                return view("signup")->with("error","somthing went wrong!, pleas try again");
            }
    }
}
