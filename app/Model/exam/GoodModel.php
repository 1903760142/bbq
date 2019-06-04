<?php

namespace App\Model\exam;

use Illuminate\Database\Eloquent\Model;

class GoodModel extends Model
{
    /**
     * 与模型关联的数据表。
     *
     * @var string
     */
    protected $table = 'good';


    /**
     * 执行模型是否自动维护时间戳.
     *
     * @var bool
     */
    public $timestamps = false;

    protected  $primaryKey = 'good_id';
}
