<?php

class DatabaseManager {
    /**
     * @var array Holds the configuration for all database connections.
     */
    private static $config = [
        'local' => [
            'dsn'      => 'mysql:host=localhost;dbname=IxMd95C0YL_projet_par;charset=utf8mb4',
            'username' => 'root',
            'password' => '',
            'options'  => [
                PDO::ATTR_PERSISTENT => false,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ]
        ],
        'iot' => [
            'dsn'      => 'pgsql:host=app.garageisep.com;port=5409;dbname=app_db',
            'username' => 'app_user',
            'password' => 'appg9',
            'options'  => [
                PDO::ATTR_PERSISTENT => false,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        ]
    ];

    /**
     * @var array Holds the active PDO connection instances.
     */
    private static $connections = [];

    // The constructor is private to prevent direct creation of object.
    private function __construct() {}

    /**
     * Gets a database connection instance.
     *
     * @param string $name The name of the connection (e.g., 'local' or 'iot').
     * @return PDO The PDO connection instance.
     */
    public static function getConnection($name = 'local') {
        // If the connection has not been made yet, create it.
        if (!isset(self::$connections[$name])) {
            if (!isset(self::$config[$name])) {
                die("Erreur: La configuration de la base de données '{$name}' n'existe pas.");
            }

            $config = self::$config[$name];
            
            try {
                // Create a new PDO instance and store it.
                self::$connections[$name] = new PDO(
                    $config['dsn'],
                    $config['username'],
                    $config['password'],
                    $config['options']
                );
            } catch (PDOException $e) {
                error_log("Erreur de connexion à la base de données '{$name}': " . $e->getMessage());
                die("Impossible de se connecter à la base de données '{$name}'.");
            }
        }

        // Return the existing connection.
        return self::$connections[$name];
    }

    /**
     * Closes a specific connection or all connections if no name is given.
     *
     * @param string|null $name The name of the connection to close.
     */
    public static function closeConnection($name = null) {
        if ($name) {
            self::$connections[$name] = null;
            unset(self::$connections[$name]);
        } else {
            // Close all connections
            self::$connections = [];
        }
    }
}