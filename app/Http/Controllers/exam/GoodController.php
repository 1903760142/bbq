<?php

namespace App\Http\Controllers\exam;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Model\exam\GoodModel;
class GoodController extends Controller
{
    //添加商品
    public function GoodsAdd()
    {

        $goods_name = isset($_POST['good_name'])?$_POST['good_name']:'';
        $goods_price = isset($_POST['good_price'])?$_POST['good_price']:'';
        $goods_num = isset($_POST['good_num'])?$_POST['good_num']:'';
        $goods_pic = isset($_POST['good_pic'])?$_POST['good_pic']:'';
        if(empty($goods_name) || empty($goods_num) || empty($goods_pic) || empty($goods_price)){
            return Response('必要参数缺失');
        }else{
            $data = [
                'good_name' => $goods_name,
                'good_price' => $goods_price,
                'good_num' => $goods_num,
                'good_pic' => $goods_pic,
            ];
            $re = GoodModel::insert($data);
            if($re){
                return Response('添加成功');
            }else{
                return Response('未知错误');
            }
        }

    }
    //商品查询
    public function GoodsQuery()
    {
        $goods_name = isset($_POST['good_name'])?$_POST['good_name']:'';
        if(empty($goods_name)){
            return Response('查询条件为空');
        }else{
            $data = GoodModel::where('good_name','like',$goods_name)->first();
            if($data){
//                var_dump($data);
                $goods = '商品名称 : '.$data['good_name'].'    商品价格 : '.$data['good_price'].'    商品数量 : '.$data['good_num'];
                return Response($goods);
            }else
                return Response('暂无该商品数据');{
            }
        }
    }
    //商品删除
    public function GoodsDel()
    {
        $goods_name = isset($_POST['good_name'])?$_POST['good_name']:'';
        if(empty($goods_name)){
            return Response('缺少必要参数');
        }else{
            $data = GoodModel::where('good_name',$goods_name)->first();
            if(empty($data)){
                return Response('暂无商品数据');
            }else{
                $good_id = $data['good_id'];
                $re = GoodModel::destroy($good_id);
                if($re){
                    return Response('删除成功');
                }else{
                    return Response('未知错误');
                }
            }
        }
    }
    //商品修改
    public function GoodsEdit()
    {
        $name = isset($_POST['name'])?$_POST['name']:'';
        $goods_name = isset($_POST['good_name'])?$_POST['good_name']:'';
        $goods_price = isset($_POST['good_price'])?$_POST['good_price']:'';
        $goods_num = isset($_POST['good_num'])?$_POST['good_num']:'';
        $goods_pic = isset($_POST['good_pic'])?$_POST['good_pic']:'';
        if(empty($goods_name) || empty($goods_num) || empty($goods_pic) || empty($goods_price)){
            return Response('修改条件不足');
        }else{
            $goods = [
                'good_name' => $goods_name,
                'good_price' => $goods_price,
                'good_num' => $goods_num,
                'good_pic' => $goods_pic,
            ];
            $data = GoodModel::where('good_name',$name)->first();
            $good_id = $data['good_id'];
            $re = GoodModel::where('good_id',$good_id)->update($goods);
            if($re){
                return Response('修改成功');
            }else{
                return Response('未知错误');
            }
        }
    }
}
