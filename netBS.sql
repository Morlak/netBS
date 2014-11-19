-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Client: localhost
-- Généré le: Mar 18 Novembre 2014 à 17:28
-- Version du serveur: 5.5.40-0ubuntu0.14.04.1
-- Version de PHP: 5.5.9-1ubuntu4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de données: `netBS`
--

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9 ;

--
-- Contenu de la table `fichier_adresses`
--

INSERT INTO `fichier_adresses` (`id`, `rue`, `npa`, `localite`, `facturable`, `remarques`) VALUES
(3, 'chemin des zboub', 1021, 'forelion', 0, NULL),
(8, 'Chemin des baaa 2', 10392, 'oloCity', 0, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `fichier_familles`
--

CREATE TABLE IF NOT EXISTS `fichier_familles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adresse_id` int(11) DEFAULT NULL,
  `pere_id` int(11) DEFAULT NULL,
  `mere_id` int(11) DEFAULT NULL,
  `nom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_27F2CBD44DE7DC5C` (`adresse_id`),
  UNIQUE KEY `UNIQ_27F2CBD43FD73900` (`pere_id`),
  UNIQUE KEY `UNIQ_27F2CBD439DEC40E` (`mere_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Contenu de la table `fichier_familles`
--

INSERT INTO `fichier_familles` (`id`, `adresse_id`, `pere_id`, `mere_id`, `nom`) VALUES
(1, 3, 1, 2, 'Hochet');

-- --------------------------------------------------------

--
-- Structure de la table `fichier_geniteurs`
--

CREATE TABLE IF NOT EXISTS `fichier_geniteurs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adresse_id` int(11) DEFAULT NULL,
  `telephones` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `emails` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `profession` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `prenom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sexe` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_10EB2AD14DE7DC5C` (`adresse_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Contenu de la table `fichier_geniteurs`
--

INSERT INTO `fichier_geniteurs` (`id`, `adresse_id`, `telephones`, `emails`, `profession`, `prenom`, `sexe`) VALUES
(1, NULL, 'a:1:{i:0;s:0:"";}''', 'a:1:{i:0;s:0:"";}''', 'Ingénieur', 'Bertrand', 'Homme'),
(2, NULL, 'a:1:{i:0;s:0:"";}''', 'a:1:{i:0;s:0:"";}''', 'Physio', 'Catherine', 'Femme');

-- --------------------------------------------------------

--
-- Structure de la table `fichier_membres`
--

CREATE TABLE IF NOT EXISTS `fichier_membres` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `famille_id` int(11) DEFAULT NULL,
  `adresse_id` int(11) DEFAULT NULL,
  `telephones` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `emails` longtext COLLATE utf8_unicode_ci NOT NULL COMMENT '(DC2Type:array)',
  `naissance` date NOT NULL,
  `numero_bs` int(11) DEFAULT NULL,
  `numero_avs` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `statut` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `inscription` date NOT NULL,
  `remarques` longtext COLLATE utf8_unicode_ci,
  `prenom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `sexe` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `adressePrincipale_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_82D91FAB4DE7DC5C` (`adresse_id`),
  UNIQUE KEY `UNIQ_82D91FABCA8EA25` (`adressePrincipale_id`),
  KEY `IDX_82D91FAB97A77B84` (`famille_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Contenu de la table `fichier_membres`
--

INSERT INTO `fichier_membres` (`id`, `famille_id`, `adresse_id`, `telephones`, `emails`, `naissance`, `numero_bs`, `numero_avs`, `statut`, `inscription`, `remarques`, `prenom`, `sexe`, `adressePrincipale_id`) VALUES
(1, 1, NULL, 'a:1:{i:0;s:0:"";}''', 'a:1:{i:0;s:0:"";}''', '2014-11-12', 5675, '583958398', NULL, '2014-11-06', NULL, 'Guillaume', 'M', NULL),
(7, 1, 8, 'N;', 'N;', '2000-07-03', NULL, NULL, NULL, '2014-11-17', NULL, 'jonas', 'homme', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `global_modifications`
--

CREATE TABLE IF NOT EXISTS `global_modifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `validation_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `champ` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `valeur` longtext COLLATE utf8_unicode_ci NOT NULL,
  `path` longtext COLLATE utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_61883D87A2274850` (`validation_id`),
  KEY `IDX_61883D87A76ED395` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=13 ;

--
-- Contenu de la table `global_modifications`
--

INSERT INTO `global_modifications` (`id`, `validation_id`, `user_id`, `champ`, `valeur`, `path`, `date`) VALUES
(1, 1, 1, 'Naissance', '2000-07-03 00:00:00', '', '2014-11-17 08:09:11'),
(2, 1, 1, 'Prenom', 'jonas', '', '2014-11-17 09:07:57'),
(3, 1, 1, 'Famille', 'ENTITY__InterneFichierBundle:Famille__1', '', '2014-11-17 09:07:57'),
(4, 1, 1, 'Rue', 'Chemin des baaa 2', 'Adresse', '2014-11-17 09:09:48'),
(5, 1, 1, 'Npa', '10392', 'Adresse', '2014-11-17 09:09:48'),
(6, 1, 1, 'Localite', 'oloCity', 'Adresse', '2014-11-17 09:09:48'),
(9, 1, 1, 'Sexe', 'homme', '', '2014-11-17 10:46:15'),
(10, 1, 1, 'Facturable', '0', 'Adresse', '2014-11-17 14:30:46'),
(12, 5, 1, 'Rue', 'Avenue des hommes qui pèsent 42', 'Adresse', '2014-11-17 14:54:15');

-- --------------------------------------------------------

--
-- Structure de la table `global_validation`
--

CREATE TABLE IF NOT EXISTS `global_validation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `statut` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `classIdentifier` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `repo` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `entityId` int(11) NOT NULL,
  `entityName` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `fullClass` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Contenu de la table `global_validation`
--

INSERT INTO `global_validation` (`id`, `statut`, `classIdentifier`, `repo`, `entityId`, `entityName`, `fullClass`) VALUES
(1, 'CREATION', '393370392a99c8e0648cce246b71964e', 'InterneFichierBundle:Membre', 0, 'Membre', 'Interne\\FichierBundle\\Entity\\Membre'),
(5, 'MODIFICATION', 'bab24574dec5c0b94f331a253b1b1e6a', 'InterneFichierBundle:Membre', 7, 'Membre', 'Interne\\FichierBundle\\Entity\\Membre');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Contenu de la table `security_roles`
--

INSERT INTO `security_roles` (`id`, `name`, `role`) VALUES
(1, 'administrateur', 'ROLE_ADMIN');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Contenu de la table `security_users`
--

INSERT INTO `security_users` (`id`, `membre_id`, `username`, `password`, `is_active`) VALUES
(1, 1, 'yolo', 'swag', 1);

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
(1, 'ziuoio', 'd3d9446802a44259755d38e6d163e820.png', 'uioj', '2014-11-12 11:28:49', 'BS', 72965, 'image/png');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Contenu de la table `stamm_news`
--

INSERT INTO `stamm_news` (`id`, `titre`, `contenu`, `date`) VALUES
(1, 'labite', '4444444444444444', '2014-11-12 11:14:43'),
(2, 'test', 'premier zhgfc', '2014-11-12 16:49:18'),
(3, 'Martial...', 'T''as échoué', '2014-11-17 13:51:23');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Contenu de la table `structure_attributions`
--

INSERT INTO `structure_attributions` (`id`, `groupe_id`, `membre_id`, `fonction_id`, `dateDebut`, `dateFin`) VALUES
(1, 1, 1, 1, '2000-11-05', '2020-12-24'),
(2, 2, 1, 1, '2009-02-01', '2014-12-14');

-- --------------------------------------------------------

--
-- Structure de la table `structure_distinctions`
--

CREATE TABLE IF NOT EXISTS `structure_distinctions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `remarques` longtext COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Contenu de la table `structure_distinctions`
--

INSERT INTO `structure_distinctions` (`id`, `nom`, `remarques`) VALUES
(1, 'responsable du swag', 'chef qui régule le niveau de swag de la BS'),
(2, 'test', 'test si au moins ca fonctionne'),
(3, 'test', 'test si au moins ca fonctionne');

-- --------------------------------------------------------

--
-- Structure de la table `structure_fonctions`
--

CREATE TABLE IF NOT EXISTS `structure_fonctions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `abreviation` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Contenu de la table `structure_fonctions`
--

INSERT INTO `structure_fonctions` (`id`, `nom`, `abreviation`) VALUES
(1, 'Chef des bails', 'CB');

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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Contenu de la table `structure_groupes`
--

INSERT INTO `structure_groupes` (`id`, `parent_id`, `type_id`, `nom`) VALUES
(1, NULL, 1, 'Brigade de Sauvabelin'),
(2, 1, 1, 'Montfort'),
(3, 2, 1, 'Jean-bart'),
(4, 1, 1, 'Test');

-- --------------------------------------------------------

--
-- Structure de la table `structure_obtention_distinctions`
--

CREATE TABLE IF NOT EXISTS `structure_obtention_distinctions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `distinction_id` int(11) DEFAULT NULL,
  `membre_id` int(11) DEFAULT NULL,
  `obtention` date NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_C7ADEDEF59F3DFC6` (`distinction_id`),
  KEY `IDX_C7ADEDEF6A99F74A` (`membre_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

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
(1, 'groupe maitre'),
(2, 'caca');

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
(1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `Validator`
--

CREATE TABLE IF NOT EXISTS `Validator` (
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
  ADD CONSTRAINT `FK_27F2CBD44DE7DC5C` FOREIGN KEY (`adresse_id`) REFERENCES `fichier_adresses` (`id`);

--
-- Contraintes pour la table `fichier_geniteurs`
--
ALTER TABLE `fichier_geniteurs`
  ADD CONSTRAINT `FK_10EB2AD14DE7DC5C` FOREIGN KEY (`adresse_id`) REFERENCES `fichier_adresses` (`id`);

--
-- Contraintes pour la table `fichier_membres`
--
ALTER TABLE `fichier_membres`
  ADD CONSTRAINT `FK_82D91FAB4DE7DC5C` FOREIGN KEY (`adresse_id`) REFERENCES `fichier_adresses` (`id`),
  ADD CONSTRAINT `FK_82D91FAB97A77B84` FOREIGN KEY (`famille_id`) REFERENCES `fichier_familles` (`id`),
  ADD CONSTRAINT `FK_82D91FABCA8EA25` FOREIGN KEY (`adressePrincipale_id`) REFERENCES `fichier_adresses` (`id`);

--
-- Contraintes pour la table `global_modifications`
--
ALTER TABLE `global_modifications`
  ADD CONSTRAINT `FK_61883D87A76ED395` FOREIGN KEY (`user_id`) REFERENCES `security_users` (`id`),
  ADD CONSTRAINT `FK_61883D87A2274850` FOREIGN KEY (`validation_id`) REFERENCES `global_validation` (`id`);

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
