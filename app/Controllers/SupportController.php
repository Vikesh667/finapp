<?php 
namespace App\Controllers;
class SupportController extends BaseController{
    public function support():string{
        return view('component-messages');
    }
}