<?php
// NOUVEAU BLOC : GESTION DE L'EXPIRATION DE SESSION
if (isset($_SESSION['user_id'])) { // Vérifier uniquement si l'utilisateur est connecté
    $timeout_duration = 1800; // Durée en secondes (ici, 30 minutes)
    $current_time = time();

    if (isset($_SESSION['login_time']) && (($current_time - $_SESSION['login_time']) > $timeout_duration)) {
        // La session a expiré
        session_unset(); // Supprime les variables de session
        session_destroy(); // Détruit la session
        
        // Redirige vers la page de connexion avec un message d'information
        header('Location: ' . BASE_URL . '/login?status=session_expired');
        exit;
    }
    
    // Si la session n'a pas expiré, on met à jour le temps de la dernière activité
    $_SESSION['login_time'] = $current_time;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- Titre de la page dynamique -->
    <title><?= isset($page_title) ? htmlspecialchars($page_title) : 'Parking Intelligent - ISEP' ?></title>
    
    <!-- Meta description pour l'éco-conception et le SEO -->
    <meta name="description" content="Gestion en temps réel des places de parking du projet commun ISEP.">
    
    <!-- Favicon -->
    <link rel="icon" href="<?= BASE_URL ?>/public/favicon.ico" type="image/x-icon">
    
    <!-- Lien vers notre feuille de style CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">
    
    <!-- Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <!-- Polices Google -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <nav class="navbar">
            <div class="container navbar-container">
                <a href="<?= BASE_URL ?>/" class="navbar-brand">
                    <i class="fas fa-parking"></i> Parking Intelligent
                </a>
                
                <ul class="navbar-nav">
                    <li><a href="<?= BASE_URL ?>/" class="nav-link">Accueil</a></li>
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <?php // Utilisateur connecté ?>
                        
                        <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                            <?php // Liens spécifiques pour l'ADMIN ?>
                            <li><a href="<?= BASE_URL ?>/admin" class="nav-link">Admin</a></li>
                            <li><a href="<?= BASE_URL ?>/iot-dashboard" class="nav-link">IoT</a></li>
                        <?php else: ?>
                            <?php // Liens spécifiques pour l'UTILISATEUR standard ?>
                            <li><a href="<?= BASE_URL ?>/user/dashboard" class="nav-link">Mon Dashboard</a></li>
                        <?php endif; ?>

                        <li><a href="<?= BASE_URL ?>/profile" class="nav-link">Profil</a></li>
                        <li><a href="<?= BASE_URL ?>/logout" class="nav-link">Déconnexion</a></li>

                    <?php else: ?>
                        <?php // Utilisateur non connecté ?>
                        <li><a href="<?= BASE_URL ?>/login" class="nav-link">Connexion</a></li>
                        <li><a href="<?= BASE_URL ?>/signup" class="nav-link">Inscription</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>
    
    <main>