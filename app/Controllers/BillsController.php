<?php
namespace App\Controllers;
class BillsController extends BaseController{
    public function bills():string{
        return view('app-bills');
    }
}