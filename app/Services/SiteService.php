<?php
namespace App\Services;

use App\Models\Site;
use Illuminate\Support\Facades\Cache;

class  SiteService extends Service
{
    public static function test(){
        echo 'tset222';
    }
    //获取站点信息
    /**
     * @return array|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Eloquent\Model|mixed|object|null
     */
    public function getInfo()
    {
        $info = Cache::get('system:config');
        if ($info) {
            $info = json_decode($info,true);
        } else {
            $info = Site::query()->first();
            if($info){
                $info = $info->toArray();
                Cache::set('system:config', json_encode($info),86400);
            }
        }
        return $info;
    }

    /**
     * 获取设置的邮箱
     * @param string $key
     * @return array|bool|\Illuminate\Config\Repository|mixed
     */
    public function getSetting($key = '')
    {
        $info = $this->getInfo();
        $data = [];
        foreach ($info['setting'] as $k => $v) {
            $tmp = explode("\n", $v);
            foreach ($tmp as $kk => $vv) {
                $data[$k][$kk] = trim($vv);
            }
        }
        if ($key && isset($data[$key])) {
            $data = $data[$key];
        }
        return $data;
    }
}
