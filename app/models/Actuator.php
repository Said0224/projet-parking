<?php
require_once ROOT_PATH . '/config/database.php';

class Actuator {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Récupère l'état de toutes les LEDs
     */
    public function getAllLEDs() {
        try {
            $stmt = $this->db->query("SELECT * FROM LED ORDER BY id ASC");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Erreur dans getAllLEDs : " . $e->getMessage());
            return [];
        }
    }

    /**
     * Met à jour l'état d'une LED
     */
    public function updateLED($id, $etat) {
        try {
            $stmt = $this->db->prepare("UPDATE LED SET etat = ? WHERE id = ?");
            return $stmt->execute([$etat, $id]);
        } catch (PDOException $e) {
            error_log("Erreur dans updateLED : " . $e->getMessage());
            return false;
        }
    }

    /**
     * Ajoute une nouvelle LED
     */
    public function addLED($etat) {
        try {
            $stmt = $this->db->prepare("INSERT INTO LED (etat) VALUES (?)");
            return $stmt->execute([$etat]);
        } catch (PDOException $e) {
            error_log("Erreur dans addLED : " . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupère les données OLED pour l'affichage des informations de parking
     */
    public function getOLEDData() {
        try {
            $stmt = $this->db->query("SELECT * FROM OLED ORDER BY id DESC LIMIT 1");
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Erreur dans getOLEDData : " . $e->getMessage());
            return null;
        }
    }

    /**
     * Met à jour les informations OLED
     */
    public function updateOLED($places_dispo, $bornes_dispo, $user = '', $plaque = '') {
        try {
            $stmt = $this->db->prepare("
                UPDATE OLED SET 
                places_dispo = ?, 
                bornes_dispo = ?, 
                heure = CURRENT_TIMESTAMP,
                \"user\" = ?,
                plaque_immatriculation = ?
                WHERE id = 1
            ");
            return $stmt->execute([$places_dispo, $bornes_dispo, $user, $plaque]);
        } catch (PDOException $e) {
            error_log("Erreur dans updateOLED : " . $e->getMessage());
            return false;
        }
    }
}