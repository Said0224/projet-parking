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
    
    public function dashboard() {
        $page_title = "IoT Dashboard - Vue d'ensemble";
        $parking_status = $this->parkingSensorModel->getLatestParkingStatus();
        $spots_occupée = 0;
        foreach ($parking_status as $spot) {
            if ($spot['valeur'] === true) {
                $spots_occupée++;
            }
        }
        $spots_total = count($parking_status);
        $spots_disponible = $spots_total - $spots_occupée;

        $leds = $this->actuatorModel->getAllLEDs();
        $leds_actives = 0;
        foreach ($leds as $led) {
            if ($led['etat'] === true) {
                $leds_actives++;
            }
        }
        
        $oled_data = $this->actuatorModel->getOLEDData();
        $latest_temp = $this->parkingSensorModel->getLatestTempReading();
        $latest_gas = $this->parkingSensorModel->getLatestGasReading();
        $latest_light = $this->parkingSensorModel->getLatestLightReading();
        $latest_sound = $this->parkingSensorModel->getLatestSoundReading();
        
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

        require_once ROOT_PATH . '/app/views/iot-dashboard.php';
    }
    
    public function updateOled() {
        header('Content-Type: application/json');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
            exit;
        }

        $places_dispo = filter_input(INPUT_POST, 'places_dispo', FILTER_VALIDATE_INT);
        $bornes_dispo = filter_input(INPUT_POST, 'bornes_dispo', FILTER_VALIDATE_INT);
        $user = htmlspecialchars($_POST['user'] ?? '');
        $plaque = htmlspecialchars($_POST['plaque'] ?? '');

        if ($places_dispo === false || $bornes_dispo === false) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Les nombres de places et de bornes doivent être des entiers.']);
            exit;
        }

        if ($this->actuatorModel->updateOLED($places_dispo, $bornes_dispo, $user, $plaque)) {
            echo json_encode(['success' => true, 'message' => 'Affichage OLED mis à jour !']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour de l\'OLED.']);
        }
        exit;
    }

     /**
     * Met à jour les détails d'une LED (état, couleur, intensité) via AJAX.
     * C'est une action manuelle, donc on enregistre la commande.
     */
    public function updateLedDetails() {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit(json_encode(['success' => false, 'message' => 'Méthode non autorisée.']));
        }
    
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        // CORRECTION : On vérifie si $etat est un entier (0 ou 1), même si c'est 0.
        $etat = filter_input(INPUT_POST, 'etat', FILTER_VALIDATE_INT, ['options' => ['min_range' => 0, 'max_range' => 1]]);
        $couleur = $_POST['couleur'] ?? '#FFFFFF';
        $intensite = filter_input(INPUT_POST, 'intensite', FILTER_VALIDATE_INT);
    
        // CORRECTION : La condition est plus robuste.
        if ($id === false || $etat === false || $etat === null || $intensite === false || $intensite === null || !preg_match('/^#[a-fA-F0-9]{6}$/', $couleur)) {
            http_response_code(400);
            exit(json_encode(['success' => false, 'message' => 'Données invalides.']));
        }
    
        // On spécifie que c'est une commande manuelle
        $command_source = 'manual_override'; 

        if ($this->actuatorModel->updateLedDetails($id, (bool)$etat, $couleur, $intensite, $command_source)) {
            echo json_encode(['success' => true, 'message' => 'LED mise à jour avec succès.']);
        } else {
            http_response_code(500);
            // CORRECTION : On utilise un message plus précis
            echo json_encode(['success' => false, 'message' => 'Erreur de mise à jour de la LED en base de données.']);
        }
        exit;
    }

    public function updateMotorState() {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
            exit;
        }
        
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        // CORRECTION : On vérifie si $etat est un entier (0 ou 1), même si c'est 0.
        $etat = filter_input(INPUT_POST, 'etat', FILTER_VALIDATE_INT, ['options' => ['min_range' => 0, 'max_range' => 1]]);
        $vitesse = filter_input(INPUT_POST, 'vitesse', FILTER_VALIDATE_INT);

        // CORRECTION : La condition est plus robuste.
        if ($id === false || $etat === false || $etat === null || $vitesse === false || $vitesse === null) {
            http_response_code(400); 
            echo json_encode(['success' => false, 'message' => 'Données invalides.']);
            exit;
        }
        
        if ($this->actuatorModel->updateMotor($id, (bool)$etat, $vitesse)) {
            echo json_encode(['success' => true, 'message' => 'État du moteur mis à jour.']);
        } else {
            http_response_code(500); 
            // CORRECTION : Message plus précis
            echo json_encode(['success' => false, 'message' => 'Erreur de mise à jour du moteur en base de données.']);
        }
        exit;
    }

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

    public function actionneurs() {
        $page_title = "Gestion des Actionneurs IoT";
        $data = [
            'leds' => $this->actuatorModel->getAllLEDs(),
            'motors' => $this->actuatorModel->getAllMotors(),
        ];
        require_once ROOT_PATH . '/app/views/admin/iot-actionneurs.php';
    }
}
?>