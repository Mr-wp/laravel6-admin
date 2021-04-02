<?php

namespace App\Models;

/**
 * 菜单
 * @filename   AdminMenu.php
 * @author    Zhenxun Du <5552123@qq.com>
 * @date      2018/6/24 16:48
 */
use App\Services\ApiDomainService;
use App\Services\ApiUrlService;
use Illuminate\Database\Eloquent\Model;

class AdminMenu extends Model
{
    protected $table = 'admin_menu';
    public $dateFormat = 'U';
    public $timestamps = true;
    protected $guarded = [];
    public static $messages = [
        'name.required' => '名称不能为空',
        'c.required' => '文件不能为空',
        'a.required' => '方法不能为空',
        'status.required' => '状态不能为空'
    ];
    public static $rules = [
        'name' => 'required|string|max:100|min:2',
        'c' => 'required|string',
        'a' => 'required|string',
        'status' => 'required|int',
    ];

    public static $statusArr = ['1' => '显示', '2' => '不显示'];
    public static $writeLogArr = ['1' => '记录', '2' => '不记录'];





}
