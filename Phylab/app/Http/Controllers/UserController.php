<?php

namespace App\Http\Controllers;

use App\Models\Console;
use Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Auth;
use App\Exceptions\App\DatabaseOperatorException;
use App\Exceptions\App\InvalidRequestInputException;
use App\Exceptions\App\InvalidFileFormatException;
use App\Exceptions\App\FileIOException;
use Exception;
use Config;
use Storage;

class UserController extends Controller
{
    /**
     * Display  the page of managing user hemself.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $authed=Auth::check();

        if (!$authed) {
            return redirect('/index');
        }

        $exists=Auth::check()&&((Console::where('email','=',Auth::user()->email)->get()->count())>0);
        $isAdmin=$exists;
        $data = ["avatarPath"   =>  "",
                 "username"     =>  "",
                 "studentId"    =>  "",
                 "email"        =>  "",
                 "sex"          =>  "",
                 "grade"        =>  "",
                 "company"      =>  "",
                 "companyAddr" =>  "",
                 "birthday"     =>  "",
                 "introduction" =>  "",
                 "auth" => $authed,
                 "userId" => "",
                 "admin" => $isAdmin];
        $auth = Auth::user();
        //$data["avatarPath"] = $auth->avatar_path;
        $data["username"] = $auth->name;
        $data["studentId"] = $auth->student_id;
        $data["grade"] = $auth->grade ;
        $data["email"] = $auth->email;
        $data["sex"] = $auth->sex;
        $data["company"] = $auth->company;
        $data["companyAddr"] = $auth->company_addr;
        $data["birthday"] = $auth->birthday;
        $data["introduction"] = $auth->introduction;
        //$data["userId"] = $auth->id;

        $uid = sprintf("%09d", $auth->id);
        $dir1 = substr($uid, 0, 3);
        $dir2 = substr($uid, 3, 2);
        $dir3 = substr($uid, 5, 2);
        $size = "max";


        if (file_exists("/var/www/wecenter/uploads" . '/avatar/' . $dir1 . '/' . $dir2 . '/' . $dir3 . '/' . substr($uid, - 2) . '_avatar_' . $size . '.jpg'))
        {
            $data["avatarPath"] =  "http://47.94.228.157:8080/wecenter/uploads". '/avatar/' . $dir1 . '/' . $dir2 . '/' . $dir3 . '/' . substr($uid, - 2) . '_avatar_' . $size . '.jpg';
        }
        else
        {
            $data["avatarPath"] = "http://47.94.228.157:8080/wecenter/static" . '/common/avatar-' . $size . '-img.png';
        }


        //return json_encode($data,JSON_UNESCAPED_UNICODE);
        return view('user.index' , $data) ;
    }

    /**
     * Show the form for editing the user infomation.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $data = ["avatarPath"   =>  "",
                 "username"     =>  "",
                 "sex"          =>  "",
                 "company"      =>  "",
                 "companyAddr" =>  "",
                 "birthday"     =>  "",
                 "introduction" =>  "" ,
                 "student_id"    =>  ""];
        $auth = Auth::user();
        $data["avatarPath"] = $auth->avatar_path;
        $data["username"] = $auth->name;
        $data["sex"] = $auth->sex;
        $data["company"] = $auth->company;
        $data["companyAddr"] = $auth->company_addr;
        $data["birthday"] = $auth->birthday;
        $data["introduction"] = $auth->introduction;
        $data["student_id"] = $auth->student_id ;
        #return json_encode($data,JSON_UNESCAPED_UNICODE);
        return view("user.edit",$data);
    }

    /**
     * Update the user infomation .
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
        $data = ["status"       =>  "",
                 "status"       =>  ""];
        $validatorRules = array(
                'password' => 'confirmed|between:6,15',
                'username'  =>  'between:6,20',
                'birthday'  =>  'date'
            );
        $validatorAttributes = array(
                'password' => '密码',
                'username'  =>  '用户名',
                'birthday'  => '生日'  
            );
        postCheck($validatorRules,Config::get('phylab.validatorMessage'),$validatorAttributes);
        $userAttr = ['password'=>'password',
                     'username'=>'name',
                     'birthday'=>'birthday',
                     'sex'=>'sex',
                     'company'=>'company',
                     'companyAddr'=>'company_addr',
                     'introduction'=>'introduction'];
        try{
            foreach ($userAttr as $key => $value) {
                if(Request::has($key)){
                    Auth::user()->update([$value => Request::get($key)]);
                }
            }
            $data["status"] = SUCCESS_MESSAGE;
        }
        catch(Exception $e)
        {
            throw new DatabaseOperatorException();
        }
        return response()->json($data);
    }

    /**
     * set user's avatar
     *
     * @return \Illuminate\Http\Response
     */
    public function setAvatar()
    {
        $data = ["status"=>"","avatarPath"=>"","message"=>""];
        if(Request::hasFile('avatar'))
        {
            $avatar = Request::file('avatar');
            if(preg_match(Config::get('phylab.allowedFileFormat'), $avatar->getClientOriginalExtension())&&
               $avatar->getSize()<Config::get('phylab.maxUploadSize'))
            {
                $fname = getRandName().'.'.$avatar->getClientOriginalExtension();
                $avatar->move(Config::get('phylab.avatarPath'),$fname);
                $auth = Auth::user();
                try{
                    if($auth->avatar_path!=Config::get('phylab.defaultAvatarPath'))
                    {
                        Storage::disk('local_public')->delete('avatar/'.$auth->avatar_path);
                    }
                }
                catch(Exception $e)
                {
                    throw new FileIOException();
                }
                try{
                    $auth->avatar_path = $fname;
                    $auth->save();
                    $data["status"] = SUCCESS_MESSAGE;
                    $data["avatarPath"] = $fname;
                }
                catch(Exception $e)
                {
                    throw new DatabaseOperatorException();
                }
            }
            else{
                throw new InvalidFileFormatException();
            }
        }
        else{
            throw new InvalidRequestInputException("上传参数不正确");
        }
        return response()->json($data);
    }
}
