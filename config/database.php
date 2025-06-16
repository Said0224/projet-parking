<?php

// === CONNEXION 1: Base de données PostgreSQL (Primaire) ===
define('DB_SENSORS_HOST', 'app.garageisep.com');
define('DB_SENSORS_PORT', '5409');
define('DB_SENSORS_NAME', 'app_db');
define('DB_SENSORS_USER', 'app_user');
define('DB_SENSORS_PASS', 'appg9');

// === CONNEXION 2: Base de données MariaDB (Capteurs et Utilisateurs) ===
define('DB_PRIMARY_HOST', 'herogu.garageisep.com');
define('DB_PRIMARY_NAME', 'IxMd95C0YL_projet_par');
define('DB_PRIMARY_USER', 'kCrBSMvT17_projet_par');
define('DB_PRIMARY_PASS', 'JVQ6LotVLJAHjH5r');


// Options PDO communes
$options = [
    PDO::ATTR_PERSISTENT => true,
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

// Classe Database adaptée pour gérer plusieurs connexions
class Database {
    // On va stocker les instances de connexion dans un tableau
    private static $instances = [];

    private function __construct() {}
    private function __clone() {}

    /**
     * Récupère une instance de connexion PDO.
     * @param string $key La clé de la connexion à utiliser ('primary' ou 'sensors')
     * @return PDO L'instance de PDO
     */
    public static function getInstance($key = 'primary') {
        // Si l'instance pour cette clé n'a pas encore été créée...
        if (!isset(self::$instances[$key])) {
            try {
                                switch ($key) {
                    case 'sensors': // L'ancienne base PostgreSQL
                        // DSN pour PostgreSQL
                        $dsn = "pgsql:host=" . DB_SENSORS_HOST . ";port=" . DB_SENSORS_PORT . ";dbname=" . DB_SENSORS_NAME;
                        self::$instances[$key] = new PDO($dsn, DB_SENSORS_USER, DB_SENSORS_PASS, $GLOBALS['options']);
                        break;
                    
                    case 'primary': // Votre nouvelle base MariaDB
                    default:
                        // DSN pour MariaDB/MySQL
                        $dsn = "mysql:host=" . DB_PRIMARY_HOST . ";dbname=" . DB_PRIMARY_NAME . ";charset=utf8mb4";
                        self::$instances[$key] = new PDO($dsn, DB_PRIMARY_USER, DB_PRIMARY_PASS, $GLOBALS['options']);
                        break;
                }
            } catch (PDOException $e) {
                error_log("Erreur de connexion à la base de données '$key' : " . $e->getMessage());
                die("Erreur de connexion à la base de données. Veuillez réessayer plus tard.");
            }
        }
        // On retourne l'instance demandée
        return self::$instances[$key];
    }
}