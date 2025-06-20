<?php
require_once ROOT_PATH . '/config/database.php';

class ParkingSensor {
    private $db;

    public function __construct() {
        // Use the 'iot' connection
        $this->db = DatabaseManager::getConnection('iot');
    }

    // ==========================================================
    // == NOUVELLE MÉTHODE POUR LA LECTURE PAR ID ==
    // ==========================================================
    
    /**
     * Récupère la dernière lecture d'un capteur de proximité par son ID de ligne.
     * @param int $sensorId L'ID de la ligne dans la table.
     * @return array|false
     */
    public function getProximityReadingById($sensorId) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM public.capteurproximite WHERE id = ?");
            $stmt->execute([$sensorId]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Erreur dans getProximityReadingById : " . $e->getMessage());
            return false;
        }
    }

    /**
     * Récupère l'historique d'un capteur de proximité par son ID de ligne.
     * Pour notre cas, cela n'a plus de sens car on UPDATE la ligne, mais on la garde
     * au cas où vous changeriez d'avis. Pour un seul capteur, on peut simuler l'historique.
     * Pour le moment, cette fonction ne sera pas utilisée par la vue.
     * @param int $sensorId L'ID de la ligne.
     * @param int $limit
     * @return array
     */
    public function getProximityHistoryById($sensorId, $limit = 20) {
        try {
            // Puisqu'on ne met à jour qu'une seule ligne, un historique n'a pas de sens.
            // On retourne la dernière valeur plusieurs fois pour l'exemple, mais l'idéal serait de ne pas appeler cette fonction.
            $stmt = $this->db->prepare("SELECT heure, valeur FROM public.capteurproximite WHERE id = ?");
            $stmt->execute([$sensorId]);
            $lastReading = $stmt->fetch();
            return $lastReading ? array_fill(0, $limit, $lastReading) : [];
        } catch (Exception $e) {
            error_log("Erreur dans getProximityHistoryById : " . $e->getMessage());
            return [];
        }
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

    // ==========================================================
    // == DÉBUT DES NOUVELLES MÉTHODES POUR LES GRAPHIQUES ==
    // ==========================================================

    /**
     * Récupère les N dernières mesures d'un type de capteur.
     * @param string $tableName Le nom de la table du capteur.
     * @param int $limit Le nombre de mesures à récupérer.
     * @return array Les données historiques.
     */
    public function getHistoricalReadings($tableName, $limit = 20) {
        try {
            $allowedTables = ['capteurgaz', 'capteurlum', 'capteurson', 'capteurtemp'];
            if (!in_array($tableName, $allowedTables)) {
                throw new Exception("Nom de table non autorisé : " . $tableName);
            }

            $stmt = $this->db->prepare("SELECT heure, valeur FROM public.{$tableName} ORDER BY id DESC LIMIT ?");
            $stmt->bindParam(1, $limit, PDO::PARAM_INT);
            $stmt->execute();
            // On inverse pour que le graphique soit dans l'ordre chronologique
            return array_reverse($stmt->fetchAll());
        } catch (Exception $e) {
            error_log("Erreur dans getHistoricalReadings pour $tableName : " . $e->getMessage());
            return [];
        }
    }

    /**
     * Récupère l'historique d'un capteur de proximité spécifique.
     * @param int $placeId L'ID de la place.
     * @param int $limit Le nombre de mesures.
     * @return array
     */
    public function getProximityHistoryForPlace($placeId, $limit = 20) {
        try {
            $stmt = $this->db->prepare("SELECT heure, valeur FROM public.capteurproximite WHERE place = ? ORDER BY id DESC LIMIT ?");
            $stmt->execute([$placeId, $limit]);
            return array_reverse($stmt->fetchAll());
        } catch (Exception $e) {
            error_log("Erreur dans getProximityHistoryForPlace : " . $e->getMessage());
            return [];
        }
    }

    // ========================================================
    // == FIN DES NOUVELLES MÉTHODES POUR LES GRAPHIQUES ==
    // ========================================================

    // --- AUTRES CAPTEURS ---

    /**
     * Récupère la dernière mesure d'un type de capteur donné.
     * @param string $tableName Le nom exact de la table du capteur (ex: 'capteurgaz')
     * @return array|null Les données de la dernière mesure ou null.
     */
    private function getLatestReading($tableName) {
        try {
            // Valide le nom de la table pour la sécurité
            $allowedTables = ['capteurgaz', 'capteurlum', 'capteurson', 'capteurtemp', 'capteurproximite']; // Ajout de proximité
            if (!in_array($tableName, $allowedTables)) {
                throw new Exception("Nom de table non autorisé : " . $tableName);
            }
            
            // Pour la proximité, on cible la place 1
            if ($tableName === 'capteurproximite') {
                 $stmt = $this->db->query("SELECT * FROM public.capteurproximite WHERE place = 1 ORDER BY id DESC LIMIT 1");
            } else {
                 $stmt = $this->db->query("SELECT * FROM public.{$tableName} ORDER BY id DESC LIMIT 1");
            }
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

    // Ajout d'une fonction pour la dernière lecture du capteur de proximité 1
    public function getLatestProximityReadingForPlace1() {
        return $this->getLatestReading('capteurproximite');
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