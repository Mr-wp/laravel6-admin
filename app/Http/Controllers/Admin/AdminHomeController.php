<?php

/**
 * 后台个人页
 * @filename  UserHomeController
 * @author    Zhenxun Du <5552123@qq.com>
 * @date      2017-8-16 10:20:11
 * @version   SVN:$Id:$
 */

namespace App\Http\Controllers\Admin;

use App\Models\AdminUser;
use Illuminate\Support\Facades\Hash;

class AdminHomeController extends Controller
{

    public function publicIndex()
    {
        return $this->view();
    }

    /**
     * 个人修改资料
     */
    public function publicInfo()
    {
        $info = AdminUser::query()->where('id', $this->loginUser['id'])->first();
        if (request('id')) {
            $params = request(['mobile', 'realname', 'email']); //可以添加或修改的参数
            AdminUser::query()->where('id', $info->id)->update($params);
            return $this->success();
        } else {
            return $this->view(compact('info'));
        }
    }

    /**
     * 个人修改密码
     */
    public function publicChangePwd()
    {
        $info = AdminUser::query()->find($this->loginUser['id']);

        if (request('id')) {
            $this->validate(request(), [
                'passwordOld' => 'required',
                'password' => 'required|min:3|max:20|confirmed',
            ], [
                'passwordOld.required' => '旧密码不能为空',
                'password.required' => '密码不能为空',
                'password.confirmed' => '密码与确认密码不一致',
            ]);
            //旧密码不正确
            if (!Hash::check(request('passwordOld'), $info->password)) {
                return $this->error('旧密码不正确');
            }

            AdminUser::query()->where('id', $info->id)->update(['password' => bcrypt(request('password'))]);
            return $this->success();
        } else {
            return $this->view(compact('info'));
        }
    }


}
