-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Client :  127.0.0.1
-- Généré le :  Mar 09 Septembre 2014 à 19:26
-- Version du serveur :  5.6.17
-- Version de PHP :  5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données :  `netbs`
--

-- --------------------------------------------------------

--
-- Structure de la table `album`
--

CREATE TABLE IF NOT EXISTS `album` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dossier_id` int(11) DEFAULT NULL,
  `nom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `creation` datetime NOT NULL,
  `photos` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  PRIMARY KEY (`id`),
  KEY `IDX_F8594147611C0C56` (`dossier_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `fichier_adresses`
--

CREATE TABLE IF NOT EXISTS `fichier_adresses` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `rue` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `npa` int(11) DEFAULT NULL,
  `localite` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `facturable` tinyint(1) NOT NULL,
  `remarques` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Contenu de la table `fichier_adresses`
--

INSERT INTO `fichier_adresses` (`id`, `rue`, `npa`, `localite`, `facturable`, `remarques`) VALUES
(1, NULL, NULL, NULL, 0, ' mnjkolu'),
(2, 'chemin des planches 1', 1073, 'savigny', 0, 'zboub yolo'),
(3, NULL, NULL, NULL, 0, NULL),
(4, 'Chemin des yolos', NULL, NULL, 1, NULL),
(5, NULL, NULL, NULL, 0, NULL),
(6, NULL, NULL, NULL, 0, NULL),
(7, 'chemin des bails 2', 7987, 'suss', 1, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `fichier_contacts`
--

CREATE TABLE IF NOT EXISTS `fichier_contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `telephone` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Contenu de la table `fichier_contacts`
--

INSERT INTO `fichier_contacts` (`id`, `telephone`, `email`) VALUES
(1, '0774117718', 'yolo.com'),
(2, '0217811641', NULL),
(3, NULL, 'bertrand.hochet@gmail.com'),
(4, '0798248747', 'catherine.hochet@hotmail.com'),
(5, NULL, NULL),
(6, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `fichier_familles`
--

CREATE TABLE IF NOT EXISTS `fichier_familles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adresse_id` int(11) DEFAULT NULL,
  `contact_id` int(11) DEFAULT NULL,
  `pere_id` int(11) DEFAULT NULL,
  `mere_id` int(11) DEFAULT NULL,
  `nom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_27F2CBD44DE7DC5C` (`adresse_id`),
  UNIQUE KEY `UNIQ_27F2CBD4E7A1254A` (`contact_id`),
  UNIQUE KEY `UNIQ_27F2CBD43FD73900` (`pere_id`),
  UNIQUE KEY `UNIQ_27F2CBD439DEC40E` (`mere_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Contenu de la table `fichier_familles`
--

INSERT INTO `fichier_familles` (`id`, `adresse_id`, `contact_id`, `pere_id`, `mere_id`, `nom`) VALUES
(1, 2, 2, 1, 2, 'hochet'),
(2, 7, 6, NULL, NULL, 'aswag');

-- --------------------------------------------------------

--
-- Structure de la table `fichier_geniteurs`
--

CREATE TABLE IF NOT EXISTS `fichier_geniteurs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_id` int(11) DEFAULT NULL,
  `adresse_id` int(11) DEFAULT NULL,
  `profession` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `prenom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sexe` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_10EB2AD1E7A1254A` (`contact_id`),
  UNIQUE KEY `UNIQ_10EB2AD14DE7DC5C` (`adresse_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Contenu de la table `fichier_geniteurs`
--

INSERT INTO `fichier_geniteurs` (`id`, `contact_id`, `adresse_id`, `profession`, `prenom`, `sexe`) VALUES
(1, 3, 3, 'ingénieur', 'bertrand', 'm'),
(2, 4, 4, 'physiothérapeute', 'catherine', 'f');

-- --------------------------------------------------------

--
-- Structure de la table `fichier_membres`
--

CREATE TABLE IF NOT EXISTS `fichier_membres` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `famille_id` int(11) DEFAULT NULL,
  `contact_id` int(11) DEFAULT NULL,
  `adresse_id` int(11) DEFAULT NULL,
  `naissance` date NOT NULL,
  `numero_bs` int(11) DEFAULT NULL,
  `numero_avs` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `statut` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `inscription` date NOT NULL,
  `prenom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sexe` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_82D91FABE7A1254A` (`contact_id`),
  UNIQUE KEY `UNIQ_82D91FAB4DE7DC5C` (`adresse_id`),
  KEY `IDX_82D91FAB97A77B84` (`famille_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Contenu de la table `fichier_membres`
--

INSERT INTO `fichier_membres` (`id`, `famille_id`, `contact_id`, `adresse_id`, `naissance`, `numero_bs`, `numero_avs`, `statut`, `inscription`, `prenom`, `sexe`) VALUES
(1, 1, 1, 1, '1994-04-09', NULL, NULL, NULL, '2014-06-11', 'Guillaume', 'homme'),
(2, 1, 5, 5, '1996-10-07', NULL, NULL, NULL, '2014-06-11', 'loriane', 'homme'),
(3, 2, 6, 6, '2014-07-15', NULL, NULL, NULL, '2014-07-22', 'Christian wowow', 'homme');

-- --------------------------------------------------------

--
-- Structure de la table `galerie_albums`
--

CREATE TABLE IF NOT EXISTS `galerie_albums` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dossier_id` int(11) DEFAULT NULL,
  `nom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `creation` datetime NOT NULL,
  `photos` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `droit_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_EDDFF731611C0C56` (`dossier_id`),
  KEY `IDX_EDDFF7315AA93370` (`droit_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Contenu de la table `galerie_albums`
--

INSERT INTO `galerie_albums` (`id`, `dossier_id`, `nom`, `creation`, `photos`, `droit_id`) VALUES
(1, 12, 'Camp d''été', '2014-08-14 23:00:46', 'a:198:{i:0;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050058__12e574c82b57f881da1fd408831c6b98.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050058__12e574c82b57f881da1fd408831c6b98.jpg";}i:1;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050059__01b8cea3b5774360e16e0b1a34ee656c.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050059__01b8cea3b5774360e16e0b1a34ee656c.jpg";}i:2;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050059__6bc20b415768ef0e986d3c395e66a6e3.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050059__6bc20b415768ef0e986d3c395e66a6e3.jpg";}i:3;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050060__2d1e01a0df8f8209e23e16a37ac36031.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050060__2d1e01a0df8f8209e23e16a37ac36031.jpg";}i:4;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050061__488202a0712fdbe17910a7a2cd3a6268.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050061__488202a0712fdbe17910a7a2cd3a6268.jpg";}i:5;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050062__8dc05d92f232fbae0907d1403fdf1407.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050062__8dc05d92f232fbae0907d1403fdf1407.jpg";}i:6;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050062__410f3008bfb6db7831b364dfbc331f9d.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050062__410f3008bfb6db7831b364dfbc331f9d.jpg";}i:7;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050063__d4c5f243e6f6e7d773fb77c15971a3a7.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050063__d4c5f243e6f6e7d773fb77c15971a3a7.jpg";}i:8;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050064__58c8f7626384038824f241547d2d61d2.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050064__58c8f7626384038824f241547d2d61d2.jpg";}i:9;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050064__5715c3440f0f366ea8008a4f461ef439.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050064__5715c3440f0f366ea8008a4f461ef439.jpg";}i:10;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050065__efb44f4a4c7d31860ae1fbf12216be5c.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050065__efb44f4a4c7d31860ae1fbf12216be5c.jpg";}i:11;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050066__1ee5d207d3324672faaf573abf5365c2.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050066__1ee5d207d3324672faaf573abf5365c2.jpg";}i:12;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050067__f5608406e37f5015c8d8df097f8f2e8a.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050067__f5608406e37f5015c8d8df097f8f2e8a.jpg";}i:13;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050067__199f38112e2523fc8d01dfa2440bb4ba.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050067__199f38112e2523fc8d01dfa2440bb4ba.jpg";}i:14;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050068__39437b2e690f401b082777e58908835a.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050068__39437b2e690f401b082777e58908835a.jpg";}i:15;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050069__fbdc4cb6ec9c428101f5d771a5d4e91a.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050069__fbdc4cb6ec9c428101f5d771a5d4e91a.jpg";}i:16;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050069__101e78f4ccb63cc0e0a005a8d0b5d0f8.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050069__101e78f4ccb63cc0e0a005a8d0b5d0f8.jpg";}i:17;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050070__c413a18bbd785c2af188fe2556d3241b.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050070__c413a18bbd785c2af188fe2556d3241b.jpg";}i:18;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050071__9032e0ec70f5163c243b06328e9153a5.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050071__9032e0ec70f5163c243b06328e9153a5.jpg";}i:19;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050071__4cd21d6b11daf896ff15d8509d62064f.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050071__4cd21d6b11daf896ff15d8509d62064f.jpg";}i:20;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050072__09a0033bcf61850ac6e76eaf5b70e222.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050072__09a0033bcf61850ac6e76eaf5b70e222.jpg";}i:21;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050073__3e1cf63c845b042f3a7618f6c8421c12.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050073__3e1cf63c845b042f3a7618f6c8421c12.jpg";}i:22;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050073__a891a6854f52ca5bd7e724c6fcce475d.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050073__a891a6854f52ca5bd7e724c6fcce475d.jpg";}i:23;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050074__84cde9b4516f3c9fc651be8a7d8298a1.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050074__84cde9b4516f3c9fc651be8a7d8298a1.jpg";}i:24;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050075__02a39d34eeec235a34cd71365a12c155.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050075__02a39d34eeec235a34cd71365a12c155.jpg";}i:25;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050076__180f2161129f058f29179e8e95a446f5.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050076__180f2161129f058f29179e8e95a446f5.jpg";}i:26;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050076__c52968af16046f1ce988ebdb0c3e6c1a.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050076__c52968af16046f1ce988ebdb0c3e6c1a.jpg";}i:27;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050077__46980acab868be061607fef5f4908c40.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050077__46980acab868be061607fef5f4908c40.jpg";}i:28;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050078__4674a018dc3b89d212997f3f1075ec11.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050078__4674a018dc3b89d212997f3f1075ec11.jpg";}i:29;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050078__7bc86eaab1fb81cbe9426a2bba77f846.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050078__7bc86eaab1fb81cbe9426a2bba77f846.jpg";}i:30;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050079__c8618af6da0790a6758cd307a1ac71dc.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050079__c8618af6da0790a6758cd307a1ac71dc.jpg";}i:31;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050080__a1e2cd00f3754dd4bd9a47fc18409277.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050080__a1e2cd00f3754dd4bd9a47fc18409277.jpg";}i:32;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050080__a43878fe8ace7bb480dab359555628d4.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050080__a43878fe8ace7bb480dab359555628d4.jpg";}i:33;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050081__cc6d5985a573744e63924a8460b8d48b.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050081__cc6d5985a573744e63924a8460b8d48b.jpg";}i:34;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050082__fb60d0aea67d0c72f19196d8a2db52ca.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050082__fb60d0aea67d0c72f19196d8a2db52ca.jpg";}i:35;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050083__19285cbcc41480dd585fb3760a227636.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050083__19285cbcc41480dd585fb3760a227636.jpg";}i:36;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050083__2bcecee51b925276a3e2c4b1b50abcb2.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050083__2bcecee51b925276a3e2c4b1b50abcb2.jpg";}i:37;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050084__7eae0ae5953e5c99e66275b77e61a68c.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050084__7eae0ae5953e5c99e66275b77e61a68c.jpg";}i:38;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050085__eff3c754b01f476ec8ad5a27d737b05e.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050085__eff3c754b01f476ec8ad5a27d737b05e.jpg";}i:39;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050085__0138e404d7805245a26bf8afc84a5f88.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050085__0138e404d7805245a26bf8afc84a5f88.jpg";}i:40;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050086__ac7eabe4237863444929352224de39e6.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050086__ac7eabe4237863444929352224de39e6.jpg";}i:41;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050087__839e684a44d3c71031bc02cc2c60cd67.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050087__839e684a44d3c71031bc02cc2c60cd67.jpg";}i:42;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050087__72f1c3e3ee47fd6cf65132d4276a011a.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050087__72f1c3e3ee47fd6cf65132d4276a011a.jpg";}i:43;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050088__a5abef1bde40574bf7a42457026f361f.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050088__a5abef1bde40574bf7a42457026f361f.jpg";}i:44;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050089__18602a1277816a6428fb7e20e0892b7e.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050089__18602a1277816a6428fb7e20e0892b7e.jpg";}i:45;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050089__a52f253f69b7d3a97f208ba9c1aeb1f8.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050089__a52f253f69b7d3a97f208ba9c1aeb1f8.jpg";}i:46;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050090__8a6dc4176aad018ef4e7826722d111ca.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050090__8a6dc4176aad018ef4e7826722d111ca.jpg";}i:47;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050091__c69c73dc2372b6fe337bd9f0c84b09f8.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050091__c69c73dc2372b6fe337bd9f0c84b09f8.jpg";}i:48;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050092__ca641a416363e43c35760a1d09b1ab3d.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050092__ca641a416363e43c35760a1d09b1ab3d.jpg";}i:49;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050092__707312b86de433e43dc9862cc14e5694.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050092__707312b86de433e43dc9862cc14e5694.jpg";}i:50;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050093__3aee6d3cc3645c1566d1a21864e7e6e4.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050093__3aee6d3cc3645c1566d1a21864e7e6e4.jpg";}i:51;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050094__519162236717612f6811eb6e8eac9cc0.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050094__519162236717612f6811eb6e8eac9cc0.jpg";}i:52;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050094__57f7addfa50f58875c72491e1b11fda0.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050094__57f7addfa50f58875c72491e1b11fda0.jpg";}i:53;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050095__96ae5488e70ef239f6da75014e081d3e.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050095__96ae5488e70ef239f6da75014e081d3e.jpg";}i:54;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050096__118c52ae09f264e876293c304037a11e.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050096__118c52ae09f264e876293c304037a11e.jpg";}i:55;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050096__4ff3d6db4ccaab91e1fd353843572b97.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050096__4ff3d6db4ccaab91e1fd353843572b97.jpg";}i:56;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050097__1f2407c963c3992b2c20f7bb1eb7c7c9.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050097__1f2407c963c3992b2c20f7bb1eb7c7c9.jpg";}i:57;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050098__004c57626d44c0ba4ba8879a1cd7f77f.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050098__004c57626d44c0ba4ba8879a1cd7f77f.jpg";}i:58;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050099__6248b9ef53e8a7ec96ad471827563bd8.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050099__6248b9ef53e8a7ec96ad471827563bd8.jpg";}i:59;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050099__dae9e040fd18816558f46706bb8d36a2.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050099__dae9e040fd18816558f46706bb8d36a2.jpg";}i:60;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050100__a9c00c5349200da0686752a5c71bcec4.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050100__a9c00c5349200da0686752a5c71bcec4.jpg";}i:61;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050101__a9e11ceea959eb76b41dee36c1c0e70a.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050101__a9e11ceea959eb76b41dee36c1c0e70a.jpg";}i:62;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050101__5ce4fee0e26560e544863f19bd5dbf4f.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050101__5ce4fee0e26560e544863f19bd5dbf4f.jpg";}i:63;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050102__d3ed8ece80404018107532ccc1daf9e9.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050102__d3ed8ece80404018107532ccc1daf9e9.jpg";}i:64;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050103__de08d0213ee58d6fda88c317ae0b4d69.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050103__de08d0213ee58d6fda88c317ae0b4d69.jpg";}i:65;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050103__f464e95cd7112973163364d5d0baaa9e.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050103__f464e95cd7112973163364d5d0baaa9e.jpg";}i:66;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050104__80296762ee14a8407e7d32a8df6943c6.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050104__80296762ee14a8407e7d32a8df6943c6.jpg";}i:67;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050105__7c70c5720e0c16f28f8e1538bee694d1.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050105__7c70c5720e0c16f28f8e1538bee694d1.jpg";}i:68;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050106__abf3fdc79a7558ac014ba6637508017f.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050106__abf3fdc79a7558ac014ba6637508017f.jpg";}i:69;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050106__6cadbd94b871a38b6d8190506bb35bdf.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050106__6cadbd94b871a38b6d8190506bb35bdf.jpg";}i:70;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050107__756f7aa4872dadd4b317fc082048d3db.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050107__756f7aa4872dadd4b317fc082048d3db.jpg";}i:71;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050108__fbc2fe14e9bb01b3f638571b2d4107cd.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050108__fbc2fe14e9bb01b3f638571b2d4107cd.jpg";}i:72;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050108__d24aeb9e984bfc811ef4c0bac994068d.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050108__d24aeb9e984bfc811ef4c0bac994068d.jpg";}i:73;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050109__93c8214c878cca6cec044184be379380.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050109__93c8214c878cca6cec044184be379380.jpg";}i:74;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050110__5e87c85d6211490d084a0a18936f76ed.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050110__5e87c85d6211490d084a0a18936f76ed.jpg";}i:75;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050111__4c99550b699f0f5a19b00d4953deb98f.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050111__4c99550b699f0f5a19b00d4953deb98f.jpg";}i:76;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050111__6c1330208e385a5874d2493afa57a78f.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050111__6c1330208e385a5874d2493afa57a78f.jpg";}i:77;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050112__64d2164367d59695f8fdeae3209c749a.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050112__64d2164367d59695f8fdeae3209c749a.jpg";}i:78;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050113__5bcafea23b1d4e376cbc40764eef19ec.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050113__5bcafea23b1d4e376cbc40764eef19ec.jpg";}i:79;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050113__10000c2d696572ab2f11697aaf973830.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050113__10000c2d696572ab2f11697aaf973830.jpg";}i:80;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050114__987c81853c49a040e81b0b89ec41491c.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050114__987c81853c49a040e81b0b89ec41491c.jpg";}i:81;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050115__b9c7d55b33e09f9b00f9e512c1d2c306.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050115__b9c7d55b33e09f9b00f9e512c1d2c306.jpg";}i:82;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050115__e2cd83799855a8c408eae09caa3f021c.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050115__e2cd83799855a8c408eae09caa3f021c.jpg";}i:83;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050116__ef0b6e5b1c8e66c121e87c4c7c752c2b.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050116__ef0b6e5b1c8e66c121e87c4c7c752c2b.jpg";}i:84;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050117__b6bdf0b23fd6c1cf10778bfa32bad406.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050117__b6bdf0b23fd6c1cf10778bfa32bad406.jpg";}i:85;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050118__7c036c95366e6619b6c5bd28a998c194.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050118__7c036c95366e6619b6c5bd28a998c194.jpg";}i:86;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050118__8bdd4815abfef0610ac4f21e44bbee25.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050118__8bdd4815abfef0610ac4f21e44bbee25.jpg";}i:87;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050119__611ecda1ec5c4271b5bd0682c11e50ca.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050119__611ecda1ec5c4271b5bd0682c11e50ca.jpg";}i:88;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050120__f30de5edea35d7de96fd8c6cdbe85a3e.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050120__f30de5edea35d7de96fd8c6cdbe85a3e.jpg";}i:89;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050120__34182571d99ce9b5af8f8fa07ef652ee.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050120__34182571d99ce9b5af8f8fa07ef652ee.jpg";}i:90;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050121__6f67d169fbe68ee1593ebfc1c21e3e51.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050121__6f67d169fbe68ee1593ebfc1c21e3e51.jpg";}i:91;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050122__3dfdac0bdbb33e92fc513143bb9dc5df.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050122__3dfdac0bdbb33e92fc513143bb9dc5df.jpg";}i:92;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050122__a46369dde45cac363ab752df15b395c7.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050122__a46369dde45cac363ab752df15b395c7.jpg";}i:93;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050123__a82ecef3cf6dce185e850a6b5d815dc9.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050123__a82ecef3cf6dce185e850a6b5d815dc9.jpg";}i:94;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050124__4009a16a23365e7efe0249950a33d2b4.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050124__4009a16a23365e7efe0249950a33d2b4.jpg";}i:95;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050125__1c0f0b18e3a44830901f7f5d593da385.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050125__1c0f0b18e3a44830901f7f5d593da385.jpg";}i:96;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050125__d3fe3a48fb44f06e64e9c703a8d9e995.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050125__d3fe3a48fb44f06e64e9c703a8d9e995.jpg";}i:97;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050126__639ae19e4ad8812b833c9ca05db6a7cc.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050126__639ae19e4ad8812b833c9ca05db6a7cc.jpg";}i:98;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050127__6e7c0e4bc0ee040236110f528af3d0e6.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050127__6e7c0e4bc0ee040236110f528af3d0e6.jpg";}i:99;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050127__b7954b71d5b29e36e11543bcbc78338a.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050127__b7954b71d5b29e36e11543bcbc78338a.jpg";}i:100;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050128__647a6a8a31538b756f00b50cc7f6aeb3.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050128__647a6a8a31538b756f00b50cc7f6aeb3.jpg";}i:101;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050129__3b834fc4f5989ed0427cc748dc7be0a0.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050129__3b834fc4f5989ed0427cc748dc7be0a0.jpg";}i:102;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050130__21a12b0b68e0fb375cd015d422c24478.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050130__21a12b0b68e0fb375cd015d422c24478.jpg";}i:103;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050130__07eff1d17103011503ebce83d8840d70.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050130__07eff1d17103011503ebce83d8840d70.jpg";}i:104;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050131__0bd024bb2626599a8d585da92b0e3910.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050131__0bd024bb2626599a8d585da92b0e3910.jpg";}i:105;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050132__228de1ccf58283d6f2a692d92e467e2f.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050132__228de1ccf58283d6f2a692d92e467e2f.jpg";}i:106;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050132__9759ff0fb5ee4fad23a4b3dd45f1f964.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050132__9759ff0fb5ee4fad23a4b3dd45f1f964.jpg";}i:107;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050133__63f48057876fe3d258a53a725218e195.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050133__63f48057876fe3d258a53a725218e195.jpg";}i:108;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050134__e0e78ae5943956030d2a67c04e4896f4.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050134__e0e78ae5943956030d2a67c04e4896f4.jpg";}i:109;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050135__a7d29828fc86a662598c11ca958fa728.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050135__a7d29828fc86a662598c11ca958fa728.jpg";}i:110;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050135__3dfee662ab4ca70a27b3097c101ea713.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050135__3dfee662ab4ca70a27b3097c101ea713.jpg";}i:111;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050136__763cd1c03b09e70b8a536ff606f4ea05.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050136__763cd1c03b09e70b8a536ff606f4ea05.jpg";}i:112;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050137__3747b0b123d6d7aaa5ba7453401a6c4b.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050137__3747b0b123d6d7aaa5ba7453401a6c4b.jpg";}i:113;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050137__64be007e34257cb3e9841437a9e01364.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050137__64be007e34257cb3e9841437a9e01364.jpg";}i:114;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050138__3b711a70c365b1ace4e05edeea1dfab6.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050138__3b711a70c365b1ace4e05edeea1dfab6.jpg";}i:115;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050139__ec2aba6567528f773aaa1a36a1df6d82.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050139__ec2aba6567528f773aaa1a36a1df6d82.jpg";}i:116;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050139__f3187ff99006f4e531850709452da514.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050139__f3187ff99006f4e531850709452da514.jpg";}i:117;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050140__23ca93b4edbb399fbf203692ecbe5f87.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050140__23ca93b4edbb399fbf203692ecbe5f87.jpg";}i:118;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050141__f1ae5273b9e8e59491007fa576ed8dd0.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050141__f1ae5273b9e8e59491007fa576ed8dd0.jpg";}i:119;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050142__e331089fd4ddcc2fa6f3a0d1dfd7d133.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050142__e331089fd4ddcc2fa6f3a0d1dfd7d133.jpg";}i:120;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050142__a7438a941756c6e8331876f96163fbdb.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050142__a7438a941756c6e8331876f96163fbdb.jpg";}i:121;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050143__b3bf6fc4cc760b1db0683b18366578ca.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050143__b3bf6fc4cc760b1db0683b18366578ca.jpg";}i:122;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050144__398ee80aa244f8108e9f8b0f0fea9c51.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050144__398ee80aa244f8108e9f8b0f0fea9c51.jpg";}i:123;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050145__602fa6409015bbd918c2ab9b3e48409e.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050145__602fa6409015bbd918c2ab9b3e48409e.jpg";}i:124;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050145__b21eca504bcab86edef2c3cffd5093ab.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050145__b21eca504bcab86edef2c3cffd5093ab.jpg";}i:125;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050146__aa882d9c4ede779a46254fe0cd13f7b4.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050146__aa882d9c4ede779a46254fe0cd13f7b4.jpg";}i:126;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050147__4033ca21a9c6995aaf84d37839da4461.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050147__4033ca21a9c6995aaf84d37839da4461.jpg";}i:127;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050147__e53ff5922f60ff7d57332e0df97cf0fc.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050147__e53ff5922f60ff7d57332e0df97cf0fc.jpg";}i:128;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050148__4c1b91c09da47c47434feda4293f8d98.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050148__4c1b91c09da47c47434feda4293f8d98.jpg";}i:129;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050149__a84ace99e17d15eb01f90c2178071432.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050149__a84ace99e17d15eb01f90c2178071432.jpg";}i:130;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050150__eb0fda5f3e7f3a996054f618add6e429.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050150__eb0fda5f3e7f3a996054f618add6e429.jpg";}i:131;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050150__4bcd02392a84d5d3be717752b9174c84.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050150__4bcd02392a84d5d3be717752b9174c84.jpg";}i:132;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050151__7f1110a378d30eb8cf100c1e73feb7ec.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050151__7f1110a378d30eb8cf100c1e73feb7ec.jpg";}i:133;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050152__2e3c38a9853634b792927c6c216ec1ba.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050152__2e3c38a9853634b792927c6c216ec1ba.jpg";}i:134;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050152__0a5e278edc8f7d08822a1fda5aef190e.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050152__0a5e278edc8f7d08822a1fda5aef190e.jpg";}i:135;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050153__085f709682d1de702e38f5bac6072916.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050153__085f709682d1de702e38f5bac6072916.jpg";}i:136;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050154__20d6052482c4a26e1a5f7a9a3d12b621.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050154__20d6052482c4a26e1a5f7a9a3d12b621.jpg";}i:137;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050154__6ec846e60239ab5ba0ae6bcd525e564b.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050154__6ec846e60239ab5ba0ae6bcd525e564b.jpg";}i:138;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050155__2e3cd6abdd92fad414deb968ae971192.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050155__2e3cd6abdd92fad414deb968ae971192.jpg";}i:139;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050156__692bf4f0b60f3afc7ca166d6326520bb.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050156__692bf4f0b60f3afc7ca166d6326520bb.jpg";}i:140;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050157__4c940105ca7e7cfb1860a84e52480665.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050157__4c940105ca7e7cfb1860a84e52480665.jpg";}i:141;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050157__2882e4ce3b62b50ff0487cad2cdf5b8d.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050157__2882e4ce3b62b50ff0487cad2cdf5b8d.jpg";}i:142;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050158__a461a17250f117e4a9743cf0bf58e90a.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050158__a461a17250f117e4a9743cf0bf58e90a.jpg";}i:143;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050159__c8e30a6a493c8287c86169bfaab360da.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050159__c8e30a6a493c8287c86169bfaab360da.jpg";}i:144;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050160__a98da25873df629c447acda5902ea952.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050160__a98da25873df629c447acda5902ea952.jpg";}i:145;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050160__6582db2d632b67f98ccebff52ff82c4b.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050160__6582db2d632b67f98ccebff52ff82c4b.jpg";}i:146;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050161__7f9bd70a040101d851757269f2b15e93.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050161__7f9bd70a040101d851757269f2b15e93.jpg";}i:147;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050162__39e057b3b6a2530479f5ae853ae9be7e.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050162__39e057b3b6a2530479f5ae853ae9be7e.jpg";}i:148;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050162__31d9bfe57c8bf7bc69a65b15fc8bca76.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050162__31d9bfe57c8bf7bc69a65b15fc8bca76.jpg";}i:149;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050163__0d2ce5b964a7d51c56c1fdf0f3387aab.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050163__0d2ce5b964a7d51c56c1fdf0f3387aab.jpg";}i:150;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050164__bdaa029013152a8086fed97337641277.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050164__bdaa029013152a8086fed97337641277.jpg";}i:151;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050164__4cf01eea3e7a6ffdcd26133031389b17.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050164__4cf01eea3e7a6ffdcd26133031389b17.jpg";}i:152;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050165__414a15c64ca5ff586a80bca75c8f4fa6.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050165__414a15c64ca5ff586a80bca75c8f4fa6.jpg";}i:153;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050166__4f13a96e5a2ae8d02a745f29034737d5.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050166__4f13a96e5a2ae8d02a745f29034737d5.jpg";}i:154;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050167__f4f399d08d21db68b711ed0a3e2a5b39.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050167__f4f399d08d21db68b711ed0a3e2a5b39.jpg";}i:155;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050167__27a204093fd8dee5293f1e98abcb3e10.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050167__27a204093fd8dee5293f1e98abcb3e10.jpg";}i:156;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050168__540a8972957eb453e4ae0ac50e8c9e2d.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050168__540a8972957eb453e4ae0ac50e8c9e2d.jpg";}i:157;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050169__23dd9fb7c2b669d1edd24b6cd4db06c5.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050169__23dd9fb7c2b669d1edd24b6cd4db06c5.jpg";}i:158;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050169__3d6cdec5665e1609ea964d931e64d1dc.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050169__3d6cdec5665e1609ea964d931e64d1dc.jpg";}i:159;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050170__dee4ff019923a81577a04a0f8bda4b0d.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050170__dee4ff019923a81577a04a0f8bda4b0d.jpg";}i:160;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050171__cbb5ec10d672a551a052f541b011496a.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050171__cbb5ec10d672a551a052f541b011496a.jpg";}i:161;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050172__62ac71be22ca645bae9154c3ede1ceee.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050172__62ac71be22ca645bae9154c3ede1ceee.jpg";}i:162;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050172__6172d6bb548b15873f7ad2a8004859e1.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050172__6172d6bb548b15873f7ad2a8004859e1.jpg";}i:163;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050173__b0b36af2fbfb92b04b42c9c9f94d63f6.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050173__b0b36af2fbfb92b04b42c9c9f94d63f6.jpg";}i:164;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050174__be827b4b4d6d97849155c82f0f405b1b.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050174__be827b4b4d6d97849155c82f0f405b1b.jpg";}i:165;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050174__e6a673d7d3189f5a8eb927980d1b91b5.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050174__e6a673d7d3189f5a8eb927980d1b91b5.jpg";}i:166;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050175__636868fc25969b722d821dfc9f5167f2.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050175__636868fc25969b722d821dfc9f5167f2.jpg";}i:167;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050176__51f341ac9a07114bb268b254a8c011dd.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050176__51f341ac9a07114bb268b254a8c011dd.jpg";}i:168;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050176__7d02fa3f1e6cc63dff56c024ffd57e58.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050176__7d02fa3f1e6cc63dff56c024ffd57e58.jpg";}i:169;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050177__2a21a783f458fceaf5dfd1d56df12926.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050177__2a21a783f458fceaf5dfd1d56df12926.jpg";}i:170;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050178__267bf77c5ddd45de0771c5987c5e6112.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050178__267bf77c5ddd45de0771c5987c5e6112.jpg";}i:171;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050179__76cba26be1bda9c7f01eae164826c2e1.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050179__76cba26be1bda9c7f01eae164826c2e1.jpg";}i:172;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050179__cc2f3a97297d41efafc12e3dfcb56a33.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050179__cc2f3a97297d41efafc12e3dfcb56a33.jpg";}i:173;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050180__1ef228a20fd455afbef80575c55a13dc.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050180__1ef228a20fd455afbef80575c55a13dc.jpg";}i:174;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050181__7b6f3189ecd0ee4917f7f765e36eb81a.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050181__7b6f3189ecd0ee4917f7f765e36eb81a.jpg";}i:175;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050181__1893c1c7572e98bba734d37e600852b3.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050181__1893c1c7572e98bba734d37e600852b3.jpg";}i:176;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050182__b08a36dfe25ad931d46052301702cffd.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050182__b08a36dfe25ad931d46052301702cffd.jpg";}i:177;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050183__0ca9cc4ddfff5c7f6a2b93a9e6312001.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050183__0ca9cc4ddfff5c7f6a2b93a9e6312001.jpg";}i:178;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050184__67a7099b3a117a56f1aea6fe9a39937c.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050184__67a7099b3a117a56f1aea6fe9a39937c.jpg";}i:179;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050184__b99b4d0ca75ba768ff018a4974ee9ad0.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050184__b99b4d0ca75ba768ff018a4974ee9ad0.jpg";}i:180;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050185__0bc4d0c18088a3f1f47ded8aaf8745ed.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050185__0bc4d0c18088a3f1f47ded8aaf8745ed.jpg";}i:181;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050186__ec19b0a319e0539b15ff3b3ca577cce2.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050186__ec19b0a319e0539b15ff3b3ca577cce2.jpg";}i:182;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050186__3e2798a866ad8c3c7b208785ce1551e0.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050186__3e2798a866ad8c3c7b208785ce1551e0.jpg";}i:183;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050187__79e4cd8a96d3b5ecb1d75ecc07ff7492.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050187__79e4cd8a96d3b5ecb1d75ecc07ff7492.jpg";}i:184;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050188__65f32810ee5b86a5b430a9c53980d378.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050188__65f32810ee5b86a5b430a9c53980d378.jpg";}i:185;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050189__ff4ee4d398a1d7ecdae3f87125395f30.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050189__ff4ee4d398a1d7ecdae3f87125395f30.jpg";}i:186;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050189__20d436814687872474e7a35b6c6cb705.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050189__20d436814687872474e7a35b6c6cb705.jpg";}i:187;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050190__4a6a767240bcd3bda7c3833db743b632.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050190__4a6a767240bcd3bda7c3833db743b632.jpg";}i:188;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050191__4c1bc90abed75b80167df783e8e070d9.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050191__4c1bc90abed75b80167df783e8e070d9.jpg";}i:189;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050191__34540911e86f3300a0b571a67616c502.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050191__34540911e86f3300a0b571a67616c502.jpg";}i:190;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050192__a4d24ca251a7b843a442d7ae72f3b269.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050192__a4d24ca251a7b843a442d7ae72f3b269.jpg";}i:191;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050193__1adebc7681c64b4be83aee9a122e2435.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050193__1adebc7681c64b4be83aee9a122e2435.jpg";}i:192;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050194__28bb12fa5a5ce16bdfd3bb2f89dff508.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050194__28bb12fa5a5ce16bdfd3bb2f89dff508.jpg";}i:193;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050194__97b0a6d9ea0412435a92364179853c13.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050194__97b0a6d9ea0412435a92364179853c13.jpg";}i:194;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050195__1d41753ce46138668f3a2c8dfbb45b85.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050195__1d41753ce46138668f3a2c8dfbb45b85.jpg";}i:195;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050196__e2fa3b089c64315f883d47d23a837902.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050196__e2fa3b089c64315f883d47d23a837902.jpg";}i:196;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050196__c2832b1ec21229f886d4b6781ea109d1.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050196__c2832b1ec21229f886d4b6781ea109d1.jpg";}i:197;a:2:{s:5:"photo";s:113:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Foriginales%2F1__1408050197__bb814b3360779546e7feebef6e69a2ce.jpg";s:9:"thumbnail";s:116:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2FCamp-dete%2Fthumbnails%2Ft__1__1408050197__bb814b3360779546e7feebef6e69a2ce.jpg";}}', 4);
INSERT INTO `galerie_albums` (`id`, `dossier_id`, `nom`, `creation`, `photos`, `droit_id`) VALUES
(2, NULL, 'yolow', '2014-08-19 15:25:05', 'a:18:{i:0;a:2:{s:5:"photo";s:109:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2Fyolow%2Foriginales%2F2__1408454729__fdbad5c5940b598f81ad9904d78a9f33.jpg";s:9:"thumbnail";s:112:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2Fyolow%2Fthumbnails%2Ft__2__1408454729__fdbad5c5940b598f81ad9904d78a9f33.jpg";}i:1;a:2:{s:5:"photo";s:109:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2Fyolow%2Foriginales%2F2__1408454729__c5a0668c1cfdd54575e370ced3186253.jpg";s:9:"thumbnail";s:112:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2Fyolow%2Fthumbnails%2Ft__2__1408454729__c5a0668c1cfdd54575e370ced3186253.jpg";}i:2;a:2:{s:5:"photo";s:109:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2Fyolow%2Foriginales%2F2__1408454730__a937d52ba5d1e48019db8496b73122f4.jpg";s:9:"thumbnail";s:112:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2Fyolow%2Fthumbnails%2Ft__2__1408454730__a937d52ba5d1e48019db8496b73122f4.jpg";}i:3;a:2:{s:5:"photo";s:109:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2Fyolow%2Foriginales%2F2__1408454731__c7a5f3fa351ff5e1cded2f58577b5ada.jpg";s:9:"thumbnail";s:112:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2Fyolow%2Fthumbnails%2Ft__2__1408454731__c7a5f3fa351ff5e1cded2f58577b5ada.jpg";}i:4;a:2:{s:5:"photo";s:109:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2Fyolow%2Foriginales%2F2__1408454732__fad89cd2f14b532c5339437a399e7fd2.jpg";s:9:"thumbnail";s:112:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2Fyolow%2Fthumbnails%2Ft__2__1408454732__fad89cd2f14b532c5339437a399e7fd2.jpg";}i:5;a:2:{s:5:"photo";s:109:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2Fyolow%2Foriginales%2F2__1408454732__c78e23d23f6d28b26eb7568430261cf2.jpg";s:9:"thumbnail";s:112:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2Fyolow%2Fthumbnails%2Ft__2__1408454732__c78e23d23f6d28b26eb7568430261cf2.jpg";}i:6;a:2:{s:5:"photo";s:109:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2Fyolow%2Foriginales%2F2__1408454733__fc9a42ef3f83eb90c46570f039e81005.jpg";s:9:"thumbnail";s:112:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2Fyolow%2Fthumbnails%2Ft__2__1408454733__fc9a42ef3f83eb90c46570f039e81005.jpg";}i:7;a:2:{s:5:"photo";s:109:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2Fyolow%2Foriginales%2F2__1408454734__e7e0c94d191b65bfe88279eb87a64e84.jpg";s:9:"thumbnail";s:112:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2Fyolow%2Fthumbnails%2Ft__2__1408454734__e7e0c94d191b65bfe88279eb87a64e84.jpg";}i:8;a:2:{s:5:"photo";s:109:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2Fyolow%2Foriginales%2F2__1408454734__ab21b3a77e9dccdd82b0427be15aa172.jpg";s:9:"thumbnail";s:112:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2Fyolow%2Fthumbnails%2Ft__2__1408454734__ab21b3a77e9dccdd82b0427be15aa172.jpg";}i:9;a:2:{s:5:"photo";s:109:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2Fyolow%2Foriginales%2F2__1408454735__d25778ed4cc51c9144243f80fcd061b5.jpg";s:9:"thumbnail";s:112:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2Fyolow%2Fthumbnails%2Ft__2__1408454735__d25778ed4cc51c9144243f80fcd061b5.jpg";}i:10;a:2:{s:5:"photo";s:109:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2Fyolow%2Foriginales%2F2__1408454736__453f79c259fbe0e9134326fa767bd90e.jpg";s:9:"thumbnail";s:112:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2Fyolow%2Fthumbnails%2Ft__2__1408454736__453f79c259fbe0e9134326fa767bd90e.jpg";}i:11;a:2:{s:5:"photo";s:109:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2Fyolow%2Foriginales%2F2__1408454736__f53a74272073f4c89ee3a8ffc37202f5.jpg";s:9:"thumbnail";s:112:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2Fyolow%2Fthumbnails%2Ft__2__1408454736__f53a74272073f4c89ee3a8ffc37202f5.jpg";}i:12;a:2:{s:5:"photo";s:109:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2Fyolow%2Foriginales%2F2__1408454737__51370ab9bd52728890511242663a8d82.jpg";s:9:"thumbnail";s:112:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2Fyolow%2Fthumbnails%2Ft__2__1408454737__51370ab9bd52728890511242663a8d82.jpg";}i:13;a:2:{s:5:"photo";s:109:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2Fyolow%2Foriginales%2F2__1408454738__592ffaaf93dc591d7d92d6d0fa0cb1a3.jpg";s:9:"thumbnail";s:112:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2Fyolow%2Fthumbnails%2Ft__2__1408454738__592ffaaf93dc591d7d92d6d0fa0cb1a3.jpg";}i:14;a:2:{s:5:"photo";s:109:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2Fyolow%2Foriginales%2F2__1408454738__5ca57742517d6a55da18fe5915d3b7e7.jpg";s:9:"thumbnail";s:112:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2Fyolow%2Fthumbnails%2Ft__2__1408454738__5ca57742517d6a55da18fe5915d3b7e7.jpg";}i:15;a:2:{s:5:"photo";s:109:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2Fyolow%2Foriginales%2F2__1408454739__fd2fba87dc55c06bff218415d3d49982.jpg";s:9:"thumbnail";s:112:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2Fyolow%2Fthumbnails%2Ft__2__1408454739__fd2fba87dc55c06bff218415d3d49982.jpg";}i:16;a:2:{s:5:"photo";s:109:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2Fyolow%2Foriginales%2F2__1408454740__ed2ad239ae1009d6587cfb284a4c2e41.jpg";s:9:"thumbnail";s:112:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2Fyolow%2Fthumbnails%2Ft__2__1408454740__ed2ad239ae1009d6587cfb284a4c2e41.jpg";}i:17;a:2:{s:5:"photo";s:109:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2Fyolow%2Foriginales%2F2__1408454740__eda9d671b4edc27cb4860216bff14d27.jpg";s:9:"thumbnail";s:112:"%2FnetBS%2Fweb%2Fphotos%2Fmontfort%2Fyolow%2Fthumbnails%2Ft__2__1408454740__eda9d671b4edc27cb4860216bff14d27.jpg";}}', 4),
(3, NULL, 'vidangite', '2014-08-19 15:28:46', 'a:0:{}', 4);

-- --------------------------------------------------------

--
-- Structure de la table `galerie_dossiers`
--

CREATE TABLE IF NOT EXISTS `galerie_dossiers` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `nom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `creation` datetime NOT NULL,
  `access` tinyint(1) NOT NULL,
  `droit_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_D953DC2D727ACA70` (`parent_id`),
  KEY `IDX_D953DC2D5AA93370` (`droit_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=20 ;

--
-- Contenu de la table `galerie_dossiers`
--

INSERT INTO `galerie_dossiers` (`id`, `parent_id`, `nom`, `creation`, `access`, `droit_id`) VALUES
(12, NULL, '2014', '2014-08-14 23:00:33', 1, 4),
(13, NULL, '2013', '2014-08-15 10:45:52', 1, 4),
(14, 13, 'Activités spéciales', '2014-08-15 11:10:28', 1, 4),
(15, NULL, 'bibite', '2014-08-15 14:33:56', 1, 4),
(16, 15, 'yolo', '2014-08-15 14:34:15', 1, 4),
(17, 16, 'uss', '2014-08-15 14:34:44', 1, 4),
(18, 17, 'ou', '2014-08-15 15:28:00', 1, 4),
(19, NULL, 'giuwffhlj', '2014-08-15 17:13:35', 1, 4);

-- --------------------------------------------------------

--
-- Structure de la table `galerie_droits`
--

CREATE TABLE IF NOT EXISTS `galerie_droits` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `droit_album` tinyint(1) NOT NULL,
  `groupe_id` int(11) DEFAULT NULL,
  `albums_visibles` tinyint(1) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `color1` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `color2` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_1E9464B07A45358C` (`groupe_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Contenu de la table `galerie_droits`
--

INSERT INTO `galerie_droits` (`id`, `droit_album`, `groupe_id`, `albums_visibles`, `active`, `color1`, `color2`) VALUES
(1, 1, 1, 1, 1, '', ''),
(3, 1, 2, 0, 1, '', ''),
(4, 1, 3, 1, 1, 'FFC60D', '3881FF'),
(5, 1, 9, 0, 1, '', ''),
(6, 1, 8, 0, 1, '', '');

-- --------------------------------------------------------

--
-- Structure de la table `public_users`
--

CREATE TABLE IF NOT EXISTS `public_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `salt` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_E52C1AE9F85E0677` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Structure de la table `security_roles`
--

CREATE TABLE IF NOT EXISTS `security_roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) COLLATE utf8_unicode_ci NOT NULL,
  `role` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_5A82CD6D57698A6A` (`role`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Contenu de la table `security_roles`
--

INSERT INTO `security_roles` (`id`, `name`, `role`) VALUES
(1, 'user', 'ROLE_USER'),
(2, 'swag', 'ROLE_SWAG'),
(3, 'test', 'ROLE_TEST'),
(4, 'trou', 'ROLE_TROU'),
(5, 'yolo', 'ROLE_YOLO');

-- --------------------------------------------------------

--
-- Structure de la table `security_users`
--

CREATE TABLE IF NOT EXISTS `security_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `membre_id` int(11) DEFAULT NULL,
  `username` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_F83F46436A99F74A` (`membre_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Contenu de la table `security_users`
--

INSERT INTO `security_users` (`id`, `membre_id`, `username`, `password`, `is_active`) VALUES
(1, 1, 'yolo', 'swag', 1),
(2, 2, 'lolo', 'pelo', 1);

-- --------------------------------------------------------

--
-- Structure de la table `stamm_downloads`
--

CREATE TABLE IF NOT EXISTS `stamm_downloads` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `categorie` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `size` int(11) NOT NULL,
  `type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Contenu de la table `stamm_downloads`
--

INSERT INTO `stamm_downloads` (`id`, `nom`, `path`, `description`, `date`, `categorie`, `size`, `type`) VALUES
(1, 'yolow', 'c9f0f895fb98ab9159f51fd0297e236d.jpeg', 'yoloooo', '2014-08-14 15:00:56', 'BS', 1660086, 'image/jpeg');

-- --------------------------------------------------------

--
-- Structure de la table `stamm_evenements`
--

CREATE TABLE IF NOT EXISTS `stamm_evenements` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `categorie` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `debut` datetime NOT NULL,
  `fin` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Contenu de la table `stamm_evenements`
--

INSERT INTO `stamm_evenements` (`id`, `nom`, `categorie`, `debut`, `fin`) VALUES
(1, 'test', 'BS', '2014-07-24 09:34:00', '2014-07-25 09:34:00'),
(2, 'camp', 'BS', '2014-10-11 08:00:00', '2014-10-15 17:00:00'),
(3, 'testes', 'BS', '2014-09-04 19:19:00', '2014-09-05 19:19:00');

-- --------------------------------------------------------

--
-- Structure de la table `stamm_news`
--

CREATE TABLE IF NOT EXISTS `stamm_news` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `contenu` longtext COLLATE utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Contenu de la table `stamm_news`
--

INSERT INTO `stamm_news` (`id`, `titre`, `contenu`, `date`) VALUES
(1, 'YAY', 'Heheheh', '2014-06-12 23:38:31'),
(2, 'los zboubos', 'les zboubs vaincront', '2014-07-22 15:52:15');

-- --------------------------------------------------------

--
-- Structure de la table `structure_attributions`
--

CREATE TABLE IF NOT EXISTS `structure_attributions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `groupe_id` int(11) DEFAULT NULL,
  `membre_id` int(11) DEFAULT NULL,
  `fonction_id` int(11) DEFAULT NULL,
  `dateDebut` date NOT NULL,
  `dateFin` date DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5B87C3A27A45358C` (`groupe_id`),
  KEY `IDX_5B87C3A26A99F74A` (`membre_id`),
  KEY `IDX_5B87C3A257889920` (`fonction_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=18 ;

--
-- Contenu de la table `structure_attributions`
--

INSERT INTO `structure_attributions` (`id`, `groupe_id`, `membre_id`, `fonction_id`, `dateDebut`, `dateFin`) VALUES
(2, 8, 2, 1, '2014-06-11', '2014-09-22'),
(5, 8, 2, 1, '2014-09-09', '2014-09-22'),
(10, 2, 2, 1, '2014-09-05', NULL),
(12, 1, 2, 1, '2014-09-19', NULL),
(15, 3, 1, 2, '2014-09-09', '2014-09-30');

-- --------------------------------------------------------

--
-- Structure de la table `structure_distinctions`
--

CREATE TABLE IF NOT EXISTS `structure_distinctions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `remarques` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Contenu de la table `structure_distinctions`
--

INSERT INTO `structure_distinctions` (`id`, `nom`, `remarques`) VALUES
(1, '1ère classe', 'A réussi l''examen du première classe'),
(2, '2ème classe', 'Progression 2 ème branche');

-- --------------------------------------------------------

--
-- Structure de la table `structure_fonctions`
--

CREATE TABLE IF NOT EXISTS `structure_fonctions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `abreviation` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Contenu de la table `structure_fonctions`
--

INSERT INTO `structure_fonctions` (`id`, `nom`, `abreviation`) VALUES
(1, 'membre', 'M'),
(2, 'adjoint', 'ADJ');

-- --------------------------------------------------------

--
-- Structure de la table `structure_groupes`
--

CREATE TABLE IF NOT EXISTS `structure_groupes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `type_id` int(11) DEFAULT NULL,
  `nom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_8CC116E8727ACA70` (`parent_id`),
  KEY `IDX_8CC116E8C54C8C93` (`type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=15 ;

--
-- Contenu de la table `structure_groupes`
--

INSERT INTO `structure_groupes` (`id`, `parent_id`, `type_id`, `nom`) VALUES
(1, NULL, NULL, 'Brigade de Sauvabelin'),
(2, 1, NULL, '2ème Branche'),
(3, 2, NULL, 'montfort'),
(4, 3, NULL, 'jean-bart'),
(5, 3, NULL, 'surcouf'),
(6, 3, NULL, 'frégate'),
(7, 1, NULL, '1ère branche'),
(8, 2, NULL, 'santis'),
(9, 2, NULL, 'Bérisal'),
(11, 2, NULL, 'La Neuvaz'),
(12, 2, NULL, 'Zanfleuron'),
(13, 3, 2, 'gallion'),
(14, 2, 1, 'Manloud');

-- --------------------------------------------------------

--
-- Structure de la table `structure_obtention_distinctions`
--

CREATE TABLE IF NOT EXISTS `structure_obtention_distinctions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `membre_id` int(11) DEFAULT NULL,
  `obtention` date NOT NULL,
  `distinction_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_C7ADEDEF6A99F74A` (`membre_id`),
  KEY `IDX_C7ADEDEF59F3DFC6` (`distinction_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Contenu de la table `structure_obtention_distinctions`
--

INSERT INTO `structure_obtention_distinctions` (`id`, `membre_id`, `obtention`, `distinction_id`) VALUES
(1, 1, '2009-12-13', 1);

-- --------------------------------------------------------

--
-- Structure de la table `structure_types`
--

CREATE TABLE IF NOT EXISTS `structure_types` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Contenu de la table `structure_types`
--

INSERT INTO `structure_types` (`id`, `nom`) VALUES
(1, 'troupe'),
(2, 'patrouille');

-- --------------------------------------------------------

--
-- Structure de la table `user_role`
--

CREATE TABLE IF NOT EXISTS `user_role` (
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `IDX_2DE8C6A3A76ED395` (`user_id`),
  KEY `IDX_2DE8C6A3D60322AC` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Contenu de la table `user_role`
--

INSERT INTO `user_role` (`user_id`, `role_id`) VALUES
(1, 1),
(1, 4),
(1, 5),
(2, 5);

-- --------------------------------------------------------

--
-- Structure de la table `validator`
--

CREATE TABLE IF NOT EXISTS `validator` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `avant` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:object)',
  `apres` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:object)',
  `date` datetime NOT NULL,
  `auteur` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:object)',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `fichier_familles`
--
ALTER TABLE `fichier_familles`
  ADD CONSTRAINT `FK_27F2CBD439DEC40E` FOREIGN KEY (`mere_id`) REFERENCES `fichier_geniteurs` (`id`),
  ADD CONSTRAINT `FK_27F2CBD43FD73900` FOREIGN KEY (`pere_id`) REFERENCES `fichier_geniteurs` (`id`),
  ADD CONSTRAINT `FK_27F2CBD44DE7DC5C` FOREIGN KEY (`adresse_id`) REFERENCES `fichier_adresses` (`id`),
  ADD CONSTRAINT `FK_27F2CBD4E7A1254A` FOREIGN KEY (`contact_id`) REFERENCES `fichier_contacts` (`id`);

--
-- Contraintes pour la table `fichier_geniteurs`
--
ALTER TABLE `fichier_geniteurs`
  ADD CONSTRAINT `FK_10EB2AD14DE7DC5C` FOREIGN KEY (`adresse_id`) REFERENCES `fichier_adresses` (`id`),
  ADD CONSTRAINT `FK_10EB2AD1E7A1254A` FOREIGN KEY (`contact_id`) REFERENCES `fichier_contacts` (`id`);

--
-- Contraintes pour la table `fichier_membres`
--
ALTER TABLE `fichier_membres`
  ADD CONSTRAINT `FK_82D91FAB4DE7DC5C` FOREIGN KEY (`adresse_id`) REFERENCES `fichier_adresses` (`id`),
  ADD CONSTRAINT `FK_82D91FAB97A77B84` FOREIGN KEY (`famille_id`) REFERENCES `fichier_familles` (`id`),
  ADD CONSTRAINT `FK_82D91FABE7A1254A` FOREIGN KEY (`contact_id`) REFERENCES `fichier_contacts` (`id`);

--
-- Contraintes pour la table `galerie_albums`
--
ALTER TABLE `galerie_albums`
  ADD CONSTRAINT `FK_EDDFF7315AA93370` FOREIGN KEY (`droit_id`) REFERENCES `galerie_droits` (`id`),
  ADD CONSTRAINT `FK_EDDFF731611C0C56` FOREIGN KEY (`dossier_id`) REFERENCES `galerie_dossiers` (`id`);

--
-- Contraintes pour la table `galerie_dossiers`
--
ALTER TABLE `galerie_dossiers`
  ADD CONSTRAINT `FK_D953DC2D5AA93370` FOREIGN KEY (`droit_id`) REFERENCES `galerie_droits` (`id`),
  ADD CONSTRAINT `FK_D953DC2D727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `galerie_dossiers` (`id`);

--
-- Contraintes pour la table `galerie_droits`
--
ALTER TABLE `galerie_droits`
  ADD CONSTRAINT `FK_1E9464B07A45358C` FOREIGN KEY (`groupe_id`) REFERENCES `structure_groupes` (`id`);

--
-- Contraintes pour la table `security_users`
--
ALTER TABLE `security_users`
  ADD CONSTRAINT `FK_F83F46436A99F74A` FOREIGN KEY (`membre_id`) REFERENCES `fichier_membres` (`id`);

--
-- Contraintes pour la table `structure_attributions`
--
ALTER TABLE `structure_attributions`
  ADD CONSTRAINT `FK_5B87C3A257889920` FOREIGN KEY (`fonction_id`) REFERENCES `structure_fonctions` (`id`),
  ADD CONSTRAINT `FK_5B87C3A26A99F74A` FOREIGN KEY (`membre_id`) REFERENCES `fichier_membres` (`id`),
  ADD CONSTRAINT `FK_5B87C3A27A45358C` FOREIGN KEY (`groupe_id`) REFERENCES `structure_groupes` (`id`);

--
-- Contraintes pour la table `structure_groupes`
--
ALTER TABLE `structure_groupes`
  ADD CONSTRAINT `FK_8CC116E8727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `structure_groupes` (`id`),
  ADD CONSTRAINT `FK_8CC116E8C54C8C93` FOREIGN KEY (`type_id`) REFERENCES `structure_types` (`id`);

--
-- Contraintes pour la table `structure_obtention_distinctions`
--
ALTER TABLE `structure_obtention_distinctions`
  ADD CONSTRAINT `FK_C7ADEDEF59F3DFC6` FOREIGN KEY (`distinction_id`) REFERENCES `structure_distinctions` (`id`),
  ADD CONSTRAINT `FK_C7ADEDEF6A99F74A` FOREIGN KEY (`membre_id`) REFERENCES `fichier_membres` (`id`);

--
-- Contraintes pour la table `user_role`
--
ALTER TABLE `user_role`
  ADD CONSTRAINT `FK_2DE8C6A3A76ED395` FOREIGN KEY (`user_id`) REFERENCES `security_users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_2DE8C6A3D60322AC` FOREIGN KEY (`role_id`) REFERENCES `security_roles` (`id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
