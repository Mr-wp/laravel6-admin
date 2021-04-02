<?php

/**
 * 定时任务
 * @filename  CrontabController
 * @author    Zhenxun Du <5552123@qq.com>
 * @date      2018-06-25 17:43:47
 */

namespace App\Http\Controllers\Admin;


use App\Models\Crontab;
use App\Services\CrontabService;

class CrontabController extends Controller
{
    //列表
    public function index()
    {
        $where = [];
        if (request('status')) {
            $where[] = ['status', request('status')];
        }
        $lists = Crontab::query()->where($where)->orderBy('id', 'desc')->paginate(20);
        $lists->load('btAdminUser');
        return $this->view(compact('lists'));

    }

    //详情
    public function info()
    {
        $info = Crontab::query()->find(request('id'));
        return $this->view(compact('info'));
    }

    //添加
    public function add()
    {
        if ($this->storage()) {
            return $this->success('添加成功', '/' . $this->menuInfo['c'] . '/index');
        } else {
            return $this->error();
        }
    }

    //修改
    public function edit()
    {
        if ($this->storage()) {
            return $this->success('修改成功', '/' . $this->menuInfo['c'] . '/index');
        } else {
            return $this->error();
        }
    }

    //存储
    public function storage()
    {
        $crontabM = new Crontab();
        $this->validate(request(), $crontabM->rules, $crontabM->messages);
        $params = request($crontabM->fillable); //可以添加或修改的参数
        if (request('id')) {
            $rs = Crontab::query()->where('id', request('id'))->update($params);
        } else {
            $params['admin_id'] = $this->loginUser['id'];
            $rs = Crontab::query()->create($params);
        }
        CrontabService::getInstance()->setCrontab();
        return $rs;
    }

    // 状态禁
    public function status()
    {
        $info = Crontab::query()->find(request('id'));
        if (!$info) {
            return $this->error('找不到这条信息');
        }
        $info->status = $info->status == 1 ? 2 : 1;
        $info->save();
        CrontabService::getInstance()->setCrontab();
        return $this->success();
    }

    //删除
    public function del()
    {
        Crontab::query()->where('id', request('id'))->delete();
        CrontabService::getInstance()->setCrontab();
        return $this->success();
    }


}
