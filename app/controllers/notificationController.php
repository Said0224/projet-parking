<?php
require_once 'models/NotificationModel.php';
// NotificationController.php

class NotificationController
{
    private $model;

    public function __construct($pdo)
    {
        $this->model = new NotificationModel($pdo);
    }

    public function index()
    {
        session_start();
        $userId = $_SESSION['user_id'] ?? null;

        $notifications = $this->model->getAll();
        $emailNotif = $this->model->getEmailNotifSetting($userId);

        include 'views/notification.view.php';
    }

    public function markAllAsRead()
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            http_response_code(403);
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }

        $this->model->markAllAsRead();
        echo json_encode(['success' => true]);
    }

    public function filterByType($type)
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            http_response_code(403);
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }

        $notifications = $this->model->getByType($type);
        echo json_encode($notifications);
    }

    public function toggleEmailNotif()
    {
        session_start();
        if (!isset($_SESSION['user_id'])) {
            http_response_code(403);
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }

        $enabled = $_POST['enabled'] === 'true' ? 1 : 0;
        $this->model->updateEmailNotifSetting($_SESSION['user_id'], $enabled);

        echo json_encode(['success' => true]);
    }
}
