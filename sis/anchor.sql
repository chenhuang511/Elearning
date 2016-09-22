/*
Navicat MySQL Data Transfer

Source Server         : moodlelocalhost
Source Server Version : 50713
Source Host           : localhost:3306
Source Database       : anchor

Target Server Type    : MYSQL
Target Server Version : 50713
File Encoding         : 65001

Date: 2016-09-22 10:23:44
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `anchor_categories`
-- ----------------------------
DROP TABLE IF EXISTS `anchor_categories`;
CREATE TABLE `anchor_categories` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `slug` varchar(40) NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of anchor_categories
-- ----------------------------
INSERT INTO `anchor_categories` VALUES ('1', 'Uncategorised', 'uncategorised', 'Ain\'t no category here.');
INSERT INTO `anchor_categories` VALUES ('2', 'menu1', 'menu1', '');
INSERT INTO `anchor_categories` VALUES ('3', 'menu2', 'menu2', '');

-- ----------------------------
-- Table structure for `anchor_category_meta`
-- ----------------------------
DROP TABLE IF EXISTS `anchor_category_meta`;
CREATE TABLE `anchor_category_meta` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `category` int(6) NOT NULL,
  `extend` int(6) NOT NULL,
  `data` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `item` (`category`),
  KEY `extend` (`extend`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of anchor_category_meta
-- ----------------------------

-- ----------------------------
-- Table structure for `anchor_comments`
-- ----------------------------
DROP TABLE IF EXISTS `anchor_comments`;
CREATE TABLE `anchor_comments` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `post` int(6) NOT NULL,
  `status` enum('pending','approved','spam') NOT NULL,
  `date` datetime NOT NULL,
  `name` varchar(140) NOT NULL,
  `email` varchar(140) NOT NULL,
  `text` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `post` (`post`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of anchor_comments
-- ----------------------------

-- ----------------------------
-- Table structure for `anchor_extend`
-- ----------------------------
DROP TABLE IF EXISTS `anchor_extend`;
CREATE TABLE `anchor_extend` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `type` enum('post','page','category','user') NOT NULL,
  `pagetype` varchar(140) NOT NULL DEFAULT 'all',
  `field` enum('text','html','image','file') NOT NULL,
  `key` varchar(160) NOT NULL,
  `label` varchar(160) NOT NULL,
  `attributes` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of anchor_extend
-- ----------------------------

-- ----------------------------
-- Table structure for `anchor_meta`
-- ----------------------------
DROP TABLE IF EXISTS `anchor_meta`;
CREATE TABLE `anchor_meta` (
  `key` varchar(140) NOT NULL,
  `value` text NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of anchor_meta
-- ----------------------------
INSERT INTO `anchor_meta` VALUES ('auto_published_comments', '0');
INSERT INTO `anchor_meta` VALUES ('comment_moderation_keys', '');
INSERT INTO `anchor_meta` VALUES ('comment_notifications', '0');
INSERT INTO `anchor_meta` VALUES ('current_migration', '211');
INSERT INTO `anchor_meta` VALUES ('date_format', 'jS M, Y');
INSERT INTO `anchor_meta` VALUES ('description', 'It’s not just any blog. It’s an Anchor blog.');
INSERT INTO `anchor_meta` VALUES ('home_page', '1');
INSERT INTO `anchor_meta` VALUES ('last_update_check', '2016-09-21 11:53:17');
INSERT INTO `anchor_meta` VALUES ('posts_page', '1');
INSERT INTO `anchor_meta` VALUES ('posts_per_page', '6');
INSERT INTO `anchor_meta` VALUES ('show_all_posts', '0');
INSERT INTO `anchor_meta` VALUES ('sitename', 'My First Anchor Blog');
INSERT INTO `anchor_meta` VALUES ('theme', 'default');
INSERT INTO `anchor_meta` VALUES ('update_version', '');

-- ----------------------------
-- Table structure for `anchor_page_meta`
-- ----------------------------
DROP TABLE IF EXISTS `anchor_page_meta`;
CREATE TABLE `anchor_page_meta` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `page` int(6) NOT NULL,
  `extend` int(6) NOT NULL,
  `data` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `page` (`page`),
  KEY `extend` (`extend`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of anchor_page_meta
-- ----------------------------

-- ----------------------------
-- Table structure for `anchor_pages`
-- ----------------------------
DROP TABLE IF EXISTS `anchor_pages`;
CREATE TABLE `anchor_pages` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `parent` int(6) NOT NULL,
  `slug` varchar(150) NOT NULL,
  `pagetype` varchar(140) NOT NULL DEFAULT 'all',
  `name` varchar(64) NOT NULL,
  `title` varchar(150) NOT NULL,
  `markdown` text,
  `html` text NOT NULL,
  `status` enum('draft','published','archived') NOT NULL,
  `redirect` text NOT NULL,
  `show_in_menu` tinyint(1) NOT NULL,
  `menu_order` int(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of anchor_pages
-- ----------------------------
INSERT INTO `anchor_pages` VALUES ('1', '0', 'posts', 'all', 'Posts', 'My posts and thoughts', 'Welcome!', '<p>Welcome!</p>', 'published', '', '1', '0');

-- ----------------------------
-- Table structure for `anchor_pagetypes`
-- ----------------------------
DROP TABLE IF EXISTS `anchor_pagetypes`;
CREATE TABLE `anchor_pagetypes` (
  `key` varchar(32) NOT NULL,
  `value` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of anchor_pagetypes
-- ----------------------------
INSERT INTO `anchor_pagetypes` VALUES ('all', 'All Pages');

-- ----------------------------
-- Table structure for `anchor_post_meta`
-- ----------------------------
DROP TABLE IF EXISTS `anchor_post_meta`;
CREATE TABLE `anchor_post_meta` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `post` int(6) NOT NULL,
  `extend` int(6) NOT NULL,
  `data` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `post` (`post`),
  KEY `extend` (`extend`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of anchor_post_meta
-- ----------------------------

-- ----------------------------
-- Table structure for `anchor_posts`
-- ----------------------------
DROP TABLE IF EXISTS `anchor_posts`;
CREATE TABLE `anchor_posts` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `slug` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `markdown` text NOT NULL,
  `html` mediumtext NOT NULL,
  `css` text NOT NULL,
  `js` text NOT NULL,
  `created` datetime NOT NULL,
  `author` int(6) NOT NULL,
  `category` int(6) NOT NULL,
  `status` enum('draft','published','archived') NOT NULL,
  `comments` tinyint(1) DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `status` (`status`),
  KEY `slug` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of anchor_posts
-- ----------------------------
INSERT INTO `anchor_posts` VALUES ('1', 'Hello World', 'hello-world', 'This is the first post.', 'Hello World!\r\n\r\nThis is the first post.', '<p>Hello World!</p>\n<p>This is the first post.</p>', '', '', '2016-09-21 03:44:57', '1', '1', 'published', '0');

-- ----------------------------
-- Table structure for `anchor_sessions`
-- ----------------------------
DROP TABLE IF EXISTS `anchor_sessions`;
CREATE TABLE `anchor_sessions` (
  `id` char(32) NOT NULL,
  `expire` int(10) NOT NULL,
  `data` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of anchor_sessions
-- ----------------------------

-- ----------------------------
-- Table structure for `anchor_user_meta`
-- ----------------------------
DROP TABLE IF EXISTS `anchor_user_meta`;
CREATE TABLE `anchor_user_meta` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `user` int(6) NOT NULL,
  `extend` int(6) NOT NULL,
  `data` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `item` (`user`),
  KEY `extend` (`extend`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of anchor_user_meta
-- ----------------------------

-- ----------------------------
-- Table structure for `anchor_users`
-- ----------------------------
DROP TABLE IF EXISTS `anchor_users`;
CREATE TABLE `anchor_users` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `username` varchar(100) NOT NULL,
  `password` text NOT NULL,
  `email` varchar(140) NOT NULL,
  `real_name` varchar(140) NOT NULL,
  `bio` text NOT NULL,
  `status` enum('inactive','active') NOT NULL,
  `role` enum('administrator','editor','user') NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of anchor_users
-- ----------------------------
INSERT INTO `anchor_users` VALUES ('1', 'admin', '$2y$12$1u63bVoSKtuIHtTUNRlHguqlHq1D6Ome8yhR57mUnuDEfWr2/ue4e', 'thien@gmail.com', 'Administrator', 'The bouse', 'active', 'administrator');


-- ----------------------------
-- Table structure for `anchor_advance`
-- ----------------------------
DROP TABLE IF EXISTS `anchor_advance`;
CREATE TABLE `anchor_advance` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `applicant_id` int(6) NOT NULL,
  `money` 	bigint(20) NOT NULL,
  `time` date NOT NULL,
  `reason` text NOT NULL,
  `status` enum('draft','published',) NOT NULL,
  `user_check_id` int(6) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of anchor_advance
-- ----------------------------
INSERT INTO `anchor_advance` VALUES (NULL, '1', '200000', '2016-09-07', '1111', 'published', '0');
INSERT INTO `anchor_advance` VALUES (NULL, '2', '200000', '2016-09-07', '1111', 'draft', '0');


-- ----------------------------
-- Table structure for `anchor_staff`
-- ----------------------------

DROP TABLE IF EXISTS `anchor_staff`;
CREATE TABLE `anchor`.`anchor_staff` (
`id` INT NOT NULL AUTO_INCREMENT ,
`full_name` VARCHAR(255) NOT NULL ,
`position` VARCHAR(255) NOT NULL ,
 PRIMARY KEY (`id`)
 ) ENGINE = InnoDB;

 -- ----------------------------
-- Records of anchor_staff
-- ----------------------------
INSERT INTO `anchor_staff` VALUES ('', 'staff 1', 'GD');
INSERT INTO `anchor_staff` VALUES ('', 'staff 2', 'NV');
INSERT INTO `anchor_staff` VALUES ('', 'staff 3', 'NV');


