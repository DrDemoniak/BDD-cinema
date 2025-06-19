-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 19 juin 2025 à 14:24
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
  `url_photo_acteur` varchar(255) DEFAULT NULL,
  `bio` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `acteurs`
--

INSERT INTO `acteurs` (`id`, `nom`, `prenom`, `date_naissance`, `url_photo_acteur`, `bio`) VALUES
(1, 'Reeves', 'Keanu', '1964-09-02', 'https://image.tmdb.org/t/p/w300/4D0PpNI0kmP58hgrwGC3wCjxhnm.jpg', 'Acteur canadien devenu icône grâce à la saga Matrix. Connu pour son humilité et ses rôles dans des films d\'action et de science-fiction.'),
(2, 'DiCaprio', 'Leonardo', '1974-11-11', 'https://upload.wikimedia.org/wikipedia/commons/2/25/Leonardo_DiCaprio_2014.jpg', 'Star hollywoodienne oscarisée, célèbre pour ses rôles dans Titanic et The Revenant. Un des acteurs les plus bankables de sa génération.'),
(3, 'Portman', 'Natalie', '1981-06-09', 'https://image.tmdb.org/t/p/w300/edPU5HxncLWa1YkgRPNkSd68ONG.jpg', 'Actrice israélo-américaine oscarisée pour Black Swan. Aussi connue pour son rôle dans Star Wars et son engagement politique.'),
(4, 'Johansson', 'Scarlett', '1984-11-22', 'https://image.tmdb.org/t/p/w300/6NsMbJXRlDZuDzatN2akFdGuTvx.jpg', 'Actrice américaine parmi les mieux payées d\'Hollywood. Vedette des Avengers et récompensée pour ses rôles dramatiques.'),
(5, 'Hanks', 'Tom', '1956-07-09', 'https://image.tmdb.org/t/p/w300/xndWFsBlClOJFRdhSt4NBwiPq2o.jpg', 'Légende du cinéma américain, double oscarisé. Connu pour ses rôles dans Forrest Gump, Philadelphia et ses collaborations avec Spielberg.'),
(6, 'Pitt', 'Brad', '1963-12-18', 'https://m.media-amazon.com/images/M/MV5BMjA1MjE2MTQ2MV5BMl5BanBnXkFtZTcwMjE5MDY0Nw@@._V1_.jpg', 'Acteur et producteur oscarisé, sex-symbol hollywoodien. Célèbre pour Fight Club, Ocean\'s Eleven et ses rôles chez Tarantino.'),
(7, 'Clooney', 'George', '1961-05-06', 'https://upload.wikimedia.org/wikipedia/commons/8/8d/George_Clooney_2016.jpg', 'Acteur, réalisateur et producteur américain. Star des sagas Ocean\'s et ER, connu pour son charisme et son engagement humanitaire.'),
(8, 'Blanchett', 'Cate', '1969-05-14', 'https://m.media-amazon.com/images/M/MV5BMTc1MDI0MDg1NV5BMl5BanBnXkFtZTgwMDM3OTAzMTE@._V1_FMjpg_UX1000_.jpg', 'Actrice australienne deux fois oscarisée. Virtuose des transformations, aussi à l\'aise dans le drame que dans la fantasy (Le Seigneur des Anneaux).'),
(9, 'Damon', 'Matt', '1970-10-08', 'https://m.media-amazon.com/images/M/MV5BMTM0NzYzNDgxMl5BMl5BanBnXkFtZTcwMDg2MTMyMw@@._V1_.jpg', 'Acteur et scénariste américain oscarisé. Célèbre pour la saga Jason Bourne et ses collaborations avec Steven Soderbergh.'),
(10, 'Washington', 'Denzel', '1954-12-28', 'https://www.ecranlarge.com/content/uploads/2016/10/t9arczbg1nlt8gzt2skwm227yok-227.jpg', 'Légende du cinéma américain, double oscarisé. Maître des rôles intenses dans des films comme Training Day et Malcolm X.');

-- --------------------------------------------------------

--
-- Structure de la table `commentaires`
--

DROP TABLE IF EXISTS `commentaires`;
CREATE TABLE IF NOT EXISTS `commentaires` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_film` int NOT NULL,
  `id_utilisateur` int NOT NULL,
  `note` tinyint NOT NULL,
  `commentaire` text NOT NULL,
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id_film` (`id_film`),
  KEY `id_utilisateur` (`id_utilisateur`)
) ;

--
-- Déchargement des données de la table `commentaires`
--

INSERT INTO `commentaires` (`id`, `id_film`, `id_utilisateur`, `note`, `commentaire`, `date_creation`) VALUES
(1, 6, 13, 5, 'Au top', '2025-06-18 17:28:29'),
(2, 6, 12, 5, 'Un superbe classic, je conseil !', '2025-06-18 17:30:47'),
(3, 3, 13, 4, 'Artistiquement bluffant !', '2025-06-19 14:05:15'),
(4, 8, 15, 3, 'Un film en manque de kayou', '2025-06-19 14:20:00'),
(5, 6, 15, 5, 'brad pitt à son prime, un narrateur en manque de calcium. C\'est une pépite !', '2025-06-19 14:22:00'),
(6, 5, 15, 5, 'Morpheus est d\'un charisme, on dirait andrew tate mais en un peu plus imposant. J\'envie son role. J\'aimerais etre acteur pour le prochain film je suis motivé', '2025-06-19 14:24:48'),
(7, 7, 15, 5, 'SOlide !', '2025-06-19 14:26:03');

-- --------------------------------------------------------

--
-- Structure de la table `films`
--

DROP TABLE IF EXISTS `films`;
CREATE TABLE IF NOT EXISTS `films` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(50) NOT NULL,
  `annee` year NOT NULL,
  `url_image` text NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `films`
--

INSERT INTO `films` (`id`, `titre`, `annee`, `url_image`, `description`) VALUES
(1, 'Inception', 2010, 'https://m.media-amazon.com/images/M/MV5BMjAxMzY3NjcxNF5BMl5BanBnXkFtZTcwNTI5OTM0Mw@@._V1_SX300.jpg', 'Un voleur expérimenté qui subtilise les secrets enfouis dans le subconscient pendant l\'état de sommeil se voit confier la tâche inverse : implanter une idée dans l\'esprit d\'un PDG. Mais une défense inattendue met toute l\'équipe en danger.'),
(2, 'Pulp Fiction', 1994, 'https://m.media-amazon.com/images/M/MV5BYTViYTE3ZGQtNDBlMC00ZTAyLTkyODMtZGRiZDg0MjA2YThkXkEyXkFqcGc@._V1_SX300.jpg', 'Les vies de deux hommes de main, d\'un boxeur, d\'un gangster et de sa femme, et d\'un couple de petits braqueurs s\'entrecroisent dans une histoire de crime à Los Angeles. Un classique du cinéma moderne aux dialogues percutants.'),
(3, 'Black Swan', 2010, 'https://m.media-amazon.com/images/M/MV5BNzY2NzI4OTE5MF5BMl5BanBnXkFtZTcwMjMyNDY4Mw@@._V1_SX300.jpg', 'Une ballerine obsédée par la perfection lutte pour le rôle principal dans \'Le Lac des Cygnes\'. Alors qu\'elle se rapproche de la perfection, elle découvre que la compétition est féroce et que la frontière entre réalité et illusion devient de plus en plus floue.'),
(4, 'Forrest Gump', 1994, 'https://m.media-amazon.com/images/M/MV5BNDYwNzVjMTItZmU5YS00YjQ5LTljYjgtMjY2NDVmYWMyNWFmXkEyXkFqcGc@._V1_SX300.jpg', 'L\'histoire extraordinaire de Forrest Gump, un homme au QI limité mais au grand cœur, qui traverse les décennies de l\'histoire américaine sans même s\'en rendre compte. Un conte émouvant sur l\'amour, la destinée et la simplicité.'),
(5, 'The Matrix', 1999, 'https://m.media-amazon.com/images/M/MV5BN2NmN2VhMTQtMDNiOS00NDlhLTliMjgtODE2ZTY0ODQyNDRhXkEyXkFqcGc@._V1_SX300.jpg', 'Un hacker découvre par le biais d\'un mystérieux rebelle que le monde dans lequel il vit n\'est qu\'une simulation informatique. Il rejoint alors la lutte pour libérer l\'humanité de cette prison virtuelle.'),
(6, 'Fight Club', 1999, 'https://m.media-amazon.com/images/M/MV5BOTgyOGQ1NDItNGU3Ny00MjU3LTg2YWEtNmEyYjBiMjI1Y2M5XkEyXkFqcGc@._V1_SX300.jpg', 'Un employé de bureau insomniaque et un fabricant de savon anarchiste établissent un club de combat souterrain qui évolue en quelque chose de bien plus grand et de bien plus dangereux. Une critique virulente de la société de consommation.'),
(7, 'Oceans Eleven', 2001, 'https://m.media-amazon.com/images/M/MV5BMmNhZDkxYTgtMDM3ZC00NTQ3LWFjZTUtNzc1Y2QyNWZjNDRmXkEyXkFqcGc@._V1_SX300.jpg', 'Danny Ocean rassemble une équipe de onze experts pour réaliser le casse du siècle : voler simultanément trois casinos à Las Vegas. Un film de braquage intelligent avec un casting de stars.'),
(8, 'The Grand Budapest Hotel', 2014, 'https://m.media-amazon.com/images/M/MV5BMzM5NjUxOTEyMl5BMl5BanBnXkFtZTgwNjEyMDM0MDE@._V1_SX300.jpg', 'Les aventures de Gustave H, un légendaire concierge d\'un célèbre hôtel européen entre-deux-guerres, et Zero Moustafa, l\'employé qu\'il forme, qui devient son ami le plus fidèle. Un conte visuellement splendide.'),
(9, 'Titanic', 1997, 'https://m.media-amazon.com/images/M/MV5BYzYyN2FiZmUtYWYzMy00MzViLWJkZTMtOGY1ZjgzNWMwN2YxXkEyXkFqcGc@._V1_SX300.jpg', 'Un peintre pauvre et une jeune femme de la haute société tombent amoureux à bord du luxueux Titanic lors de son tragique voyage inaugural. Une histoire d\'amour épique sur fond de catastrophe historique.');

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
(9, 2),
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
  `bio` text,
  `url_image_realisateur` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `realisateurs`
--

INSERT INTO `realisateurs` (`id`, `nom`, `prenom`, `date_naissance`, `bio`, `url_image_realisateur`) VALUES
(1, 'Nolan', 'Christopher', '1970-07-30', 'Christopher Nolan (né en 1970) est un réalisateur britannico-américain célèbre pour ses films complexes et ambitieux. Diplômé de l\'University College London, il se fait remarquer avec \'Memento\' (2000). Maître des structures narratives non-linéaires, il a révolutionné le cinéma de genre avec sa trilogie \'Batman\' et des films acclamés comme \'Inception\' (2010) et \'Interstellar\' (2014). Nolan privilégie les prises de vue réelles et le format IMAX.', 'https://m.media-amazon.com/images/M/MV5BNjE3NDQyOTYyMV5BMl5BanBnXkFtZTcwODcyODU2Mw@@._V1_.jpg'),
(2, 'Tarantino', 'Quentin', '1963-03-27', 'Quentin Tarantino (né en 1963) est le cinéaste américain le plus iconoclaste de sa génération. Ancien vidéoclubiste autodidacte, il explose avec \'Reservoir Dogs\' (1992). Son style unique mêle dialogues percutants, violence stylisée et hommages aux films de genre. Deux fois oscarisé (\'Pulp Fiction\', \'Django Unchained\'), il est connu pour sa collaboration avec Samuel L. Jackson et ses scènes cultes.', 'https://m.media-amazon.com/images/M/MV5BMTgyMjI3ODA3Nl5BMl5BanBnXkFtZTcwNzY2MDYxOQ@@._V1_.jpg'),
(3, 'Spielberg', 'Steven', '1946-12-18', 'Steven Spielberg (né en 1946) est le réalisateur le plus commercialement réuss de l\'histoire du cinéma. Pionnier du blockbuster moderne (\'Jaws\', \'E.T.\'), il alterne entre divertissements familiaux et sujets graves (\'La Liste de Schindler\', Oscar du meilleur film). Co-fondateur de DreamWorks, il a révolutionné les effets spéciaux et formé toute une génération de cinéastes.', 'https://m.media-amazon.com/images/M/MV5BMTY1NjAzNzE1MV5BMl5BanBnXkFtZTYwNTk0ODc0._V1_FMjpg_UX1000_.jpg'),
(4, 'Villeneuve', 'Denis', '1967-10-03', 'Denis Villeneuve (né en 1967) est le réalisateur québécois le plus acclamé internationalement. Après des débuts remarqués au Canada (\'Polytechnique\'), il conquiert Hollywood avec des thrillers psychologiques (\'Sicario\', \'Arrival\'). Spécialiste des atmosphères immersives, il a réinventé la science-fiction avec \'Blade Runner 2049\' et \'Dune\', tout en conservant une approche profondément humaine.', 'https://m.media-amazon.com/images/M/MV5BMzU2MDk5MDI2MF5BMl5BanBnXkFtZTcwNDkwMjMzNA@@._V1_.jpg'),
(5, 'Scorsese', 'Martin', '1942-11-17', 'Martin Scorsese (né en 1942) est le chantre du cinéma new-yorkais. Issu du quartier italien de Little Italy, il crée une filmographie obsessionnelle sur la culpabilité et la rédemption (\'Taxi Driver\', \'Raging Bull\'). Collaborateur historique de Robert De Niro et Leonardo DiCaprio, ce cinéaste érudit est aussi un ardent défenseur de la préservation du patrimoine cinématographique.', 'https://m.media-amazon.com/images/M/MV5BMTcyNDA4Nzk3N15BMl5BanBnXkFtZTcwNDYzMjMxMw@@._V1_FMjpg_UX1000_.jpg'),
(6, 'Cameron', 'James', '1954-08-16', 'James Cameron (né en 1954) est le roi incontesté du blockbuster technologique. Ancien camionneur devenu gourou des effets spéciaux, il a repoussé les limites du cinéma (\'Terminator 2\', \'Titanic\', \'Avatar\'). Pionnier des technologies 3D et de capture de mouvement, ce perfectionniste exigeant détient plusieurs records du box-office mondial. Il est aussi un explorateur océanographique reconnu.', 'https://m.media-amazon.com/images/M/MV5BMjI0MjMzOTg2MF5BMl5BanBnXkFtZTcwMTM3NjQxMw@@._V1_FMjpg_UX1000_.jpg');

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
(1, '2025-07-01 20:00:00', 1, 1),
(2, '2025-07-02 22:00:00', 2, 2),
(3, '2025-07-03 18:30:00', 3, 3),
(4, '2025-07-04 21:00:00', 4, 1),
(5, '2025-07-05 19:45:00', 5, 2),
(6, '2025-07-06 20:00:00', 6, 3),
(7, '2025-07-07 22:00:00', 7, 1),
(8, '2025-07-08 18:30:00', 8, 2),
(9, '2025-07-09 20:30:00', 9, 3);

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
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id`, `mail`, `nom`, `prenom`, `password`) VALUES
(12, 'clement.marquette2505@gmail.com', 'Pusterla Marquette', 'Clément', '$2y$10$KL5Av.B9864KcXcVEWKOXeFU1kT0y6qgIRZeQomjTGPyUn4Zow9pi'),
(13, 'flgherardi@gmail.com', 'GHERARDI', 'Flavien', '$2y$10$OgcsG4osbW16WNcqv/b8CO1UHTZkOaYRuZF4PEICWgGKWw8h3KCMe'),
(14, 'axelkebir@kebir.com', 'JEAN', 'kebir', '$2y$10$8Q8uh3RpBB/puyrE.8IoYePIlLnhXd3FXaqKMR/Sgenv87NcLL6Iq'),
(15, 'KAYOU@KAYOU.KAYOU', 'KAYOU', 'KAYOU', '$2y$10$sfHCe1KMqoxieBLMoTCn3Ofm7E8P1Usjvkq2ciXQbmGBCwE8kfA6W');

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
