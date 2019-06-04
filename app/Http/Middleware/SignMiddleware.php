<?php

namespace App\Http\Middleware;

use Closure;

class SignMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        //生成sign

        $shuffleStr = $_GET['shuffleStr'];
        $times = $request->times;
        $signatrue = $request->signatrue;
//        var_dump($shuffleStr);die;
        if(empty($shuffleStr) && empty($times) && empty($signatrue)){
            return response("Not null param",421);
        }
        $array = [$shuffleStr,$times,env("SIGNTOKEN")];
//        var_dump($array);die;
        sort($array,SORT_STRING);
        $str = implode($array);
//        var_dump($str);die;
        $sign = sha1($str);
//        var_dump($sign);die;
        if($sign == $signatrue){
            return $next($request);
        }else{
            return response("验证签名失败",401);
        }
    }
}
