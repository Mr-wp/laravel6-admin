<?php
/**
 * 后台总控制器
 * @filename  Controllers
 * @author    Zhenxun Du <5552123@qq.com>
 * @date      2017-8-26 21:55:17
 * @version   SVN:$Id:$
 */

namespace App\Http\Controllers\Admin;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Models\AdminLog;
use App\Models\AdminMenu;
use App\Services\AdminMenuService;
use App\Services\SiteService;
use App\Library\Jump;

class Controller extends BaseController
{
    use AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests,
        Jump;

    protected $loginUser;
    protected $menuInfo;//菜单详情


    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $userInfoSession = $request->session()->get('login_user');
            if (!$userInfoSession) {
                return redirect('admin/index/index');
            }

            $this->loginUser = json_decode($userInfoSession, true);
            //$this->loginUser['setting'] = json_decode($this->loginUser['setting'], true);

            //当前请求菜单详情
            list($m, $c, $a) = explode('/', trim($request->path(), '/'));
            $menuInfo = AdminMenu::query()->where([['m',$m],['c',$c],['a',$a]])->first();
            if (!$menuInfo) {
                return $this->error('请求地址不存在,请添加!');
            }
            $this->menuInfo = $menuInfo->toArray();

            //权限检测
            if (!$this->_checkRole()) {
                return $this->error('没有权限');
            }
            //日志记录
            $this->_saveLog($request);

            return $next($request);
        });
    }

    /**
     * 权限检测
     * @return bool
     */
    private function _checkRole()
    {
        //除public开头的方法或者非超级管理员 都需要检测
        if (preg_match('/^public/', $this->menuInfo['a']) || $this->loginUser['is_super'] == 1) {
            return true;
        }
        if (!in_array($this->menuInfo['id'], array_column($this->loginUser['menu_list'], 'id'))) {
            return false;
        }
        return true;
    }

    /**
     * 写日志
     * @param Request $request
     */
    private function _saveLog($request)
    {
        if (empty($this->menuInfo) || $this->menuInfo['write_log'] != 1) {
            return;
        }

        $data = [];
        $data['querystring'] = $request->getQueryString();
        $data['admin_id'] = $this->loginUser['id'];
        $data['ip'] = $request->ip();
        if ($request->method() == 'POST') {
            $data['data'] = json_encode($request->except('_token'));
            if (isset($request->post()['id'])) {
                $data['primary_id'] = request()->post()['id'];
            }
            if (isset($request->post()['info']['id'])) {
                $data['primary_id'] = $request->post()['info']['id'];
            }
        }
        $data['admin_menu_id'] = $this->menuInfo['id'];
        AdminLog::query()->create($data);
    }

    //返回视图
    protected function view($data = [], $tpl = '')
    {
        $data = $data ? $data : [];
        $data['login_user'] = $this->loginUser;
        $data['menu_info'] = $this->menuInfo;
        $data['site'] = SiteService::getInstance()->getInfo();
        if($this->loginUser['is_super']==1){
            $menus = AdminMenuService::getInstance()->getUserMenuList($this->loginUser['id'], 1);
        }else{
            $menus = $this->loginUser['menu_list'];
        }
        $data['my_menu_html'] = AdminMenuService::getInstance()->myMenuHtml($menus);
        //默认模板文件
        if(empty($tpl)){
            $tpl=$this->menuInfo['m'].'/'.$this->menuInfo['c'].'/'.$this->menuInfo['a'];
        }
        return view($tpl, $data);
    }

}
