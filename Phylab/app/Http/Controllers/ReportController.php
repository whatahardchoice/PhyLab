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
use DB;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class ReportController extends Controller
{
    /**
     * Confirm user's identity
     * Because if a user is an administrator, he can see the experiments
     * that have not been published and the entrance of console
     */
    public function userConfirm()
    {
        $exists=Auth::check()&&((Console::where('email','=',Auth::user()->email)->get()->count())>0);
        $isAdmin=$exists;

        return $isAdmin;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //看这个形式： $data = ["reportTemplate"=>[ ["id"=> "", "experimentId" => "","experimentName"=> ""] , [] ,.......] ]

		$isAdmin=$this->userConfirm();

        $data = ['reports'=>array(),
            'username'=>Auth::user()->name,
            'auth'=>true,
            'admin' => $isAdmin];
        $reports = Report::orderBy('experiment_tag')->get();
        foreach ($reports as $report) {
            if (!$isAdmin&&($report->status&1)==0)
                continue;
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
        $isAdmin=$this->userConfirm();
		if ($isAdmin) {
			$ad=Console::where('email','=',Auth::user()->email)->first();
			$st=$ad->status;
		} else $st=0;
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
     * This function to create the pdf file
     *
     * @return \Illuminate\Http\Response
     */
    public function createTex()
    {
        //post 传入 xml 模板文件
        $data = ["status"=> "",
            "errorcode"=>"0000",
            "experimentId" => "",
            "link"  => "",
            "message" => "",
            "errorLog"=>array()];
        /**
         * $validatorRules = array(
         * 'id'  => 'required|integer|exists:reports,id',
         * 'xml' => 'required'
         * );
         * $validatorAttributes = array(
         * 'id'  => '生成报告ID',
         * 'xml' => '模板xml文件'
         * );
         */
        //postCheck($validatorRules,Config::get('phylab.validatorMessage'),$validatorAttributes);
        //ToDo
        //$xmlLink = getRandName().".xml";

        //give a random name to the pdf file or html file created
        $tmpName = getRandName();

        try{
            Storage::put("xml_tmp/".$tmpName.'.xml',Request::get('xml'));
        }
        catch(Exception $e){
            $data["errorcode"]="7401";
            $resp = response()->json($data);
            return $resp;
            //throw new FileIOException();
        }
        // $report = Report::find(Request::get('id'));
        // $scriptLink = $report->script_link;
        $experimentId = Request::get('id');
        $output = array();
        $test = Config::get('phylab.scriptPath')."handler.py ".$experimentId.' '.Config::get('phylab.tmpXmlPath').$tmpName.'.xml '.Config::get('phylab.tmpReportPath').$tmpName;

        /**
         * The code below is the key to drive python scripts(tex -> pdf) to handle the data
         *
         * We need three parameters.
         * $experimentId -> which experiment it is
         * get('phylab.tmpXmlPath').$tmpName -> where is the data
         * get('phylab.tmpReportPath').$tmpName -> where should the created file be put
         *
         * The last line of python scripts return will be the value of $system
         *
         * The value of $reval will be what the code exit with
         */
        $system = exec('timeout 120 python3 '. Config::get('phylab.scriptPath')."handler.py ".$experimentId.' '.Config::get('phylab.tmpXmlPath').$tmpName.'.xml '.Config::get('phylab.tmpReportPath').$tmpName,$output,$reval);

        foreach ($output as &$value) {
            utf8_encode($value);
        }

        /**
         * In our python script, we get different return value when successful or failed
         *
         * success -> $system will be {"status":"success"}
         * failed -> $system will be {"status":"fail", "msg":"exception"}
         * known as json code
         *
         * Of course if something is wrong, $reval will be an exceptional value
         */

        if($reval==0){
            $system = json_decode($system);
            if($system->status== SUCCESS_MESSAGE){
                $data["status"] = SUCCESS_MESSAGE;
                $data["link"] = $tmpName.".pdf";
                $data["experimentId"] = $experimentId;
                $data["test"]= $test;
                $data["errorLog"]=$output;
            }
        }else{
            $data["status"]=FAIL_MESSAGE;
            $data["errorcode"]="7402";
            $data["message"]="生成脚本生成失败: ". $system;
            $data["test"]= $test;

            $data["errorLog"]=$output;
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
        $resp = response()->json($data);
        return $resp;

    }

    /**
     * This function is to create the html file
     *
     * Most parts are same as createTex
     */

    public function createMD()
    {
        //post 传入 xml 模板文件
        $data = ["status"=> "",
            "errorcode"=>"0000",
            "experimentId" => "",
            "link"  => "",
            "message" => "",
            "errorLog"=>array()];
        /**
         * $validatorRules = array(
         * 'id'  => 'required|integer|exists:reports,id',
         * 'xml' => 'required'
         * );
         * $validatorAttributes = array(
         * 'id'  => '生成报告ID',
         * 'xml' => '模板xml文件'
         * );
         */
        //postCheck($validatorRules,Config::get('phylab.validatorMessage'),$validatorAttributes);
        //ToDo
        //$xmlLink = getRandName().".xml";
        $tmpName = getRandName();
        try{
            Storage::put("xml_tmp/".$tmpName.'.xml',Request::get('xml'));
        }
        catch(Exception $e){
            $data["errorcode"]="7403";
            $resp = response()->json($data);
            return $resp;
            //throw new FileIOException();
        }
        // $report = Report::find(Request::get('id'));
        // $scriptLink = $report->script_link;
        $experimentId = Request::get('id');
        $output = array();
        $test = Config::get('phylab.scriptPath')."handler_md.py ".$experimentId.' '.Config::get('phylab.tmpXmlPath').$tmpName.'.xml '.Config::get('phylab.tmpReportPath').$tmpName;

        /**
         * There we need markdown python script
         */
        $system = exec('timeout 120 python3 '. Config::get('phylab.scriptPath')."handler_md.py ".$experimentId.' '.Config::get('phylab.tmpXmlPath').$tmpName.'.xml '.Config::get('phylab.tmpReportPath').$tmpName,$output,$reval);
        if($reval==0){
            $system = json_decode($system);
            if($system->status== SUCCESS_MESSAGE){
                $data["status"] = SUCCESS_MESSAGE;
                $data["link"] = $tmpName.".html";
                $data["experimentId"] = $experimentId;
                $data["test"]= $test;
                $data["errorLog"]=$output;
            }
        }else{
            $data["status"]=FAIL_MESSAGE;
            $data["errorcode"]="7404";
            $data["message"]="生成脚本生成失败: ". $system;
            $data["test"]= $test;
            $data["errorLog"]=$output;
        }
        $resp = response()->json($data);
        return $resp;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
/*
    public function show($id)
    {
        $data = ["id"   =>  "",
                 "experimentId" =>  "",
                 "prepareLink" =>  "",
                "errorcode"=>"0000",
                 "experimentName"=> ""];
        $report = Report::find($id);
        if($report){
            $data["id"]=$report->id;
            $data["experimentId"]=$report->experiment_id;
            $data["experimentName"]=$report->experiment_name;
            $data["prepareLink"]=$report->prepare_link;
        }
        else{
            $data["errorcode"]="7405";
            throw new NoResourceException();
        }
        #return view("report.show",$data);
        return json_encode($data,JSON_UNESCAPED_UNICODE);
    }
*/

    /**
    * return the xml form view from front
    * @param int $id
    * @return \Illuminate\Http\Response
    */
    /*
    public function getXmlForm($id)
    {
        $data = ["id"   =>  "","errorcode"=>"0000"];
        //$experimentId = "";
        $report = Report::find($id);
        if($report){
            $experimentId = $report->experiment_id;
            $data["id"] = $id;
        }
        else{
            $data["errorcode"]="7406";
            throw new NoResourceException();
        }
        return view("report.xmlForm.".$experimentId,$data);
    }
    */

    /**
    * download the tmp report.
    * @param string $link
    * @return \Illuminate\Http\Response
    */
    /*
    public function downloadReport($experimentId,$link)
    {
        return response()->download(Config::get('phylab.tmpReportPath').$link, $experimentId.".pdf");
    }
    */


    /**
     * get input table html file for specific lab
     *
     */
    public function getTable()
    {
        $id=$_GET['id'];
        $htmlFile = Config::get("phylab.experimentViewPath").$id.".html";
        try {
            $file = fopen($htmlFile, "r");
            $html = fread($file,filesize($htmlFile));
        } catch (Exception $e) {
            $data = ["status"=>'',"errorcode"=>"7405"];
            $data["status"]=FAIL_MESSAGE;

            return response()->json($data);
        }
        return $html;
    }

}
