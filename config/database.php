<?php

// Configuration de la connexion à la base de données
// Remplacez ces valeurs par celles de votre base de données partagée
define('DB_HOST', 'localhost');         // L'hôte de votre base de données (ex: localhost, ou l'IP du serveur ISEP)
define('DB_NAME', 'parking_isep');      // Le nom de votre base de données
define('DB_USER', 'votre_utilisateur'); // Votre nom d'utilisateur pour la BDD
define('DB_PASS', 'votre_mot_de_passe'); // Votre mot de passe pour la BDD
define('DB_CHARSET', 'utf8mb4');        // Encodage des caractères

// Data Source Name (DSN) pour PDO
define('DB_DSN', "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET);

// Options pour PDO (connexion persistante, mode d'erreur)
$options = [
    PDO::ATTR_PERSISTENT => true, // Connexions persistantes (éco-conception: réduit la charge de création de connexion)
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Lancer des exceptions en cas d'erreur SQL
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Récupérer les résultats sous forme de tableau associatif
    PDO::ATTR_EMULATE_PREPARES => false, // Utiliser de vraies requêtes préparées (sécurité: anti-injection SQL)
];

// Fonction pour obtenir une instance de PDO (connexion à la BDD)
// On utilisera un singleton pour s'assurer qu'on n'ouvre pas plusieurs connexions inutilement (éco-conception)
class Database {
    private static $instance = null;

    private function __construct() {} // Empêche l'instanciation directe
    private function __clone() {}    // Empêche le clonage

    public static function getInstance() {
        if (self::$instance === null) {
            try {
                self::$instance = new PDO(DB_DSN, DB_USER, DB_PASS, $GLOBALS['options']);
            } catch (PDOException $e) {
                // En production, logguer l'erreur et afficher un message générique
                // Pour le développement, on peut afficher l'erreur
                error_log("Erreur de connexion à la base de données : " . $e->getMessage());
                die("Erreur de connexion à la base de données. Veuillez réessayer plus tard.");
            }
        }
        return self::$instance;
    }
}