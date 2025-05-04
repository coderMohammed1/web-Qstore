<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use PDO;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class products extends BaseController
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

        function search(Request $request) {
            $appUrl = config('app.url').'/'; // u can change this from the .env
            if ($request->json('product')) {
                $result = self::$database->prepare(
                    "SELECT First_Name, Last_name, product.ID as pid, p_name as product_name, 
                    Manfacturer, description, quantity,CONCAT(:app_url, img) AS img 
                    FROM product 
                    JOIN users ON users.ID = seller 
                    WHERE p_name LIKE :product 
                    LIMIT 30"
                );
        
                $result->bindValue("product", "%" . $request->json('product') . "%");
                $result->bindParam("app_url",$appUrl);
        
                if ($result->execute()) {
                    return response()->json($result->fetchAll(PDO::FETCH_ASSOC));
                }
        
                return response()->json(["error" => "server error"], 500);
            }
        
            return response()->json(["error" => 'Missing parameter: {"product":"name"}'], 400);
        }
        

}