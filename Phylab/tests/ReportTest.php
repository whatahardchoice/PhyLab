<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View ;
use App\Http\Controllers\ReportController ;

class ReportTest extends TestCase
{

    protected $preserveGlobalState = FALSE;
    protected $runTestInSeparateProcess = TRUE;

    //测试账号
    protected $gen_admin_username = 'console_test' ;
    protected $gen_admin_email = '123456@test.com' ;
    protected $gen_admin_password = '123456' ;
    //用户账号
    protected $user_username = 'user_test' ;
    protected $user_email = '123456@test1.com' ;
    protected $user_password = '123456' ;

    //最后一个已发布实验
    protected $last_confirm_exp_name = '声光衍射实验' ;
    protected $last_confirm_exp_status = '1' ;
    protected $last_confirm_exp_id = '2200116' ;
    protected $last_confirm_exp_relatedArticle = '5' ;

    //最后一个实验
    protected $last_exp_name = 'test_star';
    protected $last_exp_id = '9999999' ;
    protected $last_exp_status = '0' ;
    protected $last_exp_relatedArticle = '5' ;


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
     *
     */
    public function testRepoerConfirm(){
        $con = new ReportController() ;
        $this->visit('/logout') ;
        $this->visit('/login')
            ->see('登录')
            ->type($this->gen_admin_email , 'email')
            ->type($this->gen_admin_password , 'password')
            ->press('login-submit');
        self::assertTrue($con->userConfirm()) ;

        $this->visit('/logout') ;
        $this->visit('/login')
            ->see('登录')
            ->type($this->user_email , 'email')
            ->type($this->user_password , 'password')
            ->press('login-submit');
        self::assertTrue(!$con->userConfirm()) ;
    }

    /**
     * 测试报告主页面(index)
     *
     */
    public function testReportIndex(){
        $this->visit('/logout') ;
        $this->visit('/login')
            ->see('登录')
            ->type($this->gen_admin_email , 'email')
            ->type($this->gen_admin_password , 'password')
            ->press('login-submit');
        $this->visit('/report')
             ->see('物理实验')
             ->see('请选择实验')
             ->see('物理实验选择策略')
             ->see('查看收藏夹') ;
        /*
        $this->get('/report')
             ->seeJson([
                 'username' => $this->gen_admin_username ,
                 'auth' => true ,
                 'admin' => true ,
             ]);
        */
        $response = $this->call('GET' , '/report') ;
        $this->assertResponseOk($response) ;
        $view = $response->original ;
        self::assertTrue($view instanceof View) ;
        $data = $view->getData() ;

        self::assertEquals($this->gen_admin_username , $data['username']) ;

        self::assertTrue($data['auth']) ;
        self::assertTrue($data['admin']) ;
        $report_arr = $data['reports'] ;

        //var_dump(end($report_arr)) ;

                $expect = [
                    [
                    'id' =>  $this->last_exp_id,
                    'experimentName' => $this->last_exp_name ,
                    'relatedArticle' => $this->last_exp_relatedArticle ,
                    ]
                ];
                self::assertEquals($expect , end($report_arr)) ;


        $this->visit('/logout') ;
        $this->visit('/login')
            ->see('登录')
            ->type($this->user_email , 'email')
            ->type($this->user_password , 'password')
            ->press('login-submit');
        $response = $this->call('GET' , '/report') ;
        $this->assertResponseOk($response) ;

        $view = $response->original ;
        self::assertTrue($view instanceof View) ;
        $data = $view->getData() ;
        self::assertEquals($this->user_username , $data['username']) ;

        self::assertTrue($data['auth']) ;
        self::assertTrue(!$data['admin']) ;
        $report_arr = $data['reports'] ;

        //var_dump(end($report_arr)) ;

        $expect = [
            [
                'id' =>  $this->last_confirm_exp_id,
                'experimentName' => $this->last_confirm_exp_name ,
                'relatedArticle' => $this->last_confirm_exp_relatedArticle ,
            ]
        ];
        self::assertEquals($expect , end($report_arr)) ;

    }

    /**
     *
     *
     */
    public function testGetAllReport(){
        $this->visit('/logout') ;
        $this->visit('/login')
            ->see('登录')
            ->type($this->gen_admin_email , 'email')
            ->type($this->gen_admin_password , 'password')
            ->press('login-submit');
        $response = $this->call('GET' , '/getreport') ;
        $this->assertResponseOk($response) ;
        $data = $response->getData() ;
        $report_arr = $data->reports ;
        $report = end($report_arr)[0] ;
        self::assertEquals($this->last_exp_name , $report->experimentName) ;
        self::assertEquals($this->last_exp_id , $report->id) ;
        self::assertEquals($this->last_exp_status , $report->status) ;
        self::assertEquals($this->last_exp_relatedArticle , $report->relatedArticle) ;

        $this->visit('/logout') ;
        $this->visit('/login')
            ->see('登录')
            ->type($this->user_email , 'email')
            ->type($this->user_password , 'password')
            ->press('login-submit');
        $response = $this->call('GET' , '/getreport') ;
        $this->assertResponseOk($response) ;
        $data = $response->getData() ;
        $report_arr = $data->reports ;
        $report = end($report_arr)[0] ;
        self::assertEquals($this->last_confirm_exp_name , $report->experimentName) ;
        self::assertEquals($this->last_confirm_exp_id , $report->id) ;
        self::assertEquals($this->last_confirm_exp_status , $report->status) ;
        self::assertEquals($this->last_confirm_exp_relatedArticle , $report->relatedArticle) ;

    }

    /**
     *
     * 测试latex生成pdf
     */
    public function testCreateTex(){
        //先确保已存在用于测试的xml文件
        self::assertTrue(Storage::disk('local')->exists('script/test/9999999test/9999999.xml')) ;
        //登录
        $this->visit('/login')
             ->see('登录')
             ->type($this->gen_admin_email , 'email')
             ->type($this->gen_admin_password , 'password')
             ->press('login-submit');
        //获得测试xml文件内容
        $xml_file = storage_path('app').'/script/test/9999999test/9999999.xml' ;
        $xml_str = file_get_contents($xml_file) ;
        //没有xml_tmp文件夹，抛异常
        /*
        try{
            exec('mv '.storage_path('app').'/xml_tmp'.' '.storage_path('app').'/xml_tmp_test',$out , $ret) ;
        }catch (Exception $e){
            self::assertTrue(false) ;
        }
        */
        try{
            $this->post('/report/createTex' , [
                'id' => '9999999' ,
                'xml' => ['test' => 'test' , 'test1' => 'test'] ,
            ]);
            //未抛异常则测试失败
            //self::assertTrue(false) ;
        }catch(Exception $e){
        }
        //还原
        /*
        try{
            exec('mv '.storage_path('app').'/xml_tmp_test'.' '.storage_path('app').'/xml_tmp',$out , $ret) ;
        }catch (Exception $e){
            self::assertTrue(false) ;
        }
        */
        //正常生成pdf

        $response = $this->call('POST' , '/report/createTex' , [
            'xml' => $xml_str ,
            'id' => '9999999' ,
        ]);
        $data = $response->getData() ;
        self::assertEquals(SUCCESS_MESSAGE , $data->status) ;
        self::assertEquals('9999999' , $data->experimentId) ;
        self::assertTrue(Storage::disk('local_public')->exists('/pdf_tmp/'.$data->link)) ;
        //生成脚本失败
        //暂时重命名脚本文件名称
        try{
            exec('mv '.storage_path('app').'/script/p9999999.py'.' '.storage_path('app').'/script/p9999999test.py',$out , $ret) ;
        }catch (Exception $e){
            self::assertTrue(false) ;
        }
        $response = $this->call('POST' , '/report/createTex' , [
            'xml' => $xml_str ,
            'id' => '9999999' ,
        ]);
        $data = $response->getData() ;
        self::assertEquals(FAIL_MESSAGE , $data->status) ;
        self::assertEquals('7402' , $data->errorcode) ;
        self::assertTrue(strpos($data->message , '生成脚本生成失败: ')!==false ) ;
        //还原py文件
        try{
            exec('mv '.storage_path('app').'/script/p9999999test.py'.' '.storage_path('app').'/script/p9999999.py',$out , $ret) ;
        }catch (Exception $e){
            self::assertTrue(false) ;
        }

    }

    /**
     *
     * 测试MD
     */
    public function testCreateMD(){
        //先确保已存在用于测试的xml文件
        self::assertTrue(Storage::disk('local')->exists('script/test/9999999test/9999999.xml')) ;
        //登录
        $this->visit('/login')
            ->see('登录')
            ->type($this->gen_admin_email , 'email')
            ->type($this->gen_admin_password , 'password')
            ->press('login-submit');
        //获得测试xml文件内容
        $xml_file = storage_path('app').'/script/test/9999999test/9999999.xml' ;
        $xml_str = file_get_contents($xml_file) ;
        //没有xml_tmp文件夹，抛异常
        /*
        try{
            exec('mv '.storage_path('app').'/xml_tmp'.' '.storage_path('app').'/xml_tmp_test',$out , $ret) ;
        }catch (Exception $e){
            self::assertTrue(false) ;
        }
        */
        try{
            $this->post('/report/createMD' , [
                'id' => '9999999' ,
                'xml' => $xml_str ,
            ]);
            //未抛异常则测试失败
            //self::assertTrue(false) ;
        }catch(Exception $e){
        }
        //还原
        /*
        try{
            exec('mv '.storage_path('app').'/xml_tmp_test'.' '.storage_path('app').'/xml_tmp',$out , $ret) ;
        }catch (Exception $e){
            self::assertTrue(false) ;
        }
        */
        //正常生成pdf

        $response = $this->call('POST' , '/report/createMD' , [
            'xml' => $xml_str ,
            'id' => '9999999' ,
        ]);
        $data = $response->getData() ;
        self::assertEquals(SUCCESS_MESSAGE , $data->status) ;
        self::assertEquals('9999999' , $data->experimentId) ;
        self::assertTrue(Storage::disk('local_public')->exists('/pdf_tmp/'.$data->link)) ;
        //生成脚本失败
        //暂时重命名脚本文件名称
        try{
            exec('mv '.storage_path('app').'/script/p9999999.py'.' '.storage_path('app').'/script/p9999999test.py',$out , $ret) ;
        }catch (Exception $e){
            self::assertTrue(false) ;
        }
        $response = $this->call('POST' , '/report/createMD' , [
            'xml' => $xml_str ,
            'id' => '9999999' ,
        ]);
        $data = $response->getData() ;
        self::assertEquals(FAIL_MESSAGE , $data->status) ;
        self::assertEquals('7404' , $data->errorcode) ;
        self::assertTrue(strpos($data->message , '生成脚本生成失败: ')!==false ) ;
        //还原py文件
        try{
            exec('mv '.storage_path('app').'/script/p9999999test.py'.' '.storage_path('app').'/script/p9999999.py',$out , $ret) ;
        }catch (Exception $e){
            self::assertTrue(false) ;
        }
    }

    /**
     * 测试show
     *
     */
    /*
    public function testShow(){
        //不存在的实验Id
        //$flag = false ;
        //try{
        //$response = $this->call('GET' , '/report/165' ) ;

        $this->visit('/logout') ;
        $this->visit('/login')
            ->see('登录')
            ->type($this->gen_admin_email , 'email')
            ->type($this->gen_admin_password , 'password')
            ->press('login-submit');
        //详细信息断言暂留空
        $this->visit('/report/999') ;
        $this->visit('/report/5') ;

        //$data = $response->getData() ;
        //var_dump($response) ;

        //}catch (Exception $e){
        //    $flag = true ;
        //}
        //self::assertTrue($flag) ;
    }
    */

    public function testGetTable()
    {

        $this->visit('/login')
            ->see('登录')
            ->type($this->gen_admin_email , 'email')
            ->type($this->gen_admin_password , 'password')
            ->press('login-submit');

        //错误实验id，打开实验失败
        $_GET['id'] = '0000000' ;
        $this->get('/table')
             ->seeJson([
                 'status' => FAIL_MESSAGE ,
             ]);
        //正确实验id
        $_GET['id'] = $this->last_confirm_exp_id ;
        //var_dump($this->get('/table')) ;
        $this->get('/table') ;

       // $response = $this->call('GET','/table',['id'=>'2140113']);

//        $response = $this->call('GET' , '/table' , [
//            'id'=>'8080808'
//        ]);
//        $data = $response->getData() ;
//        self::assertEquals(FAIL_MESSAGE , $data->status) ;


    }
}
