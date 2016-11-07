<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class DebugScriptController extends Controller {

    public function ha(){
		// $id = $_GET['id'];
		header('Location: https://www.baidu.com/');
		return 1;
	}

}