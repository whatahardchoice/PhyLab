<?php

namespace App\Http\Controllers;

use Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Models\Star;
use App\Models\User;
use Storage;
use Config;
use App\Exceptions\App\NoResourceException;
use Exception;
use Auth;
use App\Exceptions\App\FileIOException;
use App\Exceptions\App\DatabaseOperatorException;
use App\Exceptions\Star\ReachCeilingException;
use App\Models\Report;
class StarController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = ["stars"=>[]];
        $stars = Auth::user()->stars()->get();
        foreach ($stars as $star) {
            $rearr = array(
                "id" => $star->id,
                "name" => $star->name,
                "link" => $star->link,
                "time" => toTimeZone($star->created_at)
                );
            array_push($data["stars"],$rearr);
        }
        return view("star.index",$data);
        #return json_encode($data,JSON_UNESCAPED_UNICODE);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
       
        $data = ["status"=>FAIL_MESSAGE,
                 "message"=>"访问正常",
                 "id"=>""];
        //return response()->json($data);
        $validatorRules = array(
                'link' => 'required',
                'reportId'  =>  'required|integer'
            );
        $validatorAttributes = array(
                'link' => '临时报告链接',
                'reportId'  =>  '报告模板类别'
            );
        try{
            postCheck($validatorRules,Config::get('phylab.validatorMessage'),$validatorAttributes);
        }
            catch(Exception $e){
                $data["status"] = FAIL_MESSAGE;
                $data["message"] = "检查失败";
                return response()->json($data);
        }
        if(Storage::disk('local_public')->exists('pdf_tmp/'.Request::get('link'))){
            //$report = Report::find(Request::get('reportId'));
            try{
                $report = Report::where('experiment_id','=',Request::get('reportId'))->get();
                if($report->count() == 0){
                    $data["status"] = FAIL_MESSAGE;
                    $data["message"] = "没有此类型报告";
                    return response()->json($data);
                }
                $experimentName = $report->first()->experiment_name;
            }catch(Exception $e){
                $data["status"] = FAIL_MESSAGE;
                $data["message"] = "报告查询失败";
                $data['reportnumber']= $report->count();
                $data['reportId'] = Request::get('reportId');
                return response()->json($data);
            }
            try{
                if(Auth::user()->stars()->count()<=Config::get('phylab.starMaxCount'))
                {
                    $star = Star::create([
                        'link' => Request::get('link'),
                        'name' => 'Lab_'.Request::get('reportId').'_'.$experimentName.'_report',
                        'user_id' => Auth::user()->id,
                        'report_id' => Request::get('reportId')
                        ]);
                    if($star){
                        try{
                            Storage::disk('local_public')->copy('pdf_tmp/'.Request::get('link'),'star_pdf/'.Request::get('link'));
                        }
                        catch(Exception $e)
                        {
                            $star->delete();
                            throw new FileIOException();
                        }
                        $data["status"] = SUCCESS_MESSAGE;
                        $data["id"]=$star->id;
                        $data["message"] = "收藏报告成功";
                    }
                    else{
                        $data["status"] = FAIL_MESSAGE;
                        $data["message"] = "收藏报告失败";
                    }
                }
                else
                {
                    $data["status"] = FAIL_MESSAGE;
                    $data["message"] = "超过收藏最大值";
                    //throw new ReachCeilingException();
                }
            }catch(Exception $e){
                $data["status"] = FAIL_MESSAGE;
                $data["message"] = "收藏创建失败";
                return response()->json($data);
            }
        }
        else{
            $data["status"] = FAIL_MESSAGE;
            $data["message"] = "不存在pdf文件";
            //throw new NoResourceException();
        }
        
        //注意通过传入的临时文件地址来转移文件
        return response()->json($data);
    }

    /**
    * Delete the Star
    * @return \Illuminate\Http\Response
    */
    public function delete(){
        $data = ["status"=>"",
                 "messgae"=>""];
        $validatorRules = array(
                'id' => 'required|integer|exists:stars,id,user_id,'.Auth::user()->id
            );
        $validatorAttributes = array(
                'id' => '收藏的对象'
            );
        //postCheck($validatorRules,Config::get('phylab.validatorMessage'),$validatorAttributes);
        try{
            $link = Star::find(Request::get('id'))->link;
            Star::destroy(Request::get('id'));
            try{
                Storage::disk('local_public')->delete('star_pdf/'.$link);
            }
            catch(Exception $e)
            {
                $data["status"] = FAIL_MESSAGE;
                //return response()->json($data);
                //throw new FileIOException();
            }
            $data["status"] = SUCCESS_MESSAGE;
        }
        catch(Exception $e)
        {
            $data["status"] = FAIL_MESSAGE;
            //return response()->json($data);
            //throw new DatabaseOperatorException();
        }
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
        $data = ["username"     =>  "",
                 "link"         =>  "",
                 "createTime"   =>  "",
                 "name"         =>  "",
                 "experimentId" =>  "",
                 "experimentName"   =>  ""];
        $star = Star::find($id);
        if($star && $star->user->id==Auth::user()->id){
            $data["username"] = $star->user->name;
            $data["link"] = $star->link;
            $data["createTime"] = $star->created_at;
            $data["experimentName"] = $star->report->experiment_name;
            $data["experimentId"]   = $star->report->experiment_id;
            $data["name"]           = $star->name;
        }
        else{
            throw new NoResourceException();
        }
        return view("star.show",$data);
        #return json_encode($data,JSON_UNESCAPED_UNICODE);
    }

    /**
    *Download the stared report
    * @param int $id
    * @return \Illuminate\Http\Response
    */
    public function download($id)
    {
        $reportLink = "";
        $experimentId = "";
        $star = Star::find($id);
        if($star && $star->user->id==Auth::user()->id){
            $reportLink = $star->link;
            $experimentId = $star->report->experiment_id;
        }
        else{
            throw new NoResourceException();
        }
        return response()->download(Config::get('phylab.starPath').$reportLink,$experimentId.".pdf");
    }

}
