<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Console;
use Auth;

class ConsoleController extends Controller {

	public function index() {
		if (Auth::check()) {
			var_dump(Console::where('email','=',Auth::user()->email)->get());
		} else return "fasle";
		$exists=Auth::check()&&Console::where('email','=',Auth::user()->email)->get()->count()>0;
		$isAdmin=$exists;
		if (!$isAdmin) {
			header("Location:".URL::to('/'));
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

}