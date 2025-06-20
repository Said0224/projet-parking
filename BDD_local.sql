-- phpMyAdmin SQL Dump
-- Version modifiée pour une mise à jour propre
-- Hôte : db:3306
-- Généré le : jeu. 19 juin 2025
-- Version du serveur : 11.6.2-MariaDB-ubu2404
-- Version de PHP : 8.2.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `IxMd95C0YL_projet_par`
--

-- --------------------------------------------------------
-- SUPPRESSION DES TABLES EXISTANTES POUR EVITER LES CONFLITS
-- L'ordre est important à cause des clés étrangères.
-- --------------------------------------------------------

DROP TABLE IF EXISTS `reservations`;
DROP TABLE IF EXISTS `notifications`;
DROP TABLE IF EXISTS `parking_spots`;
DROP TABLE IF EXISTS `users`;
DROP TABLE IF EXISTS `OLED`;
DROP TABLE IF EXISTS `moteur`;
DROP TABLE IF EXISTS `LED`;
DROP TABLE IF EXISTS `capteurTemp`;
DROP TABLE IF EXISTS `capteurSon`;
DROP TABLE IF EXISTS `capteurProximite`;
DROP TABLE IF EXISTS `capteurLum`;
DROP TABLE IF EXISTS `capteurGaz`;

-- --------------------------------------------------------

--
-- Structure de la table `capteurGaz`
--

CREATE TABLE `capteurGaz` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` date DEFAULT NULL,
  `heure` time DEFAULT NULL,
  `valeur` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `capteurLum`
--

CREATE TABLE `capteurLum` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` date DEFAULT NULL,
  `heure` time DEFAULT NULL,
  `valeur` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `capteurProximite`
--

CREATE TABLE `capteurProximite` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `place` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `heure` time DEFAULT NULL,
  `valeur` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `capteurSon`
--

CREATE TABLE `capteurSon` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` date DEFAULT NULL,
  `heure` time DEFAULT NULL,
  `valeur` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `capteurTemp`
--

CREATE TABLE `capteurTemp` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `date` date DEFAULT NULL,
  `heure` time DEFAULT NULL,
  `valeur` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `LED`
--

CREATE TABLE `LED` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `etat` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `moteur`
--

CREATE TABLE `moteur` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `etat` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `notifications` (NOUVELLE STRUCTURE)
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `type` varchar(50) NOT NULL,
  `contenu` varchar(255) NOT NULL,
  `est_lu` tinyint(1) NOT NULL DEFAULT 0,
  `date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `OLED`
--

CREATE TABLE `OLED` (
  `id` int(11) NOT NULL,
  `display_screen` int(11) DEFAULT NULL,
  `prix_parking` double DEFAULT NULL,
  `prix_recharge` double DEFAULT NULL,
  `prix_per_hour` double DEFAULT NULL,
  `heure` datetime DEFAULT NULL,
  `user` varchar(100) DEFAULT NULL,
  `plaque_immatriculation` varchar(20) DEFAULT NULL,
  `places_dispo` int(11) DEFAULT NULL,
  `bornes_dispo` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `OLED`
--

INSERT INTO `OLED` (`id`, `display_screen`, `prix_parking`, `prix_recharge`, `prix_per_hour`, `heure`, `user`, `plaque_immatriculation`, `places_dispo`, `bornes_dispo`) VALUES
(1, 1, 5, 2.5, 1, '2025-06-13 01:41:00', 'admin', 'AA-123-BB', 10, 5);

-- --------------------------------------------------------

--
-- Structure de la table `parking_spots`
--

CREATE TABLE `parking_spots` (
  `id` int(11) NOT NULL,
  `spot_number` varchar(10) NOT NULL,
  `etage` int(11) NOT NULL DEFAULT 1,
  `status` enum('disponible','occupée','maintenance','réservée') NOT NULL DEFAULT 'disponible',
  `price_per_hour` decimal(10,2) DEFAULT 2.50,
  `has_charging_station` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `parking_spots`
--

INSERT INTO `parking_spots` (`id`, `spot_number`, `etage`, `status`, `price_per_hour`, `has_charging_station`, `created_at`, `updated_at`) VALUES
(1, '101', 1, 'disponible', 2.50, 0, '2025-06-16 17:53:33', '2025-06-17 22:30:57'),
(2, '102', 1, 'occupée', 2.50, 0, '2025-06-16 17:53:33', '2025-06-17 22:30:57'),
(3, '103', 1, 'disponible', 2.50, 1, '2025-06-16 17:53:33', '2025-06-17 22:30:57'),
(4, '104', 1, 'maintenance', 2.50, 0, '2025-06-16 17:53:33', '2025-06-17 22:32:22'),
(5, '105', 1, 'disponible', 2.50, 1, '2025-06-16 17:53:33', '2025-06-17 22:30:57'),
(6, '106', 1, 'disponible', 2.50, 0, '2025-06-16 17:53:33', '2025-06-17 22:30:57'),
(7, '107', 1, 'disponible', 2.50, 0, '2025-06-16 17:53:33', '2025-06-17 22:30:57'),
(8, '108', 1, 'occupée', 2.50, 1, '2025-06-16 17:53:33', '2025-06-17 22:30:57'),
(9, '109', 1, 'disponible', 2.50, 0, '2025-06-16 17:53:33', '2025-06-17 22:30:57'),
(10, '110', 1, 'disponible', 2.50, 0, '2025-06-16 17:53:33', '2025-06-17 22:30:57'),
(11, '201', 2, 'disponible', 3.00, 1, '2025-06-16 17:53:33', '2025-06-18 10:27:13'),
(12, '202', 2, 'disponible', 3.00, 1, '2025-06-16 17:53:33', '2025-06-18 10:27:13'),
(13, '203', 2, 'occupée', 3.00, 1, '2025-06-16 17:53:33', '2025-06-18 10:27:13'),
(14, '204', 2, 'disponible', 3.00, 1, '2025-06-16 17:53:33', '2025-06-18 10:27:13'),
(15, '205', 2, 'disponible', 3.00, 1, '2025-06-16 17:53:33', '2025-06-18 10:27:13'),
(16, '206', 2, 'disponible', 3.00, 0, '2025-06-16 17:53:33', '2025-06-18 10:27:13'),
(17, '207', 2, 'disponible', 3.00, 0, '2025-06-16 17:53:33', '2025-06-18 10:27:13'),
(18, '208', 2, 'maintenance', 3.00, 1, '2025-06-16 17:53:33', '2025-06-18 10:27:13'),
(19, '209', 2, 'disponible', 3.00, 1, '2025-06-16 17:53:33', '2025-06-18 10:27:13'),
(20, '210', 2, 'disponible', 3.00, 1, '2025-06-16 17:53:33', '2025-06-18 10:27:13'),
(21, '301', 3, 'disponible', 4.50, 1, '2025-06-16 17:53:33', '2025-06-18 10:27:13'),
(22, '302', 3, 'disponible', 4.50, 1, '2025-06-16 17:53:33', '2025-06-18 10:27:13'),
(23, '303', 3, 'disponible', 4.50, 1, '2025-06-16 17:53:33', '2025-06-18 10:27:13'),
(24, '304', 3, 'réservée', 4.50, 1, '2025-06-16 17:53:33', '2025-06-18 10:27:13'),
(25, '305', 3, 'disponible', 4.50, 1, '2025-06-16 17:53:33', '2025-06-18 10:27:13'),
(26, '121', 1, 'disponible', 2.50, 1, '2025-06-18 10:27:13', '2025-06-18 10:27:13'),
(27, '122', 1, 'disponible', 2.50, 0, '2025-06-18 10:27:13', '2025-06-18 10:27:13'),
(28, '123', 1, 'occupée', 2.50, 1, '2025-06-18 10:27:13', '2025-06-18 10:27:13'),
(29, '124', 1, 'disponible', 2.50, 0, '2025-06-18 10:27:13', '2025-06-18 10:27:13'),
(30, '125', 1, 'disponible', 2.50, 0, '2025-06-18 10:27:13', '2025-06-18 10:27:13'),
(31, '126', 1, 'disponible', 2.50, 1, '2025-06-18 10:27:13', '2025-06-18 10:27:13'),
(32, '127', 1, 'disponible', 2.50, 0, '2025-06-18 10:27:13', '2025-06-18 10:27:13'),
(33, '128', 1, 'réservée', 2.50, 1, '2025-06-18 10:27:13', '2025-06-18 10:27:13'),
(34, '129', 1, 'disponible', 2.50, 0, '2025-06-18 10:27:13', '2025-06-18 10:27:13'),
(35, '130', 1, 'disponible', 2.50, 0, '2025-06-18 10:27:13', '2025-06-18 10:27:13'),
(36, '131', 1, 'disponible', 2.50, 1, '2025-06-18 10:27:13', '2025-06-18 10:27:13'),
(37, '132', 1, 'disponible', 2.50, 0, '2025-06-18 10:27:13', '2025-06-18 10:27:13'),
(38, '133', 1, 'disponible', 2.50, 1, '2025-06-18 10:27:13', '2025-06-18 10:27:13'),
(39, '134', 1, 'disponible', 2.50, 0, '2025-06-18 10:27:13', '2025-06-18 10:27:13'),
(40, '135', 1, 'disponible', 2.50, 0, '2025-06-18 10:27:13', '2025-06-18 10:27:13'),
(41, '136', 1, 'disponible', 2.50, 1, '2025-06-18 10:27:13', '2025-06-18 10:27:13'),
(42, '137', 1, 'occupée', 2.50, 0, '2025-06-18 10:27:13', '2025-06-18 10:27:13'),
(43, '138', 1, 'disponible', 2.50, 1, '2025-06-18 10:27:13', '2025-06-18 10:27:13'),
(44, '139', 1, 'disponible', 2.50, 0, '2025-06-18 10:27:13', '2025-06-18 10:27:13'),
(45, '140', 1, 'maintenance', 2.50, 0, '2025-06-18 10:27:13', '2025-06-18 10:27:13'),
(46, '211', 2, 'disponible', 3.00, 1, '2025-06-18 10:27:13', '2025-06-18 10:27:13'),
(47, '212', 2, 'disponible', 3.00, 1, '2025-06-18 10:27:13', '2025-06-18 10:27:13'),
(48, '213', 2, 'occupée', 3.00, 0, '2025-06-18 10:27:13', '2025-06-18 10:27:13'),
(49, '214', 2, 'disponible', 3.00, 1, '2025-06-18 10:27:13', '2025-06-18 10:27:13'),
(50, '215', 2, 'disponible', 3.00, 1, '2025-06-18 10:27:13', '2025-06-18 10:27:13'),
(51, '216', 2, 'disponible', 3.00, 0, '2025-06-18 10:27:13', '2025-06-18 10:27:13'),
(52, '217', 2, 'disponible', 3.00, 0, '2025-06-18 10:27:13', '2025-06-18 10:27:13'),
(53, '218', 2, 'disponible', 3.00, 1, '2025-06-18 10:27:13', '2025-06-18 10:27:13'),
(54, '219', 2, 'disponible', 3.00, 1, '2025-06-18 10:27:13', '2025-06-18 10:27:13'),
(55, '220', 2, 'disponible', 3.00, 0, '2025-06-18 10:27:13', '2025-06-18 10:27:13'),
(56, '306', 3, 'disponible', 4.50, 1, '2025-06-18 10:27:13', '2025-06-18 10:27:13'),
(57, '307', 3, 'disponible', 4.50, 1, '2025-06-18 10:27:13', '2025-06-18 10:27:13'),
(58, '308', 3, 'disponible', 4.50, 0, '2025-06-18 10:27:13', '2025-06-18 10:27:13'),
(59, '309', 3, 'disponible', 4.50, 1, '2025-06-18 10:27:13', '2025-06-18 10:27:13'),
(60, '310', 3, 'disponible', 4.50, 1, '2025-06-18 10:27:13', '2025-06-18 10:27:13'),
(61, '311', 3, 'disponible', 4.50, 0, '2025-06-18 10:27:13', '2025-06-18 10:27:13'),
(62, '312', 3, 'disponible', 4.50, 0, '2025-06-18 10:27:13', '2025-06-18 10:27:13'),
(63, '313', 3, 'disponible', 4.50, 1, '2025-06-18 10:27:13', '2025-06-18 10:27:13'),
(64, '314', 3, 'disponible', 4.50, 1, '2025-06-18 10:27:13', '2025-06-18 10:27:13'),
(65, '315', 3, 'disponible', 4.50, 0, '2025-06-18 10:27:13', '2025-06-18 10:27:13'),
(66, '316', 3, 'disponible', 4.50, 0, '2025-06-18 10:27:13', '2025-06-18 10:27:13'),
(67, '317', 3, 'disponible', 4.50, 1, '2025-06-18 10:27:13', '2025-06-18 10:27:13'),
(68, '318', 3, 'disponible', 4.50, 1, '2025-06-18 10:27:13', '2025-06-18 10:27:13'),
(69, '319', 3, 'disponible', 4.50, 0, '2025-06-18 10:27:13', '2025-06-18 10:27:13'),
(70, '320', 3, 'occupée', 4.50, 1, '2025-06-18 10:27:13', '2025-06-18 10:27:13'),
(116, '111', 1, 'disponible', 2.50, 0, '2025-06-18 11:29:58', '2025-06-18 11:29:58'),
(117, '112', 1, 'disponible', 2.50, 0, '2025-06-18 11:29:58', '2025-06-18 11:29:58'),
(118, '113', 1, 'disponible', 2.50, 1, '2025-06-18 11:29:58', '2025-06-18 11:29:58'),
(119, '114', 1, 'disponible', 2.50, 1, '2025-06-18 11:29:58', '2025-06-18 11:29:58'),
(120, '115', 1, 'disponible', 2.50, 0, '2025-06-18 11:29:58', '2025-06-18 11:29:58'),
(121, '116', 1, 'disponible', 2.50, 1, '2025-06-18 11:29:58', '2025-06-18 11:29:58'),
(122, '117', 1, 'disponible', 2.50, 0, '2025-06-18 11:29:58', '2025-06-18 11:29:58'),
(123, '118', 1, 'disponible', 2.50, 1, '2025-06-18 11:29:58', '2025-06-18 11:29:58'),
(124, '119', 1, 'disponible', 2.50, 0, '2025-06-18 11:29:58', '2025-06-18 11:29:58'),
(125, '120', 1, 'disponible', 2.50, 0, '2025-06-18 11:29:58', '2025-06-18 11:29:58');

-- --------------------------------------------------------

--
-- Structure de la table `reservations`
--

CREATE TABLE `reservations` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `spot_id` int(11) DEFAULT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `status` enum('active','passée','annulée') NOT NULL DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `reservations`
--

INSERT INTO `reservations` (`id`, `user_id`, `spot_id`, `start_time`, `end_time`, `status`, `created_at`, `updated_at`) VALUES
(2, 1, 2, '2025-06-16 16:00:21', '2025-06-16 20:00:21', 'active', '2025-06-16 18:00:21', '2025-06-16 18:00:21'),
(3, 3, 11, '2025-06-19 18:00:21', '2025-06-19 22:00:21', 'active', '2025-06-16 18:00:21', '2025-06-16 18:00:21'),
(5, 7, 12, '2024-05-20 14:00:00', '2024-05-20 18:00:00', 'passée', '2025-06-16 18:00:21', '2025-06-18 12:42:29'),
(6, 1, 5, '2024-05-22 10:00:00', '2024-05-22 12:00:00', 'passée', '2025-06-16 18:00:21', '2025-06-17 22:35:55'),
(7, 4, 14, '2025-06-17 18:00:21', '2025-06-17 20:00:21', 'active', '2025-06-16 18:00:21', '2025-06-16 18:00:21'),
(9, 5, 16, '2025-06-18 10:00:00', '2025-06-27 18:00:00', 'active', '2025-06-17 12:07:53', '2025-06-17 12:07:53'),
(10, 6, 1, '2025-06-17 23:58:00', '2025-06-26 22:58:00', 'active', '2025-06-17 20:58:38', '2025-06-17 20:58:38'),
(11, 6, 25, '2025-06-18 03:02:00', '2025-06-18 06:08:00', 'annulée', '2025-06-17 22:02:41', '2025-06-17 22:35:55');

-- --------------------------------------------------------

--
-- Structure de la table `users` (STRUCTURE MISE À JOUR)
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `nom` varchar(255) DEFAULT NULL,
  `prenom` varchar(255) DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 0,
  `recevoir_mails` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `email`, `password_hash`, `nom`, `prenom`, `is_admin`, `recevoir_mails`, `created_at`, `updated_at`) VALUES
(1, 'admin@isep.fr', '$2y$10$L4k2mAIyjDQv3iCHDfdD8.ZYv71jHWVfwHirlqiekqqmnN3MM17I2', 'Administrateur', 'Système', 1, 1, '2025-06-16 01:04:38', '2025-06-19 03:07:02'),
(3, 'jean.dupont@isep.fr', '$2y$10$92IXfKGDJsohJ1/33Ggh8.mWZtS2v.u3J1.Lh2n.wcy.amx21vW4m', 'Babouri', 'Samy', 0, 1, '2025-06-16 18:26:49', '2025-06-16 18:26:49'),
(4, 'marie.curie@isep.fr', '$2y$10$92IXfKGDJsohJ1/33Ggh8.mWZtS2v.u3J1.Lh2n.wcy.amx21vW4m', 'Monsieur', 'Barry', 1, 1, '2025-06-16 18:26:49', '2025-06-18 13:00:46'),
(5, 'vincent.girard@eleve.isep.fr', '$2y$10$f95j/HBtpJmi60cDy4VE1.AnNeWZuSWdtkkF6pZuRJl6mQ8KP3/Gy', 'Girard', 'Vincent', 0, 1, '2025-06-17 12:07:10', '2025-06-17 12:07:14'),
(6, 'saidkonate2004@gmail.com', '$2y$10$c3vK9jhRQ6td0WtvGD0OEOCGAUznEjenPcrBm.DsclauxiZJaTzp2', 'KONATE', 'Saïd', 0, 1, '2025-06-17 20:58:01', '2025-06-18 15:47:59'),
(7, 'test@isep.fr', '$2y$10$hyyTmaYyhwml.MajKQFAGO96WsNqWu9aWfs6LgHk4xwuzwdTll0wS', 'test', 'compte', 0, 1, '2025-06-17 22:28:03', '2025-06-19 01:57:24'),
(8, 'guillaume.f4uvet@gmail.com', '$2y$10$bL2WwdEGLCvDg5eOSl3AWuyCFIlid7i5DunOLWHk/PxxZp7Nn7zmq', 'Fauvet', 'Guillaume', 0, 1, '2025-06-19 00:05:12', '2025-06-19 00:05:22'),
(10, 'alain.provist93@gmail.com', '$2y$10$GwEJtYWeik.MfkSOIbLQKeERkrDxksOfJo9Co5XlFvMQyVaiA5e3O', 'Provist', 'Alain', 0, 1, '2025-06-19 01:18:22', '2025-06-19 01:21:12');

--
-- Index pour les tables déchargées
--

ALTER TABLE `capteurGaz` ADD PRIMARY KEY (`id`);
ALTER TABLE `capteurLum` ADD PRIMARY KEY (`id`);
ALTER TABLE `capteurProximite` ADD PRIMARY KEY (`id`);
ALTER TABLE `capteurSon` ADD PRIMARY KEY (`id`);
ALTER TABLE `capteurTemp` ADD PRIMARY KEY (`id`);
ALTER TABLE `LED` ADD PRIMARY KEY (`id`);
ALTER TABLE `moteur` ADD PRIMARY KEY (`id`);
ALTER TABLE `notifications` ADD PRIMARY KEY (`id`), ADD KEY `user_id` (`user_id`);
ALTER TABLE `OLED` ADD PRIMARY KEY (`id`);
ALTER TABLE `parking_spots` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `spot_number` (`spot_number`);
ALTER TABLE `reservations` ADD PRIMARY KEY (`id`), ADD KEY `user_id` (`user_id`), ADD KEY `spot_id` (`spot_id`);
ALTER TABLE `users` ADD PRIMARY KEY (`id`), ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

ALTER TABLE `capteurGaz` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `capteurLum` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `capteurProximite` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `capteurSon` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `capteurTemp` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `LED` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `moteur` MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;
ALTER TABLE `notifications` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `OLED` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
ALTER TABLE `parking_spots` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=163;
ALTER TABLE `reservations` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
ALTER TABLE `users` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Contraintes pour les tables déchargées
--

ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

ALTER TABLE `reservations`
  ADD CONSTRAINT `reservations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reservations_ibfk_2` FOREIGN KEY (`spot_id`) REFERENCES `parking_spots` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;