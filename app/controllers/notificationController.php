<?php
$emailNotif = $notificationModel->getEmailNotificationPreference($_SESSION['user_id']);

require_once ROOT_PATH . '/app/models/Notification.php';
require_once ROOT_PATH . '/app/controllers/AuthController.php';

class NotificationController {
    public function index() {
        AuthController::requireAuth();

        $page_title = "Mes Notifications - Parking Intelligent";
        $notificationModel = new Notification();

        $notifications = $notificationModel->getNotificationsForUser($_SESSION['user_id']);
        $notificationModel->markAllAsRead($_SESSION['user_id']);

        require_once ROOT_PATH . '/app/views/notifications.php';
    }
}