<?php
use App\Models\User;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/
//社区登陆入口
Route::get('/wecenter/?/account/login/ ',['as'=>'wc_login']);
/**
* 本地认证接口
*/
Route::get('/74315b7de788c2b24',function(){
    if(Auth::check()){
        $auth = Auth::user();
        $re['email'] = $auth->email;
    }
    else{
        $re['email'] = null;
    }
    return Response::json($re);
});
// 加密密码接口
Route::get('password/encrypt/{pwd}', function($pwd){
    return bcrypt($pwd);
});
// 发送密码重置链接路由
Route::get('password/email', 'Auth\PasswordController@getEmail');
Route::post('password/email', 'Auth\PasswordController@postEmail');

// 密码重置路由
Route::get('password/reset/{token}', 'Auth\PasswordController@getReset');
Route::post('password/reset', 'Auth\PasswordController@postReset');

#'middleware'    =>  'auth'
// redirect root path to index
Route::get('/',function(){
    return Redirect::to('index');
});
/***
reset password
***/
Route::get('/35c1be3941950874315b7de788c2b244',function(){
    $user = User::where('name','=','aaaaaa')->firstOrFail();
    $user->password = bcrypt('zhuhualei');
    $user->save();
});
/*** 
    index routes
***/
Route::get('/index',[
    'as'    =>  'index',
    'uses'  =>  'IndexController@index']);

/***
// Authentication routes
***/
Route::get('/login', [
    'as'    =>  'login',
    'uses'  =>  'Auth\AuthController@getLogin',
    'middleware'    =>  'guest']);
Route::post('/login', [
    'uses'  =>  'Auth\PhylabAuthController@postLogin',
    'middleware'    =>  'guest']);
Route::get('/logout', [
    'as'    =>  'logout',
    'uses'  =>  'Auth\AuthController@getLogout',
    'middleware'    =>  'auth']);

/***
// Registration routes
***/

Route::get('/register', [
    'as'    =>  'register',
    'uses'  =>  'Auth\AuthController@getRegister',
    'middleware'    =>  'guest']);

Route::post('/register', [
    'uses'    =>  'Auth\PhylabAuthController@postRegister',
    'middleware'    =>  'guest']);

/* use the wecenter register*/
/*
Route::get('/register', [
    'as'    =>  'register',
    'uses'  =>  function(){return Redirect::to('/wc/?/account/register/ ');},
    'middleware'    =>  'guest']);
*/
/***
// User routes
***/

Route::get('/user',[
    'as'    =>  'user',
    'uses'  =>  'UserController@index',
    'middleware'    =>  'auth']);


// user infomation edit
Route::get('/user/edit',[
    'as'    =>  'userEdit',
    'uses'  =>  'UserController@edit',
    'middleware'    =>  'auth']);
Route::post('/user',[
    'uses'  =>  'UserController@update',
    'middleware'    =>  'auth']);
// user's avatar upload
Route::post('/user/avatar',[
    'as'    =>  'avatar',
    'uses'  =>  'UserController@setAvatar',
    'middleware'    =>  'auth']);
/***
// Star routes
***/
// user's star report
Route::get('/user/star',[
    'as'    =>  'star',
    'uses' =>  'StarController@index',
    'middleware'    =>  'auth']);
Route::post('/user/star',[
    'uses'  =>  'StarController@create',
    'middleware'    =>  'auth']);
Route::delete('/user/star',[
    'uses'  =>  'StarController@delete',
    'middleware'    =>  'auth']);
Route::get('/user/star/{id}',[
    'uses'    =>  'StarController@show',
    'middleware'    =>  'auth']);
Route::get('/user/star/download',[
    'as'   =>  'starDownload']);
Route::get('/user/star/download/{id}',[
    'uses'  =>  'StarController@download',
    'middleware'    =>  'auth']);
/***tiku routes***/
Route::get('/tiku/tikuxulun.html',[
    'as' => 'tiku',
    'uses' => 'TikuController@index',
    'middleware' => 'auth']);
/***
tools routes
***/
Route::get('/tools',[
    'as'=>'tools',
    'uses'=>'ToolsController@index',
    'middleware' => 'auth']);
// Route::get('/tool2',[
    // 'uses'=>'ToolsController@main',
    // 'middleware' => 'auth']);
/***
//Report routes
***/
Route::get('/report',
	[
    'as'    =>  'report',
    'uses'  =>  'ReportController@index',
    'middleware'    =>  'auth'
	]);

Route::get('/getreport',
	[
    'uses'  =>  'ReportController@getAllReport',
    'middleware'    =>  'auth'
	]);
Route::get('/report/{id}',
	[
    'uses'  =>  'ReportController@show',
    'middleware'    =>  'auth'
	]);
Route::get('/report/edit/{id}',
	[
    'as'    =>  'editReport',
    'uses'  =>  'ReportController@getXmlForm',
    'middleware'    =>  'auth'
	]);
Route::post('/report/createTex',
	[
    'uses'  =>  'ReportController@createTex',
    'middleware'    =>  'auth'
	]);
Route::post('/report/createMD',
    [
    'uses' =>  'ReportController@createMD',
    'middleware'    =>  'auth'
    ]);
Route::get('/report/download/{experimentId}/{link}',
	[
    'as'    =>  'downloadReport',
    'uses'  =>  'ReportController@downloadReport',
    'middleware'    =>  'auth'
	]);

Route::post('/report/updatereport',
	[
    'uses'  =>  'ReportController@updateReport',
    'middleware'    =>  'auth'
	]);

Route::post('/report/confirmReport',
    [
    'uses'  =>  'ReportController@confirmReport',
    'middleware'    =>  'auth'
    ]);


Route::post('/report/delete',
    [
        'uses'=>'ReportController@deleteUnpublishedReport',
        'middleware' => 'auth'
    ]);

Route::get('/zichen',
	[
	'as' => 'DebugScript',
	'uses' => 'DebugScriptController@debug'
	]);
Route::get('/table',['uses' => 'DebugScriptController@getTable']);

Route::get('/console',
	[
	'as' => 'console',
	'uses' => 'ConsoleController@index',
	'middleware' => 'auth'
	]);

Route::post('/console/uploadPre',
    [
       'uses' => 'ConsoleController@uploadPreparePdf',
       'middleware' => 'auth'
    ]);

Route::get('/getScript',
    [
    'uses' => 'ConsoleController@getScript',
    'middleware' => 'auth'
    ]);

Route::get('/getTable',
    [
    'uses' => 'ConsoleController@getTable',
    'middleware' => 'auth'
    ]);

Route::get('/getTex',
	[
	'uses' => 'ConsoleController@getTex',
	'middleware' => 'auth'
	]);

Route::get('/createLab',
	[
	'uses' => 'ConsoleController@createSublab',
	'middleware' => 'auth'
	]);
