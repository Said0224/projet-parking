-- Voir tous les utilisateurs
SELECT * FROM users;

-- Ajouter un nouvel utilisateur
INSERT INTO users (email, password_hash, nom, prenom) 
VALUES ('nouveau@isep.fr', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Nouveau', 'Utilisateur');

-- Modifier un utilisateur
UPDATE users SET nom = 'Modifié' WHERE email = 'test@isep.fr';

-- Supprimer un utilisateur
DELETE FROM users WHERE email = 'nouveau@isep.fr';