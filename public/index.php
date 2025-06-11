<?php
// Démarrer la session au tout début
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Définir le chemin racine
define('ROOT_PATH', dirname(__DIR__));

// Récupérer l'URI de la requête (sans les paramètres GET)
$request_uri = explode('?', $_SERVER['REQUEST_URI'], 2)[0];

// Système de routage
try {
    switch ($request_uri) {
        case '/':
            require_once ROOT_PATH . '/app/controllers/HomeController.php';
            $controller = new HomeController();
            $controller->index();
            break;
            
        case '/login':
            require_once ROOT_PATH . '/app/controllers/AuthController.php';
            $controller = new AuthController();
            $controller->showLoginForm();
            break;
            
        case '/login/process':
            require_once ROOT_PATH . '/app/controllers/AuthController.php';
            $controller = new AuthController();
            $controller->login();
            break;
            
        case '/logout':
            require_once ROOT_PATH . '/app/controllers/AuthController.php';
            $controller = new AuthController();
            $controller->logout();
            break;
            
        case '/signup':
            require_once ROOT_PATH . '/app/controllers/AuthController.php';
            $controller = new AuthController();
            $controller->showRegistrationForm();
            break;
            
        case '/signup/process':
            require_once ROOT_PATH . '/app/controllers/AuthController.php';
            $controller = new AuthController();
            $controller->register();
            break;
            
        // case '/dashboard':
        //     require_once ROOT_PATH . '/app/controllers/DashboardController.php';
        //     $controller = new DashboardController();
        //     $controller->index();
        //     break;
            
        default:
            http_response_code(404);
            echo "<h1>Page non trouvée (404)</h1>";
            echo "<p>La page demandée n'existe pas.</p>";
            echo "<a href='/'>Retour à l'accueil</a>";
            break;
    }
} catch (Exception $e) {
    // Gestion des erreurs générales
    error_log("Erreur dans index.php : " . $e->getMessage());
    http_response_code(500);
    echo "<h1>Erreur interne du serveur</h1>";
    echo "<p>Une erreur est survenue. Veuillez réessayer plus tard.</p>";
    echo "<a href='/'>Retour à l'accueil</a>";
}