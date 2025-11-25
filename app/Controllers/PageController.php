<?php
namespace App\Controllers;
class PageController extends BaseController{
    public function pages(){
        return view('app-pages');
    }
}