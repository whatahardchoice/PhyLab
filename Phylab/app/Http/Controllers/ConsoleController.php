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
				 "status"=>$st
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
        $id=$_GET['id'];
        $htmlFile = "/var/www/buaaphylab/resources/views/report/".$id.".html";
        $file = fopen($htmlFile, "r");
        $html = fread($file,filesize($htmlFile));
        return $html;
    }

    public function getScript()
    {
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
		if ($file==FALSE) $sc=""; else
        $sc = fread($file,filesize($htmlFile));
        return $sc;
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
		$result=array('status'=>-1,'msg'=>"该报告号码已经存在");
		if ((Report::where('experiment_id','=',$lab_id)->get()->count())==0) {
			$ret=Report::create(array(
				'experiment_id'=>$lab_id,
				'experiment_name'=>$lab_name,
				'experiment_tag'=>$lab_tag
			));
			$result['status']=0;
			$result['msg']="ok";
		}
		return response()->json($result);
	}

}