<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class JwtController extends Controller
{
    /*
	* 签发jwt token 
	* @params $public 需要添加的数据
	*/ 
	public static function jwtToken($public=[],$alg="HS256"){
		$now = time();
		$token = array(
            "iss" => env("ISS"),
            "aud" => env("AUD"),
            "exp"=>  env("EXP")
		);
        $token = encrypt(array_merge($public,$token));
		$token = JWT::encode($token, env("JWT_TOKEN"),$alg);
		return $token;
	}

	/*
	* 验证jwt token
	*/
	public static function verifyJwt($token,$alg="HS256"){

		try{
            $decoded = JWT::decode($token, C("user_key"), [$alg]);
            $user = decrypt($decoded);            
		}catch(\Exception $e){
			$decoded = ['error'=>$e->getMessage()];
		}
		return $decoded;
	}
}
