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
            if (!$isAdmin&&($report->status&1)==0) continue;
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
            throw new FileIOException();
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
            $data["errorcode"]="7401";
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
            throw new FileIOException();
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
            $data["errorcode"]="7402";
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
    * return the xml form view from front
    * @param int $id
    * @return \Illuminate\Http\Response
    */
    public function getXmlForm($id)
    {
        $data = ["id"   =>  ""];
        //$experimentId = "";
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

    /**
    * Update a report.
    *
    * If you need to change something of the reports which have been published,just edit them.
    * First of all, you need to have an administrator identity.
    *
    * @return \Illuminate\Http\Response
    */
    public function updateReport()
    {
        $isAdmin=$this->userConfirm();
        if(!$isAdmin){
            $data['status'] = FAIL_MESSAGE;
            $data["errorcode"]="7403";
            $data['message'] = "没有权限";
            return response()->json($data);
        }
        $data = ["status"   =>  "",
                "errorcode"=>"0000",
                 "message"  =>  ""];
        $report = Report::where('experiment_id','=',Request::get('reportId'))->get()->count();
        $isPubReport = Report::where('experiment_id','=',Request::get('reportId'))->where("status",'=','1')->get()->count();
        $isSuperAdmin=Auth::check()&&((Console::where('email','=',Auth::user()->email)->where('atype','=','2')->get()->count())>0);

        if ($isPubReport && !$isSuperAdmin){
            $data['status'] = FAIL_MESSAGE;
            $data["errorcode"]="7404";
            $data['message'] = "没有更新权限，请联系超级管理员";
            return response()->json($data);
        }


        if($report){
            // $system1 = exec("echo -e \"".Request::get('reportScript')."\" > ".Config::get('phylab.scriptPath')."p".Request::get('reportId').".py",$output,$reval1);
            // $system2 = exec("echo -e \"".Request::get('reportHtml')."\" > ".Config::get('phylab.experimentViewPath').Request::get('reportId').".html",$output,$reval2);
            // $system3 = exec("echo -e \"".Request::get('reportTex')."\" > ".Config::get('phylab.scriptPath')."tex/Handle".Request::get('reportId').".tex",$output,$reval3);
            file_put_contents(Config::get('phylab.scriptPath')."p".Request::get('reportId').".py", Request::get('reportScript'));
            file_put_contents(Config::get('phylab.experimentViewPath').Request::get('reportId').".html", Request::get('reportHtml'));
            file_put_contents(Config::get('phylab.scriptPath')."tex/Handle".Request::get('reportId').".tex", Request::get('reportTex'));
            file_put_contents(Config::get('phylab.scriptPath')."markdown/Handle".Request::get('reportId').".md", Request::get('reportMD'));
            if(true){
                $data['status'] = SUCCESS_MESSAGE;
                $data['message'] = "更新成功";
            }
            /*else{
                $data['status'] = FAIL_MESSAGE;
                $data['message'] = "更新失败(write_err)";
            }*/
        }
        else{
            $data['status'] = FAIL_MESSAGE;
            $data["errorcode"]="7405";
            $data['message'] = "更新失败(wrong_id)";
        }
        return response()->json($data);        
        //return view("report.show",$data);
        //return json_encode($data,JSON_UNESCAPED_UNICODE);
    }

    /**
    * To publish a report.
    * 
    * @return \Illuminate\Http\Response
    */
    public function confirmReport()
    {
        $data = ["status"   =>  "",
                "errorcode"=>"0000",
                 "message"  =>  ""];

        /**
         * To publish a new experiment, you ought to be a super administrator.
         *
         * In our database, a super administrator means his atype is 2.
         * (Check the admin table, you'll know)
         */
        $isAdmin=Auth::check()&&((Console::where('email','=',Auth::user()->email)->where('atype','=','2')->get()->count())>0);
        if(!$isAdmin){
            $data['status'] = FAIL_MESSAGE;
            $data["errorcode"]="7406";
            $data['message'] = "没有发布权限，请联系超级管理员";
            return response()->json($data);
        }
        $report = Report::where('experiment_id','=',Request::get('reportId'))->first();
        if($report){
            // $results = DB::table('wc_category')->select('title',$report->experiment_tag)->get();
            // $results = DB::select('select * from wc_category where title = ?', [$report->experiment_tag]);
            // if ($results->count() == 0) {
            //     DB::insert('insert into wc_category (title, type, parent_id, sort) values (?, ?, ?, ?)', [$report->experiment_tag, 'question', 1, 0]);
            // }
            // $results = DB::select('select * from wc_category where title = ?', [$report->experiment_tag]);
            // $category_id = $results->first()->id;
            // $time = time();
            // DB::insert('insert into wc_article (uid, title, message, comments, views, add_time, has_attach, lock, votes, title_fulltext, category_id, is_recommend, sort) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [26, $lab_id.'总评论贴',$lab_id.'总评论贴',0,0,$time,0,0,0,$report->experiment_id." 3578035770",$category_id,0,0]);
            // $report->related_article = DB::select('select * from wc_article where uid = 26 and add_time = ?', [$time])->first()->id;
            
            // $remote_server = G_BASE_URL.'wecenter/?/publish/ajax/gen_article/';
            // $post_string = 'token=fFD*(U3jfj5f4&title='.$lab_id.'总讨论帖&message=有关'.$lab_id.'实验的问题都可以在这里讨论&user_id=27&category_id=1';
            // $ch = curl_init();
            // curl_setopt($ch,CURLOPT_URL,$remote_server);
            // curl_setopt($ch,CURLOPT_POSTFIELDS,$post_string);
            // curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
            // curl_exec($ch);
            // curl_close($ch);
            $report->status = 1;
            $report->save();
            $data['status'] = SUCCESS_MESSAGE;
            $data['message'] = "发布成功";
        }
        else{
            $data['status'] =FAIL_MESSAGE;
            $data["errorcode"]="7407";
            $data['message'] = "发布失败";
        }
        return response()->json($data);
        //return view("report.show",$data);
        //return json_encode($data,JSON_UNESCAPED_UNICODE);
    }

    /**
     * Delete a report.
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteUnpublishedReport()
    {
        $data = ["status"   =>  "",
            "errorcode"=>"0000",
            "message"  =>  ""];

        /**
         * A common administrator only can delete an unpublished experiment
         */
        $isAdmin=$this->userConfirm();
        if(!$isAdmin){
            $data['status'] = FAIL_MESSAGE;
            $data["errorcode"]="7408";
            $data['message'] = "没有权限";
            return response()->json($data);
        }

        $report = Report::where('experiment_id','=',Request::get('id'))->first();
        //try{
            $report = Report::where('experiment_id','=',Request::get('id'))->first();
            if(!$report){
                $data["status"] = FAIL_MESSAGE;
                $data["errorcode"]="7409";
                $data["message"] = "实验Id不存在";
                return response()->json($data);
            }

            if ($report->status != 0)
            {
                $data["status"] = FAIL_MESSAGE;
                $data["errorcode"]="7410";
                $data["message"] = "实验已发布，请联系超级管理员";
                return response()->json($data);
            }
            else{
                $system1 = exec("rm -rf ".Config::get('phylab.experimentViewPath').Request::get('id').".html",$output,$reval1);
                $system2 = exec("rm -rf ".Config::get('phylab.scriptPath')."p".Request::get('id').".py",$output,$reval2);
                $system3 = exec("rm -rf ".Config::get('phylab.scriptPath')."tex/Handle".Request::get('id').".tex",$output,$reval3);
                $system4 = exec("rm -rf ".Config::get('phylab.scriptPath')."markdown/Handle".Request::get('id').".md",$output,$reval4);
                /*
                if($reval1!=0||$reval2!=0||$reval3!=0){
                    $data["status"]=FAIL_MESSAGE;
                    $data["message"] = "删除报告文件失败: ".$reval1.' '.$reval2.' '.$reval3;
                    return response()->json($data);
                }
                */
                //else {
                    $delete = $report->delete();
                    /*
                    if (!$delete)
                    {
                        $data["status"]=FAIL_MESSAGE;
                        $data["message"] = "删除报告数据库条目失败";
                        return response()->json($data);
                    }
                    */
                //}

            }
            $data["status"]=SUCCESS_MESSAGE;
            $data["message"] = "删除成功！";
        //}
        /*
        catch(Exception $e){
            $data['status'] = FAIL_MESSAGE;
            $data['message'] = "未知错误";
        }
        */
        return response()->json($data);
    }
}
