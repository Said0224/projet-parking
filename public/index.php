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
            
    

        // ===== ROUTES ADMIN =====
        case '/admin':
            require_once ROOT_PATH . '/app/controllers/AdminController.php';
            $controller = new AdminController();
            $controller->index();
            break;

        case '/admin/users':
            require_once ROOT_PATH . '/app/controllers/AdminController.php';
            $controller = new AdminController();
            $controller->manageUsers();
            break;

        case '/admin/parking':
            require_once ROOT_PATH . '/app/controllers/AdminController.php';
            $controller = new AdminController();
            $controller->manageParking();
            break;

        case '/admin/update-user':
            require_once ROOT_PATH . '/app/controllers/AdminController.php';
            $controller = new AdminController();
            $controller->updateUserStatus();
            break;

        case '/admin/delete-user':
            require_once ROOT_PATH . '/app/controllers/AdminController.php';
            $controller = new AdminController();
            $controller->deleteUser();
            break;

        case '/admin/create-user':
            require_once ROOT_PATH . '/app/controllers/AdminController.php';
            $controller = new AdminController();
            $controller->createUser();
            break;

        case '/admin/update-spot':
            require_once ROOT_PATH . '/app/controllers/AdminController.php';
            $controller = new AdminController();
            $controller->updateParkingSpot();
            break;

        // ===== ROUTES UTILISATEUR =====
        case '/user/dashboard':
            require_once ROOT_PATH . '/app/controllers/UserController.php';
            $controller = new UserController();
            $controller->dashboard();
            break;

        case '/user/parking':
            require_once ROOT_PATH . '/app/controllers/UserController.php';
            $controller = new UserController();
            $controller->parking();
            break;

        case '/user/reserve':
            require_once ROOT_PATH . '/app/controllers/UserController.php';
            $controller = new UserController();
            $controller->reserve();
            break;

        case '/user/cancel-reservation':
            require_once ROOT_PATH . '/app/controllers/UserController.php';
            $controller = new UserController();
            $controller->cancelReservation();
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
?>