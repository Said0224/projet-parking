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

        // --- DÉBUT DE LA CORRECTION ---
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $reservations_per_page = 5;

        // On construit le tableau des filtres à partir de l'URL, exactement comme dans la méthode AJAX
        $filters = [];
        if (!empty($_GET['filter_date'])) {
            $filters['date'] = $_GET['filter_date'];
        }
        if (!empty($_GET['filter_spot_id'])) {
            $filters['spot_id'] = $_GET['filter_spot_id'];
        }
        
        // On passe le tableau des filtres aux méthodes du modèle
        $total_reservations = $this->reservationModel->getTotalReservationsCount($filters);
        $total_pages = ceil($total_reservations / $reservations_per_page);

        $reservations = $this->reservationModel->getPaginatedReservations($page, $reservations_per_page, $filters);
        // --- FIN DE LA CORRECTION ---
        
        $page_title = "Administration - Parking Intelligent";
        require_once ROOT_PATH . '/app/views/admin/dashboard.php';
    }

    // La méthode getReservationsAjax que nous avons créée précédemment est déjà correcte et n'a pas besoin de changer.
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
        
        $users = $this->userModel->getAllUsers();
        $page_title = "Gestion des utilisateurs";
        require_once ROOT_PATH . '/app/views/admin/users.php';
    }
    
    public function manageParking() {
        if (!$this->isAdmin()) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        
        $spots = $this->parkingSpotModel->getAllSpots();
        $page_title = "Gestion du parking";
        require_once ROOT_PATH . '/app/views/admin/parking.php';
    }
    
    public function updateUserStatus() {
        if (!$this->isAdmin() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admin');
            exit;
        }
        
        $user_id = $_POST['user_id'];
        $is_admin = isset($_POST['is_admin']) ? true : false;
        
        $this->userModel->updateUserAdminStatus($user_id, $is_admin);
        header('Location: ' . BASE_URL . '/admin/users');
    }
    
    public function deleteUser() {
        if (!$this->isAdmin() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admin');
            exit;
        }
        
        $user_id = $_POST['user_id'];
        $this->userModel->deleteUser($user_id);
        header('Location: ' . BASE_URL . '/admin/users');
    }
    
    public function createUser() {
        if (!$this->isAdmin() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/admin');
            exit;
        }
        
        $email = $_POST['email'];
        $password = $_POST['password'];
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $is_admin = isset($_POST['is_admin']) ? true : false;
        
        $this->userModel->createUser($email, $password, $nom, $prenom, $is_admin);
        header('Location: ' . BASE_URL . '/admin/users');
    }
    
    

    public function updateParkingSpot() {
        // On s'attend à une requête AJAX, donc on prépare une réponse JSON
        header('Content-Type: application/json');

        // Vérification des permissions
        if (!$this->isAdmin() || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(403); // Forbidden
            echo json_encode(['success' => false, 'message' => 'Accès non autorisé.']);
            exit;
        }

        $spot_id = $_POST['spot_id'] ?? null;
        if (!$spot_id) {
            http_response_code(400); // Bad Request
            echo json_encode(['success' => false, 'message' => 'ID de la place manquant.']);
            exit;
        }

        // Préparation des données (avec la correction 1/0 pour le booléen)
        $data = [
            'status' => $_POST['status'],
            'price_per_hour' => $_POST['price_per_hour'],
            'has_charging_station' => isset($_POST['has_charging_station']) ? 1 : 0
        ];

        // Tentative de mise à jour
        if ($this->parkingSpotModel->updateSpot($spot_id, $data)) {
            // En cas de succès, on renvoie un message de succès
            echo json_encode([
                'success' => true, 
                'message' => 'Place ' . htmlspecialchars($_POST['spot_number'] ?? $spot_id) . ' mise à jour avec succès !'
            ]);
        } else {
            // En cas d'échec
            http_response_code(500); // Internal Server Error
            echo json_encode([
                'success' => false, 
                'message' => 'Erreur lors de la mise à jour de la place en base de données.'
            ]);
        }
        // Notez qu'il n'y a plus de redirection "header('Location: ...')"
        exit; // On arrête le script ici
    }
    
    private function isAdmin() {
        return isset($_SESSION['user_id']) && isset($_SESSION['is_admin']) && $_SESSION['is_admin'];
    }
}
?>