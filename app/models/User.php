<?php
require_once ROOT_PATH . '/config/database.php';

class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Créer un nouvel utilisateur
     */
    public function create($email, $password, $nom = '', $prenom = '') {
        try {
            // Vérifier si l'email existe déjà
            if ($this->findByEmail($email)) {
                return false; // Email déjà utilisé
            }

            // Hacher le mot de passe
            $password_hash = password_hash($password, PASSWORD_DEFAULT);

            // Insérer le nouvel utilisateur
            $stmt = $this->db->prepare("
                INSERT INTO users (email, password_hash, nom, prenom, is_admin, created_at, updated_at) 
                VALUES (?, ?, ?, ?, false, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
            ");
            
            return $stmt->execute([$email, $password_hash, $nom, $prenom]);
        } catch (PDOException $e) {
            error_log("Erreur dans User::create : " . $e->getMessage());
            return false;
        }
    }

    /**
     * Trouver un utilisateur par email
     */
    public function findByEmail($email) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$email]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Erreur dans User::findByEmail : " . $e->getMessage());
            return false;
        }
    }

    /**
     * Trouver un utilisateur par ID
     */
    public function findById($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Erreur dans User::findById : " . $e->getMessage());
            return false;
        }
    }

    /**
     * Vérifier les identifiants de connexion
     */
    public function authenticate($email, $password) {
        try {
            $user = $this->findByEmail($email);
            
            if ($user && password_verify($password, $user['password_hash'])) {
                // Mettre à jour la date de dernière connexion
                $this->updateLastLogin($user['id']);
                return $user;
            }
            
            return false;
        } catch (Exception $e) {
            error_log("Erreur dans User::authenticate : " . $e->getMessage());
            return false;
        }
    }

    /**
     * Mettre à jour la date de dernière connexion
     */
    private function updateLastLogin($userId) {
        try {
            $stmt = $this->db->prepare("UPDATE users SET updated_at = CURRENT_TIMESTAMP WHERE id = ?");
            $stmt->execute([$userId]);
        } catch (PDOException $e) {
            error_log("Erreur dans User::updateLastLogin : " . $e->getMessage());
        }
    }

    /**
     * Mettre à jour le profil utilisateur
     */
    public function updateProfile($userId, $nom, $prenom) {
        try {
            $stmt = $this->db->prepare("
                UPDATE users 
                SET nom = ?, prenom = ?, updated_at = CURRENT_TIMESTAMP 
                WHERE id = ?
            ");
            return $stmt->execute([$nom, $prenom, $userId]);
        } catch (PDOException $e) {
            error_log("Erreur dans User::updateProfile : " . $e->getMessage());
            return false;
        }
    }

    /**
     * Changer le mot de passe
     */
    public function changePassword($userId, $newPassword) {
        try {
            $password_hash = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $this->db->prepare("
                UPDATE users 
                SET password_hash = ?, updated_at = CURRENT_TIMESTAMP 
                WHERE id = ?
            ");
            return $stmt->execute([$password_hash, $userId]);
        } catch (PDOException $e) {
            error_log("Erreur dans User::changePassword : " . $e->getMessage());
            return false;
        }
    }

    /**
     * Obtenir tous les utilisateurs (pour l'admin)
     */
    public function getAllUsers() {
        try {
            $stmt = $this->db->query("
                SELECT id, email, nom, prenom, is_admin, created_at, updated_at 
                FROM users 
                ORDER BY created_at DESC
            ");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Erreur dans User::getAllUsers : " . $e->getMessage());
            return [];
        }
    }

    /**
     * Supprimer un utilisateur
     */
    public function delete($userId) {
        try {
            $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
            return $stmt->execute([$userId]);
        } catch (PDOException $e) {
            error_log("Erreur dans User::delete : " . $e->getMessage());
            return false;
        }
    }

    // NOUVELLES MÉTHODES POUR L'ADMINISTRATION
    
    /**
     * Mettre à jour le statut admin d'un utilisateur
     */
    public function updateUserAdminStatus($user_id, $is_admin) {
        try {
            $stmt = $this->db->prepare("UPDATE users SET is_admin = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
            return $stmt->execute([$is_admin, $user_id]);
        } catch (PDOException $e) {
            error_log("Erreur dans User::updateUserAdminStatus : " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Supprimer un utilisateur (avec ses réservations)
     */
    public function deleteUser($user_id) {
        try {
            // D'abord supprimer les réservations de l'utilisateur
            $stmt1 = $this->db->prepare("DELETE FROM reservations WHERE user_id = ?");
            $stmt1->execute([$user_id]);
            
            // Puis supprimer l'utilisateur
            $stmt2 = $this->db->prepare("DELETE FROM users WHERE id = ?");
            return $stmt2->execute([$user_id]);
        } catch (PDOException $e) {
            error_log("Erreur dans User::deleteUser : " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Créer un utilisateur (pour l'admin)
     */
    public function createUser($email, $password, $nom, $prenom, $is_admin = false) {
        try {
            // Vérifier si l'email existe déjà
            if ($this->findByEmail($email)) {
                return false;
            }

            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->db->prepare("
                INSERT INTO users (email, password_hash, nom, prenom, is_admin, created_at, updated_at) 
                VALUES (?, ?, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
            ");
            return $stmt->execute([$email, $password_hash, $nom, $prenom, $is_admin]);
        } catch (PDOException $e) {
            error_log("Erreur dans User::createUser : " . $e->getMessage());
            return false;
        }
    }
}
?>