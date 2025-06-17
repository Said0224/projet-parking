// Fichier : public/test_db_connection.php
//http://localhost:8080/test_db_connection.php

<?php
// Affichage des erreurs pour le développement
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Vos identifiants de connexion locaux
$host = 'host.docker.internal'; // Utilisez ceci pour Docker
// $host = 'localhost'; // Utilisez ceci si PHP n'est PAS dans Docker
$port = '5432';
$dbname = 'app_db_locale';
$user = 'postgres';
$pass = 'postgres';

// DSN pour PostgreSQL
$dsn = "pgsql:host=$host;port=$port;dbname=$dbname";

echo "<h1>Test de connexion à la base de données PostgreSQL</h1>";
echo "<p>Tentative de connexion avec les paramètres suivants :</p>";
echo "<ul>";
echo "<li>Hôte : $host</li>";
echo "<li>Port : $port</li>";
echo "<li>Base de données : $dbname</li>";
echo "<li>Utilisateur : $user</li>";
echo "</ul><hr>";

try {
    // Création de l'instance PDO
    $pdo = new PDO($dsn, $user, $pass);

    // Si la connexion réussit
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "<h2 style='color:green;'>Connexion réussie !</h2>";

    // Optionnel : tester une petite requête
    $stmt = $pdo->query('SELECT version()');
    $version = $stmt->fetchColumn();
    echo "<p>Version de PostgreSQL : $version</p>";

} catch (PDOException $e) {
    // Si la connexion échoue, afficher l'erreur détaillée
    echo "<h2 style='color:red;'>Échec de la connexion.</h2>";
    echo "<p><strong>Message d'erreur de PHP :</strong></p>";
    echo "<pre style='background-color:#f0f0f0; border:1px solid #ccc; padding:10px; border-radius:5px;'>" . $e->getMessage() . "</pre>";
}
?>