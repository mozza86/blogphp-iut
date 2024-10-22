-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 22 oct. 2024 à 06:30
-- Version du serveur : 8.3.0
-- Version de PHP : 8.2.18

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `blog`
--
CREATE DATABASE IF NOT EXISTS `blog` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `blog`;

-- --------------------------------------------------------

--
-- Structure de la table `articles`
--

DROP TABLE IF EXISTS `articles`;
CREATE TABLE IF NOT EXISTS `articles` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `author_id` int NOT NULL,
  `image_url` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `author_id` (`author_id`)
) ENGINE=InnoDB AUTO_INCREMENT=54 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `articles`
--

INSERT INTO `articles` (`id`, `title`, `content`, `author_id`, `image_url`, `created_at`, `updated_at`) VALUES
(44, 'coucou <script> 	alert(\'Hi\'); </script>', 'coucou <script> 	alert(\'Hi\'); </script>', 17, 'imgArticle/44.png', '2024-10-15 11:45:37', '2024-10-15 11:45:37'),
(46, 'Mergez ', 'sdsdqsd', 21, 'imgArticle/46.png', '2024-10-18 10:38:11', '2024-10-18 10:38:11'),
(47, 'administration fiscale', 'Donner moi de l\'argent', 23, 'imgArticle/47.png', '2024-10-18 11:28:04', '2024-10-18 11:28:04'),
(48, 'bonjour', 'bonjour', 23, 'imgArticle/48.png', '2024-10-18 11:51:37', '2024-10-18 11:51:37'),
(49, 'salut le monde', 'titre', 24, 'imgArticle/49.png', '2024-10-18 12:10:49', '2024-10-18 12:10:49'),
(50, 'offensive', 'offensive', 25, 'imgArticle/50.png', '2024-10-18 12:13:19', '2024-10-18 12:13:19'),
(52, 'test article', 'jzekljfezlkdsjflksdjflksjdlkfdsf', 26, '', '2024-10-18 13:25:56', '2024-10-18 13:25:56'),
(53, 'test article', 'jzekljfezlkdsjflksdjflksjdlkfdsf', 26, '', '2024-10-18 13:26:54', '2024-10-18 13:26:54');

-- --------------------------------------------------------

--
-- Structure de la table `article_categories`
--

DROP TABLE IF EXISTS `article_categories`;
CREATE TABLE IF NOT EXISTS `article_categories` (
  `article_id` int NOT NULL,
  `category_id` int NOT NULL,
  PRIMARY KEY (`article_id`,`category_id`),
  KEY `article_id` (`article_id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `comments`
--

DROP TABLE IF EXISTS `comments`;
CREATE TABLE IF NOT EXISTS `comments` (
  `id` int NOT NULL AUTO_INCREMENT,
  `author_id` int NOT NULL,
  `content` text NOT NULL,
  `article_id` int NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `id` (`id`),
  KEY `author_id` (`author_id`),
  KEY `article_id` (`article_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `comments`
--

INSERT INTO `comments` (`id`, `author_id`, `content`, `article_id`, `created_at`, `updated_at`) VALUES
(1, 13, 'coucou', 48, '2024-10-18 12:39:15', '2024-10-18 12:39:15'),
(2, 13, 'coucou2', 48, '2024-10-18 12:39:19', '2024-10-18 12:39:19'),
(8, 13, '<script> alert(\'Hi\'); </script>', 44, '2024-10-18 13:15:58', '2024-10-18 13:15:58'),
(9, 13, '<script>alert(1)</script>', 44, '2024-10-18 13:16:06', '2024-10-18 13:16:06');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` text NOT NULL,
  `password` text NOT NULL,
  `username` text NOT NULL,
  `avatar_url` text NOT NULL,
  `description` text NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `id` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `email`, `password`, `username`, `avatar_url`, `description`, `admin`) VALUES
(13, 'admin@localhost.fr', '$2y$10$sy1nRzEWGk5cyG10z5/1vuuVQwmKfiP0RCgGjLZwnrWzKgk0ATp76', 'Admin', 'uploads/13.png', 'Super admin de la mort qui tue', 1),
(14, 'actwild644@gmail.com', '$2y$10$WitaxwFxR02/bns.wQbmv.I27A6vvm9EusTWcg5GmW9EEDlb4Maoi', 'Je S&#039;appelle Groot', 'uploads/14.png', 'Je suis un arbre', 0),
(15, 'rejeri4888@givehit.com', '$2y$10$uuMgnZvsAr3lJ1NgoBqGIudUcBvpw/aYXgAbj33thmCyco19skera', 'un chat', 'uploads/15.png', 'miaou', 0),
(16, 'lebron@localhost', '$2y$10$EZrWhKj5oD6BGWaaWovPZeiYIzO.8bY3ra0I2NJR2pVWPkaywnfDC', 'lebron', 'uploads/16.png', '', 0),
(17, 'aozfiaeiufnzorngoierngnieroignoeringoierngoneirgoineroignoeirngoinerngoinergioneorigoeirgonerogineoirgoenrgoineroignoeirngoienrgoineroigneorignoeringoierginoerignoenrgoienrgoineorignoeirgn@gmail.com', '$2y$10$3fF5mlG0RG2HKap5w4SFZ.TETpwafaTsX/N6inaCOMU7ynGiLvaVW', 'penguin', 'uploads/17.png', 'il est petit', 0),
(18, 'test@yoprmail.fr\' or 1=1;--', '$2y$10$XJoNnLQU/zN/m3jfnxD3P.P7oj3ceSn1LxX4zz9CazJX4.5HvBOjy', '', '', '', 0),
(19, 'Clem3', '$2y$10$5Qv0uuMSWfjGrmRoVYLuXufLz/On1hUc47PIrx5jxINLQCxzUeHsK', '', '', '', 0),
(20, 'mergez@tutu.com', '$2y$10$MlEjgwgDqlhfSNE9.E4/ougKVw1lkvQ7TQtTG4ye6vfdHsVVGV7z.', 'merguez', 'uploads/20.png', 'J&#039;aime les barbec', 0),
(21, 'devdocs@douze.fr', '$2y$10$0Zkhj4.ELgr40WC64Nduxu23SgUzriOPusB.tZHSgjPhOea6hUp/a', 'dDs', 'uploads/21.png', '&amp;&eacute;', 0),
(22, 'a@a.com', '$2y$10$kHIlRQAtXGslATLCpdQntuHrDeE5iORT1AQcMjzTreLCw.bqeVisG', 'qeersztnztrn', 'uploads/22.png', 'oui', 0),
(23, 'admin@localost.fr', '$2y$10$rr5qucGbJ5t.6sFTULh3ROw1thCneRf5jtPpPkpws3NCr7uSLdt8a', 'Admin', 'uploads/23.png', 'Super admin de la mort qui tue', 1),
(24, 'in@localhost.fr', '$2y$10$pd/cgSmeqAHeaWKU.Qk0be9767zNnHXo1MJlaoxtdQqb2YTuwmn1e', '', '', '', 0),
(25, 'b@a.com', '$2y$10$oRwPXg.aEfLpBPyQzB9p9OZ94HQnOLtn.6G3UkMFIihWbJLMIHjR6', 'c mloi', 'uploads/25.png', 'la conaissance', 0),
(26, 'test@test.test', '$2y$10$Vz2omU3WAru273CUHrlQpOe/roRn34Jak0F6YN4DTSt4.teKqSlOq', 'test', '', '', 0);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `articles`
--
ALTER TABLE `articles`
  ADD CONSTRAINT `articles_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`);

--
-- Contraintes pour la table `article_categories`
--
ALTER TABLE `article_categories`
  ADD CONSTRAINT `article_categories_ibfk_1` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`),
  ADD CONSTRAINT `article_categories_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Contraintes pour la table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `comments_ibfk_2` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
