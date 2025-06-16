<?php
require_once ROOT_PATH . '/app/models/ParkingSpace.php'; // Inclure le nouveau modèle

class DashboardController {
    
    /**
     * Affiche le tableau de bord (nécessite une connexion)
     */
    public function index() {
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
        
        $page_title = "Tableau de Bord - Parking Intelligent";
        
        $spaceModel = new ParkingSpace();
        $all_places = $spaceModel->getAllWithDetails();

        // Grouper les places par étage pour l'affichage
        $placesByEtage = [];
        foreach ($all_places as $place) {
            // Convertir le tableau associatif en objet pour correspondre au format de la vue
            $placesByEtage[$place['etage']][] = (object)$place;
        }
        ksort($placesByEtage); // Trier les étages par ordre croissant

        require_once ROOT_PATH . '/app/views/dashboard.php';
    }

    /**
     * Point d'API pour récupérer les détails d'une place.
     * Accessible via /dashboard/api/getPlaceDetails?id=...
     */
    public function getPlaceDetails() {
        header('Content-Type: application/json');
        
        if (!isset($_SESSION['user_id']) || !isset($_GET['id'])) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Accès non autorisé ou ID manquant.']);
            exit;
        }

        $placeId = (int)$_GET['id'];
        $spaceModel = new ParkingSpace();
        $details = $spaceModel->getDetailsById($placeId);

        if ($details) {
            echo json_encode(['success' => true, 'details' => $details]);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Place non trouvée.']);
        }
    }
}