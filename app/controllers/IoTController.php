<?php
require_once ROOT_PATH . '/app/models/ParkingSensor.php';
require_once ROOT_PATH . '/app/models/Actuator.php';

class IoTController {

    private $parkingSensorModel;
    private $actuatorModel;
    
    public function __construct() {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $this->parkingSensorModel = new ParkingSensor();
        $this->actuatorModel = new Actuator();
    }
    
    /**
     * Affiche le dashboard IoT principal avec des données dynamiques.
     */
    public function dashboard() {
        $page_title = "IoT Dashboard - Vue d'ensemble";
        
        // --- Récupération des données dynamiques ---
        
        // 1. Statut des places de parking (depuis capteurproximite)
        $parking_status = $this->parkingSensorModel->getLatestParkingStatus();
        $spots_occupée = 0;
        foreach ($parking_status as $spot) {
            if ($spot['valeur'] === true) { // 't' en PostgreSQL est converti en true par PDO
                $spots_occupée++;
            }
        }
        $spots_total = count($parking_status);
        $spots_disponible = $spots_total - $spots_occupée;

        // 2. Statut des actionneurs (LEDs)
        $leds = $this->actuatorModel->getAllLEDs();
        $leds_actives = 0;
        foreach ($leds as $led) {
            if ($led['etat'] === true) {
                $leds_actives++;
            }
        }
        
        // 3. Données de l'écran OLED
        $oled_data = $this->actuatorModel->getOLEDData();

        // 4. Récupération des capteurs environnementaux
        $latest_temp = $this->parkingSensorModel->getLatestTempReading();
        $latest_gas = $this->parkingSensorModel->getLatestGasReading();
        $latest_light = $this->parkingSensorModel->getLatestLightReading();
        $latest_sound = $this->parkingSensorModel->getLatestSoundReading();
        
        // On prépare un tableau de données pour la vue
        $data = [
            'spots_disponible' => $spots_disponible,
            'spots_occupée' => $spots_occupée,
            'spots_total' => $spots_total,
            'leds_actives' => $leds_actives,
            'parking_status' => $parking_status,
            'total_actuators' => count($leds) + count($this->actuatorModel->getAllMotors()),
            'oled_data' => $oled_data,
            'temp_sensor' => $latest_temp,
            'gas_sensor' => $latest_gas,
            'light_sensor' => $latest_light,
            'sound_sensor' => $latest_sound
        ];

        // Charge la vue du dashboard IoT en lui passant les données
        require_once ROOT_PATH . '/app/views/iot-dashboard.php';
    }
    
    /**
     * Gère la mise à jour des données de l'OLED via une requête AJAX.
     */
    public function updateOled() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405); // Method Not Allowed
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
            exit;
        }

        // Récupération et validation simple des données
        $places_dispo = filter_input(INPUT_POST, 'places_dispo', FILTER_VALIDATE_INT);
        $bornes_dispo = filter_input(INPUT_POST, 'bornes_dispo', FILTER_VALIDATE_INT);
        $user = htmlspecialchars($_POST['user'] ?? '');
        $plaque = htmlspecialchars($_POST['plaque'] ?? '');

        if ($places_dispo === false || $bornes_dispo === false) {
            http_response_code(400); // Bad Request
            echo json_encode(['success' => false, 'message' => 'Les nombres de places et de bornes doivent être des entiers.']);
            exit;
        }

        if ($this->actuatorModel->updateOLED($places_dispo, $bornes_dispo, $user, $plaque)) {
            echo json_encode(['success' => true, 'message' => 'Affichage OLED mis à jour avec succès !']);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour de l\'affichage OLED.']);
        }
        exit;
    }

    public function updateLedState() {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405); // Method Not Allowed
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
            exit;
        }
        

        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $etat = filter_input(INPUT_POST, 'etat', FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        if ($id === false || $etat === null) {
            http_response_code(400); exit(json_encode(['success' => false, 'message' => 'Données invalides.']));
        }
        
        if ($this->actuatorModel->updateLEDState($id, $etat)) {
            echo json_encode(['success' => true, 'message' => 'État de la LED mis à jour.']);
        } else {
            http_response_code(500); echo json_encode(['success' => false, 'message' => 'Erreur serveur.']);
        }
        exit;
    }

    /**
     * Met à jour l'état d'un moteur via une requête AJAX.
     */
    public function updateMotorState() {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405); // Method Not Allowed
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
            exit;
        }
        
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        $etat = filter_input(INPUT_POST, 'etat', FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        $vitesse = filter_input(INPUT_POST, 'vitesse', FILTER_VALIDATE_INT);

        if ($id === false || $etat === null || $vitesse === false) {
            http_response_code(400); 
            echo json_encode(['success' => false, 'message' => 'Données invalides. L\'ID, l\'état et la vitesse sont requis.']);
            exit;
        }
        
        if ($this->actuatorModel->updateMotor($id, $etat, $vitesse)) {
            echo json_encode(['success' => true, 'message' => 'État du moteur mis à jour avec succès.']);
        } else {
            http_response_code(500); 
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour du moteur.']);
        }
        exit;
    }


    /**
     * Affiche la page de gestion des capteurs.
     */
    public function capteurs() {
        $page_title = "Gestion des Capteurs IoT";
        
        $data = [
            'parking_sensors' => $this->parkingSensorModel->getLatestParkingStatus(),
            'temp_sensor'     => $this->parkingSensorModel->getLatestTempReading(),
            'gas_sensor'      => $this->parkingSensorModel->getLatestGasReading(),
            'light_sensor'    => $this->parkingSensorModel->getLatestLightReading(),
            'sound_sensor'    => $this->parkingSensorModel->getLatestSoundReading(),
        ];

        require_once ROOT_PATH . '/app/views/admin/iot-capteurs.php';
    }

    /**
     * Affiche la page de gestion des actionneurs.
     */
     public function actionneurs() {
        $page_title = "Gestion des Actionneurs IoT";
        
        // Récupérer toutes les données nécessaires
        $data = [
            'leds' => $this->actuatorModel->getAllLEDs(),
            'motors' => $this->actuatorModel->getAllMotors(),
            // Les données pour le buzzer et l'afficheur 7 segments seront gérées côté client
            // car elles ne sont pas dans la BDD.
        ];

        // Charger la vue en lui passant les données
        require_once ROOT_PATH . '/app/views/admin/iot-actionneurs.php';
    }
}
?>