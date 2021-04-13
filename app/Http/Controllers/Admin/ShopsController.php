<?php
/**
 * Created by Alfred.
 * Date: 2021/3/23
 * Email: silentwolf_wp@163.com
 */

namespace App\Http\Controllers\Admin;


use App\Models\Shops;
use App\Services\ShopsServices;
use Illuminate\Support\Facades\DB;

class ShopsController extends Controller
{
    public $M;

    public function __construct()
    {
        parent::__construct();
        $this->M = new Shops();

    }
    /**
     * 列表页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        $params = request()->except('_token');
        $lists = ShopsServices::getInstance()->getLists($params);
        return $this->view(compact('lists'));
    }
    /**
     * 数据添加或编辑
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function info(){
        $info = $this->M::query()->find(request('id'));
        //地区回显---start---
        $provinces = DB::table('areas')->where('parentid',0)->orderBy('vieworder')->get();
        $citys = [];
        $regions = [];
        if($info){
            if(!empty($info['address'])){
                $areas = explode(',',$info['address']);
                if(!empty($areas[0]))
                    $citys = DB::table('areas')->where('parentid',$areas[0])->orderBy('vieworder')->get();
                if(!empty($areas[1]))
                    $regions = DB::table('areas')->where('parentid',$areas[1])->orderBy('vieworder')->get();
            }
        }
        //地区回显--- end ---
        return $this->view(compact('info','provinces','citys','regions'));
    }
    /**
     * 添加
     * @return \App\Library\type
     * @throws \Illuminate\Validation\ValidationException
     */
    public function add()
    {
        if ($this->storage()) {
            return $this->success('添加成功', '/' . $this->menuInfo['c'] . '/index');
        } else {
            return $this->error();
        }
    }
    /**
     * 修改
     * @return \App\Library\type
     * @throws \Illuminate\Validation\ValidationException
     */
    public function edit()
    {
        if ($this->storage()) {
            return $this->success('修改成功', '/' . $this->menuInfo['c'] . '/index');
        } else {
            return $this->error();
        }
    }

    /**
     * 存储验证
     * @return mixed
     * @throws \Illuminate\Validation\ValidationException
     */
    public function storage()
    {
        $this->validate(request(), $this->M->rules, $this->M->messages);
        $params = request($this->M->fillable); //可以添加或修改的参数
        if (request('id')) {
            // 删除图片
            if(request('auth_imgs_del')){
                $auth_imgs = explode(';',request('auth_imgs'));
                $auth_imgs_del = explode(';',request('auth_imgs_del'));
                if(!empty($auth_imgs) && !empty($auth_imgs_del)){
                    $auth_imgs = array_filter($auth_imgs,function($value)use($auth_imgs_del){
                        if(!in_array($value,$auth_imgs_del)){
                            return $value;
                        }
                    });
                    $params['auth_imgs'] = implode(';',$auth_imgs);
                }
            }
            $rs = $this->M::query()->where('id', request('id'))->update($params);
        } else {
            $time = time();
            $params['ex_start_time'] = $time;
            $params['ex_end_time'] = $time + 7*24*3600;
            $rs = $this->M::query()->create($params);
        }
        return $rs;
    }

    /**
     * 软删除
     * @return \App\Library\type
     */
    public function del(){
        if (request('id')) {
            $rs = $this->M::query()->where('id', request('id'))->delete();
            if ($rs) {
                return $this->success('删除成功', '/' . $this->menuInfo['c'] . '/index');
            } else {
                return $this->error();
            }
        }else{
            return $this->error('参数错误!');
        }
    }
}