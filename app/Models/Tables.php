<?php
/**
 * Created by Alfred.
 * Date: 2021/3/25
 * Email: silentwolf_wp@163.com
 */

namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class Tables extends Model
{
    protected $table = 'tables';
    public $dateFormat = 'U';
//    public $timestamps = true;
    const CREATED_AT = 'c_time';
    const UPDATED_AT = 'u_time';
    public static $typeArr = [1=>'小桌',2=>'中桌',3=>'大桌'];

    //可以注入
    public  $fillable = [
        'name',
        'num_id',
        'type',
        'is_show',
        'is_del'
    ];

    public  $messages = [
        'name.required' => '名称不能为空',
        'num_id.required' => '编号不能为空',
        'type.required' => '类型不能为空',
        'is_show.required' => '状态不能为空',
    ];
    public  $rules = [
        'name' => 'required|string|max:50|min:2',
        'num_id' => 'required',
        'type' => 'required',
        'is_show' => 'required',
    ];
    public function btAdminUser()
    {
        return $this->belongsTo('App\Models\AdminUser', 'admin_id', 'id');
    }
}