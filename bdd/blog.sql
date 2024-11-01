-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mar. 29 oct. 2024 à 19:26
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
CREATE DATABASE IF NOT EXISTS `blog` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
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
  KEY `author_id` (`author_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `articles`
--

INSERT INTO `articles` (`id`, `title`, `content`, `author_id`, `image_url`, `created_at`, `updated_at`) VALUES
(1, 'Mercure dans le thon : origine, risques... sept questions sur les boîtes contaminées ', 'Deux ONG révèlent, dans une enquête parue ce mardi, la « contamination généralisée au mercure » des boîtes de thon. D’où vient cette substance ? Est-elle dangereuse et en quelle quantité ? Éléments de réponse.\r\n\r\nLa totalité des boîtes de thon analysées par Bloom sont contaminées au mercure. L’association conseille en l’état de ne plus en consommer. Photo d\'illustration Sipa/Sébastien Salom-Gomis\r\n\r\nVous ne regarderez peut-être plus jamais une boîte de thon comme avant. Selon les ONG Bloom et Foodwatch, un « scandale de santé publique » se cache derrière ce produit prisé des Français. Dans un rapport publié ce mardi, elles révèlent la « contamination généralisée au mercure » des conserves de thon. Voici tout ce que vous devez savoir sur le sujet.\r\n\r\n\r\nC’est quoi le mercure déjà ?\r\n\r\nLe mercure est un métal liquide aux reflets argentés. Éruptions volcaniques, érosion des sols, feux de forêt… Celui que l’on surnomme parfois le “vif-argent” est à l’origine produit par des phénomènes naturels. Mais depuis la révolution industrielle, les activités humaines (combustion de charbon, production de ciment, incinération des déchets…) en émettent aussi. Et dans des proportions bien plus importantes.\r\n\r\n\r\nOù en trouve-t-on ?\r\n\r\nAujourd’hui, de nombreux produits contiennent du mercure. C’est le cas des piles et des batteries mais aussi de certains thermomètres, lampes et produits éclaircissant pour la peau. On en retrouve encore dans de nombreux plombages dentaires – même si le mercure métallique sera interdit dans les amalgames dentaires à compter de l’année prochaine.\r\n\r\nMais ce n’est pas tout. Très volatil, le mercure se disperse facilement dans l’atmosphère. Et quand il finit par se déposer dans l’océan, des bactéries le transforment en méthylmercure, ce qui constitue « sa forme la plus toxique », pointe l’enquête de l\'ONG Bloom.\r\n\r\n\r\nPourquoi y-en a-t-il dans le thon ?\r\n\r\nDans l’océan, le méthylmercure est absorbé par le phytoplancton, à la base de la chaîne alimentaire. Il va donc « s’accumuler biologiquement dans les poissons et les crustacés », explique l’Organisation mondiale de la santé (OMS) sur son site. In fine, ce sont les grands poissons prédateurs qui sont le plus susceptibles d’avoir une forte teneur en mercure. Or, il se trouve que le thon se trouve au sommet de la chaîne alimentaire.\r\nEst-ce que le mercure est dangereux ?\r\n\r\nL’OMS classe le mercure comme l’une des 10 substances les plus préoccupantes au monde, avec l’amiante et l’arsenic. Et pour cause : il s’agit d’un puissant neurotoxique, par ailleurs classé comme cancérogène possible par le Centre international de recherche contre le cancer (CIRC). À la fin du XIXe siècle, il a rendu « fous » de nombreux chapeliers qui y étaient exposés. Il a aussi été responsable de la maladie de Minamata, qui a fait des milliers de victimes au Japon entre 1930 et 1970.\r\n\r\n\r\nQuelles sont les conséquences d’une exposition au mercure ?\r\n\r\nQuand il est ingéré par le biais de produits contaminés, le méthylmercure passe dans le sang, les organes et le cerveau. De nombreuses études ont montré qu’ils pouvaient entraîner des problèmes neuronaux, cardiovasculaires ou immunitaires.\r\n\r\nChez les adultes, il peut attaquer le fonctionnement du système cérébral (pertes de motricités de coordination, troubles de la mémoire, anxiété…). Chez les fœtus et les jeunes enfants, il représente carrément « un grave danger », alerte Bloom. Même si une personne enceinte en ingère en faible quantité, le mercure peut se loger dans le cerveau en formation de son enfant à naître. Cette exposition prénatale peut avoir des conséquences sur le long terme : « la cognition, la mémoire, l’attention, le langage, la motricité fine et la vision dans l’espace peuvent être affectés », prévient l’OMS.\r\n\r\n\r\nEst-ce qu’on consomme trop de thon en boîte ?\r\n\r\nComme le thon est le poisson le plus consommé dans l’Hexagone, « la population française est l’une des populations européennes les plus exposées au méthylmercure », s’inquiète le rapport. L’Anses (l’Agence nationale de sécurité sanitaire de l’alimentation) reconnaît qu’il est possible de dépasser la dose hebdomadaire tolérable (DHT) déterminée par l’autorité européenne de sécurité des aliments (Efsa), rien qu’en mangeant « plus d’une portion » de thon par semaine.\r\n\r\nCette DHT est fixée à 1,3 micro-gramme de méthylmercure par kilogramme de poids corporel par semaine. Une norme qui est d’ailleurs remise en cause par les ONG à l’origine du rapport, puisque le méthylmercure « peut avoir des effets dévastateurs sur la santé même à faibles doses ».\r\n\r\n\r\nCombien faut-il en manger ?\r\n\r\nC’est là que le bât blesse. Légalement, quasiment toutes les boîtes de thon (9/10) analysées lors de l’enquête respectent la teneur maximale fixée par l’Europe (<1 mg/kg). Sauf que, paradoxalement, la quantité maximale de mercure autorisée dans le thon est « trois fois plus élevée que celle des espèces les moins contaminées », dénoncent les associations. Si elle était fixée à 0,3mg/kg (comme pour les sardines ou les anchois), plus d’une boîte testée sur deux ne serait pas dans les clous.\r\n\r\nNormes jugées pas assez protectrices, contrôles insuffisants, influence des lobbies thoniers… Dans le contexte actuel, il est « impossible » de savoir quelles boîtes sont les plus contaminées et donc quelle quantité de thon présente un risque ou non pour la santé, regrette l’ONG Bloom. Elle recommande donc « d’éviter la consommation de boîtes de thon et de privilégier du thon frais », moins concentré en mercure.\r\n\r\n\r\n\r\n« Pas dangereuses pour la santé », selon la Fiac\r\n\r\nEn fin de journée ce mardi, la Fiac (Fédération des industries d\'aliments conservés) a réagi à la polémique dans un communiqué. Rappelant que les professionnels de la filière respectaient une réglementation exigeante, la Fédération a précisé qu\'elle comptait « plus de 2 700 résultats d’analyses collectés depuis huit ans au niveau français. La teneur en mercure se situe en moyenne à 0,2 mg/kg, soit nettement sous la limite 1,0 mg/kg autorisée pour assurer la sécurité des consommateurs. Les résultats obtenus sont ainsi très en deçà de ceux diffusés de façon alarmiste par les associations militantes qui n’ont testé que 148 échantillons pour toute l’Europe ».\r\n\r\nEt de conclure : « Les professionnels de la filière du thon en conserve tiennent donc à rétablir la réalité des faits : les boîtes de thon en conserve mises sur le marché ne sont pas dangereuses pour la santé. »\r\n', 1, 'imgArticle/672136250cd6d.png', '2024-10-29 20:23:17', '2024-10-29 20:23:17');

-- --------------------------------------------------------

--
-- Structure de la table `article_categories`
--

DROP TABLE IF EXISTS `article_categories`;
CREATE TABLE IF NOT EXISTS `article_categories` (
  `article_id` int NOT NULL,
  `category_id` int NOT NULL,
  PRIMARY KEY (`article_id`,`category_id`),
  KEY `article_categories_ibfk_2` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `article_categories`
--

INSERT INTO `article_categories` (`article_id`, `category_id`) VALUES
(1, 2);

-- --------------------------------------------------------

--
-- Structure de la table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Sans Categorie'),
(2, 'Consommation');

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
  KEY `author_id` (`author_id`),
  KEY `article_id` (`article_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `comments`
--

INSERT INTO `comments` (`id`, `author_id`, `content`, `article_id`, `created_at`, `updated_at`) VALUES
(1, 2, 'Je ne mange pas trop de thon en conserve', 1, '2024-10-29 20:26:16', '2024-10-29 20:26:16');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `username` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `avatar_url` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `email`, `username`, `password`, `avatar_url`, `admin`) VALUES
(1, 'admin@localhost.fr', 'Administrateur', '$2y$10$./roSAXhILN7VEtdWZZWiuRv.LDIxCqPncFISeg3364yNHhmEgAYa', 'uploads/672134b8799cd.png', 1),
(2, 'utilisateur@localhost.fr', 'Utilisateur', '$2y$10$umMIRQIfhsL.WYAC8Urt3u/rkVs41lxWTU4CNfQWgJRErrDv5frra', 'uploads/6721367e060cf.png', 0);

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
