-- phpMyAdmin SQL Dump
-- version 4.7.9
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 27, 2018 at 01:25 PM
-- Server version: 5.7.21
-- PHP Version: 7.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `school_pics`
--

-- --------------------------------------------------------

--
-- Table structure for table `activitylogs`
--

DROP TABLE IF EXISTS `activitylogs`;
CREATE TABLE IF NOT EXISTS `activitylogs` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `pid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tid` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activity` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `activitylogs`
--

INSERT INTO `activitylogs` (`id`, `pid`, `tid`, `activity`, `status`, `created_at`, `updated_at`) VALUES
(1, NULL, '8bd89c00-c225-11e8-be2b-5d8de03672f5', 'Teacher Login', 1, '2018-09-27 01:49:05', '2018-09-27 01:49:05');

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admins_email_unique` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `classrooms`
--

DROP TABLE IF EXISTS `classrooms`;
CREATE TABLE IF NOT EXISTS `classrooms` (
  `cid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `tid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`cid`),
  KEY `classrooms_tid_foreign` (`tid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `classrooms`
--

INSERT INTO `classrooms` (`cid`, `name`, `tid`, `created_at`, `updated_at`) VALUES
('acff6c10-c225-11e8-a466-872e277c7d55', 'std 1', '8bd89c00-c225-11e8-be2b-5d8de03672f5', '2018-09-27 01:49:30', '2018-09-27 01:49:30'),
('2947abd0-c248-11e8-85a4-0f7dc42f6f19', 'std 2', '8bd89c00-c225-11e8-be2b-5d8de03672f5', '2018-09-27 05:56:22', '2018-09-27 05:56:22');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
CREATE TABLE IF NOT EXISTS `events` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `eid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `eid`, `cid`, `name`, `date`, `created_at`, `updated_at`) VALUES
(1, 'b22f5820-c255-11e8-a224-932868cae4f5', 'acff6c10-c225-11e8-a466-872e277c7d55', 'SP180927', '2018-09-27', '2018-09-27 07:34:05', '2018-09-27 07:34:05');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2018_08_10_234719_add_extra_field_to_users_table', 1),
(4, '2018_08_11_000042_create_teacher_table', 1),
(5, '2018_08_11_053551_create_jobs_table', 1),
(6, '2018_08_11_053603_create_failed_jobs_table', 1),
(7, '2018_08_11_164129_create_classrooms_table', 1),
(8, '2018_08_12_161828_create_parentusers_table', 1),
(9, '2018_08_12_171523_create_students_table', 1),
(10, '2018_08_20_003144_create_photos_table', 1),
(11, '2018_08_23_023749_create_events_table', 1),
(12, '2018_08_23_033000_create_photo_students_table', 1),
(13, '2018_09_06_225835_create_admins_table', 1),
(14, '2018_09_07_224838_add_last_loggedin_to_teachers_table', 1),
(15, '2018_09_07_225121_add_last_seen_to_parentusers_table', 1),
(16, '2018_09_07_225348_create_activitylogs_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `parentusers`
--

DROP TABLE IF EXISTS `parentusers`;
CREATE TABLE IF NOT EXISTS `parentusers` (
  `pid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activated` tinyint(4) NOT NULL DEFAULT '0',
  `email_token` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `last_seen` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `parentusers`
--

INSERT INTO `parentusers` (`pid`, `email`, `phone`, `password`, `activated`, `email_token`, `remember_token`, `created_at`, `updated_at`, `last_seen`) VALUES
('d708a8e0-c226-11e8-8028-eb8a0d8f5000', NULL, '9526123675', NULL, 0, NULL, NULL, '2018-09-27 01:57:50', '2018-09-27 01:57:50', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `photos`
--

DROP TABLE IF EXISTS `photos`;
CREATE TABLE IF NOT EXISTS `photos` (
  `iid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `eid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `originalpath` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `thumbnailpath` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`iid`),
  KEY `photos_tid_foreign` (`tid`),
  KEY `photos_cid_foreign` (`cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `photos`
--

INSERT INTO `photos` (`iid`, `tid`, `cid`, `eid`, `name`, `originalpath`, `thumbnailpath`, `created_at`, `updated_at`) VALUES
('b51c70e0-c255-11e8-9ecd-f9527865597d', '8bd89c00-c225-11e8-be2b-5d8de03672f5', 'acff6c10-c225-11e8-a466-872e277c7d55', 'b22f5820-c255-11e8-a224-932868cae4f5', '03.jpg', '/8bd89c00-c225-11e8-be2b-5d8de03672f5/acff6c10-c225-11e8-a466-872e277c7d55/original/03.jpg_1538053395.jpg', '/8bd89c00-c225-11e8-be2b-5d8de03672f5/acff6c10-c225-11e8-a466-872e277c7d55/thumbnail/03.jpg_1538053395.jpg', '2018-09-27 07:33:20', '2018-09-27 07:33:20');

-- --------------------------------------------------------

--
-- Table structure for table `photo_students`
--

DROP TABLE IF EXISTS `photo_students`;
CREATE TABLE IF NOT EXISTS `photo_students` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `iid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `photo_students`
--

INSERT INTO `photo_students` (`id`, `iid`, `sid`, `created_at`, `updated_at`) VALUES
(1, 'b51c70e0-c255-11e8-9ecd-f9527865597d', 'd70d8b00-c226-11e8-b423-6b6ec4f594e8', '2018-09-27 07:34:07', '2018-09-27 07:34:07');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
CREATE TABLE IF NOT EXISTS `students` (
  `sid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `picpath` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`sid`),
  KEY `students_tid_foreign` (`tid`),
  KEY `students_cid_foreign` (`cid`),
  KEY `students_pid_foreign` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`sid`, `tid`, `cid`, `pid`, `name`, `picpath`, `remember_token`, `created_at`, `updated_at`) VALUES
('d70d8b00-c226-11e8-b423-6b6ec4f594e8', '8bd89c00-c225-11e8-be2b-5d8de03672f5', 'acff6c10-c225-11e8-a466-872e277c7d55', 'd708a8e0-c226-11e8-8028-eb8a0d8f5000', 'Jessily', '/img/icon.jpg', NULL, '2018-09-27 01:57:50', '2018-09-27 01:57:50'),
('77b9a800-c227-11e8-918e-6dca613b1faf', '8bd89c00-c225-11e8-be2b-5d8de03672f5', 'acff6c10-c225-11e8-a466-872e277c7d55', 'd708a8e0-c226-11e8-8028-eb8a0d8f5000', 'Bincy', '/img/icon.jpg', NULL, '2018-09-27 02:02:20', '2018-09-27 02:02:20'),
('cf102a70-c256-11e8-aede-e3b38ef9f0f7', '8bd89c00-c225-11e8-be2b-5d8de03672f5', 'acff6c10-c225-11e8-a466-872e277c7d55', 'd708a8e0-c226-11e8-8028-eb8a0d8f5000', 'Chilu', '/img/icon.jpg', NULL, '2018-09-27 07:41:13', '2018-09-27 07:41:13');

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

DROP TABLE IF EXISTS `teachers`;
CREATE TABLE IF NOT EXISTS `teachers` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tid` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `school_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `school_zip_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `verified` tinyint(4) NOT NULL DEFAULT '0',
  `email_token` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `last_loggedin` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `teachers_email_unique` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`id`, `tid`, `name`, `email`, `password`, `school_name`, `school_zip_code`, `verified`, `email_token`, `remember_token`, `created_at`, `updated_at`, `last_loggedin`) VALUES
('8bd89c00-c225-11e8-be2b-5d8de03672f5', '8bd89c00-c225-11e8-be2b-5d8de03672f5', 'Reshma R', 'reshma.xtapps@gmail.com', '$2y$10$2g6bIK5qMa1H/GUOy5q0.ernuKohZHUZtJWiKldKo18aGJWnOXQ8C', 'DonBosco Public School', '682301', 0, 'cmVzaG1hLnh0YXBwc0BnbWFpbC5jb20=', NULL, '2018-09-27 01:48:35', '2018-09-27 01:49:05', '2018-09-27 07:19:05');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `school_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `school_zip_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
