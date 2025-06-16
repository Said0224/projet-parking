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
            header('Location: /login');
            exit;
        }
        
        $availableSpots = $this->parkingSpotModel->getAvailableSpots();
        $userReservations = $this->reservationModel->getUserReservations($_SESSION['user_id']);
        
        $page_title = "Mon Dashboard";
        require_once ROOT_PATH . '/app/views/user/dashboard.php';
    }
    
    public function parking() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        
        $spots = $this->parkingSpotModel->getAllSpots();
        $page_title = "Places de parking";
        require_once ROOT_PATH . '/app/views/user/parking.php';
    }
    
    public function reserve() {
        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /user/parking');
            exit;
        }
        
        $spot_id = $_POST['spot_id'];
        $start_time = $_POST['start_time'];
        $end_time = $_POST['end_time'];
        $user_id = $_SESSION['user_id'];
        
        if ($this->reservationModel->createReservation($user_id, $spot_id, $start_time, $end_time)) {
            $_SESSION['success_message'] = "Réservation effectuée avec succès !";
        } else {
            $_SESSION['error_message'] = "Erreur lors de la réservation. La place n'est peut-être plus disponible.";
        }
        
        header('Location: /user/dashboard');
    }
    
    public function cancelReservation() {
        if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /user/dashboard');
            exit;
        }
        
        $reservation_id = $_POST['reservation_id'];
        $user_id = $_SESSION['user_id'];
        
        if ($this->reservationModel->cancelReservation($reservation_id, $user_id)) {
            $_SESSION['success_message'] = "Réservation annulée avec succès !";
        } else {
            $_SESSION['error_message'] = "Erreur lors de l'annulation de la réservation.";
        }
        
        header('Location: /user/dashboard');
    }
}
?>