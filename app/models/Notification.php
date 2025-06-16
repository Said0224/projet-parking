<?php
require_once ROOT_PATH . '/config/database.php';

class Notification {
    private $db;

    public function __construct() {
        // On utilise la connexion 'primary' (MariaDB) où se trouve la table 'users'
        $this->db = Database::getInstance('primary'); 
    }

    /**
     * Crée une nouvelle notification pour un utilisateur.
     */
    public function create($user_id, $title, $message, $link = null) {
        try {
            $stmt = $this->db->prepare(
                "INSERT INTO notifications (user_id, title, message, link) VALUES (?, ?, ?, ?)"
            );
            return $stmt->execute([$user_id, $title, $message, $link]);
        } catch (PDOException $e) {
            error_log("Erreur dans Notification::create : " . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupère toutes les notifications pour un utilisateur donné.
     */
    public function getNotificationsForUser($user_id) {
        try {
            $stmt = $this->db->prepare(
                "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC"
            );
            $stmt->execute([$user_id]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Erreur dans Notification::getNotificationsForUser : " . $e->getMessage());
            return [];
        }
    }

    /**
     * Marque toutes les notifications d'un utilisateur comme lues.
     */
    public function markAllAsRead($user_id) {
        try {
            $stmt = $this->db->prepare(
                "UPDATE notifications SET is_read = TRUE WHERE user_id = ? AND is_read = FALSE"
            );
            return $stmt->execute([$user_id]);
        } catch (PDOException $e) {
            error_log("Erreur dans Notification::markAllAsRead : " . $e->getMessage());
            return false;
        }
    }
}