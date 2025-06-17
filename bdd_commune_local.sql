-- ===================================================================
-- SCRIPT D'INSTALLATION COMPLET POUR MYSQL / PHPMYADMIN
-- 1. Crée la base de données parking_db si elle n'existe pas.
-- 2. Crée toutes les tables et insère les données.
-- ===================================================================

-- Crée la base de données avec le bon encodage de caractères (pour les accents, emojis, etc.)
CREATE DATABASE IF NOT EXISTS parking_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Sélectionne la base de données pour toutes les commandes suivantes
USE parking_db;

--
-- Paramètres pour une importation sans erreurs
--
SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


-- --------------------------------------------------------
--
-- Structure de la table capteurgaz
--
DROP TABLE IF EXISTS capteurgaz;
CREATE TABLE capteurgaz (
  id int(11) NOT NULL AUTO_INCREMENT,
  date date DEFAULT NULL,
  heure time DEFAULT NULL,
  valeur double DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO capteurgaz (id, date, heure, valeur) VALUES
(1, '2025-06-13', '01:41:00', 400), (2, '2025-06-13', '01:41:00', 520.8), (3, '2025-06-13', '17:12:00', 325), (5, '2025-06-15', '12:50:23', 380), (7, '2025-06-17', '11:47:43', 1), (8, '2025-06-17', '11:48:11', 183), (9, '2025-06-17', '11:48:13', 172), (10, '2025-06-17', '11:48:15', 199), (11, '2025-06-17', '11:48:17', 191), (12, '2025-06-17', '11:48:19', 755), (13, '2025-06-17', '11:48:21', 963), (14, '2025-06-17', '11:48:23', 1280), (15, '2025-06-17', '11:48:25', 1419), (16, '2025-06-17', '11:48:28', 1399), (17, '2025-06-17', '11:48:42', 1408), (18, '2025-06-17', '11:48:44', 1423), (19, '2025-06-17', '11:48:46', 1563), (20, '2025-06-17', '09:54:02', 380), (21, '2025-06-17', '14:44:08', 0), (22, '2025-06-17', '14:44:12', 47), (23, '2025-06-17', '14:44:14', 0), (24, '2025-06-17', '14:44:20', 223), (25, '2025-06-17', '14:44:22', 418), (26, '2025-06-17', '14:44:24', 467), (27, '2025-06-17', '14:44:26', 618), (28, '2025-06-17', '14:44:29', 832), (29, '2025-06-17', '14:44:31', 915), (30, '2025-06-17', '14:44:33', 1183), (31, '2025-06-17', '14:44:35', 1207), (32, '2025-06-17', '14:44:39', 1215), (33, '2025-06-17', '14:44:41', 1158), (34, '2025-06-17', '14:44:43', 1190), (35, '2025-06-17', '14:44:45', 1152), (36, '2025-06-17', '14:44:47', 1207), (37, '2025-06-17', '14:44:49', 1156), (38, '2025-06-17', '14:44:52', 1207), (39, '2025-06-17', '14:44:54', 1175), (40, '2025-06-17', '14:44:56', 1156), (41, '2025-06-17', '14:44:58', 1171), (42, '2025-06-17', '14:45:00', 1186), (43, '2025-06-17', '14:45:02', 1215), (44, '2025-06-17', '14:45:04', 1183), (45, '2025-06-17', '14:45:06', 1175), (46, '2025-06-17', '14:45:08', 1191), (47, '2025-06-17', '14:45:10', 1156), (48, '2025-06-17', '14:45:12', 1162), (49, '2025-06-17', '14:45:14', 1199), (50, '2025-06-17', '14:45:16', 1171), (51, '2025-06-17', '14:45:19', 1184), (52, '2025-06-17', '14:45:21', 1162), (53, '2025-06-17', '14:45:23', 1207), (54, '2025-06-17', '14:45:25', 1199), (55, '2025-06-17', '14:45:27', 1207), (56, '2025-06-17', '14:45:29', 1156), (57, '2025-06-17', '14:45:35', 1215), (58, '2025-06-17', '14:45:37', 1158), (59, '2025-06-17', '14:45:39', 1207), (60, '2025-06-17', '14:45:41', 1170), (61, '2025-06-17', '14:45:43', 1156), (62, '2025-06-17', '14:45:45', 1174), (63, '2025-06-17', '14:45:47', 1215), (64, '2025-06-17', '12:52:47', 380), (65, '2025-06-17', '16:49:26', 23), (66, '2025-06-17', '16:49:28', 14), (67, '2025-06-17', '16:49:30', 4), (68, '2025-06-17', '16:49:32', 15), (69, '2025-06-17', '16:49:34', 0), (70, '2025-06-17', '16:49:38', 47), (71, '2025-06-17', '16:49:40', 0), (72, '2025-06-17', '16:49:44', 6), (73, '2025-06-17', '16:49:46', 87), (74, '2025-06-17', '16:49:48', 471), (75, '2025-06-17', '16:49:51', 959), (76, '2025-06-17', '16:49:53', 1408), (77, '2025-06-17', '16:49:55', 1973), (78, '2025-06-17', '16:49:57', 2272), (79, '2025-06-17', '16:49:59', 2615), (80, '2025-06-17', '16:50:05', 2623), (81, '2025-06-17', '16:50:07', 2600), (82, '2025-06-17', '16:50:11', 2619), (83, '2025-06-17', '16:50:13', 2810), (84, '2025-06-17', '16:50:15', 2912), (85, '2025-06-17', '16:50:17', 2923), (86, '2025-06-17', '16:50:29', 2907), (87, '2025-06-17', '16:50:31', 2920), (88, '2025-06-17', '16:50:36', 2912), (89, '2025-06-17', '16:50:42', 2923), (90, '2025-06-17', '16:50:44', 2910), (91, '2025-06-17', '14:50:47', 380);

-- --------------------------------------------------------
-- (Le reste du script pour les autres tables suit ici...)
-- Structure de la table capteurlum
DROP TABLE IF EXISTS capteurlum;
CREATE TABLE capteurlum (
  id int(11) NOT NULL AUTO_INCREMENT,
  date date DEFAULT NULL,
  heure time DEFAULT NULL,
  valeur double DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT INTO capteurlum (id, date, heure, valeur) VALUES (1, '2025-06-13', '01:41:00', 320), (2, '2025-06-13', '01:41:00', 450.5);

-- Structure de la table capteurproximite
DROP TABLE IF EXISTS capteurproximite;
CREATE TABLE capteurproximite (
  id int(11) NOT NULL AUTO_INCREMENT,
  place int(11) DEFAULT NULL,
  date date DEFAULT NULL,
  heure time DEFAULT NULL,
  valeur tinyint(1) DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT INTO capteurproximite (id, place, date, heure, valeur) VALUES (1, 1, '2025-06-13', '01:41:00', 1), (2, 2, '2025-06-13', '11:50:00', 1), (3, 3, '2025-06-13', '17:12:00', 1), (4, 4, '2025-06-13', '17:12:00', 1);

-- Structure de la table capteurson
DROP TABLE IF EXISTS capteurson;
CREATE TABLE capteurson (
  id int(11) NOT NULL AUTO_INCREMENT,
  date date DEFAULT NULL,
  heure time DEFAULT NULL,
  valeur double DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT INTO capteurson (id, date, heure, valeur) VALUES (1, '2025-06-13', '01:41:00', 58.3), (2, '2025-06-13', '01:41:00', 60.2), (3, '2025-06-13', '17:12:00', 80), (4, '2025-06-13', '17:12:00', 88);

-- Structure de la table capteurtemp
DROP TABLE IF EXISTS capteurtemp;
CREATE TABLE capteurtemp (
  id int(11) NOT NULL AUTO_INCREMENT,
  date date DEFAULT NULL,
  heure time DEFAULT NULL,
  valeur double DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT INTO capteurtemp (id, date, heure, valeur) VALUES (1, '2025-06-13', '01:41:00', 22.5), (4, '2025-06-13', '17:12:00', 21), (5, '2025-06-16', '11:42:00', 19);

-- Structure de la table led
DROP TABLE IF EXISTS led;
CREATE TABLE led (
  id int(11) NOT NULL AUTO_INCREMENT,
  etat tinyint(1) DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT INTO led (id, etat) VALUES (1, 0), (2, 1), (3, 0), (4, 1);

-- Structure de la table moteur
DROP TABLE IF EXISTS moteur;
CREATE TABLE moteur (
  id int(11) NOT NULL AUTO_INCREMENT,
  etat tinyint(1) DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT INTO moteur (id, etat) VALUES (1, 0), (2, 0);

-- Structure de la table oled
DROP TABLE IF EXISTS oled;
CREATE TABLE oled (
  id int(11) NOT NULL AUTO_INCREMENT,
  display_screen int(11) DEFAULT NULL,
  prix_parking double DEFAULT NULL,
  prix_recharge double DEFAULT NULL,
  prix_per_hour double DEFAULT NULL,
  heure datetime DEFAULT NULL,
  user varchar(100) DEFAULT NULL,
  plaque_immatriculation varchar(20) DEFAULT NULL,
  places_dispo int(11) DEFAULT NULL,
  bornes_dispo int(11) DEFAULT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT INTO oled (id, display_screen, prix_parking, prix_recharge, prix_per_hour, heure, user, plaque_immatriculation, places_dispo, bornes_dispo) VALUES (1, 1, 5, 2.5, 1, '2025-06-13 01:41:00', 'barry', 'AA-123-BB', 12, 3), (2, 2, 6, 3, 1.2, '2025-06-13 01:41:00', 'amiel', 'BB-456-CC', 9, 1), (4, 3, 5, 3, 2, '2025-06-16 11:37:00', 'marctvt@gmail.com', 'CK-413-WS', 100, 300);

-- Structure de la table users
DROP TABLE IF EXISTS users;
CREATE TABLE users (
  id int(11) NOT NULL AUTO_INCREMENT,
  email varchar(255) NOT NULL,
  password_hash varchar(255) NOT NULL,
  nom varchar(100) DEFAULT NULL,
  prenom varchar(100) DEFAULT NULL,
  created_at datetime DEFAULT current_timestamp(),
  updated_at datetime DEFAULT current_timestamp(),
  is_admin tinyint(1) DEFAULT 0,
  PRIMARY KEY (id),
  UNIQUE KEY email (email),
  KEY idx_users_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT INTO users (id, email, password_hash, nom, prenom, created_at, updated_at, is_admin) VALUES (7, 'admin@isep.fr', '$2y$10$L3cEXquXqyV5N9rLFsudZeuLNjdhpBqXw7rw3BC39F8ah7wI3XJqu', 'Administrateur', 'Système', '2025-06-14 16:18:17', '2025-06-16 02:51:20', 1), (9, 'saidkonate2004@gmail.com', '$2y$10$1505JHT3G73VfjFUDtYPVu0PvfpqQNg/wwTkB.ZPMZojRTA3Xav7u', 'KONATE', 'Saïd', '2025-06-16 00:03:58', '2025-06-17 01:08:49', 1), (11, 'test2@isep.fr', '$2y$10$uekxKKeTVv4EfX1DBMuQs.E2CRvgqU22JtNE3Ydn.IXXct5T3.Xi2', '2', 'test', '2025-06-16 03:25:26', '2025-06-16 21:24:31', 1), (12, 'user@isep.fr', '$2y$10$cLt0wRUmkWTfIDldbRIfSeqSrw1BIEJ3Tbju34VxxWcXjxD9VT95O', 'user', 'user', '2025-06-16 21:25:10', '2025-06-17 01:11:39', 0);

-- Structure de la table parking_spots
DROP TABLE IF EXISTS parking_spots;
CREATE TABLE parking_spots (
  id int(11) NOT NULL AUTO_INCREMENT,
  spot_number varchar(10) NOT NULL,
  status enum('available','occupied','maintenance','reserved') DEFAULT 'available',
  price_per_hour decimal(10,2) DEFAULT 2.50,
  has_charging_station tinyint(1) DEFAULT 0,
  sensor_data longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  created_at datetime DEFAULT current_timestamp(),
  updated_at datetime DEFAULT current_timestamp(),
  PRIMARY KEY (id),
  UNIQUE KEY spot_number (spot_number)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT INTO parking_spots (id, spot_number, status, price_per_hour, has_charging_station, sensor_data, created_at, updated_at) VALUES (1, 'A01', 'available', '2.50', 1, NULL, '2025-06-14 17:17:11', '2025-06-17 01:11:12'), (2, 'A02', 'available', '2.50', 1, NULL, '2025-06-14 17:17:11', '2025-06-14 17:17:11'), (3, 'A03', 'occupied', '2.50', 0, NULL, '2025-06-14 17:17:11', '2025-06-14 17:17:11'), (4, 'B01', 'available', '3.00', 1, NULL, '2025-06-14 17:17:11', '2025-06-14 17:17:11'), (5, 'B02', 'maintenance', '3.00', 1, NULL, '2025-06-14 17:17:11', '2025-06-17 01:11:25'), (6, 'B03', 'available', '3.00', 1, NULL, '2025-06-14 17:17:11', '2025-06-17 01:11:30'), (7, 'C01', 'available', '2.00', 0, NULL, '2025-06-14 17:17:11', '2025-06-14 17:17:11'), (8, 'C02', 'occupied', '2.00', 1, NULL, '2025-06-14 17:17:11', '2025-06-16 15:49:26');

-- Structure de la table reservations
DROP TABLE IF EXISTS reservations;
CREATE TABLE reservations (
  id int(11) NOT NULL AUTO_INCREMENT,
  user_id int(11) DEFAULT NULL,
  spot_id int(11) DEFAULT NULL,
  start_time datetime NOT NULL,
  end_time datetime NOT NULL,
  status enum('active','completed','cancelled') DEFAULT 'active',
  total_price decimal(10,2) DEFAULT NULL,
  created_at datetime DEFAULT current_timestamp(),
  updated_at datetime DEFAULT current_timestamp(),
  PRIMARY KEY (id),
  KEY user_id (user_id),
  KEY spot_id (spot_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT INTO reservations (id, user_id, spot_id, start_time, end_time, status, total_price, created_at, updated_at) VALUES (1, 9, 8, '2025-06-19 02:04:00', '2025-06-19 03:04:00', 'cancelled', NULL, '2025-06-16 00:04:52', '2025-06-16 00:06:31'), (2, 9, 2, '2025-06-16 05:11:00', '2025-06-16 09:16:00', 'active', NULL, '2025-06-16 00:07:05', '2025-06-16 00:07:05'), (3, 12, 2, '2025-06-17 03:05:00', '2025-06-17 07:05:00', 'active', NULL, '2025-06-17 01:05:46', '2025-06-17 01:05:46'), (4, 12, 7, '2025-06-17 05:12:00', '2025-06-17 05:12:00', 'active', NULL, '2025-06-17 01:12:08', '2025-06-17 01:12:08');

-- Structure de la table temperature_humidite
DROP TABLE IF EXISTS temperature_humidite;
CREATE TABLE temperature_humidite (
  id int(11) NOT NULL AUTO_INCREMENT,
  timestamp datetime NOT NULL,
  value text NOT NULL,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
INSERT INTO temperature_humidite (id, timestamp, value) VALUES (28, '2025-06-16 13:20:00', 'Temperature = 26.8      Humidité    = 25.0'), (29, '2025-06-16 13:20:02', 'Temperature = 26.6      Humidité    = 21.0'), (30, '2025-06-16 13:20:06', 'Temperature = 26.4      Humidité    = 22.0'), (31, '2025-06-16 13:20:10', 'Temperature = 26.5      Humidité    = 21.0'), (32, '2025-06-16 13:20:14', 'Temperature = 26.5      Humidité    = 25.0'), (33, '2025-06-16 13:22:07', 'Temperature = 26.6      Humidité    = 23.0'), (34, '2025-06-16 13:22:11', 'Temperature = 26.4      Humidité    = 23.0'), (35, '2025-06-16 13:22:15', 'Temperature = 26.9      Humidité    = 24.0'), (36, '2025-06-16 13:22:25', 'Temperature = 26.6      Humidité    = 24.0'), (37, '2025-06-16 13:22:27', 'Temperature = 26.6      Humidité    = 26.0'), (38, '2025-06-16 13:22:31', 'Temperature = 26.9      Humidité    = 28.0');

--
-- Contraintes pour les tables déchargées
--
ALTER TABLE reservations
  ADD CONSTRAINT reservations_spot_id_fkey FOREIGN KEY (spot_id) REFERENCES parking_spots (id) ON DELETE CASCADE,
  ADD CONSTRAINT reservations_user_id_fkey FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE;

--
-- Réactivation des contraintes de clé étrangère
--
SET FOREIGN_KEY_CHECKS=1;