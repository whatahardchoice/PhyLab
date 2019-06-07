<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Report;
//use App\Http\Requests;
use App\Http\Controllers\ConsoleController;
use Illuminate\Http\Request ;

class ConsoleTest extends TestCase
{

    use WithoutMiddleware ;
    protected $preserveGlobalState = FALSE;
    protected $runTestInSeparateProcess = TRUE;

    /**
     *
     * 测试变量定义
     */
    //普通管理员
    protected $gen_admin_username = 'console_test' ;
    protected $gen_admin_email = '123456@test.com' ;
    protected $gen_admin_password = '123456' ;

    //超级管理员
    protected $super_admin_username = 'zgj982393649' ;
    protected $super_admin_email = '982393649@qq.com' ;
    protected $super_admin_password = '199808151' ;

    //测试中新建实验id
    protected $report_id_create = '2101010' ;
    protected $report_name_create = 'phpunit' ;
    protected $report_group_create = '1010' ;

    //未发布实验id
    protected $report_id_unpub = '2101010' ;

    //错误实验id
    protected $report_id_err = '3333333' ;

    //正确已发布实验Id
    protected $report_id_pub = '1010113' ;
    protected $report_name_pub = '拉伸法测钢丝弹性模型扭摆法测定转动惯量' ;
    protected $report_group_pub = '1011' ;

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
     * 管理员账号登录预测试
     */
    public function testLogin(){
        //超级管理员账号
        $this->visit('/login')
            ->see('登录')
            ->type($this->super_admin_email , 'email')
            ->type($this->super_admin_password , 'password')
            ->press('login-submit');
        $this->visit('/index')
            ->see($this->super_admin_username)
            ->see('登出')
            ->see('控制台');

        //普通管理员账号
        $this->visit('/login')
            ->see('登录')
            ->type($this->gen_admin_email , 'email')
            ->type($this->gen_admin_password , 'password')
            ->press('login-submit');
        $this->visit('/index')
            ->see($this->gen_admin_username)
            ->see('登出')
            ->see('控制台');
    }

    /**
     *
     * 控制台主页面测试
     */
    public function testIndex(){
        //未登录
        $this->visit('logout') ;
        $this->get('/console')
            ->assertRedirectedTo('/index');

        //已登录管理员
        $this->visit('/login')
            ->see('登录')
            ->type($this->gen_admin_email , 'email')
            ->type($this->gen_admin_password , 'password')
            ->press('login-submit');

        //控制台界面主要元素测试
        $this->visit('/console')
            //->assertRedirectedTo('index')
            ->see('物理实验')
            ->see('点击选择已存在实验')
            ->see($this->gen_admin_username)
            ->see('新增实验')
            ->see('上传实验预习报告')
            ->see('删除未发布实验')
            ->see('LaTeX模板')
            ->see('实验表格HTML')
            ->see('Python脚本')
            ->see('保存实验')
            //->see('运行测试')
            ->see('发布实验');

        //函数返回数据是否正确
        $reports = Report::orderBy('experiment_id')->get();
        $exp_arr = [] ;
        foreach ($reports as $report) {
            $exp = array(
                "id"=>$report->id,
                "experimentId"=>$report->experiment_id,
                "experimentName"=>$report->experiment_name,
                "prepareLink"=>$report->prepare_link
            );
            array_push($exp_arr,$exp);
        }
        $this->get('console')
            ->assertViewHas([
                'admin' => true ,
                'username' => $this->gen_admin_username ,
                'auth' => true ,
                'status' => SUCCESS_MESSAGE ,
                'reportTemplates' => $exp_arr ,
            ]) ;
    }

    /**
     *
     * 测试getTable
     */
    public function testGetTable(){
        //未登录
        $this->visit('logout') ;
        $this->get('/getTable')
            ->assertRedirectedTo('/index');
        //登录，错误实验id（进入catch分支）
        $this->visit('/login')
            ->see('登录')
            ->type($this->gen_admin_email , 'email')
            ->type($this->gen_admin_password , 'password')
            ->press('login-submit');
        $_GET['id'] = $this->report_id_err ;
        $this->visit('/getTable')
            ->seeJson([
                'status' => FAIL_MESSAGE
            ]);

        //正确实验id
        $_GET['id'] = $this->report_id_pub ;
        $html_file = Config::get('phylab.experimentViewPath').$this->report_id_pub.".html";
        $str_html = file_get_contents($html_file);
        $this->visit('/getTable')
            ->seeJson([
                'status' => SUCCESS_MESSAGE ,
                'contents' => $str_html ,
            ]) ;
    }

    /**
     *
     * 测试getScript
     */
    public function testGetScript(){

        //未登录
        $this->visit('/logout') ;
        $this->get('/getScript')
            ->assertRedirectedTo('/index') ;

        //登录，错误实验id
        $this->visit('/login')
            ->see('登录')
            ->type($this->gen_admin_email , 'email')
            ->type($this->gen_admin_password , 'password')
            ->press('login-submit');
        $_GET['id'] = $this->report_id_err ;
        $this->visit('/getScript')
            ->seeJson([
                'status' => FAIL_MESSAGE
            ]);

        //正确实验id
        $_GET['id'] = $this->report_id_pub ;
        $py_file = Config::get('phylab.scriptPath')."p".$this->report_id_pub.".py";
        $str_py = file_get_contents($py_file);
        $this->visit('/getScript')
            ->seeJson([
                'status' => SUCCESS_MESSAGE ,
                'contents' => $str_py ,
            ]) ;
    }

    /**
     *
     * 测试getTex
     */
    public function testGetTex(){

        //未登录
        $this->visit('/logout') ;
        $this->get('/getTex')
            ->assertRedirectedTo('/index') ;

        //登录，错误实验id
        $this->visit('/login')
            ->see('登录')
            ->type($this->gen_admin_email , 'email')
            ->type($this->gen_admin_password , 'password')
            ->press('login-submit');
        $_GET['id'] = $this->report_id_err ;
        $this->visit('/getTex')
            ->seeJson([
                'status' => FAIL_MESSAGE
            ]);

        //正确实验id
        $_GET['id'] = $this->report_id_pub ;
        $tex_file = Config::get('phylab.scriptPath')."tex/Handle".$this->report_id_pub.".tex";
        $str_tex = file_get_contents($tex_file);
        $this->visit('/getTex')
            ->seeJson([
                'status' => SUCCESS_MESSAGE ,
                'contents' => $str_tex ,
            ]) ;
    }

    /**
     * 测试创建实验
     */
    public function testCreate(){
        //未登录
        $this->visit('logout') ;
        $this->get('/createLab')
            ->assertRedirectedTo('/index') ;

        //登录，新建实验号已存在
        $this->visit('/login')
            ->see('登录')
            ->type($this->gen_admin_email , 'email')
            ->type($this->gen_admin_password , 'password')
            ->press('login-submit');
        $_GET['LId'] = $this->report_id_pub;
        $_GET['LName'] = $this->report_name_pub ;
        $_GET['LTag'] = $this->report_group_pub ;
        $this->visit('/createLab')
            ->seeJson([
                'status' => FAIL_MESSAGE,
                'msg' => '该报告号码已经存在',
                'message' => '创建新报告失败' ,
            ]);

        //新建实验号不存在
        $_GET['LId'] = $this->report_id_create;
        $_GET['LName'] = $this->report_name_create ;
        $_GET['LTag'] = $this->report_group_create ;
        $this->visit('/createLab')
            ->seeJson([
                'status' => SUCCESS_MESSAGE,
                'msg' => "" ,
                'message' => '创建新报告成功' ,
            ]);

        //检测新建报告文件是否正确
        $html_file = Config::get('phylab.experimentViewPath')."template.html" ;
        $str_html_exp = file_get_contents($html_file);
        $str_html_exp = str_replace("%%LAB_SUBLAB_ID%%" , $this->report_id_create , $str_html_exp) ;
        $html_file = Config::get('phylab.experimentViewPath').$this->report_id_create.".html" ;
        $str_html_act = file_get_contents($html_file);
        $this->assertEquals($str_html_exp , $str_html_act) ;

        $py_file = Config::get('phylab.scriptPath')."template.py" ;
        $str_py_exp = file_get_contents($py_file);
        $str_py_exp = str_replace("%%LAB_SUBLAB_ID%%" , $this->report_id_create , $str_py_exp) ;
        $py_file = Config::get('phylab.scriptPath')."p".$this->report_id_create.".py" ;
        $str_py_act = file_get_contents($py_file);
        $this->assertEquals($str_py_exp , $str_py_act) ;

        $tex_file = Config::get('phylab.scriptPath')."tex/template.tex" ;
        $str_tex_exp = file_get_contents($tex_file);
        $tex_file = Config::get('phylab.scriptPath')."tex/Handle".$this->report_id_create.".tex" ;
        $str_tex_act = file_get_contents($tex_file);
        $this->assertEquals($str_tex_exp , $str_tex_act) ;

    }

    /**
     * 测试保存实验
     *
     */
    public function testSave(){

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
            ->type($this->gen_admin_email , 'email')
            ->type($this->gen_admin_password , 'password')
            ->press('login-submit');
        $this->post('/report/updatereport' , [
            'reportId' => $this->report_id_pub ,
        ])->seeJson([
            'status' => FAIL_MESSAGE ,
            'message' => "没有更新权限，请联系超级管理员" ,
            //'message' => "没有权限" ,
        ]) ;

        //超级管理员，错误的实验id
        $this->visit('/login')
            ->see('登录')
            ->type($this->super_admin_email , 'email')
            ->type($this->super_admin_password , 'password')
            ->press('login-submit');
        $this->post('/report/updatereport' , [
            'reportId' => $this->report_id_err ,
        ])->seeJson([
            'status' => FAIL_MESSAGE ,
            'message' => "更新失败(wrong_id)" ,
        ]);

        //超级管理员,正确实验id
        $str_py = "test" ;
        $str_tex = "test" ;
        $str_html = "test" ;
        $report_id = $this->report_id_unpub ;
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
    }

    /**
     *
     * 测试发布实验
     */
    public function testConfirm(){

        //普通管理员（无权限）
        $this->visit('/login')
            ->see('登录')
            ->type($this->gen_admin_email , 'email')
            ->type($this->gen_admin_password , 'password')
            ->press('login-submit');
        $this->post('/report/confirmReport')
            ->seeJson([
                'status' => FAIL_MESSAGE ,
                'message' => "没有发布权限，请联系超级管理员" ,
            ]) ;

        //超级管理员，实验id错误
        $this->visit('/login')
            ->see('登录')
            ->type($this->super_admin_email , 'email')
            ->type($this->super_admin_password , 'password')
            ->press('login-submit');
        $this->post('/report/confirmReport' , [
            'reportId' => $this->report_id_err ,
        ])->seeJson([
            'status' => FAIL_MESSAGE ,
            'message' => "发布失败" ,
        ]) ;

        //超级管理员，正确实验Id
        $this->post('/report/confirmReport' , [
            'reportId' => $this->report_id_pub ,
        ])->seeJson([
            'status' => SUCCESS_MESSAGE ,
            'message' => "发布成功" ,
        ]) ;
    }

    /**
     * 测试删除实验
     *
     */
    public function testDelete(){

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
            ->type($this->super_admin_email , 'email')
            ->type($this->super_admin_password , 'password')
            ->press('login-submit');
        $this->post('report/delete')
            ->seeJson([
                'status' => FAIL_MESSAGE ,
                'message' => '实验Id不存在' ,
            ]) ;

        //已发布实验
        $this->post('/report/delete' , [
            'id' => $this->report_id_pub ,
        ])->seeJson([
            'status' => FAIL_MESSAGE ,
            'message' => "实验已发布，请联系超级管理员" ,
        ])  ;

        //正确的未发布实验
        $this->post('/report/delete' , [
            'id' => $this->report_id_unpub ,
        ])->seeJson([
            'status' => SUCCESS_MESSAGE,
            'message' => "删除成功！" ,
        ]);
    }

    /***
     * 测试文件上传
     *
     **/
    public function testUpload(){

        //未登录，重定向
        $this->visit('/logout');
        $this->post('/console/uploadPre')
            ->assertRedirectedTo('index') ;

        //登录无文件
        $this->visit('/login')
            ->see('登录')
            ->type($this->super_admin_email , 'email')
            ->type($this->super_admin_password , 'password')
            ->press('login-submit');
        $this->post('/console/uploadPre')
            ->seeJson([
                'status' =>  FAIL_MESSAGE ,
                'message' => "上传失败，没有找到文件！",
            ]);

        //登录有文件,大小格式正确
        $_POST['labID'] = '2134';
        //Storage::fake('prepare_pdf');  // 伪造目录
        //$pdf = Storage::disk('public')->get('1011.pdf') ;
        //$pdf = new SplFileInfo('prepare_pdf/phylab_test.pdf') ;

        $pdf_info = [
            'name' => public_path().'/prepare_pdf/phylab_test.pdf' ,
            'error' => 0 ,
            'type' => 'pdf' ,
            'size' => 100000 ,
            'tmp_name' => public_path().'/prepare_pdf/phylab_test.pdf' ,
        ] ;
        //var_dump($pdf_info['name']) ;
        $pdf = new UploadedFile($pdf_info['tmp_name'], $pdf_info['name'], $pdf_info['type'], $pdf_info['size'], $pdf_info['error'] , true);
        self::assertTrue($pdf instanceof UploadedFile) ;
        $file_arr = [
            'prepare-pdf' => $pdf ,
        ] ;
        $request = Request::create(
            $this->currentUri, 'POST', ['labID'=>'2134'],
            [], $file_arr, array_replace($this->serverVariables, []), null
        );
        self::assertTrue($request->hasFile('prepare-pdf')) ;
        $file = $request->file('prepare-pdf') ;

        //var_dump($file->getFileInfo()) ;
        //var_dump($file) ;

        //var_dump(phpinfo()) ;
        ///etc/php/7.0/cli/php.ini
        //self::assertTrue(is_uploaded_file($file->getPathname())) ;

        //$file->move(Config::get('phylab.preparePath') , '4444.pdf') ;
        //array_push($file_arr , $pdf) ;
        //$pdf = new SplFileInfo('prepare_pdf/phylab_test.pdf') ;;
        $response = $this->call('POST' , '/console/uploadPre' , ['labID'=>'2134'] , [] , $file_arr);
        $data = $response->getData() ;
        //var_dump($data->message) ;
        self::assertEquals('上传成功' , $data->message) ;
        self::assertEquals(SUCCESS_MESSAGE , $data->status) ;
        self::assertTrue(Storage::disk('local_public')->exists('prepare_pdf/'.'2134.pdf'));
        exec("mv ".public_path().'/prepare_pdf/'.'2134.pdf'.' '.public_path().'/prepare_pdf/'.'phylab_test.pdf',$output,$reval1);

        //有文件，大小或格式错误
        //创建一个测试txt文件
        exec('touch '.public_path().'/prepare_pdf/'.'test.txt',$output,$reval1);
        $pdf_info = [
            'name' => public_path().'/prepare_pdf/test.txt' ,
            'error' => 0 ,
            'type' => 'txt' ,
            'size' => 100000 ,
            'tmp_name' => public_path().'/prepare_pdf/test.txt' ,
        ] ;
        $pdf = new UploadedFile($pdf_info['tmp_name'], $pdf_info['name'], $pdf_info['type'], $pdf_info['size'], $pdf_info['error'] , true);
        $file_arr = [
            'prepare-pdf' => $pdf ,
        ] ;
        $response = $this->call('POST' , '/console/uploadPre' , ['labID'=>'2134'] , [] , $file_arr);
        $data = $response->getData() ;
        self::assertTrue(!Storage::disk('local_public')->exists('prepare_pdf/'.'2134.pdf')) ;
        self::assertEquals('上传失败，文件格式或大小不符合要求！' , $data->message) ;
        self::assertEquals('7306' , $data->errorcode) ;
        self::assertEquals(FAIL_MESSAGE , $data->status) ;
        //删除创建的测试文件
        exec('rm -rf '.public_path().'/prepare_pdf/'.'test.txt',$output,$reval1);
        //$res = $this->call('POST' , '/console/uploadPre') ;
        //var_dump($res->getData()) ;
        //self::assertEquals('7307' , $res->getData()->errorcode) ;
        //self::assertTrue($pdf instanceof SplFileInfo && $pdf->getPath() != '') ;
        /*
        $_FILES["prepare-pdf"]["name"] = $pdf_info['name'] ;
        $_FILES["prepare-pdf"]['error'] = $pdf_info['error'] ;
        $_FILES["prepare-pdf"]['type'] = $pdf_info['type'] ;
        $_FILES["prepare-pdf"]['tmp_name'] = $pdf_info['tmp_name'] ;
        $_FILES["prepare-pdf"]['size'] = $pdf_info['size'] ;
        */
        //$_FILES['prepare-pdf'] = $pdf ;
        //Request::capture() ;
        //self::assertTrue(Request::hasFile('prepare-pdf')) ;
        //$request = Illuminate\Http\Request::capture()  ;
        //self::assertTrue($request->hasFile('prepare-pdf')) ;

        //$_POST['test'] = 'test' ;
        //$pdf = UploadedFile::fake()->image('2134.pdf');  // 伪造上传图片
        $this->post('/console/uploadPre' , [
            'prepare-pdf' => $pdf ,
        ])->seeJson([
            'status' => FAIL_MESSAGE ,
            //'message' => "上传失败，文件格式或大小不符合要求！",
            //'message' => 'prepare_pdf/phylab_test.pdf',
            'message' => "上传失败，没有找到文件！",
        ]) ;

        /*
        //\Illuminate\Http\Request::capture() ;
        //\App\Http\Requests\Request::capture() ;
        self::assertTrue(\Illuminate\Http\Request::hasFile('prepare-pdf')) ;
        $con = new ConsoleController() ;
        $res = $con->uploadPreparePdf();
        $data = ["status"=>FAIL_MESSAGE,"message"=>"上传失败，没有找到文件！","errorcode"=>"7307"];
        $data = response()->json($data) ;
        self::assertEquals($data , $res) ;
        */

    }

    /**
     *
     * 测试尝试
     */
    public function testTest(){
        $this->visit('/login')
            ->see('登录')
            ->type($this->super_admin_email , 'email')
            ->type($this->super_admin_password , 'password')
            ->press('登录') ;
        $this->post('/user' , [
            'introduction' => 'test' ,
        ]) ;

        $pdf_info = [
            'name' => public_path().'/prepare_pdf/phylab_test.pdf' ,
            'error' => 0 ,
            'type' => 'pdf' ,
            'size' => 100000000 ,
            'tmp_name' => public_path().'/prepare_pdf/phylab_test.pdf' ,
        ] ;

        $pdf = new UploadedFile($pdf_info['tmp_name'], $pdf_info['name'], $pdf_info['type'], $pdf_info['size'], $pdf_info['error']);
        $this->visit('console')
             ->see('上传实验预习报告')
             //->press('上传实验预习报告')
             ->see('上传')
             ->attach($pdf_info['name'] , 'prepare-pdf');
             //->press('上传') ;
            /*
             ->post('/console/uploadPre')
             ->seeJson([
                'status' => FAIL_MESSAGE ,
                //'message' => "上传失败，文件格式或大小不符合要求！",
                //'message' => 'prepare_pdf/phylab_test.pdf',
                'message' => "上传失败，没有找到文件！",
             ]) ;
            */
             //->press('btn-upload-preview');
        //$this->visit('/logout') ;

    }


}
