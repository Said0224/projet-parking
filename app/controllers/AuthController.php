<?php
require_once ROOT_PATH . '/app/models/User.php';

class AuthController {

    /**
     * Affiche la page de connexion
     */
    public function showLoginForm() {
        // Si l'utilisateur est déjà connecté, on le redirige
        if (isset($_SESSION['user_id'])) {
            header('Location: /dashboard');
            exit;
        }
        
        $page_title = "Connexion - Parking Intelligent";
        require_once ROOT_PATH . '/app/views/login.php';
    }

    /**
     * Traite la soumission du formulaire de connexion
     */
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            // Validation simple
            if (empty($email) || empty($password)) {
                $error = "Veuillez remplir tous les champs.";
                $page_title = "Connexion - Parking Intelligent";
                require_once ROOT_PATH . '/app/views/login.php';
                return;
            }

            $userModel = new User();
            $user = $userModel->findByEmail($email);

            if ($user && password_verify($password, $user['password_hash'])) {
                // Connexion réussie !
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];

                // Rediriger vers le tableau de bord
                header('Location: /dashboard');
                exit;
            } else {
                $error = "Email ou mot de passe incorrect.";
                $page_title = "Connexion - Parking Intelligent";
                require_once ROOT_PATH . '/app/views/login.php';
            }
        } else {
            header('Location: /login');
            exit;
        }
    }

    /**
     * Déconnecte l'utilisateur
     */
    public function logout() {
        session_unset();
        session_destroy();
        header('Location: /login?message=disconnected');
        exit;
    }

    /**
     * Affiche la page d'inscription
     */
    public function showRegistrationForm() {
        // Si l'utilisateur est déjà connecté, on le redirige
        if (isset($_SESSION['user_id'])) {
            header('Location: /dashboard');
            exit;
        }
        
        $page_title = "Inscription - Parking Intelligent";
        require_once ROOT_PATH . '/app/views/signup.php';
    }

    /**
     * Traite la soumission du formulaire d'inscription
     */
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';
            $confirm_password = $_POST['confirm_password'] ?? '';

            $page_title = "Inscription - Parking Intelligent";

            // Validation des entrées
            if (empty($email) || empty($password) || empty($confirm_password)) {
                $error = "Veuillez remplir tous les champs.";
                require_once ROOT_PATH . '/app/views/signup.php';
                return;
            }

            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = "L'adresse email n'est pas valide.";
                require_once ROOT_PATH . '/app/views/signup.php';
                return;
            }

            if (strlen($password) < 8) {
                $error = "Le mot de passe doit contenir au moins 8 caractères.";
                require_once ROOT_PATH . '/app/views/signup.php';
                return;
            }

            if ($password !== $confirm_password) {
                $error = "Les mots de passe ne correspondent pas.";
                require_once ROOT_PATH . '/app/views/signup.php';
                return;
            }

            $userModel = new User();

            // Vérifier si l'email existe déjà
            if ($userModel->findByEmail($email)) {
                $error = "Cette adresse email est déjà utilisée.";
                require_once ROOT_PATH . '/app/views/signup.php';
                return;
            }

            // Créer l'utilisateur
            if ($userModel->create($email, $password)) {
                // Rediriger vers la page de connexion avec un message de succès
                header('Location: /login?message=registered');
                exit;
            } else {
                $error = "Une erreur est survenue lors de l'inscription. Veuillez réessayer.";
                require_once ROOT_PATH . '/app/views/signup.php';
            }
        } else {
            header('Location: /signup');
            exit;
        }
    }
}