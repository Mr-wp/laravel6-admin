<?php

namespace App\Services;

use App\Models\Crontab;
use Illuminate\Support\Facades\Cache;

class CrontabService extends Service
{
//获取crontab
    public function getCrontab()
    {
        $crontab = Cache::get('system:crontab');
        if ($crontab) {
            $crontab = json_decode($crontab, true);
        } else {
            $crontab = Crontab::query()->select('name', 'code', 'crontab')
                ->where('status', 1)
                ->get();
            $crontab = $crontab->toArray();
            Cache::set('system:crontab', json_encode($crontab), 60);
        }

        $crontab = array_column($crontab, 'crontab', 'code');

        return $crontab;
    }

    /**
     * 设置crontab
     */
    public function setCrontab()
    {
        $crontab = Crontab::query()->select('name', 'code', 'crontab')
            ->where('status', 1)
            ->get();
        Cache::set('system:crontab', json_encode($crontab->toArray()), 1);
    }

}
