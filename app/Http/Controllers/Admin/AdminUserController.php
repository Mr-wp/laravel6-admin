<?php

/**
 * 管理员
 * @filename  AdminGroupController
 * @author    Zhenxun Du <5552123@qq.com>
 * @date      2018-6-24 18:20:12
 * @version   SVN:$Id:$
 */

namespace App\Http\Controllers\Admin;

use App\Models\AdminGroup;
use App\Models\AdminGroupUser;
use App\Models\AdminUser;
use App\Services\AdminUserService;

class AdminUserController extends Controller
{

    public $M;

    public function __construct()
    {
        parent::__construct();
        $this->M = new AdminUser();

    }


    //列表
    public function index()
    {
        $params = request()->except('_token');
        $lists = AdminUserService::getInstance()->adminUserLists($params);
        return $this->view(compact('lists'));
    }

    //详情
    public function info()
    {
        if (request('id')) {
            $info = AdminUser::query()->find(request('id'));
            $groups = AdminUserService::getInstance()->getAdminGroupUser($info->id, 'admin_id');
            if ($groups) {
                $info['group_ids'] = implode(',', array_column($groups, 'id'));
            }
            $data['info'] = $info;
        }
        $data['admin_group'] = AdminGroup::query()->pluck('name', 'id')->toArray();
        return $this->view($data);
    }

    //添加
    public function add()
    {
        $this->validate(request(), [
            'name' => 'required|min:3|max:20',
            'password' => 'required|min:1|max:20',
            'mobile' => 'required',
            'realname' => 'required',
            'email' => 'required',
        ]);
        $id = AdminUser::query()->where('name', request('name'))->value('id');
        if ($id) {
            return $this->error('用户名已存在');
        }
        //保存
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

    /*
     * 存储
     */
    private function storage()
    {
        //TODO 处理自己的字段校验格式

        $params = request(['name', 'mobile', 'password', 'realname', 'status', 'email']);
        $query = AdminUser::query();
        if(!empty(request()->file('head_img'))){
            $path = request()->file('head_img')->store('avatars','qiniu');
            $params['head_img'] = "http://cdn.findwp.cn/".$path;
        }
        if (request('id')) {
            //修改
            $adminId = request('id');
            $rs = $query->where('id', $adminId)->update($params);
        } else {
            //添加
            $params['password'] = bcrypt($params['password']);
            $rs = AdminUser::query()->create($params);
            $adminId = $rs['id'];
        }

        //用户分组
        $query = AdminGroupUser::query();
        $query->where('admin_id', $adminId)->delete();

        //添加新账号对应分组
        $groupIds = request('group_id');
        if (!empty($groupIds)) {
            foreach ($groupIds as $groupId) {
                $query->create(['admin_id' => $adminId, 'group_id' => $groupId]);
            }
        }
        return $rs;
    }


    //修改密码
    public function changePwd()
    {
        $adminId = request('id');
        $info = AdminUser::query()->find($adminId);
        if (!$info) {
            return $this->error('非法请求');
        }
        if (request('password')) {
            $this->validate(request(),
                [
                    'password' => 'required|min:3|max:20|confirmed'
                ],
                [
                    'password.required' => '密码不能为空',
                    'password.confirmed' => '密码与确认密码不一致'
                ]
            );

            AdminUser::query()->where('id', $adminId)->update(['password' => bcrypt(request('password'))]);

            return $this->success('修改成功', '/' . $this->menuInfo['c'] . '/index');
        } else {
            return $this->view(compact('info'));
        }
    }

    public function del()
    {
        $id = request('id');
        if (!empty($id)) {
            AdminUser::query()->where('id', $id)->delete();
        }
        return $this->success('操作成功');
    }
}
