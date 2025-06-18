<?php
// On inclut les modèles nécessaires au début.
require_once ROOT_PATH . '/app/models/ParkingSensor.php';
require_once ROOT_PATH . '/app/models/Actuator.php';

class IoTController {

    private $parkingSensorModel;
    private $actuatorModel;
    
    public function __construct() {
        // On s'assure que l'utilisateur est un admin pour toutes les méthodes de ce contrôleur.
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || !$_SESSION['is_admin']) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $this->parkingSensorModel = new ParkingSensor();
        $this->actuatorModel = new Actuator();
    }
    
    /**
     * Affiche le dashboard IoT principal.
     */
    public function dashboard() {
        $page_title = "IoT Dashboard - Vue d'ensemble";
        
        // Vous pouvez passer des données ici si nécessaire, par exemple :
        $total_sensors = count($this->parkingSensorModel->getAllParkingSpaces());
        $total_actuators = count($this->actuatorModel->getAllLEDs());

        // Charge la vue du dashboard IoT
        require_once ROOT_PATH . '/app/views/iot-dashboard.php';
    }

    /**
     * Affiche la page de gestion des capteurs.
     */
    public function capteurs() {
        $page_title = "Gestion des Capteurs IoT";
        
        // Récupérer les données des capteurs pour les afficher dans la vue
        $parkingSpaces = $this->parkingSensorModel->getLatestParkingStatus();
        // Ajoutez ici d'autres appels de modèles pour les autres types de capteurs si nécessaire.

        // Charge la vue des capteurs
        require_once ROOT_PATH . '/app/views/iot-capteurs.php';
    }

    /**
     * Affiche la page de gestion des actionneurs.
     */
    public function actionneurs() {
        $page_title = "Gestion des Actionneurs IoT";
        
        // Récupérer les données des actionneurs pour les afficher dans la vue
        $leds = $this->actuatorModel->getAllLEDs();
        $oledData = $this->actuatorModel->getOLEDData();
        // Ajoutez ici d'autres appels de modèles pour les autres actionneurs si nécessaire.

        // Charge la vue des actionneurs
        require_once ROOT_PATH . '/app/views/iot-actionneurs.php';
    }
}
?>