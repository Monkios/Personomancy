-- Script de création de la base de données
-- Monté à partir d'un dump de la BD de Mancy 2.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Structure de la table `capacite`
--

CREATE TABLE `capacite` (
  `id` int(3) UNSIGNED NOT NULL,
  `nom` varchar(100) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `voie_id` int(3) NOT NULL,
  `active` bit(1) NOT NULL DEFAULT 1,
  `supprime` bit(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

ALTER TABLE `capacite`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `capacite`
  MODIFY `id` int(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- Structure de la table `capacite_liste`
--

CREATE TABLE `capacite_liste` (
  `id` int(3) UNSIGNED NOT NULL,
  `nom` varchar(100) NOT NULL DEFAULT '',
  `active` bit(1) NOT NULL DEFAULT 1,
  `supprime` bit(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

ALTER TABLE `capacite_liste`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `capacite_liste`
  MODIFY `id` int(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- Structure de la table `capacite_liste_capacite`
--

CREATE TABLE `capacite_liste_capacite` (
  `capacite_liste_id` int(3) UNSIGNED NOT NULL,
  `capacite_id` int(3) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

ALTER TABLE `capacite_liste_capacite`
  ADD UNIQUE KEY `id_sort` (`capacite_liste_id`,`capacite_id`);

-- --------------------------------------------------------

--
-- Structure de la table `cite_etat`
--

CREATE TABLE `cite_etat` (
  `id` int(3) UNSIGNED NOT NULL,
  `nom` varchar(100) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `active` bit(1) NOT NULL DEFAULT 1,
  `supprime` bit(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

ALTER TABLE `cite_etat`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `cite_etat`
  MODIFY `id` int(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Structure de la table `connaissance`
--

CREATE TABLE `connaissance` (
  `id` int(3) UNSIGNED NOT NULL,
  `nom` varchar(100) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `cout` tinyint(1) NOT NULL DEFAULT 0,
  `voie_id` int(3) UNSIGNED NOT NULL DEFAULT 0,
  `capacite_prerequis` int(3) UNSIGNED NOT NULL DEFAULT 0,
  `voie_prerequis_prim` int(3) UNSIGNED NOT NULL DEFAULT 0,
  `voie_prerequis_sec` int(3) UNSIGNED NOT NULL DEFAULT 0,
  `active` bit(1) NOT NULL DEFAULT 1,
  `supprime` bit(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

ALTER TABLE `connaissance`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `connaissance`
  MODIFY `id` int(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=0;

-- --------------------------------------------------------

--
-- Structure de la table `croyance`
--

CREATE TABLE `croyance` (
  `id` int(3) UNSIGNED NOT NULL,
  `nom` varchar(100) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `active` bit(1) NOT NULL DEFAULT 1,
  `supprime` bit(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

ALTER TABLE `croyance`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `croyance`
  MODIFY `id` int(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Structure de la table `joueur`
--

CREATE TABLE `joueur` (
  `id` smallint(5) UNSIGNED NOT NULL,
  `login` varchar(25) NOT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `nom` varchar(100) DEFAULT '',
  `courriel` varchar(100) DEFAULT NULL,
  `salt` char(33) NOT NULL,
  `password` varchar(40) NOT NULL,
  `est_anim` bit(1) NOT NULL DEFAULT 0,
  `active` bit(1) NOT NULL DEFAULT 1,
  `date_insert` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_modify` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

ALTER TABLE `joueur`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nom` (`login`);

ALTER TABLE `joueur`
  MODIFY `id` smallint(5) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Structure de la table `personnage`
--

CREATE TABLE `personnage` (
  `id` int(3) UNSIGNED NOT NULL,
  `joueur` int(3) UNSIGNED NOT NULL DEFAULT 0,
  `nom` varchar(100) NOT NULL DEFAULT '',
  `race_id` int(3) UNSIGNED NOT NULL DEFAULT 0,
  `cite_etat_id` int(3) UNSIGNED NOT NULL DEFAULT 0,
  `croyance_id` int(3) UNSIGNED NOT NULL DEFAULT 0,
  `point_capacite_raciale` tinyint(1) NOT NULL,
  `point_experience` smallint(4) NOT NULL DEFAULT 0,
  `total_experience` smallint(4) NOT NULL DEFAULT 0,
  `est_vivant` bit(1) NOT NULL DEFAULT 1,
  `est_cree` bit(1) NOT NULL DEFAULT 0,
  `est_detruit` bit(1) NOT NULL DEFAULT 0,
  `commentaire` text NOT NULL,
  `notes` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

ALTER TABLE `personnage`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `personnage`
  MODIFY `id` int(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Structure de la table `personnage_capacite`
--

CREATE TABLE `personnage_capacite` (
  `personnage_id` int(3) UNSIGNED NOT NULL DEFAULT 0,
  `capacite_id` int(3) UNSIGNED NOT NULL DEFAULT 0,
  `niveau` enum('0','1','2','3') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

ALTER TABLE `personnage_capacite`
  ADD UNIQUE KEY `id_sort` (`personnage_id`,`capacite_id`);

--
-- Structure de la table `personnage_connaissance`
--

CREATE TABLE `personnage_connaissance` (
  `personnage_id` int(3) UNSIGNED NOT NULL DEFAULT 0,
  `connaissance_id` int(3) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

ALTER TABLE `personnage_connaissance`
  ADD UNIQUE KEY `id_sort` (`personnage_id`,`connaissance_id`);

-- --------------------------------------------------------

--
-- Structure de la table `personnage_journal`
--

CREATE TABLE `personnage_journal` (
  `id` int(10) UNSIGNED NOT NULL,
  `personnage_id` int(3) UNSIGNED NOT NULL DEFAULT 0,
  `quand` timestamp NOT NULL DEFAULT current_timestamp(),
  `combien` smallint(2) NOT NULL DEFAULT 0,
  `quoi` smallint(2) NOT NULL DEFAULT 0,
  `pourquoi` int(3) UNSIGNED NOT NULL DEFAULT 0,
  `note` varchar(255) NOT NULL DEFAULT '',
  `active` bit(1) NOT NULL DEFAULT 1,
  `backtrack` bit(1) NOT NULL DEFAULT 1,
  `joueur_id` smallint(8) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

ALTER TABLE `personnage_journal`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `personnage_journal`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Structure de la table `personnage_voie`
--

CREATE TABLE `personnage_voie` (
  `personnage_id` int(3) UNSIGNED NOT NULL DEFAULT 0,
  `voie_id` int(3) UNSIGNED NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

ALTER TABLE `personnage_voie`
  ADD UNIQUE KEY `id_sort` (`personnage_id`,`voie_id`);

-- --------------------------------------------------------

--
-- Structure de la table `race`
--

CREATE TABLE `race` (
  `id` int(3) UNSIGNED NOT NULL,
  `nom` varchar(100) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `active` bit(1) NOT NULL DEFAULT 1,
  `supprime` bit(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

ALTER TABLE `race`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `race`
  MODIFY `id` int(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

-- --------------------------------------------------------

--
-- Structure de la table `race_capacite`
--

CREATE TABLE `race_capacite` (
  `id` int(3) UNSIGNED NOT NULL,
  `nom` varchar(100) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `cout` tinyint(1) NOT NULL DEFAULT 0,
  `race_id` int(3) UNSIGNED NOT NULL DEFAULT 0,
  `capacite_bonus_id` int(3) UNSIGNED NOT NULL DEFAULT 0,
  `connaissance_bonus_id` int(3) UNSIGNED NOT NULL DEFAULT 0,
  `capacite_choix_bonus_id` int(3) UNSIGNED NOT NULL DEFAULT 0,
  `voie_bonus` int(3) UNSIGNED NOT NULL DEFAULT 0,
  `active` bit(1) NOT NULL DEFAULT 1,
  `supprime` bit(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

ALTER TABLE `race_capacite`
  ADD PRIMARY KEY (`id`);

-- --------------------------------------------------------

--
-- Structure de la table `voie`
--

CREATE TABLE `voie` (
  `id` int(3) UNSIGNED NOT NULL,
  `nom` varchar(100) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `active` bit(1) NOT NULL DEFAULT 1,
  `supprime` bit(1) NOT NULL DEFAULT 0,
  `ordre_affichage` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

ALTER TABLE `voie`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `voie`
  MODIFY `id` int(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
