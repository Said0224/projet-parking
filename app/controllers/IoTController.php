<?php

class IoTController {
    
    public function dashboard() {
        // Démarrer la session si elle n'est pas déjà démarrée
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Vérifier si l'utilisateur est connecté et admin
        if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
            header('Location: /login');
            exit;
        }
        
        // Utiliser le fichier existant dans views/
        require_once ROOT_PATH . '/app/views/iot-dashboard.php';
    }
    
    public function capteurs() {
        // Démarrer la session si elle n'est pas déjà démarrée
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Vérifier si l'utilisateur est connecté et admin
        if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
            header('Location: /login');
            exit;
        }
        
        require_once ROOT_PATH . '/app/views/admin/iot-capteurs.php';
    }
    
    public function actionneurs() {
        // Démarrer la session si elle n'est pas déjà démarrée
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Vérifier si l'utilisateur est connecté et admin
        if (!isset($_SESSION['user_id']) || !$_SESSION['is_admin']) {
            header('Location: /login');
            exit;
        }
        
        require_once ROOT_PATH . '/app/views/admin/iot-actionneurs.php';
    }
}
?>