<?php
namespace App\Models;

/**
 * 操作日志
 * @filename   AdminLog.php
 * @author    Zhenxun Du <5552123@qq.com>
 * @date      2018/6/24 16:48
 */
use Illuminate\Database\Eloquent\Model;

class AdminLog extends Model
{
    protected $table = 'admin_log';
    public $dateFormat = 'U';
    public $timestamps = true;
    protected $guarded = []; //不可以注入
    //public $fillable = []; //仅可注入


}
