/*
Date: 2016-09-26 12:02:45
*/

SET FOREIGN_KEY_CHECKS=0;
/*
	For table exists
*/
-- ----------------------------
-- Table structure for `anchor_users`
-- ----------------------------
ALTER TABLE `anchor_users`
ADD COLUMN `remoteid` int(11) DEFAULT NULL,
ADD COLUMN `auth` varchar(140) NOT NULL DEFAULT 'manual'
-- ----------------------------
-- Table structure for `anchor_courses`
-- ----------------------------
DROP TABLE IF EXISTS `anchor_courses`;
CREATE TABLE `anchor_courses` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `remoteid` int(6) NOT NULL,
  `fullname` varchar(500) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `shortname` varchar(100) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `summary` varchar(500) CHARACTER SET utf8 COLLATE utf8_vietnamese_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8;
-- ----------------------------
-- Table structure for `anchor_student_course`
-- ----------------------------
DROP TABLE IF EXISTS `anchor_student_course`;
CREATE TABLE `anchor_student_course` (
  `studentid` int(11) NOT NULL AUTO_INCREMENT,
  `courseid` int(11) NOT NULL,
  `grade` float DEFAULT NULL,
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
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Table structure for `anchor_user_course`
-- ----------------------------
DROP TABLE IF EXISTS `anchor_user_course`;
CREATE TABLE `anchor_user_course` (
  `userid` int(11) NOT NULL AUTO_INCREMENT,
  `courseid` int(11) NOT NULL,
  PRIMARY KEY (`userid`,`courseid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

