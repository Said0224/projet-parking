<?php

class HomeController {
    /**
     * Affiche la page d'accueil
     */
    public function index() {
        // On charge la vue (le fichier HTML)
        require_once ROOT_PATH . '/app/views/home.php';
    }
}