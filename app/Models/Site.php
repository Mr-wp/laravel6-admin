<?php

namespace App\Models;

/**
 * 站点设置
 * @filename   Site.php
 * @author    Zhenxun Du <5552123@qq.com>
 * @date      2018/6/24 16:48
 */
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    protected $table = 'site';
    public $dateFormat = 'U';
    public $timestamps = true;
    protected $guarded = []; //不可以注入
    //public $fillable = []; //仅可注入
    public static $alertTypeArr = ['weixin' => '微信', 'email' => '邮件'];//报警类型

    public static $queueArr = [
        'apim:high'=>'高',
        'apim:middle'=>'中',
        'apim:low'=>'低',
        '0'=>'禁用'
    ];
}
