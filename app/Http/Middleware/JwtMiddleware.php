<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\JWT;
use Symfony\Component\HttpFoundation\Session\Session;

class JwtMiddleware
{
    const KEY = 'hello word';
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(isset($_SERVER['HTTP_AUTHORIZATION'])){
            $token = isset($_SERVER['HTTP_AUTHORIZATION']) ? trim($_SERVER['HTTP_AUTHORIZATION']) :"";
        }else if(isset($_POST['access_token'])){
            $token = isset($_POST['access_token']) ? trim($_POST['access_token']) :"";
        }else if(isset($_GET['access_token'])){
            $token = isset($_GET['access_token']) ? trim($_GET['access_token']) :"";
        }else{
            return response("not token",401);
        }
        try{
            $token = decrypt($token);
            $decoded = JWT::decode($token, self::KEY, array('HS256'));
//            var_dump($decoded);die;

            //存入session
            session(["user"=>$decoded]);
//            var_dump(session("user"));die;
            if(session("user")){
                return $next($request);
            }else{
                return response("请先登录");
            }
        }catch(\Exception $e){
            return response($e->getMessage(),401);
        }


       
    }
}
