<?php

use App\Models\NotificationModel;
use Pusher\Pusher;

function push_notification($userId, $message, $title = "Notification")
{
    $notificationModel = new NotificationModel();
    $notificationModel->insert([
        'user_id'    => $userId,
        'message'    => $message,
        'is_read'    => 0,
        'created_at' => date('Y-m-d H:i:s')
    ]);

    $pusher = new Pusher(
        getenv('pusher.key'),
        getenv('pusher.secret'),
        getenv('pusher.app_id'),
        ['cluster' => getenv('pusher.cluster'), 'useTLS' => true]
    );

    $pusher->trigger("admin-notifications", "new-notification", [
        "title"   => $title,
        "message" => $message
    ]);
}
