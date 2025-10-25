<?php
namespace App\Http\Controllers\api;

use Firebase\JWT\JWT;
use Illuminate\Http\Request;
use PDO;
use Firebase\JWT\Key;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;

class profile extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public static $database;
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

    function main(Request $request){
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json(['error' => 'Token required! Add this to you headers: Authorization: Bearer TOKEN_STRING'], 401);
        }

        $tokenValue = JWT::decode($token, new Key($this->JWTS, 'HS256'));
        $info = self::$database->prepare("SELECT ID,First_Name,Last_Name,roles,email,birthdate FROM users WHERE ID=:id");
        $info->bindParam("id",$tokenValue->sub);

        if($info->execute()){
            return response()->json($info->fetchAll(PDO::FETCH_ASSOC),200);
        }else{
            return response()->json(['error' => 'INTERNAL SERVER ERROR!'],500);
        }
        
    }

    function update(Request $request){
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json(['error' => 'Token required! Add this to you headers: Authorization: Bearer TOKEN_STRING'], 401);
        }

        $tokenValue = JWT::decode($token, new Key($this->JWTS, 'HS256'));

        $validatedData = $request->validate([
            'First_Name' => 'sometimes|string|max:255',
            'Last_Name' => 'sometimes|string|max:255',
            'birthdate' => 'sometimes|date',
        ]); // No Mass Assignment or SQLI with this guy!

        if (empty($validatedData)) {
            return response()->json(['error' => 'No valid data provided',"usage" => "You can only update all or some of the following:First_Name,Last_Name,birthdate"], 400);
        }

        $setParts = [];
        foreach ($validatedData as $key => $value) {
            $setParts[] = "`$key` = :$key";
        }

        $setClause = implode(", ", $setParts);
        $sql = self::$database->prepare("UPDATE users SET $setClause WHERE ID = :id");
        
        foreach ($validatedData as $key => $value) {
            $sql->bindValue(":$key", htmlspecialchars($value, ENT_QUOTES, 'UTF-8'));
        }
        $sql->bindValue("id", $tokenValue->sub);

        if ($sql->execute()) {
            return response()->json(['success' =>  implode(', ', array_keys($validatedData)).' were/was updated successfully!'], 200);
        } else {
            return response()->json(['error' => 'INTERNAL SERVER ERROR!'], 500);
        }
    }
}