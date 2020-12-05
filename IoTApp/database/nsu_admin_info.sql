-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 14, 2020 at 04:36 PM
-- Server version: 5.7.19
-- PHP Version: 5.6.31

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `nsu_iot_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `nsu_admin_info`
--

DROP TABLE IF EXISTS `nsu_admin_info`;
CREATE TABLE IF NOT EXISTS `nsu_admin_info` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT 'tile of variavle',
  `value` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT 'value of title',
  `status` enum('0','1','2') DEFAULT '0' COMMENT 'status 0(not active), status 1(active), status 2 (require second step)',
  `created` int(11) DEFAULT NULL COMMENT 'date and time created',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `nsu_admin_info`
--

INSERT INTO `nsu_admin_info` (`id`, `title`, `value`, `status`, `created`) VALUES
(1, 'api_key', 'mysecretkeyisjohn', '1', 1581460031);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
