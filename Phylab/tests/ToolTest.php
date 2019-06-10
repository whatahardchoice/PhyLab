<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ToolTest extends TestCase
{

    use WithoutMiddleware ;
    protected $preserveGlobalState = FALSE;
    protected $runTestInSeparateProcess = TRUE;

    protected $gen_admin_username = 'console_test' ;
    protected $gen_admin_email = '123456@test.com' ;
    protected $gen_admin_password = '123456' ;

    public function testIndex()
    {
        $this->visit('logout');
        $this->visit('/login')
            ->see('登录')
            ->type($this->gen_admin_email , 'email')
            ->type($this->gen_admin_password , 'password')
            ->press('login-submit');

        $this->visit('/tools')
            ->see("逐差法计算器")
            ->see("线性回归分析");
    }
}
