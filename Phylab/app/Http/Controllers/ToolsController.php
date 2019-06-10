<?php namespace App\Http\Controllers;
use Auth;
use App\Http\Controllers\Controller;

class ToolsController extends Controller {
	public function index(){
	$data = ["auth" => false ,"username"    =>  "","admin"=>false];
	if(Auth::check()){
            //ToDo
            $data["auth"] = true;
            $data["username"] = Auth::user()->name;
        }
	//return view('tools.index',$data);
	return view('tools.main',$data);
	}

	// public function main(){
	// $data = ["auth" => false ,"username"    =>  ""];
	// if(Auth::check()){
            // //ToDo
            // $data["auth"] = true;
            // $data["username"] = Auth::user()->name;
        // }
	// return view('tools.main',$data);
	// }
}


