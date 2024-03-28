-- Script de création de la base de données
-- Monté à partir d'un dump de la BD de Mancy 2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------

--
-- Suppression des tables
--

DROP TABLE IF EXISTS `personnage_capacite`;
DROP TABLE IF EXISTS `personnage_connaissance`;
DROP TABLE IF EXISTS `personnage_journal`;
DROP TABLE IF EXISTS `personnage_voie`;
DROP TABLE IF EXISTS `personnage`;
DROP TABLE IF EXISTS `race_capacite`;
DROP TABLE IF EXISTS `connaissance`;
DROP TABLE IF EXISTS `capacite_liste_capacite`;
DROP TABLE IF EXISTS `capacite_liste`;
DROP TABLE IF EXISTS `capacite`;
DROP TABLE IF EXISTS `cite_etat`;
DROP TABLE IF EXISTS `croyance`;
DROP TABLE IF EXISTS `joueur`;
DROP TABLE IF EXISTS `race`;
DROP TABLE IF EXISTS `voie`;

-- --------------------------------------------------------

--
-- Structure de la table `capacite`
--
CREATE TABLE `capacite` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `voie_id` int UNSIGNED NOT NULL,
  `active` tinyint NOT NULL DEFAULT 1,
  `supprime` tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Structure de la table `capacite_liste`
--

CREATE TABLE `capacite_liste` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL DEFAULT '',
  `active` tinyint NOT NULL DEFAULT 1,
  `supprime` tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Structure de la table `capacite_liste_capacite`
--

CREATE TABLE `capacite_liste_capacite` (
  `capacite_liste_id` int UNSIGNED NOT NULL,
  `capacite_id` int UNSIGNED NOT NULL,
  UNIQUE KEY `id_sort` (`capacite_liste_id`,`capacite_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Structure de la table `cite_etat`
--

CREATE TABLE `cite_etat` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `active` tinyint NOT NULL DEFAULT 1,
  `supprime` tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Structure de la table `connaissance`
--

CREATE TABLE `connaissance` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `cout` tinyint NOT NULL DEFAULT 0,
  `prereq_capacite` int UNSIGNED NULL,
  `prereq_voie_primaire` int UNSIGNED NOT NULL,
  `prereq_voie_secondaire` int UNSIGNED NULL,
  `active` tinyint NOT NULL DEFAULT 1,
  `supprime` tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Structure de la table `croyance`
--

CREATE TABLE `croyance` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `active` tinyint NOT NULL DEFAULT 1,
  `supprime` tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Structure de la table `joueur`
--

CREATE TABLE `joueur` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `prenom` varchar(100) DEFAULT NULL,
  `nom` varchar(100) DEFAULT '',
  `courriel` varchar(100) DEFAULT NULL,
  `salt` char(33) NOT NULL,
  `password` varchar(40) NOT NULL,
  `est_animateur` tinyint NOT NULL DEFAULT 0,
  `est_administrateur` tinyint NOT NULL DEFAULT 0,
  `active` tinyint NOT NULL DEFAULT 0,
  `date_insert` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_modify` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Structure de la table `personnage`
--

CREATE TABLE `personnage` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `joueur` int UNSIGNED NOT NULL,
  `nom` varchar(100) NOT NULL DEFAULT '',
  `race_id` int UNSIGNED NOT NULL,
  `cite_etat_id` int UNSIGNED NOT NULL,
  `croyance_id` int UNSIGNED NOT NULL,
  `point_capacite_raciale` tinyint NOT NULL,
  `point_experience` smallint NOT NULL DEFAULT 0,
  `total_experience` smallint NOT NULL DEFAULT 0,
  `est_vivant` tinyint NOT NULL DEFAULT 1,
  `est_cree` tinyint NOT NULL DEFAULT 0,
  `est_detruit` tinyint NOT NULL DEFAULT 0,
  `commentaire` text NOT NULL,
  `notes` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Structure de la table `personnage_capacite`
--

CREATE TABLE `personnage_capacite` (
  `personnage_id` int UNSIGNED NOT NULL,
  `capacite_id` int UNSIGNED NOT NULL,
  `niveau` enum('0','1','2','3') NOT NULL DEFAULT '0',
  UNIQUE KEY (`personnage_id`,`capacite_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Structure de la table `personnage_connaissance`
--

CREATE TABLE `personnage_connaissance` (
  `personnage_id` int UNSIGNED NOT NULL,
  `connaissance_id` int UNSIGNED NOT NULL,
  UNIQUE KEY (`personnage_id`,`connaissance_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Structure de la table `personnage_journal`
--

CREATE TABLE `personnage_journal` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `personnage_id` int UNSIGNED NOT NULL,
  `quand` timestamp NOT NULL DEFAULT current_timestamp(),
  `combien` smallint NOT NULL DEFAULT 0,
  `quoi` int NOT NULL DEFAULT 0,
  `pourquoi` int UNSIGNED NOT NULL DEFAULT 0,
  `note` varchar(255) NOT NULL DEFAULT '',
  `active` tinyint NOT NULL DEFAULT 1,
  `backtrack` tinyint NOT NULL DEFAULT 1,
  `joueur_id` int UNSIGNED NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Structure de la table `personnage_voie`
--

CREATE TABLE `personnage_voie` (
  `personnage_id` int UNSIGNED NOT NULL,
  `voie_id` int UNSIGNED NOT NULL,
  UNIQUE KEY (`personnage_id`,`voie_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Structure de la table `race`
--

CREATE TABLE `race` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `active` tinyint NOT NULL DEFAULT 1,
  `supprime` tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Structure de la table `race_capacite`
--

CREATE TABLE `race_capacite` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `cout` enum('1','2','3','4') NOT NULL DEFAULT '1',
  `race_id` int UNSIGNED NULL,
  `capacite_bonus_id` int UNSIGNED NULL,
  `connaissance_bonus_id` int UNSIGNED NULL,
  `capacite_choix_bonus_id` int UNSIGNED NULL,
  `voie_bonus` int UNSIGNED NULL,
  `active` tinyint NOT NULL DEFAULT 1,
  `supprime` tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Structure de la table `voie`
--

CREATE TABLE `voie` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `active` tinyint NOT NULL DEFAULT 1,
  `supprime` tinyint NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Relations entre les tables
--

ALTER TABLE `capacite`
  ADD FOREIGN KEY (`voie_id`) REFERENCES `voie` (`id`);
ALTER TABLE `capacite_liste_capacite`
  ADD FOREIGN KEY (`capacite_liste_id`) REFERENCES `capacite_liste` (`id`);
ALTER TABLE `capacite_liste_capacite`
  ADD FOREIGN KEY (`capacite_id`) REFERENCES `capacite` (`id`);
ALTER TABLE `connaissance`
  ADD FOREIGN KEY (`prereq_capacite`) REFERENCES `capacite` (`id`);
ALTER TABLE `connaissance`
  ADD FOREIGN KEY (`prereq_voie_primaire`) REFERENCES `voie` (`id`);
ALTER TABLE `connaissance`
  ADD FOREIGN KEY (`prereq_voie_secondaire`) REFERENCES `voie` (`id`);
ALTER TABLE `personnage`
  ADD FOREIGN KEY (`joueur`) REFERENCES `joueur` (`id`);
ALTER TABLE `personnage`
  ADD FOREIGN KEY (`race_id`) REFERENCES `race` (`id`);
ALTER TABLE `personnage`
  ADD FOREIGN KEY (`cite_etat_id`) REFERENCES `cite_etat` (`id`);
ALTER TABLE `personnage`
  ADD FOREIGN KEY (`croyance_id`) REFERENCES `croyance` (`id`);
ALTER TABLE `personnage_capacite`
  ADD FOREIGN KEY (`personnage_id`) REFERENCES `personnage` (`id`);
ALTER TABLE `personnage_capacite`
  ADD FOREIGN KEY (`capacite_id`) REFERENCES `capacite` (`id`);
ALTER TABLE `personnage_connaissance`
  ADD FOREIGN KEY (`personnage_id`) REFERENCES `personnage` (`id`);
ALTER TABLE `personnage_connaissance`
  ADD FOREIGN KEY (`connaissance_id`) REFERENCES `connaissance` (`id`);
ALTER TABLE `personnage_journal`
  ADD FOREIGN KEY (`personnage_id`) REFERENCES `personnage` (`id`);
ALTER TABLE `personnage_journal`
  ADD FOREIGN KEY (`joueur_id`) REFERENCES `joueur` (`id`);
ALTER TABLE `personnage_voie`
  ADD FOREIGN KEY (`personnage_id`) REFERENCES `personnage` (`id`);
ALTER TABLE `personnage_voie`
  ADD FOREIGN KEY (`voie_id`) REFERENCES `voie` (`id`);
ALTER TABLE `race_capacite`
  ADD FOREIGN KEY (`race_id`) REFERENCES `race` (`id`);
ALTER TABLE `race_capacite`
  ADD FOREIGN KEY (`capacite_bonus_id`) REFERENCES `capacite` (`id`);
ALTER TABLE `race_capacite`
  ADD FOREIGN KEY (`connaissance_bonus_id`) REFERENCES `connaissance` (`id`);
ALTER TABLE `race_capacite`
  ADD FOREIGN KEY (`capacite_choix_bonus_id`) REFERENCES `capacite_liste` (`id`);
ALTER TABLE `race_capacite`
  ADD FOREIGN KEY (`voie_bonus`) REFERENCES `voie` (`id`);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
