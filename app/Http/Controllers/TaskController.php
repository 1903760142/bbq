<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\TaskModel;
use App\Http\Resources\TaskCollection;
use Illuminate\Support\Facades\Validator;


class TaskController extends Controller
{
    //restful 
    /**
     * index GET /task  获取所有的内容
     * store PO
     */
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $token = auto()->tokenByid(1);
        dd($token);
        $task = new TaskModel();
        $res =  $task->with("user")->find(9)->toArray();
        // dd($res);
        return TaskCollection::collection($res);
        
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    // public function create()
    // {
    //     //
    // }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());
        //验证
        $rule = ["text"=>"required","is_completed"=>"required","user_id"=>"required"];
        $message = ["text.required"=>"部位空","is_completed.required"=>"不为空","user_id.required"=>"不为空"];
        $validate = Validator::make($request->all(),$rule,$message);
        if($validate->fails()){
           var_dump($validate->errors()->first());
        }
        //新增
        $text = $_POST['text'];
        $is_completed = $_POST['is_completed'];
        $user_id = $_POST['user_id'];
        $data=[
            "text"=>$text,
            "is_completed"=>$is_completed,
            "user_id"=>$user_id
        ];
        $task = new TaskModel();
        return $task->createTask($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return new \App\Http\Resources\Task(TaskModel::with(['user' => function($query) {
            $query->select('id', 'username');
        }])->find($id));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function edit($id)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        
        $res = 
        $text = isset($request->text) ? $request->text : "";
        $is_completed = isset($request->is_completed) ? $request->is_completed : "";
        $user_id = isset($request->user_id) ? $request->user_id : "";
        
        $data=[
            "text"
        ];
        return TaskModel::where()->update(['id'=>$id]);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $id = explode(",",$id);
        //判断 如果没有$Id 的话 删除全部
        if(empty($id)){
            return TaskModel::delete();
        }else{
            return TaskModel::whereIn("id",$id)->delete();
        }
    }
}
