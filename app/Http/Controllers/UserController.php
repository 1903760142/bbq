<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\UserModel;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Response;
use App\Http\Controllers\Api\Rsa;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //登录
        $username = $request->u_name;
        $password = $request->pwd;

        $rsa = new Rsa();
        $en = $rsa->encrypt($username);
        $de = $rsa->decrypt($en);
        var_dump($en);die;
        $user = new UserModel();
        $re  = $user->checkUsername($username);
        // dd($re['password']);
        $pwd = $re['u_pwd'];
//        var_dump($pwd);die;
        //判断密码是否正确
        if($password == decrypt($pwd)){
            //密码正确 ，登陆成功
            //获取token
            $token = array(
                "iss" => env("ISS"),
                "aud" => env("AUD"),
                "exp"=>env("EXP"),
            );
            $key = env("JWT_TOKEN");
            unset($pwd);
            $token = encrypt(array_merge($re,$token));
            $jwt = JWT::encode($token, $key);
            var_dump($jwt);
            return response('登录成功',200);
        }else{
            return response("error password",401);
        }
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


    public function demo()
    {
        echo 22111;
    }

    public function demo1()
    {
        return view('demo');
    }
}
