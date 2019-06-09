<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View ;

class ReportTest extends TestCase
{

    protected $preserveGlobalState = FALSE;
    protected $runTestInSeparateProcess = TRUE;

    //测试账号
    protected $gen_admin_username = 'console_test' ;
    protected $gen_admin_email = '123456@test.com' ;
    protected $gen_admin_password = '123456' ;
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

    /**
     * 测试报告主页面(index)
     *
     */
    public function testReportIndex(){
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
                    'id' => 9999999 ,
                    'experimentName' => 'test_star' ,
                    'relatedArticle' => 5 ,
                    ]
                ];
                self::assertEquals($expect , end($report_arr)) ;


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
        try{
            exec('mv '.storage_path('app').'/xml_tmp'.' '.storage_path('app').'/xml_tmp_test',$out , $ret) ;
        }catch (Exception $e){
            self::assertTrue(false) ;
        }
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
        try{
            exec('mv '.storage_path('app').'/xml_tmp_test'.' '.storage_path('app').'/xml_tmp',$out , $ret) ;
        }catch (Exception $e){
            self::assertTrue(false) ;
        }
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
        self::assertEquals('7401' , $data->errorcode) ;
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
        try{
            exec('mv '.storage_path('app').'/xml_tmp'.' '.storage_path('app').'/xml_tmp_test',$out , $ret) ;
        }catch (Exception $e){
            self::assertTrue(false) ;
        }
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
        try{
            exec('mv '.storage_path('app').'/xml_tmp_test'.' '.storage_path('app').'/xml_tmp',$out , $ret) ;
        }catch (Exception $e){
            self::assertTrue(false) ;
        }
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
     * 测试show
     *
     */
    public function testShow(){
        //不存在的实验Id
        //$flag = false ;
        //try{
        $response = $this->call('GET' , '/report/165' ) ;
        //$data = $response->getData() ;
        //var_dump($response) ;
        /*
            $this->get('/report/165')
                 ->seeJson([
                     'errorcode' => '0000' ,
                 ]);
        */
        //}catch (Exception $e){
        //    $flag = true ;
        //}
        //self::assertTrue($flag) ;


    }
}
