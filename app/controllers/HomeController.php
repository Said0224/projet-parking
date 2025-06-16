<?php

class HomeController {
    /**
     * Affiche la page d'accueil
     */
    public function index() {
        $page_title = "Accueil - Parking Intelligent ISEP";
        
        // Vérifier si le fichier de vue existe
        if (file_exists(ROOT_PATH . '/app/views/home.php')) {
            require_once ROOT_PATH . '/app/views/home.php';
        } else {
            echo "<h1>Erreur</h1>";
            echo "<p>Le fichier de vue 'home.php' est introuvable.</p>";
        }
    }
}