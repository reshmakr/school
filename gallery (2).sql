-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Apr 05, 2018 at 11:55 AM
-- Server version: 5.7.19
-- PHP Version: 7.0.23

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gallery`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) UNSIGNED DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT '1',
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`),
  KEY `categories_parent_id_foreign` (`parent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `classrooms`
--

DROP TABLE IF EXISTS `classrooms`;
CREATE TABLE IF NOT EXISTS `classrooms` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `classrooms`
--

INSERT INTO `classrooms` (`id`, `name`, `created_at`, `updated_at`, `user_id`) VALUES
(1, 'Room1', '2018-03-22 05:17:22', '2018-03-22 05:17:22', 1),
(2, 'Room2', '2018-03-22 05:17:30', '2018-03-22 05:17:30', 1),
(3, 'backend', '2018-03-26 00:52:33', '2018-03-26 00:52:33', 1),
(4, 'roomui', '2018-04-05 05:33:09', '2018-04-05 05:33:09', 1),
(5, 'ujhjh', '2018-04-05 05:41:30', '2018-04-05 05:41:30', 18),
(6, 'roomui', '2018-04-05 05:45:33', '2018-04-05 05:45:33', 18),
(7, 'roomui', '2018-04-05 05:45:43', '2018-04-05 05:45:43', 18),
(8, 'abcd', '2018-04-05 05:46:34', '2018-04-05 05:46:34', 18),
(9, 'abcde', '2018-04-05 05:50:20', '2018-04-05 05:50:20', 18);

-- --------------------------------------------------------

--
-- Table structure for table `classroom_user`
--

DROP TABLE IF EXISTS `classroom_user`;
CREATE TABLE IF NOT EXISTS `classroom_user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `classroom_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `data_rows`
--

DROP TABLE IF EXISTS `data_rows`;
CREATE TABLE IF NOT EXISTS `data_rows` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `data_type_id` int(10) UNSIGNED NOT NULL,
  `field` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `required` tinyint(1) NOT NULL DEFAULT '0',
  `browse` tinyint(1) NOT NULL DEFAULT '1',
  `read` tinyint(1) NOT NULL DEFAULT '1',
  `edit` tinyint(1) NOT NULL DEFAULT '1',
  `add` tinyint(1) NOT NULL DEFAULT '1',
  `delete` tinyint(1) NOT NULL DEFAULT '1',
  `details` text COLLATE utf8mb4_unicode_ci,
  `order` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `data_rows_data_type_id_foreign` (`data_type_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `data_types`
--

DROP TABLE IF EXISTS `data_types`;
CREATE TABLE IF NOT EXISTS `data_types` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name_singular` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name_plural` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `model_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `policy_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `controller` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `generate_permissions` tinyint(1) NOT NULL DEFAULT '0',
  `server_side` tinyint(4) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `data_types_name_unique` (`name`),
  UNIQUE KEY `data_types_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `galleries`
--

DROP TABLE IF EXISTS `galleries`;
CREATE TABLE IF NOT EXISTS `galleries` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `student_id` int(11) NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `thumbnail` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `image_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_description` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `galleries`
--

INSERT INTO `galleries` (`id`, `student_id`, `image`, `thumbnail`, `is_active`, `created_at`, `updated_at`, `image_name`, `image_description`) VALUES
(1, 1, '/images/1/1521715912.jpg', '/images/1/thumbnail_1521715912.jpg', 0, '2018-03-22 05:21:52', '2018-03-22 05:21:52', 'class work1', 'class work1'),
(2, 1, '/images/1/1521715931.jpg', '/images/1/thumbnail_1521715931.jpg', 0, '2018-03-22 05:22:11', '2018-03-22 05:22:11', 'classwork2', 'classwork2'),
(3, 1, '/images/1/1521715961.jpg', '/images/1/thumbnail_1521715961.jpg', 0, '2018-03-22 05:22:41', '2018-03-22 05:22:41', 'classwork3', 'classwork3'),
(7, 4, '/images/4/1522060843.png', '/images/4/thumbnail_1522060843.png', 0, '2018-03-26 05:10:43', '2018-03-26 05:10:43', 'NULL', 'jh'),
(9, 6, '/images/6/1522739688.jpg', '/images/6/thumbnail_1522739688.jpg', 0, '2018-04-03 01:44:48', '2018-04-03 01:44:48', 'NULL', 'Group Discussion');

-- --------------------------------------------------------

--
-- Table structure for table `invitations`
--

DROP TABLE IF EXISTS `invitations`;
CREATE TABLE IF NOT EXISTS `invitations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_detail_id` int(11) NOT NULL,
  `invitaion_token` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invitations`
--

INSERT INTO `invitations` (`id`, `parent_detail_id`, `invitaion_token`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'fjLESXQE7XfhIhXZ', 2, '2018-03-22 05:19:27', '2018-03-22 05:20:40'),
(2, 2, 'WmlmWXvCzGaHzqu5', 1, '2018-03-22 05:26:57', '2018-03-27 02:15:24'),
(3, 3, 'qVE1PRvvpMFwfSUQ', 2, '2018-03-26 05:06:43', '2018-03-26 05:07:43'),
(4, 6, 'ybPnru8Knd2L8TkI', 2, '2018-04-03 01:12:36', '2018-04-03 01:13:03'),
(5, 7, 'zJjD5HSBxjt73ZVZ', 2, '2018-04-03 01:26:30', '2018-04-03 01:28:28'),
(6, 8, 'ee2Rx5csx1Gso5u6', 1, '2018-04-03 02:06:24', '2018-04-03 02:06:24');

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

DROP TABLE IF EXISTS `menus`;
CREATE TABLE IF NOT EXISTS `menus` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `menus_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

DROP TABLE IF EXISTS `menu_items`;
CREATE TABLE IF NOT EXISTS `menu_items` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `menu_id` int(10) UNSIGNED DEFAULT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `target` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '_self',
  `icon_class` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `order` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `route` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parameters` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `menu_items_menu_id_foreign` (`menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
) ENGINE=InnoDB AUTO_INCREMENT=109 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(79, '2014_10_12_000000_create_users_table', 1),
(80, '2014_10_12_100000_create_password_resets_table', 1),
(81, '2016_01_01_000000_add_voyager_user_fields', 1),
(82, '2016_01_01_000000_create_data_types_table', 1),
(83, '2016_01_01_000000_create_pages_table', 1),
(84, '2016_01_01_000000_create_posts_table', 1),
(85, '2016_02_15_204651_create_categories_table', 1),
(86, '2016_05_19_173453_create_menu_table', 1),
(87, '2016_10_21_190000_create_roles_table', 1),
(88, '2016_10_21_190000_create_settings_table', 1),
(89, '2016_11_30_135954_create_permission_table', 1),
(90, '2016_11_30_141208_create_permission_role_table', 1),
(91, '2016_12_26_201236_data_types__add__server_side', 1),
(92, '2017_01_13_000000_add_route_to_menu_items_table', 1),
(93, '2017_01_14_005015_create_translations_table', 1),
(94, '2017_01_15_000000_add_permission_group_id_to_permissions_table', 1),
(95, '2017_01_15_000000_create_permission_groups_table', 1),
(96, '2017_01_15_000000_make_table_name_nullable_in_permissions_table', 1),
(97, '2017_03_06_000000_add_controller_to_data_types_table', 1),
(98, '2017_04_11_000000_alter_post_nullable_fields_table', 1),
(99, '2017_04_21_000000_add_order_to_data_rows_table', 1),
(100, '2017_07_05_210000_add_policyname_to_data_types_table', 1),
(101, '2017_08_05_000000_add_group_to_settings_table', 1),
(102, '2018_02_16_022919_create_classrooms_table', 1),
(103, '2018_02_16_022939_create_teacher_classroom_relations_table', 1),
(104, '2018_02_16_023839_classroom_teacher', 1),
(105, '2018_02_18_041325_create_students_table', 2),
(106, '2018_02_18_061313_create_parent_details_table', 2),
(107, '2018_02_18_061327_create_galleries_table', 2),
(108, '2018_02_18_061338_create_invitations_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `author_id` int(11) NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `excerpt` text COLLATE utf8mb4_unicode_ci,
  `body` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci,
  `meta_keywords` text COLLATE utf8mb4_unicode_ci,
  `status` enum('ACTIVE','INACTIVE') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'INACTIVE',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `pages_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `parent_details`
--

DROP TABLE IF EXISTS `parent_details`;
CREATE TABLE IF NOT EXISTS `parent_details` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `relation` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `student_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `parent_details`
--

INSERT INTO `parent_details` (`id`, `first_name`, `last_name`, `email`, `relation`, `student_id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'Thomas', 'Joseph', 'jessilythomas620@gmail.com', 'Dad', 1, 2, '2018-03-22 05:19:27', '2018-03-22 05:20:40'),
(2, 'Thomas', 'joseph', 'jessily.xtapps@gmail.com', 'dad', 3, NULL, '2018-03-22 05:26:57', '2018-03-22 05:26:57'),
(3, 'yhtjy', 'tyhyt', 'jessily.xtapps@gmail.com', 'hgny', 4, 3, '2018-03-26 05:06:43', '2018-03-26 05:07:42'),
(6, 'Reshma', 'R', 'reshma.xtapps1@gmail.com', 'Mom', 5, 12, '2018-04-03 01:12:36', '2018-04-03 01:12:36'),
(7, 'jessily', 'Thomas', 'jessilythomas20@gmail.com', 'Mom', 6, 1, '2018-04-03 01:26:30', '2018-04-03 01:26:30'),
(8, 'John', 'Michel', 'reshma.xtapps@gmail.com', 'Dad', 7, NULL, '2018-04-03 02:06:24', '2018-04-03 02:06:24');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
CREATE TABLE IF NOT EXISTS `permissions` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `table_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `permission_group_id` int(10) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `permissions_key_index` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permission_groups`
--

DROP TABLE IF EXISTS `permission_groups`;
CREATE TABLE IF NOT EXISTS `permission_groups` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `permission_groups_name_unique` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permission_role`
--

DROP TABLE IF EXISTS `permission_role`;
CREATE TABLE IF NOT EXISTS `permission_role` (
  `permission_id` int(10) UNSIGNED NOT NULL,
  `role_id` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`permission_id`,`role_id`),
  KEY `permission_role_permission_id_index` (`permission_id`),
  KEY `permission_role_role_id_index` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `author_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `seo_title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `excerpt` text COLLATE utf8mb4_unicode_ci,
  `body` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_description` text COLLATE utf8mb4_unicode_ci,
  `meta_keywords` text COLLATE utf8mb4_unicode_ci,
  `status` enum('PUBLISHED','DRAFT','PENDING') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'DRAFT',
  `featured` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `posts_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `display_name`, `created_at`, `updated_at`) VALUES
(1, 'user', 'Normal user', '2018-03-14 00:00:00', '2018-03-14 00:00:00'),
(2, 'admin', 'Admin', '2018-03-14 00:00:00', '2018-03-14 00:00:00'),
(3, 'parent', 'Parent User', '2018-03-14 00:00:00', '2018-03-14 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `details` text COLLATE utf8mb4_unicode_ci,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` int(11) NOT NULL DEFAULT '1',
  `group` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

DROP TABLE IF EXISTS `students`;
CREATE TABLE IF NOT EXISTS `students` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `first_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `classroom_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `first_name`, `last_name`, `classroom_id`, `created_at`, `updated_at`) VALUES
(1, 'Neethu1', 'Thomas', 1, '2018-03-22 05:17:48', '2018-03-27 04:26:46'),
(2, 'jeethu', 'joseph', 1, '2018-03-22 05:24:42', '2018-03-22 05:24:42'),
(3, 'riya', 'Thomas', 1, '2018-03-22 05:26:32', '2018-03-22 05:26:32'),
(4, 'chinnu', 'raju', 1, '2018-03-26 05:06:24', '2018-03-26 05:06:24'),
(5, 'Anan', 'Rex', 1, '2018-04-03 01:12:21', '2018-04-03 01:12:21'),
(6, 'Aby', 'mon', 1, '2018-04-03 01:25:52', '2018-04-03 01:25:52'),
(7, 'Samuel', 'john', 1, '2018-04-03 02:06:05', '2018-04-03 02:06:05');

-- --------------------------------------------------------

--
-- Table structure for table `teacher_classroom_relations`
--

DROP TABLE IF EXISTS `teacher_classroom_relations`;
CREATE TABLE IF NOT EXISTS `teacher_classroom_relations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `classroom_id` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `translations`
--

DROP TABLE IF EXISTS `translations`;
CREATE TABLE IF NOT EXISTS `translations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `table_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `column_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `foreign_key` int(10) UNSIGNED NOT NULL,
  `locale` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `translations_table_name_column_name_foreign_key_locale_unique` (`table_name`,`column_name`,`foreign_key`,`locale`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `role_id` int(11) DEFAULT NULL,
  `first_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `school_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `school_address` text COLLATE utf8mb4_unicode_ci,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT 'users/default.png',
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role_id`, `first_name`, `last_name`, `school_name`, `school_address`, `email`, `avatar`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 1, 'Jessily1', 'Thomas', 'St Joseph', 'Kochi', 'jessilythomas20@gmail.com', 'users/default.png', '$2y$10$gWUgcae89PBggjiexO5DFOq3etmXKZnlPaU17Z5gmuXeIqEQFkkJe', 'PzgCp0OvDFPjsKW2FWuSabosZ5zZxVkOzZ9p74l0cE4J0nuoFcFjXmE6GzfO', '2018-03-22 05:16:55', '2018-03-27 04:26:33'),
(2, 3, 'Thomas', 'Joseph', NULL, NULL, 'jessilythomas620@gmail.com', 'users/default.png', '$2y$10$uLFzglb0DHjdsL6TrsqdqOc4Oe.3YB176k8qpG1JwTMvKS746LL8u', 'j0BMzINEXFE2deMRg4U9Vvv0UIy9ZeaGtP19sbJEv9Ifvbh50TrWjrCjyfCU', '2018-03-22 05:20:40', '2018-03-22 05:20:40'),
(3, 3, 'yhtjy', 'tyhyt', NULL, NULL, 'jessily.xtapps@gmail.com', 'users/default.png', '$2y$10$JVN/uAIM0lzVa/FAp/eiuOKE7GUBK6/7Z.kXKeVCnARmgzuTP1jgK', 'mexpqpj3WebVnrhMZftlwPo601HpsPecP0iu75uLXb1nPGJnsvH7dMs2CJpS', '2018-03-26 05:07:42', '2018-03-26 05:07:42'),
(4, 1, 'qaz', 'wsx', 'sa', 'sd', 'jessth111@yopmail.com', 'users/default.png', '$2y$10$i.CxX3HgrgQrOYycGruTb.T0Sm6BqdLlBMi/u4Jg98ctwifVyauLK', 'WSO9ztu1OAZ8fudPKMeJMpXYJnnvWRmb5B4sOOdKGkguq8dBzE3Z6XeRRFXM', '2018-03-27 00:08:55', '2018-03-27 00:08:55'),
(5, 1, 'fr', 'fdgrv', 'swfd', 'ds', 'jessth1111@yopmail.com', 'users/default.png', '$2y$10$.4GUg1PQ8/Xg9HgGorGLMeYVIrtQtaNyLnfgpPjglF3JbkhEQcSqO', 'jlobQSBPuEFPgPi3ASH97ZFm1zQzXGVNvY17gGNYh2MRDGrutm4BXjaDQ6dk', '2018-03-27 00:17:45', '2018-03-27 00:17:45'),
(6, 1, 'jessily', 'thomas', 'sdrfs', 'sdf', 'jessth1112@yopmail.com', 'users/default.png', '$2y$10$12tQhiF0HzGj6qQHaoVxOOBm0GDImiPQU.ysGEQNSrqRyK65i6Ez.', 'O0Vrmq6KN35D5C4kjoViDHmjgzNopsEBMfjSvWnBqVDi8TrrOkeLNxYCqB5N', '2018-03-27 03:38:54', '2018-03-27 03:38:54'),
(12, 3, 'Reshma', 'R', NULL, NULL, 'reshma.xtapps1@gmail.com', 'users/default.png', '$2y$10$xwqnx5AXTi1Nqm9B/kU1O.Rly2khSdBuJ9/jrmudiJvor0YwoKje.', 'kQaEOxkH5hvabZRPHzVgpFUHc7LsjTbzwZEeouaXqLZWhCMHclS7alDFHj2I', '2018-04-03 01:09:48', '2018-04-03 01:09:48'),
(13, 1, 'Reshma', 'R', 'xtapps', 'dfgbh', 'test.xtapps@gmail.com', 'users/default.png', '$2y$10$QDfjnAmbBCWLKNuTuBsYCeSgdzupsT7s5owO00Wt.7pEOEwsASAY.', 'hpImdkCtPSv5N5lsACZc04LCgYMRYAUUeGw4TypokqcLP3XN6jsQTblWseLf', '2018-04-03 04:08:28', '2018-04-03 04:08:28'),
(14, 1, 'Reshma', 'R', 'gdfhgfj', 'hjhgjg', 'web.xtapps@gmail.com', 'users/default.png', '$2y$10$PzCtznyn..l5o5wn87NKL.x.xNpJr7yDL1UdkbkTicdWSVD./7GbK', 'HI6QKYEDn7wyb9pqkj8p0l0uhaF8o0XFPPAideubeDHU1Je6Rw9vUbFyM1hS', '2018-04-03 04:09:18', '2018-04-03 04:09:18'),
(15, 1, 'Reshma', 'R', 'cvbcv', 'vcbcv', 'demo.xtapps@gmail.com', 'users/default.png', '$2y$10$syIi3GhEX.WvlkrZuAISyORebkGcGUp/2djIpW2WBeWXT.PTXUIRi', 'y6HIl9i4eGbK0yEJsEQLoFpUeJHynqRNsxeWrV7DpbGylOissYYuxvwgxZ8I', '2018-04-03 04:10:14', '2018-04-03 04:10:14'),
(16, 1, 'Reshma', 'R', 'ghfg', 'bnb', 'test1.xtapps@gmail.com', 'users/default.png', '$2y$10$luvm81LaZ8l0NIzEH1lXhezoc7PUAUdwQfjYsGbUXuCHG/Ra84h5i', 'gI3pvkA8bm1XaSj6VLEUDesRxqgy1DtEESoQmHmOXvCkYKc8Gk4Pv0aO2aGa', '2018-04-03 04:11:15', '2018-04-03 04:11:15'),
(17, 1, 'Rex', 'Jarvis', 'ghbnb', 'nbmnm', 'reshma.1xtapps@gmail.com', 'users/default.png', '$2y$10$nk5xGnnCLq6c0p.lMMXBXuH8tR/iD/OpAu/LEpUtO1k3oDeGvX8lm', NULL, '2018-04-03 04:11:56', '2018-04-03 04:11:56'),
(18, 1, 'Reshma', 'R', 'tset school', '12456', 'demo.xtapps1@gmail.com', 'users/default.png', '$2y$10$Z6Q906f/7gTnEY9GZdBA6uyjITDLe.eGRFpBWPVeUQ6lAUf.aNmJ6', NULL, '2018-04-05 05:34:08', '2018-04-05 05:34:08');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `data_rows`
--
ALTER TABLE `data_rows`
  ADD CONSTRAINT `data_rows_data_type_id_foreign` FOREIGN KEY (`data_type_id`) REFERENCES `data_types` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD CONSTRAINT `menu_items_menu_id_foreign` FOREIGN KEY (`menu_id`) REFERENCES `menus` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `permission_role`
--
ALTER TABLE `permission_role`
  ADD CONSTRAINT `permission_role_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `permission_role_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
