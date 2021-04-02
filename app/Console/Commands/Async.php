<?php

namespace App\Console\Commands;

use App\Library\Result;
use Illuminate\Console\Command;
use App\Services\AsyncService;

/**
 * Class Async
 *
 *  使用方法
 * 例一,service名::方法名 参数....
 * php artisan async --method=TestService::test --args=duzhenxun,32
 * php artisan async --method=Enquiry\\EnquiryService::editTaskStatus --args=6911,2,1234 //指定service下的文件
 * php artisan async --method=\\App\\Models\\Enquiry\\EnquiryProxyUserModel::getEnquiryProxyUser //任意文件
 *
 * 如果方法中需要传数组可以用以下命令  例(['age'=>19,'name'=>'duzhenxun'],'duzhenxun',['arr0','arr1'])
 * php artisan async --method=Async\\TestService::test2 --args=age=19#name=duzhenxun,duzhenxun,arr0#arr1
 *
 * 例二, 参数如果是多维数组可以使用base64方式,最后一个字段传入base64后的信息
 * 第一步初始化$data数组,
 * 第二步将$data base64_encode(json_encode($data))
 * $data['service'] = '\App\Services\TestService'; 类位置
 * $data['method'] = 'test'; 方法名
 * $data['params'] = [duzhenxun,32]; 方法不需要参数,则不添加此变量
 * $str=base64_encode(json_encode($data))
 * php artisan async  --method=base64 eyJzZXJ2aWNlIjoiXFxBcHBcXFNlcnZpY2VzXFxUZXN0U2VydmljZSIsIm1ldGhvZCI6InRlc3QiLCJwYXJhbXMiOlsiZHV6aGVueHVuIiwzMl19
 *
 * @package App\Console\Commands
 */
class Async extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'async {str=0} {--method=} {--args=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'php artisan async --method=TestService::test --args=lijg,32';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $res = $this->_formatData(); //格式化参
        if (!$res->isSuccess()) {
            dump($res->getMsg());
            return;
        }
        $data = $res->getData();
        try {
            //执行代码
            $res = AsyncService::getInstance()->execute($data);
            Log::add(json_encode($data), 'cz_async.log');
        } catch (\Exception $e) {
            $res = stripslashes($e->getMessage());
            Log::add($res, 'cz_async_error.log');
        }
        dump($res);
    }


    /**
     * 格式化数据
     * @return Result
     */
    private function _formatData()
    {
        $result = new Result();
        $method = $this->option('method');
        try {
            $data = [];
            if ($method == 'base64') {
                $str = $this->argument('str');
                if (!$str) {
                    throw new \Exception(sprintf('method:base64,缺少值'));
                }
                $data = json_decode(base64_decode($str), true);
                if (!is_array($data)) {
                    throw new \Exception('base64 值有问题:' . $str);
                }
                if (empty($data['service']) || empty($data['method'])) {
                    throw new \Exception("service或method 缺少参数");
                }
            } else {
                if (!strstr($method, '::')) {
                    throw new \Exception(sprintf('method:%s有问题', $method));
                }
                list($data['service'], $data['method']) = explode('::', $method);
                //方法所需参数
                $args = $this->option('args');
                if ($args) {
                    $data['params'] = $this->_getArgs($args);
                }
            }
            $result->setData($data)->setCode(Result::CODE_SUCCESS);
        } catch (\Exception $e) {
            $result->setCode(Result::CODE_ERROR)->setMsg($e->getMessage());
        }
        return $result;
    }

    /**
     * 获取参数
     * @param $args
     * @return array
     */
    private function _getArgs($args)
    {
        $data = [];
        $arr = explode(',', trim($args));
        foreach ($arr as $k => $v) {
            if (strstr($v, '#')) {
                $tmp = explode('#', trim(trim($v),"#"));
                foreach ($tmp as $kk => $vv) {
                    if (strstr($vv, '=')) {
                        $tmp2 = explode('=', $vv);
                        $data[$k][$tmp2[0]] = $tmp2[1];
                    }else{
                        $data[$k][] = $vv;
                    }
                }
            } else {
                $data[$k] = $v;
            }
        }
        return $data;
    }
}
