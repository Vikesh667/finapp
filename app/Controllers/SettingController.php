<?php
namespace App\Controllers;

use App\Models\UserModel;

class SettingController extends BaseController{
    public function settings():string{
        $userModel=new UserModel();
        $user=$userModel->findAll();
        return view('app-settings');
    }
}