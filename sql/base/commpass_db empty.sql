-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : db
-- Généré le : jeu. 03 oct. 2024 à 12:17
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
(0, 'Toutes les marques', 1, '#D35DB5');

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
(1, 'Toile de Com');

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

-- --------------------------------------------------------

--
-- Structure de la table `operation_brand`
--

CREATE TABLE `operation_brand` (
  `id_operation` int NOT NULL,
  `id_brand` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

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
(3, 'Tendance Ouest');

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
(2, 'fcosson', 'Fabrice', 'Cosson', '$2y$10$KXm8N435ocnGKFY0keVBrudUCjNHwab4DLAvNvCK5hTFRoCPZUrk6', 'fabrice@toiledecom.fr', '0600000000', 0, 1, 1);

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
  MODIFY `id_campaign` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

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
  MODIFY `id_operation` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=60;

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
