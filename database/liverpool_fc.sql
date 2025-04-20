-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 20, 2025 at 05:21 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `liverpool_fc`
--

-- --------------------------------------------------------

--
-- Table structure for table `matches`
--

CREATE TABLE `matches` (
  `id_match` int(11) NOT NULL,
  `date` date DEFAULT NULL,
  `team_a` varchar(50) DEFAULT NULL,
  `team_b` varchar(50) DEFAULT NULL,
  `score_a` int(11) DEFAULT NULL,
  `score_b` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `matches`
--

INSERT INTO `matches` (`id_match`, `date`, `team_a`, `team_b`, `score_a`, `score_b`) VALUES
(1, '2025-04-22', 'Liverpool', 'Manchester United', 3, 1),
(2, '2025-04-20', 'Liverpool', 'Manchester City', 4, 0),
(3, '2025-04-13', 'Liverpool', 'Everton', 2, 0),
(4, '2025-04-26', 'Liverpool', 'Wolves', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `players`
--

CREATE TABLE `players` (
  `id_player` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `position` varchar(50) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `nationality` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `players`
--

INSERT INTO `players` (`id_player`, `name`, `position`, `dob`, `nationality`) VALUES
(1, 'Mohamed Salah', 'RWF', '1992-06-15', 'Egypt'),
(3, ' Virgil van Dijk', 'Centre Back', '1991-07-08', 'Dutch'),
(5, 'Alexis Mac Allister', 'Midfielder', '1998-12-24', 'Argentina');

-- --------------------------------------------------------

--
-- Table structure for table `player_match`
--

CREATE TABLE `player_match` (
  `id_player_match` int(11) NOT NULL,
  `id_player` int(11) DEFAULT NULL,
  `id_match` int(11) DEFAULT NULL,
  `goals` int(11) DEFAULT 0,
  `assists` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `player_match`
--

INSERT INTO `player_match` (`id_player_match`, `id_player`, `id_match`, `goals`, `assists`) VALUES
(1, 1, 1, 3, 1),
(3, 1, 2, 4, 1),
(4, 1, 3, 2, 0),
(5, 3, 2, 0, 1),
(6, 5, 3, 1, 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `matches`
--
ALTER TABLE `matches`
  ADD PRIMARY KEY (`id_match`);

--
-- Indexes for table `players`
--
ALTER TABLE `players`
  ADD PRIMARY KEY (`id_player`);

--
-- Indexes for table `player_match`
--
ALTER TABLE `player_match`
  ADD PRIMARY KEY (`id_player_match`),
  ADD KEY `id_player` (`id_player`),
  ADD KEY `id_match` (`id_match`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `matches`
--
ALTER TABLE `matches`
  MODIFY `id_match` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `players`
--
ALTER TABLE `players`
  MODIFY `id_player` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `player_match`
--
ALTER TABLE `player_match`
  MODIFY `id_player_match` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `player_match`
--
ALTER TABLE `player_match`
  ADD CONSTRAINT `player_match_ibfk_1` FOREIGN KEY (`id_player`) REFERENCES `players` (`id_player`),
  ADD CONSTRAINT `player_match_ibfk_2` FOREIGN KEY (`id_match`) REFERENCES `matches` (`id_match`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
