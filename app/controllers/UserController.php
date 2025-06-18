<?php
require_once ROOT_PATH . '/app/models/ParkingSpot.php';
require_once ROOT_PATH . '/app/models/Reservation.php';

class UserController {
    private $parkingSpotModel;
    private $reservationModel;
    
    public function __construct() {
        $this->parkingSpotModel = new ParkingSpot();
        $this->reservationModel = new Reservation();
    }
    
    public function dashboard() {
        if (!isset($_SESSION['user_id'])) {

            // REDIRECTION CORRIGÉE
            header('Location: ' . BASE_URL . '/login');

            exit;
        }
        
        $disponibleSpots = $this->parkingSpotModel->getdisponibleSpots();
        $userReservations = $this->reservationModel->getUserReservations($_SESSION['user_id']);
        
        $page_title = "Mon Dashboard";
        require_once ROOT_PATH . '/app/views/user/dashboard.php';
    }
    
    public function parking() {
        if (!isset($_SESSION['user_id'])) {

            // REDIRECTION CORRIGÉE
            header('Location: ' . BASE_URL . '/login');

            exit;
        }
        
        $spots = $this->parkingSpotModel->getAllSpots();
        $page_title = "Places de parking";
        require_once ROOT_PATH . '/app/views/user/parking.php';
    }
    
    public function reserve() {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Accès non autorisé.']);
            exit;
        }

        $spot_id = $_POST['spot_id'];
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];
        $user_id = $_SESSION['user_id'];
        
        // Validation simple
        if (empty($spot_id) || empty($start_time) || empty($end_time)) {
             http_response_code(400);
             echo json_encode(['success' => false, 'message' => 'Tous les champs sont requis.']);
             exit;
        }

        if ($this->reservationModel->createReservation($user_id, $spot_id, $start_time, $end_time)) {
            echo json_encode(['success' => true, 'message' => 'Réservation effectuée avec succès !']);
        } else {
            http_response_code(409); // 409 Conflict: la ressource ne peut être créée (déjà prise)
            echo json_encode(['success' => false, 'message' => "Erreur lors de la réservation. La place n'est peut-être plus disponible pour ce créneau."]);
        }
        exit;
    }
    
    public function cancelReservation() {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Accès non autorisé.']);
            exit;
        }
        
        $reservation_id = $_POST['reservation_id'];
        $user_id = $_SESSION['user_id'];
        
        if ($this->reservationModel->cancelReservation($reservation_id, $user_id)) {
            echo json_encode(['success' => true, 'message' => 'Réservation annulée avec succès !']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => "Erreur lors de l'annulation de la réservation."]);
        }
        exit;
    }

    public function getAllSpotsStatus() {
        header('Content-Type: application/json');

        // On peut ajouter une vérification de session si on veut que l'API soit privée
        if (!isset($_SESSION['user_id'])) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Accès non autorisé.']);
            exit;
        }
        
        try {
            $spots = $this->parkingSpotModel->getAllSpots();
            echo json_encode(['success' => true, 'spots' => $spots]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erreur serveur lors de la récupération des places.']);
        }
        exit;
    }
}

?>

