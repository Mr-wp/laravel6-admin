<?php
/**
 * Created by Alfred.
 * Date: 2021/4/21
 * Email: silentwolf_wp@163.com
 */

namespace App\Http\Controllers\Api;


use Illuminate\Routing\Controller;

class ApiController extends Controller
{
    public function getSplitWordList(){
        $str = request('title');
        $regex = "/\ |\/|\~|\!|\@|\#|\\$|\%|\^|\&|\*|\(|\)|\_|\+|\{|\}|\:|\<|\>|\?|\[|\]|\,|\.|\/|\;|\'|\`|\-|\=|\\\|\|/";
        $str = preg_replace($regex,"",$str);
        try {
            set_time_limit(0);
            if (true == empty($str)) {
                return response()->json(['code'=>201,'data'=>'请求参数错误！']);
            }
            $param = ['title'=>$str];
            $data = requestPythonPost('127.0.0.1:5000/getSplitWords', $param);
            if ($data['code'] == 500) {
                return response()->json(['code'=>201,'data'=>$data['msg']]);
            }
            return response()->json(['code'=>200,'data'=>$data['strs']]);
        } catch (\Exception $e) {
            return response()->json(['code'=>200,'data'=>$e->getMessage()]);
        }
    }
}