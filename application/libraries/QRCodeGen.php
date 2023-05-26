<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once APPPATH."/third_party/phpqrcode/qrlib.php";

class QRCodeGen {
    public function generate($data, $filename = false, $size = 4, $level = 'L'){
        QRcode::png($data, $filename, $level, $size);
    }
}
?>