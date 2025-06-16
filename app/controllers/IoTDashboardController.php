<?php
require_once ROOT_PATH . '/app/models/ParkingSensor.php';
require_once ROOT_PATH . '/app/models/Actuator.php';

class IoTDashboardController {
    
    public function index() {
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        
        $page_title = "Dashboard IoT - Parking Intelligent";
        
        // Initialiser les modèles
        $parkingSensor = new ParkingSensor();
        $actuator = new Actuator();
        
        // Messages d'erreur et de succès
        $errorMessage = '';
        $successMessage = '';
        
        // Gestion des actions CRUD
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';
            $table = $_POST['table'] ?? '';
            
            try {
                switch ($action) {
                    case 'create':
                        if ($table === 'capteurProximite') {
                            $place = $_POST['place'] ?? 1;
                            $valeur = isset($_POST['valeur']) ? ($_POST['valeur'] === 'true' ? true : false) : false;
                            if ($parkingSensor->addMeasurement($place, $valeur)) {
                                $successMessage = "Nouvelle mesure ajoutée avec succès !";
                            } else {
                                $errorMessage = "Erreur lors de l'ajout de la mesure.";
                            }
                        } elseif ($table === 'LED') {
                            $etat = isset($_POST['etat']) ? ($_POST['etat'] === 'true' ? true : false) : false;
                            if ($actuator->addLED($etat)) {
                                $successMessage = "LED ajoutée avec succès !";
                            } else {
                                $errorMessage = "Erreur lors de l'ajout de la LED.";
                            }
                        }
                        break;
                        
                    case 'update':
                        $id = $_POST['id'];
                        if ($table === 'capteurProximite') {
                            $place = $_POST['place'] ?? 1;
                            $valeur = isset($_POST['valeur']) ? ($_POST['valeur'] === 'true' ? true : false) : false;
                            if ($parkingSensor->updateParkingSpace($id, $place, $valeur)) {
                                $successMessage = "Place de parking mise à jour !";
                            } else {
                                $errorMessage = "Erreur lors de la mise à jour.";
                            }
                        } elseif ($table === 'LED') {
                            $etat = isset($_POST['etat']) ? ($_POST['etat'] === 'true' ? true : false) : false;
                            if ($actuator->updateLED($id, $etat)) {
                                $successMessage = "LED mise à jour !";
                            } else {
                                $errorMessage = "Erreur lors de la mise à jour de la LED.";
                            }
                        }
                        break;
                        
                    case 'delete':
                        $id = $_POST['id'];
                        if ($table === 'capteurProximite') {
                            if ($parkingSensor->deleteMeasurement($id)) {
                                $successMessage = "Mesure supprimée !";
                            } else {
                                $errorMessage = "Erreur lors de la suppression.";
                            }
                        }
                        break;
                }
            } catch (Exception $e) {
                $errorMessage = "Erreur lors de l'opération : " . $e->getMessage();
            }
            
            // Redirection pour éviter la resoumission
            if (empty($errorMessage)) {
                header("Location: " . BASE_URL . "/iot-dashboard?success=" . urlencode($successMessage));
                exit;
            }
        }
        
        // Récupération des messages de succès depuis l'URL
        if (isset($_GET['success'])) {
            $successMessage = $_GET['success'];
        }
        
        // Récupération des données
        $parkingSpaces = $parkingSensor->getAllParkingSpaces();
        $latestParkingStatus = $parkingSensor->getLatestParkingStatus();
        $leds = $actuator->getAllLEDs();
        $oledData = $actuator->getOLEDData();
        
        // Calcul des statistiques
        $totalSpaces = count($latestParkingStatus);
        $occupiedSpaces = count(array_filter($latestParkingStatus, function($space) {
            return $space['valeur'] == true; // true = occupé
        }));
        $freeSpaces = $totalSpaces - $occupiedSpaces;
        
        require_once ROOT_PATH . '/app/views/iot-dashboard.php';
    }
}