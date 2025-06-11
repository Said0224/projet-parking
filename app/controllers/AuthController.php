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
<<<<<<< HEAD
                $page_title = "Connexion - Parking Intelligent";
=======
>>>>>>> 9e93d6e9e8a35db0cc28f6c8284073a0ac016d87
                require_once ROOT_PATH . '/app/views/login.php';
                return;
            }

            $userModel = new User();
            $user = $userModel->findByEmail($email);

            if ($user && password_verify($password, $user['password_hash'])) {
                // Connexion réussie !
<<<<<<< HEAD
=======
                // Démarrer la session et stocker les informations de l'utilisateur
>>>>>>> 9e93d6e9e8a35db0cc28f6c8284073a0ac016d87
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];

                // Rediriger vers le tableau de bord
                header('Location: /dashboard');
                exit;
            } else {
                $error = "Email ou mot de passe incorrect.";
<<<<<<< HEAD
                $page_title = "Connexion - Parking Intelligent";
=======
>>>>>>> 9e93d6e9e8a35db0cc28f6c8284073a0ac016d87
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
<<<<<<< HEAD
        session_unset();
        session_destroy();
        header('Location: /login?message=disconnected');
=======
        session_unset(); // Supprime toutes les variables de session
        session_destroy(); // Détruit la session
        header('Location: /login'); // Redirige vers la page de connexion
>>>>>>> 9e93d6e9e8a35db0cc28f6c8284073a0ac016d87
        exit;
    }

    /**
     * Affiche la page d'inscription
     */
    public function showRegistrationForm() {
        // Si l'utilisateur est déjà connecté, on le redirige
<<<<<<< HEAD
        if (isset($_SESSION['user_id'])) {
            header('Location: /dashboard');
            exit;
        }
        
        $page_title = "Inscription - Parking Intelligent";
=======
        // if (isset($_SESSION['user_id'])) {
        //     header('Location: /dashboard');
        //     exit;
        // }
>>>>>>> 9e93d6e9e8a35db0cc28f6c8284073a0ac016d87
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

<<<<<<< HEAD
            $page_title = "Inscription - Parking Intelligent";

=======
>>>>>>> 9e93d6e9e8a35db0cc28f6c8284073a0ac016d87
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
<<<<<<< HEAD
                // Rediriger vers la page de connexion avec un message de succès
                header('Location: /login?message=registered');
                exit;
=======
                $success = "Votre compte a été créé avec succès ! Vous pouvez maintenant vous connecter.";
                require_once ROOT_PATH . '/app/views/signup.php';
>>>>>>> 9e93d6e9e8a35db0cc28f6c8284073a0ac016d87
            } else {
                $error = "Une erreur est survenue lors de l'inscription. Veuillez réessayer.";
                require_once ROOT_PATH . '/app/views/signup.php';
            }
        } else {
<<<<<<< HEAD
=======
            // Si la requête n'est pas POST, rediriger vers le formulaire d'inscription
>>>>>>> 9e93d6e9e8a35db0cc28f6c8284073a0ac016d87
            header('Location: /signup');
            exit;
        }
    }
}