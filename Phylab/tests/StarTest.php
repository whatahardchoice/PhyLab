<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB ;
use Illuminate\View\View;
use App\Models\Star;
use Illuminate\Support\Facades\Storage ;

class StarTest extends TestCase
{

    protected $preserveGlobalState = FALSE;
    protected $runTestInSeparateProcess = TRUE;

    //测试账号
    protected $gen_admin_username = 'console_test' ;
    protected $gen_admin_email = '123456@test.com' ;
    protected $gen_admin_password = '123456' ;

    /**
     *预定义测试账号收藏夹内容，可直接查看或修改数据库
     *
     */
    protected $id = '1425';
    protected $name = 'Lab_9999999_test_star_report' ;
    protected $link = 'test_star.pdf' ;
    protected $report_id = '9999999' ;
    protected $user_id = '1333' ;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    /*
     * (1)确保测试账号收藏夹中有一个符合测试要求的收藏记录
     * (2)构建该数组
     */
    public function testInit_index(){
        //确定至少有一项收藏记录
        $count = DB::table('stars')->where('user_id' , '1333')->count() ;
        self::assertTrue( $count>0 ) ;
        //确定该收藏项是否符合要求
        $report = DB::table('stars')->where('report_id' , '9999999')->first() ;
        self::assertNotEmpty($report) ;
        self::assertEquals($this->id , $report->id) ;
        self::assertEquals($this->link , $report->link) ;
        self::assertEquals($this->name , $report->name) ;
        //返回
        $data = [] ;
        array_push($data ,
            array(
                'id' => $this->id ,
                'name' => $this->name ,
                'link' => $this->link ,
                'time' => toTimeZone($report->created_at) ,
            )
        );
        return $data ;
    }

    /**
     * index方法测试
     *
     * @depends testInit_index
     */

    public function testIndex($report){
        //登录
        $this->visit('/login')
            ->see('登录')
            ->type($this->gen_admin_email , 'email')
            ->type($this->gen_admin_password , 'password')
            ->press('登录');
        $this->visit('/user/star')
             ->see('收藏夹到底了哦') ;
        //
        $response = $this->call('GET' , '/user/star') ;
        $this->assertResponseOk($response) ;
        $view = $response->original ;
        self::assertTrue($view instanceof View) ;
        $data = $response->original->getData() ;

        $this->get('/user/star')
             ->assertViewHas('stars') ;
        self::assertEquals($report , $data['stars']) ;
        //self::assertEquals('' , var_dump($report)) ;
        //self::assertEquals('' , var_dump($data['stars'])) ;
    }


    /**
     * 测试delete
     *
     */
    public  function testDelete(){

        //创建一条数据库记录
        DB::table('stars')->insert([
            'id' => 9999 ,
            'name' => 'test_delete' ,
            'link' =>'abcd.pdf'
        ]) ;
        //登录
        $this->visit('/login')
            ->see('登录')
            ->type($this->gen_admin_email , 'email')
            ->type($this->gen_admin_password , 'password')
            ->press('登录');
        //没有对应id
        $this->delete('/user/star' , [
            'id' => '0000' ,
        ])->seeJson([
            'status' => FAIL_MESSAGE ,
            'errorcode' => '7509' ,
        ]);
        //有对应id；link不存在
        $this->delete('/user/star' , [
            'id' => '9999' ,
        ])->seeJson([
            'status' => SUCCESS_MESSAGE ,
            'errorcode' => '7508' ,
        ]) ;

    }

    /**
     * 测试download
     *
     */
    public function testDownload(){
        //创建一项数据库记录，***与登录账号不能相同***
        DB::table('stars')->insert([
            'id' => 9999 ,
            'name' => 'test_delete' ,
            'link' => 'test_pdf.pdf' ,
            'user_id' => 9999 ,
            'report_id' => 9999 ,
        ]);
        //登录
        $this->visit('/login')
            ->see('登录')
            ->type($this->gen_admin_email , 'email')
            ->type($this->gen_admin_password , 'password')
            ->press('登录');
        //无法找到id
        try{
            $this->get('/user/star/download/0000' ) ;
            self::assertTrue(false) ;//如未抛出异常，则测试失败
        }catch (Exception $e){
        }
        //表中user_id与登录账号id不同
        try{
            $this->get('/user/star/download/9999' ) ;
            self::assertTrue(false) ;//如未抛出异常，则测试失败
        }catch (Exception $e){
        }
        //正常下载
        $this->get('user/star/download/1425') ;
        //删除新创建的数据库记录
        DB::table('stars')->where('id' , 9999)->delete() ;
    }

    /**
     * 测试show
     *
     */
    public function testShow(){
        //创建一项数据库记录，***与登录账号不能相同***
        DB::table('stars')->insert([
            'id' => 9999 ,
            'name' => 'test_delete' ,
            'link' => 'test_pdf.pdf' ,
            'user_id' => 9999 ,
            'report_id' => 9999 ,
        ]);
        //登录
        $this->visit('/login')
            ->see('登录')
            ->type($this->gen_admin_email , 'email')
            ->type($this->gen_admin_password , 'password')
            ->press('登录');
        //id不存在
        try{
            $this->get('/user/star/0000' ) ;
            self::assertTrue(false) ;//如未抛出异常，则测试失败
        }catch (Exception $e){
        }
        //表中user_id与登录账号id不同
        try{
            $this->get('/user/star/9999' ) ;
            self::assertTrue(false) ;//如未抛出异常，则测试失败
        }catch (Exception $e){
        }
        //正确id
        $response = $this->call('GET' , '/user/star/1425') ;
        $this->assertResponseOk($response) ;
        $view = $response->original ;
        self::assertTrue($view instanceof View) ;
        $data = $view->getData() ;
        $data_struct = ["username" ,
            "link" ,
            "createTime",
            "name" ,
            "experimentId",
            "errorcode",
            "experimentName"
        ];
        $this->get('/user/star/1425')
             ->assertViewHas($data_struct) ;
        self::assertEquals('test_star.pdf' , $data['link']) ;
        self::assertEquals('0000' , $data['errorcode']) ;
        self::assertEquals('9999999' , $data['experimentId']) ;

        //删除新创建的数据库记录
        DB::table('stars')->where('id' , 9999)->delete() ;
    }

    /**
     * 测试create
     *
     */
    public function testCreate(){
        //登录
        $this->visit('/login')
            ->see('登录')
            ->type($this->gen_admin_email , 'email')
            ->type($this->gen_admin_password , 'password')
            ->press('登录');
        //复制两个pdf_tmp文件，其中一个与star_pdf文件中存在重名
        if(!Storage::disk('local_public')->exists('pdf_tmp/'.'test_star.pdf'))
            Storage::disk('local_public')->copy('star_pdf/'.'test_star.pdf','pdf_tmp/'.'test_star.pdf');
        if(!Storage::disk('local_public')->exists('pdf_tmp/'.'test_star_notsame.pdf'))
            Storage::disk('local_public')->copy('star_pdf/'.'test_star.pdf','pdf_tmp/'.'test_star_notsame.pdf');
        else
            exec("rm -rf ".public_path().'/star_pdf/'.'test_star_notsame.pdf',$output,$reval1);
        //var_dump(public_path().'/star_pdf/'.'test_star_notsame.pdf') ;
        //report_id格式错误
        $this->post('/user/star' , [
            'link' => 'test_star.pdf' ,
            'reportId' => '0000' ,
        ])->seeJson([
            'status' => FAIL_MESSAGE,
            'errorcode' => '7501' ,
            'message' => '检查失败' ,
        ]) ;
        //reportId正常，link文件不存在
        $this->post('/user/star' , [
            'link' => 'noExist.pdf' ,
            'reportId' => '9999999' ,
        ])->seeJson([
            'status' => FAIL_MESSAGE ,
            'errorcode' => '7507' ,
            'message' => "不存在pdf文件" ,
        ]);
        //存在pdf但无该实验
        $this->post('/user/star' , [
            'link' => 'test_star.pdf' ,
            'reportId' => '7777777' ,
        ])->seeJson([
            'status' => FAIL_MESSAGE ,
            'errorcode' => '7502' ,
            'message' => '没有此类型报告' ,
        ]);
        //有pdf和对应实验，查询时出错
        //创建一个新的实验和收藏夹项目，实验名字为空

        DB::table('reports')->insert([
            'id' => 9999 ,
            'experiment_id' => 8888888 ,
        ]);
        /*
        DB::table('reports')->insert([
            'id' => 9998 ,
            'experiment_id' => 8888888 ,
        ]);
        $this->post('/user/star' , [
            'link' => 'test_star.pdf' ,
            'reportId' => '8888888' ,
        ])->seeJson([
            'status' => FAIL_MESSAGE ,
            'errorcode' => '7503' ,
            'message' => '报告查询失败' ,
            'reportnumber' => '2' ,
        ]);*/
        //名字为空，收藏创建失败
        $this->post('/user/star' , [
            'link' => 'test_star.pdf' ,
            'reportId' => '8888888' ,
        ])->seeJson([
            'status' => FAIL_MESSAGE ,
            'errorcode' => '7506' ,
            'message' => '收藏创建失败' ,
        ]);
        //重名，复制失败
        try{
            $this->post('/user/star' , [
                'link' => 'test_star.pdf' ,
                'reportId' => '9999999' ,
            ]);
            //若未抛异常则测试失败
            self::assertTrue(false) ;
        }catch (Exception $e){
        }
        /*
        $response = $this->call('POST' , '/user/star' , [
            'link' => 'test_star_same.pdf' ,
            'reportId' => '9999999' ,
        ]) ;
        $data = $response->getContent() ;

        //$data = $response->getData() ;
        $id = $data['id'] ;
        */

        $this->post('/user/star' , [
            'link' => 'test_star_notsame.pdf' ,
            'reportId' => '9999999' ,
        ])->seeJson([
            'status' => SUCCESS_MESSAGE ,
            'message' => "收藏报告成功" ,
        ]);

        self::assertTrue(Storage::disk('local_public')->exists('star_pdf/'.'test_star_notsame.pdf')) ;

        DB::table('stars')->where('link' , 'test_star_notsame.pdf')->delete() ;
        //删除新创建实验
        DB::table('reports')->where('id' , '9999')->delete() ;
        //超过最大收藏数量
        for($i = 0 ; $i<10 ; $i++){
            DB::table('stars')->insert([
                'id' => 9990+$i ,
                'name' => 'test_create' ,
                'user_id' => 1333 ,
            ]);
        }
        $this->post('/user/star' , [
            'link' => 'test_star.pdf' ,
            'reportId' => '9999999' ,
        ])->seeJson([
            'status' => FAIL_MESSAGE,
            'errorcode' => '7505' ,
            'message' => "超过收藏最大值" ,
        ]);
        for($i = 0 ; $i<10 ; $i++){
            DB::table('stars')->where('name','test_create')->delete() ;
        }
    }

}
