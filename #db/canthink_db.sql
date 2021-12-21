-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 18, 2021 at 11:28 AM
-- Server version: 8.0.27
-- PHP Version: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `canthink_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `audit_trails`
--

DROP TABLE IF EXISTS `audit_trails`;
CREATE TABLE IF NOT EXISTS `audit_trails` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `user_fname` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL,
  `event` enum('insert','update','delete') DEFAULT NULL,
  `table_name` varchar(128) DEFAULT NULL,
  `old_values` text,
  `new_values` text,
  `url` varchar(255) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `user_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_code` varchar(150) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT 'Auto generate, use for system reference',
  `user_full_name` varchar(255) DEFAULT NULL,
  `user_preferred_name` varchar(50) DEFAULT NULL,
  `user_gender` tinyint(1) DEFAULT '1' COMMENT '1 - Male, 2 - Female',
  `user_email` varchar(255) DEFAULT NULL,
  `user_username` varchar(100) DEFAULT NULL,
  `user_password` varchar(255) DEFAULT NULL,
  `user_avatar` varchar(255) DEFAULT 'default/user.png',
  `role_id` int DEFAULT NULL,
  `user_status` tinyint(1) DEFAULT '1' COMMENT '0 - Inactive, 1 - Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_code` (`user_code`),
  UNIQUE KEY `user_email` (`user_email`),
  UNIQUE KEY `user_username` (`user_username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
