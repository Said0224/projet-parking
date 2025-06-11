<?php
require_once ROOT_PATH . '/app/models/User.php'; // On aura besoin du modèle User

class AuthController {

    /**
     * Affiche la page de connexion
     */
    public function showLoginForm() {
        // Si l'utilisateur est déjà connecté, on le redirige vers le tableau de bord (à implémenter)
        // if (isset($_SESSION['user_id'])) {
        //     header('Location: /dashboard');
        //     exit;
        // }
        require_once ROOT_PATH . '/app/views/login.php';
    }

    /**
     * Traite la soumission du formulaire de connexion
     */
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'] ?? '';
            $password = $_POST['password'] ?? '';

            // Validation simple (à améliorer)
            if (empty($email) || empty($password)) {
                $error = "Veuillez remplir tous les champs.";
                require_once ROOT_PATH . '/app/views/login.php'; // Réafficher le formulaire avec l'erreur
                return;
            }

            $userModel = new User();
            $user = $userModel->findByEmail($email);

            if ($user && password_verify($password, $user['password_hash'])) {
                // Connexion réussie !
                // Démarrer la session et stocker les informations de l'utilisateur
                session_start(); // Important: à appeler au début des scripts qui utilisent les sessions
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_email'] = $user['email'];
                // $_SESSION['user_role'] = $user['role']; // Si vous avez des rôles

                // Rediriger vers le tableau de bord (à créer)
                header('Location: /dashboard');
                exit;
            } else {
                $error = "Email ou mot de passe incorrect.";
                require_once ROOT_PATH . '/app/views/login.php'; // Réafficher le formulaire avec l'erreur
            }
        } else {
            // Si la requête n'est pas POST, rediriger vers le formulaire de connexion
            header('Location: /login');
            exit;
        }
    }

    /**
     * Déconnecte l'utilisateur
     */
    public function logout() {
        session_start();
        session_unset(); // Supprime toutes les variables de session
        session_destroy(); // Détruit la session
        header('Location: /login'); // Redirige vers la page de connexion
        exit;
    }

    // Plus tard, on ajoutera showRegistrationForm() et register() ici
}