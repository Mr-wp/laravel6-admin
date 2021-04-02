<?php
namespace App\Http\Controllers\Home;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class IndexController extends Controller{
    public function index(Request $request,Response $response){
            echo 'index';
    }
    public function test(){
        echo 'test';
    }
    public function test2(){
        echo 'test2';
    }
}
