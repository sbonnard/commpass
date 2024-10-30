-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : db
-- Généré le : mer. 30 oct. 2024 à 12:26
-- Version du serveur : 8.0.37
-- Version de PHP : 8.2.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `commpass_db`
--
CREATE DATABASE IF NOT EXISTS `commpass_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `commpass_db`;

-- --------------------------------------------------------

--
-- Structure de la table `brand`
--

CREATE TABLE `brand` (
  `id_brand` int NOT NULL,
  `brand_name` varchar(100) NOT NULL,
  `id_company` int DEFAULT NULL,
  `legend_colour_hex` varchar(7) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `brand`
--

INSERT INTO `brand` (`id_brand`, `brand_name`, `id_company`, `legend_colour_hex`) VALUES
(0, 'Toutes les marques', 1, '#D35DB5'),
(1, 'Lumosphère', 2, '#d17a00'),
(2, 'Vélocitix', 2, '#ffdd00'),
(3, 'Stellar Threads', 2, '#24ff99'),
(4, 'Aurélys', 2, '#02d6d9'),
(5, 'Nexmus', 3, '#44277A'),
(6, 'Cafés Geronimo', 3, '#5F3838'),
(7, 'Fripig', 4, '#4DBEBE'),
(8, 'Maxstock', 4, '#40BC54'),
(10, 'ProNerexam Versicherung', 5, '#fbff24'),
(11, 'Nerexam Schutz', 5, '#ff9238'),
(12, 'VersicherungsNexus', 5, '#DF0000'),
(13, 'AlpCare', 6, '#1dd7c2'),
(14, 'Joueur du Grenier', 8, '#ffea00'),
(15, 'Bazar du Grenier', 8, '#00d604'),
(16, 'L\'Arcadia', 8, '#0074cc'),
(17, 'Pignottes', 4, '#ff61a0'),
(19, 'Bayou Bang Bang', 11, '#09c38b'),
(20, 'Bayou Crusher', 11, '#ff6714'),
(21, 'Hair Channel', 7, '#9d3983'),
(22, 'Alpollon', 6, '#b58df2'),
(23, 'AlpAga', 6, '#e7e97c'),
(25, 'AlpAge', 6, '#9aee6d'),
(26, 'HairIsson', 7, '#f03dd8'),
(27, 'Down South', 11, '#cc0000');

-- --------------------------------------------------------

--
-- Structure de la table `budgets`
--

CREATE TABLE `budgets` (
  `id_budget` int NOT NULL,
  `year` int NOT NULL,
  `annual_budget` decimal(15,0) NOT NULL DEFAULT '0',
  `id_company` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `budgets`
--

INSERT INTO `budgets` (`id_budget`, `year`, `annual_budget`, `id_company`) VALUES
(1, 2024, 22000, 2),
(2, 2023, 0, 2),
(3, 2023, 38000, 3),
(4, 2024, 45001, 3),
(5, 2024, 19000, 11),
(7, 2024, 2500, 13),
(12, 2024, 0, 18),
(13, 2024, 0, 19),
(14, 2024, 0, 20),
(24, 2024, 2500, 30);

-- --------------------------------------------------------

--
-- Structure de la table `campaign`
--

CREATE TABLE `campaign` (
  `id_campaign` int NOT NULL,
  `campaign_name` varchar(100) NOT NULL,
  `budget` decimal(15,2) NOT NULL,
  `date_start` datetime NOT NULL,
  `date_end` datetime DEFAULT NULL,
  `id_user` int NOT NULL,
  `id_company` int DEFAULT NULL,
  `id_target` int NOT NULL,
  `id_user_TDC` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `campaign`
--

INSERT INTO `campaign` (`id_campaign`, `campaign_name`, `budget`, `date_start`, `date_end`, `id_user`, `id_company`, `id_target`, `id_user_TDC`) VALUES
(1, 'Soldes d\'été', 10500.00, '2024-06-27 00:00:00', '2024-07-14 10:45:55', 3, 2, 3, 2),
(2, 'Promos d\'hiver', 18000.00, '2023-11-14 00:00:00', '2024-01-04 10:46:23', 6, 2, 3, 1),
(3, 'Tous plus verts', 25000.00, '2024-02-01 00:00:00', '2024-05-02 10:46:55', 3, 2, 2, 1),
(14, 'Lancement Groupe Pignon', 21000.00, '2023-05-14 00:00:00', '2023-06-14 10:48:09', 5, 4, 1, 2),
(15, 'Tous plus verts', 25000.00, '2022-02-01 00:00:00', '2022-05-02 10:48:41', 3, 2, 2, 2),
(16, 'Fête des pères', 8500.00, '2025-06-09 00:00:00', '2025-06-15 10:48:58', 6, 2, 3, 2),
(18, 'Soldes d\'hiver', 4500.00, '2024-11-01 00:00:00', '2025-01-15 10:49:27', 5, 4, 3, 2),
(19, 'Green days', 12500.00, '2024-01-04 00:00:00', '2024-07-01 10:49:57', 5, 4, 2, 2),
(20, 'Salon des métiers', 0.00, '2024-09-23 00:00:00', '2024-09-28 10:50:14', 6, 2, 1, 2),
(26, 'Test 445', 8500.00, '2024-09-25 00:00:00', '2024-10-03 10:50:25', 7, 5, 1, 2),
(28, 'Peau neuve', 16000.00, '2024-11-06 00:00:00', '2025-02-12 10:50:37', 8, 6, 2, 2),
(29, 'Test 8548', 4500.00, '2023-02-22 00:00:00', '2023-04-04 10:51:06', 3, 2, 1, 2),
(30, 'Zevent', 1000.00, '2024-09-06 00:00:00', '2024-09-08 10:51:19', 15, 8, 3, 2),
(31, 'Ouverture de l\'Arcadia', 2500.00, '2024-09-30 00:00:00', '2024-10-01 10:51:46', 15, 8, 1, 2),
(32, 'Soldes d\'automne', 7800.00, '2024-09-30 00:00:00', '2024-10-15 10:51:53', 4, 3, 1, 2),
(41, 'Bayou Festival', 8500.00, '2024-11-01 00:00:00', '2024-11-16 10:52:30', 19, 11, 1, 1),
(42, 'Bluegrass Show', 2000.00, '2024-12-01 00:00:00', '2024-12-24 10:52:41', 19, 11, 3, 1),
(43, 'Jambalaya In Caen', 2200.00, '2024-12-31 00:00:00', '2025-01-31 00:00:00', 19, 11, 2, 1),
(44, 'Foire au jambon', 0.00, '2024-10-23 00:00:00', '2024-10-27 00:00:00', 5, 4, 3, 2),
(50, 'Big Bang Bayou', 5000.00, '2024-10-31 00:00:00', '2024-11-03 00:00:00', 19, 11, 2, 1),
(51, 'Ouverture du salon', 1200.00, '2024-10-28 00:00:00', '2024-10-31 00:00:00', 23, 7, 1, 2),
(53, 'Ouverture crèche', 0.00, '2024-10-29 00:00:00', '2024-10-31 00:00:00', 24, 13, 1, 1),
(54, 'Soldes d\'hiver', 0.00, '2024-10-29 00:00:00', '2024-11-22 00:00:00', 25, 18, 1, 2);

-- --------------------------------------------------------

--
-- Structure de la table `company`
--

CREATE TABLE `company` (
  `id_company` int NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `logo_url` varchar(255) DEFAULT 'logo/default.webp',
  `unique_brand` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `company`
--

INSERT INTO `company` (`id_company`, `company_name`, `logo_url`, `unique_brand`) VALUES
(1, 'Toile de Com', 'logo/toiledecom.webp', 0),
(2, 'FakeBusiness', 'logo/fakebusiness.png', 0),
(3, 'Luminase', 'logo/default.webp', 0),
(4, 'Groupe Pignon', 'logo/pignon.png', 0),
(5, 'Nerexam Solutions', 'logo/default.webp', 0),
(6, 'Helvionics SA', 'logo/Designer (4).png', 0),
(7, 'En un Écl\'Hair', 'logo/Designer (2).png', 0),
(8, 'JDG Production', 'logo/Logo_JoueurDuGrenier.webp', 0),
(11, 'Jambalaya In The Bayou', 'logo/default.webp', 0),
(13, 'Les P&#039;tits Loups', 'logo/Designer (3).png', 1),
(18, 'Logotopia', 'logo/Designer (5).png', 1),
(19, 'Kicketts', 'logo/default.webp', 0),
(20, 'Croquilles', 'logo/default.webp', 1),
(30, 'Jiggy Airlines', 'logo/Designer (6)_1730283833.png', 1);

-- --------------------------------------------------------

--
-- Structure de la table `media`
--

CREATE TABLE `media` (
  `id_media` int NOT NULL,
  `media_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `media`
--

INSERT INTO `media` (`id_media`, `media_name`) VALUES
(1, 'presse'),
(2, 'print'),
(3, 'radio'),
(4, 'télévision'),
(5, 'réseaux sociaux'),
(6, 'affichage'),
(7, 'web'),
(8, 'cinema'),
(9, 'mobile'),
(10, 'vidéos en ligne'),
(11, 'publicité extérieure dynamique'),
(12, 'évènement'),
(13, 'guerilla marketing'),
(14, 'marketing direct'),
(15, 'goodies'),
(17, 'SMS'),
(18, 'Flocage'),
(19, 'Marketing agressif');

-- --------------------------------------------------------

--
-- Structure de la table `operation`
--

CREATE TABLE `operation` (
  `id_operation` int NOT NULL,
  `description` varchar(255) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `operation_date` date NOT NULL,
  `id_campaign` int NOT NULL,
  `id_company` int DEFAULT NULL,
  `id_media` int DEFAULT NULL,
  `id_partner` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `operation`
--

INSERT INTO `operation` (`id_operation`, `description`, `price`, `operation_date`, `id_campaign`, `id_company`, `id_media`, `id_partner`) VALUES
(4, 'Flocage de totebags', 690.00, '2023-11-12', 2, 2, 2, NULL),
(5, 'Flyers soldes d\'été, 1000 exemplaires', 300.25, '2024-06-12', 1, 2, 2, NULL),
(6, 'Flyers soldes d\'hiver, 1000 exemplaires', 300.25, '2023-11-12', 2, 2, 2, NULL),
(8, 'Vitrine web mise à jour', 227.92, '2023-11-29', 2, 2, 7, NULL),
(9, 'Avatars du personnel', 205.00, '2024-01-15', 3, 2, 7, NULL),
(10, 'Encart presse dans la Manche Libre', 75.00, '2024-02-05', 3, 2, 1, 1),
(14, 'Test Stellar Threads', 1450.00, '2024-09-20', 1, 2, 1, NULL),
(15, 'Graphisme affiches fête des père', 1200.00, '2025-06-09', 16, 2, 2, NULL),
(20, 'Affiches soldes', 450.00, '2024-11-09', 18, 4, 2, NULL),
(21, 'Matraquage publicitaire sur le green washing', 800.00, '2024-01-10', 19, 4, 5, NULL),
(22, 'Publicité Linkedin sur la politique verte de l\'entreprise', 1300.80, '2024-03-07', 3, 2, 5, NULL),
(23, 'Panneau Roll-up', 75.00, '2024-09-23', 20, 2, 6, NULL),
(24, 'Panneau Roll-up', 75.00, '2024-09-23', 20, 2, 6, NULL),
(25, 'Panneau Roll-up', 75.00, '2024-09-23', 20, 2, 6, NULL),
(26, 'Panneau Roll-Up', 75.00, '2024-09-23', 20, 2, 6, NULL),
(27, 'Flocage polos Fakebusiness', 827.55, '2024-09-23', 20, 2, 2, 0),
(28, 'Flocage de stylos 4 couleurs', 27.00, '2024-09-24', 20, 2, 2, 0),
(29, 'Etiquetage de 240 bouteilles de champagne personnalisées', 6000.00, '2024-09-24', 20, 2, 2, 0),
(30, 'Publicité radio', 896.00, '2024-09-24', 1, 2, 3, 3),
(31, 'Test 880', 700.00, '2024-09-24', 21, 3, 3, 3),
(33, 'test', 1483.00, '2024-09-25', 26, 5, 3, 0),
(34, 'test 2', 450.00, '2024-09-28', 26, 5, 6, 0),
(35, 'test 3', 789.00, '2024-10-17', 26, 5, 5, 0),
(36, 'Refonte du logo original', 350.00, '2024-09-28', 27, 6, 2, 0),
(37, 'Panneau d\'affichage à Caen', 822.00, '2024-09-27', 3, 2, 6, 4),
(38, 'Pub télé', 1222.00, '2024-09-27', 1, 2, 4, 5),
(39, 'Redesign d\'un logo pour la marque', 1230.00, '2024-11-01', 28, 6, 2, 0),
(40, 'Diffusion spots radio', 2700.00, '2024-11-05', 28, 6, 3, 3),
(41, 'Spot radio', 452.00, '2024-09-30', 31, 8, 3, 3),
(42, 'Panneau découpés Seb du Grenier et Fred du Grenier', 325.00, '2024-09-30', 31, 8, 6, 0),
(43, 'Flocage chaise de gaming', 52.00, '2024-09-05', 30, 8, 2, 0),
(44, 'Redesign logo Bazar du grenier', 852.00, '2024-08-31', 30, 8, 2, 0),
(46, 'Impression d\'une nappe à motifs feuilles de palmier', 125.00, '2024-09-07', 30, 8, 2, 0),
(54, 'Flocage de marmite à Jambalaya', 2133.25, '2024-11-01', 41, 11, 15, 0),
(55, 'Affichages Boulevard du Maréchal Juin à Caen', 1300.00, '2024-10-30', 41, 11, 11, 0),
(56, 'Pub vidéo', 1982.00, '2024-11-27', 42, 11, 4, 5),
(57, 'Pub Tendance Ouest', 800.00, '2024-11-02', 42, 11, 3, 3),
(58, 'test annual_budget si j\'ajoute une opération en janvier 2024', 800.00, '2024-01-02', 2, 2, 10, 2),
(59, 'Publications intagram, linkedin et facebook', 800.00, '2024-12-31', 43, 11, 5, 0),
(60, 'truc', 3800.00, '2023-10-13', 29, 2, 10, 0),
(61, 'truc', 963.00, '2024-10-24', 15, 2, 10, 0),
(62, 'bidule', 963.00, '2024-10-09', 15, 2, 5, 0),
(63, 'chose', 986.00, '2024-10-16', 15, 2, 7, 2),
(64, 'test 78521', 1300.80, '2024-10-23', 32, 3, 5, 0),
(65, 'Test', 1300.00, '2024-10-25', 20, 2, 11, 0),
(67, 'Green washing forcing', 1940.00, '2024-10-24', 19, 4, 4, 5),
(68, 'On est tellement écolos', 1200.00, '2024-09-12', 19, 4, 3, 4),
(69, 'test', 2.00, '2023-10-04', 14, 4, 5, 0),
(70, 'test 5145485', 1300.00, '2024-10-16', 20, 2, 4, 6),
(71, 'Flocage des frites ', 800.00, '2024-10-31', 50, 11, 2, 0),
(73, 'Serviette brodées', 800.00, '2024-10-29', 51, 7, 15, 0),
(74, 'Tablier brodés', 450.00, '2024-10-29', 51, 7, 2, 0),
(75, 'Grande bâche extérieure', 252.00, '2024-10-30', 53, 13, 2, 0),
(76, 'Publicité radio', 952.00, '2024-11-01', 54, 18, 3, 3),
(77, 'Truc sur D8', 1300.00, '2024-10-31', 51, 7, 4, 9);

-- --------------------------------------------------------

--
-- Structure de la table `operation_brand`
--

CREATE TABLE `operation_brand` (
  `id_operation` int NOT NULL,
  `id_brand` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `operation_brand`
--

INSERT INTO `operation_brand` (`id_operation`, `id_brand`) VALUES
(27, 0),
(28, 0),
(36, 0),
(38, 0),
(39, 0),
(42, 0),
(56, 0),
(75, 0),
(76, 0),
(4, 1),
(5, 1),
(6, 1),
(9, 1),
(10, 1),
(23, 1),
(62, 1),
(24, 2),
(30, 2),
(37, 2),
(63, 2),
(14, 3),
(15, 3),
(22, 3),
(25, 3),
(58, 3),
(60, 3),
(65, 3),
(8, 4),
(26, 4),
(29, 4),
(61, 4),
(70, 4),
(31, 6),
(64, 6),
(20, 7),
(69, 7),
(21, 8),
(68, 8),
(33, 10),
(34, 11),
(35, 12),
(40, 13),
(43, 14),
(46, 14),
(44, 15),
(41, 16),
(67, 17),
(54, 19),
(71, 19),
(55, 20),
(57, 20),
(59, 20),
(73, 21),
(77, 21),
(74, 26);

-- --------------------------------------------------------

--
-- Structure de la table `partner`
--

CREATE TABLE `partner` (
  `id_partner` int NOT NULL,
  `partner_name` varchar(255) NOT NULL,
  `partner_colour` varchar(7) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `partner`
--

INSERT INTO `partner` (`id_partner`, `partner_name`, `partner_colour`) VALUES
(1, 'La Manche Libre', '#25D8FF'),
(2, 'Ouest France', '#FF3225'),
(3, 'Tendance Ouest', '#BA0B00'),
(4, 'Radio TSF 98', '#40BC54'),
(5, 'France 3 Basse-Normandie', '#0008C7'),
(6, 'TF1', '#8B00BC'),
(7, 'France 2', '#EA4C72'),
(9, 'D8', '#77342B');

-- --------------------------------------------------------

--
-- Structure de la table `target`
--

CREATE TABLE `target` (
  `id_target` int NOT NULL,
  `target_com` varchar(50) NOT NULL,
  `target_legend_hex` varchar(7) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `target`
--

INSERT INTO `target` (`id_target`, `target_com`, `target_legend_hex`) VALUES
(1, 'Faire connaître - La notoriété', '#44277A'),
(2, 'Faire aimer - L\'image', '#842078'),
(3, 'Faire agir - Les comportements', '#DA428F');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id_user` int NOT NULL,
  `username` varchar(100) NOT NULL,
  `firstname` varchar(50) NOT NULL,
  `lastname` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(10) NOT NULL,
  `client` tinyint(1) NOT NULL DEFAULT '1',
  `boss` tinyint(1) DEFAULT '0',
  `id_company` int NOT NULL,
  `enabled` tinyint(1) DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id_user`, `username`, `firstname`, `lastname`, `password`, `email`, `phone`, `client`, `boss`, `id_company`, `enabled`) VALUES
(1, 'sbonnard94', 'Sébastien', 'Bonnard', '$2y$10$ZMkpWcRvhkY0PHUZPlb8COU3sCBTRqIKdvvK4sZd2U84wH2HHNPwK', 'sebastien.bonnard94@gmail.com', '0608118078', 0, 0, 1, 1),
(2, 'alemaitre2', 'Alain', 'Lemaître', '$2y$10$KXm8N435ocnGKFY0keVBrudUCjNHwab4DLAvNvCK5hTFRoCPZUrk6', 'alain.lemaitre@toiledecom.fr', '0614011401', 0, 1, 1, 1),
(3, 'jcarriere3', 'Julie', 'Carrière', '$2y$10$cCubd56otzIKiNdKRj3i.u4Crxaxz586Ygn5QmVszFF91z2SgMqFS', 'julie.carriere@fakebusiness.com', '0600102030', 1, 1, 2, 1),
(4, 'mhamelin4', 'Marius', 'Hamelin', '$2y$10$ZMkpWcRvhkY0PHUZPlb8COU3sCBTRqIKdvvK4sZd2U84wH2HHNPwK', 'marius.hamelin@luminase.com', '0600102030', 1, 1, 3, 1),
(5, 'ppignon5', 'Pascale', 'Pignon', '$2y$10$ZMkpWcRvhkY0PHUZPlb8COU3sCBTRqIKdvvK4sZd2U84wH2HHNPwK', 'pascale.pignon@pignon-group.com', '0600102030', 1, 1, 4, 1),
(6, 'mchampion6', 'Manon', 'Champion', '$2y$10$cCubd56otzIKiNdKRj3i.u4Crxaxz586Ygn5QmVszFF91z2SgMqFS', 'manon.champion@fakebusiness.com', '0600102030', 1, 0, 2, 1),
(7, 'hziegler7', 'Helmut', 'Ziegler', '$2y$10$cCubd56otzIKiNdKRj3i.u4Crxaxz586Ygn5QmVszFF91z2SgMqFS', 'helmut.ziegler@nerexam.com', '0600102030', 1, 1, 5, 1),
(8, 'ldubois8', 'Léon', 'Dubois', '$2y$10$0FCcKygW2AKjhbM93u2qg.GchfDaImvsU7dsV8vy8zLYSuV9dlOYe', 'duboisleon@helvionics.com', '0601020304', 1, 1, 6, 1),
(9, 'jmuller9', 'Johannes', 'Müller', '$2y$10$ZrJPfxjaIWGVJEfu6VjQqeRjeqXrBpuaMl6ks96rYasTgda1Qp7aK', 'johannesmuller@nerexam.com', '0614141414', 1, 0, 5, 0),
(12, 'vriche74', 'Veronica', 'Riche', '$2y$10$H85YqMiJjN9Z4FT15z32pe3cjwbbv8gGVFA4tiJHXN73DGKply6XW', 'veronica-riche@helvionics.com', '0601020304', 1, 0, 6, 1),
(14, 'jnogue18', 'Justine', 'Noguera', '$2y$10$UDoOVNZngCeJ.YDWPXpzvetirugKpgan/W21/pxomQbDDomFj2O9y', 'jnogue@helvionics.com', '0614011401', 1, 0, 6, 1),
(15, 'fmolas', 'Fréderic', 'Molas', '$2y$10$RTsnwh1JROCpoQ/FXvhM8Ou9ELhvKcN4FnfSEhWNoOnaf2WGqqsFa', 'jdgprod@jdr.fr', '0601020304', 1, 1, 8, 1),
(16, 'srassiat14', 'Sébastien', 'Rassiat', '$2y$10$dDWqQnpSNuIMLPjCqn5L5OdKqjYoYEEpLGV1CTrJRtuRqBVjLYzcS', 'sebrassiat@jdegprod.com', '0601020304', 1, 1, 8, 1),
(19, 'shawkins62', 'Simeon', 'Hawkins', '$2y$10$KKB2I44p.2WSyREQLLnUVelgJZHq9BRMdSmqnz4icJ00m/3gHrICG', 'simeon.hawkins@jambalaya.com', '0688112222', 1, 1, 11, 1),
(20, 'jlafleur', 'Jasmin', 'Lafleur', '$2y$10$vx6s.PL5HAPIZjfm.Ljqm.DjhkxfarQYJtQRFp2SJd1876iT6owsC', 'jasminlafleur@helvionics.com', '0614011401', 0, 0, 6, 0),
(21, 'jcrash', 'Johnny', 'Crash', '$2y$10$f/lZxt3MBL1T1wQvAa8IVu4BpYW6126zx2mpKiJpnT0xBCCm68Kpa', 'jcrash@luminase.fr', '0606060069', 0, 0, 3, 1),
(22, 'jvienne', 'Justine', 'Vienne', '$2y$10$bkbiiKC/UMthtgIJa4E0rOJkx.VbE2iQ.UgRqqiwtjkcOkR6toRXG', 'justinevienne@pignon.fr', '0614011401', 1, 0, 4, 1),
(23, 'dmarie', 'Delphine', 'Marie', '$2y$10$YrYO4x3tbOlaZ1aP66UGcO6mF6WyeiK06TyL54xTCQjHNRoNKHPnW', 'dmarie@uneclhair.com', '0606060606', 1, 1, 7, 1),
(24, 'mblotreau', 'Maëlys', 'Blotreau', '$2y$10$tSalJTeXntK8auQQ4JtN1ednSww4zR7E6CjgeODVHkRiDA6CVUC2i', 'maelys-blotreau@ptitloups.fr', '0614011401', 1, 1, 13, 1),
(25, 'clouve', 'Cassiopée ', 'Louve', '$2y$10$7NSimWbLE0CP1gFYai2GFuIo6sc1aCdibqPkk9SYKSenJ8dtsGC7K', 'cassiolouve@logotopia.com', '0665552121', 1, 1, 18, 1),
(26, 'hjafa', 'Humphrey', 'Jafa', '$2y$10$FQ2jr6kc/60cybhsgm.zgeKbA./UwsvO0KzRXUrtdv.l49KNZlKG6', 'jhumphey@jiggy-flight.com', '0688112222', 1, 1, 30, 1),
(27, 'aswiss', 'Alenna', 'Swiss', '$2y$10$1wtBOWGs1/WiFpkOOUnZ/uQnUWs5u/qEtCIV3GdrT6IF9yNxexuWC', 'aswiss@jiggy-flight.com', '0614141414', 1, 0, 30, 1);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `brand`
--
ALTER TABLE `brand`
  ADD PRIMARY KEY (`id_brand`),
  ADD KEY `fk_company_brand` (`id_company`);

--
-- Index pour la table `budgets`
--
ALTER TABLE `budgets`
  ADD PRIMARY KEY (`id_budget`),
  ADD KEY `id_company` (`id_company`);

--
-- Index pour la table `campaign`
--
ALTER TABLE `campaign`
  ADD PRIMARY KEY (`id_campaign`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_target` (`id_target`),
  ADD KEY `id_company` (`id_company`),
  ADD KEY `fk_campain_userTDC` (`id_user_TDC`);

--
-- Index pour la table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`id_company`);

--
-- Index pour la table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`id_media`);

--
-- Index pour la table `operation`
--
ALTER TABLE `operation`
  ADD PRIMARY KEY (`id_operation`),
  ADD KEY `id_campaign` (`id_campaign`),
  ADD KEY `id_company` (`id_company`),
  ADD KEY `id_media` (`id_media`),
  ADD KEY `id_partner` (`id_partner`);

--
-- Index pour la table `operation_brand`
--
ALTER TABLE `operation_brand`
  ADD PRIMARY KEY (`id_operation`,`id_brand`),
  ADD KEY `id_brand` (`id_brand`);

--
-- Index pour la table `partner`
--
ALTER TABLE `partner`
  ADD PRIMARY KEY (`id_partner`);

--
-- Index pour la table `target`
--
ALTER TABLE `target`
  ADD PRIMARY KEY (`id_target`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id_user`),
  ADD KEY `id_company` (`id_company`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `brand`
--
ALTER TABLE `brand`
  MODIFY `id_brand` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT pour la table `budgets`
--
ALTER TABLE `budgets`
  MODIFY `id_budget` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT pour la table `campaign`
--
ALTER TABLE `campaign`
  MODIFY `id_campaign` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT pour la table `company`
--
ALTER TABLE `company`
  MODIFY `id_company` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT pour la table `media`
--
ALTER TABLE `media`
  MODIFY `id_media` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT pour la table `operation`
--
ALTER TABLE `operation`
  MODIFY `id_operation` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=78;

--
-- AUTO_INCREMENT pour la table `partner`
--
ALTER TABLE `partner`
  MODIFY `id_partner` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT pour la table `target`
--
ALTER TABLE `target`
  MODIFY `id_target` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `brand`
--
ALTER TABLE `brand`
  ADD CONSTRAINT `fk_company_brand` FOREIGN KEY (`id_company`) REFERENCES `company` (`id_company`);

--
-- Contraintes pour la table `budgets`
--
ALTER TABLE `budgets`
  ADD CONSTRAINT `budgets_ibfk_1` FOREIGN KEY (`id_company`) REFERENCES `company` (`id_company`);

--
-- Contraintes pour la table `campaign`
--
ALTER TABLE `campaign`
  ADD CONSTRAINT `campaign_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `campaign_ibfk_2` FOREIGN KEY (`id_target`) REFERENCES `target` (`id_target`),
  ADD CONSTRAINT `fk_campain_userTDC` FOREIGN KEY (`id_user_TDC`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `fk_company_campaign` FOREIGN KEY (`id_company`) REFERENCES `company` (`id_company`);

--
-- Contraintes pour la table `operation`
--
ALTER TABLE `operation`
  ADD CONSTRAINT `fk_company` FOREIGN KEY (`id_company`) REFERENCES `company` (`id_company`),
  ADD CONSTRAINT `fk_media` FOREIGN KEY (`id_media`) REFERENCES `media` (`id_media`);

--
-- Contraintes pour la table `operation_brand`
--
ALTER TABLE `operation_brand`
  ADD CONSTRAINT `operation_brand_ibfk_1` FOREIGN KEY (`id_operation`) REFERENCES `operation` (`id_operation`),
  ADD CONSTRAINT `operation_brand_ibfk_2` FOREIGN KEY (`id_brand`) REFERENCES `brand` (`id_brand`);

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`id_company`) REFERENCES `company` (`id_company`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
