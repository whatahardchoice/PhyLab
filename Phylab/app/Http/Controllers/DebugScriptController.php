<?php namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class DebugScriptController extends Controller {

    public function debug(){
        $id = $_GET['id'];
        $xml = 'null';
        $pdf = "/var/www/buaaphylab/tmp_pdf/".$id;
        $res = exec("python /var/www/buaaphylab/storage/app/script/handler.py ".$id.' '.$xml." $pdf 2>&1 ", $output,$rval);
        if($rval!=0) {
            foreach ($output as $i => $o) {
                $o = preg_replace('/ /', '&nbsp;', $o);
                $o = preg_replace('/\\t/', '&nbsp;&nbsp;&nbsp;&nbsp;', $o);
                $o = htmlspecialchars($o)."<br />\n";
                $o = preg_replace('/&amp;nbsp;/', '&nbsp;', $o);
                print($o);
            }
        }
        else {
            header("Content-type: application/pdf");
            readfile("$pdf.pdf");
        }
    }

}