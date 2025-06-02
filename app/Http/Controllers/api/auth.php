<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use PDO;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Routing\Controller as BaseController;

class auth extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    protected static $database;
       
      private $JWTS;
    
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

            $this->JWTS = config('services.JWT.key'); // JWT secret
        }


        public function login(Request $request){
            if($request->json("email") and $request->json("password")){                
                $user = self::$database->prepare("SELECT * FROM users where email = :email");
                $user->bindValue("email",$request->json("email"));

                if($user->execute()){
                    if($user -> rowCount() == 0){
                        return response()->json(["Faild" => "email or password is incorrect!"]);
                    }

                    $user = $user->fetchObject();
                    if (Hash::check($request->json("password"), $user->password) and $user->isactive == 1) {
                        $payload = [
                            'sub' => $user->ID,
                            'email' => $user->email,
                            'firstName' => $user->First_Name,
                            'lastName' => $user->Last_Name,
                            'role' => $user->roles,
                            'birthDate' => $user->birthdate,  
                            'iat' => time(),        
                            'exp' => time() + (24 * 60 * 60), // 24 hours
                        ];

                        $token = JWT::encode($payload, $this->JWTS, 'HS256');
                        return response()->json(["token" => $token]);

                    }else{
                        return response()->json(["Faild" => "email or password is incorrect!"]);
                    }
                        

                }else{
                    return response()->json(["error" => "Server error",500]);
                }

            }else{
                return response()->json(["error" => 'Missing parameter: {"email":"example@email.com","password":"your secure password"}'], 400); 
            }

        }

        public function isAuth(Request $request){
            $token = $request->bearerToken();
            if (!$token) {
                return response()->json(['error' => 'Token required! Add this to you headers: Authorization: Bearer TOKEN_STRING'], 401);
            }

            try{
                $tokenValue = JWT::decode($token, new Key($this->JWTS, 'HS256'));
                if (!isset($tokenValue->email)) {
                    return response()->json(['error' => 'You are not authorized!'], 401);
                }
                
                return response()->json("Verified!");
            }catch(\Exception $e){
                return response()->json(['error' => 'Invalid token'], 401);
            }

        }

}