<?php 
namespace App\Http\Controllers\Api;

trait ResponseTrait{
    public static function Response($data=null,$msg=null,$status){
        $array=[
            "data"=>$data,
            "msg"=>$msg,
            "status"=>$status,
        ];
        return response($array,$status);
    }
}