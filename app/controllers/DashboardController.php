<?php

class DashboardController {

    
    
    /**
     * Affiche le tableau de bord (nécessite une connexion)
     */
    public function index() {
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        
        $page_title = "Tableau de Bord - Parking Intelligent";
        
        // Ici, on récupérera plus tard les données des capteurs
        // Pour l'instant, on utilise des données fictives
        $parking_spaces = [
            ['id' => 1, 'name' => 'Place A1', 'status' => 'libre', 'sensor_id' => 'SENSOR_001'],
            ['id' => 2, 'name' => 'Place A2', 'status' => 'occupee', 'sensor_id' => 'SENSOR_002'],
            ['id' => 3, 'name' => 'Place B1', 'status' => 'libre', 'sensor_id' => 'SENSOR_003'],
            ['id' => 4, 'name' => 'Place B2', 'status' => 'occupee', 'sensor_id' => 'SENSOR_004'],
        ];
        
        require_once ROOT_PATH . '/app/views/dashboard.php';
    }
       public function admin()
    {
        // code for admin dashboard
        require_once ROOT_PATH . '/app/views/admin_dashboard.php';
    }
}



