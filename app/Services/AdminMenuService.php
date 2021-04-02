<?php

namespace App\Services;

use App\Library\Common;
use App\Models\AdminGroupUser;
use App\Models\AdminMenu;

class AdminMenuService extends Service
{


    /**
     * 所有操作菜单
     * @param array $input
     * @return array
     */
    public function getMenuList(array $input=[])
    {
        $where = [];
        if (!empty($input['status'])) {
            $where[] = ['status', $input['status']];
        }
        if (!empty($input['write_log'])) {
            $where[] = ['write_log', $input['write_log']];
        }
        if (!empty($input['name'])) {
            $where[] = ['name', 'like', '%' . $input['name'] . '%'];
        }
        $lists = AdminMenu::query()->where($where)->orderBy('listorder', 'asc')->get()->toArray();
        $lists = Common::nodeTree($lists);

        return $lists;
    }

    //下拉框菜单选择
    public function selectMenu()
    {
        $tmpArr = $this->getMenuList();
        $data = array();
        foreach ($tmpArr as $k => $v) {
            $name = $v['level'] == 0 ? '<b>' . $v['name'] . '</b>' : '├─' . $v['name'];
            $name = str_repeat("│        ", $v['level']) . $name;
            $data[$v['id']] = $name;
        }
        return $data;
    }

    /**
     * 我的菜单
     * @param int $status 状态 1 只查显示,0所有
     * @return array|bool
     */
    public function myMenu($status = 1)
    {
        $where = array();
        if ($status == 1) {
            $where[] = ['status', '=', 1];
        }
        $loginUser = json_decode(request()->session()->get('login_user'), true);
        //查看此人是否超级管理员组,如果是返回所有权限
        if ($loginUser['is_super'] == 1) {
            //超级管理员
            $menus = AdminMenu::query()->where($where)->orderBy('listorder', 'asc')->get()->toArray();
        } else {
            //查出用户所在组Id拥有的menus
            //select menus from erp_admin_group_access t1 left join erp_admin_group t2 on t1.group_id=t2.id where t1.admin_id=11
            $menu_arr = AdminGroupUser::query()
                ->froM('admin_group_user as t1')
                ->leftJoin('admin_group as t2', 't1.group_id', '=', 't2.id')
                ->where('t1.admin_id', $loginUser['id'])
                ->pluck('menus')->toArray();
            $menu_ids = array();
            foreach ($menu_arr as $k => $v) {
                if ($v) {
                    $menu_ids = array_unique(array_merge($menu_ids, explode(',', $v)));
                }
            }

            //菜单大于0查出
            if (count($menu_ids) > 0) {
                $menus = AdminMenu::query()->where($where)->wherein('id', $menu_ids)->orderBy('listorder', 'asc')->get()->toArray();
            } else {
                return false;
            }

        }

        return $menus;
    }

    /**
     * 用户菜单
     * @param $adminUserId
     * @param int $isSuper
     * @param int $status 状态 1 只查显示,0所有
     * @return array|bool
     */
    public function getUserMenuList($adminUserId, $isSuper = 0, $status = 0)
    {
        $where = [];
        if ($status == 1) {
            $where[] = ['status', '=', 1];
        }
        //查看此人是否超级管理员组,如果是返回所有权限
        if ($isSuper == 1) {
            //超级管理员
            $menus = AdminMenu::query()->where($where)->orderBy('listorder', 'asc')->get()->toArray();
        } else {
            //查出用户所在组Id拥有的menus
            //select menus from erp_admin_group_access t1 left join erp_admin_group t2 on t1.group_id=t2.id where t1.admin_id=11
            $menu_arr = AdminGroupUser::query()
                ->from('admin_group_user as t1')
                ->leftJoin('admin_group as t2', 't1.group_id', '=', 't2.id')
                ->where('t1.admin_id', $adminUserId)
                ->pluck('menus')->toArray();
            $menu_ids = array();
            foreach ($menu_arr as $k => $v) {
                if ($v) {
                    $menu_ids = array_unique(array_merge($menu_ids, explode(',', $v)));
                }
            }

            //菜单大于0查出
            if (count($menu_ids) > 0) {
                $menus = AdminMenu::query()->where($where)->wherein('id', $menu_ids)->orderBy('listorder', 'asc')->get()->toArray();
            } else {
                return [];
            }

        }

        return $menus;
    }

    /**
     * 我的菜单返回html
     * @param array $menuListAll
     * @return string
     */
    public function myMenuHtml(array $menuListAll)
    {
        $menuList=[];
        foreach ($menuListAll as $k => $v) {
            if ($v['status'] == 1) {
                $menuList[] = $v;
            }
        }
        //排序
        $menuList = Common::sortToArray($menuList, 'listorder');
        $menuTree = Common::listToTree($menuList);

        $html = '<ul class="nav nav-list">';
        $html .= $this->menu_tree($menuTree);
        $html .= "
                </ul>";
        return $html;
    }

    private function menu_tree($tree)
    {
        $html = '';
        if (is_array($tree)) {
            foreach ($tree as $val) {
                if (isset($val["name"])) {
                    $title = $val["name"];
                    $url = '/' . $val['m'] . '/' . $val['c'] . '/' . $val['a'];
                    $val['data'] ? $url .= '?' . $val['data'] : '';
                    if (empty($val["id"])) {
                        $id = $val["name"];
                    } else {
                        $id = $val["id"];
                    }
                    if (empty($val['icon'])) {
                        $icon = "fa-caret-right";
                    } else {
                        $icon = $val['icon'];
                    }
                    $pathinfo = explode('?', $_SERVER['REQUEST_URI'])[0];

                    if ($url == $pathinfo) {
                        $active = 'active ';
                    } else {
                        $active = '';
                    }

                    if (isset($val['_child'])) {
                        if ($val['id'] == 193) {
                            $open = 'open';
                        } else {
                            $open = '';
                        }
                        $html .= '
                            <li class="' . $open . '">
                            <a href="' . $url . '" class="dropdown-toggle">
                                <i class="menu-icon fa ' . $icon . '"></i>
                                <span class="menu-text"> ' . $title . ' </span>
                                <b class="arrow fa fa-angle-down"></b>
                            </a>
                            <b class="arrow"></b>
                            <ul class="submenu">
                            ';
                        $html .= $this->menu_tree($val['_child']);
                        $html .= '
                            </ul>
                        </li>
                        ';
                    } else {
                        $html .= '
                    <li class = "' . $active . '">
                    <a href = "' . $url . '">
                    <i class = "menu-icon fa ' . $icon . '"></i>
                    <span class = "menu-text"> ' . $title . ' </span>
                    </a>
                    <b class = "arrow"></b>
                    </li>
                    ';
                    }
                }
            }
        }
        return $html;
    }

}
