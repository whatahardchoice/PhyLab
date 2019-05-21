<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Report;


class ExampleTest extends TestCase
{
    use WithoutMiddleware ;
    protected $preserveGlobalState = FALSE;
    protected $runTestInSeparateProcess = TRUE;
    //use DatabaseMigrations;
    /**
     * A basic functional test example.
     *
     * @return void
     *
     */
    public function testBasicExample()
    {
        self::assertTrue(true);

    }

    public function testExample2(){
        echo("good");
    }

}
