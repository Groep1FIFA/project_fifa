-- phpMyAdmin SQL Dump
-- version 4.0.10.6
-- http://www.phpmyadmin.net
--
-- Host: mysql-fifaproject.alwaysdata.net
-- Generation Time: Jun 16, 2017 at 08:48 AM
-- Server version: 10.1.23-MariaDB
-- PHP Version: 5.6.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `fifaproject_project_fifa`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_admin`
--

CREATE TABLE IF NOT EXISTS `tbl_admin` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `tbl_admin`
--

INSERT INTO `tbl_admin` (`id`, `username`, `password`, `admin`, `created_at`, `deleted_at`) VALUES
(1, 'admin', 'admin123', 1, '2017-04-21 09:13:49', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_matches`
--

CREATE TABLE IF NOT EXISTS `tbl_matches` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `team_id_a` int(10) unsigned NOT NULL,
  `team_id_b` int(10) unsigned NOT NULL,
  `poule_id` int(10) NOT NULL,
  `score_team_a` int(10) unsigned DEFAULT NULL,
  `score_team_b` int(10) unsigned DEFAULT NULL,
  `start_time` datetime NOT NULL,
  `started` tinyint(1) NOT NULL DEFAULT '0',
  `finished` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `tbl_matches_ibfk_1` (`team_id_a`),
  KEY `tbl_matches_ibfk_2` (`team_id_b`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT AUTO_INCREMENT=33 ;

--
-- Dumping data for table `tbl_matches`
--

INSERT INTO `tbl_matches` (`id`, `team_id_a`, `team_id_b`, `poule_id`, `score_team_a`, `score_team_b`, `start_time`, `started`, `finished`) VALUES
(2, 1, 2, 2, NULL, NULL, '2017-05-04 00:00:00', 0, 0),
(3, 3, 4, 1, NULL, NULL, '2017-05-05 00:00:00', 0, 0),
(4, 3, 4, 2, NULL, NULL, '2017-05-04 00:00:00', 0, 0),
(5, 1, 4, 1, NULL, NULL, '2017-05-04 00:00:00', 0, 0),
(6, 1, 4, 2, NULL, NULL, '2017-05-04 00:00:00', 0, 0),
(7, 2, 3, 1, NULL, NULL, '2017-05-04 00:00:00', 0, 0),
(8, 2, 3, 2, NULL, NULL, '2017-05-04 00:00:00', 0, 0),
(9, 1, 3, 2, NULL, NULL, '2017-05-04 00:00:00', 0, 0),
(10, 1, 3, 1, NULL, NULL, '2017-05-04 00:00:00', 0, 0),
(11, 2, 4, 1, NULL, NULL, '2017-05-04 00:00:00', 0, 0),
(12, 2, 4, 2, NULL, NULL, '2017-05-04 00:00:00', 0, 0),
(16, 3, 4, 4, NULL, NULL, '2017-05-02 00:00:00', 0, 0),
(17, 1, 4, 3, NULL, NULL, '2017-05-03 00:00:00', 0, 0),
(18, 1, 4, 4, NULL, NULL, '2017-05-02 00:00:00', 0, 0),
(20, 2, 3, 4, NULL, NULL, '2017-05-02 00:00:00', 0, 0),
(21, 1, 3, 3, NULL, NULL, '2017-05-03 00:00:00', 0, 0),
(22, 1, 3, 4, NULL, NULL, '2017-05-02 00:00:00', 0, 0),
(24, 2, 4, 4, NULL, NULL, '2017-05-03 00:00:00', 0, 0),
(25, 1, 2, 3, NULL, NULL, '2017-07-11 11:00:00', 0, 0),
(26, 1, 2, 1, NULL, NULL, '2017-06-23 13:00:00', 0, 0),
(27, 1, 2, 4, NULL, NULL, '0000-00-00 00:00:00', 0, 0),
(28, 2, 3, 3, NULL, NULL, '0000-00-00 00:00:00', 0, 0),
(29, 2, 4, 3, NULL, NULL, '0000-00-00 00:00:00', 0, 0),
(32, 3, 4, 3, NULL, NULL, '0000-00-00 00:00:00', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_players`
--

CREATE TABLE IF NOT EXISTS `tbl_players` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `student_id` varchar(10) NOT NULL,
  `team_id` int(11) unsigned DEFAULT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `goals` int(10) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `team_id` (`team_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=34 ;

--
-- Dumping data for table `tbl_players`
--

INSERT INTO `tbl_players` (`id`, `student_id`, `team_id`, `first_name`, `last_name`, `goals`, `created_at`, `deleted_at`) VALUES
(1, 'd123456', 1, 'Lasse', 'Schöne', 0, '2017-04-13 09:44:13', NULL),
(2, 'd5435435', 1, 'Davy ', 'Klaassen', 0, '2017-04-13 09:44:13', NULL),
(3, 'd545454', 1, 'Hakim ', 'Ziyech', 0, '2017-04-13 09:45:47', NULL),
(4, 'd666555', 1, 'Kasper', 'Dolberg', 0, '2017-04-13 09:45:47', NULL),
(5, 'd74745', 2, 'Luuk', 'de Jong', 6, '2017-04-13 09:48:23', NULL),
(6, 'd987665', 2, 'Siem', 'de Jong', 0, '2017-04-13 09:48:23', NULL),
(7, 'd11555', 2, 'Jeroen', 'Zoet', 0, '2017-04-13 09:48:23', NULL),
(8, 'd544566', 2, 'Hector', 'Moreno', 0, '2017-04-13 09:48:23', NULL),
(23, 'd233407', 3, 'Youri', 'van der Sande', 0, '2017-05-02 14:30:33', NULL),
(24, 'd228788', 9, 'Alex', 'Haverkamp', 0, '2017-05-02 14:31:07', NULL),
(25, 'd167788', 9, 'Dave', 'van Oosterhout', 0, '2017-05-02 15:43:00', NULL),
(26, 'd223344', 5, 'Bart', 'Roos', 0, '2017-05-02 16:26:48', NULL),
(27, 'd181761', NULL, 'Jurriaan', 'Roelen', 0, '2017-05-03 14:00:45', NULL),
(29, 'd229606', NULL, 'Armin', 'Fahim', 0, '2017-05-15 16:44:40', NULL),
(31, 'bp153373', NULL, 'Bjorn', 'Patje', 0, '2017-05-16 16:17:30', NULL),
(33, 'd224580', NULL, 'maarten', 'Donkersloot', 0, '2017-06-09 10:04:39', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_playoffs`
--

CREATE TABLE IF NOT EXISTS `tbl_playoffs` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `poule_id_a` int(10) DEFAULT NULL,
  `poule_id_b` int(10) DEFAULT NULL,
  `poule_ranking_a` int(10) DEFAULT NULL,
  `poule_ranking_b` int(10) DEFAULT NULL,
  `playoff_id_a` int(10) DEFAULT NULL,
  `playoff_id_b` int(10) DEFAULT NULL,
  `playoff_ranking_a` int(10) NOT NULL DEFAULT '0',
  `playoff_ranking_b` int(10) NOT NULL DEFAULT '0',
  `score_team_a` int(10) DEFAULT NULL,
  `score_team_b` int(10) DEFAULT NULL,
  `start_time` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `started` tinyint(1) NOT NULL DEFAULT '0',
  `finished` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `tbl_playoffs`
--

INSERT INTO `tbl_playoffs` (`id`, `poule_id_a`, `poule_id_b`, `poule_ranking_a`, `poule_ranking_b`, `playoff_id_a`, `playoff_id_b`, `playoff_ranking_a`, `playoff_ranking_b`, `score_team_a`, `score_team_b`, `start_time`, `started`, `finished`) VALUES
(1, 1, 2, 1, 2, NULL, NULL, 0, 0, NULL, NULL, '2017-05-11 11:15:57', 0, 0),
(2, 1, 2, 2, 1, NULL, NULL, 0, 0, NULL, NULL, '2017-05-11 11:15:57', 0, 0),
(3, 3, 4, 1, 2, NULL, NULL, 0, 0, NULL, NULL, '2017-05-11 11:17:00', 0, 0),
(4, 3, 4, 2, 1, NULL, NULL, 0, 0, NULL, NULL, '2017-05-11 11:17:00', 0, 0),
(5, NULL, NULL, NULL, NULL, 1, 2, 1, 1, NULL, NULL, '2017-05-11 11:29:34', 0, 0),
(6, NULL, NULL, NULL, NULL, 3, 4, 1, 1, NULL, NULL, '2017-05-11 11:30:04', 0, 0),
(7, NULL, NULL, NULL, NULL, 5, 6, 2, 2, NULL, NULL, '2017-05-11 11:30:41', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_poules`
--

CREATE TABLE IF NOT EXISTS `tbl_poules` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(10) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `naam` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `tbl_poules`
--

INSERT INTO `tbl_poules` (`id`, `name`, `created_at`, `deleted_at`) VALUES
(1, 'A', '2017-05-02 16:31:27', NULL),
(2, 'B', '2017-05-02 16:31:27', NULL),
(3, 'C', '2017-05-02 16:32:21', NULL),
(4, 'D', '2017-05-02 16:32:21', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_teams`
--

CREATE TABLE IF NOT EXISTS `tbl_teams` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `poule_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `points` int(10) NOT NULL DEFAULT '0',
  `team_nr` int(10) DEFAULT NULL,
  `poule_ranking` int(10) NOT NULL DEFAULT '0',
  `playoff_ranking` int(11) NOT NULL DEFAULT '0',
  `playoff_id` int(10) NOT NULL DEFAULT '0',
  `win` int(10) NOT NULL DEFAULT '0',
  `lose` int(10) NOT NULL DEFAULT '0',
  `tie` int(10) NOT NULL DEFAULT '0',
  `goal_balance` int(10) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=21 ;

--
-- Dumping data for table `tbl_teams`
--

INSERT INTO `tbl_teams` (`id`, `poule_id`, `name`, `points`, `team_nr`, `poule_ranking`, `playoff_ranking`, `playoff_id`, `win`, `lose`, `tie`, `goal_balance`, `created_at`, `deleted_at`) VALUES
(1, 1, 'Ajax', 0, 1, 0, 0, 0, 0, 0, 0, 0, '2017-04-13 09:42:45', NULL),
(2, 2, 'PSV', 0, 1, 0, 0, 0, 0, 0, 0, 0, '2017-04-13 09:42:45', NULL),
(3, 1, 'FC Twente', 0, 2, 0, 0, 0, 0, 0, 0, 0, '2017-05-01 16:22:55', NULL),
(4, 1, 'NEC', 0, 3, 0, 0, 0, 0, 0, 0, 0, '2017-05-02 13:56:57', NULL),
(5, 1, 'NAC', 0, 4, 0, 0, 0, 0, 0, 0, 0, '2017-05-02 13:57:03', NULL),
(6, 2, 'Sparta', 0, 3, 0, 0, 0, 0, 0, 0, 0, '2017-05-02 16:27:08', NULL),
(7, 2, 'Willem II', 0, 2, 0, 0, 0, 0, 0, 0, 0, '2017-05-03 13:44:24', NULL),
(8, 2, 'Vitesse', 0, 4, 0, 0, 0, 0, 0, 0, 0, '2017-05-04 11:55:20', NULL),
(9, 3, 'Feyenoord', 0, 1, 0, 0, 0, 0, 0, 0, 0, '2017-05-11 09:03:47', NULL),
(10, 3, 'AZ', 0, 2, 0, 0, 0, 0, 0, 0, 0, '2017-05-11 12:13:37', NULL),
(11, 4, 'Excelsior', 0, 1, 0, 0, 0, 0, 0, 0, 0, '2017-05-12 09:19:20', NULL),
(12, 4, 'Heerenveen', 0, 2, 0, 0, 0, 0, 0, 0, 0, '2017-05-12 09:19:27', NULL),
(13, 3, 'Groningen', 0, 3, 0, 0, 0, 0, 0, 0, 0, '2017-05-15 16:19:08', NULL),
(14, 3, 'Roda JC', 0, 4, 0, 0, 0, 0, 0, 0, 0, '2017-05-16 13:57:22', NULL),
(19, 4, 'Heracles', 0, 3, 0, 0, 0, 0, 0, 0, 0, '2017-06-08 11:40:43', NULL),
(20, 4, 'FC Utrecht', 0, 4, 0, 0, 0, 0, 0, 0, 0, '2017-06-09 11:06:08', NULL);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tbl_matches`
--
ALTER TABLE `tbl_matches`
  ADD CONSTRAINT `tbl_matches_ibfk_1` FOREIGN KEY (`team_id_a`) REFERENCES `tbl_teams` (`id`),
  ADD CONSTRAINT `tbl_matches_ibfk_2` FOREIGN KEY (`team_id_b`) REFERENCES `tbl_teams` (`id`);

--
-- Constraints for table `tbl_players`
--
ALTER TABLE `tbl_players`
  ADD CONSTRAINT `tbl_players_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `tbl_teams` (`id`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
