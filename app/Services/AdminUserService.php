<?php
/**
 * AdminUserService
 * @filename  AdminUserService.php
 * @author    Zhenxun Du <5552123@qq.com>
 * @date      2018/7/8 16:18
 */

namespace App\Services;


use App\Library\Common;
use App\Library\Result;
use App\Models\AdminGroupUser;
use App\Models\AdminUser;
use Illuminate\Support\Facades\Hash;

class AdminUserService extends Service
{
    /**
     * 获取用户
     * @param array $params
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function adminUserLists(array $params = [])
    {
        $where = [];
        if (!empty($params['name'])) {
            $where[] = ['name', 'like', '%' . $params['name'] . '%'];
        }
        if (!empty($params['status'])) {
            $where[] = ['status', $params['status']];
        }
        if (!empty($params['type'])) {
            $where[] = ['type', $params['type']];
        }
        $loginUser = json_decode(request()->session()->get('login_user'), true);
        if($loginUser['shop_id']!=1){
            $where[] = ['shop_id',$loginUser['shop_id']];
        }
        $limit = $params['limit'] ?? 10;
        $res = AdminUser::query()->where($where)->orderBy('id', 'desc')->paginate($limit);

        foreach ($res as $k => $v) {
            if ($tmp = $this->getAdminGroupUser($v['id'])) {

                $res[$k]['groups'] = implode(',', array_column($tmp, 'name'));
            }
        }
        return $res;
    }


    /**
     * 根据类型id获取信息
     */
    public function getAdminGroupUser($id, $type = 'admin_id')
    {

        $where = [];
        if ($id) {
            if ($type == 'admin_id') {
                $where[] = ['t1.admin_id', $id];
            } else {
                $where[] = ['t1.group_id', $id];
            }
        }

        $res = AdminGroupUser::select('t2.id', 't2.name')
            ->from('admin_group_user as t1')
            ->leftJoin('admin_group as t2', 't1.group_id', '=', 't2.id')
            ->where($where)
            ->get()
            ->toArray();

        return $res;
    }

    /**
     * 登录逻辑
     * @param string $username
     * @param string $password
     * @param string $clientIp
     * @return Result
     */
    public function login($username, $password, $clientIp)
    {
        $result = new Result();
        try {
            //账号是否存在
            $info = AdminUser::query()->where('name', $username)->first();
            if (!$info) {
                throw new \LogicException('此用户不存在');
            }
            if ($info->status == 2) {
                throw new \LogicException('此用户已停用');
            }
            //密码错误次数处理，超过一定范围上锁


            //使用hash校验密码是否正确
            if (!Hash::check($password, $info->password)) {
                throw new \LogicException('账号密码不匹配');
            }

            //当前用户的菜单权限
            $info['menu_list'] = AdminMenuService::getInstance()->getUserMenuList($info->id, $info->is_super);
            $result->setCode(Result::CODE_SUCCESS)->setMsg('success')->setData($info->toArray());
        } catch (\LogicException $e) {
            $result->setCode(Result::CODE_ERROR)->setMsg($e->getMessage());
        }
        return $result;
    }
}
