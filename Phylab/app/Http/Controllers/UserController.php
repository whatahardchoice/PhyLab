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

        $authed=Auth::check(); //使用Laravel内置组建检查是否登陆
        if (!$authed) {
            return redirect('/index'); //如果没登陆则重定向回主页
        }

        $exists=Auth::check()&&((Console::where('email','=',Auth::user()->email)->get()->count())>0); // 检查是否是管理员
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
                 "admin" => $isAdmin]; //这里返回管理员字段为顶栏所用
        //以下获取用户具体信息
        $auth = Auth::user();
        $data["avatarPath"] = $auth->avatar_path; //头像路径，为/avatar/文件夹下相对路径
        $data["username"] = $auth->name; //用户名
        $data["studentId"] = $auth->student_id; //学号
        $data["grade"] = $auth->grade ;//年级
        $data["email"] = $auth->email; //邮箱
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

/*
 *   这部分的作用是读取wecenter头像并显示在/user界面，但目前我们使用了两套头像系统。这部分仍需要改进
        if (file_exists("/var/www/wecenter/uploads" . '/avatar/' . $dir1 . '/' . $dir2 . '/' . $dir3 . '/' . substr($uid, - 2) . '_avatar_' . $size . '.jpg'))
        {
            $data["avatarPath"] =  env("SERVER_PAGE")."/wecenter/uploads". '/avatar/' . $dir1 . '/' . $dir2 . '/' . $dir3 . '/' . substr($uid, - 2) . '_avatar_' . $size . '.jpg';
        }
        else
        {
            $data["avatarPath"] = env("SERVER_PAGE")."/wecenter/static" . '/common/avatar-' . $size . '-img.png';
        }
  */
        /*
         * 目前的头像系统，首先用户注册后数据库的头像字段为NULL
         *      头像不存在或未设置的情况下，读取wecenter的默认头像
         *      否则读取/public/avatar/下用户设置的头像
         */
        if (!$auth->avatar_path||!file_exists(Config::get("phylab.avatarPath").$data["avatarPath"]))
        {
            $data["avatarPath"] = "wecenter/static" . '/common/avatar-' . $size . '-img.png';
            //$data["avatarPath"] =  env("SERVER_PAGE")."/wecenter/uploads". '/avatar/' . $dir1 . '/' . $dir2 . '/' . $dir3 . '/' . substr($uid, - 2) . '_avatar_' . $size . '.jpg';
        }
        else
        {
            $data['avatarPath'] = 'avatar/'.$data['avatarPath'];
        }


        //return json_encode($data,JSON_UNESCAPED_UNICODE);
        return view('user.index' , $data) ; //返回页面view
    }


    /**
     * 此函数负责更新用户信息
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
        /*
         *  data定义了返回信息
         *  validator用于内容的验证，目前由于用户界面不支持修改密码和生日，因此只用到了用户名字段
         */
        $data = ["status"       =>  "",
                "errorcode" =>"0000",
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

        /*
         * 定义POST中用户信息字段与数据库中字段的对应关系，并遍历每个字段更新数据库中的相应内容
         */
        $userAttr = ['password'=>'password',
                     'username'=>'name',
                     'birthday'=>'birthday',
                     'sex'=>'sex',
                     'company'=>'company',
                     'companyAddr'=>'company_addr',
                     'introduction'=>'introduction',
                     'grade'=>'grade'];
        try{ //遍历每个字段
            foreach ($userAttr as $key => $value) {
                if(Request::has($key)){ //如果请求中存在这一字段
                    Auth::user()->update([$value => Request::get($key)]); //将其值更新至数据库中
                }
            }
            $data["status"] = SUCCESS_MESSAGE; //返回成功信息
        }
        catch(Exception $e)
        {
            $data['status']=FAIL_MASSAGE;
            $data["errorcode"]="7201";
            throw new DatabaseOperatorException();
        }
        return response()->json($data);
    }

    /**
     * 头像设置函数
     *
     * @return \Illuminate\Http\Response
     */
    public function setAvatar()
    {

        /*
         * 头像设置的返回内容，avatarPath为前端使用的更新头像后的头像路径。在返回部分可以看到其为/public文件夹下的相对路径
         */
        $data = ["status"=>"","avatarPath"=>"","message"=>"","errorcode"=>"0000"];

        /*
         * 上传头像的步骤包括
         * 1. 检查头像文件是否存在，以及头像格式及大小是否符合要求。preg_match为一php函数，正则表达式检查
         * 2. 如果符合要求，则将头像赋予一随机命名并拷贝至/public/avatar/下（move函数）
         * 3. 在上一步基础上，首先删除数据库中保存的原有头像，然后将数据库中的头像条目更新。
         *
         * 以上三步都做完可以返回正确信息。
         *
         * 这部分目前存在的主要问题是：第三步中如果用户在wecenter端设置了头像，由于user表中头像为wecenter用户表中的视图之一，导致在
         * phylab端会出现用户头像路径不存在的问题，从而导致在删除一步报错
         */
        if(Request::hasFile('avatar'))
        {
            $avatar = Request::file('avatar');
            if(preg_match(Config::get('phylab.allowedFileFormat'), $avatar->getClientOriginalExtension())&&
               $avatar->getSize()<Config::get('phylab.maxUploadSize'))
            {
                $fname = getRandName().'.'.$avatar->getClientOriginalExtension();
                $avatar->move(Config::get('phylab.avatarPath'),$fname);
                $auth = Auth::user();
                //Auth::user()->update(['avatar_path' => $fname]);
                try{
                    if($auth->avatar_path!=Config::get('phylab.defaultAvatarPath') && !empty($auth->avatar_path))
                    {
                        //local_public定义见/config/filesystems.php，就是public文件夹
                        Storage::disk('local_public')->delete("/avatar/".$auth->avatar_path);
                    }
                }
                catch(Exception $e)
                {
                    $data["errorcode"]="7202";
                    return response()->json($data);
                    //throw new FileIOException(); //问题的主要所在
                }
                try{
                    //$auth->introduction = "asd" ;
                    $auth->avatar_path = $fname;
                    $auth->save();
                    $data["status"] = SUCCESS_MESSAGE;

                    $data["avatarPath"] = 'avatar/'.$fname;

                }
                catch(Exception $e)
                {
                    $data["errorcode"]="7203";
                    return response()->json($data);
                    //throw new DatabaseOperatorException();
                }
            }
            else{
                $data["errorcode"]="7204";
                return response()->json($data);
                //throw new InvalidFileFormatException();
            }
        }
        else{
            $data["errorcode"]="7205";
            return response()->json($data);
            //throw new InvalidRequestInputException("上传参数不正确");
        }
        return response()->json($data);
    }
}
