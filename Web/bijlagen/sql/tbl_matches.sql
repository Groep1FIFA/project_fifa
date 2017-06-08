-- phpMyAdmin SQL Dump
-- version 4.6.5.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Gegenereerd op: 07 jun 2017 om 15:23
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
-- Tabelstructuur voor tabel `tbl_matches`
--

CREATE TABLE `tbl_matches` (
  `id` int(10) UNSIGNED NOT NULL,
  `team_id_a` int(10) UNSIGNED NOT NULL,
  `team_id_b` int(10) UNSIGNED NOT NULL,
  `poule_id` int(10) NOT NULL,
  `score_team_a` int(10) UNSIGNED DEFAULT NULL,
  `score_team_b` int(10) UNSIGNED DEFAULT NULL,
  `start_time` datetime NOT NULL,
  `started` tinyint(1) NOT NULL DEFAULT '0',
  `finished` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 ROW_FORMAT=COMPACT;

--
-- Gegevens worden geëxporteerd voor tabel `tbl_matches`
--

INSERT INTO `tbl_matches` (`id`, `team_id_a`, `team_id_b`, `poule_id`, `score_team_a`, `score_team_b`, `start_time`, `started`, `finished`) VALUES
(1, 1, 2, 1, NULL, NULL, '2017-04-13 18:00:00', 0, 0),
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
(13, 1, 2, 3, NULL, NULL, '0000-00-00 00:00:00', 0, 0),
(14, 1, 2, 4, NULL, NULL, '0000-00-00 00:00:00', 0, 0),
(15, 3, 4, 3, NULL, NULL, '2017-05-02 00:00:00', 0, 0),
(16, 3, 4, 4, NULL, NULL, '2017-05-02 00:00:00', 0, 0),
(17, 1, 4, 3, NULL, NULL, '2017-05-03 00:00:00', 0, 0),
(18, 1, 4, 4, NULL, NULL, '2017-05-02 00:00:00', 0, 0),
(19, 2, 3, 3, NULL, NULL, '2017-05-02 00:00:00', 0, 0),
(20, 2, 3, 4, NULL, NULL, '2017-05-02 00:00:00', 0, 0),
(21, 1, 3, 3, NULL, NULL, '2017-05-03 00:00:00', 0, 0),
(22, 1, 3, 4, NULL, NULL, '2017-05-02 00:00:00', 0, 0),
(23, 2, 4, 3, NULL, NULL, '2017-05-02 00:00:00', 0, 0),
(24, 2, 4, 4, NULL, NULL, '2017-05-03 00:00:00', 0, 0);

--
-- Indexen voor geëxporteerde tabellen
--

--
-- Indexen voor tabel `tbl_matches`
--
ALTER TABLE `tbl_matches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tbl_matches_ibfk_1` (`team_id_a`),
  ADD KEY `tbl_matches_ibfk_2` (`team_id_b`);

--
-- AUTO_INCREMENT voor geëxporteerde tabellen
--

--
-- AUTO_INCREMENT voor een tabel `tbl_matches`
--
ALTER TABLE `tbl_matches`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;
--
-- Beperkingen voor geëxporteerde tabellen
--

--
-- Beperkingen voor tabel `tbl_matches`
--
ALTER TABLE `tbl_matches`
  ADD CONSTRAINT `tbl_matches_ibfk_1` FOREIGN KEY (`team_id_a`) REFERENCES `tbl_teams` (`id`),
  ADD CONSTRAINT `tbl_matches_ibfk_2` FOREIGN KEY (`team_id_b`) REFERENCES `tbl_teams` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
