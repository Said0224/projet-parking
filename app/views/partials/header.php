<?php
// GESTION DE L'EXPIRATION DE SESSION
if (isset($_SESSION['user_id'])) { 
    $timeout_duration = 1800; // 30 minutes
    $current_time = time();

    if (isset($_SESSION['login_time']) && (($current_time - $_SESSION['login_time']) > $timeout_duration)) {
        session_unset(); 
        session_destroy();
        header('Location: ' . BASE_URL . '/login?status=session_expired');
        exit;
    }
    
    $_SESSION['login_time'] = $current_time;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title><?= isset($page_title) ? htmlspecialchars($page_title) : 'Parking Intelligent - ISEP' ?></title>
    
    <meta name="description" content="Gestion en temps réel des places de parking du projet commun ISEP.">
    
    <link rel="icon" href="<?= BASE_URL ?>/public/favicon.ico" type="image/x-icon">
    
    <link rel="stylesheet" href="<?= BASE_URL ?>/public/css/style.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
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
                    <li><a href="<?= BASE_URL ?>/faq" class="nav-link">FAQ</a></li>
                    
                    <?php if (isset($_SESSION['user_id'])): ?>
                        
                        <?php if (isset($_SESSION['is_admin']) && $_SESSION['is_admin']): ?>
                            <li><a href="<?= BASE_URL ?>/admin" class="nav-link">Admin</a></li>
                            <li><a href="<?= BASE_URL ?>/iot-dashboard" class="nav-link">IoT</a></li>
                        <?php else: ?>
                            <li><a href="<?= BASE_URL ?>/user/dashboard" class="nav-link">Mon Dashboard</a></li>
                        <?php endif; ?>

                        <li><a href="<?= BASE_URL ?>/notifications" class="nav-link">Notifications</a></li>
                        <li><a href="<?= BASE_URL ?>/profile" class="nav-link">Profil</a></li>
                        <li><a href="<?= BASE_URL ?>/logout" class="nav-link">Déconnexion</a></li>

                    <?php else: ?>
                        <li><a href="<?= BASE_URL ?>/login" class="nav-link">Connexion</a></li>
                        <li><a href="<?= BASE_URL ?>/signup" class="nav-link">Inscription</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </nav>
    </header>
    
    <main>