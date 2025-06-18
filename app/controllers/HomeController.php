<?php

class HomeController {
    /**
     * Affiche la page d'accueil
     */
    public function index() {
        $page_title = "Accueil - Parking Intelligent ISEP";
        
        if (file_exists(ROOT_PATH . '/app/views/home.php')) {
            require_once ROOT_PATH . '/app/views/home.php';
        } else {
            echo "<h1>Erreur</h1>";
            echo "<p>Le fichier de vue 'home.php' est introuvable.</p>";
        }
    }

    /**
     * NOUVELLE MÉTHODE POUR LA FAQ
     * Affiche la page FAQ avec des données directement dans le code.
     */
    public function faq() {
        $page_title = "Questions Fréquemment Posées - FAQ";

        // Ici, nous définissons les questions et réponses directement dans un tableau.
        $faqs = [
            [
                'question' => 'Comment puis-je réserver une place de parking ?',
                'answer' => 'Pour réserver une place, vous devez d\'abord vous connecter à votre compte. Ensuite, depuis votre tableau de bord utilisateur, vous pouvez voir les places disponibles en temps réel et sélectionner celle que vous souhaitez réserver en choisissant un créneau horaire.'
            ],
            [
                'question' => 'Les informations sur les places sont-elles vraiment en temps réel ?',
                'answer' => 'Oui ! Notre système utilise des capteurs IoT (Internet des Objets) installés sur chaque place. Dès qu\'un véhicule entre ou sort, le capteur envoie une mise à jour à notre serveur, et l\'état de la place est actualisé sur le site en quelques secondes.'
            ],
            [
                'question' => 'Que faire si j\'ai un véhicule électrique ?',
                'answer' => 'Certaines de nos places sont équipées de bornes de recharge. Sur la page de gestion du parking, les places avec une borne sont indiquées par une icône spéciale. Vous pouvez filtrer pour trouver ces places plus facilement.'
            ],
            [
                'question' => 'Puis-je annuler une réservation ?',
                'answer' => 'Oui, vous pouvez annuler une réservation sans frais jusqu\'à l\'heure de début de celle-ci. Rendez-vous dans la section "Mes réservations" de votre tableau de bord et cliquez sur le bouton "Annuler".'
            ],
            [
                'question' => 'Que se passe-t-il si j\'oublie mon mot de passe ?',
                'answer' => 'Sur la page de connexion, cliquez sur le lien "Mot de passe oublié ?" (fonctionnalité à venir). Vous pourrez alors réinitialiser votre mot de passe en suivant les instructions envoyées à votre adresse e-mail.'
            ]
        ];
        
        // On charge la vue et on lui passe les données
        require_once ROOT_PATH . '/app/views/faq.php';
    }
}