-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 26, 2020 at 11:37 PM
-- Server version: 5.7.19
-- PHP Version: 7.1.9

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
-- Table structure for table `nsu_added_category`
--

DROP TABLE IF EXISTS `nsu_added_category`;
CREATE TABLE IF NOT EXISTS `nsu_added_category` (
  `cid` int(11) NOT NULL AUTO_INCREMENT,
  `uid` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT 'user id',
  `nickname` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT 'anything user generated in UI',
  `address` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT 'address of device',
  `city` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT 'city of device',
  `state` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT 'state of device',
  `country` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT 'country of device',
  `zip_code` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT 'zipcode',
  `ticket` varchar(255) DEFAULT NULL COMMENT 'communication ticket within groups',
  `created` int(11) DEFAULT NULL COMMENT 'creation date',
  `updated` int(11) DEFAULT NULL COMMENT 'last update',
  PRIMARY KEY (`cid`)
) ENGINE=MyISAM AUTO_INCREMENT=112 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `nsu_added_category`
--


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
