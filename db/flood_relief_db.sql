-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 14, 2026 at 06:10 AM
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
-- Database: `flood_relief_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--
CREATE DATABASE IF NOT EXISTS `flood_relief_db`;
USE `flood_relief_db`;

CREATE TABLE `admin` (
  `Admin_ID` int(11) NOT NULL,
  `Admin_first_name` varchar(100) NOT NULL,
  `Admin_last_name` varchar(100) NOT NULL,
  `Admin_email` varchar(150) NOT NULL,
  `admin_Password_Hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`Admin_ID`, `Admin_first_name`, `Admin_last_name`, `Admin_email`, `admin_Password_Hash`) VALUES
(1, 'Admin', 'One', 'admin@test.com', '$2y$10$DzkKBVGUqWNLcH2wm4aweeK9EVqY9TS8kJjNrfuREwkRQBaQUJISq');

-- --------------------------------------------------------

--
-- Table structure for table `affecteduser`
--

CREATE TABLE `affecteduser` (
  `User_ID` int(11) NOT NULL,
  `First_name` varchar(100) NOT NULL,
  `Last_name` varchar(100) NOT NULL,
  `Contact_Number` varchar(15) DEFAULT NULL,
  `User_email` varchar(150) NOT NULL,
  `Address` text DEFAULT NULL,
  `user_Password_Hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `affecteduser`
--

INSERT INTO `affecteduser` (`User_ID`, `First_name`, `Last_name`, `Contact_Number`, `User_email`, `Address`, `user_Password_Hash`) VALUES
(1, 'User', 'One', '0763670678', 'userone@gmail.com', '44,AB,CDE,LLDE', '$2y$10$AwNH5kU0YG2tksStIqxt/OLIOweQe4VyBRsyxKzv6XWyYmkCb9oxy'),
(2, 'Test', '', NULL, 'test@email.com', NULL, '$2y$10$Fua7E2qrqyOrkW2NXgzYauXTmpik75ldNarIRKKnZZmRiLlXENfzO');

-- --------------------------------------------------------

--
-- Table structure for table `relief`
--

CREATE TABLE `relief` (
  `Relief_ID` int(11) NOT NULL,
  `Type` enum('Food','Water','Medicine','Shelter') NOT NULL,
  `Flood_severity_level` enum('Low','Medium','High') NOT NULL,
  `Description` text DEFAULT NULL,
  `District` varchar(100) NOT NULL,
  `Divisional_Secretariat` varchar(150) NOT NULL,
  `GN_Division` varchar(150) NOT NULL,
  `Number_of_family_members` int(11) NOT NULL,
  `Created_date_time` datetime DEFAULT current_timestamp(),
  `Modified_date_time` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `User_ID` int(11) NOT NULL,
  `Admin_ID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `relief`
--

INSERT INTO `relief` (`Relief_ID`, `Type`, `Flood_severity_level`, `Description`, `District`, `Divisional_Secretariat`, `GN_Division`, `Number_of_family_members`, `Created_date_time`, `Modified_date_time`, `User_ID`, `Admin_ID`) VALUES
(1, 'Food', 'Low', 'Need help for test', 'Galle', 'E.A.P.S.Kokila', 'Kandegoda', 3, '2026-02-12 20:57:10', '2026-02-12 20:57:10', 1, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`Admin_ID`),
  ADD UNIQUE KEY `Admin_email` (`Admin_email`);

--
-- Indexes for table `affecteduser`
--
ALTER TABLE `affecteduser`
  ADD PRIMARY KEY (`User_ID`),
  ADD UNIQUE KEY `User_email` (`User_email`);

--
-- Indexes for table `relief`
--
ALTER TABLE `relief`
  ADD PRIMARY KEY (`Relief_ID`),
  ADD KEY `fk_relief_user` (`User_ID`),
  ADD KEY `fk_relief_admin` (`Admin_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `Admin_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `affecteduser`
--
ALTER TABLE `affecteduser`
  MODIFY `User_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `relief`
--
ALTER TABLE `relief`
  MODIFY `Relief_ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `relief`
--
ALTER TABLE `relief`
  ADD CONSTRAINT `fk_relief_admin` FOREIGN KEY (`Admin_ID`) REFERENCES `admin` (`Admin_ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_relief_user` FOREIGN KEY (`User_ID`) REFERENCES `affecteduser` (`User_ID`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
