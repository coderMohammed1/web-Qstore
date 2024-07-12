<?php
namespace App\Http\Controllers;

use PDO;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class reg extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;
    function signUser(){
        return view("signup");
    }

    //post
    function register(){
        if(isset($_POST["reg_sub"])){
            $database = new PDO("mysql:host=127.0.0.1:8888; dbname=qshop;", config("dbenv.dbuname"), config("dbenv.dbpass"));

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
                return "coorect";
            }
    }
}
}