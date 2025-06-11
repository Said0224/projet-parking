<?php

class HomeController {
    /**
     * Affiche la page d'accueil
     */
    public function index() {
        $page_title = "Accueil - Parking Intelligent ISEP";
        require_once ROOT_PATH . '/app/views/home.php';
    }
}