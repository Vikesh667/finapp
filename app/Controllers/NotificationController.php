<?php

namespace App\Controllers;

use App\Models\NotificationModel;

class NotificationController extends BaseController
{


    public function app_notification()
    {
        $notificationModel = new NotificationModel();

        $data['notifications'] = $notificationModel
            ->orderBy('id', 'DESC')
            ->where('user_id', session()->get('user_id')) // optional: show only logged-in user's notifications
            ->findAll();

        return view('app-notification', $data);
    }


    public function fetchNotifications()
    {
        $notificationModel = new \App\Models\NotificationModel();
        $userId = session()->get('user_id');

        $data = $notificationModel
            ->where('user_id', $userId)
            ->orderBy('is_read', 'ASC')  // unread first
            ->orderBy('id', 'DESC')
            ->limit(10)
            ->findAll();  // correct method

        return $this->response->setJSON($data);
    }


    public function markNotificationRead($id)
    {
        $notificationModel = new \App\Models\NotificationModel();
        $userId = session()->get('user_id');

        // Check ownership
        $notification = $notificationModel
            ->where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$notification) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Not allowed'
            ]);
        }

        $notificationModel->update($id, ['is_read' => 1]);

        return $this->response->setJSON(['status' => 'success']);
    }
    public function view($id)
    {
        $model = new NotificationModel();
        $userId = session()->get('user_id');

        $notification = $model->where('id', $id)->where('user_id', $userId)->first();
        if (!$notification) {
            return redirect()->back()->with('error', 'Notification not found');
        }

        return view('notification-detail', ['notification' => $notification]);
    }
}
