<?php
require_once ROOT_PATH . '/config/database.php';

class ParkingSensor {
    private $db;

    public function __construct() {
    // Forcer la connexion à la base de données des capteurs (PostgreSQL)
    $this->db = Database::getInstance('sensors');
}

    /**
     * Récupère toutes les données des capteurs de proximité (places de parking)
     */
    public function getAllParkingSpaces() {
        try {
            $stmt = $this->db->query("SELECT * FROM capteurProximite ORDER BY place ASC");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Erreur dans getAllParkingSpaces : " . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupère les dernières données de chaque place
     */
    public function getLatestParkingStatus() {
        try {
            $stmt = $this->db->query("
                SELECT DISTINCT ON (place) place, date, heure, valeur 
                FROM capteurProximite 
                ORDER BY place, date DESC, heure DESC
            ");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Erreur dans getLatestParkingStatus : " . $e->getMessage());
            return [];
        }
    }

    /**
     * Ajoute une nouvelle mesure de capteur de proximité
     */
    public function addMeasurement($place, $valeur) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO capteurProximite (place, date, heure, valeur) 
                VALUES (?, CURRENT_DATE, CURRENT_TIME, ?)
            ");
            return $stmt->execute([$place, $valeur]);
        } catch (PDOException $e) {
            error_log("Erreur dans addMeasurement : " . $e->getMessage());
            return false;
        }
    }

    /**
     * Met à jour l'état d'une place de parking
     */
    public function updateParkingSpace($id, $place, $valeur) {
        try {
            $stmt = $this->db->prepare("
                UPDATE capteurProximite 
                SET place = ?, valeur = ?, date = CURRENT_DATE, heure = CURRENT_TIME 
                WHERE id = ?
            ");
            return $stmt->execute([$place, $valeur, $id]);
        } catch (PDOException $e) {
            error_log("Erreur dans updateParkingSpace : " . $e->getMessage());
            return false;
        }
    }

    /**
     * Supprime une mesure
     */
    public function deleteMeasurement($id) {
        try {
            $stmt = $this->db->prepare("DELETE FROM capteurProximite WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Erreur dans deleteMeasurement : " . $e->getMessage());
            return false;
        }
    }
}