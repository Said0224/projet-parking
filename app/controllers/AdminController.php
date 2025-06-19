<?php
// Fichier : app/controllers/AdminController.php

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
        if (!$this->isAdmin()) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        
        $users = $this->userModel->getAllUsers();
        $spots = $this->parkingSpotModel->getAllSpots();

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $reservations_per_page = 5;

        $filters = [];
        if (!empty($_GET['filter_date'])) {
            $filters['date'] = $_GET['filter_date'];
        }
        if (!empty($_GET['filter_spot_id'])) {
            $filters['spot_id'] = $_GET['filter_spot_id'];
        }
        
        $total_reservations = $this->reservationModel->getTotalReservationsCount($filters);
        $total_pages = ceil($total_reservations / $reservations_per_page);

        $reservations = $this->reservationModel->getPaginatedReservations($page, $reservations_per_page, $filters);
        
        $page_title = "Administration - Parking Intelligent";
        require_once ROOT_PATH . '/app/views/admin/dashboard.php';
    }

    public function getReservationsAjax() {
        if (!$this->isAdmin()) {
            http_response_code(403);
            echo "Accès refusé.";
            exit;
        }
    
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $reservations_per_page = 5;
    
        $filters = [];
        if (!empty($_GET['filter_date'])) {
            $filters['date'] = $_GET['filter_date'];
        }
        if (!empty($_GET['filter_spot_id'])) {
            $filters['spot_id'] = $_GET['filter_spot_id'];
        }
    
        $total_reservations = $this->reservationModel->getTotalReservationsCount($filters);
        $total_pages = ceil($total_reservations / $reservations_per_page);
        $reservations = $this->reservationModel->getPaginatedReservations($page, $reservations_per_page, $filters);
    
        require ROOT_PATH . '/app/views/partials/reservations_table.php';
        exit;
    }

     public function manageUsers() {
        if (!$this->isAdmin()) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $users_per_page = 8; // Afficher 8 utilisateurs par page

        // Récupérer les filtres depuis l'URL (pour le chargement initial)
        $filters = [];
        if (isset($_GET['filter_role']) && $_GET['filter_role'] !== '') {
            $filters['is_admin'] = $_GET['filter_role'];
        }

        // Utiliser les nouvelles méthodes du modèle
        $total_users = $this->userModel->getTotalUsersCount($filters);
        $total_pages = ceil($total_users / $users_per_page);
        $users = $this->userModel->getPaginatedUsers($page, $users_per_page, $filters);
        
        $page_title = "Gestion des utilisateurs";
        require_once ROOT_PATH . '/app/views/admin/users.php';
    }

    // ===== NOUVELLE MÉTHODE `getUsersAjax` CI-DESSOUS =====
    public function getUsersAjax() {
        if (!$this->isAdmin()) {
            http_response_code(403);
            echo "Accès refusé.";
            exit;
        }

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $users_per_page = 8;

        $filters = [];
        if (isset($_GET['filter_role']) && $_GET['filter_role'] !== '') {
            $filters['is_admin'] = $_GET['filter_role'];
        }
        
        $total_users = $this->userModel->getTotalUsersCount($filters);
        $total_pages = ceil($total_users / $users_per_page);
        $users = $this->userModel->getPaginatedUsers($page, $users_per_page, $filters);

        // Charger la vue partielle qui contient uniquement la table et la pagination
        require ROOT_PATH . '/app/views/admin/partials/users_table.php';
        exit;
    }
    
    // ===== MÉTHODE MODIFIÉE CI-DESSOUS =====
    public function manageParking() {
        if (!$this->isAdmin()) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $spots_per_page = 6; // Afficher 6 places par page

        // On construit le tableau des filtres à partir des potentiels paramètres GET
        $filters = [];
        if (!empty($_GET['filter_status'])) {
            $filters['status'] = $_GET['filter_status'];
        }
        if (isset($_GET['filter_charging']) && $_GET['filter_charging'] !== '') {
            $filters['has_charging_station'] = $_GET['filter_charging'];
        }

        // On passe les filtres aux méthodes du modèle
        $total_spots = $this->parkingSpotModel->getTotalSpotsCount($filters);
        $total_pages = ceil($total_spots / $spots_per_page);

        $spots = $this->parkingSpotModel->getPaginatedSpots($page, $spots_per_page, $filters);
        
        $page_title = "Gestion du parking";
        require_once ROOT_PATH . '/app/views/admin/parking.php';
    }

    // ===== NOUVELLE MÉTHODE CI-DESSOUS =====
    public function getParkingSpotsAjax() {
        if (!$this->isAdmin()) {
            http_response_code(403); // Forbidden
            echo "Accès refusé.";
            exit;
        }

        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $spots_per_page = 6;

        $filters = [];
        if (!empty($_GET['filter_status'])) {
            $filters['status'] = $_GET['filter_status'];
        }
        if (isset($_GET['filter_charging']) && $_GET['filter_charging'] !== '') {
            $filters['has_charging_station'] = $_GET['filter_charging'];
        }

        $total_spots = $this->parkingSpotModel->getTotalSpotsCount($filters);
        $total_pages = ceil($total_spots / $spots_per_page);
        $spots = $this->parkingSpotModel->getPaginatedSpots($page, $spots_per_page, $filters);

        // On charge la vue partielle qui contient uniquement la grille et la pagination
        require ROOT_PATH . '/app/views/admin/partials/parking_grid.php';
        exit;
    }

    public function updateUserStatus() {
        header('Content-Type: application/json');
        if (!$this->isAdmin() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Accès non autorisé.']);
            exit;
        }
        
        $user_id = $_POST['user_id'];
        $is_admin = isset($_POST['is_admin']) ? 1 : 0; // Utiliser 1 ou 0 pour le booléen
        
        if ($this->userModel->updateUserAdminStatus($user_id, $is_admin)) {
            echo json_encode(['success' => true, 'message' => 'Rôle de l\'utilisateur mis à jour.']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour du rôle.']);
        }
        exit;
    }
    
    // ===== MÉTHODE MODIFIÉE POUR AJAX =====
    public function deleteUser() {
        header('Content-Type: application/json');
        if (!$this->isAdmin() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Accès non autorisé.']);
            exit;
        }
        
        $user_id = $_POST['user_id'];
        if ($this->userModel->deleteUser($user_id)) {
            echo json_encode(['success' => true, 'message' => 'Utilisateur supprimé avec succès.']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression de l\'utilisateur.']);
        }
        exit;
    }
    
    // ===== MÉTHODE MODIFIÉE POUR AJAX =====
    public function createUser() {
        header('Content-Type: application/json');
        if (!$this->isAdmin() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Accès non autorisé.']);
            exit;
        }
        
        $email = $_POST['email'];
        $password = $_POST['password'];
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $is_admin = isset($_POST['is_admin']) ? true : false;
        
        // --- Validation basique ---
        if (empty($email) || empty($password) || empty($nom) || empty($prenom)) {
            http_response_code(400); // Bad Request
            echo json_encode(['success' => false, 'message' => 'Tous les champs sont requis.']);
            exit;
        }
        
        if ($this->userModel->createUser($email, $password, $nom, $prenom, $is_admin)) {
            echo json_encode(['success' => true, 'message' => 'Utilisateur créé avec succès ! La liste va s\'actualiser.']);
        } else {
            http_response_code(409); // Conflict (e.g., email already exists)
            echo json_encode(['success' => false, 'message' => 'Erreur : cet email est peut-être déjà utilisé.']);
        }
        exit;
    }
    
    public function updateParkingSpot() {
        header('Content-Type: application/json');

        if (!$this->isAdmin() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Accès non autorisé.']);
            exit;
        }

        $spot_id = $_POST['spot_id'] ?? null;
        if (!$spot_id) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'ID de la place manquant.']);
            exit;
        }

        $data = [
            'status' => $_POST['status'],
            'price_per_hour' => $_POST['price_per_hour'],
            'has_charging_station' => isset($_POST['has_charging_station']) ? 1 : 0
        ];

        if ($this->parkingSpotModel->updateSpot($spot_id, $data)) {
            echo json_encode([
                'success' => true, 
                'message' => 'Place ' . htmlspecialchars($_POST['spot_number'] ?? $spot_id) . ' mise à jour avec succès !'
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false, 
                'message' => 'Erreur lors de la mise à jour de la place en base de données.'
            ]);
        }
        exit;
    }
    
    private function isAdmin() {
        return isset($_SESSION['user_id']) && isset($_SESSION['is_admin']) && $_SESSION['is_admin'];
    }
}