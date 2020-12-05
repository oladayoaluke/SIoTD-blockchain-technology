-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 27, 2020 at 03:32 AM
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
-- Table structure for table `nsu_adapter_programming`
--

DROP TABLE IF EXISTS `nsu_adapter_programming`;
CREATE TABLE IF NOT EXISTS `nsu_adapter_programming` (
  `prog_id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT 'user id',
  `eid` enum('','1','2','3','4') DEFAULT '' COMMENT 'device table id',
  `adid` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT 'unique key from added_device table',
  `power_on` enum('1','0') DEFAULT '0' COMMENT 'on or off/1 or 0',
  `power_on_delay` int(11) DEFAULT NULL COMMENT 'delay in seconds',
  `start_on` enum('1','0') DEFAULT '0' COMMENT '1 or 0',
  `start_on_delay` int(11) DEFAULT NULL COMMENT 'delay in seconds',
  `choke_level_on` enum('10','9','8','7','6','5','4','3','2','1','0') DEFAULT '0' COMMENT 'Calibration settings : 0 is off, 1 is 1 mins, 2 is 2mins, 3 is 3 mins, 4 is off',
  `choke_on_delay` int(11) DEFAULT NULL COMMENT 'delay in seconds',
  `choke_led` enum('4','3','2','1','0') DEFAULT '0' COMMENT 'Calibration settings : 0 is off, 1 is 1 mins, 2 is 2mins, 3 is 3 mins, 4 is off',
  `fuel_gate_on` enum('1','0') DEFAULT '0' COMMENT '1 or 0',
  `fuel_gate_on_delay` int(11) DEFAULT NULL COMMENT '1 or 0',
  `gas_guage_led` enum('4','3','2','1','0') DEFAULT '0' COMMENT 'Calibration settings : 0 is off, 1 is 1 mins, 2 is 2mins, 3 is 3 mins, 4 is off',
  `oil_guage_led` enum('4','3','2','1','0') DEFAULT '0' COMMENT 'Calibration settings : 0 is off, 1 is 1 mins, 2 is 2mins, 3 is 3 mins, 4 is off',
  `on_finalization` enum('1','0') DEFAULT '0' COMMENT '1 or 0',
  `power_off` enum('1','0') DEFAULT '0' COMMENT 'on or off/1 or 0',
  `power_off_delay` int(11) DEFAULT NULL COMMENT 'delay in seconds',
  `start_off` enum('1','0') DEFAULT '0' COMMENT '1 or 0',
  `start_off_delay` int(11) DEFAULT NULL COMMENT 'delay in seconds',
  `choke_level_off` enum('10','9','8','7','6','5','4','3','2','1','0') DEFAULT '0' COMMENT 'Calibration settings : 0 is off, 1 is 1 mins, 2 is 2mins, 3 is 3 mins, 4 is off',
  `choke_off_delay` int(11) DEFAULT NULL COMMENT 'delay in seconds',
  `fuel_gate_off` enum('1','0') DEFAULT '0' COMMENT '1 or 0',
  `fuel_gate_off_delay` int(11) DEFAULT NULL COMMENT '1 or 0',
  `off_finalization` enum('1','0') DEFAULT '0' COMMENT 'certify that programming is complete',
  `created` datetime DEFAULT NULL COMMENT 'creation date',
  `updated` datetime DEFAULT NULL COMMENT 'last update',
  PRIMARY KEY (`prog_id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `nsu_adapter_programming`
--

INSERT INTO `nsu_adapter_programming` (`prog_id`, `uid`, `eid`, `adid`, `power_on`, `power_on_delay`, `start_on`, `start_on_delay`, `choke_level_on`, `choke_on_delay`, `choke_led`, `fuel_gate_on`, `fuel_gate_on_delay`, `gas_guage_led`, `oil_guage_led`, `on_finalization`, `power_off`, `power_off_delay`, `start_off`, `start_off_delay`, `choke_level_off`, `choke_off_delay`, `fuel_gate_off`, `fuel_gate_off_delay`, `off_finalization`, `created`, `updated`) VALUES
(16, '1', '1', '19', '1', 15, '1', NULL, '10', NULL, '4', '1', NULL, '4', '4', '1', '1', NULL, '1', NULL, '0', NULL, '1', NULL, '1', '2020-02-03 16:41:54', '2020-02-04 01:18:50'),
(6, '1', '1', '10', '1', 250030, '1', 250030, '8', 250030, '4', '1', 250030, '3', '2', '1', '0', NULL, '0', 250030, '0', NULL, '0', NULL, '1', '2020-01-30 23:32:00', '2020-02-02 20:59:10'),
(17, '5', '1', '15', '1', 4, '1', NULL, '6', NULL, '4', '1', NULL, '4', '2', '1', '1', NULL, '1', NULL, '0', NULL, '1', NULL, '1', '2020-02-10 16:15:53', '2020-02-10 16:16:44'),
(18, '5', '1', '29', '1', 1, '1', NULL, '8', NULL, '3', '1', NULL, '3', '3', '1', '1', NULL, '1', NULL, '0', NULL, '1', NULL, '1', '2020-02-11 22:50:45', '2020-02-11 22:50:55');

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

-- --------------------------------------------------------

--
-- Table structure for table `nsu_added_device`
--

DROP TABLE IF EXISTS `nsu_added_device`;
CREATE TABLE IF NOT EXISTS `nsu_added_device` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT 'user id',
  `dtid` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT 'device table id(1 to 7)',
  `gid` varchar(255) NOT NULL COMMENT 'group id from added_group',
  `nickname` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `prog_code` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT 'for authentication',
  `ticket` varchar(255) DEFAULT NULL COMMENT 'communication ticket within groups',
  `private_key` varchar(255) DEFAULT NULL COMMENT 'encryption key',
  `public_key` varchar(255) DEFAULT NULL COMMENT 'encryption key',
  `passphrase` varchar(255) DEFAULT NULL,
  `blockchain_acct` varchar(255) DEFAULT NULL,
  `created` date DEFAULT NULL COMMENT 'creation date',
  `updated` int(11) DEFAULT NULL COMMENT 'last update',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=34 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `nsu_added_device`
--

INSERT INTO `nsu_added_device` (`id`, `uid`, `dtid`, `gid`, `nickname`, `prog_code`, `ticket`, `private_key`, `public_key`, `passphrase`, `blockchain_acct`, `created`, `updated`) VALUES
(26, '5', '5', '110', 'SecureTransfer', '121023', 'ff7d19a97d3741a7861abdbb21f21869368ff0ebb446a225d9482e4b7b8ef9bdc2db0571ac4cc3a10e5b5d801909a4d281154edc3c35100c77c2ab48403246a55bbc83a8c7a7627833de3ab702ad5685b5751b70cd2475592fe4ca78145ad785e0e8a717b3d334b6f5181cea06ff1058a6f554ea93eeda1e390279bbc4634a2', '-----BEGIN PRIVATE KEY-----\nMIIJQwIBADANBgkqhkiG9w0BAQEFAASCCS0wggkpAgEAAoICAQC8VupsOnkr0bc0\n9NWw6hwpEw/OyihiIAo3hkRSq3u+kZFMsA/9SS5b0GqkaKgUwI2FugpWDLBvjjOi\nhLeqOa6i0vy4aOWH+lTA2rWo+RHTDQ3bA1fOHviwfn7XSuT84hbG/qYjF1Weoz1A\nuz4u/oM8fSgM69KOXHttbNvCVsHvlo4P', '-----BEGIN PUBLIC KEY-----\nMIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEAvFbqbDp5K9G3NPTVsOoc\nKRMPzsooYiAKN4ZEUqt7vpGRTLAP/UkuW9BqpGioFMCNhboKVgywb44zooS3qjmu\notL8uGjlh/pUwNq1qPkR0w0N2wNXzh74sH5+10rk/OIWxv6mIxdVnqM9QLs+Lv6D\nPH0oDOvSjlx7bWzbwlbB75aOD54+s6tjC', 'AP/UkuW9BqpGioFMCNhboKVgywb44zooS3qjmu\r\notL8uGjlh/pUwNq1qPkR0w0N', '0xA43C2dD3d8335173AFD9c88f4Ae7Aa272Cc137F6', '2020-02-11', 1581460031),
(25, '5', '7', '110', 'SecureWifi', '121023', 'ff7d19a97d3741a7861abdbb21f21869368ff0ebb446a225d9482e4b7b8ef9bdc2db0571ac4cc3a10e5b5d801909a4d281154edc3c35100c77c2ab48403246a55bbc83a8c7a7627833de3ab702ad5685b5751b70cd2475592fe4ca78145ad785e0e8a717b3d334b6f5181cea06ff1058a6f554ea93eeda1e390279bbc4634a2', '-----BEGIN PRIVATE KEY-----\nMIIJRAIBADANBgkqhkiG9w0BAQEFAASCCS4wggkqAgEAAoICAQCg/lZ6bywUceWX\ndpuRISbI9DyjRoVvIeXXhOp8SahXWsrOmeWKu/lX+I0njE2c1Wa5V5pdCad7Y4HN\nPRna2zORgsdS3IIqIlSH/jq5lFloZIVjaaFFF1pZIyF45JCdDdSSv9wa3auZBgcR\nCgs/3wu7TgIN/oDi924xqcoA2Fkh3453', '-----BEGIN PUBLIC KEY-----\nMIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEAoP5Wem8sFHHll3abkSEm\nyPQ8o0aFbyHl14TqfEmoV1rKzpnlirv5V/iNJ4xNnNVmuVeaXQmne2OBzT0Z2tsz\nkYLHUtyCKiJUh/46uZRZaGSFY2mhRRdaWSMheOSQnQ3Ukr/cGt2rmQYHEQoLP98L\nu04CDf6A4vduManKANhZId+Od91+sckU/', '0a81548173fffffffffffffffffffffff', '0x6bC5E9D47D175ffF361d05E21f8596F85Cedf1BB', '2020-02-11', 1581459570),
(24, '5', '1', '110', 'SecureGen', '113515', 'ff7d19a97d3741a7861abdbb21f21869368ff0ebb446a225d9482e4b7b8ef9bdc2db0571ac4cc3a10e5b5d801909a4d281154edc3c35100c77c2ab48403246a55bbc83a8c7a7627833de3ab702ad5685b5751b70cd2475592fe4ca78145ad785e0e8a717b3d334b6f5181cea06ff1058a6f554ea93eeda1e390279bbc4634a2', '-----BEGIN PRIVATE KEY-----\nMIIJQgIBADANBgkqhkiG9w0BAQEFAASCCSwwggkoAgEAAoICAQDZblPZzflXhm2g\nes/5ul5ZPwpkI06lcRcHQiR9VGXpVj8sKEHRqCUXwG3ByAYL3f+vudMjnU1T0oZj\nEJweeDEN8Ae+GBJzwA1InWRKpf8gMKYnxO0ICuW59LTmSXzaN55IFMgiJIxZ0WVc\nhDGf9SGUKxKNZqXuTY5spJ0Qaj1RuhIC', '-----BEGIN PUBLIC KEY-----\nMIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEA2W5T2c35V4ZtoHrP+bpe\nWT8KZCNOpXEXB0IkfVRl6VY/LChB0aglF8BtwcgGC93/r7nTI51NU9KGYxCcHngx\nDfAHvhgSc8ANSJ1kSqX/IDCmJ8TtCArlufS05kl82jeeSBTIIiSMWdFlXIQxn/Uh\nlCsSjWal7k2ObKSdEGo9UboSAmsIBuzSW', NULL, NULL, '2020-02-11', 1581458774),
(23, '5', '8', '110', 'SecureRobot', '113515', 'ff7d19a97d3741a7861abdbb21f21869368ff0ebb446a225d9482e4b7b8ef9bdc2db0571ac4cc3a10e5b5d801909a4d281154edc3c35100c77c2ab48403246a55bbc83a8c7a7627833de3ab702ad5685b5751b70cd2475592fe4ca78145ad785e0e8a717b3d334b6f5181cea06ff1058a6f554ea93eeda1e390279bbc4634a2', '-----BEGIN PRIVATE KEY-----\nMIIJRAIBADANBgkqhkiG9w0BAQEFAASCCS4wggkqAgEAAoICAQDWufqrrUFhmm7q\nyvkZx1s/pyXd5aFmXOmgigl+qIF9/IRQKkWwx9i1o0uiLJorsCLeDRXZjG66gX2h\nM5vRGyETxfOmx/7FthuMxKLA3G96LZTbz9JIl21X0x+vDA2HhADjoiUFm/lgf0MU\nozWw/FA+omlpyT4TJIAF63XH70/k2/kP', '-----BEGIN PUBLIC KEY-----\nMIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEA1rn6q61BYZpu6sr5Gcdb\nP6cl3eWhZlzpoIoJfqiBffyEUCpFsMfYtaNLoiyaK7Ai3g0V2YxuuoF9oTOb0Rsh\nE8Xzpsf+xbYbjMSiwNxvei2U28/SSJdtV9MfrwwNh4QA46IlBZv5YH9DFKM1sPxQ\nPqJpack+EySABet1x+9P5Nv5Dynr5OgMu', NULL, NULL, '2020-02-11', 1581458741),
(28, '5', '6', '110', 'securePhase', '103582', 'ff7d19a97d3741a7861abdbb21f21869368ff0ebb446a225d9482e4b7b8ef9bdc2db0571ac4cc3a10e5b5d801909a4d281154edc3c35100c77c2ab48403246a55bbc83a8c7a7627833de3ab702ad5685b5751b70cd2475592fe4ca78145ad785e0e8a717b3d334b6f5181cea06ff1058a6f554ea93eeda1e390279bbc4634a2', '-----BEGIN PRIVATE KEY-----\nMIIJQgIBADANBgkqhkiG9w0BAQEFAASCCSwwggkoAgEAAoICAQDXfwaQ2kF82O1M\n51H+xqQ40zpvSD53v26nobGPzulwoTmNl5+rkOJOmYJkTDlry9q1viEkjSe6jKw8\n+QJmZ4uOyfDRMIQw2i2veXQXzI+7GvYqNV0lFNuUL3kMcDFUvzeL6u8f4/0ZCq5O\nBC3/XdHhg4AkBGzTVZJSgGo9dDCZqOH/', '-----BEGIN PUBLIC KEY-----\nMIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEA138GkNpBfNjtTOdR/sak\nONM6b0g+d79up6Gxj87pcKE5jZefq5DiTpmCZEw5a8vatb4hJI0nuoysPPkCZmeL\njsnw0TCEMNotr3l0F8yPuxr2KjVdJRTblC95DHAxVL83i+rvH+P9GQquTgQt/13R\n4YOAJARs01WSUoBqPXQwmajh/ytQQI8sn', NULL, NULL, '2020-02-11', 1581461249),
(29, '5', '1', '111', 'fgen', '109241', 'a53d8717f409b9f5c2c078cec8814be8de003e1ef254ef8f9d9bf1228b09d15c9846ba751e1bf3b3ce43f4e6099d60635851cdf50c32741ff3a5a74d8eaa764be6958bc81a78c1f17952a4db9e37bda5165696c97414a740e068773c3aa0b02c6d38aa229f564ba342c2b30e0ac5415f13df10cf4f2bded8a60f67403ecb9e0', '-----BEGIN PRIVATE KEY-----\nMIIJQgIBADANBgkqhkiG9w0BAQEFAASCCSwwggkoAgEAAoICAQDRhagLYqVnEijK\nzyZ2d65uRZk8UIm0ExrBjKdIY657lbLuvuAGoK358GRbPj5wt4sUOAs/9twqtJrQ\nT/HIbSHqoah7yHpFHNXHrhYEQafDoD+yd+gLLoYJzogU1MRXq0Tq8+Ce+K6dNILC\nJCW9JhngyWt2AiKAs81QNiKlJ7sSA4rq', '-----BEGIN PUBLIC KEY-----\nMIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEA0YWoC2KlZxIoys8mdneu\nbkWZPFCJtBMawYynSGOue5Wy7r7gBqCt+fBkWz4+cLeLFDgLP/bcKrSa0E/xyG0h\n6qGoe8h6RRzVx64WBEGnw6A/snfoCy6GCc6IFNTEV6tE6vPgnviunTSCwiQlvSYZ\n4MlrdgIigLPNUDYipSe7EgOK6p4SVW3GI', NULL, NULL, '2020-02-11', 1581461362),
(30, '5', '5', '111', 'ftrans', '109241', 'a53d8717f409b9f5c2c078cec8814be8de003e1ef254ef8f9d9bf1228b09d15c9846ba751e1bf3b3ce43f4e6099d60635851cdf50c32741ff3a5a74d8eaa764be6958bc81a78c1f17952a4db9e37bda5165696c97414a740e068773c3aa0b02c6d38aa229f564ba342c2b30e0ac5415f13df10cf4f2bded8a60f67403ecb9e0', '-----BEGIN PRIVATE KEY-----\nMIIJQQIBADANBgkqhkiG9w0BAQEFAASCCSswggknAgEAAoICAQCyVvLpe69JB3mW\nOXQCXRaZEk9h4BASgrU+BgquqMtlMYLQlVWtt5lZVjxd0sinkGTEC3HOUBpsDv0c\ndygUh3AYZ8YkBb5eAtAVzF5BqXB+ALf4Y+Tc1ieIEF+N57ZQBlpqJLG+wVCghgXz\nq2Aias8bsDhKncp2gh3j3RWDV67qr5Sq', '-----BEGIN PUBLIC KEY-----\nMIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEAslby6XuvSQd5ljl0Al0W\nmRJPYeAQEoK1PgYKrqjLZTGC0JVVrbeZWVY8XdLIp5BkxAtxzlAabA79HHcoFIdw\nGGfGJAW+XgLQFcxeQalwfgC3+GPk3NYniBBfjee2UAZaaiSxvsFQoIYF86tgImrP\nG7A4Sp3KdoId490Vg1eu6q+Uqtun2J4SD', NULL, NULL, '2020-02-11', 1581461379),
(31, '5', '6', '111', 'ftrans', '109241', 'a53d8717f409b9f5c2c078cec8814be8de003e1ef254ef8f9d9bf1228b09d15c9846ba751e1bf3b3ce43f4e6099d60635851cdf50c32741ff3a5a74d8eaa764be6958bc81a78c1f17952a4db9e37bda5165696c97414a740e068773c3aa0b02c6d38aa229f564ba342c2b30e0ac5415f13df10cf4f2bded8a60f67403ecb9e0', '-----BEGIN PRIVATE KEY-----\nMIIJQQIBADANBgkqhkiG9w0BAQEFAASCCSswggknAgEAAoICAQCYdKxBWBOvSi2U\ncVfmx33G3pgreIkg4zPY5IEkPxY4K14NIQ/1jCPQvA7H3B4S8/7s1gnP+cDhxjMR\nIh/81/gS+dCsd4ZPaFWxoFTVBDqOlzxB0U5nuaC2Sd1yoonzoSN2Qr9FRWG1wzNk\nhuANrgelPZpEdlV5xWGgvL2cybSTWPwW', '-----BEGIN PUBLIC KEY-----\nMIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEAmHSsQVgTr0otlHFX5sd9\nxt6YK3iJIOMz2OSBJD8WOCteDSEP9Ywj0LwOx9weEvP+7NYJz/nA4cYzESIf/Nf4\nEvnQrHeGT2hVsaBU1QQ6jpc8QdFOZ7mgtkndcqKJ86EjdkK/RUVhtcMzZIbgDa4H\npT2aRHZVecVhoLy9nMm0k1j8FrGSV8/r7', NULL, NULL, '2020-02-11', 1581461386),
(32, '5', '7', '111', 'ftranswifi', '109241', 'a53d8717f409b9f5c2c078cec8814be8de003e1ef254ef8f9d9bf1228b09d15c9846ba751e1bf3b3ce43f4e6099d60635851cdf50c32741ff3a5a74d8eaa764be6958bc81a78c1f17952a4db9e37bda5165696c97414a740e068773c3aa0b02c6d38aa229f564ba342c2b30e0ac5415f13df10cf4f2bded8a60f67403ecb9e0', '-----BEGIN PRIVATE KEY-----\nMIIJRAIBADANBgkqhkiG9w0BAQEFAASCCS4wggkqAgEAAoICAQDdDppK2rE037Ed\n6SXV3XyEx6yownIPHQ5cmrWKOPuS2muM96lza5w1FHbWsjbOY6NLinRrIsgYjuxc\nk/4ZQtAabc9/x1P10Z5h4PuQzvHlf2R+I4Ph3w2tm86+mkn8nLRlBQQLef1PMyhn\ntfXvRUEVtOzdzjRi1lXTdmtMFzbW46Na', '-----BEGIN PUBLIC KEY-----\nMIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEA3Q6aStqxNN+xHekl1d18\nhMesqMJyDx0OXJq1ijj7ktprjPepc2ucNRR21rI2zmOjS4p0ayLIGI7sXJP+GULQ\nGm3Pf8dT9dGeYeD7kM7x5X9kfiOD4d8NrZvOvppJ/Jy0ZQUEC3n9TzMoZ7X170VB\nFbTs3c40YtZV03ZrTBc21uOjWhRdt2hLt', NULL, NULL, '2020-02-11', 1581461399),
(33, '5', '8', '111', 'ftranswifi', '109241', 'a53d8717f409b9f5c2c078cec8814be8de003e1ef254ef8f9d9bf1228b09d15c9846ba751e1bf3b3ce43f4e6099d60635851cdf50c32741ff3a5a74d8eaa764be6958bc81a78c1f17952a4db9e37bda5165696c97414a740e068773c3aa0b02c6d38aa229f564ba342c2b30e0ac5415f13df10cf4f2bded8a60f67403ecb9e0', '-----BEGIN PRIVATE KEY-----\nMIIJQwIBADANBgkqhkiG9w0BAQEFAASCCS0wggkpAgEAAoICAQDQJqNBykmvUkYm\nASdmccCtx8u5yC50oDFbazIIL9e10wHKQoOnsbPBgE1/IKifauQEveFQXAo6PuM+\nGH98a8R+Eer0ar7Q76xsGUcRUuDz/2bvMCal8jHM3xtmiflIxcRVRmMsG+hgLjEa\np/KGISgYdlRQquF6i0pXbenF3P9qihKf', '-----BEGIN PUBLIC KEY-----\nMIICIjANBgkqhkiG9w0BAQEFAAOCAg8AMIICCgKCAgEA0CajQcpJr1JGJgEnZnHA\nrcfLucgudKAxW2syCC/XtdMBykKDp7GzwYBNfyCon2rkBL3hUFwKOj7jPhh/fGvE\nfhHq9Gq+0O+sbBlHEVLg8/9m7zAmpfIxzN8bZon5SMXEVUZjLBvoYC4xGqfyhiEo\nGHZUUKrheotKV23pxdz/aooSn6q50298+', NULL, NULL, '2020-02-11', 1581461411);

-- --------------------------------------------------------

--
-- Table structure for table `nsu_added_group`
--

DROP TABLE IF EXISTS `nsu_added_group`;
CREATE TABLE IF NOT EXISTS `nsu_added_group` (
  `gid` int(11) NOT NULL AUTO_INCREMENT,
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
  PRIMARY KEY (`gid`)
) ENGINE=MyISAM AUTO_INCREMENT=112 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `nsu_added_group`
--

INSERT INTO `nsu_added_group` (`gid`, `uid`, `nickname`, `address`, `city`, `state`, `country`, `zip_code`, `ticket`, `created`, `updated`) VALUES
(110, '5', 'SecurityGroupPrivpubKeys', '7912 Jasons Landing Way', 'Severn', 'MD', 'USA', '21144', 'ff7d19a97d3741a7861abdbb21f21869368ff0ebb446a225d9482e4b7b8ef9bdc2db0571ac4cc3a10e5b5d801909a4d281154edc3c35100c77c2ab48403246a55bbc83a8c7a7627833de3ab702ad5685b5751b70cd2475592fe4ca78145ad785e0e8a717b3d334b6f5181cea06ff1058a6f554ea93eeda1e390279bbc4634a2', 1581456026, NULL),
(111, '5', 'FinalSecurityTest', '555 Geo Drive', 'Philipsburg', 'PA', 'United States', '16866', 'a53d8717f409b9f5c2c078cec8814be8de003e1ef254ef8f9d9bf1228b09d15c9846ba751e1bf3b3ce43f4e6099d60635851cdf50c32741ff3a5a74d8eaa764be6958bc81a78c1f17952a4db9e37bda5165696c97414a740e068773c3aa0b02c6d38aa229f564ba342c2b30e0ac5415f13df10cf4f2bded8a60f67403ecb9e0', 1581461288, NULL);

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
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `nsu_admin_info`
--

INSERT INTO `nsu_admin_info` (`id`, `title`, `value`, `status`, `created`) VALUES
(1, 'api_key', 'mysecretkeyisjohn', '1', 1581460031),
(2, 'ethereum_owner_account', '0x5d89f6EC46fcF4436c4173ce5e4df66d258751c0', '1', NULL),
(3, 'ethereum_host', '127.0.0.1', '1', NULL),
(4, 'socket_token', 'a460201b60201c565b6200039b565b60', '1', NULL),
(5, 'device_port', '65430', '1', NULL),
(6, 'contract_address', '0xca9545ada0b924c57da8ec75cdbf4c855f92f1a2', '1', NULL),
(7, 'ethereum_port', '7545', '1', NULL),
(8, 'network_id', '5557', '1', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `nsu_control`
--

DROP TABLE IF EXISTS `nsu_control`;
CREATE TABLE IF NOT EXISTS `nsu_control` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT 'user id',
  `gid` int(11) DEFAULT NULL COMMENT 'group id(each group needs new gid)',
  `start_status` enum('0','1') DEFAULT '0' COMMENT 'equipment adapter on and off',
  `t_switch_status` enum('0','1') DEFAULT '0' COMMENT 'transfer switch on and off',
  `p_switch_status` enum('0','1') DEFAULT '0' COMMENT 'phase on and off',
  `t_switch_value` enum('0','1','2') DEFAULT '0' COMMENT 'equipment adapter on and off',
  `auto_tswitch` enum('0','1') DEFAULT '0' COMMENT 'auto transfer on and off',
  `p_switch_value` enum('0','1','2') DEFAULT '0' COMMENT 'equipment adapter on and off',
  `auto_pswitch` enum('0','1') DEFAULT '0' COMMENT 'auto transfer on and off',
  `auto_navigation` enum('0','1') DEFAULT '0' COMMENT 'toggled to 0 when manual is on.',
  `max_speed` int(11) NOT NULL DEFAULT '0' COMMENT 'robot max speed ',
  `a_battery_status` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'enable battery recharge ',
  `a_battery_level` int(11) NOT NULL DEFAULT '10' COMMENT 'battery recharge max level',
  `r_battery_status` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'enable battery recharge',
  `r_battery_level` int(11) NOT NULL DEFAULT '10' COMMENT 'battery recharge max level',
  `t_battery_status` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'enable battery recharge',
  `t_battery_level` int(11) DEFAULT '10' COMMENT 'battery recharge max level',
  `p_battery_status` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'enable battery recharge',
  `p_battery_level` int(11) DEFAULT '10' COMMENT 'battery recharge max level',
  `w_battery_status` enum('0','1') NOT NULL DEFAULT '0' COMMENT 'enable battery recharge ',
  `w_battery_level` int(11) NOT NULL DEFAULT '10',
  `updated` int(11) DEFAULT NULL COMMENT 'last time updated',
  `created` int(11) DEFAULT NULL COMMENT 'date and time created',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `nsu_control`
--

INSERT INTO `nsu_control` (`id`, `uid`, `gid`, `start_status`, `t_switch_status`, `p_switch_status`, `t_switch_value`, `auto_tswitch`, `p_switch_value`, `auto_pswitch`, `auto_navigation`, `max_speed`, `a_battery_status`, `a_battery_level`, `r_battery_status`, `r_battery_level`, `t_battery_status`, `t_battery_level`, `p_battery_status`, `p_battery_level`, `w_battery_status`, `w_battery_level`, `updated`, `created`) VALUES
(2, '1', 103, '1', '0', '0', '0', '0', '1', '0', '0', 2, '0', 50, '0', 70, '0', 90, '0', 90, '0', 40, 1581301296, NULL),
(3, '5', 105, '0', '0', '0', '0', '0', '1', '0', '0', 0, '0', 10, '0', 10, '0', 10, '0', 10, '0', 10, 1582748165, 1581284469),
(5, '5', 108, '0', '0', '0', '0', '0', '1', '0', '0', 0, '0', 10, '0', 10, '0', 10, '0', 10, '0', 10, 1582748165, 1581354599),
(6, '5', 106, '0', '0', '0', '0', '0', '1', '0', '0', 0, '0', 10, '0', 10, '0', 10, '0', 10, '0', 10, 1582748165, 1581354599),
(7, '5', 109, '0', '0', '0', '0', '0', '0', '0', '0', 0, '0', 10, '0', 10, '0', 10, '0', 10, '0', 10, 1582748165, 1581455819),
(8, '5', 110, '0', '0', '0', '0', '0', '0', '0', '0', 0, '0', 10, '0', 10, '0', 10, '0', 10, '0', 10, 1582748165, 1581456026),
(9, '5', 111, '0', '0', '0', '0', '0', '0', '0', '0', 0, '0', 10, '0', 10, '0', 10, '0', 10, '0', 10, 1582748165, 1581461288);

-- --------------------------------------------------------

--
-- Table structure for table `nsu_device`
--

DROP TABLE IF EXISTS `nsu_device`;
CREATE TABLE IF NOT EXISTS `nsu_device` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `nsu_device`
--

INSERT INTO `nsu_device` (`id`, `name`) VALUES
(1, 'Generator Adapter'),
(2, 'Lawn Mower Adapter'),
(3, 'Snow Removal Adapter'),
(4, 'Water Pump Adapter'),
(5, 'Transfer Switch'),
(6, 'Phase Switch'),
(7, 'WiFi Adapter'),
(8, 'Robot');

-- --------------------------------------------------------

--
-- Table structure for table `nsu_device_menu`
--

DROP TABLE IF EXISTS `nsu_device_menu`;
CREATE TABLE IF NOT EXISTS `nsu_device_menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dtid` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT 'device type id(1 to 8)',
  `name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `nsu_device_menu`
--

INSERT INTO `nsu_device_menu` (`id`, `dtid`, `name`) VALUES
(1, '1', 'Generator'),
(2, '2', 'Lawn Mower'),
(3, '3', 'Snow Removal'),
(4, '4', 'Water Pump'),
(5, '5', 'Transfer Switch'),
(6, '6', 'Phase Switch'),
(7, '7', 'WiFi Adapter'),
(8, '8', 'Robot');

-- --------------------------------------------------------

--
-- Table structure for table `nsu_device_type`
--

DROP TABLE IF EXISTS `nsu_device_type`;
CREATE TABLE IF NOT EXISTS `nsu_device_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `nsu_device_type`
--

INSERT INTO `nsu_device_type` (`id`, `name`) VALUES
(1, 'Equipment Adapter'),
(2, 'Transfer Switch'),
(3, 'Phase Switch'),
(4, 'WiFi Adapter');

-- --------------------------------------------------------

--
-- Table structure for table `nsu_equipment_type`
--

DROP TABLE IF EXISTS `nsu_equipment_type`;
CREATE TABLE IF NOT EXISTS `nsu_equipment_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `nsu_equipment_type`
--

INSERT INTO `nsu_equipment_type` (`id`, `name`) VALUES
(1, 'Generator'),
(2, 'Lawn Mower'),
(3, 'Water Pump'),
(4, 'Snow Remover');

-- --------------------------------------------------------

--
-- Table structure for table `nsu_settings`
--

DROP TABLE IF EXISTS `nsu_settings`;
CREATE TABLE IF NOT EXISTS `nsu_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `uid` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT 'user id',
  `start_status` enum('0','1') DEFAULT '0' COMMENT 'equipment adapter on and off',
  `t_switch_status` enum('0','1') DEFAULT '0' COMMENT 'transfer switch adapter on and off',
  `p_switch_status` enum('0','1') DEFAULT '0' COMMENT 'equipment adapter on and off',
  `auto_navigation` enum('0','1') DEFAULT '0' COMMENT 'toggled to 0 when manual is on.',
  `touch_id` enum('0','1') DEFAULT '0' COMMENT 'enable touch id',
  `learn` enum('0','1') DEFAULT '0' COMMENT 'enable learn',
  `follow` enum('0','1') DEFAULT '0' COMMENT 'enable robot follow function',
  `z_range` varchar(255) CHARACTER SET utf8 DEFAULT NULL COMMENT 'zone range ',
  `max_speed` int(11) DEFAULT NULL COMMENT 'robot max speed ',
  `temp` int(11) DEFAULT NULL COMMENT 'temperation ',
  `vibration` int(11) DEFAULT NULL COMMENT 'vibration ',
  `sound` int(11) DEFAULT NULL COMMENT 'sound ',
  `gas` int(11) DEFAULT NULL COMMENT 'gas ',
  `oil` int(11) DEFAULT NULL COMMENT 'oil ',
  `camera1` enum('1','2','3') DEFAULT '1' COMMENT 'adapter camera',
  `camera2` enum('1','2','3') DEFAULT '2' COMMENT 'robot camera 1',
  `camera3` enum('1','2','3') DEFAULT '3' COMMENT 'rebot camera 2',
  `updated` int(11) DEFAULT NULL COMMENT 'certify that programming is complete without error',
  `created` int(11) DEFAULT NULL COMMENT 'certify that programming is complete without error',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

--
-- Dumping data for table `nsu_settings`
--

INSERT INTO `nsu_settings` (`id`, `uid`, `start_status`, `t_switch_status`, `p_switch_status`, `auto_navigation`, `touch_id`, `learn`, `follow`, `z_range`, `max_speed`, `temp`, `vibration`, `sound`, `gas`, `oil`, `camera1`, `camera2`, `camera3`, `updated`, `created`) VALUES
(1, '1', '', '1', '0', '0', '0', '0', '0', NULL, NULL, 130, 4, 1, 3, 4, '1', '2', '2', NULL, NULL),
(2, '5', '', '1', '0', '0', '0', '0', '0', NULL, NULL, 130, 4, 1, 3, 4, '1', '2', '2', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `nsu_users`
--

DROP TABLE IF EXISTS `nsu_users`;
CREATE TABLE IF NOT EXISTS `nsu_users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `firstname` varchar(255) DEFAULT NULL COMMENT 'first name',
  `lastname` varchar(255) DEFAULT NULL COMMENT 'last name',
  `companyname` varchar(255) CHARACTER SET utf8 COLLATE utf8_german2_ci DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL COMMENT 'address',
  `address2` varchar(255) DEFAULT NULL COMMENT 'address 2',
  `city` varchar(255) DEFAULT NULL COMMENT 'city',
  `state` varchar(255) DEFAULT NULL COMMENT 'state',
  `province` varchar(255) DEFAULT NULL COMMENT 'province',
  `zipcode` varchar(255) DEFAULT NULL COMMENT 'zip code',
  `country` varchar(255) DEFAULT NULL COMMENT 'country',
  `phonenumber` varchar(255) DEFAULT NULL COMMENT 'phone number',
  `password` varchar(255) DEFAULT NULL,
  `password_recovery` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `securityquestion` varchar(255) DEFAULT NULL COMMENT 'security question',
  `questionanswer` varchar(255) DEFAULT NULL COMMENT 'security answer',
  `ip` varchar(255) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `private_key` varchar(255) DEFAULT NULL,
  `public_key` varchar(255) DEFAULT NULL,
  `created` int(11) DEFAULT NULL,
  `updated` int(11) DEFAULT NULL,
  `otp` int(11) DEFAULT NULL COMMENT 'validation. 1 is yes',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;

--
-- Dumping data for table `nsu_users`
--

INSERT INTO `nsu_users` (`id`, `name`, `firstname`, `lastname`, `companyname`, `address`, `address2`, `city`, `state`, `province`, `zipcode`, `country`, `phonenumber`, `password`, `password_recovery`, `email`, `securityquestion`, `questionanswer`, `ip`, `status`, `private_key`, `public_key`, `created`, `updated`, `otp`) VALUES
(5, 'demo1', 'demo', 'demo last', 'demo co', 'demo add', 'demo add2', 'demo city', 'demo state', 'demo prov', 'demo zip', 'demo country', '2022504858', 'fe01ce2a7fbac8fafaed7c982a04e229', '', 'demo1@yahoo.com', '', '', '', 1, NULL, NULL, 1581206400, NULL, NULL);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
