
<?php
require_once ROOT_PATH . '/config/database.php';

class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    public function create($email, $password, $nom = '', $prenom = '') {
        try {
            if ($this->findByEmail($email)) { return false; }
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->db->prepare("INSERT INTO users (email, password_hash, nom, prenom, is_admin, created_at, updated_at) VALUES (?, ?, ?, ?, false, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");
            return $stmt->execute([$email, $password_hash, $nom, $prenom]);
        } catch (PDOException $e) {
            error_log("Erreur dans User::create : " . $e->getMessage());
            return false;
        }
    }

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

    public function authenticate($email, $password) {
        try {
            $user = $this->findByEmail($email);
            if ($user && password_verify($password, $user['password_hash'])) {
                $this->updateLastLogin($user['id']);
                return $user;
            }
            return false;
        } catch (Exception $e) {
            error_log("Erreur dans User::authenticate : " . $e->getMessage());
            return false;
        }
    }

    private function updateLastLogin($userId) {
        try {
            $stmt = $this->db->prepare("UPDATE users SET updated_at = CURRENT_TIMESTAMP WHERE id = ?");
            $stmt->execute([$userId]);
        } catch (PDOException $e) {
            error_log("Erreur dans User::updateLastLogin : " . $e->getMessage());
        }
    }

    public function updateProfile($userId, $nom, $prenom) {
        try {
            $stmt = $this->db->prepare("UPDATE users SET nom = ?, prenom = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
            return $stmt->execute([$nom, $prenom, $userId]);
        } catch (PDOException $e) {
            error_log("Erreur dans User::updateProfile : " . $e->getMessage());
            return false;
        }
    }

    public function changePassword($userId, $currentPassword, $newPassword) {
        try {
            $user = $this->findById($userId);
            if (!$user) { return false; }
            if (!password_verify($currentPassword, $user['password_hash'])) {
                return 'wrong_password';
            }
            $password_hash = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $this->db->prepare("UPDATE users SET password_hash = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
            return $stmt->execute([$password_hash, $userId]);
        } catch (PDOException $e) {
            error_log("Erreur dans User::changePassword : " . $e->getMessage());
            return false;
        }
    }

    public function getAllUsers() {
        try {
            $stmt = $this->db->query("SELECT id, email, nom, prenom, is_admin, created_at, updated_at FROM users ORDER BY created_at DESC");
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Erreur dans User::getAllUsers : " . $e->getMessage());
            return [];
        }
    }
    
    public function updateUserAdminStatus($user_id, $is_admin) {
        try {
            $stmt = $this->db->prepare("UPDATE users SET is_admin = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
            return $stmt->execute([$is_admin, $user_id]);
        } catch (PDOException $e) {
            error_log("Erreur dans User::updateUserAdminStatus : " . $e->getMessage());
            return false;
        }
    }
    
    public function deleteUser($user_id) {
        try {
            $stmt1 = $this->db->prepare("DELETE FROM reservations WHERE user_id = ?");
            $stmt1->execute([$user_id]);
            $stmt2 = $this->db->prepare("DELETE FROM users WHERE id = ?");
            return $stmt2->execute([$user_id]);
        } catch (PDOException $e) {
            error_log("Erreur dans User::deleteUser : " . $e->getMessage());
            return false;
        }
    }
    
    public function createUser($email, $password, $nom, $prenom, $is_admin = false) {
        try {
            if ($this->findByEmail($email)) { return false; }
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $this->db->prepare("INSERT INTO users (email, password_hash, nom, prenom, is_admin, created_at, updated_at) VALUES (?, ?, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");
            return $stmt->execute([$email, $password_hash, $nom, $prenom, $is_admin]);
        } catch (PDOException $e) {
            error_log("Erreur dans User::createUser : " . $e->getMessage());
            return false;
        }
    }
}

