<?php
/**
 * 菜单管理
 * @filename  AdminMenuController.php
 * @author    Zhenxun Du <5552123@qq.com>
 * @date      2018/6/23 14:33
 */

namespace App\Http\Controllers\Admin;
use App\Models\AdminMenu;
use App\Services\AdminMenuService;
use Illuminate\Http\Request;

class AdminMenuController extends Controller
{
    //首页
    public function index()
    {
        $input = request()->input();
        $lists = AdminMenuService::getInstance()->getMenuList($input);
        foreach ($lists as $k => $v) {
            $lists[$k]['name'] = $v['level'] == 0 ? $v['name'] : '├─' . $v['name'];
            $lists[$k]['name'] = str_repeat("│        ", $v['level']) . $lists[$k]['name'];
        }
        return $this->view(compact('lists'));
    }

    //详情
    public function info()
    {
        $id = request('id');
        $info = AdminMenu::query()->find($id);
        $lists = AdminMenuService::getInstance()->getMenuList();
        $menus = [];
        foreach ($lists as $k => $v) {
            $name = $v['level'] == 0 ? '<b>' . $v['name'] . '</b>' : '├─' . $v['name'];
            $name = str_repeat("│        ", $v['level']) . $name;
            $menus[$v['id']] = $name;
        }
        return $this->view(compact('info', 'menus'));
    }

    //添加
    public function add()
    {
        $params = request()->except('_token');

        if (empty($params['parentid'])) {
            $params['parentid'] = 0;
        }
        $res = AdminMenu::query()->create($params);

        //创建首页时自动创建 info,add,edit,del
        if ($res->a == 'index') {
            $params['parentid'] = $res->id;
            $params['icon'] = '';
            $params['status'] = 2;

            $params['name'] = '详情';
            $params['a'] = 'info';
            AdminMenu::query()->create($params);

            $params['name'] = '添加';
            $params['a'] = 'add';
            AdminMenu::query()->create($params);

            $params['name'] = '修改';
            $params['a'] = 'edit';
            AdminMenu::query()->create($params);

            $params['name'] = '删除';
            $params['a'] = 'del';
            AdminMenu::query()->create($params);
        }
        return $this->success('添加成功', '/'. $this->menuInfo['c'] .'/index');
    }
    //修改
    public function edit()
    {
        $params = request()->except('_token');
        if ($params['parentid'] === null) {
            $params['parentid'] = 0;
        }
        $rs = AdminMenu::query()->where('id', request('id'))->update($params);
        if ($rs) {
            return $this->success('修改成功', '/' . $this->menuInfo['c'] . '/index');
        } else {
            return $this->error();
        }
    }


    //删除
    public function del()
    {
        $id = request('id');
        AdminMenu::query()->where('id', $id)->delete();
        AdminMenu::query()->where('parentid', $id)->delete();
        return $this->success();
    }

    //排序
    public function setListorder()
    {
        $data = request('listorder');
        foreach ($data as $k => $v) {
            AdminMenu::query()->where('id', $k)->update(['listorder' => $v]);
        }
        return $this->success();
    }
}
