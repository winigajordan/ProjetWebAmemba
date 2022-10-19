-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : localhost:3306
-- Généré le : mer. 19 oct. 2022 à 16:54
-- Version du serveur : 5.7.33
-- Version de PHP : 8.1.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `amemba_end`
--

-- --------------------------------------------------------

--
-- Structure de la table `abonne`
--

CREATE TABLE `abonne` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mail` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `achat`
--

CREATE TABLE `achat` (
  `id` int(11) NOT NULL,
  `commande_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `admin`
--

INSERT INTO `admin` (`id`) VALUES
(1);

-- --------------------------------------------------------

--
-- Structure de la table `article`
--

CREATE TABLE `article` (
  `id` int(11) NOT NULL,
  `auteur_id` int(11) NOT NULL,
  `categorie_article_id` int(11) NOT NULL,
  `titre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `contenu` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `statut` tinyint(1) NOT NULL,
  `lisibilite` tinyint(1) NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `published_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `refused` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `categorie_article`
--

CREATE TABLE `categorie_article` (
  `id` int(11) NOT NULL,
  `libelle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `categorie_evenement`
--

CREATE TABLE `categorie_evenement` (
  `id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `categorie_evenement`
--

INSERT INTO `categorie_evenement` (`id`, `name`) VALUES
(9, 'Réunion'),
(10, 'Rencontre'),
(11, 'Assemblée génégrale'),
(12, 'Evenement');

-- --------------------------------------------------------

--
-- Structure de la table `categorie_produit`
--

CREATE TABLE `categorie_produit` (
  `id` int(11) NOT NULL,
  `libelle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `categorie_produit`
--

INSERT INTO `categorie_produit` (`id`, `libelle`) VALUES
(1, 'Categorie Test0');

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

CREATE TABLE `client` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `commande`
--

CREATE TABLE `commande` (
  `id` int(11) NOT NULL,
  `client_id` int(11) NOT NULL,
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `etat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prix_total` int(11) NOT NULL,
  `adresse` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `commentaire`
--

CREATE TABLE `commentaire` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `evenement_id` int(11) DEFAULT NULL,
  `article_id` int(11) DEFAULT NULL,
  `sujet_id` int(11) DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `is_visible` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cotisation`
--

CREATE TABLE `cotisation` (
  `id` int(11) NOT NULL,
  `titre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `montant` int(11) NOT NULL,
  `solde` int(11) NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `etat` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cotisation_membre`
--

CREATE TABLE `cotisation_membre` (
  `cotisation_id` int(11) NOT NULL,
  `membre_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `cotisation_transaction`
--

CREATE TABLE `cotisation_transaction` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `demande`
--

CREATE TABLE `demande` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mail` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `promotion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pays` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ville` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL,
  `etat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `depot`
--

CREATE TABLE `depot` (
  `id` int(11) NOT NULL,
  `reference` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `etat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `detail_commande`
--

CREATE TABLE `detail_commande` (
  `id` int(11) NOT NULL,
  `commande_id` int(11) NOT NULL,
  `produit_id` int(11) NOT NULL,
  `quantite` int(11) NOT NULL,
  `prix` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `entreprise`
--

CREATE TABLE `entreprise` (
  `id` int(11) NOT NULL,
  `proprietaire_id` int(11) NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `domaine` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `adresses` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `etat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `evenement`
--

CREATE TABLE `evenement` (
  `id` int(11) NOT NULL,
  `categorie_id` int(11) DEFAULT NULL,
  `date` date NOT NULL,
  `start_at` time NOT NULL,
  `end_at` time NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `image`
--

CREATE TABLE `image` (
  `id` int(11) NOT NULL,
  `produit_id` int(11) DEFAULT NULL,
  `evenement_id` int(11) DEFAULT NULL,
  `article_id` int(11) DEFAULT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `image`
--

INSERT INTO `image` (`id`, `produit_id`, `evenement_id`, `article_id`, `path`) VALUES
(1, 1, NULL, NULL, 'test.png');

-- --------------------------------------------------------

--
-- Structure de la table `letter`
--

CREATE TABLE `letter` (
  `id` int(11) NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `titre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `membre`
--

CREATE TABLE `membre` (
  `id` int(11) NOT NULL,
  `promotion` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pays` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ville` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `statut` tinyint(1) NOT NULL,
  `cv` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profile` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `profession` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `secteur` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `entreprise` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role_amicale` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bac` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `univ` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `diplome` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `experience` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bio` longtext COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `membre_bureau`
--

CREATE TABLE `membre_bureau` (
  `id` int(11) NOT NULL,
  `fonction_id` int(11) NOT NULL,
  `nom_complet` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `etat` tinyint(1) NOT NULL,
  `img` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `offre_emplois`
--

CREATE TABLE `offre_emplois` (
  `id` int(11) NOT NULL,
  `membre_id` int(11) DEFAULT NULL,
  `titre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `domaine` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `create_at` date NOT NULL,
  `end_at` date NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `etat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `page_about`
--

CREATE TABLE `page_about` (
  `id` int(11) NOT NULL,
  `mission_titre` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `mission_text` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `mot_titre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mot_contenu` longtext COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `page_about`
--

INSERT INTO `page_about` (`id`, `mission_titre`, `mission_text`, `mot_titre`, `mot_contenu`) VALUES
(1, 'Mission de l\'Amicale de MEMBA', '<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut nec volutpat elit. Mauris sollicitudin, est ac tristique euismod, velit turpis vehicula dui, at fringilla dui orci non nibh. Quisque congue tellus lacus, et tristique leo tristique sit amet. Mauris tincidunt faucibus mi eu tempus. Sed congue risus sed fermentum dapibus. In feugiat egestas bibendum. Aenean vitae risus id ante ornare vehicula a sed sem. Sed nibh mauris, fermentum et dignissim a, tempus ut urna.</p>', 'Quelques mots de la présidente', '\'\'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Magna donec nulla eu purus sed pharetra. Enim vel sed posuere ut massa ut. Non sit imperdiet enim at diam orci augue sit ultrices. Rhoncus et ipsum, quis egestas\'\'');

-- --------------------------------------------------------

--
-- Structure de la table `page_accueil`
--

CREATE TABLE `page_accueil` (
  `id` int(11) NOT NULL,
  `carousel_titre1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `carousel_titre2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `carousel_titre3` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `carousel_text1` longtext COLLATE utf8mb4_unicode_ci,
  `carousel_text2` longtext COLLATE utf8mb4_unicode_ci,
  `carousel_text3` longtext COLLATE utf8mb4_unicode_ci,
  `carousel_image1` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `carousel_image2` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `carousel_image3` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mission_titre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mission_text` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `chiffre_alumni` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `chiffre_projet` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `chiffre_fonds` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `chiffre_alumni_text` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `chiffre_projet_text` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `chiffre_fonds_text` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entreprise_titre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entreprise_texte` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `temoignage_auteur1` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `temoignage_auteur2` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `temoignage_auteur3` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `temoignage_auteur4` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `temoignage_titre1` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `temoignage_titre2` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `temoignage_text1` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `temoignage_text2` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `temoignage_text4` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `temoignage_text3` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `membre_titre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `membre_text` longtext COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `page_accueil`
--

INSERT INTO `page_accueil` (`id`, `carousel_titre1`, `carousel_titre2`, `carousel_titre3`, `carousel_text1`, `carousel_text2`, `carousel_text3`, `carousel_image1`, `carousel_image2`, `carousel_image3`, `mission_titre`, `mission_text`, `chiffre_alumni`, `chiffre_projet`, `chiffre_fonds`, `chiffre_alumni_text`, `chiffre_projet_text`, `chiffre_fonds_text`, `entreprise_titre`, `entreprise_texte`, `temoignage_auteur1`, `temoignage_auteur2`, `temoignage_auteur3`, `temoignage_auteur4`, `temoignage_titre1`, `temoignage_titre2`, `temoignage_text1`, `temoignage_text2`, `temoignage_text4`, `temoignage_text3`, `membre_titre`, `membre_text`) VALUES
(1, 'test ', 'LE RESEAU ALUMNI FAIT LE LIEN', 'LE RESEAU ALUMNI FAIT LE LIEN', ' Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut nec volutpat elit. Mauris sollicitudin, est ac tristique euismod, velit turpis vehicula dui, at fringilla dui orci non nibh. Quisque congue tellus lacus, et tristique leo tristique sit amet. Mauris tincidunt faucibus mi eu tempus. Sed congue risus sed fermentum dapibus. In feugiat egestas bibendum. Aenean vitae risus id ante ornare vehicula a sed sem. Sed nibh mauris, fermentum et dignissim a, tempus ut urna. ', ' Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut nec volutpat elit. Mauris sollicitudin, est ac tristique euismod, velit turpis vehicula dui, at fringilla dui orci non nibh. Quisque congue tellus lacus, et tristique leo tristique sit amet. Mauris tincidunt faucibus mi eu tempus. Sed congue risus sed fermentum dapibus. In feugiat egestas bibendum. Aenean vitae risus id ante ornare vehicula a sed sem. Sed nibh mauris, fermentum et dignissim a, tempus ut urna.', ' Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut nec volutpat elit. Mauris sollicitudin, est ac tristique euismod, velit turpis vehicula dui, at fringilla dui orci non nibh. Quisque congue tellus lacus, et tristique leo tristique sit amet. Mauris tincidunt faucibus mi eu tempus. Sed congue risus sed fermentum dapibus. In feugiat egestas bibendum. Aenean vitae risus id ante ornare vehicula a sed sem. Sed nibh mauris, fermentum et dignissim a, tempus ut urna.', '62d188adee06f.png', '62d187f3a7b51.png', '62d187f3a8b87.png', 'Mission de l\'Amicale ', '<p>L’Amicale a trois missions principales :</p><ul><li>Promouvoir le cadre socio-éducatif du Lycée d’Excellence Mariama Bâ de Gorée</li><li>Cultiver et promouvoir le leadership féminin, le réseautage, l’excellence</li><li>Venir en aide aux populations vulnérables</li></ul>', '1200', '50000', '50000', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Magna donec nulla eu purus sed pharetra.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Magna donec nulla eu purus sed pharetra.', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Magna donec nulla eu purus sed pharetra.', 'Vous êtes une entreprise ? oui', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Magna donec nulla eu purus sed pharetra. Enim vel sed posuere ut massa ut. Non sit imperdiet enim at diam orci augue sit ultrices. Rhoncus et ipsum, quis egestas scelerisque viverra id sit. Ullamcorper enim ultrices suspendisse odio.', 'Nom et prenom', 'Nom et prenom', 'Auteur 3', 'Nom et prenom', 'A.S nous raconte son parcours de jeune femme entrepreneur', 'A.S nous raconte son parcours de jeune femme entrepreneur', 'Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint. Velit officia consequat duis enim velit mollit. Exercitation veniam consequat sunt nostrud amet.', 'Amet minim mollit non deserunt ullamco est sit aliqua dolor do amet sint. Velit officia consequat duis enim velit mollit. Exercitation veniam consequat sunt nostrud amet.', '“ Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut.“', '“ Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut.“', 'Vous être une ancienne', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Magna donec nulla eu purus sed pharetra. Enim vel sed posuere ut massa ut. Non sit imperdiet enim at diam orci augue');

-- --------------------------------------------------------

--
-- Structure de la table `partenaire`
--

CREATE TABLE `partenaire` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `logo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `etat` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `partenaire`
--

INSERT INTO `partenaire` (`id`, `nom`, `description`, `logo`, `etat`) VALUES
(1, 'Hôpital Militaire de Ouakam', 'Hôpital Militaire de Ouakam', '63362e65a298f.png', 1),
(2, 'AIMES-AFRIQUE SENEGAL', 'AIMES-AFRIQUE SENEGAL', '63362f4ee844f.png', 1);

-- --------------------------------------------------------

--
-- Structure de la table `partenariat`
--

CREATE TABLE `partenariat` (
  `id` int(11) NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entreprise` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mail` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `requete` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `etat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reponse` longtext COLLATE utf8mb4_unicode_ci,
  `date_reponse` datetime DEFAULT NULL,
  `telephone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `postes_bureau`
--

CREATE TABLE `postes_bureau` (
  `id` int(11) NOT NULL,
  `libelle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `produit`
--

CREATE TABLE `produit` (
  `id` int(11) NOT NULL,
  `categorie_id` int(11) NOT NULL,
  `libelle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prix` int(11) NOT NULL,
  `qte_stock` int(11) NOT NULL,
  `etat` tinyint(1) NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `produit`
--

INSERT INTO `produit` (`id`, `categorie_id`, `libelle`, `prix`, `qte_stock`, `etat`, `description`, `slug`) VALUES
(1, 1, 'Produit Test', 1000, 5, 1, 'Ceci est le test de cette description', 'slugTest');

-- --------------------------------------------------------

--
-- Structure de la table `realisation`
--

CREATE TABLE `realisation` (
  `id` int(11) NOT NULL,
  `titre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `etat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `reset_password`
--

CREATE TABLE `reset_password` (
  `id` int(11) NOT NULL,
  `mail` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mode` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `etat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `sujet`
--

CREATE TABLE `sujet` (
  `id` int(11) NOT NULL,
  `auteur_id` int(11) NOT NULL,
  `thematique_id` int(11) NOT NULL,
  `contenu` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `published_at` datetime DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
  `etat` tinyint(1) NOT NULL,
  `lisibilite` tinyint(1) NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `super_admin`
--

CREATE TABLE `super_admin` (
  `id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `thematique`
--

CREATE TABLE `thematique` (
  `id` int(11) NOT NULL,
  `libelle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `transaction`
--

CREATE TABLE `transaction` (
  `id` int(11) NOT NULL,
  `wallet_id` int(11) NOT NULL,
  `date` datetime NOT NULL,
  `montant` double NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dtype` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` json NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dtype` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `nom`, `prenom`, `dtype`) VALUES
(1, 'dev@admin.com', '[\"ROLE_ADMIN\"]', '$2y$13$FZz/6a7oFB/hHrrKtbaKVeKPbPvun/ohfAWpLdQr4ccefH4dhse9K', 'Admin', 'Dev', 'admin');

-- --------------------------------------------------------

--
-- Structure de la table `wallet`
--

CREATE TABLE `wallet` (
  `id` int(11) NOT NULL,
  `membre_id` int(11) DEFAULT NULL,
  `solde` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `abonne`
--
ALTER TABLE `abonne`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `achat`
--
ALTER TABLE `achat`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_26A9845682EA2E54` (`commande_id`);

--
-- Index pour la table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `article`
--
ALTER TABLE `article`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_23A0E6660BB6FE6` (`auteur_id`),
  ADD KEY `IDX_23A0E66EC5D4C30` (`categorie_article_id`);

--
-- Index pour la table `categorie_article`
--
ALTER TABLE `categorie_article`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `categorie_evenement`
--
ALTER TABLE `categorie_evenement`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `categorie_produit`
--
ALTER TABLE `categorie_produit`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `client`
--
ALTER TABLE `client`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `commande`
--
ALTER TABLE `commande`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_6EEAA67D19EB6921` (`client_id`);

--
-- Index pour la table `commentaire`
--
ALTER TABLE `commentaire`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_67F068BCA76ED395` (`user_id`),
  ADD KEY `IDX_67F068BCFD02F13` (`evenement_id`),
  ADD KEY `IDX_67F068BC7294869C` (`article_id`),
  ADD KEY `IDX_67F068BC7C4D497E` (`sujet_id`);

--
-- Index pour la table `cotisation`
--
ALTER TABLE `cotisation`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `cotisation_membre`
--
ALTER TABLE `cotisation_membre`
  ADD PRIMARY KEY (`cotisation_id`,`membre_id`),
  ADD KEY `IDX_F300D9E23EAA84B1` (`cotisation_id`),
  ADD KEY `IDX_F300D9E26A99F74A` (`membre_id`);

--
-- Index pour la table `cotisation_transaction`
--
ALTER TABLE `cotisation_transaction`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `demande`
--
ALTER TABLE `demande`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `depot`
--
ALTER TABLE `depot`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `detail_commande`
--
ALTER TABLE `detail_commande`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_98344FA682EA2E54` (`commande_id`),
  ADD KEY `IDX_98344FA6F347EFB` (`produit_id`);

--
-- Index pour la table `entreprise`
--
ALTER TABLE `entreprise`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_D19FA6076C50E4A` (`proprietaire_id`);

--
-- Index pour la table `evenement`
--
ALTER TABLE `evenement`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_B26681EBCF5E72D` (`categorie_id`);

--
-- Index pour la table `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_C53D045FF347EFB` (`produit_id`),
  ADD KEY `IDX_C53D045FFD02F13` (`evenement_id`),
  ADD KEY `IDX_C53D045F7294869C` (`article_id`);

--
-- Index pour la table `letter`
--
ALTER TABLE `letter`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `membre`
--
ALTER TABLE `membre`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `membre_bureau`
--
ALTER TABLE `membre_bureau`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_8C664B7D57889920` (`fonction_id`);

--
-- Index pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Index pour la table `offre_emplois`
--
ALTER TABLE `offre_emplois`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_EAC907996A99F74A` (`membre_id`);

--
-- Index pour la table `page_about`
--
ALTER TABLE `page_about`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `page_accueil`
--
ALTER TABLE `page_accueil`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `partenaire`
--
ALTER TABLE `partenaire`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `partenariat`
--
ALTER TABLE `partenariat`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `postes_bureau`
--
ALTER TABLE `postes_bureau`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `produit`
--
ALTER TABLE `produit`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_29A5EC27BCF5E72D` (`categorie_id`);

--
-- Index pour la table `realisation`
--
ALTER TABLE `realisation`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `reset_password`
--
ALTER TABLE `reset_password`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `sujet`
--
ALTER TABLE `sujet`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_2E13599D60BB6FE6` (`auteur_id`),
  ADD KEY `IDX_2E13599D476556AF` (`thematique_id`);

--
-- Index pour la table `super_admin`
--
ALTER TABLE `super_admin`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `thematique`
--
ALTER TABLE `thematique`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `transaction`
--
ALTER TABLE `transaction`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_723705D1712520F3` (`wallet_id`);

--
-- Index pour la table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`);

--
-- Index pour la table `wallet`
--
ALTER TABLE `wallet`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_7C68921F6A99F74A` (`membre_id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `abonne`
--
ALTER TABLE `abonne`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `article`
--
ALTER TABLE `article`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `categorie_article`
--
ALTER TABLE `categorie_article`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `categorie_evenement`
--
ALTER TABLE `categorie_evenement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT pour la table `categorie_produit`
--
ALTER TABLE `categorie_produit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `commande`
--
ALTER TABLE `commande`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `commentaire`
--
ALTER TABLE `commentaire`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `cotisation`
--
ALTER TABLE `cotisation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `demande`
--
ALTER TABLE `demande`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `detail_commande`
--
ALTER TABLE `detail_commande`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `entreprise`
--
ALTER TABLE `entreprise`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `evenement`
--
ALTER TABLE `evenement`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `image`
--
ALTER TABLE `image`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `letter`
--
ALTER TABLE `letter`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `membre_bureau`
--
ALTER TABLE `membre_bureau`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `offre_emplois`
--
ALTER TABLE `offre_emplois`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `page_about`
--
ALTER TABLE `page_about`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `page_accueil`
--
ALTER TABLE `page_accueil`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `partenaire`
--
ALTER TABLE `partenaire`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT pour la table `partenariat`
--
ALTER TABLE `partenariat`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `postes_bureau`
--
ALTER TABLE `postes_bureau`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `produit`
--
ALTER TABLE `produit`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `realisation`
--
ALTER TABLE `realisation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `reset_password`
--
ALTER TABLE `reset_password`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `sujet`
--
ALTER TABLE `sujet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `thematique`
--
ALTER TABLE `thematique`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `transaction`
--
ALTER TABLE `transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT pour la table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT pour la table `wallet`
--
ALTER TABLE `wallet`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `achat`
--
ALTER TABLE `achat`
  ADD CONSTRAINT `FK_26A9845682EA2E54` FOREIGN KEY (`commande_id`) REFERENCES `commande` (`id`),
  ADD CONSTRAINT `FK_26A98456BF396750` FOREIGN KEY (`id`) REFERENCES `transaction` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `FK_880E0D76BF396750` FOREIGN KEY (`id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `article`
--
ALTER TABLE `article`
  ADD CONSTRAINT `FK_23A0E6660BB6FE6` FOREIGN KEY (`auteur_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_23A0E66EC5D4C30` FOREIGN KEY (`categorie_article_id`) REFERENCES `categorie_article` (`id`);

--
-- Contraintes pour la table `client`
--
ALTER TABLE `client`
  ADD CONSTRAINT `FK_C7440455BF396750` FOREIGN KEY (`id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `commande`
--
ALTER TABLE `commande`
  ADD CONSTRAINT `FK_6EEAA67D19EB6921` FOREIGN KEY (`client_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `commentaire`
--
ALTER TABLE `commentaire`
  ADD CONSTRAINT `FK_67F068BC7294869C` FOREIGN KEY (`article_id`) REFERENCES `article` (`id`),
  ADD CONSTRAINT `FK_67F068BC7C4D497E` FOREIGN KEY (`sujet_id`) REFERENCES `sujet` (`id`),
  ADD CONSTRAINT `FK_67F068BCA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_67F068BCFD02F13` FOREIGN KEY (`evenement_id`) REFERENCES `evenement` (`id`);

--
-- Contraintes pour la table `cotisation_membre`
--
ALTER TABLE `cotisation_membre`
  ADD CONSTRAINT `FK_F300D9E23EAA84B1` FOREIGN KEY (`cotisation_id`) REFERENCES `cotisation` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_F300D9E26A99F74A` FOREIGN KEY (`membre_id`) REFERENCES `membre` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `cotisation_transaction`
--
ALTER TABLE `cotisation_transaction`
  ADD CONSTRAINT `FK_59E91927BF396750` FOREIGN KEY (`id`) REFERENCES `transaction` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `depot`
--
ALTER TABLE `depot`
  ADD CONSTRAINT `FK_47948BBCBF396750` FOREIGN KEY (`id`) REFERENCES `transaction` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `detail_commande`
--
ALTER TABLE `detail_commande`
  ADD CONSTRAINT `FK_98344FA682EA2E54` FOREIGN KEY (`commande_id`) REFERENCES `commande` (`id`),
  ADD CONSTRAINT `FK_98344FA6F347EFB` FOREIGN KEY (`produit_id`) REFERENCES `produit` (`id`);

--
-- Contraintes pour la table `entreprise`
--
ALTER TABLE `entreprise`
  ADD CONSTRAINT `FK_D19FA6076C50E4A` FOREIGN KEY (`proprietaire_id`) REFERENCES `membre` (`id`);

--
-- Contraintes pour la table `evenement`
--
ALTER TABLE `evenement`
  ADD CONSTRAINT `FK_B26681EBCF5E72D` FOREIGN KEY (`categorie_id`) REFERENCES `categorie_evenement` (`id`);

--
-- Contraintes pour la table `image`
--
ALTER TABLE `image`
  ADD CONSTRAINT `FK_C53D045F7294869C` FOREIGN KEY (`article_id`) REFERENCES `article` (`id`),
  ADD CONSTRAINT `FK_C53D045FF347EFB` FOREIGN KEY (`produit_id`) REFERENCES `produit` (`id`),
  ADD CONSTRAINT `FK_C53D045FFD02F13` FOREIGN KEY (`evenement_id`) REFERENCES `evenement` (`id`);

--
-- Contraintes pour la table `membre`
--
ALTER TABLE `membre`
  ADD CONSTRAINT `FK_F6B4FB29BF396750` FOREIGN KEY (`id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `membre_bureau`
--
ALTER TABLE `membre_bureau`
  ADD CONSTRAINT `FK_8C664B7D57889920` FOREIGN KEY (`fonction_id`) REFERENCES `postes_bureau` (`id`);

--
-- Contraintes pour la table `offre_emplois`
--
ALTER TABLE `offre_emplois`
  ADD CONSTRAINT `FK_EAC907996A99F74A` FOREIGN KEY (`membre_id`) REFERENCES `membre` (`id`);

--
-- Contraintes pour la table `produit`
--
ALTER TABLE `produit`
  ADD CONSTRAINT `FK_29A5EC27BCF5E72D` FOREIGN KEY (`categorie_id`) REFERENCES `categorie_produit` (`id`);

--
-- Contraintes pour la table `sujet`
--
ALTER TABLE `sujet`
  ADD CONSTRAINT `FK_2E13599D476556AF` FOREIGN KEY (`thematique_id`) REFERENCES `thematique` (`id`),
  ADD CONSTRAINT `FK_2E13599D60BB6FE6` FOREIGN KEY (`auteur_id`) REFERENCES `user` (`id`);

--
-- Contraintes pour la table `super_admin`
--
ALTER TABLE `super_admin`
  ADD CONSTRAINT `FK_BC8C2783BF396750` FOREIGN KEY (`id`) REFERENCES `user` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `transaction`
--
ALTER TABLE `transaction`
  ADD CONSTRAINT `FK_723705D1712520F3` FOREIGN KEY (`wallet_id`) REFERENCES `wallet` (`id`);

--
-- Contraintes pour la table `wallet`
--
ALTER TABLE `wallet`
  ADD CONSTRAINT `FK_7C68921F6A99F74A` FOREIGN KEY (`membre_id`) REFERENCES `membre` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
