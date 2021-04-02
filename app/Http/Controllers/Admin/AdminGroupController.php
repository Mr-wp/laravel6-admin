<?php

/**
 * 分组
 * @filename  AdminGroupController
 * @author    Zhenxun Du <5552123@qq.com>
 * @date      2018-6-24 18:20:12
 * @version   SVN:$Id:$
 */

namespace App\Http\Controllers\Admin;

use App\Models\AdminGroup;
use App\Services\AdminMenuService;

class AdminGroupController extends Controller
{
    //列表
    public function index()
    {
        $where = [];
        $lists = AdminGroup::query()->where($where)->orderBy('id', 'desc')->paginate(20);
        return $this->view(compact('lists'));
    }

    //详情
    public function info()
    {
        $info = AdminGroup::query()->find(request('id'));
        $menu_lists = AdminMenuService::getInstance()->getMenuList();
        if (isset($info->menus) && $info->menus != null) {
            $menus_in = explode(',', $info->menus);
        } else {
            $menus_in = [];
        }
        foreach ($menu_lists as $k => $v) {
            $menus[$k] = ['id' => $v['id'], 'pId' => $v['parentid'], 'name' => $v['name'], 'open' => true];
            if (in_array($v['id'], $menus_in)) {
                $menus[$k]['checked'] = true;
            }
        }
        return $this->view(compact('info', 'menus'));
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
    private function storage()
    {
        $params = request(['name', 'description', 'menus']);
        if (request('id')) {
            $rs = AdminGroup::query()->where('id', request('id'))->update($params);
        } else {
            $rs = AdminGroup::query()->create($params);
        }
        return $rs;
    }

    //删除
    public function del()
    {
        AdminGroup::query()->where('id', request('id'))->update(['is_delete' => 1]);
        return $this->success();
    }
}
