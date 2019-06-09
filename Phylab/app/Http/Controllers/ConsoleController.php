<?php namespace App\Http\Controllers;

use App\Exceptions\App\InvalidFileFormatException;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Report;
use App\Models\Console;
use Auth;
use Config;
use Exception;
use Request;



class ConsoleController extends Controller {

    /**
     * Check user's identity
     */
    public function userConfirm()
    {
        $exists=Auth::check()&&((Console::where('email','=',Auth::user()->email)->get()->count())>0);
        $isAdmin=$exists;

        return $isAdmin;
    }

    /**
     * The index of console
     *
     * @return mixed
     */
	public function index() {
		$isAdmin=$this->userConfirm();
        /**
         * If the user is not an administrator, website will redirect to the index
         */
		if (!$isAdmin) {
			return redirect('/index');
		}
		//$ad=Console::where('email','=',Auth::user()->email)->first();
		//$st=$ad->status;
        $data = ["reportTemplates"=>[],
                 "username"=>Auth::user()->name,
				 "auth"=>$isAdmin,
				 "status"=>SUCCESS_MESSAGE,
                 "admin"=>$isAdmin
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
            $data["errorcode"]="7301";
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
            $data["errorcode"]="7302";
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
            $data["errorcode"]="7303";
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
            $data["errorcode"]="7304";
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
            $data["errorcode"]="7305";
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
            $data["errorcode"]="7306";
            $data['message'] = "没有权限";
            return response()->json($data);
        }

        $report = Report::where('experiment_id','=',Request::get('id'))->first();
        //try{
        $report = Report::where('experiment_id','=',Request::get('id'))->first();
        if(!$report){
            $data["status"] = FAIL_MESSAGE;
            $data["errorcode"]="7307";
            $data["message"] = "实验Id不存在";
            return response()->json($data);
        }

        if ($report->status != 0)
        {
            $data["status"] = FAIL_MESSAGE;
            $data["errorcode"]="7308";
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

    /**
     * Get what you edit in the html table
     *
     * @return mixed
     */
    public function getTable()
    {
		$result = [
			'status'=>'',
			'contents'=>'',
             'errorcode'=>'0000'];
        $isAdmin=$this->userConfirm();
        if (!$isAdmin) {
            return redirect('/index');
        }
        $id=$_GET['id'];
        //$id = "2160115" ;
        //$htmlFile = "/var/www/Phylab/resources/views/report/".$id.".html";
        $htmlFile = Config::get('phylab.experimentViewPath').$id.".html";
		try{
			$file = fopen($htmlFile, "r");
			$result['status'] = SUCCESS_MESSAGE;
			$result['contents'] = file_get_contents($htmlFile);
			fclose($file);
		}catch(Exception $e) {
			$result['status']=FAIL_MESSAGE;
			$result['errorcode']="7309";
		}
		return response()->json($result);
    }

    /**
     * Get what you edit in the python script table
     *
     * @return mixed
     */
    public function getScript()
    {
		$result = [
			'status'=>'',
            'errorcode'=>'0000',
			'contents'=>''];
		$isAdmin=$this->userConfirm();
		if (!$isAdmin) {
			return redirect('/index');
		}
		//$ad=Console::where('email','=',Auth::user()->email)->first();
		//$st=$ad->status;
        $id=$_GET['id'];
        $htmlFile = Config::get('phylab.scriptPath')."p".$id.".py";
        //return $htmlFile;
        //
        try{
            $file = fopen($htmlFile, "r");
            $result['status'] = SUCCESS_MESSAGE;
            $result['contents'] = file_get_contents($htmlFile);
            fclose($file);
        }catch (Exception $e){
            $result['status']=FAIL_MESSAGE;
            $result['errorcode']="7310";
        }
        /*
		if ($file==FALSE)
		    $result['status']=FAIL_MESSAGE;
		else
		{
			$result['status'] = SUCCESS_MESSAGE;
			$result['contents'] = file_get_contents($htmlFile);
			fclose($file);
		}
        */
        return response()->json($result);
    }

    /**
     * Get what you edit in the latex table
     *
     * @return mixed
     */
    public function getTex()
    {
		$result = [
			'status'=>'',
            'errorcode'=>'0000',
			'contents'=>''];
        $isAdmin=$this->userConfirm();
		if (!$isAdmin) {
			return redirect('/index');
		}
		//$ad=Console::where('email','=',Auth::user()->email)->first();
		//$st=$ad->status;
        $id=$_GET['id'];
        $htmlFile = Config::get('phylab.scriptPath')."tex/Handle".$id.".tex";
        //$file = fopen($htmlFile, "r");
		try{
            $file = fopen($htmlFile, "r");
            $result['status'] = SUCCESS_MESSAGE;
            $result['contents'] = file_get_contents($htmlFile);
            fclose($file);
        }catch (Exception $e){
            $result['status']=FAIL_MESSAGE;
            $result['errorcode']="7311";
        }
        /*
        if ($file==FALSE)
		    $result['status']=FAIL_MESSAGE;
		else
		{
			$result['status'] = SUCCESS_MESSAGE;
			$result['contents'] = file_get_contents($htmlFile);
			fclose($file);
		}
        */
        return response()->json($result);
    }


    /**
     * Get what you edit in the markdown table
     *
     * @return mixed
     */
    public function getMD()
    {
        $result = [
            'status'=>'',
            'errorcode'=>'0000',
            'contents'=>''];
        $isAdmin=$this->userConfirm();
        if (!$isAdmin) {
            return redirect('/index');
        }
        //$ad=Console::where('email','=',Auth::user()->email)->first();
        //$st=$ad->status;
        $id=$_GET['id'];
        $htmlFile = Config::get('phylab.scriptPath')."markdown/Handle".$id.".md";
        //$file = fopen($htmlFile, "r");
        try{
            $file = fopen($htmlFile, "r");
            $result['status'] = SUCCESS_MESSAGE;
            $result['contents'] = file_get_contents($htmlFile);
            fclose($file);
        }catch (Exception $e){
            $result['status']=FAIL_MESSAGE;
            $result['errorcode']="7312";
        }
        /*
        if ($file==FALSE)
		    $result['status']=FAIL_MESSAGE;
		else
		{
			$result['status'] = SUCCESS_MESSAGE;
			$result['contents'] = file_get_contents($htmlFile);
			fclose($file);
		}
        */
        return response()->json($result);
    }



    public function createSublab() {
        $isAdmin=$this->userConfirm();
		if (!$isAdmin) {
			return redirect('/index');
		}
		//$ad=Console::where('email','=',Auth::user()->email)->first();
		//$st=$ad->status;
        $lab_id=$_GET['LId'];
        $lab_name=$_GET['LName'];
        $lab_tag=$_GET['LTag'];
		$result=array('status'=>FAIL_MESSAGE,'errorcode'=>'7313','msg'=>"该报告号码已经存在" , 'message' => "创建新报告失败");
		if ((Report::where('experiment_id','=',$lab_id)->get()->count())==0) {
            /**
             * Move the three files to the right dictionary
             */
		    $htmlcmd = "cp ".Config::get('phylab.experimentViewPath')."template.html ".Config::get('phylab.experimentViewPath').$lab_id.".html";
		    $pycmd = "cp ".Config::get('phylab.scriptPath')."template.py ".Config::get('phylab.scriptPath')."p".$lab_id.".py";
		    $latexcmd = "cp ".Config::get('phylab.scriptPath')."tex/template.tex ".Config::get('phylab.scriptPath')."tex/Handle".$lab_id.".tex";
            $mdcmd = "cp ".Config::get('phylab.scriptPath')."markdown/template.md ".Config::get('phylab.scriptPath')."markdown/Handle".$lab_id.".md";
		    $htmlsed = "sed -i 's/%%LAB_SUBLAB_ID%%/".$lab_id."/g' ".Config::get('phylab.experimentViewPath').$lab_id.".html";
		    $pysed = "sed -i 's/%%LAB_SUBLAB_ID%%/".$lab_id."/g' ".Config::get('phylab.scriptPath')."p".$lab_id.".py";
		    $system1 = exec($htmlcmd,$output,$reval1);
            $system2 = exec($pycmd,$output,$reval2);
            $system3 = exec($latexcmd,$output,$reval3);
            $system6 = exec($mdcmd,$output,$reval6);
            $system4 = exec($htmlsed,$output,$reval4);
            $system5 = exec($pysed,$output,$reval5);
            if($reval1==0&&$reval2==0&&$reval3==0 && $reval4==0&&$reval5==0&&$reval6==0){
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
				$result['message'] = "创建新报告成功" ;
			}
			/*
			else {
                $data["status"]=FAIL_MESSAGE;
                $data["message"] = "创建新报告失败";				
			}
			*/
		}
		return response()->json($result);
	}

    /**
     * Upload the prepare pdf file for the new experiment
     *
     * @return mixed
     */
    public function uploadPreparePdf()
    {
        $isAdmin=$this->userConfirm();
        if (!$isAdmin) {
            return redirect('/index');
        }
        $data = ["status"=>"","message"=>"","errorcode"=>"0000"];

        /*
        if (Request::hasfile('prepare-pdf')){
            $testt = Request::file('prepare-pdf');
            //if ($testt instanceof SplFileInfo && $testt->getPath() != '')
            $data['message'] = $_FILES['prepare-pdf']['name'] ;
            //$data['status'] = $_FILES['prepare-pdf']['error'] ;
            return response()->json($data);
        }
        */


        if (Request::hasFile('prepare-pdf'))
        {
            $pdfFile = Request::file('prepare-pdf');
            $labID = $_POST['labID'];
            if (preg_match('/^pdf$/', $pdfFile->getClientOriginalExtension()) &&
                    $pdfFile->getSize() < Config::get('phylab.maxUploadSize'))
            {
                $fname = $labID . '.pdf'; //TODO use lab id instead
                $pdfFile->move(Config::get('phylab.preparePath'), $fname);
                $data['status']=SUCCESS_MESSAGE;
                $data['message']="上传成功";
            }
            else
            {
                $data["status"]=FAIL_MESSAGE;
                $data["errorcode"]="7314";
                $data["message"] = "上传失败，文件格式或大小不符合要求！";
            }
        }
        else
        {
            $data["status"]=FAIL_MESSAGE;
            $data["errorcode"]="7315";
            $data["message"] = "上传失败，没有找到文件！";
        }

        return response()->json($data);
    }

}
