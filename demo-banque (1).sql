-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le : mer. 29 mars 2023 à 22:21
-- Version du serveur : 10.4.27-MariaDB
-- Version de PHP : 8.0.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `demo-banque`
--

-- --------------------------------------------------------

--
-- Structure de la table `document_identification`
--

CREATE TABLE `document_identification` (
  `id` int(11) NOT NULL,
  `utilisateur` int(11) NOT NULL,
  `titre` varchar(100) NOT NULL,
  `numero` varchar(100) NOT NULL,
  `date_emission` date NOT NULL,
  `date_expiration` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `document_identification`
--

INSERT INTO `document_identification` (`id`, `utilisateur`, `titre`, `numero`, `date_emission`, `date_expiration`) VALUES
(13, 1, 'g', '5', '0000-00-00', '0000-00-00');

-- --------------------------------------------------------

--
-- Structure de la table `livrets`
--

CREATE TABLE `livrets` (
  `id` int(11) NOT NULL,
  `envoyeur` int(11) NOT NULL,
  `receveur` int(11) NOT NULL,
  `montant` double NOT NULL,
  `montant_reel` double NOT NULL,
  `frais` double NOT NULL,
  `date_transactions` datetime NOT NULL DEFAULT current_timestamp(),
  `notes` varchar(255) NOT NULL,
  `transaction_inhabitualle` int(11) NOT NULL,
  `status` int(12) NOT NULL DEFAULT 2
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `livrets`
--

INSERT INTO `livrets` (`id`, `envoyeur`, `receveur`, `montant`, `montant_reel`, `frais`, `date_transactions`, `notes`, `transaction_inhabitualle`, `status`) VALUES
(1, 1, 2, 10.45, -11, 0.55, '2023-02-24 10:00:34', '', 0, 0),
(2, 1, 2, 95, -100, 5, '2023-02-24 10:00:51', '', 0, 1),
(3, 1, 2, 95, -100, 5, '2023-02-25 06:52:53', '', 0, 2),
(4, 1, 2, 95, -100, 5, '2023-03-19 19:50:09', '', 0, 2);

-- --------------------------------------------------------

--
-- Structure de la table `renseignement_personnel`
--

CREATE TABLE `renseignement_personnel` (
  `id` int(11) NOT NULL,
  `utilisateur` int(11) NOT NULL,
  `cellulaire` varchar(16) NOT NULL,
  `apt` int(11) NOT NULL,
  `no_municipal` int(11) NOT NULL,
  `rue` varchar(100) NOT NULL,
  `ville` varchar(100) NOT NULL,
  `province` varchar(100) NOT NULL,
  `pays` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `renseignement_personnel`
--

INSERT INTO `renseignement_personnel` (`id`, `utilisateur`, `cellulaire`, `apt`, `no_municipal`, `rue`, `ville`, `province`, `pays`) VALUES
(6, 1, '66', 66, 77, '0', 'fg', 'fg', 'fg');

-- --------------------------------------------------------

--
-- Structure de la table `utilisateurs`
--

CREATE TABLE `utilisateurs` (
  `id` int(11) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `pseudonyme` varchar(100) NOT NULL,
  `date_naissance` date NOT NULL,
  `courriel` varchar(255) DEFAULT NULL,
  `motdepasse` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Déchargement des données de la table `utilisateurs`
--

INSERT INTO `utilisateurs` (`id`, `nom`, `prenom`, `pseudonyme`, `date_naissance`, `courriel`, `motdepasse`) VALUES
(1, 'fh', 'fh', 'Esthy', '0000-00-00', 'esther@test.com', 'dc76e9f0c0006e8f919e0c515c66dbba3982f785'),
(2, '', '', 'Esthy', '0000-00-00', 'romanie@test.com', 'dc76e9f0c0006e8f919e0c515c66dbba3982f785');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `document_identification`
--
ALTER TABLE `document_identification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilisateur` (`utilisateur`);

--
-- Index pour la table `livrets`
--
ALTER TABLE `livrets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `envoyeur` (`envoyeur`,`receveur`),
  ADD KEY `receveur` (`receveur`);

--
-- Index pour la table `renseignement_personnel`
--
ALTER TABLE `renseignement_personnel`
  ADD PRIMARY KEY (`id`),
  ADD KEY `utilisateur` (`utilisateur`);

--
-- Index pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `document_identification`
--
ALTER TABLE `document_identification`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT pour la table `livrets`
--
ALTER TABLE `livrets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT pour la table `renseignement_personnel`
--
ALTER TABLE `renseignement_personnel`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT pour la table `utilisateurs`
--
ALTER TABLE `utilisateurs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `document_identification`
--
ALTER TABLE `document_identification`
  ADD CONSTRAINT `document_identification_ibfk_1` FOREIGN KEY (`utilisateur`) REFERENCES `utilisateurs` (`id`);

--
-- Contraintes pour la table `livrets`
--
ALTER TABLE `livrets`
  ADD CONSTRAINT `livrets_ibfk_1` FOREIGN KEY (`envoyeur`) REFERENCES `utilisateurs` (`id`),
  ADD CONSTRAINT `livrets_ibfk_2` FOREIGN KEY (`receveur`) REFERENCES `utilisateurs` (`id`);

--
-- Contraintes pour la table `renseignement_personnel`
--
ALTER TABLE `renseignement_personnel`
  ADD CONSTRAINT `renseignement_personnel_ibfk_1` FOREIGN KEY (`utilisateur`) REFERENCES `utilisateurs` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
