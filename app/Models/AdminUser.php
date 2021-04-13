<?php

namespace App\Models;

/**
 * 用户
 * @filename   AdminUser.php
 * @author    Zhenxun Du <5552123@qq.com>
 * @date      2018/6/24 16:48
 */
use Illuminate\Database\Eloquent\Model;

class AdminUser extends Model
{
    protected $connection = 'mysql';
    protected $primaryKey = 'id';
    protected $table = 'admin_user';
    public $dateFormat = 'U';
    public $timestamps = true;
    protected $guarded = [];

    public static $statusArr = [1 => '正常', 2 => '禁用'];
    public static $typeArr = [1 => '外部账号', 2 => '域账号'];

    public function getBelongToAttribute(){
        return $this->attributes['belong_to'] = $this->attributes['belong_to']==0?'系统用户':$this->attributes['belong_to'];
    }
//    public function btShopUser()
//    {
//        return $this->belongsTo('App\Models\AdminUser', 'admin_id', 'id');
//    }
}
