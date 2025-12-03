<?php

namespace App\Controllers;

class NotificationController extends BaseController
{

    public function fetchNotifications()
    {
        $notificationModel = new \App\Models\NotificationModel();
        $userId = session()->get('user_id');

        $data = $notificationModel
            ->orderBy('id', 'DESC')
            ->limit(10)
            ->find();

        return $this->response->setJSON($data);
    }

    public function markNotificationRead($id)
    {
        
        $notificationModel = new \App\Models\NotificationModel();

        $notificationModel->update($id, ['is_read' => 1]);

        return $this->response->setJSON(['status' => 'success']);
    }
}
