<?php

class NotificationModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAll()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM notifications ORDER BY date DESC, heure DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function markAllAsRead()
    {
        $stmt = $this->pdo->prepare("UPDATE notifications SET is_read = TRUE");
        return $stmt->execute();
    }

    public function getByType($type)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM notifications WHERE type = ? ORDER BY date DESC, heure DESC");
        $stmt->execute([$type]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEmailNotifSetting($userId)
    {
        $stmt = $this->pdo->prepare("SELECT email_notifications FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchColumn();
    }

    public function updateEmailNotifSetting($userId, $enabled)
    {
        $stmt = $this->pdo->prepare("UPDATE users SET email_notifications = ? WHERE id = ?");
        return $stmt->execute([$enabled, $userId]);
    }
}
