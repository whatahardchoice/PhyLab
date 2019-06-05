<?php

namespace App\Http\Controllers;

use App\Models\Console;
use App\Models\Desexp;
use Request;
use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Exceptions\App\NoResourceException;
use Exception;

/**
 *
 * 设计性实验页面控制器
 *
 */

class DesexpController extends Controller
{
    /**
     *  返回设计性实验页面主页及实验个数信息
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $data = ["auth" => false ,"username"    =>  "", "admin"=>false, "expOptions"=>array(),"errorcode"=>"0000"];
        if(Auth::check()){
            //ToDo
            $data["auth"] = true;
            $data["username"] = Auth::user()->name;
            $exists=Auth::check()&&((Console::where('email','=',Auth::user()->email)->get()->count())>0);
            $isAdmin=$exists;
            if ($isAdmin)
                $data["admin"] = true;
        }
        else{
            $data["errorcode"]="7601";
        }
        $data['expOptions'] = Desexp::all("id",'name');
        return view("desexp.index", $data);
    }


    /**
     *  给定实验id，返回特定实验的html文件
     *
     *  post: {'id'};
     *
     */
    public function getDesexp($id)
    {

        $data = ['status'=>'', 'message'=> '', 'id'=>'', 'link'=>'', 'name' => '','errorcode'=>'0000'];

//        if(!Auth::check()) {
//            //如果没登录返回出错，前端重定向至登录页面。
//            $data["status"] = FAIL_MESSAGE;
//            $data["message"] = "未登录，请登陆后查看其他内容";
//            return response()->json($data);
//        }


        $report = Desexp::find($id);
        if($report){
            $data["id"]=$report->id;
            $data["link"] = $report->link;
            $data["name"] = $report->name;
            $data["status"] = SUCCESS_MESSAGE;
        }
        else{
            $data["status"] = FAIL_MESSAGE;
            $data["errorcode"]="7602";
            $data["message"] = "未找到实验".$id;
        }

        return response()->json($data);

    }




}