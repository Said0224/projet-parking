<?php
require_once ROOT_PATH . '/config/database.php';

class ParkingSpace {
    private $db;

    public function __construct() {
        // On se connecte à la base de données des capteurs (PostgreSQL)
        $this->db = Database::getInstance('sensors');
    }

    /**
     * Récupère toutes les places avec un statut et des détails simulés (étage, numéro).
     */
    public function getAllWithDetails() {
        try {
            // Récupère le dernier état connu pour chaque place avec la syntaxe PostgreSQL
            $stmt = $this->db->query("
                SELECT DISTINCT ON (place) id, place, valeur
                FROM capteurProximite
                ORDER BY place, date DESC, heure DESC
            ");
            $sensor_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $places = [];
            foreach ($sensor_data as $data) {
                $etage = floor(($data['place'] - 1) / 20) + 1;
                $numero = (($etage - 1) * 100) + (($data['place'] - 1) % 20) + 1;

                $places[] = [
                    'id' => $data['id'],
                    'numero' => $numero,
                    'etage' => $etage,
                    'statut' => ($data['valeur'] === true || $data['valeur'] === 't') ? 'occupee' : 'libre',
                    'id_user' => null,
                    'reservation_id' => null
                ];
            }
            return $places;
        } catch (PDOException $e) {
            // Log l'erreur pour le débogage
            error_log("Erreur dans ParkingSpace::getAllWithDetails : " . $e->getMessage());
            // Retourne un tableau vide en cas d'erreur pour que la page ne plante pas
            return [];
        }
    }

    /**
     * Récupère les détails d'une place spécifique par son ID de mesure.
     */
    public function getDetailsById($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM capteurProximite WHERE id = ?");
            $stmt->execute([$id]);
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$data) {
                return null;
            }
            
            $etage = floor(($data['place'] - 1) / 20) + 1;
            $numero = (($etage - 1) * 100) + (($data['place'] - 1) % 20) + 1;

            return [
                'id' => $data['id'],
                'numero' => $numero,
                'etage' => $etage,
                'statut' => ($data['valeur'] === true || $data['valeur'] === 't') ? 'occupee' : 'libre',
                'derniere_maj' => $data['date'] . ' ' . $data['heure']
            ];

        } catch (PDOException $e) {
            error_log("Erreur dans ParkingSpace::getDetailsById : " . $e->getMessage());
            return null;
        }
    }
}