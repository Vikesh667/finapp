<?php 
namespace App\Controllers;

class NotificationController extends BaseController{

    public function notifications():string{
        return view('app-notifications');
    }
     public function notification_details():string{
        return view('app-notification-detail');
    }
}