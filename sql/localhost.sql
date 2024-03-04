-- phpMyAdmin SQL Dump

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;


--
-- Structure de la table `alignement`
--

CREATE TABLE `alignement` (
  `id` int(3) UNSIGNED NOT NULL,
  `nom` varchar(100) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `active` enum('0','1') NOT NULL DEFAULT '0',
  `supprime` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `capacite`
--

CREATE TABLE `capacite` (
  `id` int(3) UNSIGNED NOT NULL,
  `nom` varchar(100) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `voie` int(3) NOT NULL,
  `active` enum('0','1') NOT NULL DEFAULT '0',
  `supprime` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Structure de la table `capacite_capacite_categorie`
--

CREATE TABLE `capacite_capacite_categorie` (
  `id_capacite` int(3) NOT NULL DEFAULT 0,
  `id_capacite_categorie` int(3) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `capacite_categorie`
--

CREATE TABLE `capacite_categorie` (
  `id` int(3) UNSIGNED NOT NULL,
  `nom` varchar(100) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `active` enum('0','1') NOT NULL DEFAULT '0',
  `supprime` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `connaissance`
--

CREATE TABLE `connaissance` (
  `id` int(3) UNSIGNED NOT NULL,
  `nom` varchar(100) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `statistique` int(1) NOT NULL DEFAULT 0,
  `statistique_niveau` int(2) NOT NULL DEFAULT 0,
  `statistique_sec` int(1) NOT NULL DEFAULT 0,
  `statistique_sec_niveau` int(2) NOT NULL DEFAULT 0,
  `capacite` int(3) NOT NULL DEFAULT 0,
  `capacite_niveau` int(2) NOT NULL DEFAULT 0,
  `capacite_sec` int(3) NOT NULL DEFAULT 0,
  `capacite_sec_niveau` int(2) NOT NULL DEFAULT 0,
  `connaissance` int(3) NOT NULL DEFAULT 0,
  `connaissance_sec` int(3) NOT NULL DEFAULT 0,
  `voie` int(3) NOT NULL DEFAULT 0,
  `divinite` int(3) NOT NULL DEFAULT 0,
  `active` enum('0','1') NOT NULL DEFAULT '0',
  `supprime` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `connaissance_categorie`
--

CREATE TABLE `connaissance_categorie` (
  `id` int(3) UNSIGNED NOT NULL,
  `nom` varchar(100) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `active` enum('0','1') NOT NULL DEFAULT '0',
  `supprime` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `connaissance_connaissance_categorie`
--

CREATE TABLE `connaissance_connaissance_categorie` (
  `id_connaissance` int(3) NOT NULL DEFAULT 0,
  `id_connaissance_categorie` int(3) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `divinite`
--

CREATE TABLE `divinite` (
  `id` int(3) UNSIGNED NOT NULL,
  `nom` varchar(100) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `dogme` varchar(100) NOT NULL DEFAULT '',
  `avatar` varchar(100) NOT NULL DEFAULT '',
  `pantheon` int(3) UNSIGNED NOT NULL DEFAULT 0,
  `active` enum('0','1') NOT NULL DEFAULT '0',
  `supprime` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `faction`
--

CREATE TABLE `faction` (
  `id` int(3) UNSIGNED NOT NULL,
  `nom` varchar(100) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `active` enum('0','1') NOT NULL DEFAULT '0',
  `supprime` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `joueur`
--

CREATE TABLE `joueur` (
  `id` smallint(8) UNSIGNED NOT NULL,
  `login` varchar(25) NOT NULL,
  `prenom` varchar(100) DEFAULT NULL,
  `nom` varchar(100) DEFAULT '',
  `courriel` varchar(100) DEFAULT NULL,
  `date_naissance` date NOT NULL,
  `salt` char(33) NOT NULL,
  `password` varchar(40) NOT NULL COMMENT 'Les mots de passes salés',
  `old_password` varchar(40) NOT NULL COMMENT 'Les mots de passes non-salés supprimés tranquillement',
  `passe_saison` enum('0','1') NOT NULL DEFAULT '0',
  `est_anim` enum('0','1') NOT NULL DEFAULT '0',
  `est_admin` enum('0','1') NOT NULL DEFAULT '0',
  `active` enum('0','1') NOT NULL DEFAULT '1',
  `date_insert` timestamp NOT NULL DEFAULT current_timestamp(),
  `date_modify` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Déchargement des données de la table `joueur`
--

INSERT INTO `joueur` (`id`, `login`, `prenom`, `nom`, `courriel`, `date_naissance`, `salt`, `password`, `old_password`, `passe_saison`, `est_anim`, `est_admin`, `active`, `date_insert`, `date_modify`) VALUES
(0, 'Personnomancy', 'Mancy', '', NULL, '0000-00-00', '', '', '', '0', '0', '0', '1', '2018-05-14 17:35:35', '2018-05-14 17:35:35');

-- --------------------------------------------------------

--
-- Structure de la table `pantheon`
--

CREATE TABLE `pantheon` (
  `id` int(3) UNSIGNED NOT NULL,
  `nom` varchar(100) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `active` enum('0','1') NOT NULL DEFAULT '0',
  `supprime` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `personnage`
--

CREATE TABLE `personnage` (
  `id` int(3) UNSIGNED NOT NULL,
  `joueur` int(3) NOT NULL DEFAULT 0,
  `nom` varchar(100) NOT NULL DEFAULT '',
  `race` int(3) NOT NULL DEFAULT 0,
  `religion` int(3) NOT NULL DEFAULT 0,
  `alignement` int(3) NOT NULL DEFAULT 0,
  `faction` int(3) DEFAULT NULL,
  `prestige` int(3) NOT NULL,
  `st_alerte` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `st_constitution` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `st_spiritisme` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `st_intelligence` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `st_vigueur` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `st_volonte` tinyint(1) UNSIGNED NOT NULL DEFAULT 0,
  `point_capacite_raciale` tinyint(1) NOT NULL,
  `point_experience` int(4) NOT NULL DEFAULT 0,
  `total_experience` int(4) NOT NULL DEFAULT 0,
  `est_vivant` enum('0','1') NOT NULL DEFAULT '1',
  `est_cree` enum('0','1') NOT NULL DEFAULT '0',
  `est_detruit` enum('0','1') NOT NULL DEFAULT '0',
  `commentaire` text NOT NULL,
  `notes` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `personnage_capacite`
--

CREATE TABLE `personnage_capacite` (
  `id_personnage` int(3) NOT NULL DEFAULT 0,
  `id_capacite` int(3) NOT NULL DEFAULT 0,
  `niveau` enum('0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Structure de la table `personnage_connaissance`
--

CREATE TABLE `personnage_connaissance` (
  `id_personnage` int(3) NOT NULL DEFAULT 0,
  `id_connaissance` int(3) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `personnage_connaissance_categorie`
--

CREATE TABLE `personnage_connaissance_categorie` (
  `id_personnage` int(3) NOT NULL DEFAULT 0,
  `id_connaissance_categorie` int(3) NOT NULL DEFAULT 0,
  `nombre` enum('0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `personnage_journal`
--

CREATE TABLE `personnage_journal` (
  `id` int(10) UNSIGNED NOT NULL,
  `id_personnage` int(3) NOT NULL DEFAULT 0,
  `quand` timestamp NOT NULL DEFAULT current_timestamp(),
  `combien` smallint(2) NOT NULL DEFAULT 0,
  `quoi` smallint(2) NOT NULL DEFAULT 0,
  `pourquoi` int(3) NOT NULL DEFAULT 0,
  `note` varchar(255) NOT NULL DEFAULT '',
  `active` enum('0','1') NOT NULL DEFAULT '1',
  `backtrack` enum('0','1') NOT NULL DEFAULT '1',
  `joueur_id` smallint(8) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `personnage_pouvoir`
--

CREATE TABLE `personnage_pouvoir` (
  `id_personnage` int(3) NOT NULL DEFAULT 0,
  `id_pouvoir` int(3) NOT NULL DEFAULT 0,
  `nombre` enum('0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `personnage_pouvoir_categorie`
--

CREATE TABLE `personnage_pouvoir_categorie` (
  `id_personnage` int(3) NOT NULL DEFAULT 0,
  `id_pouvoir_categorie` int(3) NOT NULL DEFAULT 0,
  `nombre` enum('0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15','16','17','18','19','20','21','22','23','24','25','26','27','28','29','30') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `personnage_voie`
--

CREATE TABLE `personnage_voie` (
  `id_personnage` int(3) NOT NULL DEFAULT 0,
  `id_voie` int(3) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `pouvoir`
--

CREATE TABLE `pouvoir` (
  `id` int(3) UNSIGNED NOT NULL,
  `nom` varchar(100) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `affiche` enum('0','1') NOT NULL DEFAULT '0',
  `bon_alerte` enum('-5','-4','-3','-2','-1','0','1','2','3','4','5') NOT NULL DEFAULT '0',
  `bon_constitution` enum('-5','-4','-3','-2','-1','0','1','2','3','4','5') NOT NULL DEFAULT '0',
  `bon_intelligence` enum('-5','-4','-3','-2','-1','0','1','2','3','4','5') NOT NULL DEFAULT '0',
  `bon_spiritisme` enum('-5','-4','-3','-2','-1','0','1','2','3','4','5') NOT NULL DEFAULT '0',
  `bon_vigueur` enum('-5','-4','-3','-2','-1','0','1','2','3','4','5') NOT NULL DEFAULT '0',
  `bon_volonte` enum('-5','-4','-3','-2','-1','0','1','2','3','4','5') NOT NULL DEFAULT '0',
  `bon_pVie` enum('-5','-4','-3','-2','-1','0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15') NOT NULL DEFAULT '0',
  `bon_pMagie` enum('-5','-4','-3','-2','-1','0','1','2','3','4','5','6','7','8','9','10','11','12','13','14','15') NOT NULL DEFAULT '0',
  `bon_fDivine` enum('-5','-4','-3','-2','-1','0','1','2','3','4','5') NOT NULL DEFAULT '0',
  `bon_fMagique` enum('-5','-4','-3','-2','-1','0','1','2','3','4','5') NOT NULL DEFAULT '0',
  `bon_sVigueur` enum('-5','-4','-3','-2','-1','0','1','2','3','4','5') NOT NULL DEFAULT '0',
  `bon_sVolonte` enum('-5','-4','-3','-2','-1','0','1','2','3','4','5') NOT NULL DEFAULT '0',
  `active` enum('0','1') NOT NULL DEFAULT '0',
  `supprime` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `pouvoir_capacite`
--

CREATE TABLE `pouvoir_capacite` (
  `id_pouvoir` int(3) NOT NULL DEFAULT 0,
  `id_capacite` int(3) NOT NULL DEFAULT 0,
  `niveau` enum('0','1','2','3','4','5') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `pouvoir_capacite_categorie`
--

CREATE TABLE `pouvoir_capacite_categorie` (
  `id_pouvoir` int(3) NOT NULL DEFAULT 0,
  `id_capacite_categorie` int(3) NOT NULL DEFAULT 0,
  `nombre` enum('0','1','2','3','4','5','6','7','8','9') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `pouvoir_categorie`
--

CREATE TABLE `pouvoir_categorie` (
  `id` int(3) UNSIGNED NOT NULL,
  `nom` varchar(100) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `active` enum('0','1') NOT NULL DEFAULT '0',
  `supprime` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `pouvoir_connaissance`
--

CREATE TABLE `pouvoir_connaissance` (
  `id_pouvoir` int(3) NOT NULL DEFAULT 0,
  `id_connaissance` int(3) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `pouvoir_connaissance_categorie`
--

CREATE TABLE `pouvoir_connaissance_categorie` (
  `id_pouvoir` int(3) NOT NULL DEFAULT 0,
  `id_connaissance_categorie` int(3) NOT NULL DEFAULT 0,
  `nombre` enum('0','1','2','3','4','5','6','7','8','9') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `pouvoir_pouvoir`
--

CREATE TABLE `pouvoir_pouvoir` (
  `id_pouvoir` int(3) NOT NULL DEFAULT 0,
  `id_pouvoir_categorie` int(3) NOT NULL DEFAULT 0,
  `nombre` enum('0','1','2','3','4','5','6','7','8','9') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `pouvoir_pouvoir_categorie`
--

CREATE TABLE `pouvoir_pouvoir_categorie` (
  `id_pouvoir` int(3) NOT NULL DEFAULT 0,
  `id_pouvoir_categorie` int(3) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `pouvoir_sort`
--

CREATE TABLE `pouvoir_sort` (
  `id_pouvoir` int(3) NOT NULL DEFAULT 0,
  `id_sort` int(3) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `pouvoir_voie`
--

CREATE TABLE `pouvoir_voie` (
  `id_pouvoir` int(3) NOT NULL DEFAULT 0,
  `id_voie` int(3) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `prestige`
--

CREATE TABLE `prestige` (
  `id` int(3) UNSIGNED NOT NULL,
  `nom` varchar(100) NOT NULL,
  `voie_id` int(3) NOT NULL,
  `active` enum('0','1') NOT NULL,
  `supprime` enum('0','1') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `race`
--

CREATE TABLE `race` (
  `id` int(3) UNSIGNED NOT NULL,
  `nom` varchar(100) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `base_alerte` enum('0','1','2','3','4','5','6','7','8','9','10') NOT NULL DEFAULT '0',
  `base_constitution` enum('0','1','2','3','4','5','6','7','8','9','10') NOT NULL DEFAULT '0',
  `base_intelligence` enum('0','1','2','3','4','5','6','7','8','9','10') NOT NULL DEFAULT '0',
  `base_spiritisme` enum('0','1','2','3','4','5','6','7','8','9','10') NOT NULL DEFAULT '0',
  `base_vigueur` enum('0','1','2','3','4','5','6','7','8','9','10') NOT NULL DEFAULT '0',
  `base_volonte` enum('0','1','2','3','4','5','6','7','8','9','10') NOT NULL DEFAULT '0',
  `active` enum('0','1') NOT NULL DEFAULT '0',
  `supprime` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `race_pouvoir`
--

CREATE TABLE `race_pouvoir` (
  `id_race` int(3) NOT NULL DEFAULT 0,
  `id_pouvoir` int(3) NOT NULL DEFAULT 0,
  `cout` enum('0','1','2','3','4','5') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sort`
--

CREATE TABLE `sort` (
  `id` int(3) UNSIGNED NOT NULL,
  `nom` varchar(100) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `id_sphere` int(3) NOT NULL,
  `mot_pouvoir` varchar(100) NOT NULL DEFAULT '',
  `active` enum('0','1') NOT NULL DEFAULT '0',
  `supprime` enum('0','1') NOT NULL DEFAULT '0',
  `niveau` enum('0','1','2','3','4','5') NOT NULL DEFAULT '1',
  `duree_id` smallint(2) NOT NULL DEFAULT 0,
  `portee_id` smallint(2) NOT NULL DEFAULT 0,
  `champ_id` smallint(2) NOT NULL DEFAULT 0,
  `sauvegarde_id` smallint(2) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sort_champ`
--

CREATE TABLE `sort_champ` (
  `id` int(3) UNSIGNED NOT NULL,
  `description` varchar(100) NOT NULL DEFAULT '',
  `active` enum('0','1') NOT NULL DEFAULT '0',
  `supprime` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sort_duree`
--

CREATE TABLE `sort_duree` (
  `id` int(3) UNSIGNED NOT NULL,
  `description` varchar(100) NOT NULL DEFAULT '',
  `active` enum('0','1') NOT NULL DEFAULT '0',
  `supprime` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sort_portee`
--

CREATE TABLE `sort_portee` (
  `id` int(3) UNSIGNED NOT NULL,
  `description` varchar(100) NOT NULL DEFAULT '',
  `active` enum('0','1') NOT NULL DEFAULT '0',
  `supprime` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sort_sauvegarde`
--

CREATE TABLE `sort_sauvegarde` (
  `id` int(3) UNSIGNED NOT NULL,
  `description` varchar(100) NOT NULL DEFAULT '',
  `active` enum('0','1') NOT NULL DEFAULT '0',
  `supprime` enum('0','1') NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `voie`
--

CREATE TABLE `voie` (
  `id` int(3) UNSIGNED NOT NULL,
  `nom` varchar(100) NOT NULL DEFAULT '',
  `description` text NOT NULL,
  `active` enum('0','1') NOT NULL DEFAULT '0',
  `supprime` enum('0','1') NOT NULL DEFAULT '0',
  `ordre_affichage` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8mb3_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `alignement`
--
ALTER TABLE `alignement`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `capacite`
--
ALTER TABLE `capacite`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `capacite_capacite_categorie`
--
ALTER TABLE `capacite_capacite_categorie`
  ADD UNIQUE KEY `id_sort` (`id_capacite`,`id_capacite_categorie`);

--
-- Index pour la table `capacite_categorie`
--
ALTER TABLE `capacite_categorie`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `connaissance`
--
ALTER TABLE `connaissance`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `connaissance_categorie`
--
ALTER TABLE `connaissance_categorie`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `connaissance_connaissance_categorie`
--
ALTER TABLE `connaissance_connaissance_categorie`
  ADD UNIQUE KEY `id_sort` (`id_connaissance`,`id_connaissance_categorie`);

--
-- Index pour la table `divinite`
--
ALTER TABLE `divinite`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nom` (`nom`);

--
-- Index pour la table `experience`
--
ALTER TABLE `experience`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Index pour la table `faction`
--
ALTER TABLE `faction`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nom` (`nom`);

--
-- Index pour la table `joueur`
--
ALTER TABLE `joueur`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nom` (`login`),
  ADD UNIQUE KEY `id_2` (`id`),
  ADD KEY `id` (`id`);

--
-- Index pour la table `joueur_experience`
--
ALTER TABLE `joueur_experience`
  ADD UNIQUE KEY `joueur_id` (`joueur_id`,`experience_id`);

--
-- Index pour la table `pantheon`
--
ALTER TABLE `pantheon`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `personnage`
--
ALTER TABLE `personnage`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `id` (`id`);

--
-- Index pour la table `personnage_capacite`
--
ALTER TABLE `personnage_capacite`
  ADD UNIQUE KEY `id_sort` (`id_personnage`,`id_capacite`);

--
-- Index pour la table `personnage_capacite_categorie`
--
ALTER TABLE `personnage_capacite_categorie`
  ADD UNIQUE KEY `id_sort` (`id_personnage`,`id_capacite_categorie`);

--
-- Index pour la table `personnage_connaissance`
--
ALTER TABLE `personnage_connaissance`
  ADD UNIQUE KEY `id_sort` (`id_personnage`,`id_connaissance`);

--
-- Index pour la table `personnage_connaissance_categorie`
--
ALTER TABLE `personnage_connaissance_categorie`
  ADD UNIQUE KEY `id_sort` (`id_personnage`,`id_connaissance_categorie`);

--
-- Index pour la table `personnage_journal`
--
ALTER TABLE `personnage_journal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_personnage` (`id_personnage`);

--
-- Index pour la table `personnage_pouvoir`
--
ALTER TABLE `personnage_pouvoir`
  ADD UNIQUE KEY `id_sort` (`id_personnage`,`id_pouvoir`);

--
-- Index pour la table `personnage_pouvoir_categorie`
--
ALTER TABLE `personnage_pouvoir_categorie`
  ADD UNIQUE KEY `id_sort` (`id_personnage`,`id_pouvoir_categorie`);

--
-- Index pour la table `personnage_sort`
--
ALTER TABLE `personnage_sort`
  ADD UNIQUE KEY `id_sort` (`id_personnage`,`id_sort`);

--
-- Index pour la table `personnage_voie`
--
ALTER TABLE `personnage_voie`
  ADD UNIQUE KEY `id_sort` (`id_personnage`,`id_voie`);

--
-- Index pour la table `pouvoir`
--
ALTER TABLE `pouvoir`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `pouvoir_capacite`
--
ALTER TABLE `pouvoir_capacite`
  ADD UNIQUE KEY `id_pouvoir` (`id_pouvoir`,`id_capacite`);

--
-- Index pour la table `pouvoir_capacite_categorie`
--
ALTER TABLE `pouvoir_capacite_categorie`
  ADD UNIQUE KEY `id_pouvoir` (`id_pouvoir`,`id_capacite_categorie`);

--
-- Index pour la table `pouvoir_categorie`
--
ALTER TABLE `pouvoir_categorie`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `pouvoir_connaissance`
--
ALTER TABLE `pouvoir_connaissance`
  ADD UNIQUE KEY `id_sort` (`id_pouvoir`,`id_connaissance`);

--
-- Index pour la table `pouvoir_connaissance_categorie`
--
ALTER TABLE `pouvoir_connaissance_categorie`
  ADD UNIQUE KEY `id_pouvoir` (`id_pouvoir`,`id_connaissance_categorie`);

--
-- Index pour la table `pouvoir_pouvoir`
--
ALTER TABLE `pouvoir_pouvoir`
  ADD UNIQUE KEY `id_pouvoir` (`id_pouvoir`,`id_pouvoir_categorie`);

--
-- Index pour la table `pouvoir_pouvoir_categorie`
--
ALTER TABLE `pouvoir_pouvoir_categorie`
  ADD UNIQUE KEY `id_sort` (`id_pouvoir`,`id_pouvoir_categorie`);

--
-- Index pour la table `pouvoir_sort`
--
ALTER TABLE `pouvoir_sort`
  ADD UNIQUE KEY `id_sort` (`id_pouvoir`,`id_sort`);

--
-- Index pour la table `pouvoir_voie`
--
ALTER TABLE `pouvoir_voie`
  ADD UNIQUE KEY `id_pouvoir` (`id_pouvoir`,`id_voie`);

--
-- Index pour la table `prestige`
--
ALTER TABLE `prestige`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `race`
--
ALTER TABLE `race`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `race_pouvoir`
--
ALTER TABLE `race_pouvoir`
  ADD UNIQUE KEY `id_sort` (`id_race`,`id_pouvoir`);

--
-- Index pour la table `sort`
--
ALTER TABLE `sort`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `sort_champ`
--
ALTER TABLE `sort_champ`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `description` (`description`);

--
-- Index pour la table `sort_duree`
--
ALTER TABLE `sort_duree`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `description` (`description`);

--
-- Index pour la table `sort_portee`
--
ALTER TABLE `sort_portee`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `description` (`description`);

--
-- Index pour la table `sort_sauvegarde`
--
ALTER TABLE `sort_sauvegarde`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `description` (`description`);

--
-- Index pour la table `voie`
--
ALTER TABLE `voie`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `alignement`
--
ALTER TABLE `alignement`
  MODIFY `id` int(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT pour la table `capacite`
--
ALTER TABLE `capacite`
  MODIFY `id` int(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

--
-- AUTO_INCREMENT pour la table `capacite_categorie`
--
ALTER TABLE `capacite_categorie`
  MODIFY `id` int(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `connaissance`
--
ALTER TABLE `connaissance`
  MODIFY `id` int(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=263;

--
-- AUTO_INCREMENT pour la table `connaissance_categorie`
--
ALTER TABLE `connaissance_categorie`
  MODIFY `id` int(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `divinite`
--
ALTER TABLE `divinite`
  MODIFY `id` int(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT pour la table `experience`
--
ALTER TABLE `experience`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `faction`
--
ALTER TABLE `faction`
  MODIFY `id` int(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT pour la table `joueur`
--
ALTER TABLE `joueur`
  MODIFY `id` smallint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1515;

--
-- AUTO_INCREMENT pour la table `pantheon`
--
ALTER TABLE `pantheon`
  MODIFY `id` int(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `personnage`
--
ALTER TABLE `personnage`
  MODIFY `id` int(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5471;

--
-- AUTO_INCREMENT pour la table `personnage_journal`
--
ALTER TABLE `personnage_journal`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=149568;

--
-- AUTO_INCREMENT pour la table `pouvoir`
--
ALTER TABLE `pouvoir`
  MODIFY `id` int(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;

--
-- AUTO_INCREMENT pour la table `pouvoir_categorie`
--
ALTER TABLE `pouvoir_categorie`
  MODIFY `id` int(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `prestige`
--
ALTER TABLE `prestige`
  MODIFY `id` int(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `race`
--
ALTER TABLE `race`
  MODIFY `id` int(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT pour la table `sort`
--
ALTER TABLE `sort`
  MODIFY `id` int(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=290;

--
-- AUTO_INCREMENT pour la table `sort_champ`
--
ALTER TABLE `sort_champ`
  MODIFY `id` int(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT pour la table `sort_duree`
--
ALTER TABLE `sort_duree`
  MODIFY `id` int(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT pour la table `sort_portee`
--
ALTER TABLE `sort_portee`
  MODIFY `id` int(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT pour la table `sort_sauvegarde`
--
ALTER TABLE `sort_sauvegarde`
  MODIFY `id` int(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT pour la table `voie`
--
ALTER TABLE `voie`
  MODIFY `id` int(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
