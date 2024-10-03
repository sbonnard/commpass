-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : db
-- Généré le : jeu. 03 oct. 2024 à 08:40
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
(2, 'Vélocitix', 2, '#ECFF12'),
(3, 'Stellar Threads', 2, '#24ff99'),
(4, 'Aurélys', 2, '#2ecbff'),
(5, 'Nexmus', 3, '#44277A'),
(6, 'Cafés Geronimo', 3, '#5F3838'),
(7, 'Fripig', 4, '#4DBEBE'),
(8, 'Maxstock', 4, '#40BC54'),
(10, 'ProNerexam Versicherung', 5, '#fbff24'),
(11, 'Nerexam Schutz', 5, '#ff9238'),
(12, 'VersicherungsNexus', 5, '#DF0000'),
(13, 'AlpCare', 6, '#1dd7c2'),
(14, 'Joueur du Grenier', 8, '#fbff00'),
(15, 'Bazar du Grenier', 8, '#00d604'),
(16, 'L\'Arcadia', 8, '#0074cc'),
(17, 'Pignottes', 4, '#ff61a0'),
(19, 'Bayou Bang Bang', 11, '#09c38b'),
(20, 'Bayou Crusher', 11, '#ff6714');

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
(2, 2023, 40000, 2),
(3, 2023, 38000, 3),
(4, 2024, 45001, 3),
(5, 2024, 19000, 11);

-- --------------------------------------------------------

--
-- Structure de la table `campaign`
--

CREATE TABLE `campaign` (
  `id_campaign` int NOT NULL,
  `campaign_name` varchar(100) NOT NULL,
  `budget` decimal(15,2) NOT NULL,
  `date_start` datetime NOT NULL,
  `id_user` int NOT NULL,
  `id_company` int DEFAULT NULL,
  `id_target` int NOT NULL,
  `id_user_TDC` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `campaign`
--

INSERT INTO `campaign` (`id_campaign`, `campaign_name`, `budget`, `date_start`, `id_user`, `id_company`, `id_target`, `id_user_TDC`) VALUES
(1, 'Soldes d\'été', 10500.00, '2024-06-27 00:00:00', 3, 2, 3, 2),
(2, 'Promos d\'hiver', 18000.00, '2023-11-14 00:00:00', 3, 2, 3, 1),
(3, 'Tous plus verts', 25000.00, '2024-02-01 00:00:00', 3, 2, 2, 1),
(13, 'Salon du luminaire', 178000.10, '2022-05-05 00:00:00', 4, 3, 1, 2),
(14, 'Lancement Groupe Pignon', 21000.00, '2023-05-14 00:00:00', 5, 4, 1, 2),
(15, 'Tous plus verts', 25000.00, '2022-02-01 00:00:00', 3, 2, 2, 2),
(16, 'Fête des pères', 8500.00, '2025-06-09 00:00:00', 6, 2, 3, 2),
(18, 'Soldes d\'hiver', 4500.00, '2024-11-01 00:00:00', 5, 4, 3, 2),
(19, 'Green days', 12500.00, '2024-01-04 00:00:00', 5, 4, 2, 2),
(20, 'Salon des métiers', 0.00, '2024-09-23 00:00:00', 6, 2, 1, 2),
(26, 'Test 445', 8500.00, '2024-09-25 00:00:00', 7, 5, 1, 2),
(28, 'Peau neuve', 16000.00, '2024-11-06 00:00:00', 8, 6, 2, 2),
(29, 'Test 8548', 4500.00, '2023-02-22 00:00:00', 3, 2, 1, 2),
(30, 'Zevent', 1000.00, '2024-09-05 00:00:00', 15, 8, 3, 2),
(31, 'Ouverture de l\'Arcadia', 2500.00, '2024-09-30 00:00:00', 15, 8, 1, 2),
(32, 'Soldes d\'automne', 7800.00, '2024-09-30 00:00:00', 4, 3, 1, 2),
(37, 'Rentrée scolaire', 2000.00, '2024-09-02 00:00:00', 7, 5, 2, 1),
(39, 'test', 16000.00, '2024-10-01 00:00:00', 7, 5, 1, 1),
(41, 'Bayou Festival', 8500.00, '2024-11-01 00:00:00', 19, 11, 1, 1),
(42, 'Bluegrass Show', 2000.00, '2024-12-01 00:00:00', 19, 11, 3, 1);

-- --------------------------------------------------------

--
-- Structure de la table `company`
--

CREATE TABLE `company` (
  `id_company` int NOT NULL,
  `company_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `company`
--

INSERT INTO `company` (`id_company`, `company_name`) VALUES
(1, 'Toile de Com'),
(2, 'FakeBusiness'),
(3, 'Luminase'),
(4, 'Groupe Pignon'),
(5, 'Nerexam Solutions'),
(6, 'Helvionics SA'),
(7, 'En un Écl\'Hair'),
(8, 'JDG Production'),
(11, 'Jambalaya In The Bayou');

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
(15, 'goodies');

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
(12, 'Mise en lumière de la société Luminase avec un beau panneau néon', 14250.28, '2024-09-12', 13, 3, 6, NULL),
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
(48, 'Affiches abribus', 900.00, '2024-10-01', 37, 5, 6, 0),
(50, 'test', 6712.00, '2024-10-02', 39, 5, 4, 5),
(51, 'test', 273.00, '2024-10-02', 39, 5, 5, 0),
(52, 'achtung', 2762.00, '2024-10-02', 39, 5, 3, 3),
(53, 'Pataplouf', 6252.00, '2024-10-03', 39, 5, 7, 0),
(54, 'Flocage de marmite à Jambalaya', 2133.25, '2024-11-01', 41, 11, 15, 0),
(55, 'Affichages Boulevard du Maréchal Juin à Caen', 1300.00, '2024-10-30', 41, 11, 11, 0),
(56, 'Pub vidéo', 1982.00, '2024-11-27', 42, 11, 4, 5),
(57, 'Pub Tendance Ouest', 800.00, '2024-11-02', 42, 11, 3, 3);

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
(53, 0),
(56, 0),
(4, 1),
(5, 1),
(6, 1),
(8, 1),
(9, 1),
(10, 1),
(23, 1),
(24, 2),
(30, 2),
(37, 2),
(14, 3),
(15, 3),
(22, 3),
(25, 3),
(4, 4),
(8, 4),
(26, 4),
(29, 4),
(31, 6),
(20, 7),
(21, 8),
(33, 10),
(52, 10),
(34, 11),
(48, 11),
(51, 11),
(35, 12),
(50, 12),
(40, 13),
(43, 14),
(46, 14),
(44, 15),
(41, 16),
(54, 19),
(55, 20),
(57, 20);

-- --------------------------------------------------------

--
-- Structure de la table `partner`
--

CREATE TABLE `partner` (
  `id_partner` int NOT NULL,
  `partner_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `partner`
--

INSERT INTO `partner` (`id_partner`, `partner_name`) VALUES
(1, 'La Manche Libre'),
(2, 'Ouest France'),
(3, 'Tendance Ouest'),
(4, 'Radio TSF 98'),
(5, 'France 3 Basse-Normandie');

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
  `id_company` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id_user`, `username`, `firstname`, `lastname`, `password`, `email`, `phone`, `client`, `boss`, `id_company`) VALUES
(1, 'sbonnard94', 'Sébastien', 'Bonnard', '$2y$10$ZMkpWcRvhkY0PHUZPlb8COU3sCBTRqIKdvvK4sZd2U84wH2HHNPwK', 'sebastien.bonnard94@gmail.com', '0608118078', 0, 0, 1),
(2, 'alemaitre2', 'Alain', 'Lemaître', '$2y$10$KXm8N435ocnGKFY0keVBrudUCjNHwab4DLAvNvCK5hTFRoCPZUrk6', 'alain.lemaitre@toiledecom.fr', '0614011401', 0, 1, 1),
(3, 'jcarriere3', 'Julie', 'Carrière', '$2y$10$cCubd56otzIKiNdKRj3i.u4Crxaxz586Ygn5QmVszFF91z2SgMqFS', 'julie.carriere@fakebusiness.com', '0600102030', 1, 1, 2),
(4, 'mhamelin4', 'Marius', 'Hamelin', '$2y$10$ZMkpWcRvhkY0PHUZPlb8COU3sCBTRqIKdvvK4sZd2U84wH2HHNPwK', 'marius.hamelin@luminase.com', '0600102030', 1, 1, 3),
(5, 'ppignon5', 'Pascale', 'Pignon', '$2y$10$ZMkpWcRvhkY0PHUZPlb8COU3sCBTRqIKdvvK4sZd2U84wH2HHNPwK', 'pascale.pignon@pignon-group.com', '0600102030', 1, 1, 4),
(6, 'mchampion6', 'Manon', 'Champion', '$2y$10$cCubd56otzIKiNdKRj3i.u4Crxaxz586Ygn5QmVszFF91z2SgMqFS', 'manon.champion@fakebusiness.com', '0600102030', 1, 0, 2),
(7, 'hziegler7', 'Helmut', 'Ziegler', '$2y$10$cCubd56otzIKiNdKRj3i.u4Crxaxz586Ygn5QmVszFF91z2SgMqFS', 'helmut.ziegler@nerexam.com', '0600102030', 1, 1, 5),
(8, 'ldubois8', 'Léon', 'Dubois', '$2y$10$0FCcKygW2AKjhbM93u2qg.GchfDaImvsU7dsV8vy8zLYSuV9dlOYe', 'duboisleon@helvionics.com', '0601020304', 1, 1, 6),
(9, 'jmuller9', 'Johannes', 'Müller', '$2y$10$ZrJPfxjaIWGVJEfu6VjQqeRjeqXrBpuaMl6ks96rYasTgda1Qp7aK', 'johannesmuller@nerexam.com', '0614141414', 1, 0, 5),
(12, 'vriche74', 'Veronica', 'Riche', '$2y$10$H85YqMiJjN9Z4FT15z32pe3cjwbbv8gGVFA4tiJHXN73DGKply6XW', 'veronica-riche@helvionics.com', '0601020304', 1, 0, 6),
(14, 'jnogue18', 'Justine', 'Noguera', '$2y$10$UDoOVNZngCeJ.YDWPXpzvetirugKpgan/W21/pxomQbDDomFj2O9y', 'jnogue@helvionics.com', '0614011401', 1, 0, 6),
(15, 'jmolas', 'Fréderic', 'Molas', '$2y$10$RTsnwh1JROCpoQ/FXvhM8Ou9ELhvKcN4FnfSEhWNoOnaf2WGqqsFa', 'jdgprod@jdr.fr', '0601020304', 1, 1, 8),
(16, 'srassiat14', 'Sébastien', 'Rassiat', '$2y$10$dDWqQnpSNuIMLPjCqn5L5OdKqjYoYEEpLGV1CTrJRtuRqBVjLYzcS', 'sebrassiat@jdegprod.com', '0601020304', 1, 1, 8),
(19, 'shawkins62', 'Simeon', 'Hawkins', '$2y$10$KKB2I44p.2WSyREQLLnUVelgJZHq9BRMdSmqnz4icJ00m/3gHrICG', 'simeon.hawkins@jambalaya.com', '0688112222', 1, 1, 11);

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
  MODIFY `id_brand` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `budgets`
--
ALTER TABLE `budgets`
  MODIFY `id_budget` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `campaign`
--
ALTER TABLE `campaign`
  MODIFY `id_campaign` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT pour la table `company`
--
ALTER TABLE `company`
  MODIFY `id_company` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `media`
--
ALTER TABLE `media`
  MODIFY `id_media` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT pour la table `operation`
--
ALTER TABLE `operation`
  MODIFY `id_operation` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT pour la table `partner`
--
ALTER TABLE `partner`
  MODIFY `id_partner` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `target`
--
ALTER TABLE `target`
  MODIFY `id_target` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

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
