-- Adminer 3.7.1 MySQL dump

SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = '+04:00';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `archive`;
CREATE TABLE `archive` (
  `name` varchar(255) NOT NULL,
  `lat` varchar(255) NOT NULL,
  `lon` varchar(255) NOT NULL,
  `hdop` float NOT NULL,
  `timestamp` datetime NOT NULL,
  `speed` varchar(255) NOT NULL,
  `misc` varchar(255) NOT NULL,
  `hash` varchar(255) NOT NULL,
  `passwd` varchar(255) NOT NULL,
  `flag` int(11) NOT NULL,
  KEY `name` (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `auth`;
CREATE TABLE `auth` (
  `login` varchar(50) NOT NULL,
  `pass` varchar(50) NOT NULL,
  `misc` varchar(255) NOT NULL,
  `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `login` (`login`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `points`;
CREATE TABLE `points` (
  `name` varchar(255) NOT NULL,
  `lat` float NOT NULL,
  `lon` float NOT NULL,
  `desc` varchar(255) NOT NULL,
  `marker` varchar(255) NOT NULL,
  `misc` varchar(255) NOT NULL,
  `test` varchar(255) NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `tracking`;
CREATE TABLE `tracking` (
  `name` varchar(50) NOT NULL,
  `lat` float NOT NULL,
  `lon` float NOT NULL,
  `hdop` float NOT NULL,
  `timestamp` datetime NOT NULL,
  `speed` varchar(255) NOT NULL,
  `misc` varchar(255) NOT NULL,
  `hash` varchar(50) NOT NULL,
  `flag` int(11) NOT NULL,
  UNIQUE KEY `name` (`name`,`hash`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- 2013-09-17 15:50:04