-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Хост: localhost
-- Время создания: Янв 19 2015 г., 14:11
-- Версия сервера: 5.5.40-0ubuntu0.14.04.1
-- Версия PHP: 5.5.9-1ubuntu4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

CREATE DATABASE ciauth;
USE ciauth;

--
-- База данных: `ciauth`
--

-- --------------------------------------------------------

--
-- Структура таблицы `groups`
--

CREATE TABLE IF NOT EXISTS `groups` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Дамп данных таблицы `groups`
--

INSERT INTO `groups` (`id`, `name`) VALUES
(1, 'admin'),
(2, 'users');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `userid` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `createdon` int(10) unsigned NOT NULL,
  `lastname` varchar(100) DEFAULT NULL,
  `firstname` varchar(100) DEFAULT NULL,
  `gender` enum('m','w') DEFAULT NULL,
  `dateofbirth` date DEFAULT NULL,
  PRIMARY KEY (`userid`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Users' AUTO_INCREMENT=13 ;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`userid`, `username`, `password`, `createdon`, `lastname`, `firstname`, `gender`, `dateofbirth`) VALUES
(4, 'admin', '$2y$10$MbJx5U4miS1yj3VNurAgIeIBnlDT.icK4n0jOSm9efMvdDmobQPqG', 1421632688, 'adminLastName', 'adminFirstName', 'm', '1997-10-04'),
(6, 'test1', '$2y$10$40OYV308EjLaQq0Lq.mWq.aNytoDMQ1ZBUKIzVTqiW7Iq8l/mAb/K', 1421639961, 'test1', 'test1', 'w', '0000-00-00'),
(8, 'test4', '$2y$10$Y58MW.GkykPRcdsgJ9oV2.b0pb8WgcVnHmL4CuEgkhBEMyVChSsYq', 1421640005, 'test4', 'test4', 'w', '2015-01-02'),
(9, 'test5', '$2y$10$DiybpXlpVUXakmGojLL7Z.Pnv1GwMrLNN9y6IUJsGKhxMKB4uLvPq', 1421640019, 'test5', 'test5', 'w', '1997-10-04'),
(10, 'test7', '$2y$10$ZOloww94LM61MWd9goUYVOhuUUs/6GWh2gaaJFsaGbCNQe8eTYfe6', 1421640123, 'test7', 'test7', 'm', '0000-00-00'),
(11, 'test11', '$2y$10$phaTrDjtTfqYoPD9MELcYuhmPwZ.kgy8vnMRI9BIFKkkgXY.XLqwS', 1421647858, 'test11', 'test11', 'w', '1997-10-04'),
(12, 'test12', '$2y$10$tTYUKZCGgEWhhgfUtWBlhuG3fr8PH5eyDHiLYNDTIl.Ll3TSz856C', 1421647867, 'test12', 'test12', NULL, '0000-00-00');

-- --------------------------------------------------------

--
-- Структура таблицы `users_group`
--

CREATE TABLE IF NOT EXISTS `users_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `userid` int(10) unsigned NOT NULL,
  `groupid` smallint(5) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Дамп данных таблицы `users_group`
--

INSERT INTO `users_group` (`id`, `userid`, `groupid`) VALUES
(1, 4, 1),
(2, 5, 2),
(3, 6, 2),
(4, 7, 2),
(5, 8, 2),
(6, 9, 2),
(7, 10, 2),
(8, 11, 2),
(9, 12, 2);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
