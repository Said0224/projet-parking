<?php
require_once ROOT_PATH . '/app/models/ParkingSensor.php';
require_once ROOT_PATH . '/app/models/Actuator.php';

class IoTController {
    private $parkingSensorModel;
    private $actuatorModel;
    
    public function __construct() {
        // Vérifier si l'utilisateur est connecté et est admin
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        
        $this->parkingSensorModel = new ParkingSensor();
        $this->actuatorModel = new Actuator();
    }
    
    /**
     * Page principale du dashboard IoT
     */
    public function index() {
        $page_title = "Dashboard IoT - Parking Intelligent";
        
        // Récupérer un aperçu des données
        $data = [
            'temp_sensor' => $this->parkingSensorModel->getLatestTempReading(),
            'gas_sensor' => $this->parkingSensorModel->getLatestGasReading(),
            'light_sensor' => $this->parkingSensorModel->getLatestLightReading(),
            'sound_sensor' => $this->parkingSensorModel->getLatestSoundReading(),
            'parking_sensors' => $this->parkingSensorModel->getLatestParkingStatus(),
            'leds' => $this->actuatorModel->getAllLEDs(),
            'motors' => $this->actuatorModel->getAllMotors(),
            'oled_data' => $this->actuatorModel->getOLEDData()
        ];
        
        require_once ROOT_PATH . '/app/views/iot-dashboard.php';
    }
    
    /**
     * Page de gestion des capteurs
     */
    public function capteurs() {
        $page_title = "Gestion des Capteurs IoT";
        
        // Récupérer les données des capteurs
        $data = [
            'temp_sensor' => $this->parkingSensorModel->getLatestTempReading(),
            'gas_sensor' => $this->parkingSensorModel->getLatestGasReading(),
            'light_sensor' => $this->parkingSensorModel->getLatestLightReading(),
            'sound_sensor' => $this->parkingSensorModel->getLatestSoundReading(),
            // LIMITATION À 3 PLACES COMME DEMANDÉ
            'parking_sensors' => array_slice($this->parkingSensorModel->getLatestParkingStatus(), 0, 3)
        ];
        
        require_once ROOT_PATH . '/app/views/iot-capteurs.php';
    }
    
    /**
     * Page de gestion des actionneurs
     */
    public function actionneurs() {
        $page_title = "Gestion des Actionneurs IoT";
        
        // Récupérer les données des actionneurs
        $data = [
            'leds' => $this->actuatorModel->getAllLEDs(),
            'motors' => $this->actuatorModel->getAllMotors(),
            'oled_data' => $this->actuatorModel->getOLEDData()
        ];
        
        require_once ROOT_PATH . '/app/views/iot-actionneurs.php';
    }
    
    /**
     * Mettre à jour l'état d'une LED
     */
    public function updateLedState() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
            exit;
        }
        
        $id = $_POST['id'] ?? null;
        $etat = $_POST['etat'] ?? null;
        
        if ($id === null || $etat === null) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Paramètres manquants.']);
            exit;
        }
        
        try {
            $success = $this->actuatorModel->updateLEDState($id, (bool)$etat);
            if ($success) {
                echo json_encode(['success' => true, 'message' => 'État de la LED mis à jour avec succès.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour de la LED.']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erreur interne du serveur.']);
        }
        exit;
    }
    
    /**
     * Mettre à jour l'état d'un moteur
     */
    public function updateMotorState() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
            exit;
        }
        
        $id = $_POST['id'] ?? null;
        $etat = $_POST['etat'] ?? null;
        $vitesse = $_POST['vitesse'] ?? null;
        
        if ($id === null || $etat === null || $vitesse === null) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Paramètres manquants.']);
            exit;
        }
        
        try {
            $success = $this->actuatorModel->updateMotor($id, (bool)$etat, (int)$vitesse);
            if ($success) {
                echo json_encode(['success' => true, 'message' => 'État du moteur mis à jour avec succès.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour du moteur.']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erreur interne du serveur.']);
        }
        exit;
    }
    
    /**
     * Mettre à jour l'affichage OLED
     */
    public function updateOLED() {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Méthode non autorisée.']);
            exit;
        }
        
        $places_dispo = $_POST['places_dispo'] ?? null;
        $bornes_dispo = $_POST['bornes_dispo'] ?? null;
        $user = $_POST['user'] ?? '';
        $plaque = $_POST['plaque'] ?? '';
        
        if ($places_dispo === null || $bornes_dispo === null) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Paramètres manquants.']);
            exit;
        }
        
        try {
            $success = $this->actuatorModel->updateOLED((int)$places_dispo, (int)$bornes_dispo, $user, $plaque);
            if ($success) {
                echo json_encode(['success' => true, 'message' => 'Affichage OLED mis à jour avec succès.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour de l\'affichage OLED.']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erreur interne du serveur.']);
        }
        exit;
    }
    
    /**
     * API pour récupérer les données des capteurs en temps réel
     */
    public function getSensorsData() {
        header('Content-Type: application/json');
        
        try {
            $data = [
                'temp_sensor' => $this->parkingSensorModel->getLatestTempReading(),
                'gas_sensor' => $this->parkingSensorModel->getLatestGasReading(),
                'light_sensor' => $this->parkingSensorModel->getLatestLightReading(),
                'sound_sensor' => $this->parkingSensorModel->getLatestSoundReading(),
                'parking_sensors' => array_slice($this->parkingSensorModel->getLatestParkingStatus(), 0, 3),
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            echo json_encode(['success' => true, 'data' => $data]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la récupération des données.']);
        }
        exit;
    }
    
    /**
     * API pour récupérer les données des actionneurs en temps réel
     */
    public function getActuatorsData() {
        header('Content-Type: application/json');
        
        try {
            $data = [
                'leds' => $this->actuatorModel->getAllLEDs(),
                'motors' => $this->actuatorModel->getAllMotors(),
                'oled_data' => $this->actuatorModel->getOLEDData(),
                'timestamp' => date('Y-m-d H:i:s')
            ];
            
            echo json_encode(['success' => true, 'data' => $data]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la récupération des données.']);
        }
        exit;
    }
}
?>
