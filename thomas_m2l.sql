-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 14 mai 2025 à 16:04
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `thomas_m2l`
--

-- --------------------------------------------------------

--
-- Structure de la table `bulletin`
--

DROP TABLE IF EXISTS `bulletin`;
CREATE TABLE IF NOT EXISTS `bulletin` (
  `mois` varchar(10) DEFAULT NULL,
  `annee` date DEFAULT NULL,
  `bulletinPDF` varchar(255) DEFAULT NULL,
  `idContrat` int DEFAULT NULL,
  `idBulletin` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`idBulletin`),
  KEY `fk_contrat_bulletin` (`idContrat`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `club`
--

DROP TABLE IF EXISTS `club`;
CREATE TABLE IF NOT EXISTS `club` (
  `nomClub` varchar(30) DEFAULT NULL,
  `adresseClub` varchar(60) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `idLigue` int DEFAULT NULL,
  `idCommune` int DEFAULT NULL,
  `idClub` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`idClub`),
  KEY `fk_ligue_club` (`idLigue`),
  KEY `fk_commune_club` (`idCommune`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `club`
--

INSERT INTO `club` (`nomClub`, `adresseClub`, `idLigue`, `idCommune`, `idClub`) VALUES
('Boxe Toulousaine', '12 rue du combat', 2, 5, 2),
('Dojo Occitanie', '8 allée des arts martiaux', 2, 7, 3),
('Tennis Limoges', '10 cours du service', 3, 6, 4),
('Service Tennis', '45 rue centrale', 3, 78, 5),
('Cognac United', '11 chemin des vignes', 1, 7, 6),
('Gironde Sport Plus', '29 place Pey Berland', 1, 33, 7);

-- --------------------------------------------------------

--
-- Structure de la table `commune`
--

DROP TABLE IF EXISTS `commune`;
CREATE TABLE IF NOT EXISTS `commune` (
  `idCommune` int NOT NULL AUTO_INCREMENT,
  `codePostal` int NOT NULL DEFAULT '0',
  `nomCommune` varchar(40) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL,
  `codeDepartement` int NOT NULL,
  PRIMARY KEY (`idCommune`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `commune`
--

INSERT INTO `commune` (`idCommune`, `codePostal`, `nomCommune`, `codeDepartement`) VALUES
(1, 31000, 'Toulouse', 31),
(2, 34000, 'Montpellier', 34),
(3, 87000, 'Limoges', 87),
(4, 64000, 'Pau', 64),
(5, 33000, 'Bordeaux', 33);

-- --------------------------------------------------------

--
-- Structure de la table `contrat`
--

DROP TABLE IF EXISTS `contrat`;
CREATE TABLE IF NOT EXISTS `contrat` (
  `dateDebut` date DEFAULT NULL,
  `dateFin` date DEFAULT NULL,
  `typeContrat` varchar(16) DEFAULT NULL,
  `nbHeures` float DEFAULT NULL,
  `idUser` int DEFAULT NULL,
  `idContrat` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`idContrat`),
  KEY `fk_utilisateur_contrat` (`idUser`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `demandeutilisateur`
--

DROP TABLE IF EXISTS `demandeutilisateur`;
CREATE TABLE IF NOT EXISTS `demandeutilisateur` (
  `idFormation` int NOT NULL,
  `idUser` int NOT NULL,
  `etat` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`idFormation`,`idUser`),
  KEY `fk_utilisateur_FA` (`idUser`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `demandeutilisateur`
--

INSERT INTO `demandeutilisateur` (`idFormation`, `idUser`, `etat`) VALUES
(2, 2, 'refusée'),
(2, 3, 'en attente'),
(4, 2, 'validée'),
(4, 3, 'en attente');

-- --------------------------------------------------------

--
-- Structure de la table `departement`
--

DROP TABLE IF EXISTS `departement`;
CREATE TABLE IF NOT EXISTS `departement` (
  `codeDepartement` int NOT NULL,
  `nomDepartement` varchar(40) NOT NULL,
  PRIMARY KEY (`codeDepartement`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `departement`
--

INSERT INTO `departement` (`codeDepartement`, `nomDepartement`) VALUES
(33, 'Gironde'),
(34, 'Hérault'),
(44, 'Loire Atlantique'),
(60, 'Oise');

-- --------------------------------------------------------

--
-- Structure de la table `fonction`
--

DROP TABLE IF EXISTS `fonction`;
CREATE TABLE IF NOT EXISTS `fonction` (
  `libelle` varchar(16) DEFAULT NULL,
  `idFonct` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`idFonct`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Structure de la table `formation`
--

DROP TABLE IF EXISTS `formation`;
CREATE TABLE IF NOT EXISTS `formation` (
  `intitule` varchar(25) DEFAULT NULL,
  `description` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `duree` varchar(5) DEFAULT NULL,
  `dateOuvertInscriptions` date DEFAULT NULL,
  `dateClotureInscriptions` date DEFAULT NULL,
  `idFormation` int NOT NULL AUTO_INCREMENT,
  `capaciteMax` int NOT NULL,
  PRIMARY KEY (`idFormation`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `formation`
--

INSERT INTO `formation` (`intitule`, `description`, `duree`, `dateOuvertInscriptions`, `dateClotureInscriptions`, `idFormation`, `capaciteMax`) VALUES
('Gestion Asso', 'Gérer les tâches administratives d\'une association sportive', '12h', '2024-06-10', '2025-07-10', 2, 20),
('Communication Digitale', 'Outils numériques de communication', '09h', '2024-04-01', '2025-06-10', 4, 25),
('Coaching', 'Formation au coaching sportif', '14h', '2024-01-15', '2025-02-20', 5, 10),
('Règlementation Sportive', 'Connaître les règles du sport professionnel', '17h', '2024-01-20', '2024-02-10', 7, 14),
('Organisation Événements', 'Organiser un événement sportif', '15h', '2024-02-01', '2025-04-10', 8, 30),
('Prévention Santé', 'Prévention santé pour sportifs', '10h', '2024-01-20', '2025-03-11', 9, 20),
('Marketing Club', 'Stratégies marketing pour clubs', '08h', '2024-03-01', '2025-05-01', 10, 12),
('Développement Web', 'Créer un site pour son club', '06h', '2024-05-01', '2025-06-01', 11, 16),
('Programmation Python', 'Bases de la programmation en Python', '05h', '2024-04-15', '2025-05-15', 12, 25);

-- --------------------------------------------------------

--
-- Structure de la table `ligue`
--

DROP TABLE IF EXISTS `ligue`;
CREATE TABLE IF NOT EXISTS `ligue` (
  `nomLigue` varchar(20) DEFAULT NULL,
  `site` varchar(255) DEFAULT NULL,
  `description` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci DEFAULT NULL,
  `idCommune` int DEFAULT NULL,
  `idLigue` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`idLigue`),
  KEY `fk_commune_ligue` (`idCommune`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `ligue`
--

INSERT INTO `ligue` (`nomLigue`, `site`, `description`, `idCommune`, `idLigue`) VALUES
('Ligue de Handball', 'handballFrance.fr', 'Ligue de handball française', 10, 1),
('Ligue Escrime', 'escrimeNationale.fr', 'Ligue pour tous les clubs d\'escrime', 20, 2),
('Ligue de Natation', 'natationFederale.fr', 'Ligue nationale de natation', 30, 3);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `nom` varchar(20) DEFAULT NULL,
  `prenom` varchar(20) DEFAULT NULL,
  `login` varchar(16) DEFAULT NULL,
  `mdp` varchar(128) DEFAULT NULL,
  `typeUser` varchar(16) DEFAULT NULL,
  `idFonct` int DEFAULT NULL,
  `idLigue` int DEFAULT NULL,
  `idClub` int DEFAULT NULL,
  `idUser` int NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`idUser`),
  KEY `fk_fonction_utilisateur` (`idFonct`),
  KEY `fk_ligue_utilisateur` (`idLigue`),
  KEY `fk_club_utilisateur` (`idClub`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`nom`, `prenom`, `login`, `mdp`, `typeUser`, `idFonct`, `idLigue`, `idClub`, `idUser`) VALUES
('Guyot', 'Thomas', 'thom', '7682fe272099ea26efe39c890b33675b', 'rh', NULL, NULL, NULL, 1),
('Casteignau', 'Alexandre', 'alex', '7682fe272099ea26efe39c890b33675b', 'benevole', NULL, NULL, NULL, 2),
('Serrano', 'Thomas', 'greg', '7682fe272099ea26efe39c890b33675b', 'salarie', NULL, NULL, NULL, 3);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `bulletin`
--
ALTER TABLE `bulletin`
  ADD CONSTRAINT `fk_contrat_bulletin` FOREIGN KEY (`idContrat`) REFERENCES `contrat` (`idContrat`);

--
-- Contraintes pour la table `contrat`
--
ALTER TABLE `contrat`
  ADD CONSTRAINT `fk_utilisateur_contrat` FOREIGN KEY (`idUser`) REFERENCES `utilisateur` (`idUser`);

--
-- Contraintes pour la table `demandeutilisateur`
--
ALTER TABLE `demandeutilisateur`
  ADD CONSTRAINT `fk_formation_FU` FOREIGN KEY (`idFormation`) REFERENCES `formation` (`idFormation`),
  ADD CONSTRAINT `fk_utilisateur_FA` FOREIGN KEY (`idUser`) REFERENCES `utilisateur` (`idUser`);

--
-- Contraintes pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD CONSTRAINT `fk_club_utilisateur` FOREIGN KEY (`idClub`) REFERENCES `club` (`idClub`),
  ADD CONSTRAINT `fk_fonction_utilisateur` FOREIGN KEY (`idFonct`) REFERENCES `fonction` (`idFonct`),
  ADD CONSTRAINT `fk_ligue_utilisateur` FOREIGN KEY (`idLigue`) REFERENCES `ligue` (`idLigue`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
