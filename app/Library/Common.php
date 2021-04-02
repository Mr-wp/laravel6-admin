<?php

namespace App\Library;
/**
 * 公用方法
 */
class Common
{
    /**
     * curl请求
     * @author DuZhenxun <5552123@qq.com>
     * @param $url
     * @param null $post_fields
     * @param string $headers
     * @param int $read_timeout
     * @param int $connect_timeout
     * @param string $referer 来源
     * @return mixed|string
     */
    public static function myCurl($url, $post_fields = null, $headers = '', $read_timeout = 30, $connect_timeout = 30, $referer = 'jc.xin.com')
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if ($headers) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }
        if ($read_timeout) {
            curl_setopt($ch, CURLOPT_TIMEOUT, $read_timeout);
        }
        if ($connect_timeout) {
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $connect_timeout);
        }
        if ($referer) {
            curl_setopt($ch, CURLOPT_REFERER, $referer);
        }

        //https
        if (strlen($url) > 5 && strtolower(substr($url, 0, 5)) == "https") {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }

        //post请求
        if (is_array($post_fields) && 0 < count($post_fields)) {
            $post_string = "";
            $post_multipart = false;
            foreach ($post_fields as $k => $v) {
                if ("@" != substr($v, 0, 1)) {
                    //判断是不是文件上传
                    $post_string .= "$k=" . urlencode($v) . "&";
                } else {
                    //文件上传用multipart/form-data，否则用www-form-urlencoded
                    $post_multipart = true;
                    if (class_exists('\CURLFile')) {
                        $post_fields[$k] = new \CURLFile(substr($v, 1));
                    }
                }
            }
            unset($k, $v);
            curl_setopt($ch, CURLOPT_POST, true);
            if ($post_multipart) {
                if (class_exists('\CURLFile')) {
                    curl_setopt($ch, CURLOPT_SAFE_UPLOAD, true);
                } else {
                    if (defined('CURLOPT_SAFE_UPLOAD')) {
                        curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
                    }
                }
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
            } else {
                curl_setopt($ch, CURLOPT_POSTFIELDS, substr($post_string, 0, -1));
            }
        } else {
            //发送整个body
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
        }

        $reponse = curl_exec($ch);
        //错误信息
        if (curl_errno($ch)) {
            $reponse = curl_error($ch);
        }
        curl_close($ch);
        return $reponse;
    }

    /**
     * 批量curl请求
     * @param array $curl_data
     * @param int $read_timeout
     * @param int $connect_timeout
     * @return array
     */
    public static function myCurlMulti($curl_data, $read_timeout = 30, $connect_timeout = 30)
    {
        //加入子curl
        $mh = curl_multi_init();
        $curl_array = array();

        foreach ($curl_data as $k => $info) {
            $curl_array[$k] = curl_init($info['url']);
            curl_setopt($curl_array[$k], CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl_array[$k], CURLOPT_HEADER, 0);

            if ($read_timeout) {
                curl_setopt($curl_array[$k], CURLOPT_TIMEOUT, $read_timeout);
            }
            if ($connect_timeout) {
                curl_setopt($curl_array[$k], CURLOPT_CONNECTTIMEOUT, $connect_timeout);
            }

            if (!empty($info['headers'])) {
                curl_setopt($curl_array[$k], CURLOPT_HTTPHEADER, $info['headers']);
            }
            //发送整个body
            if (!empty($info['post_fields'])) {
                curl_setopt($curl_array[$k], CURLOPT_POSTFIELDS, $info['post_fields']);
            }

            curl_multi_add_handle($mh, $curl_array[$k]);
        }


        //执行curl
        $running = null;
        do {
            $mrc = curl_multi_exec($mh, $running);
        } while ($mrc == CURLM_CALL_MULTI_PERFORM);


        while ($running && $mrc == CURLM_OK) {
            if (curl_multi_select($mh) == -1) {
                usleep(100);
            }
            do {
                $mrc = curl_multi_exec($mh, $running);
            } while ($mrc == CURLM_CALL_MULTI_PERFORM);
        }

        //获取执行结果
        $response = [];
        foreach ($curl_array as $key => $val) {
            $response[$key] = curl_multi_getcontent($val);
        }

        //关闭子curl
        foreach ($curl_data as $key => $val) {
            curl_multi_remove_handle($mh, $curl_array[$key]);
        }

        //关闭父curl
        curl_multi_close($mh);

        return $response;
    }


    /**
     * 创建路由规则
     * @param string $method 请求方法
     * @param string $path 请求地址
     * @param string $middleware_default 默认中间件
     * @param array $middleware_other 其它中间件
     */
    public static function createRoute($method,$path,$middleware_default='',array $middleware_other=[])
    {
        $method = strtolower($method);//请求方法
        $path = explode('?', $path)[0];//请求地址
        if (!in_array($method, ['get', 'post', 'GET', 'POST'])) {
            $method = 'get';
        }
        $uris = explode('/', trim($path, '/'));
        if (is_array($uris) && count($uris) == 3) {
            $uris[0] = ucfirst($uris[0]);
            $uris[1] = ucfirst($uris[1]);

            $attributes = [];
            if (!empty($middleware_other[lcfirst($uris[0])])) {
                $attributes['middleware'] = $middleware_other[lcfirst($uris[0])];
            } else {
                //默认使用的中间件
                if(!empty($middleware_default)){
                    $attributes['middleware'] = $middleware_default;
                }
            }

            \Route::group($attributes, function () use ($method, $path, $uris) {
                //生成对应地址 Route::get('/ucl/notices/index','Ucl\NoticesController@index')
                \Route::$method($path, $uris[0] . '\\' . $uris[1] . 'Controller@' . $uris[2])->name($uris[1] . '.' . $uris[2]);
            });
        }
    }

    /**
     * 日期合法性验证
     * @param $date
     * @param int $type 1:Y-m-d,2:Y-m-d H:i:s
     * @return bool
     */
    public static function checkDate($date, $type = 1)
    {
        $format = $type == 1 ? 'Y-m-d' : 'Y-m-d H:i:s';
        if (date($format, strtotime($date)) == $date) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 格式化时间戳
     * @param $time
     * @return string
     */
    public static function formatDate($time)
    {
        $t = time() - $time;
        $f = array(
            '31536000' => '年',
            '2592000' => '个月',
            '604800' => '星期',
            '86400' => '天',
            '3600' => '小时',
            '60' => '分钟',
            '1' => '秒'
        );
        foreach ($f as $k => $v) {
            if (0 != $c = floor($t / (int)$k)) {
                return $c . $v;
            }
        }
        return '';
    }
   /**
     * 发邮件
     * @param string $subject 主题
     * @param string $str 内容
     * @param string $to 收件人 多人以 逗号 分隔
     * @param string $attach 附件 多个以 逗号 分隔
     * @param string $blade 模板
     * @param string $send_name 发件人 1系统,2客服
     */
    public static  function sendMail($subject, $str = '', $to = '', $attach = '', $blade = '', $send_name = 1)
    {
        if (!$to) {
            $to_arr = explode(',', config('mail.to_email'));
        } else {
            if (is_array($to)) {
                $to_arr = $to;
            } else {
                $to_arr = explode(',', $to);
            }
        }

        if ($attach) {
            if (is_array($attach)) {
                $attach_arr = $attach;
            } else {
                $attach_arr = explode(',', $attach);
            }
        }
        if (!$blade) {
            $blade = 'emails.mail';
        }
        if ($send_name == 2) {
            //使用客服邮件
            config(['mail.from.address' => env('MAIL_USERNAME2')]);
            config(['mail.from.name' => env('MAIL_FROM_NAME2')]);
            config(['mail.username' => env('MAIL_USERNAME2')]);
            config(['mail.password' => env('MAIL_PASSWORD2')]);
        }
        \Mail::send($blade, ['str' => $str], function ($message) use ($subject, $to_arr, $attach_arr) {
            $message->subject($subject);
            foreach ($to_arr as $mail) {
                $message->to($mail);
            }

            if (is_array($attach_arr) && count($attach_arr) > 0) {
                foreach ($attach_arr as $file) {
                    $message->attach($file);
                }
            }
        });
    }

    /**
     * 节点
     * @param $arr
     * @param int $id
     * @param int $level
     * @return array
     */
    public static function nodeTree($arr, $id = 0, $level = 0)
    {
        static $array = array();
        foreach ($arr as $v) {
            if ($v['parentid'] == $id) {
                $v['level'] = $level;
                $array[] = $v;
                self::nodeTree($arr, $v['id'], $level + 1);
            }
        }
        return $array;
    }

    /**
     * 数组转树
     * @param $list
     * @param int $root
     * @param string $pk
     * @param string $pid
     * @param string $child
     * @return array
     */
    public static function listToTree($list, $root = 0, $pk = 'id', $pid = 'parentid', $child = '_child')
    {
        // 创建Tree
        $tree = array();
        if (is_array($list)) {
            // 创建基于主键的数组引用
            $refer = array();
            foreach ($list as $key => $data) {
                $refer[$data[$pk]] = &$list[$key];
            }
            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parentId = 0;
                if (isset($data[$pid])) {
                    $parentId = $data[$pid];
                }
                if ((string)$root == $parentId) {
                    $tree[] = &$list[$key];
                } else {
                    if (isset($refer[$parentId])) {
                        $parent = &$refer[$parentId];
                        $parent[$child][] = &$list[$key];
                    }
                }
            }
        }
        return $tree;
    }

    /**
     * 数组排序，按指定的KEY
     * @param array $array 要排序的2维数组
     * @param string $key 要排序的key
     * @param string $orderBy asc从小到大，desc从大到小
     * @return mixed
     */
    public static function sortToArray($array, $key, $orderBy = 'asc')
    {
        usort($array, function ($a, $b) use ($key, $orderBy) {
            return $orderBy == 'asc' ? strnatcmp($a[$key], $b[$key]) : strnatcmp($b[$key], $a[$key]);
        });
        return $array;
    }

   public static function arrToStr($arr, $str = '')
    {

        if (is_array($arr)) {
            foreach ($arr as $k => $v) {
                if (is_array($v)) {
                    return arr2str($v, $str);
                } else {
                    $str .= "<p>{$k}-->{$v}</p>";
                }
            }
        }
        return $str;
    }
    //初始化storage目录
    public static function initStorageDir(){
        $storage = app('path.storage');
        $dirs = [
            '/framework/sessions',
            '/framework/views',
            '/framework/testing',
            '/framework/cache',
            '/app/public',
            '/logs'
        ];
        foreach ($dirs as $k=>$v){
            if(!is_dir($storage.$v)){
                mkdir($storage.$v,0777,true);
            }
        }
    }
}
