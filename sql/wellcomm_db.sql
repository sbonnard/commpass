-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : db
-- Généré le : lun. 23 sep. 2024 à 14:48
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
-- Base de données : `wellcomm_db`
--
CREATE DATABASE IF NOT EXISTS `wellcomm_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `wellcomm_db`;

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
(1, 'Lumosphère', 2, '#ff772e'),
(2, 'Vélocitix', 2, '#ECFF12'),
(3, 'Stellar Threads', 2, '#24ff99'),
(4, 'Aurélys', 2, '#7a1564'),
(5, 'Nexmus', 3, '#44277A'),
(6, 'Cafés Geronimo', 3, '#5F3838'),
(7, 'Fripig', 4, '#4DBEBE'),
(8, 'Maxstock', 4, '#40BC54');

-- --------------------------------------------------------

--
-- Structure de la table `campaign`
--

CREATE TABLE `campaign` (
  `id_campaign` int NOT NULL,
  `campaign_name` varchar(100) NOT NULL,
  `budget` decimal(15,2) NOT NULL,
  `date` datetime NOT NULL,
  `id_user` int NOT NULL,
  `id_company` int DEFAULT NULL,
  `id_target` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `campaign`
--

INSERT INTO `campaign` (`id_campaign`, `campaign_name`, `budget`, `date`, `id_user`, `id_company`, `id_target`) VALUES
(1, 'Soldes d\'été', 25000.00, '2024-06-27 00:00:00', 3, 2, 3),
(2, 'Promos d\'hiver', 18000.00, '2023-11-14 00:00:00', 3, 2, 3),
(3, 'Tous plus verts', 25000.00, '2024-02-01 00:00:00', 3, 2, 2),
(13, 'Salon du luminaire', 178000.10, '2022-05-05 00:00:00', 4, 3, 1),
(14, 'Lancement Groupe Pignon', 21000.00, '2023-05-14 00:00:00', 5, 4, 1),
(15, 'Tous plus verts', 25000.00, '2022-02-01 00:00:00', 3, 2, 2),
(16, 'Fête des pères', 8500.00, '2025-06-09 00:00:00', 6, 2, 3),
(17, 'Soldes d\'hiver', 7000.00, '2024-11-15 00:00:00', 4, 3, 3),
(18, 'Soldes d\'hiver', 4500.00, '2024-11-01 00:00:00', 5, 4, 3),
(19, 'Green days', 12500.00, '2024-01-04 00:00:00', 5, 4, 2),
(20, 'Salon des métiers', 7000.00, '2024-09-23 00:00:00', 6, 2, 1);

-- --------------------------------------------------------

--
-- Structure de la table `company`
--

CREATE TABLE `company` (
  `id_company` int NOT NULL,
  `company_name` varchar(100) NOT NULL,
  `annual_budget` decimal(15,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `company`
--

INSERT INTO `company` (`id_company`, `company_name`, `annual_budget`) VALUES
(1, 'Toile de Com', 0.00),
(2, 'FakeBusiness', 87000.00),
(3, 'Luminase', 52000.00),
(4, 'Groupe Pignon', 14800.00),
(5, 'Nerexam Solutions', 72000.00);

-- --------------------------------------------------------

--
-- Structure de la table `operation`
--

CREATE TABLE `operation` (
  `id_operation` int NOT NULL,
  `description` varchar(255) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `date_` date NOT NULL,
  `id_campaign` int NOT NULL,
  `id_company` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `operation`
--

INSERT INTO `operation` (`id_operation`, `description`, `price`, `date_`, `id_campaign`, `id_company`) VALUES
(4, 'Flocage de totebags', 690.00, '2023-11-12', 2, 2),
(5, 'Flyers soldes d\'été, 1000 exemplaires', 300.25, '2024-06-12', 1, 2),
(6, 'Flyers soldes d\'hiver, 1000 exemplaires', 300.25, '2023-11-12', 2, 2),
(8, 'Vitrine web mise à jour', 227.92, '2023-11-29', 2, 2),
(9, 'Avatars du personnel', 205.00, '2024-01-15', 3, 2),
(10, 'Encart presse dans la Manche Libre', 75.00, '2024-02-05', 3, 2),
(11, 'Réseaux sociaux', 144.85, '2024-03-02', 3, 2),
(12, 'Mise en lumière de la société Luminase avec un beau panneau néon', 14250.28, '2024-09-12', 13, 3),
(14, 'Test Stellar Threads', 1450.00, '2024-09-20', 1, 2),
(15, 'Graphisme affiches fête des père', 1200.00, '2025-06-09', 16, 2),
(17, 'Campagne radio Tendance Ouest', 800.00, '2024-11-15', 17, 3),
(18, 'Campagne radio Tendance Ouest', 800.00, '2024-09-15', 17, 3),
(19, 'Prints affiches soldes abribus ', 452.50, '2024-11-17', 17, 3),
(20, 'Affiches soldes', 450.00, '2024-11-09', 18, 4),
(21, 'Matraquage publicitaire sur le green washing', 800.00, '2024-01-10', 19, 4),
(22, 'Publicité Linkedin sur la politique verte de l\'entreprise', 1300.80, '2024-03-07', 3, 2),
(23, 'Panneau Roll-up', 75.00, '2024-09-23', 20, 2),
(24, 'Panneau Roll-up', 75.00, '2024-09-23', 20, 2),
(25, 'Panneau Roll-up', 75.00, '2024-09-23', 20, 2),
(26, 'Panneau Roll-Up', 75.00, '2024-09-23', 20, 2),
(27, 'Flocage polos Fakebusiness', 527.55, '2024-09-23', 20, 2);

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
(4, 1),
(5, 1),
(6, 1),
(8, 1),
(9, 1),
(10, 1),
(11, 1),
(23, 1),
(24, 2),
(14, 3),
(15, 3),
(22, 3),
(25, 3),
(4, 4),
(8, 4),
(26, 4),
(18, 5),
(19, 5),
(17, 6),
(20, 7),
(21, 8);

-- --------------------------------------------------------

--
-- Structure de la table `partner`
--

CREATE TABLE `partner` (
  `id_partners` int NOT NULL,
  `partner_name` varchar(255) NOT NULL,
  `id_partner_sector` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `partner`
--

INSERT INTO `partner` (`id_partners`, `partner_name`, `id_partner_sector`) VALUES
(1, 'La Manche Libre', 1),
(2, 'Ouest France', 1),
(3, 'Tendance Ouest', 2);

-- --------------------------------------------------------

--
-- Structure de la table `partner_sector`
--

CREATE TABLE `partner_sector` (
  `id_partner_sector` int NOT NULL,
  `sector` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `partner_sector`
--

INSERT INTO `partner_sector` (`id_partner_sector`, `sector`) VALUES
(1, 'presse'),
(2, 'radio'),
(3, 'print');

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
-- Structure de la table `type_operation`
--

CREATE TABLE `type_operation` (
  `id_type_operation` int NOT NULL,
  `operation` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `type_operation`
--

INSERT INTO `type_operation` (`id_type_operation`, `operation`) VALUES
(4, 'undefined');

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
(6, 'mchampion6', 'Manon', 'Champion', '$2y$10$cCubd56otzIKiNdKRj3i.u4Crxaxz586Ygn5QmVszFF91z2SgMqFS', 'manon.champion@fakebusiness.com', '0600102030', 1, 0, 2);

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `brand`
--
ALTER TABLE `brand`
  ADD PRIMARY KEY (`id_brand`);

--
-- Index pour la table `campaign`
--
ALTER TABLE `campaign`
  ADD PRIMARY KEY (`id_campaign`),
  ADD KEY `id_user` (`id_user`),
  ADD KEY `id_target` (`id_target`),
  ADD KEY `id_company` (`id_company`);

--
-- Index pour la table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`id_company`);

--
-- Index pour la table `operation`
--
ALTER TABLE `operation`
  ADD PRIMARY KEY (`id_operation`),
  ADD KEY `id_campaign` (`id_campaign`),
  ADD KEY `id_company` (`id_company`);

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
  ADD PRIMARY KEY (`id_partners`),
  ADD KEY `id_partner_sector` (`id_partner_sector`);

--
-- Index pour la table `partner_sector`
--
ALTER TABLE `partner_sector`
  ADD PRIMARY KEY (`id_partner_sector`);

--
-- Index pour la table `target`
--
ALTER TABLE `target`
  ADD PRIMARY KEY (`id_target`);

--
-- Index pour la table `type_operation`
--
ALTER TABLE `type_operation`
  ADD PRIMARY KEY (`id_type_operation`);

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
  MODIFY `id_brand` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT pour la table `campaign`
--
ALTER TABLE `campaign`
  MODIFY `id_campaign` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT pour la table `company`
--
ALTER TABLE `company`
  MODIFY `id_company` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT pour la table `operation`
--
ALTER TABLE `operation`
  MODIFY `id_operation` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT pour la table `partner`
--
ALTER TABLE `partner`
  MODIFY `id_partners` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `partner_sector`
--
ALTER TABLE `partner_sector`
  MODIFY `id_partner_sector` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `target`
--
ALTER TABLE `target`
  MODIFY `id_target` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT pour la table `type_operation`
--
ALTER TABLE `type_operation`
  MODIFY `id_type_operation` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id_user` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `campaign`
--
ALTER TABLE `campaign`
  ADD CONSTRAINT `campaign_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id_user`),
  ADD CONSTRAINT `campaign_ibfk_2` FOREIGN KEY (`id_target`) REFERENCES `target` (`id_target`);

--
-- Contraintes pour la table `operation`
--
ALTER TABLE `operation`
  ADD CONSTRAINT `operation_ibfk_1` FOREIGN KEY (`id_campaign`) REFERENCES `campaign` (`id_campaign`);

--
-- Contraintes pour la table `operation_brand`
--
ALTER TABLE `operation_brand`
  ADD CONSTRAINT `operation_brand_ibfk_1` FOREIGN KEY (`id_operation`) REFERENCES `operation` (`id_operation`),
  ADD CONSTRAINT `operation_brand_ibfk_2` FOREIGN KEY (`id_brand`) REFERENCES `brand` (`id_brand`);

--
-- Contraintes pour la table `partner`
--
ALTER TABLE `partner`
  ADD CONSTRAINT `partner_ibfk_1` FOREIGN KEY (`id_partner_sector`) REFERENCES `partner_sector` (`id_partner_sector`);

--
-- Contraintes pour la table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`id_company`) REFERENCES `company` (`id_company`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
