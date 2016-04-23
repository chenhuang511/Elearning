-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Apr 23, 2016 at 03:58 AM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `userfrosting`
--

-- --------------------------------------------------------

--
-- Table structure for table `uf_authorize_group`
--

CREATE TABLE IF NOT EXISTS `uf_authorize_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(10) unsigned NOT NULL,
  `hook` varchar(200) NOT NULL COMMENT 'A code that references a specific action or URI that the group has access to.',
  `conditions` text NOT NULL COMMENT 'The conditions under which members of this group have access to this hook.',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

--
-- Dumping data for table `uf_authorize_group`
--

INSERT INTO `uf_authorize_group` (`id`, `group_id`, `hook`, `conditions`) VALUES
(1, 1, 'uri_dashboard', 'always()'),
(2, 2, 'uri_dashboard', 'always()'),
(3, 2, 'uri_users', 'always()'),
(4, 1, 'uri_account_settings', 'always()'),
(5, 1, 'update_account_setting', 'equals(self.id, user.id)&&in(property,["email","locale","password"])'),
(6, 2, 'update_account_setting', '!in_group(user.id,2)&&in(property,["email","display_name","title","locale","flag_password_reset","flag_enabled"])'),
(7, 2, 'view_account_setting', 'in(property,["user_name","email","display_name","title","locale","flag_enabled","groups","primary_group_id"])'),
(8, 2, 'delete_account', '!in_group(user.id,2)'),
(9, 2, 'create_account', 'always()');

-- --------------------------------------------------------

--
-- Table structure for table `uf_authorize_user`
--

CREATE TABLE IF NOT EXISTS `uf_authorize_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `hook` varchar(200) NOT NULL COMMENT 'A code that references a specific action or URI that the user has access to.',
  `conditions` text NOT NULL COMMENT 'The conditions under which the user has access to this action.',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `uf_configuration`
--

CREATE TABLE IF NOT EXISTS `uf_configuration` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `plugin` varchar(50) NOT NULL COMMENT 'The name of the plugin that manages this setting (set to ''userfrosting'' for core settings)',
  `name` varchar(150) NOT NULL COMMENT 'The name of the setting.',
  `value` longtext NOT NULL COMMENT 'The current value of the setting.',
  `description` text NOT NULL COMMENT 'A brief description of this setting.',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='A configuration table, mapping global configuration options to their values.' AUTO_INCREMENT=20 ;

--
-- Dumping data for table `uf_configuration`
--

INSERT INTO `uf_configuration` (`id`, `plugin`, `name`, `value`, `description`) VALUES
(1, 'userfrosting', 'site_title', 'UserFrosting', 'The title of the site.  By default, displayed in the title tag, as well as the upper left corner of every user page.'),
(2, 'userfrosting', 'admin_email', 'admin@userfrosting.com', 'The administrative email for the site.  Automated emails, such as verification emails and password reset links, will come from this address.'),
(3, 'userfrosting', 'email_login', '1', 'Specify whether users can login via email address or username instead of just username.'),
(4, 'userfrosting', 'can_register', '1', 'Specify whether public registration of new accounts is enabled.  Enable if you have a service that users can sign up for, disable if you only want accounts to be created by you or an admin.'),
(5, 'userfrosting', 'enable_captcha', '1', 'Specify whether new users must complete a captcha code when registering for an account.'),
(6, 'userfrosting', 'require_activation', '1', 'Specify whether email verification is required for newly registered accounts.  Accounts created by another user never need to be verified.'),
(7, 'userfrosting', 'resend_activation_threshold', '0', 'The time, in seconds, that a user must wait before requesting that the account verification email be resent.'),
(8, 'userfrosting', 'reset_password_timeout', '10800', 'The time, in seconds, before a user''s password reset token expires.'),
(9, 'userfrosting', 'create_password_expiration', '86400', 'The time, in seconds, before a new user''s password creation token expires.'),
(10, 'userfrosting', 'default_locale', 'en_US', 'The default language for newly registered users.'),
(11, 'userfrosting', 'guest_theme', 'root', 'The template theme to use for unauthenticated (guest) users.'),
(12, 'userfrosting', 'minify_css', '0', 'Specify whether to use concatenated, minified CSS (production) or raw CSS includes (dev).'),
(13, 'userfrosting', 'minify_js', '0', 'Specify whether to use concatenated, minified JS (production) or raw JS includes (dev).'),
(14, 'userfrosting', 'version', '0.3.1.12', 'The current version of UserFrosting.'),
(15, 'userfrosting', 'author', 'Alex Weissman', 'The author of the site.  Will be used in the site''s author meta tag.'),
(16, 'userfrosting', 'show_terms_on_register', '1', 'Specify whether or not to show terms and conditions when registering.'),
(17, 'userfrosting', 'site_location', 'The State of Indiana', 'The nation or state in which legal jurisdiction for this site falls.'),
(18, 'userfrosting', 'install_status', 'complete', ''),
(19, 'userfrosting', 'root_account_config_token', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `uf_group`
--

CREATE TABLE IF NOT EXISTS `uf_group` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Specifies whether this permission is a default setting for new accounts.',
  `can_delete` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Specifies whether this permission can be deleted from the control panel.',
  `theme` varchar(100) NOT NULL DEFAULT 'default' COMMENT 'The theme assigned to primary users in this group.',
  `landing_page` varchar(200) NOT NULL DEFAULT 'dashboard' COMMENT 'The page to take primary members to when they first log in.',
  `new_user_title` varchar(200) NOT NULL DEFAULT 'New User' COMMENT 'The default title to assign to new primary users.',
  `icon` varchar(100) NOT NULL DEFAULT 'fa fa-user' COMMENT 'The icon representing primary users in this group.',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `uf_group`
--

INSERT INTO `uf_group` (`id`, `name`, `is_default`, `can_delete`, `theme`, `landing_page`, `new_user_title`, `icon`) VALUES
(1, 'User', 2, 0, 'default', 'dashboard', 'New User', 'fa fa-user'),
(2, 'Administrator', 0, 0, 'nyx', 'dashboard', 'Brood Spawn', 'fa fa-flag'),
(4, 'test', 0, 1, 'default', 'dashboard', 'New User', 'fa fa-user'),
(5, 'dddddddddddddddd', 0, 1, 'default', 'dashboard', 'New User', 'fa fa-user');

-- --------------------------------------------------------

--
-- Table structure for table `uf_group_user`
--

CREATE TABLE IF NOT EXISTS `uf_group_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `group_id` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COMMENT='Maps users to their group(s)' AUTO_INCREMENT=5 ;

--
-- Dumping data for table `uf_group_user`
--

INSERT INTO `uf_group_user` (`id`, `user_id`, `group_id`) VALUES
(1, 1, 1),
(2, 2, 1),
(4, 4, 1);

-- --------------------------------------------------------

--
-- Table structure for table `uf_user`
--

CREATE TABLE IF NOT EXISTS `uf_user` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_name` varchar(50) NOT NULL,
  `display_name` varchar(50) NOT NULL,
  `email` varchar(150) NOT NULL,
  `title` varchar(150) NOT NULL,
  `locale` varchar(10) NOT NULL DEFAULT 'en_US' COMMENT 'The language and locale to use for this user.',
  `primary_group_id` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'The id of this user''s primary group.',
  `secret_token` varchar(32) NOT NULL DEFAULT '' COMMENT 'The current one-time use token for various user activities confirmed via email.',
  `flag_verified` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Set to ''1'' if the user has verified their account via email, ''0'' otherwise.',
  `flag_enabled` tinyint(1) NOT NULL DEFAULT '1' COMMENT 'Set to ''1'' if the user''s account is currently enabled, ''0'' otherwise.  Disabled accounts cannot be logged in to, but they retain all of their data and settings.',
  `flag_password_reset` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Set to ''1'' if the user has an outstanding password reset request, ''0'' otherwise.',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `uf_user`
--

INSERT INTO `uf_user` (`id`, `user_name`, `display_name`, `email`, `title`, `locale`, `primary_group_id`, `secret_token`, `flag_verified`, `flag_enabled`, `flag_password_reset`, `created_at`, `updated_at`, `password`) VALUES
(1, 'admin', 'MinhNguyen', 'ndminh9x@gmail.com', '333', 'en_US', 1, '', 1, 1, 0, '2016-03-22 21:40:52', '2016-03-31 20:56:05', '$2y$10$5ivhbL5FzYw3aynoC.6fauYTTVx0UjwCfa.LiUqp5po1bsjtQa6Yu'),
(2, 'usser1', 'usser1', 'usser1@gmail.com', '333', 'en_US', 1, 'ddbae5a50295b19fdf8b4767781ff9da', 1, 0, 1, '2016-03-22 21:57:26', '2016-04-20 04:25:54', ''),
(4, 'TEsst', 'ddddddddd', 'dantri@gamil.com', 'ddddddddddddd', 'en_US', 1, '1e8e6854dc3a27f5901d5fdba2e2f631', 1, 1, 1, '2016-04-11 06:35:15', '2016-04-11 06:35:15', '');

-- --------------------------------------------------------

--
-- Table structure for table `uf_user_event`
--

CREATE TABLE IF NOT EXISTS `uf_user_event` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `event_type` varchar(255) NOT NULL COMMENT 'An identifier used to track the type of event.',
  `occurred_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=66 ;

--
-- Dumping data for table `uf_user_event`
--

INSERT INTO `uf_user_event` (`id`, `user_id`, `event_type`, `occurred_at`, `description`) VALUES
(1, 1, 'sign_up', '2016-03-23 08:40:52', 'User admin successfully registered on 2016-03-23 04:40:52.'),
(2, 1, 'sign_in', '2016-03-23 08:41:03', 'User admin signed in at 2016-03-23 04:41:03.'),
(3, 1, 'sign_in', '2016-03-23 08:55:36', 'User admin signed in at 2016-03-23 04:55:36.'),
(4, 2, 'sign_up', '2016-03-23 08:57:26', 'User usser1 was created by admin on 2016-03-23 04:57:26.'),
(5, 2, 'password_reset_request', '2016-03-23 08:57:26', 'User usser1 requested a password reset on 2016-03-23 04:57:26.'),
(6, 1, 'sign_in', '2016-03-23 08:58:58', 'User admin signed in at 2016-03-23 04:58:58.'),
(7, 1, 'sign_in', '2016-03-23 09:00:00', 'User admin signed in at 2016-03-23 05:00:00.'),
(8, 1, 'sign_in', '2016-03-25 10:47:43', 'User admin signed in at 2016-03-25 06:47:43.'),
(9, 1, 'sign_in', '2016-03-28 07:55:06', 'User admin signed in at 2016-03-28 03:55:06.'),
(10, 1, 'sign_in', '2016-03-28 08:30:16', 'User admin signed in at 2016-03-28 04:30:16.'),
(11, 1, 'sign_in', '2016-03-28 09:31:09', 'User admin signed in at 2016-03-28 05:31:09.'),
(12, 1, 'sign_in', '2016-03-29 02:40:45', 'User admin signed in at 2016-03-28 22:40:45.'),
(13, 1, 'sign_in', '2016-03-29 07:50:31', 'User admin signed in at 2016-03-29 03:50:31.'),
(14, 1, 'sign_in', '2016-03-30 01:22:54', 'User admin signed in at 2016-03-29 21:22:54.'),
(15, 1, 'sign_in', '2016-03-30 01:30:23', 'User admin signed in at 2016-03-29 21:30:23.'),
(16, 1, 'sign_in', '2016-03-30 03:01:58', 'User admin signed in at 2016-03-29 23:01:58.'),
(17, 1, 'sign_in', '2016-03-30 03:51:42', 'User admin signed in at 2016-03-29 23:51:42.'),
(18, 1, 'sign_in', '2016-03-30 09:54:00', 'User admin signed in at 2016-03-30 05:54:00.'),
(21, 1, 'sign_in', '2016-04-05 01:23:48', 'User admin signed in at 2016-04-04 21:23:48.'),
(22, 1, 'sign_in', '2016-04-05 06:48:02', 'User admin signed in at 2016-04-05 13:48:02.'),
(23, 1, 'sign_in', '2016-04-05 06:51:07', 'User admin signed in at 2016-04-05 13:51:07.'),
(24, 1, 'sign_in', '2016-04-05 08:15:32', 'User admin signed in at 2016-04-05 15:15:32.'),
(25, 1, 'sign_in', '2016-04-05 08:27:46', 'User admin signed in at 2016-04-05 15:27:46.'),
(26, 1, 'sign_in', '2016-04-05 08:28:45', 'User admin signed in at 2016-04-05 15:28:45.'),
(27, 1, 'sign_in', '2016-04-05 08:35:21', 'User admin signed in at 2016-04-05 15:35:21.'),
(28, 1, 'sign_in', '2016-04-05 08:46:21', 'User admin signed in at 2016-04-05 15:46:21.'),
(29, 1, 'sign_in', '2016-04-06 02:19:08', 'User admin signed in at 2016-04-06 09:19:08.'),
(30, 1, 'sign_in', '2016-04-06 07:07:50', 'User admin signed in at 2016-04-06 14:07:50.'),
(31, 1, 'sign_in', '2016-04-06 10:06:52', 'User admin signed in at 2016-04-06 17:06:52.'),
(32, 1, 'sign_in', '2016-04-06 10:13:12', 'User admin signed in at 2016-04-06 17:13:12.'),
(33, 1, 'sign_in', '2016-04-06 10:25:39', 'User admin signed in at 2016-04-06 17:25:39.'),
(34, 1, 'sign_in', '2016-04-07 02:42:36', 'User admin signed in at 2016-04-07 09:42:36.'),
(35, 1, 'sign_in', '2016-04-07 10:38:04', 'User admin signed in at 2016-04-07 17:38:04.'),
(36, 4, 'sign_up', '2016-04-11 06:35:15', 'User TEsst was created by admin on 2016-04-11 13:35:15.'),
(37, 4, 'password_reset_request', '2016-04-11 06:35:15', 'User TEsst requested a password reset on 2016-04-11 13:35:15.'),
(38, 1, 'sign_in', '2016-04-12 08:40:09', 'User admin signed in at 2016-04-12 15:40:09.'),
(39, 1, 'sign_in', '2016-04-14 01:25:52', 'User admin signed in at 2016-04-14 08:25:52.'),
(40, 1, 'sign_in', '2016-04-14 10:49:39', 'User admin signed in at 2016-04-14 17:49:39.'),
(41, 1, 'sign_in', '2016-04-15 03:54:14', 'User admin signed in at 2016-04-15 10:54:14.'),
(42, 1, 'sign_in', '2016-04-15 04:10:26', 'User admin signed in at 2016-04-15 11:10:26.'),
(43, 1, 'sign_in', '2016-04-15 04:12:00', 'User admin signed in at 2016-04-15 11:12:00.'),
(44, 1, 'sign_in', '2016-04-15 04:53:53', 'User admin signed in at 2016-04-15 11:53:53.'),
(45, 1, 'sign_in', '2016-04-15 04:55:11', 'User admin signed in at 2016-04-15 11:55:11.'),
(46, 1, 'sign_in', '2016-04-15 07:03:14', 'User admin signed in at 2016-04-15 14:03:14.'),
(47, 1, 'sign_in', '2016-04-15 09:58:41', 'User admin signed in at 2016-04-15 16:58:41.'),
(48, 1, 'sign_in', '2016-04-15 10:08:09', 'User admin signed in at 2016-04-15 17:08:09.'),
(49, 1, 'sign_in', '2016-04-15 10:10:29', 'User admin signed in at 2016-04-15 17:10:29.'),
(50, 1, 'sign_in', '2016-04-19 08:16:12', 'User admin signed in at 2016-04-19 15:16:12.'),
(51, 1, 'sign_in', '2016-04-19 08:42:22', 'User admin signed in at 2016-04-19 15:42:22.'),
(52, 1, 'sign_in', '2016-04-21 03:51:41', 'User admin signed in at 2016-04-21 10:51:41.'),
(53, 1, 'sign_in', '2016-04-21 03:52:55', 'User admin signed in at 2016-04-21 10:52:55.'),
(54, 1, 'sign_in', '2016-04-21 04:15:50', 'User admin signed in at 2016-04-21 11:15:50.'),
(55, 1, 'sign_in', '2016-04-21 08:57:00', 'User admin signed in at 2016-04-21 15:57:00.'),
(56, 1, 'sign_in', '2016-04-21 09:10:28', 'User admin signed in at 2016-04-21 16:10:28.'),
(57, 1, 'sign_in', '2016-04-21 09:52:51', 'User admin signed in at 2016-04-21 16:52:51.'),
(58, 1, 'sign_in', '2016-04-22 08:47:00', 'User admin signed in at 2016-04-22 15:47:00.'),
(59, 1, 'sign_in', '2016-04-22 09:19:28', 'User admin signed in at 2016-04-22 16:19:28.'),
(60, 1, 'sign_in', '2016-04-22 09:49:46', 'User admin signed in at 2016-04-22 16:49:46.'),
(61, 1, 'sign_in', '2016-04-22 09:59:14', 'User admin signed in at 2016-04-22 16:59:14.'),
(62, 1, 'sign_in', '2016-04-22 11:05:06', 'User admin signed in at 2016-04-22 18:05:06.'),
(63, 1, 'sign_in', '2016-04-22 11:13:55', 'User admin signed in at 2016-04-22 07:13:55.'),
(64, 1, 'sign_in', '2016-04-22 11:15:05', 'User admin signed in at 2016-04-22 07:15:05.'),
(65, 1, 'sign_in', '2016-04-22 11:22:10', 'User admin signed in at 2016-04-22 07:22:10.');

-- --------------------------------------------------------

--
-- Table structure for table `uf_user_rememberme`
--

CREATE TABLE IF NOT EXISTS `uf_user_rememberme` (
  `user_id` int(11) NOT NULL,
  `token` varchar(40) NOT NULL,
  `persistent_token` varchar(40) NOT NULL,
  `expires` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
