-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 06, 2025 at 02:09 AM
-- Server version: 8.2.0
-- PHP Version: 8.1.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smart_doc_verification`
--

-- --------------------------------------------------------

--
-- Table structure for table `access_token`
--

DROP TABLE IF EXISTS `access_token`;
CREATE TABLE IF NOT EXISTS `access_token` (
  `id` int NOT NULL AUTO_INCREMENT,
  `organization_id` int NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `token` varchar(256) NOT NULL,
  `expire_date` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `access_token`
--

INSERT INTO `access_token` (`id`, `organization_id`, `name`, `token`, `expire_date`, `created_at`, `updated_at`) VALUES
(9, 3, 'Web App', '5a090861c9701d0b2b8aa3f28c9c7317', '2025-12-04 23:32:00', '2025-09-05 18:02:24', '2025-09-05 18:02:24'),
(1, 1, 'EKYC App', '474edcd0b36805ae411077228da5c3c0', '2025-12-04 23:32:00', '2025-09-05 18:02:42', '2025-09-05 23:33:36'),
(8, 3, 'Web App', '21d92043d34ffbe3589e9a225d1f4514', '2025-12-04 23:24:00', '2025-09-05 17:54:52', '2025-09-05 17:54:52');

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(300) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `name`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, 'Arvin Jayanake', 'arvinjnk05@gmail.com', 'ed02457b5c41d964dbd2f2a609d63fe1bb7528dbe55e1abf5b52c249cd735797', '2025-09-05 19:06:42', '2025-09-06 00:37:27'),
(2, 'Support Admin', 'support@smartdoc.com', 'f1ab0f0c56b6c7a72c74de33ecab04752de81ee60e05db96e9c62cf6dddf4a5b', '2025-09-05 19:06:42', '2025-09-05 19:06:42');

-- --------------------------------------------------------

--
-- Table structure for table `organization`
--

DROP TABLE IF EXISTS `organization`;
CREATE TABLE IF NOT EXISTS `organization` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) DEFAULT NULL,
  `address` varchar(200) DEFAULT NULL,
  `mobile` varchar(10) DEFAULT NULL,
  `status` int NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `organization`
--

INSERT INTO `organization` (`id`, `name`, `address`, `mobile`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Seylan Bank PLC', 'Colombo 03, Sri Lanka', '0771234567', 1, '2025-09-05 19:07:39', '2025-09-05 19:07:39'),
(2, 'Commercial Bank of Ceylon PLC', 'Colombo 01, Sri Lanka', '0712345678', 1, '2025-09-05 19:07:39', '2025-09-05 19:07:39'),
(3, 'Hatton National Bank PLC', 'Colombo 02, Sri Lanka', '0759876543', 1, '2025-09-05 19:07:39', '2025-09-05 19:07:39'),
(4, 'Nations Trust Bank PLC', 'Colombo 04, Sri Lanka', '0704567890', 1, '2025-09-05 19:07:39', '2025-09-05 19:07:39');

-- --------------------------------------------------------

--
-- Table structure for table `organization_user`
--

DROP TABLE IF EXISTS `organization_user`;
CREATE TABLE IF NOT EXISTS `organization_user` (
  `id` int NOT NULL AUTO_INCREMENT,
  `organization_id` int NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `password` varchar(300) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `organization_user`
--

INSERT INTO `organization_user` (`id`, `organization_id`, `name`, `email`, `password`, `created_at`, `updated_at`) VALUES
(1, 1, 'Seylan Bank Officer', 'officer@seylanbank.com', 'f1ab0f0c56b6c7a72c74de33ecab04752de81ee60e05db96e9c62cf6dddf4a5b', '2025-09-05 19:09:20', '2025-09-05 19:09:20'),
(2, 2, 'Commercial Bank Officer', 'officer@combank.com', 'f1ab0f0c56b6c7a72c74de33ecab04752de81ee60e05db96e9c62cf6dddf4a5b', '2025-09-05 19:09:20', '2025-09-05 19:09:20'),
(3, 3, 'HNB Officer', 'officer@hnb.lk', 'f1ab0f0c56b6c7a72c74de33ecab04752de81ee60e05db96e9c62cf6dddf4a5b', '2025-09-05 19:09:20', '2025-09-05 19:09:20'),
(4, 4, 'Nations Trust Officer', 'officer@ntb.lk', 'f1ab0f0c56b6c7a72c74de33ecab04752de81ee60e05db96e9c62cf6dddf4a5b', '2025-09-05 19:09:20', '2025-09-05 19:09:20');

-- --------------------------------------------------------

--
-- Table structure for table `usage`
--

DROP TABLE IF EXISTS `usage`;
CREATE TABLE IF NOT EXISTS `usage` (
  `id` int NOT NULL AUTO_INCREMENT,
  `access_token_id` int NOT NULL,
  `status` int NOT NULL DEFAULT '0',
  `doc_type` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=203 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `usage`
--

INSERT INTO `usage` (`id`, `access_token_id`, `status`, `doc_type`, `created_at`) VALUES
(1, 1, 1, 'passport', '2025-08-06 21:19:18'),
(2, 1, 1, 'new_nic', '2025-09-01 00:19:18'),
(3, 1, 1, 'new_nic', '2025-08-17 01:19:18'),
(4, 1, 1, 'old_nic', '2025-08-26 20:19:18'),
(5, 1, 1, 'new_nic', '2025-08-11 00:19:18'),
(6, 1, 0, 'old_nic', '2025-08-18 18:19:18'),
(7, 1, 0, 'old_nic', '2025-09-04 22:19:18'),
(8, 1, 0, 'new_nic', '2025-08-14 05:19:18'),
(9, 1, 1, 'passport', '2025-08-23 10:19:18'),
(10, 1, 1, 'driving_licence', '2025-08-07 03:19:18'),
(11, 1, 0, 'old_nic', '2025-08-15 23:19:18'),
(12, 1, 0, 'old_nic', '2025-08-07 03:19:18'),
(13, 1, 1, 'driving_licence', '2025-08-24 14:19:18'),
(14, 1, 1, 'old_nic', '2025-08-14 10:19:18'),
(15, 1, 1, 'passport', '2025-08-12 13:19:18'),
(16, 1, 1, 'old_nic', '2025-08-29 17:19:18'),
(17, 1, 1, 'driving_licence', '2025-08-15 00:19:18'),
(18, 1, 1, 'driving_licence', '2025-09-04 17:19:18'),
(19, 1, 0, 'new_nic', '2025-08-08 05:19:18'),
(20, 1, 0, 'driving_licence', '2025-08-13 21:19:18'),
(21, 1, 1, 'driving_licence', '2025-09-04 15:19:18'),
(22, 1, 1, 'driving_licence', '2025-08-21 07:19:18'),
(23, 1, 1, 'new_nic', '2025-08-11 15:19:18'),
(24, 1, 0, 'old_nic', '2025-08-08 21:19:18'),
(25, 1, 0, 'new_nic', '2025-08-31 06:19:18'),
(26, 1, 0, 'old_nic', '2025-08-07 02:19:18'),
(27, 1, 1, 'passport', '2025-08-28 18:19:18'),
(28, 1, 0, 'driving_licence', '2025-09-03 09:19:18'),
(29, 1, 0, 'old_nic', '2025-08-09 07:19:18'),
(30, 1, 1, 'old_nic', '2025-08-10 00:19:18'),
(31, 1, 0, 'old_nic', '2025-08-20 08:19:18'),
(32, 1, 1, 'driving_licence', '2025-08-17 09:19:18'),
(33, 1, 0, 'passport', '2025-08-10 05:19:18'),
(34, 1, 0, 'old_nic', '2025-08-24 01:19:18'),
(35, 1, 1, 'new_nic', '2025-09-01 01:19:18'),
(36, 1, 0, 'passport', '2025-08-20 08:19:18'),
(37, 1, 1, 'passport', '2025-08-26 16:19:18'),
(38, 1, 1, 'passport', '2025-08-16 07:19:18'),
(39, 1, 1, 'driving_licence', '2025-08-16 15:19:18'),
(40, 1, 1, 'driving_licence', '2025-08-08 03:19:18'),
(41, 1, 1, 'old_nic', '2025-08-18 02:19:18'),
(42, 1, 1, 'passport', '2025-08-23 13:19:18'),
(43, 1, 0, 'driving_licence', '2025-08-20 17:19:18'),
(44, 1, 1, 'old_nic', '2025-09-05 05:19:18'),
(45, 1, 0, 'new_nic', '2025-09-03 21:19:18'),
(46, 1, 0, 'driving_licence', '2025-08-08 04:19:18'),
(47, 1, 0, 'old_nic', '2025-09-03 13:19:18'),
(48, 1, 0, 'driving_licence', '2025-08-25 02:19:18'),
(49, 1, 1, 'new_nic', '2025-08-19 04:19:18'),
(50, 1, 0, 'new_nic', '2025-09-02 04:19:18'),
(51, 1, 1, 'old_nic', '2025-09-05 17:19:18'),
(52, 1, 0, 'passport', '2025-08-15 13:19:18'),
(53, 1, 0, 'old_nic', '2025-08-18 18:19:18'),
(54, 1, 0, 'old_nic', '2025-08-24 05:19:18'),
(55, 1, 1, 'passport', '2025-09-01 18:19:18'),
(56, 1, 1, 'driving_licence', '2025-09-05 13:19:18'),
(57, 1, 0, 'old_nic', '2025-08-08 22:19:18'),
(58, 1, 1, 'old_nic', '2025-08-19 10:19:18'),
(59, 1, 0, 'passport', '2025-08-07 21:19:18'),
(60, 1, 1, 'new_nic', '2025-09-04 11:19:18'),
(61, 1, 1, 'passport', '2025-08-17 06:19:18'),
(62, 1, 1, 'driving_licence', '2025-08-08 07:19:18'),
(63, 1, 1, 'passport', '2025-08-21 22:19:18'),
(64, 1, 1, 'old_nic', '2025-09-01 22:19:18'),
(65, 1, 0, 'new_nic', '2025-08-18 02:19:18'),
(66, 1, 1, 'passport', '2025-08-14 15:19:18'),
(67, 1, 1, 'driving_licence', '2025-08-15 12:19:18'),
(68, 1, 1, 'driving_licence', '2025-08-08 05:19:18'),
(69, 1, 0, 'driving_licence', '2025-09-04 11:19:18'),
(70, 1, 1, 'driving_licence', '2025-08-31 13:19:18'),
(71, 1, 1, 'passport', '2025-08-24 11:19:18'),
(72, 1, 0, 'old_nic', '2025-08-26 10:19:18'),
(73, 1, 1, 'new_nic', '2025-09-03 10:19:18'),
(74, 1, 1, 'new_nic', '2025-08-30 12:19:18'),
(75, 1, 1, 'driving_licence', '2025-08-10 15:19:18'),
(76, 1, 0, 'old_nic', '2025-08-14 14:19:18'),
(77, 1, 1, 'passport', '2025-09-04 11:19:18'),
(78, 1, 1, 'passport', '2025-09-01 02:19:18'),
(79, 1, 0, 'passport', '2025-08-29 23:19:18'),
(80, 1, 1, 'new_nic', '2025-08-10 09:19:18'),
(81, 1, 1, 'driving_licence', '2025-08-12 07:19:18'),
(82, 1, 0, 'driving_licence', '2025-08-18 19:19:18'),
(83, 1, 0, 'driving_licence', '2025-08-30 21:19:18'),
(84, 1, 0, 'old_nic', '2025-08-18 18:19:18'),
(85, 1, 0, 'old_nic', '2025-08-08 07:19:18'),
(86, 1, 1, 'new_nic', '2025-09-05 13:19:18'),
(87, 1, 0, 'driving_licence', '2025-08-20 03:19:18'),
(88, 1, 1, 'old_nic', '2025-08-26 20:19:18'),
(89, 1, 0, 'old_nic', '2025-08-11 01:19:18'),
(90, 1, 0, 'driving_licence', '2025-08-16 09:19:18'),
(91, 1, 0, 'passport', '2025-09-01 08:19:18'),
(92, 1, 1, 'new_nic', '2025-08-26 12:19:18'),
(93, 1, 1, 'driving_licence', '2025-09-04 19:19:18'),
(94, 1, 0, 'old_nic', '2025-08-22 21:19:18'),
(95, 1, 0, 'passport', '2025-08-16 06:19:18'),
(96, 1, 1, 'passport', '2025-09-02 21:19:18'),
(97, 1, 0, 'passport', '2025-08-17 10:19:18'),
(98, 1, 0, 'old_nic', '2025-08-19 07:19:18'),
(99, 1, 1, 'old_nic', '2025-08-08 21:19:18'),
(100, 1, 1, 'new_nic', '2025-09-02 02:19:18'),
(101, 1, 0, 'passport', '2025-08-18 15:19:31'),
(102, 1, 0, 'old_nic', '2025-08-21 22:19:31'),
(103, 1, 1, 'passport', '2025-09-05 14:19:31'),
(104, 1, 0, 'new_nic', '2025-09-01 08:19:31'),
(105, 1, 1, 'new_nic', '2025-08-21 02:19:31'),
(106, 1, 0, 'driving_licence', '2025-09-02 12:19:31'),
(107, 1, 0, 'driving_licence', '2025-08-16 00:19:31'),
(108, 1, 0, 'driving_licence', '2025-08-23 20:19:31'),
(109, 1, 1, 'new_nic', '2025-08-07 02:19:31'),
(110, 1, 1, 'old_nic', '2025-08-18 03:19:31'),
(111, 1, 1, 'passport', '2025-08-31 05:19:31'),
(112, 1, 0, 'old_nic', '2025-08-08 19:19:31'),
(113, 1, 0, 'new_nic', '2025-08-12 22:19:31'),
(114, 1, 0, 'new_nic', '2025-08-29 16:19:31'),
(115, 1, 1, 'new_nic', '2025-08-12 18:19:31'),
(116, 1, 1, 'new_nic', '2025-08-19 15:19:31'),
(117, 1, 0, 'old_nic', '2025-08-23 02:19:31'),
(118, 1, 0, 'driving_licence', '2025-08-07 17:19:31'),
(119, 1, 0, 'old_nic', '2025-08-20 16:19:31'),
(120, 1, 0, 'driving_licence', '2025-09-01 00:19:31'),
(121, 1, 1, 'passport', '2025-08-09 06:19:31'),
(122, 1, 0, 'driving_licence', '2025-08-10 05:19:31'),
(123, 1, 0, 'old_nic', '2025-08-07 16:19:31'),
(124, 1, 1, 'passport', '2025-08-10 04:19:31'),
(125, 1, 1, 'new_nic', '2025-08-18 21:19:31'),
(126, 1, 0, 'new_nic', '2025-08-21 04:19:31'),
(127, 1, 1, 'new_nic', '2025-08-12 23:19:31'),
(128, 1, 1, 'passport', '2025-08-29 12:19:31'),
(129, 1, 1, 'passport', '2025-08-29 07:19:31'),
(130, 1, 1, 'new_nic', '2025-08-28 07:19:31'),
(131, 1, 1, 'passport', '2025-08-30 08:19:31'),
(132, 1, 1, 'old_nic', '2025-09-04 09:19:31'),
(133, 1, 0, 'old_nic', '2025-08-28 22:19:31'),
(134, 1, 1, 'old_nic', '2025-08-08 04:19:31'),
(135, 1, 0, 'new_nic', '2025-08-08 23:19:31'),
(136, 1, 1, 'passport', '2025-08-30 14:19:31'),
(137, 1, 0, 'driving_licence', '2025-08-11 12:19:31'),
(138, 1, 0, 'new_nic', '2025-08-09 12:19:31'),
(139, 1, 1, 'driving_licence', '2025-08-27 09:19:31'),
(140, 1, 0, 'new_nic', '2025-08-26 19:19:31'),
(141, 1, 1, 'passport', '2025-08-22 02:19:31'),
(142, 1, 0, 'passport', '2025-08-07 18:19:31'),
(143, 1, 0, 'driving_licence', '2025-08-26 04:19:31'),
(144, 1, 0, 'old_nic', '2025-08-17 18:19:31'),
(145, 1, 0, 'passport', '2025-08-25 16:19:31'),
(146, 1, 0, 'old_nic', '2025-08-08 07:19:31'),
(147, 1, 1, 'old_nic', '2025-08-09 00:19:31'),
(148, 1, 0, 'new_nic', '2025-08-23 13:19:31'),
(149, 1, 0, 'driving_licence', '2025-08-29 20:19:31'),
(150, 1, 0, 'old_nic', '2025-09-02 16:19:31'),
(151, 1, 0, 'old_nic', '2025-08-30 18:19:31'),
(152, 1, 1, 'new_nic', '2025-08-11 18:19:31'),
(153, 1, 1, 'old_nic', '2025-09-01 22:19:31'),
(154, 1, 0, 'passport', '2025-09-01 23:19:31'),
(155, 1, 1, 'old_nic', '2025-09-04 03:19:31'),
(156, 1, 0, 'driving_licence', '2025-08-22 11:19:31'),
(157, 1, 0, 'old_nic', '2025-08-22 10:19:31'),
(158, 1, 0, 'old_nic', '2025-08-25 08:19:31'),
(159, 1, 0, 'driving_licence', '2025-08-15 05:19:31'),
(160, 1, 1, 'driving_licence', '2025-08-14 04:19:31'),
(161, 1, 1, 'passport', '2025-08-14 23:19:31'),
(162, 1, 0, 'old_nic', '2025-08-26 15:19:31'),
(163, 1, 1, 'new_nic', '2025-08-23 08:19:31'),
(164, 1, 0, 'passport', '2025-08-16 18:19:31'),
(165, 1, 0, 'old_nic', '2025-08-26 23:19:31'),
(166, 1, 0, 'driving_licence', '2025-08-29 03:19:31'),
(167, 1, 1, 'driving_licence', '2025-08-17 05:19:31'),
(168, 1, 0, 'new_nic', '2025-08-28 04:19:31'),
(169, 1, 0, 'old_nic', '2025-08-14 17:19:31'),
(170, 1, 0, 'passport', '2025-09-03 08:19:31'),
(171, 1, 0, 'old_nic', '2025-08-26 11:19:31'),
(172, 1, 1, 'driving_licence', '2025-08-10 07:19:31'),
(173, 1, 1, 'new_nic', '2025-08-18 17:19:31'),
(174, 1, 1, 'new_nic', '2025-09-02 16:19:31'),
(175, 1, 0, 'new_nic', '2025-08-09 10:19:31'),
(176, 1, 0, 'passport', '2025-08-26 11:19:31'),
(177, 1, 1, 'new_nic', '2025-08-08 08:19:31'),
(178, 1, 1, 'passport', '2025-08-11 22:19:31'),
(179, 1, 1, 'new_nic', '2025-08-24 15:19:31'),
(180, 1, 1, 'new_nic', '2025-08-16 16:19:31'),
(181, 1, 1, 'old_nic', '2025-08-22 21:19:31'),
(182, 1, 0, 'passport', '2025-08-16 07:19:31'),
(183, 1, 1, 'driving_licence', '2025-08-24 21:19:31'),
(184, 1, 1, 'passport', '2025-08-10 01:19:31'),
(185, 1, 0, 'new_nic', '2025-08-17 22:19:31'),
(186, 1, 1, 'old_nic', '2025-08-20 05:19:31'),
(187, 1, 0, 'new_nic', '2025-08-26 01:19:31'),
(188, 1, 1, 'driving_licence', '2025-09-01 05:19:31'),
(189, 1, 1, 'passport', '2025-08-21 21:19:31'),
(190, 1, 0, 'passport', '2025-08-30 05:19:31'),
(191, 1, 0, 'passport', '2025-08-11 22:19:31'),
(192, 1, 1, 'old_nic', '2025-08-23 16:19:31'),
(193, 1, 0, 'passport', '2025-09-02 06:19:31'),
(194, 1, 1, 'old_nic', '2025-08-31 12:19:31'),
(195, 1, 0, 'driving_licence', '2025-08-12 20:19:31'),
(196, 1, 1, 'old_nic', '2025-08-15 12:19:31'),
(197, 1, 0, 'driving_licence', '2025-08-15 11:19:31'),
(198, 1, 1, 'old_nic', '2025-08-11 00:19:31'),
(199, 1, 1, 'old_nic', '2025-09-03 22:19:31'),
(200, 1, 0, 'driving_licence', '2025-08-22 09:19:31'),
(202, 1, 1, 'driving_licence', '2025-09-06 01:58:33');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
