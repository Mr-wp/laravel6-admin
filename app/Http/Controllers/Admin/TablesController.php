<?php
/**
 * Created by Alfred.
 * Date: 2021/3/23
 * Email: silentwolf_wp@163.com
 */

namespace App\Http\Controllers\Admin;


use App\Models\Tables;
use App\Services\TablesServices;

class TablesController extends Controller
{
    public $M;

    public function __construct()
    {
        parent::__construct();
        $this->M = new Tables();

    }

    /**
     * 列表页
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(){
        $params = request()->except('_token');
        $lists = TablesServices::getInstance()->tableLists($params);
        return $this->view(compact('lists'));
    }

    /**
     * 数据添加或编辑
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function info(){
        $info = $this->M::query()->find(request('id'));
        return $this->view(compact('info'));
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
            $rs = $this->M::query()->where('id', request('id'))->update($params);
        } else {
            $params['admin_id'] = $this->loginUser['id'];
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
            $rs = $this->M::query()->where('id', request('id'))->update(['is_del'=>'1']);
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