/*
Navicat MySQL Data Transfer

Source Server         : 249
Source Server Version : 50549
Source Host           : 192.168.1.249:3306
Source Database       : anchor

Target Server Type    : MYSQL
Target Server Version : 50549
File Encoding         : 65001

Date: 2016-10-10 10:42:56
*/

SET FOREIGN_KEY_CHECKS=0;
-- ----------------------------
-- Table structure for `anchor_advance`
-- ----------------------------
DROP TABLE IF EXISTS `anchor_advance`;
CREATE TABLE `anchor_advance` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `applicant_id` int(6) NOT NULL,
  `course_id` int(6) NOT NULL,
  `money` bigint(20) NOT NULL,
  `time_request` date NOT NULL,
  `time_response` date DEFAULT NULL,
  `reason` text NOT NULL,
  `status` enum('draft','published','rebuff') NOT NULL,
  `user_check_id` int(6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of anchor_advance
-- ----------------------------

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of anchor_categories
-- ----------------------------
INSERT INTO `anchor_categories` VALUES ('1', 'Uncategorised', 'uncategorised', 'Aint no category here.');

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
-- Table structure for `anchor_courses`
-- ----------------------------
DROP TABLE IF EXISTS `anchor_courses`;
CREATE TABLE `anchor_courses` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `remoteid` bigint(10) DEFAULT NULL,
  `fullname` varchar(500) NOT NULL,
  `shortname` varchar(100) NOT NULL,
  `summary` varchar(500) NOT NULL,
  `startdate` timestamp NULL DEFAULT NULL,
  `enddate` timestamp NULL DEFAULT NULL,
  `status` int(1) DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of anchor_courses
-- ----------------------------

-- ----------------------------
-- Table structure for `anchor_curriculum`
-- ----------------------------
DROP TABLE IF EXISTS `anchor_curriculum`;
CREATE TABLE `anchor_curriculum` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `course` bigint(10) NOT NULL,
  `topicday` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `topictime` varchar(20) DEFAULT NULL,
  `topicname` varchar(512) DEFAULT NULL,
  `teacher` int DEFAULT NULL,
  `room` int DEFAULT NULL,
  `userid` bigint(10) DEFAULT NULL,
  `timecreated` int(11) DEFAULT '0',
  `timemodified` int(11) DEFAULT '0',
  `usermodified` bigint(10) DEFAULT NULL,
  `note` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of anchor_curriculum
-- ----------------------------

-- ----------------------------
-- Table structure for `anchor_equipment`
-- ----------------------------
DROP TABLE IF EXISTS `anchor_equipment`;
CREATE TABLE `anchor_equipment` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `room` bigint(10) NOT NULL,
  `virtual_class_equipmentname` varchar(512) NOT NULL,
  `virtual_class_equipmenttime` varchar(20) DEFAULT NULL,
  `description` text NOT NULL,
  `name` varchar(150) NOT NULL,
  `quantity` int(6) DEFAULT NULL,
  `userid` bigint(10) NOT NULL,
  `timecreated` int(11) DEFAULT '0',
  `timemodified` int(11) DEFAULT '0',
  `usermodified` bigint(10) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of anchor_equipment
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of anchor_extend
-- ----------------------------
INSERT INTO `anchor_extend` VALUES ('1', 'post', 'all', 'image', 'feature_image', 'Ảnh', '{\"type\":\"\",\"size\":{\"width\":\"\",\"height\":\"\"}}');

-- ----------------------------
-- Table structure for `anchor_instructor_contract`
-- ----------------------------
DROP TABLE IF EXISTS `anchor_instructor_contract`;
CREATE TABLE `anchor_instructor_contract` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `instructor_id` int(6) NOT NULL,
  `type` enum('personal','organization') NOT NULL,
  `name_partner` varchar(200) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `salary` varchar(15) NOT NULL,
  `rules` varchar(1000) NOT NULL,
  `state` enum('unpaid','paid') NOT NULL,
  `name_contract` varchar(140) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of anchor_instructor_contract
-- ----------------------------

-- ----------------------------
-- Table structure for `anchor_instructors`
-- ----------------------------
DROP TABLE IF EXISTS `anchor_instructors`;
CREATE TABLE `anchor_instructors` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `email` varchar(140) NOT NULL,
  `birthday` date NOT NULL,
  `subject` varchar(200) NOT NULL,
  `fullname` varchar(140) DEFAULT NULL,
  `type_instructor` enum('contract','official') DEFAULT NULL,
  `comment` varchar(1000) DEFAULT NULL,
  `curriculum_taught` int(6) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of anchor_instructors
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of anchor_posts
-- ----------------------------

-- ----------------------------
-- Table structure for `anchor_rooms`
-- ----------------------------
DROP TABLE IF EXISTS `anchor_rooms`;
CREATE TABLE `anchor_rooms` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `description` text NOT NULL,
  `name` varchar(150) NOT NULL,
  `time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `userid` bigint(10) NOT NULL,
  `timecreated` int(11) DEFAULT '0',
  `timemodified` int(11) DEFAULT '0',
  `usermodified` bigint(10) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of anchor_rooms
-- ----------------------------

-- ----------------------------
-- Table structure for `anchor_router`
-- ----------------------------
DROP TABLE IF EXISTS `anchor_router`;
CREATE TABLE `anchor_router` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `router` varchar(100) NOT NULL,
  `action` varchar(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of anchor_router
-- ----------------------------

-- ----------------------------
-- Table structure for `anchor_schools`
-- ----------------------------
DROP TABLE IF EXISTS `anchor_schools`;
CREATE TABLE `anchor_schools` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `remoteid` int(6) NOT NULL,
  `name` varchar(100) NOT NULL,
  `wwwroot` varchar(255) DEFAULT NULL,
  `token` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of anchor_schools
-- ----------------------------
INSERT INTO `anchor_schools` VALUES ('1', '2', 'All Hosts', ' ', null);
INSERT INTO `anchor_schools` VALUES ('2', '3', 'Learning Management System', 'http://192.168.1.253', 'd1d97b79cdfbf816180ff3c820da7ca0');

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
-- Table structure for `anchor_student_course`
-- ----------------------------
DROP TABLE IF EXISTS `anchor_student_course`;
CREATE TABLE `anchor_student_course` (
  `studentid` int(11) NOT NULL AUTO_INCREMENT,
  `courseid` int(11) NOT NULL,
  `grade` float DEFAULT NULL,
  `remoterole` int(11) DEFAULT NULL,
  PRIMARY KEY (`studentid`,`courseid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of anchor_student_course
-- ----------------------------

-- ----------------------------
-- Table structure for `anchor_students`
-- ----------------------------
DROP TABLE IF EXISTS `anchor_students`;
CREATE TABLE `anchor_students` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `remoteid` int(11) DEFAULT NULL,
  `fullname` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `schoolid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of anchor_students
-- ----------------------------
INSERT INTO `anchor_students` VALUES ('2', '4', 'admin 1', 'admin1@gmail.com', '2');
INSERT INTO `anchor_students` VALUES ('3', '5', 'student 253', 'student_253@gmail.com', '2');
INSERT INTO `anchor_students` VALUES ('4', '6', 'teacher 253', 'teacher_253@gmail.com', '2');
INSERT INTO `anchor_students` VALUES ('5', '7', 'student 253_1', 'student_253_1@gmail.com', '2');
INSERT INTO `anchor_students` VALUES ('6', '8', 'student 253_2', 'student_253_2@gmail.com', '2');
INSERT INTO `anchor_students` VALUES ('7', '9', 'student 253_2', 'student_253_5@gmail.com', '2');
INSERT INTO `anchor_students` VALUES ('8', '10', 'student 253_6', 'student_253_6@gmail.com', '2');
INSERT INTO `anchor_students` VALUES ('9', '11', 'student 253_t', 'thiennccsoft@gmail.com', '2');
INSERT INTO `anchor_students` VALUES ('10', '12', 'student 253_v', 'huongh3k45@gmail.com', '2');
INSERT INTO `anchor_students` VALUES ('11', '13', 'teacher 253_v', 'vuvanbk50@gmail.com', '2');
INSERT INTO `anchor_students` VALUES ('12', '14', 'student_hanv1 Nguyen', 'student_hanv1@tecapro.com.vn', '2');
INSERT INTO `anchor_students` VALUES ('13', '15', 'Tuynh Student', 'nguyenvantuynh95@gmail.com', '2');
INSERT INTO `anchor_students` VALUES ('14', '16', 'Tuynh Teacher', 'tuynh.nguyen@nccsoft.com', '2');
INSERT INTO `anchor_students` VALUES ('15', '17', 'name Tesla', 'thien.dang@nccsoft.vn', '2');
INSERT INTO `anchor_students` VALUES ('16', '18', 'thien thien', 'thienth3@gmail.com', '2');
INSERT INTO `anchor_students` VALUES ('17', '19', 'student tunganh', 'tunganhmta@gmail.com', '2');
INSERT INTO `anchor_students` VALUES ('18', '20', 'Admin User', 'host253@nccsoft.vn', '2');
INSERT INTO `anchor_students` VALUES ('27', '3', '   ', ' ', '2');

-- ----------------------------
-- Table structure for `anchor_user_course`
-- ----------------------------
DROP TABLE IF EXISTS `anchor_user_course`;
CREATE TABLE `anchor_user_course` (
  `userid` int(11) NOT NULL,
  `courseid` int(11) NOT NULL,
  `remoterole` int(11) DEFAULT NULL,
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of anchor_user_course
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
-- Table structure for `anchor_user_role`
-- ----------------------------
DROP TABLE IF EXISTS `anchor_user_role`;
CREATE TABLE `anchor_user_role` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `role` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of anchor_user_role
-- ----------------------------
INSERT INTO `anchor_user_role` VALUES ('1', 'administrator');
INSERT INTO `anchor_user_role` VALUES ('2', 'user');
INSERT INTO `anchor_user_role` VALUES ('3', 'student');
INSERT INTO `anchor_user_role` VALUES ('4', 'school');
INSERT INTO `anchor_user_role` VALUES ('5', 'instructor');
INSERT INTO `anchor_user_role` VALUES ('6', 'contract');

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
  `remoteid` int(11) DEFAULT NULL,
  `auth` varchar(140) NOT NULL DEFAULT 'manual',
  `role_id` enum('1','2','3','4','5','6') NOT NULL,
  `schoolid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=50 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of anchor_users
-- ----------------------------
INSERT INTO `anchor_users` VALUES ('41', 'teacher_253', '', 'teacher_253@gmail.com', 'teacher 253', '', 'active', 'administrator', '6', 'remote', '1', '2');
INSERT INTO `anchor_users` VALUES ('42', 'teacher_253_v', '', 'vuvanbk50@gmail.com', 'teacher 253_v', '', 'active', 'administrator', '13', 'remote', '1', '2');
INSERT INTO `anchor_users` VALUES ('43', 'teacher_t1', '', 'tuynh.nguyen@nccsoft.com', 'Tuynh Teacher', '', 'active', 'administrator', '16', 'remote', '1', '2');
INSERT INTO `anchor_users` VALUES ('44', 'admin', '', 'host253@nccsoft.vn', 'Admin User', '', 'active', 'administrator', '20', 'remote', '1', '2');

-- ----------------------------
-- Table structure for `anchor_virtual_class_equipment`
-- ----------------------------
DROP TABLE IF EXISTS `anchor_virtual_class_equipment`;
CREATE TABLE `anchor_virtual_class_equipment` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `image_url` varchar(150) DEFAULT NULL,
  `description` text NOT NULL,
  `quantity` int(6) DEFAULT NULL,
  `userid` bigint(10) NOT NULL,
  `timecreated` int(11) DEFAULT '0',
  `timemodified` int(11) DEFAULT '0',
  `usermodified` bigint(10) DEFAULT NULL,
  `created` datetime DEFAULT NULL,
  `roomid` int(6) DEFAULT NULL,
  `status` tinyint(1) DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of anchor_virtual_class_equipment
-- ----------------------------
