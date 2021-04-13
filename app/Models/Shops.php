<?php
/**
 * Created by Alfred.
 * Date: 2021/3/25
 * Email: silentwolf_wp@163.com
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Shops extends Model
{
    protected $table = 'shops';
    public $dateFormat = 'U';
    public static $types = [1=>'火锅',2=>'串串',3=>'自助',4=>'饭店'];//店铺类型
    public static $verifyStatus = [1=>'未提交',2=>'已提交',3=>'审核中',4=>'已通过',5=>'已拒绝'];//审核状态
    public static $payMethods = [1=>'按日计费',2=>'按周计费',3=>'按月计费',4=>'按季计费',5=>'按年计费'];//计费方式
    public static $status = [0=>'启用',1=>'停用'];//使用状态

    //可以注入
    public  $fillable = [
        'name','owner_name','owner_phone','contact_name','contact_phone','address','address_detail','type','auth_imgs','status','pay_method','ali_appid','ali_private_key','ali_public_key','wx_mch_id','wx_appid','wx_secret'
    ];

    public  $messages = [
        'name.required' => '店铺名称不能为空',
        'owner_name.required' => '持有人不能为空',
        'owner_phone.required' => '持有人电话不能为空',
        'address.required' => '地区不能为空',
    ];
    public  $rules = [
        'name' => 'required|string|max:50|min:2',
        'owner_name' => 'required',
        'owner_phone' => 'required',
        'address' => 'required',
    ];
    public function btAdminUser()
    {
        return $this->belongsTo('App\Models\AdminUser', 'admin_id', 'id');
    }
}