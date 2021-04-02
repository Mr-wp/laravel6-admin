<?php

namespace App\Listeners;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;

/**
 * Class SqlListener
 * @package App\Listeners
 * @author    Zhenxun Du <5552123@qq.com>
 * @date      2010-3-3 14:39:12
 */
class SqlListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param object $event
     * @return void
     */
    public function handle($event)
    {
        if (env('APP_DEBUG')==true) {
            $sql = str_replace("?", "'%s'", $event->sql);
            $str = '【' . $event->time . 'ms】 ' . vsprintf($sql, $event->bindings);

            //设置SQL保存路径
            $logSqlDir = storage_path() . DIRECTORY_SEPARATOR . "logs" . DIRECTORY_SEPARATOR . "sql" . DIRECTORY_SEPARATOR;
            if (!is_dir($logSqlDir)) {
                mkdir($logSqlDir, 0777, true);
            }
            $logFile = $logSqlDir . DIRECTORY_SEPARATOR . date('Y-m-d') . '.log';
            $handler = new StreamHandler($logFile, Logger::INFO, false);
            //日志格式化
            $handler->setFormatter(new LineFormatter(null, 'Y-m-d H:i:s', true, true));
            $log = new Logger('sql');
            $log->pushHandler($handler);
            $log->info($str);
        }
    }
}
