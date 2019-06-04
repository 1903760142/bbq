<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Auth\Access\Response;

class UserModel extends BaseModel
{
    protected $table="user";
    protected $primaryKey="u_id";

    public function Task(){
        return $this->belongsTo("App\Model\TaskModel","u_id");
    }
    public function checkUsername($username){
        //判断是否存在
//        var_dump($username);die;
        $re = $this->where(["u_name"=>$username])->first()->toArray();
       if(!$re){
        return response('Not Username', 401) ->content();
       }else{
        return $re;
       }
    }
}
