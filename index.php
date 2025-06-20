<?php
session_start();

// Configuration des chemins
define('ROOT_PATH', __DIR__);
define('BASE_URL', 'http://localhost/projet-parking');

// Inclusion de la configuration de la base de données
require_once ROOT_PATH . '/config/database.php';

// Fonction d'autoload simple
function autoload($className) {
    $paths = [
        ROOT_PATH . '/app/controllers/' . $className . '.php',
        ROOT_PATH . '/app/models/' . $className . '.php'
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
}

spl_autoload_register('autoload');

// Récupération de l'URL
$url = $_GET['url'] ?? '';
$url = rtrim($url, '/');
$urlSegments = explode('/', $url);

// Routage
$controller = 'HomeController';
$method = 'index';

if (!empty($urlSegments[0])) {
    switch ($urlSegments[0]) {
        case 'login':
            $controller = 'AuthController';
            $method = isset($urlSegments[1]) && $urlSegments[1] === 'process' ? 'processLogin' : 'login';
            break;
            
        case 'signup':
            $controller = 'AuthController';
            $method = isset($urlSegments[1]) && $urlSegments[1] === 'process' ? 'processSignup' : 'signup';
            break;
            
        case 'logout':
            $controller = 'AuthController';
            $method = 'logout';
            break;
            
        case 'dashboard':
            $controller = 'DashboardController';
            break;
            
        case 'admin':
            $controller = 'AdminController';
            break;
            
        case 'user':
            $controller = 'UserController';
            $method = isset($urlSegments[1]) ? $urlSegments[1] : 'dashboard';
            break;
            
        case 'api':
            $controller = 'ApiController';
            if (isset($urlSegments[1])) {
                switch ($urlSegments[1]) {
                    case 'update-spot-status':
                        $method = 'updateSpotStatus';
                        break;
                    case 'get-spot-status':
                        $method = 'getSpotStatus';
                        break;
                    case 'get-spot-details':
                        $method = 'getSpotDetails';
                        break;
                }
            }
            break;
            
        case 'notifications':
            $controller = 'NotificationController';
            $method = isset($urlSegments[1]) && $urlSegments[1] === 'update-preference' ? 'updateMailPreference' : 'index';
            break;
            
        case 'faq':
            $controller = 'HomeController';
            $method = 'faq';
            break;
            
        // NOUVELLES ROUTES IOT
        case 'iot-dashboard':
            $controller = 'IoTController';
            if (isset($urlSegments[1])) {
                switch ($urlSegments[1]) {
                    case 'capteurs':
                        $method = 'capteurs';
                        break;
                    case 'actionneurs':
                        $method = 'actionneurs';
                        break;
                    case 'update-led-state':
                        $method = 'updateLedState';
                        break;
                    case 'update-motor-state':
                        $method = 'updateMotorState';
                        break;
                    case 'update-oled':
                        $method = 'updateOLED';
                        break;
                    case 'get-sensors-data':
                        $method = 'getSensorsData';
                        break;
                    case 'get-actuators-data':
                        $method = 'getActuatorsData';
                        break;
                    default:
                        $method = 'index';
                }
            }
            break;
            
        default:
            $controller = 'HomeController';
            break;
    }
}

// Instanciation et exécution
try {
    if (class_exists($controller)) {
        $controllerInstance = new $controller();
        if (method_exists($controllerInstance, $method)) {
            $controllerInstance->$method();
        } else {
            throw new Exception("Méthode $method non trouvée dans $controller");
        }
    } else {
        throw new Exception("Contrôleur $controller non trouvé");
    }
} catch (Exception $e) {
    // Page d'erreur simple
    http_response_code(404);
    echo "<h1>Erreur 404</h1>";
    echo "<p>Page non trouvée.</p>";
    echo "<p><a href='" . BASE_URL . "'>Retour à l'accueil</a></p>";
}
?>
