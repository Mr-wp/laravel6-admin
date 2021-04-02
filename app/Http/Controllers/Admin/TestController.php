<?php
namespace App\Http\Controllers\Admin;
use Illuminate\Routing\Controller as BaseController;

class TestController extends BaseController {
    public function test(){
        return 'test';
    }
}
