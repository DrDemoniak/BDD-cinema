-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 16 juin 2025 à 11:59
-- Version du serveur : 8.0.27
-- Version de PHP : 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `cinema`
--

-- --------------------------------------------------------

--
-- Structure de la table `acteurs`
--

DROP TABLE IF EXISTS `acteurs`;
CREATE TABLE IF NOT EXISTS `acteurs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `date_naissance` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `acteurs`
--

INSERT INTO `acteurs` (`id`, `nom`, `prenom`, `date_naissance`) VALUES
(1, 'Reeves', 'Keanu', '1964-09-02'),
(2, 'DiCaprio', 'Leonardo', '1974-11-11'),
(3, 'Portman', 'Natalie', '1981-06-09'),
(4, 'Johansson', 'Scarlett', '1984-11-22'),
(5, 'Hanks', 'Tom', '1956-07-09'),
(6, 'Pitt', 'Brad', '1963-12-18'),
(7, 'Clooney', 'George', '1961-05-06'),
(8, 'Blanchett', 'Cate', '1969-05-14'),
(9, 'Damon', 'Matt', '1970-10-08'),
(10, 'Washington', 'Denzel', '1954-12-28');

-- --------------------------------------------------------

--
-- Structure de la table `films`
--

DROP TABLE IF EXISTS `films`;
CREATE TABLE IF NOT EXISTS `films` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(50) NOT NULL,
  `annee` year NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `films`
--

INSERT INTO `films` (`id`, `titre`, `annee`) VALUES
(1, 'Inception', 2010),
(2, 'Pulp Fiction', 1994),
(3, 'Black Swan', 2010),
(4, 'Forrest Gump', 1994),
(5, 'The Matrix', 1999),
(6, 'Fight Club', 1999),
(7, 'Oceans Eleven', 2001),
(8, 'The Grand Budapest Hotel', 2014),
(9, 'Titanic', 1997);

-- --------------------------------------------------------

--
-- Structure de la table `films_acteurs`
--

DROP TABLE IF EXISTS `films_acteurs`;
CREATE TABLE IF NOT EXISTS `films_acteurs` (
  `id_film` int NOT NULL,
  `id_acteur` int NOT NULL,
  PRIMARY KEY (`id_film`,`id_acteur`),
  KEY `joue_acteurs0_FK` (`id_acteur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `films_acteurs`
--

INSERT INTO `films_acteurs` (`id_film`, `id_acteur`) VALUES
(5, 1),
(1, 2),
(3, 3),
(1, 4),
(2, 4),
(4, 5),
(2, 6),
(6, 6),
(7, 7),
(8, 8),
(7, 9),
(9, 10);

-- --------------------------------------------------------

--
-- Structure de la table `films_realisateurs`
--

DROP TABLE IF EXISTS `films_realisateurs`;
CREATE TABLE IF NOT EXISTS `films_realisateurs` (
  `id_film` int NOT NULL,
  `id_realisateur` int NOT NULL,
  PRIMARY KEY (`id_film`,`id_realisateur`),
  KEY `creer_real0_FK` (`id_realisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `films_realisateurs`
--

INSERT INTO `films_realisateurs` (`id_film`, `id_realisateur`) VALUES
(1, 1),
(3, 1),
(5, 1),
(2, 2),
(1, 3),
(4, 3),
(8, 4),
(6, 5),
(7, 5),
(9, 6);

-- --------------------------------------------------------

--
-- Structure de la table `genres`
--

DROP TABLE IF EXISTS `genres`;
CREATE TABLE IF NOT EXISTS `genres` (
  `id` int NOT NULL AUTO_INCREMENT,
  `intitule` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `genres`
--

INSERT INTO `genres` (`id`, `intitule`) VALUES
(1, 'Science-Fiction'),
(2, 'Action'),
(3, 'Drame'),
(4, 'Comédie'),
(5, 'Aventure'),
(6, 'Thriller'),
(7, 'Crime'),
(8, 'Romance');

-- --------------------------------------------------------

--
-- Structure de la table `genres_films`
--

DROP TABLE IF EXISTS `genres_films`;
CREATE TABLE IF NOT EXISTS `genres_films` (
  `id_genre` int NOT NULL,
  `id_film` int NOT NULL,
  PRIMARY KEY (`id_genre`,`id_film`),
  KEY `possede_films0_FK` (`id_film`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `genres_films`
--

INSERT INTO `genres_films` (`id_genre`, `id_film`) VALUES
(1, 1),
(2, 1),
(2, 2),
(7, 2),
(3, 3),
(3, 4),
(4, 4),
(1, 5),
(5, 5),
(6, 6),
(7, 6),
(4, 7),
(7, 7),
(3, 8),
(3, 9),
(8, 9);

-- --------------------------------------------------------

--
-- Structure de la table `lieux`
--

DROP TABLE IF EXISTS `lieux`;
CREATE TABLE IF NOT EXISTS `lieux` (
  `id` int NOT NULL AUTO_INCREMENT,
  `salle` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `lieux`
--

INSERT INTO `lieux` (`id`, `salle`) VALUES
(1, 'Salle Gotham'),
(2, 'Salle Pandora'),
(3, 'Salle Mordor');

-- --------------------------------------------------------

--
-- Structure de la table `realisateurs`
--

DROP TABLE IF EXISTS `realisateurs`;
CREATE TABLE IF NOT EXISTS `realisateurs` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `date_naissance` date NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `realisateurs`
--

INSERT INTO `realisateurs` (`id`, `nom`, `prenom`, `date_naissance`) VALUES
(1, 'Nolan', 'Christopher', '1970-07-30'),
(2, 'Tarantino', 'Quentin', '1963-03-27'),
(3, 'Spielberg', 'Steven', '1946-12-18'),
(4, 'Villeneuve', 'Denis', '1967-10-03'),
(5, 'Scorsese', 'Martin', '1942-11-17'),
(6, 'Cameron', 'James', '1954-08-16');

-- --------------------------------------------------------

--
-- Structure de la table `seances`
--

DROP TABLE IF EXISTS `seances`;
CREATE TABLE IF NOT EXISTS `seances` (
  `id` int NOT NULL AUTO_INCREMENT,
  `horaires` datetime NOT NULL,
  `id_film` int NOT NULL,
  `id_lieu` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `seances_films_FK` (`id_film`),
  KEY `seances_lieux0_FK` (`id_lieu`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `seances`
--

INSERT INTO `seances` (`id`, `horaires`, `id_film`, `id_lieu`) VALUES
(1, '2025-02-21 20:00:00', 1, 1),
(2, '2025-02-22 22:00:00', 2, 2),
(3, '2025-02-23 18:30:00', 3, 3),
(4, '2025-02-24 21:00:00', 4, 1),
(5, '2025-02-25 19:45:00', 5, 2),
(6, '2025-02-26 20:00:00', 6, 3),
(7, '2025-02-27 22:00:00', 7, 1),
(8, '2025-02-28 18:30:00', 8, 2),
(9, '2025-02-28 20:30:00', 9, 3);

-- --------------------------------------------------------

--
-- Structure de la table `seances_utilisateurs`
--

DROP TABLE IF EXISTS `seances_utilisateurs`;
CREATE TABLE IF NOT EXISTS `seances_utilisateurs` (
  `id_seance` int NOT NULL,
  `id_utilisateur` int NOT NULL,
  PRIMARY KEY (`id_seance`,`id_utilisateur`),
  KEY `participe_utilisateur0_FK` (`id_utilisateur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `seances_utilisateurs`
--

INSERT INTO `seances_utilisateurs` (`id_seance`, `id_utilisateur`) VALUES
(1, 1),
(5, 1),
(2, 2),
(7, 2),
(3, 3),
(8, 3),
(4, 4),
(9, 4),
(6, 5);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

DROP TABLE IF EXISTS `utilisateur`;
CREATE TABLE IF NOT EXISTS `utilisateur` (
  `id` int NOT NULL AUTO_INCREMENT,
  `mail` varchar(50) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id`, `mail`, `nom`, `prenom`) VALUES
(1, 'alpha_92@randommail.com', 'Dupont', 'Jean'),
(2, 'bravo_x1@fakemail.net', 'Martin', 'Sophie'),
(3, 'charlie87@webmail.org', 'Leroy', 'Paul'),
(4, 'delta_Emma@tempemail.com', 'Bernard', 'Emma'),
(5, 'echo77_lucas@mailbox.com', 'Durand', 'Lucas');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `films_acteurs`
--
ALTER TABLE `films_acteurs`
  ADD CONSTRAINT `joue_acteurs0_FK` FOREIGN KEY (`id_acteur`) REFERENCES `acteurs` (`id`),
  ADD CONSTRAINT `joue_films_FK` FOREIGN KEY (`id_film`) REFERENCES `films` (`id`);

--
-- Contraintes pour la table `films_realisateurs`
--
ALTER TABLE `films_realisateurs`
  ADD CONSTRAINT `creer_films_FK` FOREIGN KEY (`id_film`) REFERENCES `films` (`id`),
  ADD CONSTRAINT `creer_real0_FK` FOREIGN KEY (`id_realisateur`) REFERENCES `realisateurs` (`id`);

--
-- Contraintes pour la table `genres_films`
--
ALTER TABLE `genres_films`
  ADD CONSTRAINT `possede_films0_FK` FOREIGN KEY (`id_film`) REFERENCES `films` (`id`),
  ADD CONSTRAINT `possede_genres_FK` FOREIGN KEY (`id_genre`) REFERENCES `genres` (`id`);

--
-- Contraintes pour la table `seances`
--
ALTER TABLE `seances`
  ADD CONSTRAINT `seances_films_FK` FOREIGN KEY (`id_film`) REFERENCES `films` (`id`),
  ADD CONSTRAINT `seances_lieux0_FK` FOREIGN KEY (`id_lieu`) REFERENCES `lieux` (`id`);

--
-- Contraintes pour la table `seances_utilisateurs`
--
ALTER TABLE `seances_utilisateurs`
  ADD CONSTRAINT `participe_seances_FK` FOREIGN KEY (`id_seance`) REFERENCES `seances` (`id`),
  ADD CONSTRAINT `participe_utilisateur0_FK` FOREIGN KEY (`id_utilisateur`) REFERENCES `utilisateur` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
