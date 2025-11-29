-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : sam. 29 nov. 2025 à 20:20
-- Version du serveur : 8.4.7
-- Version de PHP : 8.3.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
 /*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
 /*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
 /*!40101 SET NAMES utf8mb4 */;

-- ✅ Création de la base si elle n’existe pas
CREATE DATABASE IF NOT EXISTS rentcar;
USE rentcar;

--
-- Base de données : `rentcar`
--

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) NOT NULL,
  `pseudo` varchar(50) NOT NULL,
  `email` varchar(150) NOT NULL,
  `email_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `email_token_expires` datetime DEFAULT CURRENT_TIMESTAMP,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(255) NOT NULL,
  `password_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `password_token_expires` datetime DEFAULT NULL,
  `date_birth` date NOT NULL,
  `gender` enum('Masculin','Feminin') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `role` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_verified` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `phone` (`phone`),
  UNIQUE KEY `email_token` (`email_token`),
  UNIQUE KEY `password_token` (`password_token`)
) ENGINE=InnoDB AUTO_INCREMENT=158 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Données de la table `users`
--

INSERT INTO `users` (`id`, `full_name`, `pseudo`, `email`, `email_token`, `email_token_expires`, `phone`, `password`, `password_token`, `password_token_expires`, `date_birth`, `gender`, `role`, `created_at`, `is_verified`) VALUES
(156, 'agim coroli', 'gimzed', 'agim.coroli.pro@gmail.com', NULL, NULL, '0477423505', '$2y$10$eys6nuGk4jn0qZGKg/GYJuURbrU9DmhvzylTcAWAJUyUftp8zmZp.', NULL, NULL, '1993-03-11', 'Feminin', 1, '2025-11-29 21:19:01', 1),
(157, 'Marie Lefebvre', 'marielefebvre448', 'marie.lefebvre486@hotmail.com', '3456aabf496ca132126057da2d398d838ddf929d1d2ceb5ab0f4eb6e5ca9caee', '2025-11-30 20:20:08', '+38258518123', '$2y$10$3iWWwK1tBcoxK63.MK2kVeBpw0XUu9n01ZtBNrc26jRtDDCndcSSG', NULL, NULL, '1984-05-20', 'Masculin', 0, '2025-11-29 21:20:08', 0);

--
-- Déclencheur `users`
--

DELIMITER $$
CREATE TRIGGER `trg_users_after_delete` AFTER DELETE ON `users` FOR EACH ROW BEGIN
    INSERT INTO users_deleted (id, email, full_name, deleted_at)
    VALUES (OLD.id, OLD.email, OLD.full_name, NOW());
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Structure de la table `users_deleted`
--

CREATE TABLE IF NOT EXISTS `users_deleted` (
  `id` int NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Données de la table `users_deleted`
--

INSERT INTO `users_deleted` (`id`, `email`, `full_name`, `deleted_at`) VALUES
(142, 'david.lefebvre801@gmail.com', 'David Lefebvre', '2025-11-29 18:46:25'),
(143, 'agim.coroli8016@gmail.com', 'Agim Coroli', '2025-11-29 19:01:19'),
(144, 'agim.coroli8016@gmail.com', 'Agim Coroli', '2025-11-29 19:03:36'),
(145, 'agim.coroli8016@gmail.com', 'Agim Coroli', '2025-11-29 19:04:33'),
(146, 'agim.coroli8016@gmail.com', 'Agim Coroli', '2025-11-29 19:05:58'),
(147, 'david.leroy952@outlook.fr', 'David Leroy', '2025-11-29 19:12:34'),
(141, 'agim.coroli.pro@gmail.com', 'agim coroli', '2025-11-29 20:05:01'),
(151, 'agim.coroli.pro@gmail.com', 'agim coroli', '2025-11-29 20:14:04'),
(155, 'nicolas.petit180@example.com', 'Nicolas Petit', '2025-11-29 20:14:21'),
(154, 'laura.garcia774@yahoo.fr', 'Laura Garcia', '2025-11-29 20:14:30'),
(153, 'david.moreau977@example.com', 'David Moreau', '2025-11-29 20:14:31'),
(152, 'nicolas.lefebvre226@outlook.fr', 'Nicolas Lefebvre', '2025-11-29 20:14:32'),
(150, 'azdazd@bialode.com', 'agim coroli', '2025-11-29 20:18:52');

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
 /*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
 /*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
