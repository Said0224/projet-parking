<?php
require_once ROOT_PATH . '/config/database.php';

class Actuator {
    private $db;

    public function __construct() {
        // Use the 'iot' connection
        $this->db = DatabaseManager::getConnection('iot');
    }

    // --- LED Methods ---
    public function getAllLEDs() {
        try {
            $stmt = $this->db->query("SELECT * FROM public.led ORDER BY id ASC");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Erreur dans getAllLEDs : " . $e->getMessage());
            return [];
        }
    }

    public function updateLEDState($id, $etat) {
        try {
            $stmt = $this->db->prepare("UPDATE public.led SET etat = ?, timestamp = CURRENT_TIMESTAMP WHERE id = ?");
            return $stmt->execute([$etat, $id]);
        } catch (PDOException $e) {
            error_log("Erreur dans updateLEDState : " . $e->getMessage());
            return false;
        }
    }

    // --- OLED Methods ---
    public function getOLEDData() {
        try {
            $stmt = $this->db->query("SELECT * FROM public.oled ORDER BY id DESC LIMIT 1");
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Erreur dans getOLEDData : " . $e->getMessage());
            return null;
        }
    }

    public function updateOLED($places_dispo, $bornes_dispo, $user = '', $plaque = '') {
        try {
            $stmt = $this->db->prepare('
                UPDATE public.oled SET 
                places_dispo = ?, 
                bornes_dispo = ?, 
                heure = CURRENT_TIMESTAMP,
                "user" = ?,
                plaque_immatriculation = ?
                WHERE id = 1
            ');
            return $stmt->execute([$places_dispo, $bornes_dispo, $user, $plaque]);
        } catch (PDOException $e) {
            error_log("Erreur dans updateOLED : " . $e->getMessage());
            return false;
        }
    }
    
    // ===== NOUVELLES MÉTHODES POUR LES MOTEURS =====
    
    /**
     * Récupère tous les moteurs.
     * @return array
     */
    public function getAllMotors() {
        try {
            $stmt = $this->db->query("SELECT * FROM public.moteur ORDER BY id ASC");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Erreur dans getAllMotors : " . $e->getMessage());
            return [];
        }
    }

    /**
     * Met à jour l'état et la vitesse d'un moteur.
     * @param int $id
     * @param bool $etat
     * @param int $vitesse
     * @return bool
     */
    public function updateMotor($id, $etat, $vitesse) {
        try {
            $stmt = $this->db->prepare("UPDATE public.moteur SET etat = ?, vitesse = ?, timestamp = CURRENT_TIMESTAMP WHERE id = ?");
            return $stmt->execute([$etat, $vitesse, $id]);
        } catch (PDOException $e) {
            error_log("Erreur dans updateMotor : " . $e->getMessage());
            return false;
        }
    }

    
}