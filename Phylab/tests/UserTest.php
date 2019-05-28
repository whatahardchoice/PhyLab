<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
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
     * 测试index
     */
    public function  testIndex(){
        //未登录，重定向
        $this->get('/user')
             ->assertRedirectedTo('/index');
        //登录
        $this->visit('/login')
             ->see('登录')
             ->type($this->gen_admin_email , 'email')
             ->type($this->gen_admin_password , 'password')
             ->press('login-submit');
        $this->get('/user');
             //->assertViewHas('username' , 'zgj982393649') ;
             //->assertViewHasAll([
             //    'username' => 'zgj982393649' ,
             //]) ;

    }
}
