<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;


class ExampleTest extends TestCase
{
    use WithoutMiddleware ;
    //use DatabaseMigrations;
    /**
     * A basic functional test example.
     *
     * @return void
     *
     */
    public function testBasicExample()
    {
        $username = 'zgj982393649' ;

        //$this->get('index')
        //     ->dontSee('控制台') ;
        $this->get('/console')
             ->assertRedirectedTo('/index');


        /*
        $this->visit('/console')
             //->assertRedirected('/index')
             ->seePageIs('/index')
             ->dontSee('实验选择')
             ->see('登录')
             ->see('注册') ;
*/
        $this->visit('/login')
            ->see('登录')
            ->type('982393649@qq.com' , 'email')
            ->type('199808151' , 'password')
            ->press('login-submit');
            //->see('登录');
            //->seePageIs('/index') ;

        $this->visit('/index')
             ->see('zgj982393649')
             ->see('登出')
             ->see('控制台');
        //$this->be($username) ;
        $this->visit('user')
             ->type('tttt' , 'introduction')
             ->press('update') ;

        $_POST['introduction'] = 'sdf' ;
        $this->post('/user' , [
           'introduction' => 'shi' ,
        ]);

        $this->visit('/console')
             //->assertRedirectedTo('index')
             ->see('物理实验')
             ->see('点击选择已存在实验')
             ->see('zgj982393649')
             ->see('新增实验')
             ->see('Python脚本')
             ->see('保存实验');

        //测试新增和删除
        /*
        $this->visit('/console')
            ->see('点击选择已存在实验')
            ->click('新增实验');
        */

        /**
         * 测试创建实验
         */
        $_GET['LId'] = '2101010';
        $_GET['LName'] = 'phpunit' ;
        $_GET['LTag'] = '1010' ;
        $this->visit('/createLab')
             ->seeJson([
                 'status' => SUCCESS_MESSAGE,
             ]);

        $_GET['LId'] = '2160115';
        $_GET['LName'] = '密立根油滴实验' ;
        $_GET['LTag'] = '2161' ;
        $this->visit('/createLab')
            ->seeJson([
                'status' => FAIL_MESSAGE,
                'msg' => '该报告号码已经存在',
            ]);


        //->press('lab-name');
        /*
                     ->type('2101010' , 'l_id')
                     ->type('phpunit' , 'l_name')
                     ->type('1010' , 'l_tag')
                     ->press('提交') ;
        */
        //$this->visit('/');
            //->see('控制台');

        /**
         * 测试保存实验
         *
         */
        //无权限
        $this->visit('logout') ;
        $this->post('/report/updatereport')
             ->seeJson([
                 'status' => FAIL_MESSAGE ,
                 'message' => "没有权限" ,
             ]);
        //普通管理员
        $this->visit('/login')
            ->see('登录')
            ->type('123456@test.com' , 'email')
            ->type('123456' , 'password')
            ->press('login-submit');
        $this->post('/report/updatereport' , [
            'reportId' => '1010113' ,
        ])->seeJson([
            'status' => FAIL_MESSAGE ,
            'message' => "没有更新权限，请联系超级管理员" ,
            //'message' => "没有权限" ,
        ]) ;
        //超级管理员，错误的实验id
        $this->visit('/login')
            ->see('登录')
            ->type('982393649@qq.com' , 'email')
            ->type('199808151' , 'password')
            ->press('login-submit');
        $this->post('/report/updatereport' , [
            'reportId' => '3333333' ,
        ])->seeJson([
            'status' => FAIL_MESSAGE ,
            'message' => "更新失败(wrong_id)" ,
        ]);
        //超级管理员,正确实验id
        $str_py = "test" ;
        $str_tex = "test" ;
        $str_html = "test" ;
        $report_id = "1010113" ;
        $this->post('/report/updatereport' , [
            'reportId' => $report_id ,
            'reportScript' => $str_py ,
            'reportTex' => $str_tex ,
            'reportHtml' => $str_html ,
        ])->seeJson([
            'status' => SUCCESS_MESSAGE ,
            'message' => "更新成功" ,
        ]);
        //测试更新是否正确
        $tex_file = Config::get('phylab.scriptPath')."tex/Handle".$report_id.".tex";
        $tex_read = file_get_contents($tex_file);
        $this->assertEquals($str_tex , $tex_read) ;
        $py_file = Config::get('phylab.scriptPath')."p".$report_id.".py";
        $py_read = file_get_contents($py_file);
        $this->assertEquals($str_py , $py_read) ;
        $html_file = Config::get('phylab.experimentViewPath').$report_id.".html";
        $html_read = file_get_contents($html_file);
        $this->assertEquals($str_html , $html_read) ;


        /**
         * 测试删除实验
         *
         */
        //未登录
        $this->visit('logout') ;
        $this->post('report/delete')
             ->seeJson([
                 'status' => FAIL_MESSAGE,
                 'message' => "没有权限" ,
             ]);
        //登录，实验id错误
        $this->visit('/login')
            ->see('登录')
            ->type('982393649@qq.com' , 'email')
            ->type('199808151' , 'password')
            ->press('login-submit');
        $this->post('report/delete')
             ->seeJson([
                 'status' => FAIL_MESSAGE ,
                 'message' => '实验Id不存在' ,
             ]) ;
        //已发布实验
        $this->post('/report/delete' , [
            'id' => '1010113' ,
        ])->seeJson([
            'status' => FAIL_MESSAGE ,
            'message' => "实验已发布，请联系超级管理员" ,
        ])  ;
        //正确的未发布实验
        $this->post('/report/delete' , [
            'id' => '2101010' ,
        ])->seeJson([
            'status' => SUCCESS_MESSAGE,
            'message' => "删除成功！" ,
        ]);

        /***
         * 测试文件上传
         *
         **/
        //未登录，重定向
        $this->visit('/logout');
        $this->post('/console/uploadPre')
             ->assertRedirectedTo('index') ;

        //登录无文件
        $this->visit('/login')
            ->see('登录')
            ->type('982393649@qq.com' , 'email')
            ->type('199808151' , 'password')
            ->press('login-submit');
        $this->post('/console/uploadPre')
             ->seeJson([
                'status' =>  FAIL_MESSAGE ,
                 'message' => "上传失败，没有找到文件！",
             ]);

        //登录有文件
        $_POST['labID'] = '2222222';
        //Storage::fake('prepare_pdf');  // 伪造目录
        //$pdf = Storage::disk('public')->get('1011.pdf') ;
        $pdf = new SplFileInfo('prepare_pdf/phylab_test.pdf') ;
        self::assertTrue($pdf instanceof SplFileInfo && $pdf->getPath() != '') ;
        $_FILES["prepare-pdf"]["name"] = 'prepare_pdf/phylab_test.pdf' ;
        $_FILES["prepare-pdf"]['error'] = 0 ;
        $_FILES["prepare-pdf"]['type'] = 'pdf' ;
        $_FILES["prepare-pdf"]['tmp_name'] = 'prepare_pdf/phylab_test.pdf' ;
        $_FILES["prepare-pdf"]['size'] = 1000 ;
        //$pdf = UploadedFile::fake()->image('2134.pdf');  // 伪造上传图片
        $this->post('/console/uploadPre' , [
            'prepare-pdf' => $pdf ,
        ])->seeJson([
            //'status' => FAIL_MESSAGE ,
            //'message' => "上传失败，文件格式或大小不符合要求！",
            //'message' => 'prepare_pdf/phylab_test.pdf',
        ]) ;

        //Storage::disk('prepare_pdf')->assertExists('/prepare_pdf/2134.pdf');




        //分开测试时可能需要先进行登录
        $_GET['id'] = '2160115' ;
        $this->visit('/getTable')
            ->seeJson([
                'status' => SUCCESS_MESSAGE
            ]);

        $this->visit('/getScript')
            ->seeJson([
                'status' => SUCCESS_MESSAGE
            ]);

        $this->visit('/getTex')
             ->seeJson([
                 'status' => SUCCESS_MESSAGE
             ]);

        $_GET['id'] = '2110111' ;
        $this->visit('/getTable')
            ->seeJson([
                'status' => FAIL_MESSAGE
            ]);

        $this->visit('/getScript')
            ->seeJson([
                'status' => FAIL_MESSAGE
            ]);

        $this->visit('/getTex')
            ->seeJson([
                'status' => FAIL_MESSAGE
            ]);

        //当分开测试时这里需要进行更改
        $this->visit('/logout');
        $this->get('/getScript')
             ->assertRedirectedTo('/index');

        $this->get('/getTex')
             ->assertRedirectedTo('/index');

        $this->get('/createLab')
             ->assertRedirectedTo('index') ;
    }
}
