<?php
/**
 * 日志记录
 * @filename  AdminLogController.php
 * @author    Zhenxun Du <5552123@qq.com>
 * @date      2018/6/24 15:49
 */

namespace App\Http\Controllers\Admin;


use App\Models\AdminLog;
use App\Services\AdminLogService;

class AdminLogController extends Controller
{
    //列表
    public function index()
    {
        $params = request()->input();
        if (!empty($params['reportrange'])) {
            $arr = explode(' - ', $params['reportrange']);
            $start_time = $arr[0] . " 0:00:00";
            $end_time = $arr[1] . " 23:59:59";
        } else {
            $start_time = date('Y-m-d H:i:s', time() - 86400*30);
            $end_time = date('Y-m-d H:i:s', time());
        }

        $where = [];
        if (!empty($start_time) && !empty($end_time)) {
            $where[] = ['t1.created_at', '>=', strtotime($start_time)];
            $where[] = ['t1.created_at', '<=', strtotime($end_time)];
        }
        if (!empty($params['name'])) {
            $where[] = ['t2.name', 'like', '%' . $params['name']. '%'];
        }
        if (!empty($params['admin_name'])) {
            $where[] = ['t3.admin_name', $params['admin_name']];
        }
        if (!empty($params['ip'])) {
            $where[] = ['t1.ip', $params['ip']];
        }

        $limit = $params['limit'] ?? 20;
        $lists = AdminLog::query()->from('admin_log as t1')
            ->select('t2.name as menu_name','t2.c','t2.a','t1.id','t1.querystring','t1.ip','t1.admin_id','t1.created_at','t1.data','t3.name as admin_name','t3.realname')
            ->where($where)
            ->leftJoin('admin_menu as t2','t1.admin_menu_id','=','t2.id')
            ->leftJoin('admin_user as t3','t1.admin_id','=','t3.id')
            ->orderBy('t1.id', 'desc')
            ->paginate($limit);

        return $this->view(compact('lists','start_time','end_time'));
    }

    //详情
    public function info()
    {
        $info = AdminLogService::getInstance()->getInfo(request('id'));

        //上次信息
        $where=[];
        $where[]=['admin_menu_id',$info->admin_menu_id];
        $where[]=['primary_id',$info->primary_id];
        $where[] = ['id','<',$info->id];
        $last_id = AdminLog::query()->where($where)->orderBy('id','desc')->value('id');

        if($last_id){
            $last_info = AdminLogService::getInstance()->getInfo($last_id);
        }else{
            $last_info=[];
        }


        $info->data = json_decode($info->data,true);
        if($info->data && $last_info->data){
            $last_info->data = @json_decode($last_info->data,true);
            $last_info->data = $this->diffArr($info->data,$last_info->data);
        }

        return $this->view(compact('info','last_info'));

    }

    //删除日志
    public function del(){
        $params = request()->input();
        $query = AdminLog::query();
        if(!empty($params['ids'])){
            $query->whereIn('id',explode(',',$params['ids']))->delete();
        }
        if(!empty($params['id'])){
            $query->where('id',$params['id'])->delete();
        }
        return ['code'=>1,'msg'=>'删除成功'];
    }




    //对比不同
    private function diffArr($info,$last_info,$key=''){
        static $arr;
        foreach($info as $k=>$v){
            if(!is_array($v)){
                if($v!=$last_info[$k]){
                    if($key){
                        $arr[$key][$k] ='<font color="red">'.$last_info[$k].'</font>';
                    }else{
                        $arr[$k] ='<font color="red">'.$last_info[$k].'</font>';
                    }
                }else{
                    $arr[$k]=$last_info[$k];
                }
            }else{
                return $this->diffArr($v,$last_info[$k],$k);
            }
        }
        return $arr;
    }




}
