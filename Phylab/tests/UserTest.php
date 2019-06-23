<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\View\View;
//use App\Http\Controllers\Auth;
use App\Models\User;


class UserTest extends TestCase
{

    use WithoutMiddleware ;
    protected $preserveGlobalState = FALSE;
    protected $runTestInSeparateProcess = TRUE;

    //测试账号1
    protected $gen_admin_username = 'console_test' ;
    protected $gen_admin_email = '123456@test.com' ;
    protected $gen_admin_password = '123456' ;
    //测试账号2
    protected $super_admin_username = 'zgj982393649' ;
    protected $super_admin_email = '982393649@qq.com' ;
    protected $super_admin_password = '199808151' ;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    /**
     *
     * 测试index
     */
    public function  testIndex(){
        //未登录，重定向
        $this->get('/logout') ;
        Auth::logout() ;
        $this->visit('/index')
             ->see('登录') ;
        $this->get('/user')
             ->assertRedirectedTo('/index');
        //登录
        $this->visit('/login')
             ->see('登录')
             ->type($this->gen_admin_email , 'email')
             ->type($this->gen_admin_password , 'password')
             ->press('登录');
        //是否正常返回视图数据
        $response = $this->call('GET','/user') ;
        self::assertResponseOk($response) ;
        $view = $response->original ;
        self::assertTrue($view instanceof View) ;
        $data = $view->getData() ;
        //self::assertTrue(is_array($data)) ;

        //视图数据结构是否正确
        $data_struct = ["avatarPath",
            "username",
            "studentId",
            "email",
            "sex",
            "grade",
            "company",
            "companyAddr",
            "birthday",
            "introduction",
            "auth",
            "userId",
            "admin"
        ];
        $this->get('user')
             ->assertViewHas($data_struct);
        //视图数据是否正确
        //self::assertEquals($this->gen_admin_username , $data['username']) ;
        self::assertEquals($this->gen_admin_email , $data['email']) ;
        self::assertEquals(true , $data['auth']) ;
        self::assertEquals(true , $data['admin']) ;
        //头像路径，未上传过，使用默认
        $avatar_path = "wecenter/static" . '/common/avatar-' . "max" . '-img.png';
        self::assertEquals($avatar_path , $data['avatarPath']) ;
        //头像路径，已上传过，查询
        $this->visit('logout') ;
        $this->visit('/login')
            ->see('登录')
            ->type($this->super_admin_email , 'email')
            ->type($this->super_admin_password , 'password')
            ->press('login-submit');
        $user = Auth::user() ;
        //$user = User::where('name','=',$this->super_admin_username)->get() ;
        $this->get('/user')
             ->assertViewHas('avatarPath' , 'avatar/'.$user->avatar_path) ;
        //self::assertEquals('' , Config::get('phylab.avatarPath').$user->avatar_path) ;
        //self::assertEquals($user->introduction , $data['introduction']) ;

        //self::assertEquals('' , var_dump($data)) ;
             //->assertViewHas('username') ;
             //->assertViewHasAll([
             //    'username' => 'zgj982393649' ,
             //]) ;
    }

    public function testUpdate(){
        $this->visit('logout') ;
        $this->visit('/login')
            ->see('登录')
            ->type($this->gen_admin_email , 'email')
            ->type($this->gen_admin_password , 'password')
            ->press('登录');
        //$this->visit('/index')
             //->see($this->gen_admin_username) ;

        //暂存修改前信息
        //$this->get('/user') ;
        $this->post('/user') ;
        $user = Auth::user() ;
        $temp_intro = $user->introduction ;
        $temp_grade = $user->grade ;
        $temp_sex = $user->sex ;
        //
        $test_name = 'tttttttttttt' ;

        $this->post('/user' , [
            'introduction' => 'test' ,
            'grade' => '2016' ,
            'sex' => '1' ,
            'username' => $test_name ,
        ])->seeJson([
            'status' => SUCCESS_MESSAGE ,
        ]) ;
        /*
        $this->post('/user' , [
            'introduction' => $temp_intro ,
            'grade' => $temp_grade ,
            'sex' => $temp_sex ,
            'username' => $this->gen_admin_username ,
        ])->seeJson([
            'status' => SUCCESS_MESSAGE ,
        ]) ;
        */
        $user = Auth::user() ;
        self::assertEquals($test_name , $user->name) ;
        self::assertEquals('2016' , $user->grade) ;
        self::assertEquals('1' , $user->sex) ;
        self::assertEquals('test' ,$user->introduction) ;

        //还原，以确保下次测试的有效性
        //var_dump($this->gen_admin_username) ;
        $this->post('/user' , [
            'introduction' => $temp_intro ,
            'grade' => $temp_grade ,
            'sex' => $temp_sex ,
            'username' => $this->gen_admin_username ,
        ])->seeJson([
            'status' => SUCCESS_MESSAGE ,
        ]) ;
        //提交错误信息，异常
        try{
            $this->post('/user' , [
                'company' => ['test' => null] ,
            ]) ;
        }catch (Exception $e){
        }

    }

    /**
     * 测试上传头像
     *
     */
    /*
    public function testSetAvatar(){
        //
        $this->visit('logout') ;
        $this->visit('/login')
            ->see('登录')
            ->type($this->gen_admin_email , 'email')
            ->type($this->gen_admin_password , 'password')
            ->press('登录');
        //无头像文件
        $this->post('/user/avatar') ;
        //有文件，格式不对
        $pdf_info = [
            'name' => public_path().'/prepare_pdf/phylab_test.pdf' ,
            'error' => 0 ,
            'type' => 'pdf' ,
            'size' => 100000 ,
            'tmp_name' => public_path().'/prepare_pdf/phylab_test.pdf' ,
        ] ;
        //var_dump($pdf_info['name']) ;
        $pdf = new UploadedFile($pdf_info['tmp_name'], $pdf_info['name'], $pdf_info['type'], $pdf_info['size'], $pdf_info['error'] , true);
        //self::assertTrue($pdf instanceof UploadedFile) ;
        $file_arr = [
            'prepare-pdf' => $pdf ,
        ] ;
        $response = $this->call('POST' , '/user.avatar' , [] , [] , $file_arr);
        //有文件
    }
    */
}
