-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Feb 25, 2021 at 03:04 PM
-- Server version: 8.0.22
-- PHP Version: 8.0.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sky266`
--
CREATE DATABASE IF NOT EXISTS `sky266` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `sky266`;

-- --------------------------------------------------------

--
-- Table structure for table `collection_schedules`
--

DROP TABLE IF EXISTS `collection_schedules`;
CREATE TABLE `collection_schedules` (
  `id` bigint NOT NULL,
  `schedule_name` varchar(50) NOT NULL,
  `schedule_city` varchar(40) NOT NULL,
  `schedule_date` datetime NOT NULL,
  `late_cut_off` datetime NOT NULL,
  `final_cut_off` datetime NOT NULL,
  `status` varchar(30) NOT NULL,
  `comments` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `countries_cities`
--

DROP TABLE IF EXISTS `countries_cities`;
CREATE TABLE `countries_cities` (
  `id` int NOT NULL,
  `country_name` varchar(50) NOT NULL,
  `city_name` varchar(50) NOT NULL,
  `city_abr` varchar(20) NOT NULL,
  `has_deport` varchar(5) NOT NULL,
  `is_origin` varchar(5) NOT NULL,
  `is_destination` varchar(5) NOT NULL,
  `has_collection` varchar(5) NOT NULL,
  `has_delivery` varchar(5) NOT NULL,
  `country_depot` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `other_settings`
--

DROP TABLE IF EXISTS `other_settings`;
CREATE TABLE `other_settings` (
  `id` int NOT NULL,
  `meta_key` varchar(80) NOT NULL,
  `meta_data` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `routes`
--

DROP TABLE IF EXISTS `routes`;
CREATE TABLE `routes` (
  `id` bigint NOT NULL,
  `origin_country` varchar(60) NOT NULL,
  `origin_city` varchar(60) NOT NULL,
  `dest_country` varchar(60) NOT NULL,
  `dest_city` varchar(60) NOT NULL,
  `road_costs` text NOT NULL,
  `air_costs` text NOT NULL,
  `ocean_costs` text NOT NULL,
  `road_item_costs` text NOT NULL,
  `air_item_costs` text NOT NULL,
  `ocean_item_costs` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `trips`
--

DROP TABLE IF EXISTS `trips`;
CREATE TABLE `trips` (
  `id` bigint NOT NULL,
  `trip_name` varchar(50) NOT NULL,
  `routes_ids` varchar(40) NOT NULL,
  `routes_data` text NOT NULL,
  `trip_date` datetime NOT NULL,
  `drivers` text NOT NULL,
  `city_schedules` text NOT NULL,
  `status` varchar(30) NOT NULL,
  `comments` text NOT NULL,
  `extra_info` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `wp_actionscheduler_actions`
--

DROP TABLE IF EXISTS `wp_actionscheduler_actions`;
CREATE TABLE `wp_actionscheduler_actions` (
  `action_id` bigint UNSIGNED NOT NULL,
  `hook` varchar(191) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `scheduled_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `scheduled_date_local` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `args` varchar(191) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `schedule` longtext COLLATE utf8mb4_unicode_520_ci,
  `group_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `attempts` int NOT NULL DEFAULT '0',
  `last_attempt_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `last_attempt_local` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `claim_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `extended_args` varchar(8000) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_actionscheduler_claims`
--

DROP TABLE IF EXISTS `wp_actionscheduler_claims`;
CREATE TABLE `wp_actionscheduler_claims` (
  `claim_id` bigint UNSIGNED NOT NULL,
  `date_created_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_actionscheduler_groups`
--

DROP TABLE IF EXISTS `wp_actionscheduler_groups`;
CREATE TABLE `wp_actionscheduler_groups` (
  `group_id` bigint UNSIGNED NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_actionscheduler_logs`
--

DROP TABLE IF EXISTS `wp_actionscheduler_logs`;
CREATE TABLE `wp_actionscheduler_logs` (
  `log_id` bigint UNSIGNED NOT NULL,
  `action_id` bigint UNSIGNED NOT NULL,
  `message` text COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `log_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `log_date_local` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_commentmeta`
--

DROP TABLE IF EXISTS `wp_commentmeta`;
CREATE TABLE `wp_commentmeta` (
  `meta_id` bigint UNSIGNED NOT NULL,
  `comment_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `meta_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `meta_value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_comments`
--

DROP TABLE IF EXISTS `wp_comments`;
CREATE TABLE `wp_comments` (
  `comment_ID` bigint UNSIGNED NOT NULL,
  `comment_post_ID` bigint UNSIGNED NOT NULL DEFAULT '0',
  `comment_author` tinytext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `comment_author_email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `comment_author_url` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `comment_author_IP` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `comment_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `comment_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `comment_karma` int NOT NULL DEFAULT '0',
  `comment_approved` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '1',
  `comment_agent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `comment_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'comment',
  `comment_parent` bigint UNSIGNED NOT NULL DEFAULT '0',
  `user_id` bigint UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_cp_wp_calculated_fields_discount_codes`
--

DROP TABLE IF EXISTS `wp_cp_wp_calculated_fields_discount_codes`;
CREATE TABLE `wp_cp_wp_calculated_fields_discount_codes` (
  `id` mediumint NOT NULL,
  `form_id` mediumint NOT NULL DEFAULT '1',
  `code` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `discount` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `expires` datetime DEFAULT NULL,
  `availability` int UNSIGNED NOT NULL DEFAULT '0',
  `used` int UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_cp_wp_calculated_fields_posts`
--

DROP TABLE IF EXISTS `wp_cp_wp_calculated_fields_posts`;
CREATE TABLE `wp_cp_wp_calculated_fields_posts` (
  `id` mediumint NOT NULL,
  `formid` int NOT NULL,
  `time` datetime DEFAULT NULL,
  `ipaddr` varchar(41) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `notifyto` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `data` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `paypal_post` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `paid` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_cp_wp_calculated_fields_revision`
--

DROP TABLE IF EXISTS `wp_cp_wp_calculated_fields_revision`;
CREATE TABLE `wp_cp_wp_calculated_fields_revision` (
  `id` mediumint NOT NULL,
  `formid` mediumint NOT NULL,
  `time` datetime DEFAULT NULL,
  `revision` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_cp_wp_calculated_fields_settings`
--

DROP TABLE IF EXISTS `wp_cp_wp_calculated_fields_settings`;
CREATE TABLE `wp_cp_wp_calculated_fields_settings` (
  `id` mediumint NOT NULL,
  `form_name` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `form_structure` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `fp_from_email` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `fp_destination_emails` text COLLATE utf8mb4_unicode_ci,
  `fp_subject` text COLLATE utf8mb4_unicode_ci,
  `fp_inc_additional_info` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `fp_return_page` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `fp_message` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `fp_emailformat` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `cu_enable_copy_to_user` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `cu_user_email_field` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `cu_subject` text COLLATE utf8mb4_unicode_ci,
  `cu_message` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `cu_emailformat` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `fp_emailfrommethod` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `enable_paypal_option_yes` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `enable_paypal_option_no` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `vs_use_validation` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `vs_text_is_required` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `vs_text_is_email` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `vs_text_datemmddyyyy` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `vs_text_dateddmmyyyy` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `vs_text_number` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `vs_text_digits` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `vs_text_max` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `vs_text_min` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `vs_text_submitbtn` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `vs_text_previousbtn` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `vs_text_nextbtn` varchar(250) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `vs_all_texts` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `enable_paypal` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `enable_submit` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `paypal_notiemails` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `paypal_email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `request_cost` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `paypal_product_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `currency` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `paypal_language` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `paypal_mode` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `paypal_recurrent` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `paypal_recurrent_setup` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `paypal_recurrent_setup_days` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `paypal_recurrent_times` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `paypal_recurrent_times_field` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `paypal_identify_prices` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `paypal_zero_payment` varchar(10) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `paypal_base_amount` varchar(250) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paypal_address` tinyint NOT NULL DEFAULT '1',
  `cv_enable_captcha` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `cv_width` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `cv_height` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `cv_chars` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `cv_font` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `cv_min_font_size` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `cv_max_font_size` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `cv_noise` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `cv_noise_length` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `cv_background` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `cv_border` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `cv_text_enter_valid_captcha` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `cache` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_employees`
--

DROP TABLE IF EXISTS `wp_employees`;
CREATE TABLE `wp_employees` (
  `employee_id` mediumint UNSIGNED NOT NULL,
  `employee_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `employee_title` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_links`
--

DROP TABLE IF EXISTS `wp_links`;
CREATE TABLE `wp_links` (
  `link_id` bigint UNSIGNED NOT NULL,
  `link_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `link_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `link_image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `link_target` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `link_description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `link_visible` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'Y',
  `link_owner` bigint UNSIGNED NOT NULL DEFAULT '1',
  `link_rating` int NOT NULL DEFAULT '0',
  `link_updated` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `link_rel` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `link_notes` mediumtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `link_rss` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_options`
--

DROP TABLE IF EXISTS `wp_options`;
CREATE TABLE `wp_options` (
  `option_id` bigint UNSIGNED NOT NULL,
  `option_name` varchar(191) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `option_value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `autoload` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'yes'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_postmeta`
--

DROP TABLE IF EXISTS `wp_postmeta`;
CREATE TABLE `wp_postmeta` (
  `meta_id` bigint UNSIGNED NOT NULL,
  `post_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `meta_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `meta_value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_posts`
--

DROP TABLE IF EXISTS `wp_posts`;
CREATE TABLE `wp_posts` (
  `ID` bigint UNSIGNED NOT NULL,
  `post_author` bigint UNSIGNED NOT NULL DEFAULT '0',
  `post_date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_date_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `post_title` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `post_excerpt` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `post_status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'publish',
  `comment_status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'open',
  `ping_status` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'open',
  `post_password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `post_name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `to_ping` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `pinged` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `post_modified` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_modified_gmt` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `post_content_filtered` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `post_parent` bigint UNSIGNED NOT NULL DEFAULT '0',
  `guid` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `menu_order` int NOT NULL DEFAULT '0',
  `post_type` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT 'post',
  `post_mime_type` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `comment_count` bigint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_spbc_auth_logs`
--

DROP TABLE IF EXISTS `wp_spbc_auth_logs`;
CREATE TABLE `wp_spbc_auth_logs` (
  `id` int NOT NULL,
  `datetime` datetime NOT NULL,
  `user_login` varchar(60) NOT NULL,
  `event` varchar(32) NOT NULL,
  `page` varchar(500) DEFAULT NULL,
  `page_time` varchar(10) DEFAULT NULL,
  `blog_id` int NOT NULL,
  `auth_ip` varchar(50) DEFAULT NULL,
  `role` varchar(64) DEFAULT NULL,
  `user_agent` varchar(1024) DEFAULT NULL,
  `browser_sign` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `wp_spbc_backuped_files`
--

DROP TABLE IF EXISTS `wp_spbc_backuped_files`;
CREATE TABLE `wp_spbc_backuped_files` (
  `id` int UNSIGNED NOT NULL,
  `backup_id` int UNSIGNED NOT NULL,
  `real_path` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL,
  `back_path` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_spbc_backups`
--

DROP TABLE IF EXISTS `wp_spbc_backups`;
CREATE TABLE `wp_spbc_backups` (
  `backup_id` int UNSIGNED NOT NULL,
  `type` enum('FILE','ALL','SIGNATURES') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'FILE',
  `datetime` datetime NOT NULL,
  `status` enum('PROCESSING','BACKUPED','ROLLBACK','ROLLBACKED','STOPPED') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'PROCESSING'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_spbc_firewall_data`
--

DROP TABLE IF EXISTS `wp_spbc_firewall_data`;
CREATE TABLE `wp_spbc_firewall_data` (
  `id` char(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `network` int UNSIGNED NOT NULL DEFAULT '0',
  `mask` int UNSIGNED NOT NULL DEFAULT '0',
  `country_code` char(2) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` tinyint DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_spbc_firewall_logs`
--

DROP TABLE IF EXISTS `wp_spbc_firewall_logs`;
CREATE TABLE `wp_spbc_firewall_logs` (
  `entry_id` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_entry` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` enum('PASS','PASS_BY_TRUSTED_NETWORK','PASS_BY_WHITELIST','DENY','DENY_BY_NETWORK','DENY_BY_DOS','DENY_BY_WAF_XSS','DENY_BY_WAF_SQL','DENY_BY_WAF_FILE','DENY_BY_WAF_EXPLOIT','DENY_BY_BFP') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pattern` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `requests` int DEFAULT NULL,
  `page_url` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `request_method` varchar(5) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `x_forwarded_for` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `http_user_agent` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `entry_timestamp` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_spbc_firewall__personal_countries`
--

DROP TABLE IF EXISTS `wp_spbc_firewall__personal_countries`;
CREATE TABLE `wp_spbc_firewall__personal_countries` (
  `id` int NOT NULL,
  `country_code` char(2) NOT NULL,
  `status` tinyint NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `wp_spbc_firewall__personal_ips`
--

DROP TABLE IF EXISTS `wp_spbc_firewall__personal_ips`;
CREATE TABLE `wp_spbc_firewall__personal_ips` (
  `id` int NOT NULL,
  `network` int UNSIGNED NOT NULL DEFAULT '0',
  `mask` int UNSIGNED NOT NULL DEFAULT '0',
  `status` tinyint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `wp_spbc_scan_frontend`
--

DROP TABLE IF EXISTS `wp_spbc_scan_frontend`;
CREATE TABLE `wp_spbc_scan_frontend` (
  `page_id` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL,
  `url` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL,
  `dbd_found` tinyint DEFAULT NULL,
  `redirect_found` tinyint DEFAULT NULL,
  `signature` tinyint DEFAULT NULL,
  `bad_code` tinyint DEFAULT NULL,
  `weak_spots` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_spbc_scan_links_logs`
--

DROP TABLE IF EXISTS `wp_spbc_scan_links_logs`;
CREATE TABLE `wp_spbc_scan_links_logs` (
  `link_id` int NOT NULL,
  `scan_id` int NOT NULL,
  `domain` tinytext COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(2048) COLLATE utf8mb4_unicode_ci NOT NULL,
  `link_text` varchar(2048) COLLATE utf8mb4_unicode_ci NOT NULL,
  `page_url` varchar(2048) COLLATE utf8mb4_unicode_ci NOT NULL,
  `spam_active` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_spbc_scan_results`
--

DROP TABLE IF EXISTS `wp_spbc_scan_results`;
CREATE TABLE `wp_spbc_scan_results` (
  `path` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` int NOT NULL,
  `perms` int NOT NULL,
  `mtime` int NOT NULL,
  `source_type` enum('CORE','PLUGIN','THEME') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `source` varchar(300) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `source_status` set('UP_TO_DATE','OUTDATED','NOT_IN_DIRECTORY','UNKNOWN') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `version` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `checked` enum('NO','YES','YES_SIGNATURE','YES_HEURISTIC') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'NO',
  `status` enum('UNKNOWN','OK','APROVED','MODIFIED','INFECTED','QUARANTINED') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'UNKNOWN',
  `severity` enum('CRITICAL','DANGER','SUSPICIOUS','NONE') COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `weak_spots` varchar(2048) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `difference` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_sent` int DEFAULT NULL,
  `fast_hash` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `full_hash` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `real_full_hash` varchar(32) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `previous_state` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `q_path` varchar(1024) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `q_time` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_spbc_scan_signatures`
--

DROP TABLE IF EXISTS `wp_spbc_scan_signatures`;
CREATE TABLE `wp_spbc_scan_signatures` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `body` varchar(512) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` enum('FILE','CODE_PHP','CODE_HTML','CODE_JS','WAF_RULE') COLLATE utf8mb4_unicode_ci NOT NULL,
  `attack_type` set('SQL_INJECTION','XSS','MALWARE','EXPLOIT') COLLATE utf8mb4_unicode_ci NOT NULL,
  `submitted` datetime NOT NULL,
  `cci` text COLLATE utf8mb4_unicode_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_spbc_traffic_control_logs`
--

DROP TABLE IF EXISTS `wp_spbc_traffic_control_logs`;
CREATE TABLE `wp_spbc_traffic_control_logs` (
  `id` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `log_type` tinyint DEFAULT NULL,
  `ip` varchar(40) COLLATE utf8mb4_unicode_ci NOT NULL,
  `entries` int DEFAULT '0',
  `interval_start` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_termmeta`
--

DROP TABLE IF EXISTS `wp_termmeta`;
CREATE TABLE `wp_termmeta` (
  `meta_id` bigint UNSIGNED NOT NULL,
  `term_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `meta_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `meta_value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_terms`
--

DROP TABLE IF EXISTS `wp_terms`;
CREATE TABLE `wp_terms` (
  `term_id` bigint UNSIGNED NOT NULL,
  `name` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `slug` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `term_group` bigint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_term_relationships`
--

DROP TABLE IF EXISTS `wp_term_relationships`;
CREATE TABLE `wp_term_relationships` (
  `object_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `term_taxonomy_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `term_order` int NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_term_taxonomy`
--

DROP TABLE IF EXISTS `wp_term_taxonomy`;
CREATE TABLE `wp_term_taxonomy` (
  `term_taxonomy_id` bigint UNSIGNED NOT NULL,
  `term_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `taxonomy` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `parent` bigint UNSIGNED NOT NULL DEFAULT '0',
  `count` bigint NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_um_metadata`
--

DROP TABLE IF EXISTS `wp_um_metadata`;
CREATE TABLE `wp_um_metadata` (
  `umeta_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `um_key` varchar(255) COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `um_value` longtext COLLATE utf8mb4_unicode_520_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_usermeta`
--

DROP TABLE IF EXISTS `wp_usermeta`;
CREATE TABLE `wp_usermeta` (
  `umeta_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL DEFAULT '0',
  `meta_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci DEFAULT NULL,
  `meta_value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_users`
--

DROP TABLE IF EXISTS `wp_users`;
CREATE TABLE `wp_users` (
  `ID` bigint UNSIGNED NOT NULL,
  `user_login` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `user_pass` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `user_nicename` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `user_email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `user_url` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `user_registered` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `user_activation_key` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT '',
  `user_status` int NOT NULL DEFAULT '0',
  `display_name` varchar(250) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_520_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wp_wpmailsmtp_tasks_meta`
--

DROP TABLE IF EXISTS `wp_wpmailsmtp_tasks_meta`;
CREATE TABLE `wp_wpmailsmtp_tasks_meta` (
  `id` bigint NOT NULL,
  `action` varchar(255) COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `data` longtext COLLATE utf8mb4_unicode_520_ci NOT NULL,
  `date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `collection_schedules`
--
ALTER TABLE `collection_schedules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `countries_cities`
--
ALTER TABLE `countries_cities`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `routes`
--
ALTER TABLE `routes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `trips`
--
ALTER TABLE `trips`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_actionscheduler_actions`
--
ALTER TABLE `wp_actionscheduler_actions`
  ADD PRIMARY KEY (`action_id`),
  ADD KEY `hook` (`hook`),
  ADD KEY `status` (`status`),
  ADD KEY `scheduled_date_gmt` (`scheduled_date_gmt`),
  ADD KEY `args` (`args`),
  ADD KEY `group_id` (`group_id`),
  ADD KEY `last_attempt_gmt` (`last_attempt_gmt`),
  ADD KEY `claim_id` (`claim_id`);

--
-- Indexes for table `wp_actionscheduler_claims`
--
ALTER TABLE `wp_actionscheduler_claims`
  ADD PRIMARY KEY (`claim_id`),
  ADD KEY `date_created_gmt` (`date_created_gmt`);

--
-- Indexes for table `wp_actionscheduler_groups`
--
ALTER TABLE `wp_actionscheduler_groups`
  ADD PRIMARY KEY (`group_id`),
  ADD KEY `slug` (`slug`(191));

--
-- Indexes for table `wp_actionscheduler_logs`
--
ALTER TABLE `wp_actionscheduler_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `action_id` (`action_id`),
  ADD KEY `log_date_gmt` (`log_date_gmt`);

--
-- Indexes for table `wp_commentmeta`
--
ALTER TABLE `wp_commentmeta`
  ADD PRIMARY KEY (`meta_id`),
  ADD KEY `comment_id` (`comment_id`),
  ADD KEY `meta_key` (`meta_key`(191));

--
-- Indexes for table `wp_comments`
--
ALTER TABLE `wp_comments`
  ADD PRIMARY KEY (`comment_ID`),
  ADD KEY `comment_post_ID` (`comment_post_ID`),
  ADD KEY `comment_approved_date_gmt` (`comment_approved`,`comment_date_gmt`),
  ADD KEY `comment_date_gmt` (`comment_date_gmt`),
  ADD KEY `comment_parent` (`comment_parent`),
  ADD KEY `comment_author_email` (`comment_author_email`(10));

--
-- Indexes for table `wp_cp_wp_calculated_fields_discount_codes`
--
ALTER TABLE `wp_cp_wp_calculated_fields_discount_codes`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `wp_cp_wp_calculated_fields_posts`
--
ALTER TABLE `wp_cp_wp_calculated_fields_posts`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `wp_cp_wp_calculated_fields_revision`
--
ALTER TABLE `wp_cp_wp_calculated_fields_revision`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `wp_cp_wp_calculated_fields_settings`
--
ALTER TABLE `wp_cp_wp_calculated_fields_settings`
  ADD UNIQUE KEY `id` (`id`);

--
-- Indexes for table `wp_employees`
--
ALTER TABLE `wp_employees`
  ADD PRIMARY KEY (`employee_id`);

--
-- Indexes for table `wp_links`
--
ALTER TABLE `wp_links`
  ADD PRIMARY KEY (`link_id`),
  ADD KEY `link_visible` (`link_visible`);

--
-- Indexes for table `wp_options`
--
ALTER TABLE `wp_options`
  ADD PRIMARY KEY (`option_id`),
  ADD UNIQUE KEY `option_name` (`option_name`),
  ADD KEY `autoload` (`autoload`);

--
-- Indexes for table `wp_postmeta`
--
ALTER TABLE `wp_postmeta`
  ADD PRIMARY KEY (`meta_id`),
  ADD KEY `post_id` (`post_id`),
  ADD KEY `meta_key` (`meta_key`(191));

--
-- Indexes for table `wp_posts`
--
ALTER TABLE `wp_posts`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `post_name` (`post_name`(191)),
  ADD KEY `type_status_date` (`post_type`,`post_status`,`post_date`,`ID`),
  ADD KEY `post_parent` (`post_parent`),
  ADD KEY `post_author` (`post_author`);

--
-- Indexes for table `wp_spbc_auth_logs`
--
ALTER TABLE `wp_spbc_auth_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `datetime` (`datetime`,`event`);

--
-- Indexes for table `wp_spbc_backuped_files`
--
ALTER TABLE `wp_spbc_backuped_files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_spbc_backups`
--
ALTER TABLE `wp_spbc_backups`
  ADD PRIMARY KEY (`backup_id`);

--
-- Indexes for table `wp_spbc_firewall_data`
--
ALTER TABLE `wp_spbc_firewall_data`
  ADD PRIMARY KEY (`id`),
  ADD KEY `network` (`network`,`mask`);

--
-- Indexes for table `wp_spbc_firewall_logs`
--
ALTER TABLE `wp_spbc_firewall_logs`
  ADD PRIMARY KEY (`entry_id`);

--
-- Indexes for table `wp_spbc_firewall__personal_countries`
--
ALTER TABLE `wp_spbc_firewall__personal_countries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_spbc_firewall__personal_ips`
--
ALTER TABLE `wp_spbc_firewall__personal_ips`
  ADD PRIMARY KEY (`id`),
  ADD KEY `network` (`network`,`mask`);

--
-- Indexes for table `wp_spbc_scan_links_logs`
--
ALTER TABLE `wp_spbc_scan_links_logs`
  ADD PRIMARY KEY (`link_id`),
  ADD KEY `spam_active` (`spam_active`),
  ADD KEY `scan_id` (`scan_id`),
  ADD KEY `domain` (`domain`(40));

--
-- Indexes for table `wp_spbc_scan_results`
--
ALTER TABLE `wp_spbc_scan_results`
  ADD UNIQUE KEY `fast_hash` (`fast_hash`);

--
-- Indexes for table `wp_spbc_scan_signatures`
--
ALTER TABLE `wp_spbc_scan_signatures`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `wp_spbc_traffic_control_logs`
--
ALTER TABLE `wp_spbc_traffic_control_logs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `wp_termmeta`
--
ALTER TABLE `wp_termmeta`
  ADD PRIMARY KEY (`meta_id`),
  ADD KEY `term_id` (`term_id`),
  ADD KEY `meta_key` (`meta_key`(191));

--
-- Indexes for table `wp_terms`
--
ALTER TABLE `wp_terms`
  ADD PRIMARY KEY (`term_id`),
  ADD KEY `slug` (`slug`(191)),
  ADD KEY `name` (`name`(191));

--
-- Indexes for table `wp_term_relationships`
--
ALTER TABLE `wp_term_relationships`
  ADD PRIMARY KEY (`object_id`,`term_taxonomy_id`),
  ADD KEY `term_taxonomy_id` (`term_taxonomy_id`);

--
-- Indexes for table `wp_term_taxonomy`
--
ALTER TABLE `wp_term_taxonomy`
  ADD PRIMARY KEY (`term_taxonomy_id`),
  ADD UNIQUE KEY `term_id_taxonomy` (`term_id`,`taxonomy`),
  ADD KEY `taxonomy` (`taxonomy`);

--
-- Indexes for table `wp_um_metadata`
--
ALTER TABLE `wp_um_metadata`
  ADD PRIMARY KEY (`umeta_id`),
  ADD KEY `user_id_indx` (`user_id`),
  ADD KEY `meta_key_indx` (`um_key`),
  ADD KEY `meta_value_indx` (`um_value`(191));

--
-- Indexes for table `wp_usermeta`
--
ALTER TABLE `wp_usermeta`
  ADD PRIMARY KEY (`umeta_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `meta_key` (`meta_key`(191));

--
-- Indexes for table `wp_users`
--
ALTER TABLE `wp_users`
  ADD PRIMARY KEY (`ID`),
  ADD KEY `user_login_key` (`user_login`),
  ADD KEY `user_nicename` (`user_nicename`),
  ADD KEY `user_email` (`user_email`);

--
-- Indexes for table `wp_wpmailsmtp_tasks_meta`
--
ALTER TABLE `wp_wpmailsmtp_tasks_meta`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `collection_schedules`
--
ALTER TABLE `collection_schedules`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `countries_cities`
--
ALTER TABLE `countries_cities`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `routes`
--
ALTER TABLE `routes`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trips`
--
ALTER TABLE `trips`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_actionscheduler_actions`
--
ALTER TABLE `wp_actionscheduler_actions`
  MODIFY `action_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_actionscheduler_claims`
--
ALTER TABLE `wp_actionscheduler_claims`
  MODIFY `claim_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_actionscheduler_groups`
--
ALTER TABLE `wp_actionscheduler_groups`
  MODIFY `group_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_actionscheduler_logs`
--
ALTER TABLE `wp_actionscheduler_logs`
  MODIFY `log_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_commentmeta`
--
ALTER TABLE `wp_commentmeta`
  MODIFY `meta_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_comments`
--
ALTER TABLE `wp_comments`
  MODIFY `comment_ID` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_cp_wp_calculated_fields_discount_codes`
--
ALTER TABLE `wp_cp_wp_calculated_fields_discount_codes`
  MODIFY `id` mediumint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_cp_wp_calculated_fields_posts`
--
ALTER TABLE `wp_cp_wp_calculated_fields_posts`
  MODIFY `id` mediumint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_cp_wp_calculated_fields_revision`
--
ALTER TABLE `wp_cp_wp_calculated_fields_revision`
  MODIFY `id` mediumint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_cp_wp_calculated_fields_settings`
--
ALTER TABLE `wp_cp_wp_calculated_fields_settings`
  MODIFY `id` mediumint NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_employees`
--
ALTER TABLE `wp_employees`
  MODIFY `employee_id` mediumint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_links`
--
ALTER TABLE `wp_links`
  MODIFY `link_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_options`
--
ALTER TABLE `wp_options`
  MODIFY `option_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_postmeta`
--
ALTER TABLE `wp_postmeta`
  MODIFY `meta_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_posts`
--
ALTER TABLE `wp_posts`
  MODIFY `ID` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_spbc_auth_logs`
--
ALTER TABLE `wp_spbc_auth_logs`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_spbc_backuped_files`
--
ALTER TABLE `wp_spbc_backuped_files`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_spbc_backups`
--
ALTER TABLE `wp_spbc_backups`
  MODIFY `backup_id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_spbc_firewall__personal_countries`
--
ALTER TABLE `wp_spbc_firewall__personal_countries`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_spbc_firewall__personal_ips`
--
ALTER TABLE `wp_spbc_firewall__personal_ips`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_spbc_scan_links_logs`
--
ALTER TABLE `wp_spbc_scan_links_logs`
  MODIFY `link_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_spbc_scan_signatures`
--
ALTER TABLE `wp_spbc_scan_signatures`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_termmeta`
--
ALTER TABLE `wp_termmeta`
  MODIFY `meta_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_terms`
--
ALTER TABLE `wp_terms`
  MODIFY `term_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_term_taxonomy`
--
ALTER TABLE `wp_term_taxonomy`
  MODIFY `term_taxonomy_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_um_metadata`
--
ALTER TABLE `wp_um_metadata`
  MODIFY `umeta_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_usermeta`
--
ALTER TABLE `wp_usermeta`
  MODIFY `umeta_id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_users`
--
ALTER TABLE `wp_users`
  MODIFY `ID` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wp_wpmailsmtp_tasks_meta`
--
ALTER TABLE `wp_wpmailsmtp_tasks_meta`
  MODIFY `id` bigint NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
