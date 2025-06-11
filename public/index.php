<?php
// DÉBOGAGE : Afficher toutes les erreurs
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

echo "<!-- Début du script -->";

// Démarrer la session au tout début
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

echo "<!-- Session démarrée -->";

// Définir le chemin racine
define('ROOT_PATH', dirname(__DIR__));

echo "<!-- ROOT_PATH défini: " . ROOT_PATH . " -->";

// Récupérer l'URI de la requête (sans les paramètres GET)
$request_uri = explode('?', $_SERVER['REQUEST_URI'], 2)[0];

echo "<!-- URI demandée: " . $request_uri . " -->";

// Système de routage
try {
    echo "<!-- Début du routage -->";
    
    switch ($request_uri) {
        case '/':
            echo "<!-- Route: page d'accueil -->";
            if (file_exists(ROOT_PATH . '/app/controllers/HomeController.php')) {
                require_once ROOT_PATH . '/app/controllers/HomeController.php';
                echo "<!-- HomeController inclus -->";
                $controller = new HomeController();
                echo "<!-- HomeController instancié -->";
                $controller->index();
                echo "<!-- Méthode index() appelée -->";
            } else {
                die("Erreur: HomeController.php non trouvé dans " . ROOT_PATH . '/app/controllers/');
            }
            break;
            
        case '/login':
            echo "<!-- Route: login -->";
            if (file_exists(ROOT_PATH . '/app/controllers/AuthController.php')) {
                require_once ROOT_PATH . '/app/controllers/AuthController.php';
                $controller = new AuthController();
                $controller->showLoginForm();
            } else {
                die("Erreur: AuthController.php non trouvé");
            }
            break;
            
        case '/login/process':
            if (file_exists(ROOT_PATH . '/app/controllers/AuthController.php')) {
                require_once ROOT_PATH . '/app/controllers/AuthController.php';
                $controller = new AuthController();
                $controller->login();
            } else {
                die("Erreur: AuthController.php non trouvé");
            }
            break;
            
        case '/logout':
            if (file_exists(ROOT_PATH . '/app/controllers/AuthController.php')) {
                require_once ROOT_PATH . '/app/controllers/AuthController.php';
                $controller = new AuthController();
                $controller->logout();
            } else {
                die("Erreur: AuthController.php non trouvé");
            }
            break;
            
        case '/signup':
            if (file_exists(ROOT_PATH . '/app/controllers/AuthController.php')) {
                require_once ROOT_PATH . '/app/controllers/AuthController.php';
                $controller = new AuthController();
                $controller->showRegistrationForm();
            } else {
                die("Erreur: AuthController.php non trouvé");
            }
            break;
            
        case '/signup/process':
            if (file_exists(ROOT_PATH . '/app/controllers/AuthController.php')) {
                require_once ROOT_PATH . '/app/controllers/AuthController.php';
                $controller = new AuthController();
                $controller->register();
            } else {
                die("Erreur: AuthController.php non trouvé");
            }
            break;
            
        case '/dashboard':
            if (file_exists(ROOT_PATH . '/app/controllers/DashboardController.php')) {
                require_once ROOT_PATH . '/app/controllers/DashboardController.php';
                $controller = new DashboardController();
                $controller->index();
            } else {
                die("Erreur: DashboardController.php non trouvé");
            }
            break;
            
        default:
            http_response_code(404);
            echo "<h1>Page non trouvée (404)</h1>";
            echo "<p>La page demandée '" . htmlspecialchars($request_uri) . "' n'existe pas.</p>";
            echo "<a href='/'>Retour à l'accueil</a>";
            break;
    }
    
    echo "<!-- Fin du routage -->";
    
} catch (Error $e) {
    // Capture les erreurs PHP (plus large que Exception)
    echo "<h1>Erreur PHP détectée :</h1>";
    echo "<pre>" . $e->getMessage() . "</pre>";
    echo "<pre>Fichier: " . $e->getFile() . " (ligne " . $e->getLine() . ")</pre>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
} catch (Exception $e) {
    // Gestion des exceptions
    echo "<h1>Exception détectée :</h1>";
    echo "<pre>" . $e->getMessage() . "</pre>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

echo "<!-- Fin du script -->";