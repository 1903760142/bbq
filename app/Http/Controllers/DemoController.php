<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DemoController extends Controller
{
    public function demoIndex()
    {
        return view('demoindex');
    }
    public function domeadd(Request $request){
        $data = $request->all();

        unset($data['_token']);
//                dd($data);
        $file = $request->file('file');
        //定义允许上传的文件类型
        $allow = ['jpg','png','gif'];
        if ($request->hasFile('file') && $file->isValid()) {
            //获取文件的后缀名
            $ext =  $file->getClientOriginalExtension();
//            dd($ext);
            if(in_array($ext,$allow)){
                //获取当前文件的位置
                $path = $file->getRealPath();
                //echo $path;die;
                //生成新文件的文件名
                $newfilename = date("Ymd")."/".$request->book_name.mt_rand(10000,99999).'.'.$ext;
                //$dirpath = ".\\uploads\\".date("ymd");
                //$file->move($dirpath,$newfilename);
                $data['file'] = $newfilename;
                $re =  Storage::disk('uploads')->put($newfilename, file_get_contents($path));
                if($re){
                    echo '上传成功';
                }else{
                    exit("上传失败，请重新上传");
                }

            }else{
                exit("文件类型不合法，请重新检查");
            }
        }else{
            exit("上传文件错误，请重新检查");
        }


    }


}
