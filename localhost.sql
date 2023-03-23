-- Adminer 4.8.1 MySQL 10.6.11-MariaDB-1 dump

SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

DROP DATABASE IF EXISTS `com`;
CREATE DATABASE `com` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `com`;

DROP TABLE IF EXISTS `complaint`;
CREATE TABLE `complaint` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `complaint` text NOT NULL,
  `address` varchar(50) NOT NULL,
  `mobile` varchar(12) NOT NULL,
  `lat` double NOT NULL,
  `lng` double NOT NULL,
  `img` varchar(300) NOT NULL,
  `ip` varchar(45) NOT NULL,
  `up_date` varchar(45) NOT NULL,
  `commands` varchar(200) NOT NULL,
  `com_state` varchar(20) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `complaint` (`id`, `name`, `complaint`, `address`, `mobile`, `lat`, `lng`, `img`, `ip`, `up_date`, `commands`, `com_state`, `status`) VALUES
(1,	'user',	'water support',	'Salem',	'9876543210',	0,	0,	'upload/img_000000000.jpg',	'192.168.1.2',	'2023/03/22 23:16',	'null',	'new',	0);

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(33) NOT NULL,
  `email` varchar(33) NOT NULL,
  `password` varchar(25) NOT NULL,
  `confirm_pass` varchar(25) NOT NULL,
  `mobile` varchar(12) NOT NULL,
  `role` enum('admin','user') NOT NULL,
  `ip` varchar(40) NOT NULL,
  `up_date` varchar(40) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

INSERT INTO `users` (`id`, `username`, `email`, `password`, `confirm_pass`, `mobile`, `role`, `ip`, `up_date`, `status`) VALUES
(1,	'admin',	'',	'admin',	'admin',	'999998888',	'admin',	'0.0.0.0',	'00/00/0000',	1),
(2,	'user',	'',	'user',	'user',	'999993333',	'user',	'0.0.0.0',	'00/00/0000',	1);

-- 2023-03-22 20:42:33
