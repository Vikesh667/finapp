<?php
namespace App\Controllers;
class SavingController extends BaseController{
    public function savings():string{
        return view('app-savings');
    }
}