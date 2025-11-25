<?php
namespace App\Controllers;

class ComponentController extends BaseController{
    public function components():string{
        return view('app-components');
    }
}