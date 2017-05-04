-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 04 mei 2017 om 11:10
-- Serverversie: 10.1.21-MariaDB
-- PHP-versie: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `project_fifa`
--

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tbl_admin`
--

CREATE TABLE `tbl_admin` (
  `id` int(10) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `tbl_admin`
--

INSERT INTO `tbl_admin` (`id`, `username`, `password`, `admin`, `created_at`, `deleted_at`) VALUES
(1, 'admin', 'admin123', 1, '2017-04-21 09:13:49', NULL);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tbl_matches`
--

CREATE TABLE `tbl_matches` (
  `id` int(10) UNSIGNED NOT NULL,
  `team_id_a` int(10) UNSIGNED NOT NULL,
  `team_id_b` int(10) UNSIGNED NOT NULL,
  `poule_id` int(10) NOT NULL,
  `score_team_a` int(10) UNSIGNED DEFAULT NULL,
  `score_team_b` int(10) UNSIGNED DEFAULT NULL,
  `start_time` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Gegevens worden geëxporteerd voor tabel `tbl_matches`
--

INSERT INTO `tbl_matches` (`id`, `team_id_a`, `team_id_b`, `poule_id`, `score_team_a`, `score_team_b`, `start_time`) VALUES
(1, 1, 2, 1, 2, 4, '2017-04-13 18:00:00'),
(2, 1, 2, 2, NULL, NULL, '2017-05-04 00:00:00'),
(3, 3, 4, 1, NULL, NULL, '2017-05-05 00:00:00'),
(4, 3, 4, 2, NULL, NULL, '2017-05-04 00:00:00'),
(5, 1, 4, 1, NULL, NULL, '2017-05-04 00:00:00'),
(6, 1, 4, 2, NULL, NULL, '2017-05-04 00:00:00'),
(7, 2, 3, 1, NULL, NULL, '2017-05-04 00:00:00'),
(8, 2, 3, 2, NULL, NULL, '2017-05-04 00:00:00'),
(9, 1, 3, 1, NULL, NULL, '2017-05-04 00:00:00'),
(10, 1, 3, 2, NULL, NULL, '2017-05-04 00:00:00'),
(11, 2, 4, 1, NULL, NULL, '2017-05-04 00:00:00'),
(12, 2, 4, 2, NULL, NULL, '2017-05-04 00:00:00');

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tbl_players`
--

CREATE TABLE `tbl_players` (
  `id` int(11) UNSIGNED NOT NULL,
  `student_id` varchar(10) NOT NULL,
  `team_id` int(11) UNSIGNED DEFAULT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `goals` int(10) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `tbl_players`
--

INSERT INTO `tbl_players` (`id`, `student_id`, `team_id`, `first_name`, `last_name`, `goals`, `created_at`, `deleted_at`) VALUES
(1, 'd123456', 1, 'Lasse', 'Schöne', 0, '2017-04-13 09:44:13', NULL),
(2, 'd5435435', 1, 'Davy ', 'Klaassen', 0, '2017-04-13 09:44:13', NULL),
(3, 'd545454', 1, 'Hakim ', 'Ziyech', 0, '2017-04-13 09:45:47', NULL),
(4, 'd666555', 1, 'Kasper', 'Dolberg', 0, '2017-04-13 09:45:47', NULL),
(5, 'd74745', 2, 'Luuk', 'de Jong', 0, '2017-04-13 09:48:23', NULL),
(6, 'd987665', 2, 'Siem', 'de Jong', 0, '2017-04-13 09:48:23', NULL),
(7, 'd11555', 2, 'Jeroen', 'Zoet', 0, '2017-04-13 09:48:23', NULL),
(8, 'd544566', 2, 'Hector', 'Moreno', 0, '2017-04-13 09:48:23', NULL),
(23, 'd233407', NULL, 'Youri', 'van der Sande', 10, '2017-05-02 14:30:33', NULL),
(24, 'd228788', NULL, 'Alex', 'Haverkamp', 0, '2017-05-02 14:31:07', NULL),
(25, 'd167788', NULL, 'Dave', 'van Oosterhout', 0, '2017-05-02 15:43:00', NULL),
(26, 'd223344', NULL, 'Bart', 'Roos', 0, '2017-05-02 16:26:48', NULL),
(27, 'd181761', NULL, 'Jurriaan', 'Roelen', 0, '2017-05-03 14:00:45', NULL),
(28, 'd223013', NULL, 'Dion', 'Rodie', 0, '2017-05-04 08:44:30', NULL);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tbl_poules`
--

CREATE TABLE `tbl_poules` (
  `id` int(11) NOT NULL,
  `naam` varchar(10) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `tbl_poules`
--

INSERT INTO `tbl_poules` (`id`, `naam`, `created_at`, `deleted_at`) VALUES
(1, 'A', '2017-05-02 16:31:27', NULL),
(2, 'B', '2017-05-02 16:31:27', NULL),
(3, 'C', '2017-05-02 16:32:21', NULL),
(4, 'D', '2017-05-02 16:32:21', NULL);

-- --------------------------------------------------------

--
-- Tabelstructuur voor tabel `tbl_teams`
--

CREATE TABLE `tbl_teams` (
  `id` int(11) UNSIGNED NOT NULL,
  `poule_id` int(11) DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `points` int(10) NOT NULL DEFAULT '0',
  `team_nr` int(10) DEFAULT NULL,
  `win` int(10) NOT NULL DEFAULT '0',
  `lose` int(10) NOT NULL DEFAULT '0',
  `tie` int(10) NOT NULL DEFAULT '0',
  `goal_balance` int(10) NOT NULL DEFAULT '0',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Gegevens worden geëxporteerd voor tabel `tbl_teams`
--

INSERT INTO `tbl_teams` (`id`, `poule_id`, `name`, `points`, `team_nr`, `win`, `lose`, `tie`, `goal_balance`, `created_at`, `deleted_at`) VALUES
(1, NULL, 'Ajax', 0, NULL, 0, 0, 0, 0, '2017-04-13 09:42:45', NULL),
(2, NULL, 'PSV', 0, NULL, 0, 0, 0, 0, '2017-04-13 09:42:45', NULL),
(3, NULL, 'FC Twente', 0, NULL, 0, 0, 0, 0, '2017-05-01 16:22:55', NULL),
(4, NULL, 'NEC', 0, NULL, 0, 0, 0, 0, '2017-05-02 13:56:57', NULL),
(5, NULL, 'NAC', 0, NULL, 0, 0, 0, 0, '2017-05-02 13:57:03', NULL),
(6, NULL, 'Sparta', 0, NULL, 0, 0, 0, 0, '2017-05-02 16:27:08', NULL),
(7, NULL, 'Willem II', 0, NULL, 0, 0, 0, 0, '2017-05-03 13:44:24', NULL);

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `tbl_admin`
--
ALTER TABLE `tbl_admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexen voor tabel `tbl_matches`
--
ALTER TABLE `tbl_matches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tbl_matches_ibfk_1` (`team_id_a`),
  ADD KEY `tbl_matches_ibfk_2` (`team_id_b`);

--
-- Indexen voor tabel `tbl_players`
--
ALTER TABLE `tbl_players`
  ADD PRIMARY KEY (`id`),
  ADD KEY `team_id` (`team_id`);

--
-- Indexen voor tabel `tbl_poules`
--
ALTER TABLE `tbl_poules`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `naam` (`naam`);

--
-- Indexen voor tabel `tbl_teams`
--
ALTER TABLE `tbl_teams`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `tbl_admin`
--
ALTER TABLE `tbl_admin`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT voor een tabel `tbl_matches`
--
ALTER TABLE `tbl_matches`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT voor een tabel `tbl_players`
--
ALTER TABLE `tbl_players`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;
--
-- AUTO_INCREMENT voor een tabel `tbl_poules`
--
ALTER TABLE `tbl_poules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT voor een tabel `tbl_teams`
--
ALTER TABLE `tbl_teams`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `tbl_matches`
--
ALTER TABLE `tbl_matches`
  ADD CONSTRAINT `tbl_matches_ibfk_1` FOREIGN KEY (`team_id_a`) REFERENCES `tbl_teams` (`id`),
  ADD CONSTRAINT `tbl_matches_ibfk_2` FOREIGN KEY (`team_id_b`) REFERENCES `tbl_teams` (`id`);

--
-- Beperkingen voor tabel `tbl_players`
--
ALTER TABLE `tbl_players`
  ADD CONSTRAINT `tbl_players_ibfk_1` FOREIGN KEY (`team_id`) REFERENCES `tbl_teams` (`id`) ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
