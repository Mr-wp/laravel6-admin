<?php

namespace App\Console;

use App\Services\CrontabService;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        $crontabLists = CrontabService::getInstance()->getCrontab();
        //处理信息
        foreach ($crontabLists as $k => $v) {
            if (!$v || count(explode(' ', $v)) < 5) {
                unset($crontabLists[$k]);
            }
        }
        //任务处理
        foreach ($crontabLists as $k => $v) {
            switch ($k) {
                //工作提醒
                case 'work_reminder':
                    $schedule->call(function () {
                        echo '发邮件或发微信提醒';
                    })->cron($v)->name('work_reminder')->withoutOverlapping();
                    break;
                default:
                    break;
            }
        }
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
