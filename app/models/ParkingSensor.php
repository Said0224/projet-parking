<?php
require_once ROOT_PATH . '/config/database.php';

class ParkingSensor {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    // --- Capteur de Proximité ---

    public function getAllParkingSpaces() {
        try {
            $stmt = $this->db->query("SELECT * FROM capteurProximite ORDER BY place ASC");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Erreur dans getAllParkingSpaces : " . $e->getMessage());
            return [];
        }
    }

    public function getLatestParkingStatus() {
        try {
            $sql = "
                SELECT cp1.*
                FROM capteurProximite cp1
                INNER JOIN (
                    SELECT place, MAX(id) as max_id
                    FROM capteurProximite
                    GROUP BY place
                ) cp2 ON cp1.place = cp2.place AND cp1.id = cp2.max_id
                ORDER BY cp1.place ASC
            ";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Erreur dans getLatestParkingStatus (MySQL) : " . $e->getMessage());
            return [];
        }
    }

    // --- AUTRES CAPTEURS ---

    /**
     * Récupère la dernière mesure d'un type de capteur donné.
     * @param string $tableName Le nom exact de la table du capteur (ex: 'capteurGaz')
     * @return array|null Les données de la dernière mesure ou null.
     */
    private function getLatestReading($tableName) {
        try {
            // Valide le nom de la table pour la sécurité (évite l'injection SQL)
            $allowedTables = ['capteurGaz', 'capteurLum', 'capteurSon', 'capteurTemp'];
            if (!in_array($tableName, $allowedTables)) {
                throw new Exception("Nom de table non autorisé : " . $tableName);
            }

            // Utilise le nom de table validé
            $stmt = $this->db->query("SELECT * FROM `$tableName` ORDER BY id DESC LIMIT 1");
            return $stmt->fetch();
        } catch (Exception $e) {
            error_log("Erreur dans getLatestReading pour la table $tableName : " . $e->getMessage());
            return null;
        }
    }

    public function getLatestGasReading() {
        return $this->getLatestReading('capteurGaz');
    }

    public function getLatestLightReading() {
        return $this->getLatestReading('capteurLum');
    }

    public function getLatestSoundReading() {
        return $this->getLatestReading('capteurSon');
    }

    public function getLatestTempReading() {
        return $this->getLatestReading('capteurTemp');
    }

    // --- Méthodes de modification (Add, Update, Delete) ---

    public function addMeasurement($place, $valeur) {
        try {
            $stmt = $this->db->prepare("
                INSERT INTO capteurProximite (place, date, heure, valeur) 
                VALUES (?, CURRENT_DATE(), CURRENT_TIME(), ?)
            ");
            return $stmt->execute([$place, $valeur]);
        } catch (PDOException $e) {
            error_log("Erreur dans addMeasurement : " . $e->getMessage());
            return false;
        }
    }
    
    // Vous pouvez ajouter des méthodes similaires pour les autres capteurs si nécessaire
}