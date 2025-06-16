<?php
require_once ROOT_PATH . '/app/models/User.php';
require_once ROOT_PATH . '/app/models/ParkingSpot.php';
require_once ROOT_PATH . '/app/models/Reservation.php';

class AdminController {
    private $userModel;
    private $parkingSpotModel;
    private $reservationModel;
    
    public function __construct() {
        $this->userModel = new User();
        $this->parkingSpotModel = new ParkingSpot();
        $this->reservationModel = new Reservation();
    }
    
    public function index() {
        // Vérifier si l'utilisateur est admin
        if (!$this->isAdmin()) {
            // REDIRECTION CORRIGÉE
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        
        $users = $this->userModel->getAllUsers();
        $spots = $this->parkingSpotModel->getAllSpots();
        $reservations = $this->reservationModel->getAllReservations();
        
        $page_title = "Administration - Parking Intelligent";
        require_once ROOT_PATH . '/app/views/admin/dashboard.php';
    }
    
    public function manageUsers() {
        if (!$this->isAdmin()) {
            // REDIRECTION CORRIGÉE
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        
        $users = $this->userModel->getAllUsers();
        $page_title = "Gestion des utilisateurs";
        require_once ROOT_PATH . '/app/views/admin/users.php';
    }
    
    public function manageParking() {
        if (!$this->isAdmin()) {
            // REDIRECTION CORRIGÉE
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        
        $spots = $this->parkingSpotModel->getAllSpots();
        $page_title = "Gestion du parking";
        require_once ROOT_PATH . '/app/views/admin/parking.php';
    }
    
    public function updateUserStatus() {
        if (!$this->isAdmin() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            // REDIRECTION CORRIGÉE
            header('Location: ' . BASE_URL . '/admin');
            exit;
        }
        
        $user_id = $_POST['user_id'];
        $is_admin = isset($_POST['is_admin']) ? true : false;
        
        $this->userModel->updateUserAdminStatus($user_id, $is_admin);
        // REDIRECTION CORRIGÉE
        header('Location: ' . BASE_URL . '/admin/users');
    }
    
    public function deleteUser() {
        if (!$this->isAdmin() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            // REDIRECTION CORRIGÉE
            header('Location: ' . BASE_URL . '/admin');
            exit;
        }
        
        $user_id = $_POST['user_id'];
        $this->userModel->deleteUser($user_id);
        // REDIRECTION CORRIGÉE
        header('Location: ' . BASE_URL . '/admin/users');
    }
    
    public function createUser() {
        if (!$this->isAdmin() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            // REDIRECTION CORRIGÉE
            header('Location: ' . BASE_URL . '/admin');
            exit;
        }
        
        $email = $_POST['email'];
        $password = $_POST['password'];
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $is_admin = isset($_POST['is_admin']) ? true : false;
        
        $this->userModel->createUser($email, $password, $nom, $prenom, $is_admin);
        // REDIRECTION CORRIGÉE
        header('Location: ' . BASE_URL . '/admin/users');
    }
    
    public function updateParkingSpot() {
        if (!$this->isAdmin() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            // REDIRECTION CORRIGÉE
            header('Location: ' . BASE_URL . '/admin');
            exit;
        }
        
        $spot_id = $_POST['spot_id'];
        $data = [
            'status' => $_POST['status'],
            'price_per_hour' => $_POST['price_per_hour'],
            'has_charging_station' => isset($_POST['has_charging_station']) ? true : false
        ];
        
        $this->parkingSpotModel->updateSpot($spot_id, $data);
        // REDIRECTION CORRIGÉE
        header('Location: ' . BASE_URL . '/admin/parking');
    }
    
    private function isAdmin() {
        return isset($_SESSION['user_id']) && isset($_SESSION['is_admin']) && $_SESSION['is_admin'];
    }
}