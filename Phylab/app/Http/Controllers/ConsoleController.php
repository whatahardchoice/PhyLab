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
			$result['errorcode']="7301";
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
            $result['errorcode']="7302";
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
            $result['errorcode']="7303";
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
            $result['errorcode']="7304";
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
		$result=array('status'=>FAIL_MESSAGE,'errorcode'=>'7305','msg'=>"该报告号码已经存在" , 'message' => "创建新报告失败");
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
                $data["errorcode"]="7306";
                $data["message"] = "上传失败，文件格式或大小不符合要求！";
            }
        }
        else
        {
            $data["status"]=FAIL_MESSAGE;
            $data["errorcode"]="7307";
            $data["message"] = "上传失败，没有找到文件！";
        }

        return response()->json($data);
    }

}
