<?php
require_once ROOT_PATH . '/app/models/User.php';

class AuthController {
    
    /**
     * Afficher le formulaire de connexion
     */
    public function showLoginForm() {
        if (isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
        
        $page_title = "Connexion - Parking Intelligent";
        $error = $_SESSION['login_error'] ?? '';
        unset($_SESSION['login_error']);
        
        require_once ROOT_PATH . '/app/views/login.php';
    }

    /**
     * Traiter la connexion
     */
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);

        $errors = [];
        if (empty($email)) $errors[] = "L'email est requis.";
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Format d'email invalide.";
        if (empty($password)) $errors[] = "Le mot de passe est requis.";

        if (!empty($errors)) {
            $_SESSION['login_error'] = implode('<br>', $errors);
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $userModel = new User();
        $user = $userModel->authenticate($email, $password);

        if ($user) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_nom'] = $user['nom'];
            $_SESSION['user_prenom'] = $user['prenom'];
            $_SESSION['is_admin'] = (bool)$user['is_admin']; // Assurez-vous que c'est un booléen
            $_SESSION['login_time'] = time();

            if ($remember) {
                $token = bin2hex(random_bytes(32));
                setcookie('remember_token', $token, time() + (86400 * 30), '/', '', false, true);
            }

            if ($_SESSION['is_admin']) {
                header('Location: ' . BASE_URL . '/admin');
            } else {
                header('Location: ' . BASE_URL . '/user/dashboard');
            }
            exit;
        
        } else {
            $_SESSION['login_error'] = "Email ou mot de passe incorrect.";
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }

    /**
     * Afficher le formulaire d'inscription
     */
    public function showRegistrationForm() {
        if (isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/dashboard');
            exit;
        }
        
        $page_title = "Inscription - Parking Intelligent";
        $error = $_SESSION['register_error'] ?? '';
        $success = $_SESSION['register_success'] ?? '';
        unset($_SESSION['register_error'], $_SESSION['register_success']);
        
        require_once ROOT_PATH . '/app/views/signup.php';
    }

    /**
     * Traiter l'inscription
     */
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/signup');
            exit;
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        $nom = trim($_POST['nom'] ?? '');
        $prenom = trim($_POST['prenom'] ?? '');

        $errors = [];
        if (empty($email)) $errors[] = "L'email est requis.";
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Format d'email invalide.";
        if (empty($password)) $errors[] = "Le mot de passe est requis.";
        elseif (strlen($password) < 6) $errors[] = "Le mot de passe doit contenir au moins 6 caractères.";
        if ($password !== $confirm_password) $errors[] = "Les mots de passe ne correspondent pas.";
        if (empty($nom)) $errors[] = "Le nom est requis.";
        if (empty($prenom)) $errors[] = "Le prénom est requis.";

        if (!empty($errors)) {
            $_SESSION['register_error'] = implode('<br>', $errors);
            header('Location: ' . BASE_URL . '/signup');
            exit;
        }

        $userModel = new User();
        
        if ($userModel->create($email, $password, $nom, $prenom)) {
            $_SESSION['register_success'] = "Compte créé avec succès ! Vous pouvez maintenant vous connecter.";
            header('Location: ' . BASE_URL . '/login');
            exit;
        } else {
            $_SESSION['register_error'] = "Erreur lors de la création du compte. L'email est peut-être déjà utilisé.";
            header('Location: ' . BASE_URL . '/signup');
            exit;
        }
    }

    /**
     * Déconnexion
     */
    public function logout() {
        if (isset($_COOKIE['remember_token'])) {
            setcookie('remember_token', '', time() - 3600, '/', '', false, true);
        }
        session_destroy();
        header('Location: ' . BASE_URL . '/');
        exit;
    }

    /**
     * Vérifier si l'utilisateur est connecté (middleware)
     */
    public static function requireAuth() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }

    /**
     * Afficher le profil utilisateur
     */
    public function profile() {
        self::requireAuth();
        
        $page_title = "Mon Profil - Parking Intelligent";
        $userModel = new User();
        $user = $userModel->findById($_SESSION['user_id']);
        
        if (!$user) {
            header('Location: ' . BASE_URL . '/logout');
            exit;
        }
        
        $success = $_SESSION['profile_success'] ?? '';
        $error = $_SESSION['profile_error'] ?? '';
        unset($_SESSION['profile_success'], $_SESSION['profile_error']);
        
        require_once ROOT_PATH . '/app/views/profile.php';
    }

    /**
     * Mettre à jour le profil
     */
    public function updateProfile() {
        self::requireAuth();
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/profile');
            exit;
        }

        $nom = trim($_POST['nom'] ?? '');
        $prenom = trim($_POST['prenom'] ?? '');

        $errors = [];
        if (empty($nom)) $errors[] = "Le nom est requis.";
        if (empty($prenom)) $errors[] = "Le prénom est requis.";

        if (!empty($errors)) {
            $_SESSION['profile_error'] = implode('<br>', $errors);
            header('Location: ' . BASE_URL . '/profile');
            exit;
        }

        $userModel = new User();
        
        if ($userModel->updateProfile($_SESSION['user_id'], $nom, $prenom)) {
            $_SESSION['user_nom'] = $nom;
            $_SESSION['user_prenom'] = $prenom;
            $_SESSION['profile_success'] = "Profil mis à jour avec succès !";
        } else {
            $_SESSION['profile_error'] = "Erreur lors de la mise à jour du profil.";
        }
        
        header('Location: ' . BASE_URL . '/profile');
        exit;
    }

    /**
     * Changer le mot de passe de l'utilisateur
     */
    public function changePassword() {
        self::requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/profile');
            exit;
        }

        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        $errors = [];

        if (empty($password)) {
            $errors[] = "Le nouveau mot de passe est requis.";
        } elseif (strlen($password) < 6) {
            $errors[] = "Le mot de passe doit contenir au moins 6 caractères.";
        }
        if ($password !== $confirm_password) {
            $errors[] = "Les mots de passe ne correspondent pas.";
        }

        if (!empty($errors)) {
            $_SESSION['profile_error'] = implode('<br>', $errors);
            header('Location: ' . BASE_URL . '/profile');
            exit;
        }
        
        $userModel = new User();
        if ($userModel->changePassword($_SESSION['user_id'], $password)) {
            $_SESSION['profile_success'] = "Mot de passe modifié avec succès.";
        } else {
            $_SESSION['profile_error'] = "Erreur lors de la modification du mot de passe.";
        }
        
        header('Location: ' . BASE_URL . '/profile');
        exit;
    }

    /**
     * Supprimer le compte de l'utilisateur
     */
    public function deleteAccount() {
        self::requireAuth();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/profile');
            exit;
        }

        $userModel = new User();
        if ($userModel->deleteUser($_SESSION['user_id'])) {
            // Déconnexion et destruction de la session après suppression
            session_destroy();
            header('Location: ' . BASE_URL . '/?message=account_deleted');
            exit;
        } else {
            $_SESSION['profile_error'] = "Erreur lors de la suppression du compte.";
            header('Location: ' . BASE_URL . '/profile');
            exit;
        }
    }
}