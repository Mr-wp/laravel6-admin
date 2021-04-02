<?php

/**
 * 工作记录
 * @filename  WorkInfoController
 * @author    Zhenxun Du <5552123@qq.com>
 * @date      2018-06-25 19:43:47
 * @version   SVN:$Id:$
 */

namespace App\Http\Controllers\Admin;


use App\Models\WorkInfo;

class WorkInfoController extends Controller
{
    //列表
    public function index()
    {
        $params = request()->input();
        if (!empty($params['reportrange'])) {
            $arr = explode(' - ', $params['reportrange']);
            $start_time = $arr[0] . " 0:00:00";
            $end_time = $arr[1] . " 23:59:59";
        } else {
            $start_time = date('Y-m-d H:i:s', time() - 86400*30);
            $end_time = date('Y-m-d H:i:s', time());
        }
        //条件
        $where = [];
        $where[] = ['is_delete', 0];
        if(!empty($params['admin_id'])){
            $where[] = ['admin_id', $params['admin_id']];
        }
        if (!empty($params['is_reminder'])) {
            $where[] = ['is_reminder', $params['is_reminder']];
        }
        if (!empty($params['reminder_status'])) {
            $where[] = ['reminder_status', $params['reminder_status']];
        }
        if (!empty($start_time) && !empty($end_time)) {
            $where[] = ['created_at', '>=', strtotime($start_time)];
            $where[] = ['created_at', '<=', strtotime($end_time)];
        }
        $limit = $params['limit'] ?? 20;
        $lists = WorkInfo::query()->where($where)->orderBy('id', 'desc')->paginate($limit);

        $lists->load('btAdminUser');
        return $this->view(compact('lists','start_time','end_time'));
    }

    //详情
    public function info()
    {
        $info = WorkInfo::query()->find(request('id'));
        return $this->view(compact('info'));
    }

    //添加
    protected function add()
    {
        if ($this->storage()) {
            return $this->success('添加成功', '/' . $this->menuInfo['c'] . '/index');
        } else {
            return $this->error();
        }
    }

    //修改
    protected function edit()
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
        $info = request('info');
        $info['admin_id'] = $this->loginUser['id'];
        $info['reminder_at'] = strtotime($info['reminder_at']);
        $id = request('id');
        //修改
        if ($id) {
            $info['is_reminder'] = 1; //再次提醒
            $rs = WorkInfo::query()->where('id', $id)->update($info);
        } else {
            //添加
            $rs = WorkInfo::query()->create($info);
        }
        return $rs;
    }

    public function del()
    {
        $id = request('id');
        if (!empty($id)) {
            WorkInfo::query()->where('id', $id)->delete();
        }
        return $this->success('操作成功');
    }


}
