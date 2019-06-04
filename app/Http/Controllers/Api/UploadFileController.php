<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UploadFileController extends Controller
{
    /**
     * 
     */
    private $config=[
        //允许上传的MIME类型
        "allowMime"=>["image/jpg","image/png","image/jpeg","image/gif"],
        //允许上传的后缀名
        "allowExt"=>[".jpg",".jpeg",".png",".gif",".JPEG",".JPG",".PNG",".GIF"],
        //允许上传的文件大小
        "maxSize"=>0,
        //需要返回的路径
        "uoloadsPath"=>"./uploads/",
        //是否创建新的文件名
        "isChangeFileName"=>true,
        //是否用base64二进制流编码的格式
        "binaryStream"=>false
    ];
    private $error ="";
    private $errorNum=0;
    public function __construct($config=[]){
        $this->config = array_merge($this->config,$config);
    }
    //单个文件上传
    public function upload($name=""){
        //判断是不是二进制流
        if($this->config['binaryStream'] == true){
            if(is_array($_POST[$name]['name'])){
                $infos =[];
                foreach($_POST[$name] as $k=>$v){
                    $info  =$this->streamSaveFile($_POST[$name]);
                    if(!$info){
                        return false;
                    }
                    $infos[]=$info;
                }
                return $infos;
            }
            return  $this->streamSaveFile($_POST['file']);
        }
        if($name != ""){
            $file = $_FILES[$name];
            if(is_array($_FILES[$name]['name'])){
                $info =$this->uploads($file);
            }else{
                $info = $this->uploadfile($file);
            }
        }else{
            $value = array_values($_FILES);
            $file = $value[0];
            if(is_array($file['name'])){
                $info =$this->uploads($file);
            }else{
                //单文件上传
                $info = $this->uploadfile($file);
            }
        }

        if(!$info){
            return false;
        }else{
            return $info;
        }
    }
    //多文件上传
    public function uploads($file){
        // var_dump($file);
        $infos=[];
        foreach($file['name'] as $key=>$value){
            $name = $value;
            $type = $file['type'][$key];
            $error = $file['error'][$key];
            $size = $file['size'][$key];
            $tmp_name = $file['tmp_name'][$key];
            $files  = ["name"=>$name,"type"=>$type,"error"=>$error,"size"=>$size,"tmp_name"=>$tmp_name];
            
            $info =$this->uploadfile($files);
            if(!$info){
                return false;
            }
            $infos[]=$info;
        }
        return $infos;
    }
    //二进制流处理
    public function streamSaveFile($filename){
        //解码内容
        $file = base64_decode($filename['file_content']);
        //申城新的文件名 和路径
        $newFileName = $this->newFile($filename['name']);
        $savePath = $this->config['uoloadsPath'].DIRECTORY_SEPARATOR.$newFileName;
        //判断文件上传是否上传成功，成功返回该有的信息，失败返回false
        if( false   ===   file_put_contents($savePath,$file) ){
            $this->errorNum= 9;
            return false;
        }
        $info =[
            "newfilename"=>$newFileName,
            "path"=>$this->config['uoloadsPath'],
            "filePath"=>$savePath,
            "size"=>strlen($file),
            "name"=>$filename['name']
        ];
        return $info;
    }
    public function uploadfile($file){
        // dd($file);
        //1，判断文件错误信息
        $this->checkError($file['error']);
        //2，判断文件类型和后缀名是否是我们规定的
        if( !$this->checkMime($file['type']) || !$this->checkExt($file['name']) ){
            return false;
        }
        //3，判断大小是否符合规定
        if(!$this->checkSize($file['size'])){
            echo 3;
            return false;
        };
        //4，判断路径是否正确，不正确创建他，
        $this->checkPath($this->config['uoloadsPath']);
        //5，生成新的文件名
        $newFileName = $this->newFile($file['name']);
        $savePath = $this->config['uoloadsPath'].DIRECTORY_SEPARATOR.$newFileName;
        //6，move_uploads_file移动文件到指定目录
        if(is_uploaded_file($file['tmp_name'])  && move_uploaded_file($file['tmp_name'],$savePath)){
            //7，判断文件上传是否上传成功，成功返回该有的信息，失败返回false
            $info =[
                "newfilename"=>$newFileName,
                "path"=>$this->config['uoloadsPath'],
                "filePath"=>$savePath
            ];

            return $info;
        }
        $this->errorNum= 9;
        return false;

    }
    //生成新文件名
    public function newFile($filename){
        if($this->config['isChangeFileName']){
            $newFileName = uniqid(date("Ymd")).$this->getExt($filename);
            // dd($newFileName);
        }
        return  $newFileName;
    }
    //判断文件是否存在  ,没有的话创建他
    public function checkPath($path){
        if(!is_dir($path) && !file_exists($path)){
            mkdir($path,0777,true);
        }
    }
    //判断文件大小
    public function checkSize($size){
        if(!$this->config['maxSize']){
            return true;
        }else if($size > $this->config['maxSize']){
            $this->errorNum= 10;
            return false;
        }

        return true;
    }
    public function checkMime($type){
        // echo $type."\n";
        // var_dump( $this->config['allowMime']  )."\n";
        // var_dump(in_array($type,$this->config['allowMime']))."\n";
        if(in_array($type,$this->config['allowMime'])){
            return true;
        }
        $this->errorNum= 11;
        return false;
    }
    public function checkExt($filename){
        $ext = $this->getExt($filename);
        if(in_array($ext,$this->config['allowExt'])){
            return true;
        }
        $this->errorNum = 12;   
        return false;
    }
    public function getExt($filename){
        $ext = ".".pathinfo($filename, PATHINFO_EXTENSION );
        return $ext;
    }
    //错误信息
    public function checkError($errorNum=""){
        $errorNum !="" && $this->errorNum=$errorNum;
        switch($this->errorNum){
            case UPLOAD_ERR_INI_SIZE:
                $this->error="上传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值。 ";
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $this->error="上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值";
                break;
            case UPLOAD_ERR_PARTIAL:
                $this->error="文件只有部分被上传";
                break;
            case UPLOAD_ERR_NO_FILE:
                $this->error="没有文件被上传";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $this->error="找不到临时文件夹。PHP 4.3.10 和 PHP 5.0.3 引进";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $this->error="文件写入失败";
                break;
            case 9:
                $this->error="文件上传失败";
                break;    
            case 10:
                $this->error="文件超过规定大小";
                break;    
            case 11:
                $this->error="上传文件类型不正确";
                break;    
            case 12:
                $this->error="上传文件后缀名不符合要求";
                break;    
            case 0:
                $this->error="";
                break;    
            default:
               $this->error="未知错误";
        }
        return $this->error;
    }
}
