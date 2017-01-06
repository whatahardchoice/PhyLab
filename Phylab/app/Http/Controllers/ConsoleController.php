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
        $htmlFile = Config::get('phylab.experimentViewPath').$id.".html";
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
        $htmlFile = Config::get('phylab.scriptPath')."p".$id.".py";
        $file = fopen($htmlFile, "r");
		if ($file==FALSE) $result['status']=FAIL_MESSAGE; else
		{
			$result['status'] = SUCCESS_MESSAGE;
			$result['contents'] = fread($file,filesize($htmlFile));
		}
        return response()->json($result);
    }
	

    public function getTex()
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
        $htmlFile = Config::get('phylab.scriptPath')."tex/Handle".$id.".tex";
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
            $system1 = exec("touch ".Config::get('phylab.experimentViewPath').$lab_id.".html",$output,$reval1);
            $system2 = exec("touch ".Config::get('phylab.scriptPath')."p".$lab_id.".py",$output,$reval2);
            $system3 = exec("touch ".Config::get('phylab.scriptPath')."tex/Handle".$lab_id.".tex",$output,$reval3);
            if($reval1==0&&$reval2==0&&$reval3==0){    
				$ret=Report::create(array(
					'experiment_id'=>$lab_id,
					'experiment_name'=>$lab_name,
					'experiment_tag'=>$lab_tag,
	                'prepare_link' => '',
	                'related_article' => 5,
	                'status' => 0
				));
				$result['status']=SUCCESS_MESSAGE;
				$result['msg']="";
				$pysrc=Config::get('phylab.scriptPath')."p".$lab_id.".py";
				$htmsrc = Config::get('phylab.experimentViewPath').$lab_id.".html";
				file_put_contents($pysrc,"# coding here...");
				file_put_contents($htmsrc,"<table><tr><td>半径</td><td>高度</td></tr><tr><td> <input class='para form-control' type='number'/></td><td><input class='para form-control' type='number'/></td></tr></table>");
			} else {
                $data["status"]=FAIL_MESSAGE;
                $data["message"] = "创建新报告失败";				
			}
		}
		return response()->json($result);
	}

}
