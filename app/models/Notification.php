<?php
// Fichier : app/models/Notification.php

require_once ROOT_PATH . '/config/database.php';

class Notification {
    private $db;

    public function __construct() {
        // Utilise la connexion 'local' définie dans votre DatabaseManager
        $this->db = DatabaseManager::getConnection('local');
    }

    /**
     * Crée une nouvelle notification pour un utilisateur.
     */
    public function createNotification($user_id, $type, $contenu) {
        try {
            $stmt = $this->db->prepare(
                "INSERT INTO notifications (user_id, type, contenu) VALUES (?, ?, ?)"
            );
            return $stmt->execute([$user_id, $type, $contenu]);
        } catch (PDOException $e) {
            error_log("Erreur dans Notification::createNotification : " . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupère toutes les notifications d'un utilisateur.
     */
    public function getNotifications($user_id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY date DESC");
            $stmt->execute([$user_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur dans Notification::getNotifications : " . $e->getMessage());
            return [];
        }
    }

    /**
     * Marque toutes les notifications d'un utilisateur comme lues.
     */
    public function markAsRead($user_id) {
        try {
            $stmt = $this->db->prepare("UPDATE notifications SET est_lu = TRUE WHERE user_id = ? AND est_lu = FALSE");
            return $stmt->execute([$user_id]);
        } catch (PDOException $e) {
            error_log("Erreur dans Notification::markAsRead : " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Récupère les préférences de notification par e-mail d'un utilisateur.
     */
    public function getMailPreference($user_id) {
        try {
            $stmt = $this->db->prepare("SELECT recevoir_mails FROM users WHERE id = ?");
            $stmt->execute([$user_id]);
            $result = $stmt->fetch();
            return $result ? (bool)$result['recevoir_mails'] : true; // Par défaut à true
        } catch (PDOException $e) {
            error_log("Erreur dans Notification::getMailPreference : " . $e->getMessage());
            return true;
        }
    }

    /**
     * Met à jour les préférences de notification par e-mail d'un utilisateur.
     */
    public function updateMailPreference($user_id, $preference) {
        try {
            $stmt = $this->db->prepare("UPDATE users SET recevoir_mails = ? WHERE id = ?");
            return $stmt->execute([$preference, $user_id]);
        } catch (PDOException $e) {
            error_log("Erreur dans Notification::updateMailPreference : " . $e->getMessage());
            return false;
        }
    }
}