<?php
/**
 * Created by Alfred.
 * Date: 2021/3/23
 * Email: silentwolf_wp@163.com
 */

namespace App\Http\Controllers\Admin;


use Illuminate\Support\Facades\DB;

class AreasController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }
    /**
     * 获取省市区列表
     * @return false|string
     */
    public function city(){
        $cid = request('cid');
        if(empty($cid))
            return response()->json([]);
        $citys = DB::table('areas')->where('parentid',$cid)->orderBy('vieworder')->get();
        return response()->json($citys);
    }
}