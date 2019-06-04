<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class Rsa extends Controller
{
    private $private_key;
    private $public_key;
    private $config=["config"=>"D:\phpStudy\PHPTutorial\Apache\conf\openssl.cnf"];
    public function __construct($config=[]){
        $config = $this->config +$config;
        $res = openssl_pkey_new($config);
//        var_dump($res);die;
        openssl_pkey_export($res,$private_key,null,$config);
        $detils = openssl_pkey_get_details($res);
        $this->public_key =$detils['key'];
        $this->private_key = $private_key;
        file_put_contents("./uploads/rsa/openssl_public.pem",$detils['key']);
        file_put_contents("./uploads/rsa/openssl_private.pem",$private_key);
    }
    public function encrypt($data,$key="public"){
        if($key == "public"){
            openssl_public_encrypt($data,$crypt,$this->public_key);
        }else{
            openssl_private_encrypt($data,$crypt,$this->public_key);
        }
        return base64_encode($crypt);
    }

    public function decrypt($data,$key="private"){
        if($key == "public"){
            openssl_public_decrypt(base64_decode($data),$crypt,$this->private_key);
        }else{
            openssl_private_decrypt(base64_decode($data),$crypt,$this->private_key);
        }

        return $crypt;
    }

    public function getPrivateKey(){
        return $this->private_key;
    }
    public function getPublicKey(){
        return $this->public_key;
    }
    
    public function setPrivateKey($key){
        return $this->private_key = $key;
    }
    public function setPublicKey($key){
        return $this->public_key = $key;
    }
}
