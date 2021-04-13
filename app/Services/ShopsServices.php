<?php
/**
 * Created by Alfred.
 * Date: 2021/3/25
 * Email: silentwolf_wp@163.com
 */

namespace App\Services;


use App\Models\Shops;

class ShopsServices extends Service
{
    /**
     * 获取列表数据
     * @param array $params
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getLists(array $params = [])
    {
        $limit = $params['pageSize']??10;
        $where = [];
        if (!empty($params['name'])) {
            $where[] = ['name', 'like', '%' . $params['name'] . '%'];
        }
        if (!empty($params['type'])) {
            $where[] = ['type', $params['type']];
        }
        $res = Shops::query()->where($where)->paginate($limit);
        return $res;
    }
}