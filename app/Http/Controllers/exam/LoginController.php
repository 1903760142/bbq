<?php

namespace App\Http\Controllers\exam;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use App\Model\exam\UserModel;
use Firebase\JWT\JWT;

class LoginController extends Controller
{
    const KEY = 'hello word';
    public function LoginIndex()
    {
        return view('exam\loginindex');
    }
    public function LoginAdd()
    {
        $u_name = isset($_POST['u_name'])? $_POST['u_name']:'';
        $u_pwd = isset($_POST['u_pwd'])?$_POST['u_pwd']:'';
        if(empty($u_name) || empty($u_pwd)){
            return Response('必要参数缺失');
        }
        $data = UserModel::where('u_name',$u_name)->first();
//        var_dump($data['u_id']);die;
        if(empty($data)){
            return Response('暂无该用户信息');
        }
        if($u_pwd == $data['u_pwd']){
            //获取accessToken
            $accessToken = $this->createJwt(['u_id' => $data['u_id']]);
            $accessToken = encrypt($accessToken);
            var_dump($accessToken);
            return Response('登录成功');
        }else{
            return Response('密码错误,请重试');
        }
    }

    public function createJwt($data){
        $token = array(
            "iss" => "http://www.laravel.com",
            "aud" => "http://www.laravel.com",
            "exp"=>time()+7200
        );
        $token = $token + $data;
        $token = JWT::encode($token, self::KEY);
        return $token;
    }

}
