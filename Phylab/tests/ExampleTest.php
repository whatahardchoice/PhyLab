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

        //测试文件上传
        /*
        $_POST['labID'] = '2222222';
        //Storage::fake('prepare_pdf');  // 伪造目录
        $pdf = Storage::disk('public')->get('1011.pdf') ;
        //$pdf = UploadedFile::fake()->image('2134.pdf');  // 伪造上传图片
        $this->post('/console/uploadPre', [
            'prepare-pdf' => $pdf
        ]);

        Storage::disk('prepare_pdf')->assertExists('/prepare_pdf/2134.pdf');
        */



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
