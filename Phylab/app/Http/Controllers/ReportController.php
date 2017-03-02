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
        $exists=Auth::check()&&((Console::where('email','=',Auth::user()->email)->get()->count())>0);
		$isAdmin=$exists;
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
		$exists=Auth::check()&&((Console::where('email','=',Auth::user()->email)->get()->count())>0);
		$isAdmin=$exists;
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

    /**
    * new a report.
    * 
    * @return \Illuminate\Http\Response
    */
    public function newReport()
    {
        $data = ["status"   =>  "",
                 "message"  =>  ""];
        try{
            $reports = Report::where('experiment_id','=',Request::get('reportId'))->get();
            if($reports->count() > 0){
                $data["status"] = FAIL_MESSAGE;
                $data["message"] = "实验Id已存在";
            }else{
                $system1 = exec("touch ".Config::get('phylab.experimentViewPath').Request::get('reportId').".html",$output,$reval1);
                $system2 = exec("touch ".Config::get('phylab.scriptPath')."p".Request::get('reportId').".py",$output,$reval2);
                $system3 = exec("touch ".Config::get('phylab.scriptPath')."tex/Handle".Request::get('reportId').".tex",$output,$reval3);

                if($reval1==0&&$reval2==0&&$reval3==0){    
                    $newRep = Report::create([
                        'experiment_id' => Request::get('reportId'),
                        'experiment_tag' => Request::get('reportTag'),
                        'experiment_name' => Request::get('reportName'),
                        'prepare_link' => '',
                        'related_article' => 5,
                        'status' => 0
                    ]);
                    if($newRep){
                        $data["status"] = SUCCESS_MESSAGE;
                        $data["message"] = "创建新报告成功";
                    }
                    else{
                        $data["status"] = FAIL_MESSAGE;
                        $data["message"] = "创建新报告失败";
                    }
                }else{
                    $data["status"]=FAIL_MESSAGE;
                    $data["message"] = "创建新报告失败";
                }
            }
        }
        catch(Exception $e){
            $data['status'] = FAIL_MESSAGE;
            $data['message'] = "未知错误";
        }
        return response()->json($data);
    }

    /**
    * update a report.
    * 
    * @return \Illuminate\Http\Response
    */
    public function updateReport()
    {
        $isAdmin=Auth::check()&&((Console::where('email','=',Auth::user()->email)->get()->count())>0);
        if(!$isAdmin){
            $data['status'] = FAIL_MESSAGE;
            $data['message'] = "没有权限";
            return response()->json($data);
        }
        $data = ["status"   =>  "",
                 "message"  =>  ""];
        $report = Report::where('experiment_id','=',Request::get('reportId'))->get()->count();
        if($report){
            // $system1 = exec("echo -e \"".Request::get('reportScript')."\" > ".Config::get('phylab.scriptPath')."p".Request::get('reportId').".py",$output,$reval1);
            // $system2 = exec("echo -e \"".Request::get('reportHtml')."\" > ".Config::get('phylab.experimentViewPath').Request::get('reportId').".html",$output,$reval2);
            // $system3 = exec("echo -e \"".Request::get('reportTex')."\" > ".Config::get('phylab.scriptPath')."tex/Handle".Request::get('reportId').".tex",$output,$reval3);
            file_put_contents(Config::get('phylab.scriptPath')."p".Request::get('reportId').".py", Request::get('reportScript'));
            file_put_contents(Config::get('phylab.experimentViewPath').Request::get('reportId').".html", Request::get('reportHtml'));
            file_put_contents(Config::get('phylab.scriptPath')."tex/Handle".Request::get('reportId').".tex", Request::get('reportTex'));
            if(true){
                $data['status'] = SUCCESS_MESSAGE;
                $data['message'] = "更新成功";
            }else{
                $data['status'] = FAIL_MESSAGE;
                $data['message'] = "更新失败(write_err)";
            }
        }
        else{
            $data['status'] = FAIL_MESSAGE;
            $data['message'] = "更新失败(wrong_id)";
        }
        return response()->json($data);        
        //return view("report.show",$data);
        //return json_encode($data,JSON_UNESCAPED_UNICODE);
    }

    /**
    * update a report.
    * 
    * @return \Illuminate\Http\Response
    */
    public function confirmReport()
    {
        $data = ["status"   =>  "",
                 "message"  =>  ""];
        $isAdmin=Auth::check()&&((Console::where('email','=',Auth::user()->email)->get()->count())>0);
        if(!$isAdmin){
            $data['status'] = FAIL_MESSAGE;
            $data['message'] = "没有权限";
            return response()->json($data);
        }
        $report = Report::where('experiment_id','=',Request::get('reportId'))->first();
        if($report){
            DB::transaction(function () {
                $report->status = 1;
                $results = DB::select('select * from wc_category where title = ?', [$report->experiment_tag]);
                // if ($results->count() == 0) {
                //     DB::insert('insert into wc_category (title, type, parent_id, sort) values (?, ?, ?, ?)', [$report->experiment_tag, 'question', 1, 0]);
                // }
                // $results = DB::select('select * from wc_category where title = ?', [$report->experiment_tag]);
                // $category_id = $results->first()->id;
                // $time = time();
                // DB::insert('insert into wc_article (uid, title, message, comments, views, add_time, has_attach, lock, votes, title_fulltext, category_id, is_recommend, sort) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)', [26, $lab_id.'总评论贴',$lab_id.'总评论贴',0,0,$time,0,0,0,$report->experiment_id." 3578035770",$category_id,0,0]);
                // $report->related_article = DB::select('select * from wc_article where uid = 26 and add_time = ?', [$time])->first()->id;
                $report->save();
            });
            $data['status'] = SUCCESS_MESSAGE;
            $data['message'] = "发布成功";
        }
        else{
            $data['status'] = FAIL_MESSAGE;
            $data['message'] = "发布失败";
        }
        return response()->json($data);
        //return view("report.show",$data);
        //return json_encode($data,JSON_UNESCAPED_UNICODE);
    }
}
