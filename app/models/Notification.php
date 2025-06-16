<?php
<?php
require_once ROOT_PATH . '/config/database.php';

class Notification
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance('primary');
    }

    public function create($user_id, $title, $message, $link = null)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO notifications (user_id, title, message, link) VALUES (?, ?, ?, ?)"
        );
        return $stmt->execute([$user_id, $title, $message, $link]);
    }

    public function getNotificationsForUser($user_id)
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC"
        );
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function markAllAsRead($user_id)
    {
        $stmt = $this->db->prepare(
            "UPDATE notifications SET is_read = TRUE WHERE user_id = ? AND is_read = FALSE"
        );
        return $stmt->execute([$user_id]);
    }

    public function getByType($user_id, $type)
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM notifications WHERE user_id = ? AND type = ? ORDER BY created_at DESC"
        );
        $stmt->execute([$user_id, $type]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getEmailNotifSetting($userId)
    {
        $stmt = $this->db->prepare("SELECT email_notifications FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchColumn();
    }

    public function updateEmailNotifSetting($userId, $enabled)
    {
        $stmt = $this->db->prepare("UPDATE users SET email_notifications = ? WHERE id = ?");
        return $stmt->execute([$enabled, $userId]);
    }
}