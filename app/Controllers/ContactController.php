<?php
namespace App\Controllers;
class ContactController extends BaseController{
    public function contacts():string{
        return view('app-contact');
    }
}