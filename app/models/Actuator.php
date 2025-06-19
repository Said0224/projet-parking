<?php
require_once ROOT_PATH . '/config/database.php';

class Actuator {
    private $db;

    public function __construct() {
        $this->db = DatabaseManager::getConnection('iot');
    }

    // --- NOUVELLE MÉTHODE ---
    /**
     * Récupère les détails d'une LED par son ID.
     * @param int $id
     * @return array|false
     */
    public function getLedById($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM public.led WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Erreur dans getLedById : " . $e->getMessage());
            return false;
        }
    }
    // --- FIN NOUVELLE MÉTHODE ---

    public function getAllLEDs() {
        try {
            $stmt = $this->db->query("SELECT * FROM public.led ORDER BY id ASC");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Erreur dans getAllLEDs : " . $e->getMessage());
            return [];
        }
    }

    /**
     * Met à jour les détails d'une LED (état, couleur, intensité, et commande).
     * @param int $id
     * @param bool $etat
     * @param string $couleur
     * @param int $intensite
     * @param string $command La source de la commande ('manual_override', 'auto_status', etc.)
     * @return bool
     */
    public function updateLedDetails($id, $etat, $couleur, $intensite, $command) {
        try {
            $stmt = $this->db->prepare("
                UPDATE public.led 
                SET etat = ?, couleur = ?, intensite = ?, last_command = ?, timestamp = CURRENT_TIMESTAMP 
                WHERE id = ?
            ");
            return $stmt->execute([$etat, $couleur, $intensite, $command, $id]);
        } catch (PDOException $e) {
            error_log("Erreur dans updateLedDetails : " . $e->getMessage());
            return false;
        }
    }
    
    // ... (Le reste du fichier reste identique) ...
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
    
    public function getAllMotors() {
        try {
            $stmt = $this->db->query("SELECT * FROM public.moteur ORDER BY id ASC");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Erreur dans getAllMotors : " . $e->getMessage());
            return [];
        }
    }

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