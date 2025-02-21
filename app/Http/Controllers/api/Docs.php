<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Docs extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    function main(Request $request){
        
        if($request->isMethod("get")){
            $data = ["message"=>"Welcom to Q-stroe api!"];
            return response()->json($data);
        }else{
            return response()->json(["ERROR"=>"Pleas use GET!"],405);
        }

    }

}