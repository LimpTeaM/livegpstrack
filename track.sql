-- phpMyAdmin SQL Dump
-- version 3.5.5
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Фев 20 2013 г., 15:03
-- Версия сервера: 5.1.66-0+squeeze1
-- Версия PHP: 5.3.19-1~dotdeb.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- База данных: `track`
--
CREATE DATABASE `track` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `track`;

-- --------------------------------------------------------

--
-- Структура таблицы `archive`
--

CREATE TABLE IF NOT EXISTS `archive` (
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

-- --------------------------------------------------------

--
-- Структура таблицы `auth`
--

CREATE TABLE IF NOT EXISTS `auth` (
  `login` varchar(50) NOT NULL,
  `pass` varchar(50) NOT NULL,
  `misc` varchar(255) NOT NULL,
  UNIQUE KEY `login` (`login`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `points`
--

CREATE TABLE IF NOT EXISTS `points` (
  `name` varchar(255) NOT NULL,
  `lat` float NOT NULL,
  `lon` float NOT NULL,
  `desc` varchar(255) NOT NULL,
  `marker` varchar(255) NOT NULL,
  `misc` varchar(255) NOT NULL,
  `test` varchar(255) NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Структура таблицы `tracking`
--

CREATE TABLE IF NOT EXISTS `tracking` (
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
