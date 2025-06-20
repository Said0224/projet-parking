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
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        
        // On récupère toutes les places avec leurs détails pour la vue 3D
        $allSpots = $this->parkingSpotModel->findAllWithDetails();
        
        // On organise les places par étage pour les passer à la vue
        $spotsByEtage = [];
        if (!empty($allSpots)) {
            foreach ($allSpots as $spot) {
                $spotsByEtage[$spot['etage']][] = $spot;
            }
        }
        
        // On récupère les réservations de l'utilisateur pour le tableau du bas
        $userReservations = $this->reservationModel->getUserReservations($_SESSION['user_id']);
        
        $page_title = "Mon Dashboard";
        // On passe les variables à la vue
        require_once ROOT_PATH . '/app/views/user/dashboard.php';
    }
    
    public function parking() {
        if (!isset($_SESSION['user_id'])) {
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
            // ---- DEBUT AJOUT ----
            require_once ROOT_PATH . '/app/models/Notification.php';
            $notificationModel = new Notification();
            $spotDetails = $this->parkingSpotModel->getSpotById($spot_id);
            $message = "Votre réservation pour la place " . $spotDetails['spot_number'] . " a été confirmée.";
            $notificationModel->createNotification($user_id, 'reservation_success', $message);
            // ---- FIN AJOUT ----

            echo json_encode(['success' => true, 'message' => 'Réservation effectuée avec succès !']);
        } else {
            http_response_code(409); // 409 Conflict
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
            // ---- DEBUT AJOUT ----
            require_once ROOT_PATH . '/app/models/Notification.php';
            $notificationModel = new Notification();
            $message = "Votre réservation (ID: {$reservation_id}) a bien été annulée.";
            $notificationModel->createNotification($user_id, 'reservation_cancelled', $message);
            // ---- FIN AJOUT ----

            echo json_encode(['success' => true, 'message' => 'Réservation annulée avec succès !']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => "Erreur lors de l'annulation de la réservation."]);
        }
        exit;
    }

    public function getAllSpotsStatus() {
        header('Content-Type: application/json');

        if (!isset($_SESSION['user_id'])) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Accès non autorisé.']);
            exit;
        }
        
        try {
            $spots = $this->parkingSpotModel->findAllWithDetails();
            echo json_encode(['success' => true, 'spots' => $spots]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erreur serveur lors de la récupération des places.']);
        }
        exit;
    }
}
?>