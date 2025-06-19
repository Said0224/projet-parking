<?php
require_once ROOT_PATH . '/config/database.php';

class ParkingSensor {
    private $db;

    public function __construct() {
        // Use the 'iot' connection
        $this->db = DatabaseManager::getConnection('iot');
    }

    // --- Capteur de Proximité ---

    public function getAllParkingSpaces() {
        try {
            $stmt = $this->db->query("SELECT * FROM public.capteurproximite ORDER BY place ASC");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Erreur dans getAllParkingSpaces : " . $e->getMessage());
            return [];
        }
    }

    public function getLatestParkingStatus() {
        try {
            // This standard SQL should work fine on PostgreSQL
            $sql = '
                SELECT cp1.*
                FROM public.capteurproximite cp1
                INNER JOIN (
                    SELECT place, MAX(id) as max_id
                    FROM public.capteurproximite
                    GROUP BY place
                ) cp2 ON cp1.place = cp2.place AND cp1.id = cp2.max_id
                ORDER BY cp1.place ASC
            ';
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Erreur dans getLatestParkingStatus (PostgreSQL) : " . $e->getMessage());
            return [];
        }
    }

    // --- AUTRES CAPTEURS ---

    /**
     * Récupère la dernière mesure d'un type de capteur donné.
     * @param string $tableName Le nom exact de la table du capteur (ex: 'capteurgaz')
     * @return array|null Les données de la dernière mesure ou null.
     */
    private function getLatestReading($tableName) {
        try {
            // Valide le nom de la table pour la sécurité
            $allowedTables = ['capteurgaz', 'capteurlum', 'capteurson', 'capteurtemp'];
            if (!in_array($tableName, $allowedTables)) {
                throw new Exception("Nom de table non autorisé : " . $tableName);
            }

            // SQL CORRECTION: PostgreSQL uses double quotes for identifiers if needed, not backticks.
            // We also specify the 'public' schema for clarity.
            $stmt = $this->db->query("SELECT * FROM public.{$tableName} ORDER BY id DESC LIMIT 1");
            return $stmt->fetch();
        } catch (Exception $e) {
            error_log("Erreur dans getLatestReading pour la table $tableName : " . $e->getMessage());
            return null;
        }
    }

    public function getLatestGasReading() {
        return $this->getLatestReading('capteurgaz');
    }

    public function getLatestLightReading() {
        return $this->getLatestReading('capteurlum');
    }

    public function getLatestSoundReading() {
        return $this->getLatestReading('capteurson');
    }

    public function getLatestTempReading() {
        return $this->getLatestReading('capteurtemp');
    }

    // --- Méthodes de modification (Add, Update, Delete) ---

    public function addMeasurement($place, $valeur) {
        try {
            // CURRENT_DATE et CURRENT_TIME are standard SQL functions.
            $stmt = $this->db->prepare("
                INSERT INTO public.capteurproximite (place, date, heure, valeur) 
                VALUES (?, CURRENT_DATE, CURRENT_TIME, ?)
            ");
            return $stmt->execute([$place, $valeur]);
        } catch (PDOException $e) {
            error_log("Erreur dans addMeasurement : " . $e->getMessage());
            return false;
        }
    }
}