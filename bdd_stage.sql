-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : lun. 25 mai 2026 à 21:05
-- Version du serveur : 10.4.32-MariaDB
-- Version de PHP : 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `bdd_stage`
--

-- --------------------------------------------------------

--
-- Structure de la table `enseignant`
--

CREATE TABLE `enseignant` (
  `id_enseignant` int(11) NOT NULL,
  `id_jury` int(11) DEFAULT NULL,
  `Nom` varchar(200) DEFAULT NULL,
  `Prenom` varchar(200) DEFAULT NULL,
  `email` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `entreprise`
--

CREATE TABLE `entreprise` (
  `id_entreprise` int(11) NOT NULL,
  `nom` varchar(200) DEFAULT NULL,
  `adresse` varchar(200) DEFAULT NULL,
  `secteur` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `etudiant`
--

CREATE TABLE `etudiant` (
  `N°Etudiant` int(11) NOT NULL,
  `Nom` varchar(200) DEFAULT NULL,
  `Prenom` varchar(200) DEFAULT NULL,
  `Email` varchar(200) DEFAULT NULL,
  `Formation` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `historique`
--

CREATE TABLE `historique` (
  `id_historique` int(11) NOT NULL,
  `N°Etudiant` int(11) DEFAULT NULL,
  `date_consultation` date DEFAULT NULL,
  `heure_consultation` time DEFAULT NULL,
  `offre_consulter` varchar(200) DEFAULT NULL,
  `candidature` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `jury`
--

CREATE TABLE `jury` (
  `id_jury` int(11) NOT NULL,
  `numero_jury` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `maitre de stage`
--

CREATE TABLE `maitre de stage` (
  `id_maitre` int(11) NOT NULL,
  `id_Entreprise` int(11) DEFAULT NULL,
  `Nom` varchar(200) DEFAULT NULL,
  `Prenom` varchar(200) DEFAULT NULL,
  `Email` varchar(200) DEFAULT NULL,
  `Nom entreprise` varchar(200) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `offre`
--

CREATE TABLE `offre` (
  `id_offre` int(11) NOT NULL,
  `id_entreprise` int(11) DEFAULT NULL,
  `titre_offre` varchar(200) DEFAULT NULL,
  `description_offre` text DEFAULT NULL,
  `lieu` varchar(200) DEFAULT NULL,
  `date_de_publication` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `soutenance`
--

CREATE TABLE `soutenance` (
  `id_soutenancecodate` int(11) NOT NULL,
  `id_jury` int(11) DEFAULT NULL,
  `id_enseignant` int(11) DEFAULT NULL,
  `N°Etudiant` int(11) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `horaire` time DEFAULT NULL,
  `note` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Structure de la table `stage`
--

CREATE TABLE `stage` (
  `id_stage` int(11) NOT NULL,
  `id_maitre` int(11) DEFAULT NULL,
  `id_etudiant` int(11) DEFAULT NULL,
  `id_offre` int(11) DEFAULT NULL,
  `id_enseignant` int(11) DEFAULT NULL,
  `convention_signer` tinyint(1) DEFAULT NULL,
  `competence` varchar(200) DEFAULT NULL,
  `lieu` varchar(200) DEFAULT NULL,
  `date_incident` date DEFAULT NULL,
  `description_incident` text DEFAULT NULL,
  `date_debut` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `enseignant`
--
ALTER TABLE `enseignant`
  ADD PRIMARY KEY (`id_enseignant`),
  ADD KEY `id_jury` (`id_jury`);

--
-- Index pour la table `entreprise`
--
ALTER TABLE `entreprise`
  ADD PRIMARY KEY (`id_entreprise`);

--
-- Index pour la table `etudiant`
--
ALTER TABLE `etudiant`
  ADD PRIMARY KEY (`N°Etudiant`);

--
-- Index pour la table `historique`
--
ALTER TABLE `historique`
  ADD PRIMARY KEY (`id_historique`),
  ADD KEY `N°Etudiant` (`N°Etudiant`);

--
-- Index pour la table `jury`
--
ALTER TABLE `jury`
  ADD PRIMARY KEY (`id_jury`);

--
-- Index pour la table `maitre de stage`
--
ALTER TABLE `maitre de stage`
  ADD PRIMARY KEY (`id_maitre`),
  ADD KEY `id_Entreprise` (`id_Entreprise`);

--
-- Index pour la table `offre`
--
ALTER TABLE `offre`
  ADD PRIMARY KEY (`id_offre`),
  ADD KEY `id_entreprise` (`id_entreprise`);

--
-- Index pour la table `soutenance`
--
ALTER TABLE `soutenance`
  ADD PRIMARY KEY (`id_soutenancecodate`),
  ADD KEY `id_jury` (`id_jury`),
  ADD KEY `id_enseignant` (`id_enseignant`),
  ADD KEY `N°Etudiant` (`N°Etudiant`);

--
-- Index pour la table `stage`
--
ALTER TABLE `stage`
  ADD PRIMARY KEY (`id_stage`),
  ADD KEY `id_maitre` (`id_maitre`),
  ADD KEY `id_etudiant` (`id_etudiant`),
  ADD KEY `id_offre` (`id_offre`),
  ADD KEY `id_enseignant` (`id_enseignant`);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `enseignant`
--
ALTER TABLE `enseignant`
  ADD CONSTRAINT `enseignant_ibfk_1` FOREIGN KEY (`id_jury`) REFERENCES `jury` (`id_jury`);

--
-- Contraintes pour la table `historique`
--
ALTER TABLE `historique`
  ADD CONSTRAINT `historique_ibfk_1` FOREIGN KEY (`N°Etudiant`) REFERENCES `etudiant` (`N°Etudiant`);

--
-- Contraintes pour la table `maitre de stage`
--
ALTER TABLE `maitre de stage`
  ADD CONSTRAINT `maitre de stage_ibfk_1` FOREIGN KEY (`id_Entreprise`) REFERENCES `entreprise` (`id_entreprise`);

--
-- Contraintes pour la table `offre`
--
ALTER TABLE `offre`
  ADD CONSTRAINT `offre_ibfk_1` FOREIGN KEY (`id_entreprise`) REFERENCES `entreprise` (`id_entreprise`);

--
-- Contraintes pour la table `soutenance`
--
ALTER TABLE `soutenance`
  ADD CONSTRAINT `soutenance_ibfk_1` FOREIGN KEY (`id_jury`) REFERENCES `jury` (`id_jury`),
  ADD CONSTRAINT `soutenance_ibfk_2` FOREIGN KEY (`id_enseignant`) REFERENCES `enseignant` (`id_enseignant`),
  ADD CONSTRAINT `soutenance_ibfk_3` FOREIGN KEY (`N°Etudiant`) REFERENCES `etudiant` (`N°Etudiant`);

--
-- Contraintes pour la table `stage`
--
ALTER TABLE `stage`
  ADD CONSTRAINT `stage_ibfk_1` FOREIGN KEY (`id_maitre`) REFERENCES `maitre de stage` (`id_maitre`),
  ADD CONSTRAINT `stage_ibfk_2` FOREIGN KEY (`id_etudiant`) REFERENCES `etudiant` (`N°Etudiant`),
  ADD CONSTRAINT `stage_ibfk_3` FOREIGN KEY (`id_offre`) REFERENCES `offre` (`id_offre`),
  ADD CONSTRAINT `stage_ibfk_4` FOREIGN KEY (`id_enseignant`) REFERENCES `enseignant` (`id_enseignant`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
