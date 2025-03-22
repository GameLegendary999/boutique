-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : sam. 22 mars 2025 à 21:46
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `tp4azure_boutique`
--

-- --------------------------------------------------------

--
-- Structure de la table `clients`
--

DROP TABLE IF EXISTS `clients`;
CREATE TABLE IF NOT EXISTS `clients` (
  `id_client` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `adresse` text,
  `mot_de_passe` varchar(255) NOT NULL,
  PRIMARY KEY (`id_client`),
  UNIQUE KEY `email` (`email`(100))
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `clients`
--

INSERT INTO `clients` (`id_client`, `nom`, `email`, `adresse`, `mot_de_passe`) VALUES
(3, 'Jean Dupont', 'jeandupont@gmail.com', NULL, '1234'),
(4, 'Test', 'test@gmail.com', NULL, '12345');

-- --------------------------------------------------------

--
-- Structure de la table `commandes`
--

DROP TABLE IF EXISTS `commandes`;
CREATE TABLE IF NOT EXISTS `commandes` (
  `id_commande` int NOT NULL AUTO_INCREMENT,
  `id_client` int NOT NULL,
  `description` varchar(255) NOT NULL,
  `date` datetime DEFAULT CURRENT_TIMESTAMP,
  `total` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id_commande`),
  KEY `id_client` (`id_client`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `commandes`
--

INSERT INTO `commandes` (`id_commande`, `id_client`, `description`, `date`, `total`) VALUES
(11, 3, 'Chemise en lin bleu (x1)', '2025-03-22 14:44:39', 39.99);

-- --------------------------------------------------------

--
-- Structure de la table `panier`
--

DROP TABLE IF EXISTS `panier`;
CREATE TABLE IF NOT EXISTS `panier` (
  `id_panier` int NOT NULL AUTO_INCREMENT,
  `id_client` int NOT NULL,
  `id_produit` int NOT NULL,
  `quantité` int NOT NULL,
  `date_ajout` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `prix` decimal(10,2) NOT NULL DEFAULT '0.00',
  `nom` varchar(255) NOT NULL,
  PRIMARY KEY (`id_panier`),
  KEY `id_client` (`id_client`),
  KEY `id_produit` (`id_produit`)
) ENGINE=MyISAM AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `produits`
--

DROP TABLE IF EXISTS `produits`;
CREATE TABLE IF NOT EXISTS `produits` (
  `id_produit` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `description` text,
  `prix` decimal(10,2) NOT NULL,
  `stock` int NOT NULL,
  `image_path` varchar(255) NOT NULL,
  PRIMARY KEY (`id_produit`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `produits`
--

INSERT INTO `produits` (`id_produit`, `nom`, `description`, `prix`, `stock`, `image_path`) VALUES
(1, 'Chemise blanche classique', 'Chemise blanche en coton, idéale pour le bureau', 29.99, 50, 'image\\chemise blanche.webp'),
(2, 'Chemise à carreaux rouges', 'Chemise à carreaux rouges et noirs, style décontracté', 34.99, 30, 'image\\chemise carreau rouge.jpg'),
(3, 'Chemise en lin bleu', 'Chemise légère en lin bleu, parfaite pour l\'été', 39.99, 19, 'image\\chemise- bleu.jpg'),
(4, 'Chemise noire slim fit', 'Chemise noire ajustée pour un look élégant', 49.99, 25, 'image\\chemise noire.jpg'),
(5, 'Chemise rayée bleue et blanche', 'Chemise rayée, style professionnel et moderne', 27.99, 40, 'image\\chemise-business-blanche.jpg'),
(6, 'Chemise en jean', 'Chemise en denim, durable et intemporelle', 44.99, 15, 'image\\chemise-jeen.jpg'),
(7, 'Chemise hawaïenne', 'Chemise à motifs floraux, idéale pour les vacances', 24.99, 60, 'image\\chemise-hawaii.png'),
(8, 'Chemise à manches courtes', 'Chemise blanche à manches courtes, basique et confortable', 19.99, 100, 'image\\chemise manche courte.jpg'),
(9, 'Chemise en flanelle', 'Chemise chaude et douce, parfaite pour l\'hiver', 35.99, 30, 'image\\chemise flanelle.jpg'),
(10, 'Chemise violette élégante', 'Chemise violette en soie, idéale pour les occasions spéciales', 59.99, 10, 'image\\chemise viollette.jpg');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
