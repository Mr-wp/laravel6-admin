<?php

/**
 *
 * @filename  Login
 * @author    Zhenxun Du <5552123@qq.com>
 * @date      2018-7-13 21:55:17
 * @version   SVN:$Id:$
 */

namespace App\Http\Controllers\Admin;

use App\Services\AdminUserService;
use App\Services\SiteService;
use Auth;
use Exception;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Routing\Redirector;
use Illuminate\View\View;


class IndexController extends BaseController
{

    /**
     * 后台首页
     * @return Factory|RedirectResponse|Redirector|View
     * @throws Exception
     */
    public function index()
    {
        if (session('login_user')) {
            return redirect('admin/adminHome/publicIndex');
        } else {
            $info = SiteService::getInstance()->getInfo();
            return view('admin.index.index', compact('info'));
        }
    }

    /**
     * 登录
     * @param Request $request
     * @return RedirectResponse|Redirector
     */
    public function login(Request $request)
    {
        $params = $request->input();
        //字段验证
        $roules = ['name' => 'required|max:25','password'=>'required'];
        $request->validate($roules,[],['name'=>'用户名','password'=>'密码']);

        //真实密码
        $privateKey = Config('common.login_private_key');
        openssl_private_decrypt(base64_decode($params['password']), $params['password'], openssl_pkey_get_private($privateKey));

        //用户登录
        $loginResult = AdminUserService::getInstance()->login($params['name'],$params['password'],$request->getClientIp());
        //不正确返回去
        if(!$loginResult->isSuccess()){
            return redirect()->back()->withErrors($loginResult->getMsg());
        }
        //保存用户信息到session中
        $request->session()->put('login_user',json_encode($loginResult->getData()));

        $back_url =!empty($params['back_url']) ?$params['back_url']: 'admin/adminHome/publicIndex';
        return redirect($back_url);
    }

    //登出
    public function logout()
    {
        session()->forget('login_user');
        return redirect('admin/index/index');
    }

    //跳转页
    public function jump()
    {
        //验证参数
        if (!empty(session('msg'))) {
            $data = [
                'msg' => session('msg'),
                'url' => session('url'),
                'wait' => session('wait') ?: 1,
                'code' => session('code') ?: 0,
                'data' => session('data')
            ];
        } else {
            $data = [
                'msg' => '前往首页中...',
                'url' => '/',
                'wait' => 1,
                'code' => 0,
                'data' => '',
            ];
        }
        return view('admin.index.jump', ['data' => $data]);
    }

}
