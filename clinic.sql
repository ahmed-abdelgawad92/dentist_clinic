-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Erstellungszeit: 19. Jul 2018 um 14:49
-- Server-Version: 10.1.30-MariaDB
-- PHP-Version: 7.2.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `clinic`
--
CREATE DATABASE IF NOT EXISTS `clinic` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `clinic`;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `appointments`
--

CREATE TABLE IF NOT EXISTS `appointments` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `diagnose_id` int(10) UNSIGNED NOT NULL,
  `treatment` text COLLATE utf8mb4_unicode_ci,
  `date` date NOT NULL,
  `time` time NOT NULL,
  `approved` tinyint(1) NOT NULL DEFAULT '2',
  `approved_time` datetime DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `appointments_diagnose_id_foreign` (`diagnose_id`),
  KEY `deleted` (`deleted`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `appointment_states`
--

CREATE TABLE IF NOT EXISTS `appointment_states` (
  `id` tinyint(3) NOT NULL AUTO_INCREMENT,
  `value` int(100) NOT NULL,
  `date` date DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cases_photos`
--

CREATE TABLE IF NOT EXISTS `cases_photos` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `diagnose_id` int(10) UNSIGNED NOT NULL,
  `photo` varchar(255) NOT NULL,
  `before_after` tinyint(1) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `diagnose_id` (`diagnose_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `diagnoses`
--

CREATE TABLE IF NOT EXISTS `diagnoses` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `patient_id` int(10) UNSIGNED NOT NULL,
  `total_paid` double(10,2) DEFAULT NULL,
  `done` tinyint(1) DEFAULT '0',
  `discount` decimal(6,2) NOT NULL DEFAULT '0.00',
  `discount_type` tinyint(1) NOT NULL DEFAULT '0',
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `diagnoses_patient_id_foreign` (`patient_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `diagnose_drug`
--

CREATE TABLE IF NOT EXISTS `diagnose_drug` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `diagnose_id` int(10) UNSIGNED NOT NULL,
  `drug_id` int(10) UNSIGNED NOT NULL,
  `dose` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `drugs_diagnose_id_foreign` (`diagnose_id`),
  KEY `drug_id` (`drug_id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `drugs`
--

CREATE TABLE IF NOT EXISTS `drugs` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `migrations`
--

CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `oral_radiologies`
--

CREATE TABLE IF NOT EXISTS `oral_radiologies` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `diagnose_id` int(10) UNSIGNED NOT NULL,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oral_radiologies_diagnose_id_foreign` (`diagnose_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `password_resets`
--

CREATE TABLE IF NOT EXISTS `password_resets` (
  `uname` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_uname_index` (`uname`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `patients`
--

CREATE TABLE IF NOT EXISTS `patients` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL,
  `dob` date NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `diabetes` enum('0','1') COLLATE utf8mb4_unicode_ci NOT NULL,
  `blood_pressure` enum('low','normal','high') COLLATE utf8mb4_unicode_ci NOT NULL,
  `medical_compromise` longtext COLLATE utf8mb4_unicode_ci,
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `deleted` (`deleted`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `teeth`
--

CREATE TABLE IF NOT EXISTS `teeth` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `diagnose_id` int(10) UNSIGNED NOT NULL,
  `teeth_name` varchar(255) CHARACTER SET utf8mb4 NOT NULL,
  `diagnose_type` varchar(255) NOT NULL,
  `price` decimal(6,2) NOT NULL,
  `description` varchar(1000) DEFAULT NULL,
  `color` varchar(7) NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `diagnose_id` (`diagnose_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uname` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` tinyint(4) NOT NULL DEFAULT '0',
  `photo` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_uname_unique` (`uname`),
  KEY `deleted` (`deleted`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `users`
--

INSERT INTO `users` (`id`, `uname`, `password`, `name`, `phone`, `role`, `photo`, `remember_token`, `deleted`, `created_at`, `updated_at`) VALUES
(1, 'fox', '$2y$10$4L9S2t0oQTIHfHcg75dC7OyrT8ARIPKEn8kYE8kCMeKHugFTge27e', 'mostafa fox', '0101010102222', 1, NULL, 'n7GwJRojA98amdvCTN6bBXd4mxDRDBuhwUfvxKowTyTuY0sF0feeqn5UXJnQ', 0, '2018-06-06 20:32:09', '2018-07-05 11:22:44'),
(2, 'a_ayman', '$2y$10$yGlt2VLavsx7UQ1Epn0oZuylz6yoVXY9ImxeNHiGjPwDdDSiXPWr6', 'ahmed ayman mokhtar', '0112130000', 1, 'patient_profile/Xplev1ZHJoJdI0E5qEil2I1FcLD764eLKmRFRx3o.jpeg', 'yahkxoHna4nltpOGGSPL2a8ZWFJlUtOzjnQEGR4CoVNGs6TFgPH7HiGegDLI', 0, '2018-07-04 05:12:15', '2018-07-09 06:15:47'),
(3, 'ahmed_12', '$2y$10$17T.ybSzsn.o9IoEabxH9OeJSAduNkYyfU5kbzPLgOfwkrZFuYCaC', 'ahmed aly hassan', '0222222222', 0, 'user_profile/ZDETUvmq1GkYLiaAVdtyNFGec3lDqep3IsNWorGK.jpeg', NULL, 0, '2018-07-04 11:23:39', '2018-07-06 08:36:11'),
(5, 'asd', '$2y$10$DtirQ0C9vvu4YOKV0Qs0J.I/ig3.IqGwgHpCvAN18CoxTzPDTz4j.', 'ahmed', '0111111111111', 1, NULL, NULL, 0, '2018-07-05 08:21:22', '2018-07-05 08:21:22'),
(14, 'miller', '$2y$10$30BOiUWPlol.tCjePoC7ee3ZX.o3xBP7stsjG5umUCkc7d.FZKek2', 'john miller', '0111111111111', 1, NULL, '08DpfjZ5hQB0Bmx8CpSQ2xOzugTFlnvSNtEtapDdeaqERrJQYshlcWij7eOp', 1, '2018-07-05 09:51:47', '2018-07-06 04:47:39'),
(15, 'assssdddddd', '$2y$10$4.9uMgIg0ibWXl6L0n1WLOpVX/kjzMipHO88SSfSmJUNznKy5gcAC', 'ahmed ayman mokhtar', '0111111111111', 1, NULL, NULL, 0, '2018-07-05 09:57:03', '2018-07-05 09:57:03'),
(16, 'aaaaa', '$2y$10$8A8WAom83Iw5duRGVnBXiOQhOPxuOHDsm7VIghp1S5jsIkCHaKqc6', 'john miller', '0111111111111', 1, NULL, NULL, 0, '2018-07-05 09:57:59', '2018-07-05 09:57:59'),
(17, 'miller92', '$2y$10$t0LAbqKpYC/Iqms.A.3LO.9hY3.HzXBX/ZJ3JZmtu.YHyu.jSpMP2', 'john miller', '0111111111111', 1, NULL, NULL, 0, '2018-07-05 10:03:07', '2018-07-05 10:03:07'),
(18, 'miller_95', '$2y$10$/ZYmoHBel7ZymjbMUr81G.Q32d.HM10wG6CdBhwkKF/roQQrcbywq', 'mostafa fox', '0111111111111', 1, NULL, NULL, 0, '2018-07-05 10:04:19', '2018-07-05 10:04:19'),
(19, 'miller_92', '$2y$10$ZEmYW.WjXtkcKFL/ox/l/eJEJLobbiPQBfyN040PAENCDOaKOMJGq', 'john miller', '0121121212121', 1, NULL, NULL, 0, '2018-07-05 10:12:02', '2018-07-05 10:12:02'),
(20, 'ahmed', '$2y$10$ug.DSEMCHnMOpKvuFc7USOqTwme3D08lrsWBKDA9TWw24u9lvIscm', 'john miller', '0111111111111', 1, NULL, NULL, 0, '2018-07-05 10:14:49', '2018-07-05 10:14:49'),
(21, 'hamada', '$2y$10$JWpi1dwu0yCH1hYqJw7LpeIrOTc8sxQrXVot.nN1aREN3TDsIGS62', 'john miller', '0111111111111', 1, NULL, NULL, 0, '2018-07-05 10:23:46', '2018-07-05 10:23:46'),
(22, 'asdfdgg', '$2y$10$SMHalEoO8KypGh3h/l8KKu0K.iHh5a400cmcxD6Tc/Mrm9C4IVllS', 'john miller', '0111111111111', 1, NULL, NULL, 0, '2018-07-05 10:30:10', '2018-07-05 10:30:10'),
(23, 'abo2albgamed', '$2y$10$7Zp8/.WugKdo9eGDiZ.vrOcHQUleDBotl0UE7lkJSSidpC884Fnse', 'hamid', '01010101010', 1, NULL, NULL, 0, '2018-07-05 10:49:49', '2018-07-05 10:49:49'),
(24, 'hamed', '$2y$10$PRa4BfMF6VPUAe1M.f0uFu41Rt.tGV.tVTejIaQFBq.eriTUVJB4u', 'hamed', '01010101010', 1, NULL, NULL, 0, '2018-07-05 10:52:46', '2018-07-05 10:52:46'),
(25, 'aaaaaaaaaaaa', '$2y$10$7ie2m7jNk2ntC3NYuzvdJOn51jlw/9rpN3hLibVe3ehq/oV0RXzR.', 'john miller', '0122222222222', 0, NULL, NULL, 0, '2018-07-05 10:54:16', '2018-07-05 10:54:16'),
(26, 'millerr', '$2y$10$TnQRnn4.waDeMnqg6WWZ/OliqF3TdP4Ky304tDzTBXvrjLnYlrMSu', 'john miller', '0122222222222', 0, NULL, NULL, 0, '2018-07-05 11:03:07', '2018-07-05 11:03:07'),
(27, 'hamedhuaa', '$2y$10$wrPTyn6YoD5jx6SZkjapB.eFItGuoD9IfQ1loJp.h/LNNtOKuZAiG', 'hemdan', '0111111111111', 1, 'user_profile/T0jFUlyX1bzwxG96hgfbvypy1rHaj2sivB6Vdjcy.jpeg', NULL, 0, '2018-07-05 11:13:49', '2018-07-05 11:13:49');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user_logs`
--

CREATE TABLE IF NOT EXISTS `user_logs` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `affected_table` varchar(100) NOT NULL,
  `affected_row` int(10) UNSIGNED NOT NULL,
  `process_type` varchar(100) NOT NULL,
  `description` text,
  `user_id` int(10) UNSIGNED NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=152 DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `working_times`
--

CREATE TABLE IF NOT EXISTS `working_times` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `day` tinyint(1) NOT NULL,
  `time_from` time NOT NULL,
  `time_to` time NOT NULL,
  `deleted` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_diagnose_id_foreign` FOREIGN KEY (`diagnose_id`) REFERENCES `diagnoses` (`id`);

--
-- Constraints der Tabelle `cases_photos`
--
ALTER TABLE `cases_photos`
  ADD CONSTRAINT `cases_photos_ibfk_1` FOREIGN KEY (`diagnose_id`) REFERENCES `diagnoses` (`id`);

--
-- Constraints der Tabelle `diagnoses`
--
ALTER TABLE `diagnoses`
  ADD CONSTRAINT `diagnoses_patient_id_foreign` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`);

--
-- Constraints der Tabelle `diagnose_drug`
--
ALTER TABLE `diagnose_drug`
  ADD CONSTRAINT `diagnose_drug_ibfk_1` FOREIGN KEY (`drug_id`) REFERENCES `drugs` (`id`),
  ADD CONSTRAINT `diagnose_drug_ibfk_2` FOREIGN KEY (`diagnose_id`) REFERENCES `diagnoses` (`id`);

--
-- Constraints der Tabelle `oral_radiologies`
--
ALTER TABLE `oral_radiologies`
  ADD CONSTRAINT `oral_radiologies_diagnose_id_foreign` FOREIGN KEY (`diagnose_id`) REFERENCES `diagnoses` (`id`);

--
-- Constraints der Tabelle `teeth`
--
ALTER TABLE `teeth`
  ADD CONSTRAINT `teeth_ibfk_1` FOREIGN KEY (`diagnose_id`) REFERENCES `diagnoses` (`id`);

--
-- Constraints der Tabelle `user_logs`
--
ALTER TABLE `user_logs`
  ADD CONSTRAINT `user_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
