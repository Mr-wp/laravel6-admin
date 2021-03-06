<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::middleware('api', 'throttle:60,1')->group(function () {
    Route::post('/getSplitWordList', "Api\\ApiController@getSplitWordList");
    Route::post('/getTranslateWord', "Api\\ApiController@getTranslateWord");
    Route::post('/transWordsByYd', "Api\\ApiController@transWordsByYd");
    Route::post('/testTopApi', "Api\\ApiController@testTopApi");
});