<?php
require_once ROOT_PATH . '/config/database.php';

class User {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance();
    }

    /**
     * Trouve un utilisateur par son adresse email.
     * @param string $email L'email de l'utilisateur.
     * @return array|false Les données de l'utilisateur ou false si non trouvé.
     */
    public function findByEmail($email) {
        try {
            $stmt = $this->db->prepare("SELECT id, email, password_hash FROM Utilisateurs WHERE email = :email LIMIT 1");
            // Note: Assurez-vous que votre table s'appelle bien 'Utilisateurs' et la colonne de mot de passe 'password_hash'
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            // Gérer l'erreur (ex: logguer)
            error_log("Erreur dans findByEmail : " . $e->getMessage());
            return false;
        }
    }

    /**
     * Crée un nouvel utilisateur.
     * @param string $email L'email de l'utilisateur.
     * @param string $passwordLe mot de passe en clair (sera hashé).
     * @return bool True si la création réussit, false sinon.
     */
    public function create($email, $password) {
        // Sécurité : Toujours hasher les mots de passe avant de les stocker !
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        try {
            $stmt = $this->db->prepare("INSERT INTO Utilisateurs (email, password_hash) VALUES (:email, :password_hash)");
            // Assurez-vous que votre table 'Utilisateurs' a bien les colonnes 'email' et 'password_hash'
            $stmt->bindParam(':email', $email, PDO::PARAM_STR);
            $stmt->bindParam(':password_hash', $password_hash, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            // Gérer l'erreur (ex: logguer, vérifier si l'email existe déjà)
            error_log("Erreur dans create User : " . $e->getMessage());
            return false;
        }
    }
    // D'autres méthodes pour la gestion des utilisateurs (update, delete, etc.) pourront être ajoutées ici.
}