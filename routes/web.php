<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Route;
//路由访问时自动生成
if (php_sapi_name() != 'cli') {
    $method = strtolower($_SERVER['REQUEST_METHOD']); //方法
    $act = explode('?', $_SERVER['REQUEST_URI'])[0]; //请求
    $path = explode('/', trim($act, '/'));
    if (!empty($path[0]) && !in_array($path[0],['_debugbar'])) {
        //默认访问地址3层 模块/控制器/方法
        $siteRoute = config('common.route');
        $path[0] = !empty($path[0]) ? ucfirst($path[0]) : $siteRoute['default_module'];
        $path[1] = !empty($path[1]) ? ucfirst($path[1]) : $siteRoute['default_controller'];
        $path[2] = !empty($path[2]) ? $path[2] : $siteRoute['default_action'];

        //中间件，需要时在这里添加
        $middleware = [];
        if ($path[0] == 'Admin') {
            // $middleware[] = '\App\Http\Middleware\AdminMiddleware';
        }
        if ($path[0] == 'Api') {
            $act = str_replace('api/','',$act);
        }
        //登入后台的所有操作在这里自动生成
        Route::group(['middleware' => $middleware], function () use ($method, $act, $path) {
            Route::$method($act, ucfirst($path[0]) . '\\' . ucfirst($path[1]) . 'Controller@' . $path[2]);
        });
    }
}
//==========定制路由规则，覆盖上面规则  Start ============================//
Route::get("/", "Admin\IndexController@index");

//======== 定制路由规则 End ============================//


