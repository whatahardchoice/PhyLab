<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ConsoleTest extends TestCase
{

    use WithoutMiddleware ;
    protected $preserveGlobalState = FALSE;
    protected $runTestInSeparateProcess = TRUE;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }
}
