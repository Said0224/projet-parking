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
        $emailNotif = $notificationModel->getEmailNotificationPreference($_SESSION['user_id']);

        require_once ROOT_PATH . '/app/views/notifications.php';
    }
}
/**
 * Reçoit la requête AJAX pour activer/désactiver les notifications par e‑mail.
 */
public function toggleEmailNotifications() {
    AuthController::requireAuth();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $enabled = isset($_POST['enabled']) && $_POST['enabled'] === 'true';

        $notificationModel = new Notification();
        $success = $notificationModel->updateEmailNotificationPreference($_SESSION['user_id'], $enabled);

        header('Content-Type: application/json');
        echo json_encode(['success' => $success]);
        exit;
    }
}
