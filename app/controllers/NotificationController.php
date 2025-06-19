<?php
// Fichier : app/controllers/NotificationController.php

require_once ROOT_PATH . '/app/models/Notification.php';
require_once ROOT_PATH . '/app/models/User.php';

class NotificationController {
    private $notificationModel;
    private $userModel;

    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        $this->notificationModel = new Notification();
        $this->userModel = new User();
    }

    /**
     * Affiche la page des notifications.
     */
    public function index() {
        $user_id = $_SESSION['user_id'];
        $page_title = "Mes Notifications";
        
        $notifications = $this->notificationModel->getNotifications($user_id);
        $email_preference = $this->notificationModel->getMailPreference($user_id);
        
        // Marquer les notifications comme lues lors de la visite de la page
        $this->notificationModel->markAsRead($user_id);

        require_once ROOT_PATH . '/app/views/notification.php';
    }

    /**
     * Met à jour la préférence de réception d'e-mails.
     */
    public function updateMailPreference() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/notifications');
            exit;
        }

        $user_id = $_SESSION['user_id'];
        $preference = isset($_POST['notif_email']) ? 1 : 0;

        if ($this->notificationModel->updateMailPreference($user_id, $preference)) {
            $_SESSION['notif_success'] = "Préférences de notification mises à jour.";
        } else {
            $_SESSION['notif_error'] = "Erreur lors de la mise à jour des préférences.";
        }

        header('Location: ' . BASE_URL . '/notifications');
        exit;
    }
}