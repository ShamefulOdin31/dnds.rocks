-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 11, 2020 at 02:56 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dndproject`
--

-- --------------------------------------------------------

--
-- Table structure for table `dndcharacters`
--

CREATE TABLE `dndcharacters` (
  `characterID` int(11) NOT NULL,
  `cname` varchar(255) NOT NULL,
  `race` varchar(255) NOT NULL,
  `class` varchar(255) NOT NULL,
  `background` varchar(255) NOT NULL,
  `notes` varchar(255) NOT NULL,
  `userOwner` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `dndcharacters`
--

INSERT INTO `dndcharacters` (`characterID`, `cname`, `race`, `class`, `background`, `notes`, `userOwner`) VALUES
(9, 'asdf', 'Dwarf', 'Cleric', 'sadf', 'sadf', 4),
(10, 'asdfasdf', 'Dwarf', 'Barbarian', 'asdf', 'sadf', 4);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dndcharacters`
--
ALTER TABLE `dndcharacters`
  ADD PRIMARY KEY (`characterID`),
  ADD KEY `owner_fk` (`userOwner`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dndcharacters`
--
ALTER TABLE `dndcharacters`
  MODIFY `characterID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dndcharacters`
--
ALTER TABLE `dndcharacters`
  ADD CONSTRAINT `owner_fk` FOREIGN KEY (`userOwner`) REFERENCES `logins` (`loginID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
