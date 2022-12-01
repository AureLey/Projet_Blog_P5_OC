-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : jeu. 01 déc. 2022 à 16:36
-- Version du serveur : 5.7.36
-- Version de PHP : 8.1.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `p5_blog`
--

-- --------------------------------------------------------

--
-- Structure de la table `category`
--

DROP TABLE IF EXISTS `category`;
CREATE TABLE IF NOT EXISTS `category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(75) CHARACTER SET utf8 NOT NULL,
  `slug` varchar(45) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug_UNIQUE` (`slug`),
  UNIQUE KEY `name_UNIQUE` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `category`
--

INSERT INTO `category` (`id`, `name`, `slug`) VALUES
(1, 'DÃ©veloppement', 'developpement'),
(2, 'Ecologie', 'ecologie'),
(3, 'Lorem', 'lorem'),
(4, 'Ipsum', 'ipsum'),
(6, 'catÃ©gorie 5', 'catcinq');

-- --------------------------------------------------------

--
-- Structure de la table `comment`
--

DROP TABLE IF EXISTS `comment`;
CREATE TABLE IF NOT EXISTS `comment` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` text CHARACTER SET utf8 NOT NULL,
  `comment_status` tinyint(4) NOT NULL,
  `post_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_comment_post1_idx` (`post_id`),
  KEY `fk_comment_user1_idx` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=69 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `comment`
--

INSERT INTO `comment` (`id`, `content`, `comment_status`, `post_id`, `user_id`) VALUES
(1, 'comment 1', 0, 1, 2),
(2, 'comment 1', 1, 1, 2),
(3, 'comment 1', 1, 1, 2),
(4, 'comment 1', 0, 1, 2),
(5, 'comment 1', 0, 1, 2),
(6, 'comment 1', 0, 1, 2),
(7, 'comment 1', 0, 1, 2),
(8, 'comment 1', 1, 1, 2),
(9, 'comment 1', 1, 1, 2),
(10, 'comment 1', 1, 1, 2),
(11, 'comment 1', 0, 2, 2),
(12, 'comment 1', 0, 2, 2),
(13, 'comment 1', 1, 2, 2),
(14, 'comment 1', 1, 2, 2),
(15, 'comment 1', 1, 2, 2),
(16, 'comment 1', 1, 2, 2),
(17, 'comment 1', 1, 2, 2),
(18, 'comment 1', 1, 2, 2),
(19, 'comment 1', 1, 2, 2),
(20, 'comment 1', 1, 2, 2),
(21, 'comment 1', 1, 2, 2),
(22, 'comment 1', 1, 3, 2),
(23, 'comment 1', 1, 3, 2),
(24, 'comment 1', 1, 3, 2),
(25, 'comment 1', 1, 3, 2),
(26, 'comment 1', 1, 3, 2),
(27, 'comment 1', 1, 3, 2),
(28, 'comment 1', 1, 3, 2),
(29, 'comment 1', 1, 3, 2),
(30, 'comment 1', 1, 3, 2),
(31, 'comment 1', 1, 3, 2),
(32, 'comment 1', 1, 3, 2),
(33, 'comment 1', 1, 3, 2),
(36, 'un super commentaire', 1, 1, 1),
(37, 'un super commentaire', 1, 1, 1),
(38, 'azerty', 1, 1, 1),
(39, 'TESTCOMMENT', 1, 1, 1),
(40, 'un super commentaire', 1, 1, 1),
(41, 'azertyuio', 1, 1, 1),
(44, 'test commentaire', 1, 1, 1),
(45, '<script>alert(\'Badaboum\')</script>', 1, 1, 1),
(46, 'test required textarea', 1, 1, 1),
(49, 'un super commentaire', 1, 2, 1);

-- --------------------------------------------------------

--
-- Structure de la table `post`
--

DROP TABLE IF EXISTS `post`;
CREATE TABLE IF NOT EXISTS `post` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) CHARACTER SET utf8 NOT NULL,
  `chapo` varchar(150) CHARACTER SET utf8 NOT NULL,
  `author` varchar(75) CHARACTER SET utf8 NOT NULL,
  `content` text CHARACTER SET utf8 NOT NULL,
  `picture` varchar(100) CHARACTER SET utf8 NOT NULL,
  `slug` varchar(45) CHARACTER SET utf8 NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug_UNIQUE` (`slug`),
  KEY `fk_post_user1_idx` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `post`
--

INSERT INTO `post` (`id`, `title`, `chapo`, `author`, `content`, `picture`, `slug`, `created_at`, `updated_at`, `user_id`) VALUES
(1, 'Article sur PHP', 'bonnes pratiques en PHP', 'the developper PHP', 'I need your help, Luke. She needs your help. I\'m getting too old for this sort of thing. She must have hidden the plans in the escape pod. Send a detachment down to retrieve them, and see to it personally, Commander. There\'ll be no one to stop us this time!', 'php.jpg', 'article-sur-php', '2022-10-13 00:00:00', '2022-12-01 16:02:23', 1),
(2, 'Article sur le dÃ©veloppement durable', 'Don\'t underestimate the Force.', 'Green guy', 'I don\'t know what you\'re talking about. I am a member of the Imperial Senate on a diplomatic mission to Alderaan-- I don\'t know what you\'re talking about. I am a member of the Imperial Senate on a diplomatic mission to Alderaan', 'ecology.jpg', 'article-eco', '2022-10-13 00:00:00', '2022-12-01 16:03:45', 1),
(3, 'Article Ouragan dans la tÃªte', 'Brainstorming', 'Brain guy', 'A tremor in the Force. The last time I felt it was in the presence of my old master. Leave that to me. Send a distress signal, and inform the Senate that all on board were killed. Leave that to me. Send a distress signal, and inform the Senate that all on board were killed.A tremor in the Force. The last time I felt it was in the presence of my old master. Leave that to me. Send a distress signal, and inform the Senate that all on board were killed. Leave that to me. Send a distress signal, and inform the Senate that all on board were killed.', 'think.jpg', 'brain-storming', '2022-10-13 00:00:00', '2022-12-01 16:07:28', 1),
(4, 'Article sur l\'environnement de travail', 'Environnement le mot Ã  la mode', 'Jane Doe', 'I need your help, Luke. She needs your help. I\'m getting too old for this sort of thing. She must have hidden the plans in the escape pod. Send a detachment down to retrieve them, and see to it personally, Commander. There\'ll be no one to stop us this time!', 'work.jpg', 'article-working-environment', '2022-10-13 00:00:00', '2022-12-01 16:01:45', 1);

-- --------------------------------------------------------

--
-- Structure de la table `post_cat`
--

DROP TABLE IF EXISTS `post_cat`;
CREATE TABLE IF NOT EXISTS `post_cat` (
  `post_id_post` int(11) NOT NULL,
  `category_id_cat` int(11) NOT NULL,
  KEY `fk_post_cat_post_idx` (`post_id_post`),
  KEY `fk_post_cat_category1_idx` (`category_id_cat`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `post_cat`
--

INSERT INTO `post_cat` (`post_id_post`, `category_id_cat`) VALUES
(4, 4),
(1, 1),
(1, 3),
(2, 2),
(2, 4),
(3, 1),
(3, 2);

-- --------------------------------------------------------

--
-- Structure de la table `tag`
--

DROP TABLE IF EXISTS `tag`;
CREATE TABLE IF NOT EXISTS `tag` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(75) CHARACTER SET utf8 NOT NULL,
  `slug` varchar(45) CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name_UNIQUE` (`name`),
  UNIQUE KEY `slug_UNIQUE` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `tag`
--

INSERT INTO `tag` (`id`, `name`, `slug`) VALUES
(1, 'Alpha', 'alpha'),
(2, 'Beta', 'beta'),
(3, 'Gamma', 'gamma'),
(4, 'Delta', 'delta'),
(5, 'Epsilon', 'epsilon');

-- --------------------------------------------------------

--
-- Structure de la table `tag_post`
--

DROP TABLE IF EXISTS `tag_post`;
CREATE TABLE IF NOT EXISTS `tag_post` (
  `post_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  KEY `fk_tag_post_post1_idx` (`post_id`),
  KEY `fk_tag_post_tag1_idx` (`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Déchargement des données de la table `tag_post`
--

INSERT INTO `tag_post` (`post_id`, `tag_id`) VALUES
(4, 3),
(1, 1),
(1, 3),
(2, 2),
(2, 4),
(3, 1),
(3, 2);

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(75) CHARACTER SET utf8 NOT NULL,
  `surname` varchar(75) CHARACTER SET utf8 NOT NULL,
  `nickname` varchar(45) CHARACTER SET utf8 NOT NULL,
  `email` varchar(75) CHARACTER SET utf8 NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 NOT NULL,
  `role` text CHARACTER SET utf8 NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  UNIQUE KEY `nickname_UNIQUE` (`nickname`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `name`, `surname`, `nickname`, `email`, `password`, `role`) VALUES
(1, 'admin', 'admin', 'admin', 'admin@admin.fr', '$2y$10$i2YCly8tp.wZyIP2DnJ0Gena5ldpEJycwbU7QE8ui5gM0QD8KD7xa', 'admin'),
(2, 'Lannister', 'Tyrion', 'Tyrion', 'tyrion@lannister.got', 'Tyrion', 'user'),
(3, 'Stark', 'Sansa', 'Sansa', 'sansa@stark.got', 'sansa', 'user'),
(21, 'aurel', 'aurel', 'aurel', 'myemail@blog.fr', '$2y$10$soz5bReFW5TxInszUo1bY.oLUrXw64XqD6p2HhxT0hoz7NUCJ4dN2', 'user'),
(22, 'Snow', 'Jon', 'Jon', 'jon@stark.got', '$2y$10$i2YCly8tp.wZyIP2DnJ0Gena5ldpEJycwbU7QE8ui5gM0QD8KD7xa', 'user');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `fk_comment_post1` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_comment_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Contraintes pour la table `post`
--
ALTER TABLE `post`
  ADD CONSTRAINT `fk_post_user1` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Contraintes pour la table `post_cat`
--
ALTER TABLE `post_cat`
  ADD CONSTRAINT `fk_post_cat_category1` FOREIGN KEY (`category_id_cat`) REFERENCES `category` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_post_cat_post` FOREIGN KEY (`post_id_post`) REFERENCES `post` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Contraintes pour la table `tag_post`
--
ALTER TABLE `tag_post`
  ADD CONSTRAINT `fk_tag_post_post1` FOREIGN KEY (`post_id`) REFERENCES `post` (`id`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_tag_post_tag1` FOREIGN KEY (`tag_id`) REFERENCES `tag` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
