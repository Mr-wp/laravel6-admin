<?php
/**
 * Created by Alfred.
 * Date: 2021/4/13
 * Email: silentwolf_wp@163.com
 */

namespace App\Http\Controllers\Admin;


class CommonController extends Controller
{
    /**
     * 上传图片
     * @return false|string
     */
    public function uploadFiles(){
        $imgs = '';
        if(!empty(request()->file('auth_imgs_file'))){
            $path = request()->file('auth_imgs_file')->store('avatars','qiniu');
            $imgs = "http://cdn.findwp.cn/".$path;
        }
        return response()->json($imgs);
    }
    /**
     * 上传图片
     * @return false|string
     */
    public function deleteFiles(){
        return response()->json(request()->all());
    }
}