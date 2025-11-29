SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `full_name` varchar(100) NOT NULL,
  `pseudo` varchar(50) NOT NULL,
  `email` varchar(150) NOT NULL,
  `email_token` varchar(255) NOT NULL,
  `email_token_expires` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `phone` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(255) NOT NULL,
  `date_birth` date NOT NULL,
  `gender` enum('Masculin','Feminin') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `role` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_verified` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `phone` (`phone`),
  UNIQUE KEY `email_token` (`email_token`)
) ENGINE=InnoDB AUTO_INCREMENT=120 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

INSERT INTO `users` (`id`, `full_name`, `pseudo`, `email`, `email_token`, `email_token_expires`, `phone`, `password`, `date_birth`, `gender`, `role`, `created_at`, `is_verified`) VALUES
(117, 'agim coroli', 'gimzed', 'agim.coroli.pro@gmail.com', '', '0000-00-00 00:00:00', '0477423505', '$2y$10$fqgEyd8Y/DeGXXpblhL6luJ3OhNuUfgYoakFGLB8FwIFtpUfl8g7S', '1993-03-11', 'Masculin', 1, '2025-11-28 21:51:19', 1);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
