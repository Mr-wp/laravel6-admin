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
                return response()->json(['code'=>201,'data'=>'请求参数错误1！']);
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
    public function getTranslateWord(){
        $str = request('word');
        $regex = "/\ |\/|\~|\!|\@|\#|\\$|\%|\^|\&|\*|\(|\)|\_|\+|\{|\}|\:|\<|\>|\?|\[|\]|\.|\/|\;|\'|\`|\-|\=|\\\|\|/";
        $str = preg_replace($regex,"",$str);
        try {
            set_time_limit(0);
            if (true == empty($str)) {
                return response()->json(['code'=>201,'data'=>'请求参数错误！']);
            }
            $param = ['word'=>$str];
            $data = requestPythonPost('127.0.0.1:5000/translatetozn', $param);
            if ($data['code'] == 500) {
                return response()->json(['code'=>201,'data'=>$data['msg']]);
            }
            return response()->json(['code'=>200,'data'=>$data['result']]);
        } catch (\Exception $e) {
            return response()->json(['code'=>200,'data'=>$e->getMessage()]);
        }
    }
    public function transWordsByYd(){
        $str = request('word');
        $regex = "/\ |\/|\~|\!|\@|\#|\\$|\%|\^|\&|\*|\(|\)|\_|\+|\{|\}|\:|\<|\>|\?|\[|\]|\.|\/|\;|\'|\`|\-|\=|\\\|\|/";
        $str = preg_replace($regex,"",$str);
        try {
            set_time_limit(0);
            if (true == empty($str)) {
                return response()->json(['code'=>201,'data'=>'请求参数错误！']);
            }
            $param = ['word'=>$str];
            $data = requestPythonPost('127.0.0.1:5000/translate_youdao', $param);
            if ($data['code'] == 500) {
                return response()->json(['code'=>201,'data'=>$data['msg']]);
            }
            return response()->json(['code'=>200,'data'=>$data['result']]);
        } catch (\Exception $e) {
            return response()->json(['code'=>200,'data'=>$e->getMessage()]);
        }
    }
    public function testTopApi(){
        $appkey = '30319605';
        $secret = '06ebf77bda2b5c8760e9be9720ff8131';
        $c = new \TopClient;
        $c->appkey = $appkey;
        $c->secretKey = $secret;
        $req = new \VasOrderSearchRequest;
        $req->setArticleCode("FW_GOODS-1001104343");
//        $req->setItemCode("ts-1234-1");
        $req->setNick("tb568805454");
//        $req->setStartCreated("2000-01-01 00:00:00");
//        $req->setEndCreated("2000-01-01 00:00:00");
//        $req->setBizType("1");
//        $req->setBizOrderId("12345");
//        $req->setOrderId("12345");
//        $req->setPageSize("20");
//        $req->setPageNo("1");
        $resp = $c->execute($req);
        dump($resp->article_biz_orders);
    }
}