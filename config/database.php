<?php

// Configuration de la connexion à la base de données PostgreSQL commune
define('DB_HOST', 'host.docker.internal');
define('DB_PORT', '5432');
define('DB_NAME', 'app_db_locale');
define('DB_USER', 'postgres');
define('DB_PASS', 'postgres');

// Data Source Name (DSN) pour PostgreSQL
define('DB_DSN', "pgsql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME);

// Options pour PDO
$options = [
    PDO::ATTR_PERSISTENT => true,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

// Classe Database adaptée pour PostgreSQL
class Database {
    private static $instance = null;

    private function __construct() {}
    private function __clone() {}

    public static function getInstance() {
        if (self::$instance === null) {
            try {
                self::$instance = new PDO(DB_DSN, DB_USER, DB_PASS, $GLOBALS['options']);
            } catch (PDOException $e) {
                error_log("Erreur de connexion à la base de données : " . $e->getMessage());
                die("Erreur de connexion à la base de données. Veuillez réessayer plus tard.");
            }
        }
        return self::$instance;
    }
}