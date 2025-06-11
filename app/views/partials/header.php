<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Titre de la page dynamique -->
    <title><?= isset($page_title) ? htmlspecialchars($page_title) : 'Parking Intelligent - ISEP' ?></title>
    
    <!-- Meta description pour l'éco-conception et le SEO -->
    <meta name="description" content="Gestion en temps réel des places de parking du projet commun ISEP.">
    
    <!-- Lien vers notre feuille de style CSS -->
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>
    <header>
        <?php 
        // Vérifier si le fichier navbar.php existe
        if (file_exists(ROOT_PATH . '/app/views/partials/navbar.php')) {
            require_once ROOT_PATH . '/app/views/partials/navbar.php';
        } else {
            echo "<p>Erreur: Le fichier navbar.php est introuvable.</p>";
        }
        ?>
    </header>
    
    <main>