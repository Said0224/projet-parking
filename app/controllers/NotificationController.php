<?php
require_once ROOT_PATH . '/app/models/Notification.php';
require_once ROOT_PATH . '/app/controllers/AuthController.php';

class NotificationController {

    public function index() {
        // S'assure que l'utilisateur est connecté
        AuthController::requireAuth();

        $page_title = "Mes Notifications - Parking Intelligent";
        $notificationModel = new Notification();
        
        // Récupérer les notifications pour l'utilisateur connecté
        $notifications = $notificationModel->getNotificationsForUser($_SESSION['user_id']);

        // Marquer toutes les notifications comme lues une fois que la page est affichée
        $notificationModel->markAllAsRead($_SESSION['user_id']);

        require_once ROOT_PATH . '/app/views/notifications.php';
    }
}