<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class DebugScriptController extends Controller {

    public function debug(){
        // $id = $_GET['id'];
        /*$id = $_GET['id'];
        $xml = 'null';
        $pdf = "/var/www/buaaphylab/tmp_pdf/".$id;
        $res = exec("python /var/www/buaaphylab/storage/app/script/handler.py ".$id.' '.$xml." df 2>&1     ", $output,$rval);
        if($rval!=0)
            print_r($output);
        else {
            header("Content-type: application/pdf");
            readfile("$pdf.pdf");
        }*/
        hedaer('Location: https://www.baidu.com/');
        exit;
        return 1;
    }

}