SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

CREATE DATABASE IF NOT EXISTS `flood_relief_db`;
USE `flood_relief_db`;

SET FOREIGN_KEY_CHECKS = 0;

CREATE TABLE `admin` (
  `Admin_ID` int(11) NOT NULL,
  `Admin_first_name` varchar(100) NOT NULL,
  `Admin_last_name` varchar(100) NOT NULL,
  `Admin_email` varchar(150) NOT NULL,
  `admin_Password_Hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `affecteduser` (
  `User_ID` int(11) NOT NULL,
  `First_name` varchar(100) NOT NULL,
  `Last_name` varchar(100) NOT NULL,
  `Contact_Number` varchar(15) DEFAULT NULL,
  `User_email` varchar(150) NOT NULL,
  `Address` text DEFAULT NULL,
  `user_Password_Hash` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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

ALTER TABLE `admin`
  ADD PRIMARY KEY (`Admin_ID`),
  ADD UNIQUE KEY `Admin_email` (`Admin_email`);

ALTER TABLE `affecteduser`
  ADD PRIMARY KEY (`User_ID`),
  ADD UNIQUE KEY `User_email` (`User_email`);

ALTER TABLE `relief`
  ADD PRIMARY KEY (`Relief_ID`),
  ADD KEY `fk_relief_user` (`User_ID`),
  ADD KEY `fk_relief_admin` (`Admin_ID`);

ALTER TABLE `admin`
  MODIFY `Admin_ID` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `affecteduser`
  MODIFY `User_ID` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `relief`
  MODIFY `Relief_ID` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `relief`
  ADD CONSTRAINT `fk_relief_admin` FOREIGN KEY (`Admin_ID`) REFERENCES `admin` (`Admin_ID`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_relief_user` FOREIGN KEY (`User_ID`) REFERENCES `affecteduser` (`User_ID`) ON DELETE CASCADE ON UPDATE CASCADE;

COMMIT;

INSERT INTO `admin` (`Admin_ID`, `Admin_first_name`, `Admin_last_name`, `Admin_email`, `admin_Password_Hash`) VALUES
(1, 'System', 'Admin', 'admin@flood.lk', '$2b$12$9VZGvJq43LTjSXtUc91WXuxKyZ/kcnSByhxbF4udHlRL5uith3kly');

SET FOREIGN_KEY_CHECKS = 1;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
