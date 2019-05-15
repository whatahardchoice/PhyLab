<?php

namespace App\Http\Controllers;

use App\Models\Console;
use Request;
use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;

/**
 *
 * 设计性实验页面控制器
 *
 */

class DesexpController extends Controller
{
    /**
     *
     *
     */
    public function index()
    {
        //显示主页
        $data = ["auth" => false ,"username"    =>  "", "admin"=>false];
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
            //ToDo
            //if not login
        }


        return view("desexp.index", $data);
    }




}