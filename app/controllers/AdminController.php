<?php
// Fichier : app/controllers/AdminController.php

require_once ROOT_PATH . '/app/models/User.php';
require_once ROOT_PATH . '/app/models/ParkingSpot.php';
require_once ROOT_PATH . '/app/models/Reservation.php';
require_once ROOT_PATH . '/app/models/Actuator.php'; // <-- AJOUT

class AdminController {
    private $userModel;
    private $parkingSpotModel;
    private $reservationModel;
    private $actuatorModel; // <-- AJOUT
    
    public function __construct() {
        $this->userModel = new User();
        $this->parkingSpotModel = new ParkingSpot();
        $this->reservationModel = new Reservation();
        $this->actuatorModel = new Actuator(); // <-- AJOUT
    }
    
    // ... (les autres méthodes comme index(), getReservationsAjax(), etc. ne changent pas) ...
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
        $users_per_page = 8;

        $filters = [];
        if (isset($_GET['filter_role']) && $_GET['filter_role'] !== '') {
            $filters['is_admin'] = $_GET['filter_role'];
        }

        $total_users = $this->userModel->getTotalUsersCount($filters);
        $total_pages = ceil($total_users / $users_per_page);
        $users = $this->userModel->getPaginatedUsers($page, $users_per_page, $filters);
        
        $page_title = "Gestion des utilisateurs";
        require_once ROOT_PATH . '/app/views/admin/users.php';
    }

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

        require ROOT_PATH . '/app/views/admin/partials/users_table.php';
        exit;
    }
    
    public function manageParking() {
        if (!$this->isAdmin()) {
            header('Location: ' . BASE_URL . '/login');
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
        
        $page_title = "Gestion du parking";
        require_once ROOT_PATH . '/app/views/admin/parking.php';
    }

    public function getParkingSpotsAjax() {
        if (!$this->isAdmin()) {
            http_response_code(403);
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
        $is_admin = isset($_POST['is_admin']) ? 1 : 0;
        
        if ($this->userModel->updateUserAdminStatus($user_id, $is_admin)) {
            echo json_encode(['success' => true, 'message' => 'Rôle de l\'utilisateur mis à jour.']);
        } else {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour du rôle.']);
        }
        exit;
    }
    
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
        
        if (empty($email) || empty($password) || empty($nom) || empty($prenom)) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Tous les champs sont requis.']);
            exit;
        }
        
        if ($this->userModel->createUser($email, $password, $nom, $prenom, $is_admin)) {
            echo json_encode(['success' => true, 'message' => 'Utilisateur créé ! La liste va s\'actualiser.']);
        } else {
            http_response_code(409);
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
        
        $spot_number = $_POST['spot_number']; // On le récupère pour la logique LED

        if ($this->parkingSpotModel->updateSpot($spot_id, $data)) {
            
            // ======================================================
            // == DÉBUT DE LA NOUVELLE LOGIQUE CORRIGÉE POUR LA LED ==
            // ======================================================
            if ($spot_number === '101') { // On applique la logique seulement si c'est la place connectée
                $led_id = 1;
                $new_spot_status = $data['status'];
                
                // 1. Lire l'état actuel de la LED pour ne pas écraser la luminosité
                $currentLedState = $this->actuatorModel->getLedById($led_id);

                if ($currentLedState) {
                    // 2. Préserver l'état et la luminosité existants
                    $led_etat = $currentLedState['etat'];
                    $led_intensite = $currentLedState['intensite'];
                    
                    // 3. Déterminer la nouvelle couleur en fonction du statut de la place
                    $led_couleur = '#00FF00'; // Vert par défaut pour 'disponible'
                    switch ($new_spot_status) {
                        case 'occupée':
                        case 'maintenance':
                            $led_couleur = '#FF0000'; // Rouge
                            break;
                        case 'réservée':
                            $led_couleur = '#f59e0b'; // Orange/Jaune
                            break;
                    }

                    // 4. Mettre à jour la LED avec la nouvelle couleur mais en gardant l'ancienne luminosité
                    // On passe la commande en 'auto_status' pour signaler que c'est une mise à jour automatique
                    $this->actuatorModel->updateLedDetails($led_id, $led_etat, $led_couleur, $led_intensite, 'auto_status');
                }
            }
            // =====================================================
            // ==  FIN DE LA NOUVELLE LOGIQUE CORRIGÉE POUR LA LED ==
            // =====================================================

            echo json_encode([
                'success' => true, 
                'message' => 'Place ' . htmlspecialchars($spot_number) . ' mise à jour avec succès !'
            ]);
        } else {
            http_response_code(500);
            echo json_encode([
                'success' => false, 
                'message' => 'Erreur lors de la mise à jour de la place.'
            ]);
        }
        exit;
    }

    
    private function isAdmin() {
        return isset($_SESSION['user_id']) && isset($_SESSION['is_admin']) && $_SESSION['is_admin'];
    }
}