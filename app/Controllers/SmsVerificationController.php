<?php
namespace App\Controllers;

class SmsVerificationController extends BaseController{
    public function sms_verification():string{
        return view('app-sms-verification');
    }
}