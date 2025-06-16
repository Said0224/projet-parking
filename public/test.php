<?php
echo "<h1>Test PHP</h1>";
echo "<p>PHP Version: " . phpversion() . "</p>";
echo "<p>Date: " . date('Y-m-d H:i:s') . "</p>";

// Test des chemins
echo "<p>ROOT_PATH: " . dirname(__DIR__) . "</p>";
echo "<p>Dossier app existe: " . (is_dir(dirname(__DIR__) . '/app') ? 'OUI' : 'NON') . "</p>";
echo "<p>Fichier HomeController existe: " . (file_exists(dirname(__DIR__) . '/app/controllers/HomeController.php') ? 'OUI' : 'NON') . "</p>";

phpinfo();
?>