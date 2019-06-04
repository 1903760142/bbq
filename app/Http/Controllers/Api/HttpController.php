<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HttpController extends Controller
{
    /*
	* 缺少HTTPS请求的curl方法
	*/

	/*
	* post 发送post请求
	* @param $url string 请求地址
	* @param $data array 请求参数
	* @return string
	*/
	public static function postHttp($url,$data=[],$option=[],$multi=false){
		$options=[];
		if(self::getSchema($url)){
			$options[CURLOPT_SSL_VERIFYPEER]= false;
			$options[CURLOPT_SSL_VERIFYHOST]= false;
		}
		$data = $multi ? $data : http_build_query($data);
		$options=[
			CURLOPT_URL=> $url,
			CURLOPT_POST =>  true,
			CURLOPT_POSTFIELDS => $data
		];
		$options = $options + $option;
		
		return self::doHttp($options);
	}
	/*
	* get 发送get请求
	* @param $url string 请求地址
	* @return string
	*/
	public static  function getHttp($url,$option=[]){
		$options =[];
		$options=[
			CURLOPT_URL=> $url,
		];
		$options = $options + $option;
		return self::doHttp($options);
	}

	/*
	* do curl发起http请求
	* @param $options array curl参数
	* @return string
	*/
	private static  function doHttp($options=[]){
		$defaultOptions = [
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_FOLLOWLOCATION => true
		];

		$options = $defaultOptions + $options;
		$ch = curl_init();
		curl_setopt_array($ch, $options);
		$res = curl_exec($ch);
		if (!$res){
			return curl_error($ch);
		}
		return $res;
	}
}
