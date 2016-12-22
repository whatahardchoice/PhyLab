<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Console;
use Auth;

class ConsoleController extends Controller {

	public function index() {
		$exists=Auth::check()&&((Console::where('email','=',Auth::user()->email)->get()->count())>0);
		$isAdmin=$exists;
		if (!$isAdmin) {
			return redirect('/index');
		}
		$ad=Console::where('email','=',Auth::user()->email)->first();
		$st=$ad->status;
        $data = ["reportTemplates"=>[],
                 "username"=>Auth::user()->name,
				 "auth"=>$isAdmin,
				 "status"=>SUCCESS_MESSAGE
				 ];
        $reports = Report::orderBy('experiment_id')->get();
        foreach ($reports as $report) {
            $rearr = array(
                "id"=>$report->id,
                "experimentId"=>$report->experiment_id,
                "experimentName"=>$report->experiment_name,
                "prepareLink"=>$report->prepare_link
                );
            array_push($data["reportTemplates"],$rearr);
        }
        return view("console.index",$data);
	}

    public function getTable()
    {
		$result = [
			'status'=>'',
			'contents'=>''];
        $id=$_GET['id'];
        $htmlFile = "/var/www/buaaphylab/resources/views/report/".$id.".html";
		try{
			$file = fopen($htmlFile, "r");
			$result['status'] = SUCCESS_MESSAGE;
        	$result['contents'] = fread($file,filesize($htmlFile));
		}catch(Exception $e) {
			$result['status']=FAIL_MESSAGE;
		}
		return response()->json($result);
    }

    public function getScript()
    {
		$result = [
			'status'=>'',
			'contents'=>''];
		$exists=Auth::check()&&((Console::where('email','=',Auth::user()->email)->get()->count())>0);
		$isAdmin=$exists;
		if (!$isAdmin) {
			return redirect('/index');
		}
		$ad=Console::where('email','=',Auth::user()->email)->first();
		$st=$ad->status;
        $id=$_GET['id'];
        $htmlFile = "/var/www/buaaphylab/storage/app/script/p".$id.".py";
        $file = fopen($htmlFile, "r");
		if ($file==FALSE) $result['status']=FAIL_MESSAGE; else
		{
			$result['status'] = SUCCESS_MESSAGE;
			$result['contents'] = fread($file,filesize($htmlFile));
		}
        return response()->json($result);
    }
	
	public function createSublab() {
		$exists=Auth::check()&&((Console::where('email','=',Auth::user()->email)->get()->count())>0);
		$isAdmin=$exists;
		if (!$isAdmin) {
			return redirect('/index');
		}
		$ad=Console::where('email','=',Auth::user()->email)->first();
		$st=$ad->status;
        $lab_id=$_GET['LId'];
        $lab_name=$_GET['LName'];
        $lab_tag=$_GET['LTag'];
		$result=array('status'=>FAIL_MESSAGE,'msg'=>"该报告号码已经存在");
		if ((Report::where('experiment_id','=',$lab_id)->get()->count())==0) {
			$ret=Report::create(array(
				'experiment_id'=>$lab_id,
				'experiment_name'=>$lab_name,
				'experiment_tag'=>$lab_tag
			));
			$result['status']=SUCCESS_MESSAGE;
			$result['msg']="";
			$pysrc="/var/www/buaaphylab/storage/app/script/p".$lab_id.".py";;
			$htmsrc = "/var/www/buaaphylab/resources/views/report/".$lab_id.".html";
			file_put_contents($pysrc,"# coding here...");
			file_put_contents($htmsrc,"<table><tr><td>半径</td><td>高度</td></tr><tr><td> <input class='para form-control' type='number'/></td><td><input class='para form-control' type='number'/></td></tr></table>");
		}
		return response()->json($result);
	}

}
