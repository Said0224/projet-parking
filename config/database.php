<?php

// --- NOUVELLE CONFIGURATION POUR LA BASE DE DONNÉES MySQL ---
define('DB_HOST', 'herogu.garageisep.com');
define('DB_NAME', 'IxMd95C0YL_projet_par');
define('DB_USER', 'kCrBSMvT17_projet_par');
define('DB_PASS', 'JVQ6LotVLJAHjH5r');
// Le port standard pour MySQL est 3306, il est généralement omis car c'est le défaut.

/*
// Configuration de la connexion à la base de données PostgreSQL commune
define('DB_HOST', 'app.garageisep.com');
define('DB_PORT', '5409');
define('DB_NAME', 'app_db');
define('DB_USER', 'app_user');
define('DB_PASS', 'appg9');
*/

/*
// Configuration de la connexion à la base de données PostgreSQL locale 
define('DB_HOST', 'localhost');
define('DB_PORT', '5432');
define('DB_NAME', 'app_db_locale');
define('DB_USER', 'postgres');
define('DB_PASS', 'postgres');
*/


// --- NOUVEAU DSN (Data Source Name) POUR MYSQL ---
// On utilise "mysql:" au lieu de "pgsql:" et on ajoute le charset.
define('DB_DSN', "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4");


class Database {
    private static $instance = null;

    private function __construct() {}
    private function __clone() {}

    public static function getInstance() {
        if (self::$instance === null) {

            // Options pour PDO, optimisées pour la sécurité et la robustesse
            $options = [
                PDO::ATTR_PERSISTENT => false, // Crucial pour éviter "Too many connections"
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false, // Toujours utiliser de vraies requêtes préparées
            ];

            try {
                // On crée une nouvelle instance de PDO avec les bonnes informations

                self::$instance = new PDO(DB_DSN, DB_USER, DB_PASS, $options);
            } catch (PDOException $e) {
                // En cas d'échec, on log l'erreur et on arrête le script proprement
                error_log("Erreur de connexion à la base de données : " . $e->getMessage());
                // Message générique pour l'utilisateur
                die("Impossible de se connecter à la base de données. Veuillez contacter un administrateur.");
            }
        }
        return self::$instance;
    }

    
    // NOUVELLE MÉTHODE CI-DESSOUS
    /**
     * Ferme la connexion à la base de données en détruisant l'instance.
     * Cela force PDO à libérer la connexion au serveur MySQL.
     */
    public static function closeConnection() {
        self::$instance = null;
    }
}