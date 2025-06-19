<?php
// Affichage des erreurs pour le développement
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

define('ROOT_PATH', __DIR__);
// La variable BASE_URL reste utile pour construire les liens dans les vues
$script_name = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
define('BASE_URL', rtrim($script_name, '/'));

// 1. Récupérer l'URL propre depuis le paramètre GET envoyé par .htaccess
$request_uri = '/' . trim($_GET['url'] ?? '', '/');

try {
    // Inclure la configuration de la base de données une seule fois au début
    require_once ROOT_PATH . '/config/database.php';

    switch ($request_uri) {

        case '/':
            require_once ROOT_PATH . '/app/controllers/HomeController.php';
            $controller = new HomeController();
            $controller->index();
            break;
            
        case '/faq':
            require_once ROOT_PATH . '/app/controllers/HomeController.php';
            $controller = new HomeController();
            $controller->faq();
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

        // ===== ROUTE CORRIGÉE ET AJOUTÉE ICI =====
        case '/profile/change-password':
            require_once ROOT_PATH . '/app/controllers/AuthController.php';
            $controller = new AuthController();
            $controller->changePassword();
            break;

        // ===== ROUTE AJOUTÉE POUR LA SUPPRESSION DE COMPTE =====
        case '/profile/delete-account':
            require_once ROOT_PATH . '/app/controllers/AuthController.php';
            $controller = new AuthController();
            $controller->deleteAccount();
            break;
            
        case '/dashboard':
            require_once ROOT_PATH . '/app/controllers/DashboardController.php';
            $controller = new DashboardController();
            $controller->index();
            break;
            

        case '/iot-dashboard':
            require_once ROOT_PATH . '/app/controllers/IoTController.php';
            $controller = new IoTController();
            $controller->dashboard();
            break;

        case '/iot-dashboard/capteurs':
            require_once ROOT_PATH . '/app/controllers/IoTController.php';
            $controller = new IoTController();
            $controller->capteurs();
            break;

        case '/iot-dashboard/actionneurs':
            require_once ROOT_PATH . '/app/controllers/IoTController.php';
            $controller = new IoTController();
            $controller->actionneurs();
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


        case '/admin/api/reservations':
            require_once ROOT_PATH . '/app/controllers/AdminController.php';
            $controller = new AdminController();
            $controller->getReservationsAjax();
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


         case '/api/update-spot-status':
            require_once ROOT_PATH . '/app/controllers/ApiController.php';
            $controller = new ApiController();
            $controller->updateSpotStatus();
            break;

        case '/api/get-spot-status':
            require_once ROOT_PATH . '/app/controllers/ApiController.php';
            $controller = new ApiController();
            $controller->getSpotStatus();
            break;

        case '/api/get-spot-details':
            require_once ROOT_PATH . '/app/controllers/ApiController.php';
            $controller = new ApiController();
            $controller->getSpotDetails();
            break;
            
        case '/api/get-all-spots-status':
            require_once ROOT_PATH . '/app/controllers/UserController.php';
            $controller = new UserController();
            $controller->getAllSpotsStatus();
            break;

            
        default:
            http_response_code(404);
            echo "<h1>Page non trouvée (404)</h1>";
            echo "<p>La page demandée '" . htmlspecialchars($request_uri) . "' n'existe pas.</p>";
            echo "<a href='" . BASE_URL . "/'>Retour à l'accueil</a>";
            break;
    }
} catch (Error $e) {
    echo "<h1>Erreur PHP détectée :</h1>";
    echo "<pre>" . $e->getMessage() . "</pre>";
    echo "<pre>Fichier: " . $e->getFile() . " (ligne " . $e->getLine() . ")</pre>";
} catch (Exception $e) {
    echo "<h1>Exception détectée :</h1>";
    echo "<pre>" . $e->getMessage() . "</pre>";

} finally {
    Database::closeConnection();

}
?>