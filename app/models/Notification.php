<?php
require_once ROOT_PATH . '/config/database.php';
class Notification {
    private $pdo;

    public function __construct() {
        try {
            $this->pdo = new PDO("mysql:host=localhost;dbname=nom_de_ta_bdd", "user", "password");
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Erreur de connexion : " . $e->getMessage());
        }
    }

    public function getNotifications($user_id) {
        $stmt = $this->pdo->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY date DESC");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll();
    }

    public function deleteNotification($notif_id) {
        $stmt = $this->pdo->prepare("DELETE FROM notifications WHERE id = ?");
        $stmt->execute([$notif_id]);
    }

    public function updateMailPreference($user_id, $preference) {
        $stmt = $this->pdo->prepare("UPDATE utilisateurs SET recevoir_mails = ? WHERE id = ?");
        $stmt->execute([$preference, $user_id]);
    }

    public function getMailPreference($user_id) {
        $stmt = $this->pdo->prepare("SELECT recevoir_mails FROM utilisateurs WHERE id = ?");
        $stmt->execute([$user_id]);
        $result = $stmt->fetch();
        return $result ? (int)$result['recevoir_mails'] : 1;
    }

    public function getUserEmail($user_id) {
        $stmt = $this->pdo->prepare("SELECT email FROM utilisateurs WHERE id = ?");
        $stmt->execute([$user_id]);
        $result = $stmt->fetch();
        return $result ? $result['email'] : null;
    }
}
