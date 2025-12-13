-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 01 déc. 2025 à 21:07
-- Version du serveur : 8.4.7
-- Version de PHP : 8.3.28

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `rentcar`
--

-- --------------------------------------------------------


-- --------------------------------------------------------

--
-- Structure de la table `catalogue`
--

DROP TABLE IF EXISTS `catalogue`;
CREATE TABLE IF NOT EXISTS `catalogue` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `marque` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(150) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('Camionette','Voiture') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `annee` date NOT NULL,
  `prix` int UNSIGNED NOT NULL,
  `caution` int UNSIGNED NOT NULL,
  `volume` decimal(5,2) NOT NULL,
  `dimension` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `charge_utile` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `navigateur_gps` enum('Inclus','Non-inclus') COLLATE utf8mb4_unicode_ci NOT NULL,
  `puissance` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `transmission` enum('Electrique','Manuel','Automatique') COLLATE utf8mb4_unicode_ci NOT NULL,
  `air_co` enum('Oui','Non') COLLATE utf8mb4_unicode_ci NOT NULL,
  `carburant` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nombre_siege` int UNSIGNED NOT NULL,
  `classe_environnementale` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `km_inclus` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `catalogue`
--

INSERT INTO `catalogue` (`id`, `marque`, `slug`, `image`, `description`, `type`, `annee`, `prix`, `caution`, `volume`, `dimension`, `charge_utile`, `navigateur_gps`, `puissance`, `transmission`, `air_co`, `carburant`, `nombre_siege`, `classe_environnementale`, `km_inclus`) VALUES
(1, 'Peugeot 208', 'peugeot-208', 'peugeot-208.webp', 'Citadine compacte idéale pour la ville', 'Voiture', '2019-01-01', 45, 500, 0.30, '3.97m x 1.74m x 1.46m', 'N/A', 'Inclus', '74 kW', 'Manuel', 'Oui', 'Essence', 5, 'Euro 6d', '200 km/jour'),
(2, 'Renault Clio', 'renault-clio', 'renault-clio.webp', 'Polyvalente et économique', 'Voiture', '2019-01-01', 40, 450, 0.28, '4.05m x 1.73m x 1.45m', 'N/A', 'Non-inclus', '66 kW', 'Manuel', 'Oui', 'Diesel', 5, 'Euro 6c', '200 km/jour'),
(3, 'Volkswagen Golf', 'volkswagen-golf', 'volkswagen-golf.webp', 'Berline compacte confortable', 'Voiture', '2020-01-01', 55, 600, 0.38, '4.26m x 1.79m x 1.45m', 'N/A', 'Inclus', '85 kW', 'Automatique', 'Oui', 'Essence', 5, 'Euro 6d', '250 km/jour'),
(4, 'Toyota Corolla', 'toyota-corolla', 'toyota-corolla.webp', 'Fiable et économique', 'Voiture', '2018-01-01', 50, 550, 0.37, '4.37m x 1.79m x 1.46m', 'N/A', 'Inclus', '81 kW', 'Automatique', 'Oui', 'Hybride', 5, 'Euro 6b', '250 km/jour'),
(5, 'BMW Série 3', 'bmw-serie-3', 'bmw-serie-3.webp', 'Berline premium pour longs trajets', 'Voiture', '2019-01-01', 85, 1000, 0.48, '4.70m x 1.82m x 1.44m', 'N/A', 'Inclus', '110 kW', 'Automatique', 'Oui', 'Diesel', 5, 'Euro 6d', '300 km/jour'),
(6, 'Renault Master', 'renault-master', 'renault-master.webp', 'Camionnette utilitaire grand volume', 'Camionette', '2019-01-01', 95, 1200, 12.00, '6.20m x 2.07m x 2.50m', '1500 kg', 'Inclus', '96 kW', 'Manuel', 'Oui', 'Diesel', 3, 'Euro 6c', '200 km/jour'),
(7, 'Mercedes Sprinter', 'mercedes-sprinter', 'mercedes-sprinter.webp', 'Camionnette robuste et fiable', 'Camionette', '2018-01-01', 100, 1300, 14.00, '6.90m x 2.10m x 2.60m', '1700 kg', 'Inclus', '103 kW', 'Manuel', 'Oui', 'Diesel', 3, 'Euro 6d', '200 km/jour'),
(8, 'Ford Transit', 'ford-transit', 'ford-transit.webp', 'Camionnette polyvalente pour transport', 'Camionette', '2019-01-01', 90, 1100, 11.00, '5.98m x 2.05m x 2.50m', '1400 kg', 'Non-inclus', '92 kW', 'Manuel', 'Oui', 'Diesel', 3, 'Euro 6c', '200 km/jour'),
(9, 'Citroën Jumper', 'citroen-jumper', 'citroen-jumper.webp', 'Camionnette pratique pour déménagements', 'Camionette', '2019-01-01', 95, 1150, 13.00, '6.00m x 2.05m x 2.52m', '1600 kg', 'Inclus', '99 kW', 'Manuel', 'Oui', 'Diesel', 3, 'Euro 6d', '200 km/jour'),
(10, 'Iveco Daily', 'iveco-daily', 'iveco-daily.webp', 'Camionnette utilitaire haute capacité', 'Camionette', '2019-01-01', 110, 1400, 15.00, '7.00m x 2.10m x 2.70m', '1800 kg', 'Inclus', '110 kW', 'Automatique', 'Oui', 'Diesel', 3, 'Euro 6d', '200 km/jour');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
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
) ENGINE=InnoDB AUTO_INCREMENT=159 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `full_name`, `pseudo`, `email`, `email_token`, `email_token_expires`, `phone`, `password`, `password_token`, `password_token_expires`, `date_birth`, `gender`, `role`, `created_at`, `is_verified`) VALUES
(156, 'coroli', 'gimzed', 'agim.coroli.pro@gmail.com', NULL, NULL, '0477423505', '$2y$10$c1E0UYRKgop/oVXYbkde4u1agVt5G9OL/caWMMTp5IeM9W./uMTme', NULL, NULL, '1993-03-11', 'Feminin', 0, '2025-11-29 21:19:01', 1);

--
-- Déclencheurs `users`
--
DROP TRIGGER IF EXISTS `trg_users_after_delete`;
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

DROP TABLE IF EXISTS `users_deleted`;
CREATE TABLE IF NOT EXISTS `users_deleted` (
  `id` int NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `full_name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `users_deleted`
--

INSERT INTO `users_deleted` (`id`, `email`, `full_name`, `deleted_at`) VALUES
(141, 'agim.coroli.pro@gmail.com', 'agim coroli', '2025-11-29 20:05:01'),
(142, 'david.lefebvre801@gmail.com', 'David Lefebvre', '2025-11-29 18:46:25'),
(143, 'agim.coroli8016@gmail.com', 'Agim Coroli', '2025-11-29 19:01:19'),
(144, 'agim.coroli8016@gmail.com', 'Agim Coroli', '2025-11-29 19:03:36'),
(145, 'agim.coroli8016@gmail.com', 'Agim Coroli', '2025-11-29 19:04:33'),
(146, 'agim.coroli8016@gmail.com', 'Agim Coroli', '2025-11-29 19:05:58'),
(147, 'david.leroy952@outlook.fr', 'David Leroy', '2025-11-29 19:12:34'),
(150, 'azdazd@bialode.com', 'agim coroli', '2025-11-29 20:18:52'),
(151, 'agim.coroli.pro@gmail.com', 'agim coroli', '2025-11-29 20:14:04'),
(152, 'nicolas.lefebvre226@outlook.fr', 'Nicolas Lefebvre', '2025-11-29 20:14:32'),
(153, 'david.moreau977@example.com', 'David Moreau', '2025-11-29 20:14:31'),
(154, 'laura.garcia774@yahoo.fr', 'Laura Garcia', '2025-11-29 20:14:30'),
(155, 'nicolas.petit180@example.com', 'Nicolas Petit', '2025-11-29 20:14:21'),
(157, 'marie.lefebvre486@hotmail.com', 'Marie Lefebvre', '2025-12-01 09:42:11');

-- --------------------------------------------------------

--
-- Structure de la table `vehicule_status`
--

DROP TABLE IF EXISTS `vehicule_status`;
CREATE TABLE IF NOT EXISTS `vehicule_status` (
  `id` int UNSIGNED NOT NULL,
  `vehicule_id` int UNSIGNED NOT NULL,
  `status` enum('disponible','réservé','loué','indisponible') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'disponible',
  `commentaire` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `vehicule_id` (`vehicule_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `agenda`
--
ALTER TABLE `agenda`
  ADD CONSTRAINT `agenda_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `agenda_ibfk_2` FOREIGN KEY (`vehicule_id`) REFERENCES `catalogue` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Contraintes pour la table `vehicule_status`
--
ALTER TABLE `vehicule_status`
  ADD CONSTRAINT `vehicule_status_ibfk_1` FOREIGN KEY (`vehicule_id`) REFERENCES `catalogue` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
SET FOREIGN_KEY_CHECKS=1;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
