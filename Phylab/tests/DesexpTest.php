<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class DesexpTest extends TestCase
{

    use WithoutMiddleware ;
    protected $preserveGlobalState = FALSE;
    protected $runTestInSeparateProcess = TRUE;

    protected $gen_admin_username = 'console_test' ;
    protected $gen_admin_email = '123456@test.com' ;
    protected $gen_admin_password = '123456' ;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndex()
    {
        $this->visit('logout') ;
        $this->visit('/desexp')
            ->see("设计性实验")
            ->see("请选择实验")
            ->see("D01");

        $this->visit('/login')
            ->see('登录')
            ->type($this->gen_admin_email , 'email')
            ->type($this->gen_admin_password , 'password')
            ->press('login-submit');

        $this->visit('/desexp')
            ->see("设计性实验")
            ->see("请选择实验")
            ->see("D01");
    }

    public function testGetDesexp()
    {
        $this->visit("/desexp/D01")
            ->seeJson([
                'status' => SUCCESS_MESSAGE ,
                "id"=> "D01",
                "link" => "/desexp_html/D01.html",
                "name" => "D01 单量程三用表的设计和校准"
            ]) ;

        $this->visit("/desexp/D05")
            ->seeJson([
                'status' => FAIL_MESSAGE
            ]) ;
    }
}
