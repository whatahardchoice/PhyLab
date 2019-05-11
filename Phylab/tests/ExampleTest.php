<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    use WithoutMiddleware ;
    /**
     * A basic functional test example.
     *
     * @return void
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

        $this->visit('/console')
             //->assertRedirectedTo('index')
             ->see('物理实验')
             ->see('点击选择已存在实验')
             ->see('zgj982393649')
             ->see('新增实验')
             ->see('Python脚本')
             ->see('保存实验');

        //$this->visit('/');
            //->see('控制台');

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

        $_GET['id'] = '2110111' ;
        $this->visit('/getTable')
            ->seeJson([
                'status' => FAIL_MESSAGE
            ]);

        $this->visit('/getScript')
            ->seeJson([
                'status' => FAIL_MESSAGE
            ]);
        //当分开测试时这里需要进行更改
        $this->visit('/logout');
        $this->get('/getScript')
             ->assertRedirectedTo('/index');
    }
}
