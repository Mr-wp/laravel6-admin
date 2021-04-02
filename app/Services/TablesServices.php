<?php
/**
 * Created by Alfred.
 * Date: 2021/3/25
 * Email: silentwolf_wp@163.com
 */

namespace App\Services;


use App\Models\Tables;

class TablesServices extends Service
{
    public function tableLists(array $params = [])
    {
        $limit = $params['pageSize']??10;
        $where = [];
        if (!empty($params['name'])) {
            $where[] = ['name', 'like', '%' . $params['name'] . '%'];
        }
        if (!empty($params['type'])) {
            $where[] = ['type', $params['type']];
        }
        $where[] = ['is_del','0'];
//        $where[] = ['is_show','1'];
        $res = Tables::query()->where($where)->paginate($limit);
        return $res;
    }
}