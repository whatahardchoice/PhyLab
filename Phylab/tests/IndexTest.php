<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class IndexTest extends TestCase
{

    use WithoutMiddleware ;
    protected $preserveGlobalState = FALSE;
    protected $runTestInSeparateProcess = TRUE;

    protected $gen_admin_username = 'console_test' ;
    protected $gen_admin_email = '123456@test.com' ;
    protected $gen_admin_password = '123456' ;

    protected $gen_normal_username = '123' ;
    protected $gen_normal_email = '123@123.com' ;
    protected $gen_normal_password = '123123' ;

    public function testIndex()
    {
        $this->visit('logout');
        $this->visit('/')
            ->see("公告栏")
            ->dontsee("编辑");

        $this->visit('/login')
            ->see('登录')
            ->type($this->gen_normal_email , 'email')
            ->type($this->gen_normal_password , 'password')
            ->press('login-submit');

        $this->visit('/')
            ->see("公告栏")
            ->dontsee("编辑");

        $this->visit('logout');
        $this->visit('/login')
            ->see('登录')
            ->type($this->gen_admin_email , 'email')
            ->type($this->gen_admin_password , 'password')
            ->press('login-submit');

        $this->visit('/')
            ->see("公告栏")
            ->see("编辑");
    }

    public function testModifyBulletin()
    {
        $this->visit('logout');
        $response = $this->call('POST' , '/modifyBulletin' , [
            'contents'=>'unit testing'
        ]);
        $data = $response->getData() ;
        self::assertEquals(FAIL_MESSAGE , $data->status) ;

        $this->visit('/login')
            ->see('登录')
            ->type($this->gen_normal_email , 'email')
            ->type($this->gen_normal_password , 'password')
            ->press('login-submit');
        $response = $this->call('POST' , '/modifyBulletin' , [
            'contents'=>'unit testing'
        ]);
        $data = $response->getData() ;
        self::assertEquals(FAIL_MESSAGE , $data->status) ;

        $orig_contents = file_get_contents(public_path().'/bulletin.html');
        $this->visit('logout');
        $this->visit('/login')
            ->see('登录')
            ->type($this->gen_admin_email , 'email')
            ->type($this->gen_admin_password , 'password')
            ->press('login-submit');

        $response = $this->call('POST' , '/modifyBulletin' , [
            'contents'=>'unit testing'
        ]);
        $data = $response->getData() ;
        self::assertEquals(SUCCESS_MESSAGE , $data->status) ;
        self::assertEquals('unit testing', file_get_contents(public_path().'/bulletin.html'));
        file_put_contents(public_path().'/bulletin.html',$orig_contents);
    }

}
