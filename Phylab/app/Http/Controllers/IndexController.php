<?php

namespace App\Http\Controllers;
use App\Models\Console;
use Auth;
use Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class IndexController extends Controller
{
    /**
     * Display the index page.
     * check if login and return different page
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //显示主页
        $data = ["auth" => false ,"username"    =>  "", "admin"=>false,"errorcode"=>"0000", "bulletin"=>""];
        $data["bulletin"] = file_get_contents(public_path()."/bulletin.html");
        if(Auth::check()){
            //ToDo
            $data["auth"] = true;
            $data["username"] = Auth::user()->name;
            $isAdmin=Auth::check()&&((Console::where('email','=',Auth::user()->email)->get()->count())>0);
            if ($isAdmin)
                $data["admin"] = true;
        }
        else{
            //ToDo
            //if not login
        }
        return view('index',$data);
    }


    /**
     *
     * Modify bulletin from index page.
     * require admin privileges
     *
     * @return \Illuminate\Http\Response
     *
     */
    public function modifyBulletin()
    {
        /**
         * 获取前端传来的公告栏内容（html代码），直接存入bulletin.html内
         * 暂时不需要检查其内容合法性（如是否存在script等）
         * 需要检查管理员权限！
         */
        $data = ["status"=> "",
            "errorcode"=>"0000",
            "message" => ""];
        $exists=Auth::check()&&((Console::where('email','=',Auth::user()->email)->get()->count())>0);
        if (!$exists)
        {
            //不是管理员，返回错误码
            $data = ["status"=> FAIL_MESSAGE,
                "errorcode"=>"7701",
                "message" => "没有权限！"];
        }
        else
        {
            //修改逻辑
            file_put_contents(public_path()."/bulletin.html", Request::get("contents"));
            $data['status'] = SUCCESS_MESSAGE;
            $data['message'] = "更新成功";
        }
        return response()->json($data);
    }

}
