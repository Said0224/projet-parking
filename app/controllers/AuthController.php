<?php
require_once ROOT_PATH . '/app/models/User.php';

class AuthController {
    
    public function showLoginForm() {
        if (isset($_SESSION['user_id'])) {

            // REDIRECTION CORRIGÉE
            header('Location: ' . BASE_URL . '/dashboard');

            exit;
        }
        $page_title = "Connexion - Parking Intelligent";
        $error = $_SESSION['login_error'] ?? '';
        unset($_SESSION['login_error']);
        require_once ROOT_PATH . '/app/views/login.php';
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            // REDIRECTION CORRIGÉE
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
            // REDIRECTION CORRIGÉE
            header('Location: ' . BASE_URL . '/login');
            exit;
        }


        

        // Tentative d'authentification
        $userModel = new User();
        $user = $userModel->authenticate($email, $password);

         if ($user) {
            // Connexion réussie

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_nom'] = $user['nom'];
            $_SESSION['user_prenom'] = $user['prenom'];

            $_SESSION['login_time'] = time(); // NOUVEAU : Enregistrer le moment de la connexion

            $_SESSION['is_admin'] = $user['is_admin'] ?? false;


            // Gestion du "Se souvenir de moi"
            if ($remember) {
                $token = bin2hex(random_bytes(32));
                setcookie('remember_token', $token, time() + (86400 * 30), '/', '', false, true);
            }


            // ===== REDIRECTION CORRIGÉE SELON LE STATUT =====
            if ($user['is_admin']) {
                header('Location: ' . BASE_URL . '/admin');
            } else {
                header('Location: ' . BASE_URL . '/user/dashboard');
            }

            exit;
        
        } else {
            $_SESSION['login_error'] = "Email ou mot de passe incorrect.";
            // REDIRECTION CORRIGÉE
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }

    public function showRegistrationForm() {
        if (isset($_SESSION['user_id'])) {

            // REDIRECTION CORRIGÉE
            header('Location: ' . BASE_URL . '/dashboard');

            exit;
        }
        $page_title = "Inscription - Parking Intelligent";
        $error = $_SESSION['register_error'] ?? '';
        $success = $_SESSION['register_success'] ?? '';
        unset($_SESSION['register_error'], $_SESSION['register_success']);
        require_once ROOT_PATH . '/app/views/signup.php';
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            // REDIRECTION CORRIGÉE
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
            // REDIRECTION CORRIGÉE
            header('Location: ' . BASE_URL . '/signup');
            exit;
        }
        $userModel = new User();
        if ($userModel->create($email, $password, $nom, $prenom)) {
            // ---- DEBUT AJOUT ----
            $newUser = $userModel->findByEmail($email);
            if ($newUser) {
                require_once ROOT_PATH . '/app/models/Notification.php';
                $notificationModel = new Notification();
                $notificationModel->createNotification($newUser['id'], 'account_created', 'Bienvenue ! Votre compte a été créé avec succès.');
            }
            // ---- FIN AJOUT ----
            $_SESSION['register_success'] = "Compte créé avec succès ! Vous pouvez maintenant vous connecter.";
            header('Location: ' . BASE_URL . '/login');
            exit;
        }  else {
            $_SESSION['register_error'] = "Erreur lors de la création du compte. L'email est peut-être déjà utilisé.";
            // REDIRECTION CORRIGÉE
            header('Location: ' . BASE_URL . '/signup');
            exit;
        }
    }

    public function logout() {
        if (isset($_COOKIE['remember_token'])) {
            setcookie('remember_token', '', time() - 3600, '/', '', false, true);
        }
        session_destroy();

        header('Location: ' . BASE_URL . '/');
        exit;
    }

    public static function requireAuth() {
        if (!isset($_SESSION['user_id'])) {
            // REDIRECTION CORRIGÉE
            header('Location: ' . BASE_URL . '/login');
            exit;
        }
    }

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

    public function updateProfile() {
        self::requireAuth();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            // REDIRECTION CORRIGÉE
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
            // REDIRECTION CORRIGÉE
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

    public function changePassword() {
        self::requireAuth();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/profile');
            exit;
        }
        
        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        
        $errors = [];
        if (empty($current_password)) $errors[] = "L'ancien mot de passe est requis.";
        if (empty($new_password)) $errors[] = "Le nouveau mot de passe est requis.";
        elseif (strlen($new_password) < 6) $errors[] = "Le nouveau mot de passe doit contenir au moins 6 caractères.";
        if ($new_password !== $confirm_password) $errors[] = "Les nouveaux mots de passe ne correspondent pas.";
        
        if (!empty($errors)) {
            $_SESSION['profile_error'] = implode('<br>', $errors);
            header('Location: ' . BASE_URL . '/profile');
            exit;
        }
        
        $userModel = new User();
        // On appelle la nouvelle méthode du modèle qui vérifie l'ancien mot de passe
        $result = $userModel->changePassword($_SESSION['user_id'], $current_password, $new_password);
        
        if ($result === true) {
            $_SESSION['profile_success'] = "Mot de passe modifié avec succès !";
        } else {
            // On gère les différents cas d'erreur renvoyés par le modèle
            if ($result === 'wrong_password') {
                $_SESSION['profile_error'] = "L'ancien mot de passe est incorrect.";
            } else {
                $_SESSION['profile_error'] = "Erreur lors de la modification du mot de passe.";
            }
        }
        
        header('Location: ' . BASE_URL . '/profile');
        exit;
    }

    public function deleteAccount() {
        self::requireAuth();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/profile');
            exit;
        }
        $userModel = new User();
        if ($userModel->deleteUser($_SESSION['user_id'])) {
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