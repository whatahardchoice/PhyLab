<?php

namespace App\Http\Controllers;

use Request;
use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Console;
use Storage;
use App\Exceptions\App\FileIOException;
use App\Exceptions\App\NoResourceException;
use Exception;
use Config;
class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //看这个形式： $data = ["reportTemplate"=>[ ["id"=> "", "experimentId" => "","experimentName"=> ""] , [] ,.......] ]
        
        $data = ['reports'=>array(),
                'username'=>Auth::user()->name,
                'auth'=>true];
        $reports = Report::orderBy('experiment_tag')->get();
        foreach ($reports as $report) {
            $rearr = array(
                "id"=>$report->experiment_id,
                "experimentName"=>$report->experiment_name,
                "relatedArticle"=>$report->related_article
                );
            if(array_key_exists($report->experiment_tag,$data['reports'])){
                array_push($data['reports'][$report->experiment_tag],$rearr);
            }else{
                $data['reports'][$report->experiment_tag]=array($rearr);
            }
        }
        // return view("report.index",$data);
        return view("report.index",$data);
        #return json_encode($data,JSON_UNESCAPED_UNICODE);
    }

    /**
     * Show all reports file.
     *
     * @return \Illuminate\Http\Response
     */
    public function getAllReport(){
		$exists=Auth::check()&&((Console::where('email','=',Auth::user()->email)->get()->count())>0);
		$isAdmin=$exists;
		$ad=Console::where('email','=',Auth::user()->email)->first();
		$st=$ad->status;
        $data = ['reports'=>array()];
        $reports = Report::orderBy('experiment_tag')->get();
        foreach ($reports as $report) {
			if (!$isAdmin&&($report->status&1)==0) continue;
            $rearr = array(
                "id"=>$report->experiment_id,
                "experimentName"=>$report->experiment_name,
                "relatedArticle"=>$report->related_article,
                "status"=>$report->status
                );
            if(array_key_exists($report->experiment_tag,$data['reports'])){
                array_push($data['reports'][$report->experiment_tag],$rearr);
            }else{
                $data['reports'][$report->experiment_tag]=array($rearr);
            }
        }
        return response()->json($data);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //post 传入 xml 模板文件
        $data = ["status"=> "",
                 "experimentId" => "",
                 "link"  => "",
                 "message" => ""];
        $validatorRules = array(
                'id'  => 'required|integer|exists:reports,id',
                'xml' => 'required'
            );
        $validatorAttributes = array(
                'id'  => '生成报告ID',
                'xml' => '模板xml文件'
            );
        //postCheck($validatorRules,Config::get('phylab.validatorMessage'),$validatorAttributes);
        //ToDo
        //$xmlLink = getRandName().".xml";
        $tmpName = getRandName();
        try{
            Storage::put("xml_tmp/".$tmpName.'.xml',Request::get('xml'));
        }
        catch(Exception $e){
            throw new FileIOException();
        }
        // $report = Report::find(Request::get('id'));
        // $scriptLink = $report->script_link;
        $experimentId = Request::get('id');
        $output = array();
        $test = Config::get('phylab.scriptPath')."handler.py ".$experimentId.' '.Config::get('phylab.tmpXmlPath').$tmpName.'.xml '.Config::get('phylab.tmpReportPath').$tmpName;
        $system = exec(Config::get('phylab.scriptPath')."handler.py ".$experimentId.' '.Config::get('phylab.tmpXmlPath').$tmpName.'.xml '.Config::get('phylab.tmpReportPath').$tmpName,$output,$reval);
        if($reval==0){
            $system = json_decode($system);
                if($system->status== SUCCESS_MESSAGE){
                    $data["status"] = SUCCESS_MESSAGE;
                    $data["link"] = $tmpName.".pdf";
                    $data["experimentId"] = $experimentId;
                    $data["test"]= $test;
                }
        }else{
            $data["status"]=FAIL_MESSAGE;
            $data["message"]="生成脚本生成失败";
            $data["test"]= $test;
        }
        // if($scriptLink!=null){
		// 	$output = array();
        //     $system = exec(Config::get('phylab.scriptPath')."handler.py ".$experimentId.' '.Config::get('phylab.tmpXmlPath').$tmpName.' '.Config::get('phylab.tmpReportPath').$tmpName,$output,$reval);
        //     #echo Config::get('phylab.scriptPath')."create.sh ".Config::get('phylab.tmpReportPath')." ".Config::get('phylab.scriptPath').$scriptLink." ".Config::get('phylab.tmpXmlPath').$tmpName." ".Config::get('phylab.tmpReportPath').$tmpName;
        //     #echo $out;
        //     #echo $system."\n";
        //     #echo $reval."\n";
        //     #echo var_dump($output);
        //     if($reval==0){
        //         #echo $system.'\n';
        //         #echo "python ".storage_path()."/app/script/".$scriptLink." ".storage_path()."/app/xml_tmp/".$xmlLink." ".public_path()."/pdf_tmp/".$tmpName.".tex";
        //         $system = json_decode($system);
        //         if($system->status== SUCCESS_MESSAGE){
        //             $data["status"] = SUCCESS_MESSAGE;
        //             $data["link"] = $tmpName.".pdf";
        //             $data["experimentId"] = $experimentId;
        //         }
        //         else{
        //             $data["status"]=FAIL_MESSAGE;
        //             $data["message"]="生成脚本生成失败，请检查您的输入";
        //         }
        //     }
        //     else{
        //         $data["status"]=FAIL_MESSAGE;
        //         $data["message"]="似乎发生了系统级的错误";
        //     }
        // }
        // else{
        //     $data["status"]=FAIL_MESSAGE;
        //     $data["message"]="暂时未有生成模板的脚本";
        // }
        return response()->json($data);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = ["id"   =>  "",
                 "experimentId" =>  "",
                 "prepareLink" =>  "",
                 "experimentName"=> ""];
        $report = Report::find($id);
        if($report){
            $data["id"]=$report->id;
            $data["experimentId"]=$report->experiment_id;
            $data["experimentName"]=$report->experiment_name;
            $data["prepareLink"]=$report->prepare_link;
        }
        else{
            throw new NoResourceException();
        }
        #return view("report.show",$data);
        return json_encode($data,JSON_UNESCAPED_UNICODE);
    }

    /**
    * return the xml form view to front
    * @param int $id
    * @return \Illuminate\Http\Response
    */
    public function getXmlForm($id)
    {
        $data = ["id"   =>  ""];
        $experimentId = "";
        $report = Report::find($id);
        if($report){
            $experimentId = $report->experiment_id;
            $data["id"] = $id;
        }
        else{
            throw new NoResourceException();
        }
        return view("report.xmlForm.".$experimentId,$data);
    }

    /**
    * download the tmp report.
    * @param string $link
    * @return \Illuminate\Http\Response
    */
    public function downloadReport($experimentId,$link)
    {
        return response()->download(Config::get('phylab.tmpReportPath').$link, $experimentId.".pdf");
    }
}
