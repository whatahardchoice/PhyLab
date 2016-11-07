<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class DebugScriptController extends Controller {

    public function index(){
		$id = $_GET['id'];
		echo $id;
		return;
	}

}