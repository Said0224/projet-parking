<?php
// Affichage des erreurs pour le développement
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

define('ROOT_PATH', dirname(__DIR__));

$request_uri = explode('?', $_SERVER['REQUEST_URI'], 2)[0];

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
            
        case '/profile':
            require_once ROOT_PATH . '/app/controllers/AuthController.php';
            $controller = new AuthController();
            $controller->profile();
            break;
            
        case '/profile/update':
            require_once ROOT_PATH . '/app/controllers/AuthController.php';
            $controller = new AuthController();
            $controller->updateProfile();
            break;
            
        case '/dashboard':
            require_once ROOT_PATH . '/app/controllers/DashboardController.php';
            $controller = new DashboardController();
            $controller->index();
            break;
            
        case '/iot-dashboard':
            require_once ROOT_PATH . '/app/controllers/IoTDashboardController.php';
            $controller = new IoTDashboardController();
            $controller->index();
            break;
            
        default:
            http_response_code(404);
            echo "<h1>Page non trouvée (404)</h1>";
            echo "<p>La page demandée '" . htmlspecialchars($request_uri) . "' n'existe pas.</p>";
            echo "<a href='/'>Retour à l'accueil</a>";
            break;
    }
} catch (Error $e) {
    echo "<h1>Erreur PHP détectée :</h1>";
    echo "<pre>" . $e->getMessage() . "</pre>";
    echo "<pre>Fichier: " . $e->getFile() . " (ligne " . $e->getLine() . ")</pre>";
} catch (Exception $e) {
    echo "<h1>Exception détectée :</h1>";
    echo "<pre>" . $e->getMessage() . "</pre>";
}