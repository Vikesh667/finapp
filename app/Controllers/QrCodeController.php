<?php 
namespace App\Controllers;
class QrCodeController extends BaseController{
    public function qr_code():string{
        return view('app-qr-code');
    }
}