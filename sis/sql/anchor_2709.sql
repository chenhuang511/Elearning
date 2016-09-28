/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50713
Source Host           : localhost:3306
Source Database       : anchor

Target Server Type    : MYSQL
Target Server Version : 50713
File Encoding         : 65001

Date: 2016-09-27 14:31:46
*/

SET FOREIGN_KEY_CHECKS=0;
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
-- Records of anchor_courses
-- ----------------------------
INSERT INTO `anchor_courses` VALUES ('1', '1', 'Elearning service HUB', 'ElearningHUB', ' ');
INSERT INTO `anchor_courses` VALUES ('2', '2', 'PharmD Training', 'PharmD', 'As an independent contractor, it can be hard to work in a new system remotely off site. This training course is designed to walk you through how to put your interpretation and medication breakdown into the system.');
INSERT INTO `anchor_courses` VALUES ('3', '3', 'TOEIC 500 - Online, Free (12/12 Units) ', 'T500', 'TOEIC 500 la khoa h?c nh?m giup cac b?n d?t d??c di?m thi TOEIC 500. Khoa h?c d??c thi?t k? d?a tren n?n t?ng c?a TOEIC 400. Khoa h?c ti?p t?c trang b? cho b?n nh?ng k? nang va ki?n th?c c?n thi?t d? b?n co th? tham gia ki thi TOEIC t?i ETS.&nbsp;\r\n+ Target for Real TOEIC: 500 di?m');
INSERT INTO `anchor_courses` VALUES ('4', '5', 'Types of Sport', 'Types of Sport', 'A course in which you will be introduced to different types of sport and get a feel for how you might enjoy participating in them. Best suited to students aged 8-13.');
INSERT INTO `anchor_courses` VALUES ('5', '6', 'Workflow - Part I (Teacher)', 'Teacher Workflow ', 'In this course teachers will be able to explain the various componets of a mobile workflow and begin to create and manage a professional mobile workflow of their own. &nbsp;be able to confidently manage and create a professional mobile workflow.&nbsp;');
INSERT INTO `anchor_courses` VALUES ('6', '7', 'Workflow - Part II (Classroom)', 'Classroom Workflow ', 'In this module participants will look at ways to create a classroom workflow that includes both collaboration between teacher and students and between students. &nbsp;');
INSERT INTO `anchor_courses` VALUES ('7', '62', 'English Pronunciation Clinic', 'Pronunciation', 'Through this course students will be able to improve their English pronunciation. This\r\nthree week course will cover three different weekly sections which include the phonemes, differences between Korean and English, and reductions.');
INSERT INTO `anchor_courses` VALUES ('8', '45', 'Step  by Step with Lesson:Examples', 'Lesson', 'This course was created for a workshop at the Mountain Moot 2013 where participants learned how to create Moodle Lessons. There are three lessons available to try as students and a fourth which was built together as an introduction to lesson construction. I have also included the slideshow presentation used during the course of the workshop.');
INSERT INTO `anchor_courses` VALUES ('9', '66', 'The Lakes Poets', 'SSE1: Lakes Poets', 'An introductory module to the study of Wordsworth, Coleridge and Southey, commonly known as the Lakes Poets.');
INSERT INTO `anchor_courses` VALUES ('10', '83', 'Travel and Tourism LearnNet', 'Toursim LN', 'Travel and Tourism LearnNet');
INSERT INTO `anchor_courses` VALUES ('11', '84', 'WebTools a course for teachers', 'Tools', 'This an introductury course for teachers to use a range of different webtools as teaching aids.&nbsp;');
INSERT INTO `anchor_courses` VALUES ('12', '82', 'PAVE Science 20 ', 'Science 20', 'Researchers have found a way to give photons, or light packets, their marching orders. The researchers have capitalized on the largesse of an energy state in an optical field to make photons in their lasing system travel in a consistent mode, either clockwise or counterclockwise.');
INSERT INTO `anchor_courses` VALUES ('13', '122', 'Art History ', 'ArtHist_1', 'Art history is the study of objects of art in their historical development and stylistic contexts, i.e. genre, design, format, and style. This includes the \"major\" arts of painting, sculpture, and architecture as well as the \"minor\" arts of ceramics, furniture, and other decorative objects.');
INSERT INTO `anchor_courses` VALUES ('14', '103', 'Intermediate A Intensive Reading ??A??', 'IAIR', 'Intensive vocabulary study (500+ new words)\r\nIntensive grammar study\r\nFocus on how Chinese readings are composed');
INSERT INTO `anchor_courses` VALUES ('15', '123', 'Social Studies 12 - Mangefrida', 'SS12-Mange', 'The term social refers to a characteristic of living organisms as applied to populations of humans and other animals');
INSERT INTO `anchor_courses` VALUES ('16', '182', 'Dao tao ky nang nghiep vu Thu/Chi', 'Dao tao ki nang', 'Dao tao ki nang nghiep vu thu/chi');
INSERT INTO `anchor_courses` VALUES ('17', '202', 'L?p b?i d??ng nghi?p v? cho vien ch?c d??c giao nhi?m v? thanh tra chuyen nganh b?o hi?m x? h?i k03-2016', 'NV-BHXH-TT03', 'Trang b? nh?ng ki?n th?c c? b?n v? h? th?ng cac quan nha n??c; quy trinh nghi?p v?, k? nang ti?n hanh m?t cu?c thanh tra, gi?i quy?t khi?u n?i, t? cao va phong, ch?ng tham nh?ng');
INSERT INTO `anchor_courses` VALUES ('18', '262', 'L?p b?i d??ng k? nang l?nh d?o c?p phong cho pho tr??ng phong BHXH t?nh, thanh ph? nam 2016 (L?p 4 - d?t III)', 'L?p b?i d??ng k? nang l?nh d?o c?p phong - L04 - D?t III', 'N?i dung ch??ng trinh khoa h?c g?m: L?nh d?o c?p phong va v?n d?ng ki?n th?c, k? nang c?a l?nh d?o c?p phong; Yeu c?u v? k? nang d?i v?i can b? qu?n ly nganh BHXH theo ch? d?o c?i cach hanh chinh c?a T?ng Giam d?c BHXH Vi?t Nam; Ki?n th?c va k? nang qu?n ly, l?nh d?o c?p phong; K? nang l?p va t? ch?c th?c hi?n k? ho?ch cong tac c?a l?nh d?o c?p phong; K? nang tham m?u c?a l?nh d?o c?p phong; K? nang qu?n ly va phat tri?n nhan s? c?a l?nh d?o c?p phong; K? nang c?p nh?t va ap d?ng phap lu?t trong');
INSERT INTO `anchor_courses` VALUES ('19', '282', 'L?p b?i d??ng k? nang thanh tra - ki?m tra tai chinh', 'TT-KT TC', 'N?i dung ch??ng trinh khoa h?c g?m: L?nh d?o c?p phong va v?n d?ng ki?n th?c, k? nang c?a l?nh d?o c?p phong; Yeu c?u v? k? nang d?i v?i can b? qu?n ly nganh BHXH theo ch? d?o c?i cach hanh chinh c?a T?ng Giam d?c BHXH Vi?t Nam; Ki?n th?c va k? nang qu?n ly, l?nh d?o c?p phong; K? nang l?p va t? ch?c th?c hi?n k? ho?ch cong tac c?a l?nh d?o c?p phong; K? nang tham m?u c?a l?nh d?o c?p phong; K? nang qu?n ly va phat tri?n nhan s? c?a l?nh d?o c?p phong; K? nang c?p nh?t va ap d?ng phap lu?t trong');
INSERT INTO `anchor_courses` VALUES ('20', '168', 'Moodle Features Demo remove', 'Features Demo', 'Sau khi h?i y va ch?n ph??ng an b? phi?u, H?i d?ng ti?n l??ng Qu?c gia d? th?ng nh?t m?c d? xu?t tang l??ng t?i thi?u 2017 nh? sau: Vung 1: tang 250.000 d?ng, t??ng d??ng 7,1 %; Vung 2 tang 220.000 d?ng, t??ng 7,1 %; vung 3 tang 200.000 d?ng, t??ng d??ng 7,4 %, vung 4 tang 180.000 d?ng, t??ng d??ng 7,9 %.\r\n\r\nTinh trung binh, m?c d? xu?t tang l??ng t?i thi?u vung nam 2017 d??c H?i d?ng ti?n l??ng Qu?c gia th?ng nh?t la 7,3 % so v?i l??ng t?i thi?u nam 2016, m?c tang dao d?ng t? 180.000 - 250.000 ');
INSERT INTO `anchor_courses` VALUES ('21', '382', 'Test1', 't1', ' ');
INSERT INTO `anchor_courses` VALUES ('22', '402', 'L?p b?i d??ng nghi?p v? b?o hi?m ', 'NVBH', ' ');

-- ----------------------------
-- Table structure for `anchor_school_student`
-- ----------------------------
DROP TABLE IF EXISTS `anchor_school_student`;
CREATE TABLE `anchor_school_student` (
  `schoolid` int(6) NOT NULL,
  `userid` int(6) NOT NULL,
  `username` varchar(100) NOT NULL,
  `schoolname` varchar(100) NOT NULL,
  PRIMARY KEY (`schoolid`,`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of anchor_school_student
-- ----------------------------
INSERT INTO `anchor_school_student` VALUES ('2', '2', 'admin', 'Host elearning site');
INSERT INTO `anchor_school_student` VALUES ('2', '9', 'nguyentran', 'Host elearning site');
INSERT INTO `anchor_school_student` VALUES ('2', '20', 'student1', 'Host elearning site');
INSERT INTO `anchor_school_student` VALUES ('2', '21', 'teacher1', 'Host elearning site');
INSERT INTO `anchor_school_student` VALUES ('3', '4', 'admin', 'hostphuc');
INSERT INTO `anchor_school_student` VALUES ('4', '3', 'admin', 'haonh-host');
INSERT INTO `anchor_school_student` VALUES ('4', '11', 'min_16', 'haonh-host');
INSERT INTO `anchor_school_student` VALUES ('4', '44', 'min_teacher', 'haonh-host');
INSERT INTO `anchor_school_student` VALUES ('5', '4', 'admin', 'TiepPT-host');
INSERT INTO `anchor_school_student` VALUES ('5', '17', 'admin-teacher', 'TiepPT-host');
INSERT INTO `anchor_school_student` VALUES ('5', '24', 'admin-1', 'TiepPT-host');
INSERT INTO `anchor_school_student` VALUES ('5', '93', 'lenhdenh', 'TiepPT-host');
INSERT INTO `anchor_school_student` VALUES ('6', '6', 'admin', 'host139');
INSERT INTO `anchor_school_student` VALUES ('6', '44', 'teacher_van2', 'host139');
INSERT INTO `anchor_school_student` VALUES ('6', '45', 'teacher_van5', 'host139');
INSERT INTO `anchor_school_student` VALUES ('6', '75', 'student_139_1', 'host139');
INSERT INTO `anchor_school_student` VALUES ('6', '76', 'teacher_139_1', 'host139');
INSERT INTO `anchor_school_student` VALUES ('7', '5', 'admin', 'Host202');
INSERT INTO `anchor_school_student` VALUES ('7', '25', 'student1', 'Host202');
INSERT INTO `anchor_school_student` VALUES ('7', '93', 'teacher', 'Host202');
INSERT INTO `anchor_school_student` VALUES ('8', '6', 'admin', 'Phuc-Host');
INSERT INTO `anchor_school_student` VALUES ('9', '7', 'admin', 'HostCuong');
INSERT INTO `anchor_school_student` VALUES ('9', '89', 'student', 'HostCuong');
INSERT INTO `anchor_school_student` VALUES ('10', '8', 'admin', 'HostLam');
INSERT INTO `anchor_school_student` VALUES ('10', '74', 'studen_tunganh123', 'HostLam');
INSERT INTO `anchor_school_student` VALUES ('10', '77', 'studen_tunglam', 'HostLam');
INSERT INTO `anchor_school_student` VALUES ('10', '85', 'teacher_tunganh1', 'HostLam');
INSERT INTO `anchor_school_student` VALUES ('11', '9', 'admin', 'Hosst Minh');
INSERT INTO `anchor_school_student` VALUES ('11', '18', 'student1', 'Hosst Minh');
INSERT INTO `anchor_school_student` VALUES ('11', '19', 'teacher1', 'Hosst Minh');
INSERT INTO `anchor_school_student` VALUES ('11', '88', 'student2', 'Hosst Minh');
INSERT INTO `anchor_school_student` VALUES ('12', '9', 'khoanglang', 'HaCa Host');
INSERT INTO `anchor_school_student` VALUES ('13', '10', 'khoanglang', 'HaNV TecaHost');
INSERT INTO `anchor_school_student` VALUES ('13', '12', 'student1', 'HaNV TecaHost');
INSERT INTO `anchor_school_student` VALUES ('13', '13', 'student2', 'HaNV TecaHost');
INSERT INTO `anchor_school_student` VALUES ('13', '14', 'student3', 'HaNV TecaHost');
INSERT INTO `anchor_school_student` VALUES ('13', '30', 'teacher_1', 'HaNV TecaHost');
INSERT INTO `anchor_school_student` VALUES ('13', '31', 'teacher_2', 'HaNV TecaHost');
INSERT INTO `anchor_school_student` VALUES ('14', '15', 'admin', 'LMS');
INSERT INTO `anchor_school_student` VALUES ('14', '22', 'student1', 'LMS');
INSERT INTO `anchor_school_student` VALUES ('14', '23', 'teacher1', 'LMS');
INSERT INTO `anchor_school_student` VALUES ('14', '35', 'user2', 'LMS');
INSERT INTO `anchor_school_student` VALUES ('14', '45', 'teacher_249_1', 'LMS');
INSERT INTO `anchor_school_student` VALUES ('14', '52', 'teacher_van4', 'LMS');
INSERT INTO `anchor_school_student` VALUES ('14', '53', 'teachermy', 'LMS');
INSERT INTO `anchor_school_student` VALUES ('14', '54', 'mystudent', 'LMS');
INSERT INTO `anchor_school_student` VALUES ('14', '55', 'teacher004', 'LMS');
INSERT INTO `anchor_school_student` VALUES ('14', '56', 'student004', 'LMS');
INSERT INTO `anchor_school_student` VALUES ('14', '61', 'student_t2', 'LMS');
INSERT INTO `anchor_school_student` VALUES ('14', '62', 'teacher_t2', 'LMS');
INSERT INTO `anchor_school_student` VALUES ('14', '66', 'student_tunganh', 'LMS');
INSERT INTO `anchor_school_student` VALUES ('14', '67', 'studen_tunganh', 'LMS');
INSERT INTO `anchor_school_student` VALUES ('14', '70', 'stu_thuong', 'LMS');
INSERT INTO `anchor_school_student` VALUES ('14', '71', 'tec_thuong', 'LMS');
INSERT INTO `anchor_school_student` VALUES ('14', '72', 'teacher_n', 'LMS');
INSERT INTO `anchor_school_student` VALUES ('14', '73', 'student_n', 'LMS');
INSERT INTO `anchor_school_student` VALUES ('14', '83', 'stu_thuong1', 'LMS');
INSERT INTO `anchor_school_student` VALUES ('14', '93', 'student_t1', 'LMS');
INSERT INTO `anchor_school_student` VALUES ('15', '16', 'admin', 'Learning Management System');
INSERT INTO `anchor_school_student` VALUES ('15', '81', 'student_253', 'Learning Management System');
INSERT INTO `anchor_school_student` VALUES ('15', '82', 'student_253_1', 'Learning Management System');
INSERT INTO `anchor_school_student` VALUES ('15', '84', 'teacher_253', 'Learning Management System');
INSERT INTO `anchor_school_student` VALUES ('15', '88', 'student_t1', 'Learning Management System');
INSERT INTO `anchor_school_student` VALUES ('15', '90', 'student_253_v', 'Learning Management System');
INSERT INTO `anchor_school_student` VALUES ('15', '91', 'student_253_t', 'Learning Management System');
INSERT INTO `anchor_school_student` VALUES ('15', '92', 'student_253_2', 'Learning Management System');
INSERT INTO `anchor_school_student` VALUES ('15', '93', 'student_253_5', 'Learning Management System');
INSERT INTO `anchor_school_student` VALUES ('16', '26', 'student1', 'vanhaPC HOST_VPN65');
INSERT INTO `anchor_school_student` VALUES ('16', '27', 'student2', 'vanhaPC HOST_VPN65');
INSERT INTO `anchor_school_student` VALUES ('16', '92', 'student65_1', 'vanhaPC HOST_VPN65');
INSERT INTO `anchor_school_student` VALUES ('17', '35', 'admin', 'Host89');
INSERT INTO `anchor_school_student` VALUES ('18', '42', 'admin', 'host95');
INSERT INTO `anchor_school_student` VALUES ('18', '68', 'teacher006', 'host95');
INSERT INTO `anchor_school_student` VALUES ('18', '82', 'student006', 'host95');
INSERT INTO `anchor_school_student` VALUES ('18', '84', 'teacher005', 'host95');
INSERT INTO `anchor_school_student` VALUES ('19', '65', 'admin', 'host17');
INSERT INTO `anchor_school_student` VALUES ('19', '69', 'student1', 'host17');
INSERT INTO `anchor_school_student` VALUES ('19', '78', 'student2', 'host17');
INSERT INTO `anchor_school_student` VALUES ('19', '79', 'teacher_thien_1', 'host17');
INSERT INTO `anchor_school_student` VALUES ('19', '84', 'teacher171', 'host17');
INSERT INTO `anchor_school_student` VALUES ('19', '93', 'strudent_17', 'host17');
INSERT INTO `anchor_school_student` VALUES ('20', '86', 'admin', 'PTHost');
INSERT INTO `anchor_school_student` VALUES ('21', '86', 'admin-teacher', '151 - Host');
INSERT INTO `anchor_school_student` VALUES ('21', '87', 'student1', '151 - Host');
INSERT INTO `anchor_school_student` VALUES ('22', '87', 'admin-teacher', '151 - Host');
INSERT INTO `anchor_school_student` VALUES ('22', '88', 'hocsinh', '151 - Host');
INSERT INTO `anchor_school_student` VALUES ('23', '86', 'admin-teacher', '151 - Host');
INSERT INTO `anchor_school_student` VALUES ('23', '87', 'admin', '151 - Host');
INSERT INTO `anchor_school_student` VALUES ('23', '88', 'teacher_154', '151 - Host');

-- ----------------------------
-- Table structure for `anchor_schools`
-- ----------------------------
DROP TABLE IF EXISTS `anchor_schools`;
CREATE TABLE `anchor_schools` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `remoteid` int(6) NOT NULL,
  `name` varchar(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of anchor_schools
-- ----------------------------
INSERT INTO `anchor_schools` VALUES ('1', '2', 'All Hosts');
INSERT INTO `anchor_schools` VALUES ('2', '21', 'Host elearning site');
INSERT INTO `anchor_schools` VALUES ('3', '41', 'hostphuc');
INSERT INTO `anchor_schools` VALUES ('4', '42', 'haonh-host');
INSERT INTO `anchor_schools` VALUES ('5', '43', 'TiepPT-host');
INSERT INTO `anchor_schools` VALUES ('6', '44', 'host139');
INSERT INTO `anchor_schools` VALUES ('7', '61', 'Host202');
INSERT INTO `anchor_schools` VALUES ('8', '81', 'Phuc-Host');
INSERT INTO `anchor_schools` VALUES ('9', '82', 'HostCuong');
INSERT INTO `anchor_schools` VALUES ('10', '83', 'HostLam');
INSERT INTO `anchor_schools` VALUES ('11', '84', 'Hosst Minh');
INSERT INTO `anchor_schools` VALUES ('12', '101', 'HaCa Host');
INSERT INTO `anchor_schools` VALUES ('13', '121', 'HaNV TecaHost');
INSERT INTO `anchor_schools` VALUES ('14', '141', 'LMS');
INSERT INTO `anchor_schools` VALUES ('15', '142', 'Learning Management System');
INSERT INTO `anchor_schools` VALUES ('16', '161', 'vanhaPC HOST_VPN65');
INSERT INTO `anchor_schools` VALUES ('17', '181', 'Host89');
INSERT INTO `anchor_schools` VALUES ('18', '201', 'host95');
INSERT INTO `anchor_schools` VALUES ('19', '221', 'host17');
INSERT INTO `anchor_schools` VALUES ('20', '241', 'PTHost');
INSERT INTO `anchor_schools` VALUES ('21', '242', '151 - Host');
INSERT INTO `anchor_schools` VALUES ('22', '261', '151 - Host');
INSERT INTO `anchor_schools` VALUES ('23', '262', '151 - Host');

-- ----------------------------
-- Table structure for `anchor_student_course`
-- ----------------------------
DROP TABLE IF EXISTS `anchor_student_course`;
CREATE TABLE `anchor_student_course` (
  `studentid` int(11) NOT NULL,
  `courseid` int(11) NOT NULL,
  `grade` float DEFAULT NULL,
  PRIMARY KEY (`studentid`,`courseid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of anchor_student_course
-- ----------------------------
INSERT INTO `anchor_student_course` VALUES ('1', '2', '0');
INSERT INTO `anchor_student_course` VALUES ('1', '3', '0');
INSERT INTO `anchor_student_course` VALUES ('1', '13', '0');
INSERT INTO `anchor_student_course` VALUES ('1', '17', '0');
INSERT INTO `anchor_student_course` VALUES ('1', '18', '0');
INSERT INTO `anchor_student_course` VALUES ('1', '19', '0');
INSERT INTO `anchor_student_course` VALUES ('1', '20', '0');
INSERT INTO `anchor_student_course` VALUES ('2', '2', '0');
INSERT INTO `anchor_student_course` VALUES ('2', '3', '0');
INSERT INTO `anchor_student_course` VALUES ('2', '4', '0');
INSERT INTO `anchor_student_course` VALUES ('2', '5', '0');
INSERT INTO `anchor_student_course` VALUES ('2', '8', '0');
INSERT INTO `anchor_student_course` VALUES ('2', '12', '0');
INSERT INTO `anchor_student_course` VALUES ('2', '13', '0');
INSERT INTO `anchor_student_course` VALUES ('2', '17', '0');
INSERT INTO `anchor_student_course` VALUES ('2', '18', '0');
INSERT INTO `anchor_student_course` VALUES ('2', '20', '0');
INSERT INTO `anchor_student_course` VALUES ('3', '5', '0');
INSERT INTO `anchor_student_course` VALUES ('3', '11', '0');
INSERT INTO `anchor_student_course` VALUES ('3', '14', '0');
INSERT INTO `anchor_student_course` VALUES ('3', '16', '0');
INSERT INTO `anchor_student_course` VALUES ('3', '18', '0');
INSERT INTO `anchor_student_course` VALUES ('3', '19', '0');
INSERT INTO `anchor_student_course` VALUES ('3', '20', '0');
INSERT INTO `anchor_student_course` VALUES ('4', '2', '130');
INSERT INTO `anchor_student_course` VALUES ('4', '4', '130');
INSERT INTO `anchor_student_course` VALUES ('4', '5', '130');
INSERT INTO `anchor_student_course` VALUES ('4', '18', '130');
INSERT INTO `anchor_student_course` VALUES ('4', '20', '130');
INSERT INTO `anchor_student_course` VALUES ('5', '2', '0');
INSERT INTO `anchor_student_course` VALUES ('5', '4', '0');
INSERT INTO `anchor_student_course` VALUES ('5', '5', '0');
INSERT INTO `anchor_student_course` VALUES ('5', '6', '0');
INSERT INTO `anchor_student_course` VALUES ('5', '7', '0');
INSERT INTO `anchor_student_course` VALUES ('5', '8', '0');
INSERT INTO `anchor_student_course` VALUES ('5', '9', '0');
INSERT INTO `anchor_student_course` VALUES ('5', '10', '0');
INSERT INTO `anchor_student_course` VALUES ('5', '11', '0');
INSERT INTO `anchor_student_course` VALUES ('5', '12', '0');
INSERT INTO `anchor_student_course` VALUES ('5', '13', '0');
INSERT INTO `anchor_student_course` VALUES ('5', '14', '0');
INSERT INTO `anchor_student_course` VALUES ('5', '16', '0');
INSERT INTO `anchor_student_course` VALUES ('5', '17', '0');
INSERT INTO `anchor_student_course` VALUES ('5', '18', '0');
INSERT INTO `anchor_student_course` VALUES ('5', '19', '0');
INSERT INTO `anchor_student_course` VALUES ('5', '20', '0');
INSERT INTO `anchor_student_course` VALUES ('6', '2', '0');
INSERT INTO `anchor_student_course` VALUES ('6', '3', '0');
INSERT INTO `anchor_student_course` VALUES ('6', '4', '0');
INSERT INTO `anchor_student_course` VALUES ('6', '5', '0');
INSERT INTO `anchor_student_course` VALUES ('6', '6', '0');
INSERT INTO `anchor_student_course` VALUES ('6', '7', '0');
INSERT INTO `anchor_student_course` VALUES ('6', '8', '0');
INSERT INTO `anchor_student_course` VALUES ('6', '9', '0');
INSERT INTO `anchor_student_course` VALUES ('6', '10', '0');
INSERT INTO `anchor_student_course` VALUES ('6', '11', '0');
INSERT INTO `anchor_student_course` VALUES ('6', '12', '0');
INSERT INTO `anchor_student_course` VALUES ('6', '13', '0');
INSERT INTO `anchor_student_course` VALUES ('6', '14', '0');
INSERT INTO `anchor_student_course` VALUES ('6', '17', '0');
INSERT INTO `anchor_student_course` VALUES ('6', '20', '0');
INSERT INTO `anchor_student_course` VALUES ('7', '4', '165');
INSERT INTO `anchor_student_course` VALUES ('7', '5', '165');
INSERT INTO `anchor_student_course` VALUES ('7', '17', '165');
INSERT INTO `anchor_student_course` VALUES ('7', '18', '165');
INSERT INTO `anchor_student_course` VALUES ('7', '20', '165');
INSERT INTO `anchor_student_course` VALUES ('8', '2', '0');
INSERT INTO `anchor_student_course` VALUES ('8', '4', '0');
INSERT INTO `anchor_student_course` VALUES ('8', '16', '0');
INSERT INTO `anchor_student_course` VALUES ('8', '17', '0');
INSERT INTO `anchor_student_course` VALUES ('8', '18', '0');
INSERT INTO `anchor_student_course` VALUES ('8', '20', '0');
INSERT INTO `anchor_student_course` VALUES ('9', '2', '0');
INSERT INTO `anchor_student_course` VALUES ('9', '3', '0');
INSERT INTO `anchor_student_course` VALUES ('9', '4', '0');
INSERT INTO `anchor_student_course` VALUES ('9', '5', '0');
INSERT INTO `anchor_student_course` VALUES ('9', '13', '0');
INSERT INTO `anchor_student_course` VALUES ('9', '18', '0');
INSERT INTO `anchor_student_course` VALUES ('9', '19', '0');
INSERT INTO `anchor_student_course` VALUES ('9', '20', '0');
INSERT INTO `anchor_student_course` VALUES ('10', '3', '0');
INSERT INTO `anchor_student_course` VALUES ('10', '12', '0');
INSERT INTO `anchor_student_course` VALUES ('10', '13', '0');
INSERT INTO `anchor_student_course` VALUES ('10', '18', '0');
INSERT INTO `anchor_student_course` VALUES ('10', '19', '0');
INSERT INTO `anchor_student_course` VALUES ('10', '20', '0');
INSERT INTO `anchor_student_course` VALUES ('11', '14', '0');
INSERT INTO `anchor_student_course` VALUES ('11', '16', '0');
INSERT INTO `anchor_student_course` VALUES ('11', '18', '0');
INSERT INTO `anchor_student_course` VALUES ('11', '19', '0');
INSERT INTO `anchor_student_course` VALUES ('11', '20', '0');
INSERT INTO `anchor_student_course` VALUES ('12', '3', '0');
INSERT INTO `anchor_student_course` VALUES ('12', '12', '0');
INSERT INTO `anchor_student_course` VALUES ('12', '13', '0');
INSERT INTO `anchor_student_course` VALUES ('12', '18', '0');
INSERT INTO `anchor_student_course` VALUES ('12', '19', '0');
INSERT INTO `anchor_student_course` VALUES ('12', '20', '0');
INSERT INTO `anchor_student_course` VALUES ('13', '3', '100');
INSERT INTO `anchor_student_course` VALUES ('13', '13', '100');
INSERT INTO `anchor_student_course` VALUES ('13', '18', '100');
INSERT INTO `anchor_student_course` VALUES ('13', '19', '100');
INSERT INTO `anchor_student_course` VALUES ('13', '20', '100');
INSERT INTO `anchor_student_course` VALUES ('14', '3', '0');
INSERT INTO `anchor_student_course` VALUES ('14', '13', '0');
INSERT INTO `anchor_student_course` VALUES ('14', '18', '0');
INSERT INTO `anchor_student_course` VALUES ('14', '19', '0');
INSERT INTO `anchor_student_course` VALUES ('14', '20', '0');
INSERT INTO `anchor_student_course` VALUES ('15', '2', '0');
INSERT INTO `anchor_student_course` VALUES ('15', '3', '0');
INSERT INTO `anchor_student_course` VALUES ('15', '4', '0');
INSERT INTO `anchor_student_course` VALUES ('15', '5', '0');
INSERT INTO `anchor_student_course` VALUES ('15', '6', '0');
INSERT INTO `anchor_student_course` VALUES ('15', '7', '0');
INSERT INTO `anchor_student_course` VALUES ('15', '8', '0');
INSERT INTO `anchor_student_course` VALUES ('15', '9', '0');
INSERT INTO `anchor_student_course` VALUES ('15', '10', '0');
INSERT INTO `anchor_student_course` VALUES ('15', '11', '0');
INSERT INTO `anchor_student_course` VALUES ('15', '12', '0');
INSERT INTO `anchor_student_course` VALUES ('15', '13', '0');
INSERT INTO `anchor_student_course` VALUES ('15', '14', '0');
INSERT INTO `anchor_student_course` VALUES ('15', '15', '0');
INSERT INTO `anchor_student_course` VALUES ('15', '17', '0');
INSERT INTO `anchor_student_course` VALUES ('15', '18', '0');
INSERT INTO `anchor_student_course` VALUES ('15', '19', '0');
INSERT INTO `anchor_student_course` VALUES ('15', '20', '0');
INSERT INTO `anchor_student_course` VALUES ('16', '3', '0');
INSERT INTO `anchor_student_course` VALUES ('16', '13', '0');
INSERT INTO `anchor_student_course` VALUES ('16', '17', '0');
INSERT INTO `anchor_student_course` VALUES ('16', '18', '0');
INSERT INTO `anchor_student_course` VALUES ('16', '19', '0');
INSERT INTO `anchor_student_course` VALUES ('16', '20', '0');
INSERT INTO `anchor_student_course` VALUES ('17', '18', '100');
INSERT INTO `anchor_student_course` VALUES ('17', '20', '100');
INSERT INTO `anchor_student_course` VALUES ('18', '3', '33');
INSERT INTO `anchor_student_course` VALUES ('18', '13', '33');
INSERT INTO `anchor_student_course` VALUES ('18', '18', '33');
INSERT INTO `anchor_student_course` VALUES ('18', '19', '33');
INSERT INTO `anchor_student_course` VALUES ('18', '20', '33');
INSERT INTO `anchor_student_course` VALUES ('19', '3', '0');
INSERT INTO `anchor_student_course` VALUES ('19', '13', '0');
INSERT INTO `anchor_student_course` VALUES ('19', '18', '0');
INSERT INTO `anchor_student_course` VALUES ('19', '19', '0');
INSERT INTO `anchor_student_course` VALUES ('19', '20', '0');
INSERT INTO `anchor_student_course` VALUES ('20', '3', '0');
INSERT INTO `anchor_student_course` VALUES ('20', '8', '0');
INSERT INTO `anchor_student_course` VALUES ('20', '13', '0');
INSERT INTO `anchor_student_course` VALUES ('20', '18', '0');
INSERT INTO `anchor_student_course` VALUES ('20', '20', '0');
INSERT INTO `anchor_student_course` VALUES ('21', '3', '0');
INSERT INTO `anchor_student_course` VALUES ('21', '8', '0');
INSERT INTO `anchor_student_course` VALUES ('21', '13', '0');
INSERT INTO `anchor_student_course` VALUES ('21', '18', '0');
INSERT INTO `anchor_student_course` VALUES ('21', '20', '0');
INSERT INTO `anchor_student_course` VALUES ('22', '2', '0');
INSERT INTO `anchor_student_course` VALUES ('22', '3', '0');
INSERT INTO `anchor_student_course` VALUES ('22', '4', '0');
INSERT INTO `anchor_student_course` VALUES ('22', '5', '0');
INSERT INTO `anchor_student_course` VALUES ('22', '6', '0');
INSERT INTO `anchor_student_course` VALUES ('22', '7', '0');
INSERT INTO `anchor_student_course` VALUES ('22', '8', '0');
INSERT INTO `anchor_student_course` VALUES ('22', '9', '0');
INSERT INTO `anchor_student_course` VALUES ('22', '10', '0');
INSERT INTO `anchor_student_course` VALUES ('22', '11', '0');
INSERT INTO `anchor_student_course` VALUES ('22', '12', '0');
INSERT INTO `anchor_student_course` VALUES ('22', '13', '0');
INSERT INTO `anchor_student_course` VALUES ('22', '14', '0');
INSERT INTO `anchor_student_course` VALUES ('22', '15', '0');
INSERT INTO `anchor_student_course` VALUES ('22', '17', '0');
INSERT INTO `anchor_student_course` VALUES ('22', '18', '0');
INSERT INTO `anchor_student_course` VALUES ('22', '19', '0');
INSERT INTO `anchor_student_course` VALUES ('22', '20', '0');
INSERT INTO `anchor_student_course` VALUES ('23', '2', '0');
INSERT INTO `anchor_student_course` VALUES ('23', '3', '0');
INSERT INTO `anchor_student_course` VALUES ('23', '4', '0');
INSERT INTO `anchor_student_course` VALUES ('23', '5', '0');
INSERT INTO `anchor_student_course` VALUES ('23', '6', '0');
INSERT INTO `anchor_student_course` VALUES ('23', '7', '0');
INSERT INTO `anchor_student_course` VALUES ('23', '8', '0');
INSERT INTO `anchor_student_course` VALUES ('23', '9', '0');
INSERT INTO `anchor_student_course` VALUES ('23', '10', '0');
INSERT INTO `anchor_student_course` VALUES ('23', '11', '0');
INSERT INTO `anchor_student_course` VALUES ('23', '12', '0');
INSERT INTO `anchor_student_course` VALUES ('23', '13', '0');
INSERT INTO `anchor_student_course` VALUES ('23', '14', '0');
INSERT INTO `anchor_student_course` VALUES ('23', '15', '0');
INSERT INTO `anchor_student_course` VALUES ('23', '17', '0');
INSERT INTO `anchor_student_course` VALUES ('23', '18', '0');
INSERT INTO `anchor_student_course` VALUES ('23', '19', '0');
INSERT INTO `anchor_student_course` VALUES ('23', '20', '0');
INSERT INTO `anchor_student_course` VALUES ('24', '18', '80');
INSERT INTO `anchor_student_course` VALUES ('24', '20', '80');
INSERT INTO `anchor_student_course` VALUES ('25', '8', '0');
INSERT INTO `anchor_student_course` VALUES ('25', '10', '0');
INSERT INTO `anchor_student_course` VALUES ('25', '12', '0');
INSERT INTO `anchor_student_course` VALUES ('25', '13', '0');
INSERT INTO `anchor_student_course` VALUES ('25', '16', '0');
INSERT INTO `anchor_student_course` VALUES ('25', '17', '0');
INSERT INTO `anchor_student_course` VALUES ('25', '18', '0');
INSERT INTO `anchor_student_course` VALUES ('25', '19', '0');
INSERT INTO `anchor_student_course` VALUES ('25', '20', '0');
INSERT INTO `anchor_student_course` VALUES ('26', '3', '0');
INSERT INTO `anchor_student_course` VALUES ('26', '13', '0');
INSERT INTO `anchor_student_course` VALUES ('26', '16', '0');
INSERT INTO `anchor_student_course` VALUES ('26', '17', '0');
INSERT INTO `anchor_student_course` VALUES ('26', '18', '0');
INSERT INTO `anchor_student_course` VALUES ('26', '19', '0');
INSERT INTO `anchor_student_course` VALUES ('26', '20', '0');
INSERT INTO `anchor_student_course` VALUES ('27', '3', '65');
INSERT INTO `anchor_student_course` VALUES ('27', '13', '65');
INSERT INTO `anchor_student_course` VALUES ('27', '16', '65');
INSERT INTO `anchor_student_course` VALUES ('27', '17', '65');
INSERT INTO `anchor_student_course` VALUES ('27', '18', '65');
INSERT INTO `anchor_student_course` VALUES ('27', '19', '65');
INSERT INTO `anchor_student_course` VALUES ('27', '20', '65');
INSERT INTO `anchor_student_course` VALUES ('28', '3', '0');
INSERT INTO `anchor_student_course` VALUES ('28', '20', '0');
INSERT INTO `anchor_student_course` VALUES ('29', '3', '50');
INSERT INTO `anchor_student_course` VALUES ('29', '13', '50');
INSERT INTO `anchor_student_course` VALUES ('29', '18', '50');
INSERT INTO `anchor_student_course` VALUES ('29', '19', '50');
INSERT INTO `anchor_student_course` VALUES ('29', '20', '50');
INSERT INTO `anchor_student_course` VALUES ('30', '3', '0');
INSERT INTO `anchor_student_course` VALUES ('30', '13', '0');
INSERT INTO `anchor_student_course` VALUES ('30', '18', '0');
INSERT INTO `anchor_student_course` VALUES ('30', '19', '0');
INSERT INTO `anchor_student_course` VALUES ('30', '20', '0');
INSERT INTO `anchor_student_course` VALUES ('31', '3', '0');
INSERT INTO `anchor_student_course` VALUES ('31', '13', '0');
INSERT INTO `anchor_student_course` VALUES ('31', '18', '0');
INSERT INTO `anchor_student_course` VALUES ('31', '19', '0');
INSERT INTO `anchor_student_course` VALUES ('31', '20', '0');
INSERT INTO `anchor_student_course` VALUES ('32', '13', '0');
INSERT INTO `anchor_student_course` VALUES ('33', '3', '11.25');
INSERT INTO `anchor_student_course` VALUES ('33', '13', '11.25');
INSERT INTO `anchor_student_course` VALUES ('33', '18', '11.25');
INSERT INTO `anchor_student_course` VALUES ('33', '19', '11.25');
INSERT INTO `anchor_student_course` VALUES ('33', '20', '11.25');
INSERT INTO `anchor_student_course` VALUES ('34', '3', '0');
INSERT INTO `anchor_student_course` VALUES ('34', '13', '0');
INSERT INTO `anchor_student_course` VALUES ('35', '2', '0');
INSERT INTO `anchor_student_course` VALUES ('35', '3', '0');
INSERT INTO `anchor_student_course` VALUES ('35', '4', '0');
INSERT INTO `anchor_student_course` VALUES ('35', '5', '0');
INSERT INTO `anchor_student_course` VALUES ('35', '6', '0');
INSERT INTO `anchor_student_course` VALUES ('35', '7', '0');
INSERT INTO `anchor_student_course` VALUES ('35', '8', '0');
INSERT INTO `anchor_student_course` VALUES ('35', '9', '0');
INSERT INTO `anchor_student_course` VALUES ('35', '11', '0');
INSERT INTO `anchor_student_course` VALUES ('35', '12', '0');
INSERT INTO `anchor_student_course` VALUES ('35', '14', '0');
INSERT INTO `anchor_student_course` VALUES ('35', '15', '0');
INSERT INTO `anchor_student_course` VALUES ('35', '17', '0');
INSERT INTO `anchor_student_course` VALUES ('35', '18', '0');
INSERT INTO `anchor_student_course` VALUES ('35', '19', '0');
INSERT INTO `anchor_student_course` VALUES ('35', '20', '0');
INSERT INTO `anchor_student_course` VALUES ('36', '15', '100');
INSERT INTO `anchor_student_course` VALUES ('36', '18', '100');
INSERT INTO `anchor_student_course` VALUES ('36', '19', '100');
INSERT INTO `anchor_student_course` VALUES ('36', '20', '100');
INSERT INTO `anchor_student_course` VALUES ('37', '3', '0');
INSERT INTO `anchor_student_course` VALUES ('37', '5', '0');
INSERT INTO `anchor_student_course` VALUES ('37', '6', '0');
INSERT INTO `anchor_student_course` VALUES ('37', '8', '0');
INSERT INTO `anchor_student_course` VALUES ('37', '18', '0');
INSERT INTO `anchor_student_course` VALUES ('37', '19', '0');
INSERT INTO `anchor_student_course` VALUES ('37', '20', '0');
INSERT INTO `anchor_student_course` VALUES ('38', '2', '62.5');
INSERT INTO `anchor_student_course` VALUES ('38', '3', '62.5');
INSERT INTO `anchor_student_course` VALUES ('38', '13', '62.5');
INSERT INTO `anchor_student_course` VALUES ('38', '15', '62.5');
INSERT INTO `anchor_student_course` VALUES ('38', '20', '62.5');
INSERT INTO `anchor_student_course` VALUES ('39', '3', '0.86207');
INSERT INTO `anchor_student_course` VALUES ('39', '18', '0.86207');
INSERT INTO `anchor_student_course` VALUES ('39', '19', '0.86207');
INSERT INTO `anchor_student_course` VALUES ('39', '20', '0.86207');
INSERT INTO `anchor_student_course` VALUES ('40', '3', '0');
INSERT INTO `anchor_student_course` VALUES ('40', '13', '0');
INSERT INTO `anchor_student_course` VALUES ('40', '20', '0');
INSERT INTO `anchor_student_course` VALUES ('41', '3', '22.2222');
INSERT INTO `anchor_student_course` VALUES ('41', '13', '22.2222');
INSERT INTO `anchor_student_course` VALUES ('41', '20', '22.2222');
INSERT INTO `anchor_student_course` VALUES ('42', '3', '0');
INSERT INTO `anchor_student_course` VALUES ('42', '4', '0');
INSERT INTO `anchor_student_course` VALUES ('42', '15', '0');
INSERT INTO `anchor_student_course` VALUES ('42', '19', '0');
INSERT INTO `anchor_student_course` VALUES ('42', '20', '0');
INSERT INTO `anchor_student_course` VALUES ('43', '17', '0');
INSERT INTO `anchor_student_course` VALUES ('43', '18', '0');
INSERT INTO `anchor_student_course` VALUES ('43', '19', '0');
INSERT INTO `anchor_student_course` VALUES ('43', '20', '0');
INSERT INTO `anchor_student_course` VALUES ('44', '14', '3');
INSERT INTO `anchor_student_course` VALUES ('44', '16', '3');
INSERT INTO `anchor_student_course` VALUES ('44', '18', '3');
INSERT INTO `anchor_student_course` VALUES ('44', '19', '3');
INSERT INTO `anchor_student_course` VALUES ('44', '20', '3');
INSERT INTO `anchor_student_course` VALUES ('45', '17', '0');
INSERT INTO `anchor_student_course` VALUES ('45', '18', '0');
INSERT INTO `anchor_student_course` VALUES ('45', '19', '0');
INSERT INTO `anchor_student_course` VALUES ('45', '20', '0');
INSERT INTO `anchor_student_course` VALUES ('46', '3', '0');
INSERT INTO `anchor_student_course` VALUES ('46', '13', '0');
INSERT INTO `anchor_student_course` VALUES ('46', '20', '0');
INSERT INTO `anchor_student_course` VALUES ('47', '16', '0');
INSERT INTO `anchor_student_course` VALUES ('47', '17', '0');
INSERT INTO `anchor_student_course` VALUES ('47', '20', '0');
INSERT INTO `anchor_student_course` VALUES ('48', '4', '1');
INSERT INTO `anchor_student_course` VALUES ('48', '17', '1');
INSERT INTO `anchor_student_course` VALUES ('48', '20', '1');
INSERT INTO `anchor_student_course` VALUES ('49', '20', '0');
INSERT INTO `anchor_student_course` VALUES ('50', '17', '22.5');
INSERT INTO `anchor_student_course` VALUES ('50', '18', '22.5');
INSERT INTO `anchor_student_course` VALUES ('50', '19', '22.5');
INSERT INTO `anchor_student_course` VALUES ('50', '20', '22.5');
INSERT INTO `anchor_student_course` VALUES ('51', '16', '0');
INSERT INTO `anchor_student_course` VALUES ('51', '20', '0');
INSERT INTO `anchor_student_course` VALUES ('52', '17', '0');
INSERT INTO `anchor_student_course` VALUES ('52', '18', '0');
INSERT INTO `anchor_student_course` VALUES ('52', '19', '0');
INSERT INTO `anchor_student_course` VALUES ('52', '20', '0');
INSERT INTO `anchor_student_course` VALUES ('53', '3', '0');
INSERT INTO `anchor_student_course` VALUES ('53', '13', '0');
INSERT INTO `anchor_student_course` VALUES ('53', '15', '0');
INSERT INTO `anchor_student_course` VALUES ('53', '17', '0');
INSERT INTO `anchor_student_course` VALUES ('53', '18', '0');
INSERT INTO `anchor_student_course` VALUES ('53', '19', '0');
INSERT INTO `anchor_student_course` VALUES ('53', '20', '0');
INSERT INTO `anchor_student_course` VALUES ('54', '3', '33.3333');
INSERT INTO `anchor_student_course` VALUES ('54', '13', '33.3333');
INSERT INTO `anchor_student_course` VALUES ('54', '15', '33.3333');
INSERT INTO `anchor_student_course` VALUES ('54', '17', '33.3333');
INSERT INTO `anchor_student_course` VALUES ('54', '18', '33.3333');
INSERT INTO `anchor_student_course` VALUES ('54', '19', '33.3333');
INSERT INTO `anchor_student_course` VALUES ('54', '20', '33.3333');
INSERT INTO `anchor_student_course` VALUES ('55', '3', '0');
INSERT INTO `anchor_student_course` VALUES ('55', '15', '0');
INSERT INTO `anchor_student_course` VALUES ('55', '17', '0');
INSERT INTO `anchor_student_course` VALUES ('55', '18', '0');
INSERT INTO `anchor_student_course` VALUES ('55', '19', '0');
INSERT INTO `anchor_student_course` VALUES ('55', '20', '0');
INSERT INTO `anchor_student_course` VALUES ('56', '3', '0');
INSERT INTO `anchor_student_course` VALUES ('56', '15', '0');
INSERT INTO `anchor_student_course` VALUES ('56', '17', '0');
INSERT INTO `anchor_student_course` VALUES ('56', '18', '0');
INSERT INTO `anchor_student_course` VALUES ('56', '19', '0');
INSERT INTO `anchor_student_course` VALUES ('56', '20', '0');
INSERT INTO `anchor_student_course` VALUES ('57', '3', '10');
INSERT INTO `anchor_student_course` VALUES ('57', '20', '10');
INSERT INTO `anchor_student_course` VALUES ('58', '20', '43');
INSERT INTO `anchor_student_course` VALUES ('59', '20', '0');
INSERT INTO `anchor_student_course` VALUES ('60', '3', '0');
INSERT INTO `anchor_student_course` VALUES ('60', '19', '0');
INSERT INTO `anchor_student_course` VALUES ('60', '20', '0');
INSERT INTO `anchor_student_course` VALUES ('61', '3', '0');
INSERT INTO `anchor_student_course` VALUES ('61', '17', '0');
INSERT INTO `anchor_student_course` VALUES ('61', '18', '0');
INSERT INTO `anchor_student_course` VALUES ('61', '19', '0');
INSERT INTO `anchor_student_course` VALUES ('61', '20', '0');
INSERT INTO `anchor_student_course` VALUES ('62', '3', '0');
INSERT INTO `anchor_student_course` VALUES ('62', '17', '0');
INSERT INTO `anchor_student_course` VALUES ('62', '18', '0');
INSERT INTO `anchor_student_course` VALUES ('62', '19', '0');
INSERT INTO `anchor_student_course` VALUES ('62', '20', '0');
INSERT INTO `anchor_student_course` VALUES ('63', '3', '26.6667');
INSERT INTO `anchor_student_course` VALUES ('63', '20', '26.6667');
INSERT INTO `anchor_student_course` VALUES ('64', '3', '75');
INSERT INTO `anchor_student_course` VALUES ('64', '19', '75');
INSERT INTO `anchor_student_course` VALUES ('64', '20', '75');
INSERT INTO `anchor_student_course` VALUES ('65', '16', '0');
INSERT INTO `anchor_student_course` VALUES ('65', '18', '0');
INSERT INTO `anchor_student_course` VALUES ('65', '20', '0');
INSERT INTO `anchor_student_course` VALUES ('66', '17', '0');
INSERT INTO `anchor_student_course` VALUES ('66', '18', '0');
INSERT INTO `anchor_student_course` VALUES ('66', '19', '0');
INSERT INTO `anchor_student_course` VALUES ('66', '20', '0');
INSERT INTO `anchor_student_course` VALUES ('67', '17', '0');
INSERT INTO `anchor_student_course` VALUES ('67', '18', '0');
INSERT INTO `anchor_student_course` VALUES ('67', '19', '0');
INSERT INTO `anchor_student_course` VALUES ('67', '20', '0');
INSERT INTO `anchor_student_course` VALUES ('68', '3', '0');
INSERT INTO `anchor_student_course` VALUES ('68', '19', '0');
INSERT INTO `anchor_student_course` VALUES ('68', '20', '0');
INSERT INTO `anchor_student_course` VALUES ('69', '18', '0');
INSERT INTO `anchor_student_course` VALUES ('69', '20', '0');
INSERT INTO `anchor_student_course` VALUES ('70', '3', '7.89474');
INSERT INTO `anchor_student_course` VALUES ('70', '18', '7.89474');
INSERT INTO `anchor_student_course` VALUES ('70', '19', '7.89474');
INSERT INTO `anchor_student_course` VALUES ('70', '20', '7.89474');
INSERT INTO `anchor_student_course` VALUES ('71', '3', '0');
INSERT INTO `anchor_student_course` VALUES ('71', '18', '0');
INSERT INTO `anchor_student_course` VALUES ('71', '19', '0');
INSERT INTO `anchor_student_course` VALUES ('71', '20', '0');
INSERT INTO `anchor_student_course` VALUES ('72', '3', '0');
INSERT INTO `anchor_student_course` VALUES ('72', '18', '0');
INSERT INTO `anchor_student_course` VALUES ('72', '19', '0');
INSERT INTO `anchor_student_course` VALUES ('72', '20', '0');
INSERT INTO `anchor_student_course` VALUES ('73', '3', '0');
INSERT INTO `anchor_student_course` VALUES ('73', '18', '0');
INSERT INTO `anchor_student_course` VALUES ('73', '19', '0');
INSERT INTO `anchor_student_course` VALUES ('73', '20', '0');
INSERT INTO `anchor_student_course` VALUES ('74', '4', '0');
INSERT INTO `anchor_student_course` VALUES ('74', '18', '0');
INSERT INTO `anchor_student_course` VALUES ('74', '20', '0');
INSERT INTO `anchor_student_course` VALUES ('75', '20', '0');
INSERT INTO `anchor_student_course` VALUES ('76', '20', '0');
INSERT INTO `anchor_student_course` VALUES ('77', '4', '0');
INSERT INTO `anchor_student_course` VALUES ('77', '18', '0');
INSERT INTO `anchor_student_course` VALUES ('77', '20', '0');
INSERT INTO `anchor_student_course` VALUES ('78', '18', '7.77777');
INSERT INTO `anchor_student_course` VALUES ('78', '20', '7.77777');
INSERT INTO `anchor_student_course` VALUES ('79', '18', '0');
INSERT INTO `anchor_student_course` VALUES ('79', '20', '0');
INSERT INTO `anchor_student_course` VALUES ('80', '20', '0');
INSERT INTO `anchor_student_course` VALUES ('81', '3', '40');
INSERT INTO `anchor_student_course` VALUES ('81', '13', '40');
INSERT INTO `anchor_student_course` VALUES ('81', '17', '40');
INSERT INTO `anchor_student_course` VALUES ('81', '18', '40');
INSERT INTO `anchor_student_course` VALUES ('81', '19', '40');
INSERT INTO `anchor_student_course` VALUES ('81', '20', '40');
INSERT INTO `anchor_student_course` VALUES ('82', '3', '99');
INSERT INTO `anchor_student_course` VALUES ('82', '13', '99');
INSERT INTO `anchor_student_course` VALUES ('82', '17', '99');
INSERT INTO `anchor_student_course` VALUES ('82', '18', '99');
INSERT INTO `anchor_student_course` VALUES ('82', '19', '99');
INSERT INTO `anchor_student_course` VALUES ('82', '20', '99');
INSERT INTO `anchor_student_course` VALUES ('83', '18', '0');
INSERT INTO `anchor_student_course` VALUES ('83', '19', '0');
INSERT INTO `anchor_student_course` VALUES ('83', '20', '0');
INSERT INTO `anchor_student_course` VALUES ('84', '18', '0');
INSERT INTO `anchor_student_course` VALUES ('84', '20', '0');
INSERT INTO `anchor_student_course` VALUES ('85', '18', '0');
INSERT INTO `anchor_student_course` VALUES ('85', '20', '0');
INSERT INTO `anchor_student_course` VALUES ('86', '20', '0');
INSERT INTO `anchor_student_course` VALUES ('87', '18', '0');
INSERT INTO `anchor_student_course` VALUES ('87', '20', '0');
INSERT INTO `anchor_student_course` VALUES ('88', '3', '104.167');
INSERT INTO `anchor_student_course` VALUES ('88', '13', '104.167');
INSERT INTO `anchor_student_course` VALUES ('88', '17', '104.167');
INSERT INTO `anchor_student_course` VALUES ('88', '18', '104.167');
INSERT INTO `anchor_student_course` VALUES ('88', '19', '104.167');
INSERT INTO `anchor_student_course` VALUES ('88', '20', '104.167');
INSERT INTO `anchor_student_course` VALUES ('89', '18', '0');
INSERT INTO `anchor_student_course` VALUES ('90', '3', '9');
INSERT INTO `anchor_student_course` VALUES ('90', '13', '9');
INSERT INTO `anchor_student_course` VALUES ('90', '18', '9');
INSERT INTO `anchor_student_course` VALUES ('90', '19', '9');
INSERT INTO `anchor_student_course` VALUES ('90', '20', '9');
INSERT INTO `anchor_student_course` VALUES ('91', '3', '9');
INSERT INTO `anchor_student_course` VALUES ('91', '13', '9');
INSERT INTO `anchor_student_course` VALUES ('91', '18', '9');
INSERT INTO `anchor_student_course` VALUES ('91', '19', '9');
INSERT INTO `anchor_student_course` VALUES ('91', '20', '9');
INSERT INTO `anchor_student_course` VALUES ('92', '3', '9');
INSERT INTO `anchor_student_course` VALUES ('92', '13', '9');
INSERT INTO `anchor_student_course` VALUES ('92', '18', '9');
INSERT INTO `anchor_student_course` VALUES ('92', '19', '9');
INSERT INTO `anchor_student_course` VALUES ('92', '20', '9');
INSERT INTO `anchor_student_course` VALUES ('93', '3', '9');
INSERT INTO `anchor_student_course` VALUES ('93', '13', '9');
INSERT INTO `anchor_student_course` VALUES ('93', '18', '9');
INSERT INTO `anchor_student_course` VALUES ('93', '19', '9');
INSERT INTO `anchor_student_course` VALUES ('93', '20', '9');

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
) ENGINE=InnoDB AUTO_INCREMENT=94 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of anchor_students
-- ----------------------------
INSERT INTO `anchor_students` VALUES ('1', '2', 'Admin User', 'info@nccsoft.vn');
INSERT INTO `anchor_students` VALUES ('2', '21', 'Admin User', 'admin@local.host');
INSERT INTO `anchor_students` VALUES ('3', '22', 'Hao Nguyen', 'hao.nguyen@nccsoft.vn');
INSERT INTO `anchor_students` VALUES ('4', '23', 'Tiep Phan', 'tiep.phan@nccsoft.vn');
INSERT INTO `anchor_students` VALUES ('5', '26', 'Admin User', 'ntvinh2014@gmail.com');
INSERT INTO `anchor_students` VALUES ('6', '27', 'Admin User', 'admin@local.host');
INSERT INTO `anchor_students` VALUES ('7', '43', 'Vu Cuong', 'borugairu.love@gmail.com');
INSERT INTO `anchor_students` VALUES ('8', '44', 'Admin User', 'tunganhmta@gmail.com');
INSERT INTO `anchor_students` VALUES ('9', '45', 'Admin Minh User', 'minhnd@teca.com.vn');
INSERT INTO `anchor_students` VALUES ('10', '81', 'HaCaAdmin Host119', 'hanv@tecapro.com.vn');
INSERT INTO `anchor_students` VALUES ('11', '101', 'min nguyen', 'haonh.87@gmail.com');
INSERT INTO `anchor_students` VALUES ('12', '102', 'student1 Nguyen', 'student1@tecapro.com.vn');
INSERT INTO `anchor_students` VALUES ('13', '103', 'student2 Nguyen', 'student2@tecapro.com.vn');
INSERT INTO `anchor_students` VALUES ('14', '104', 'student3 Nguyen', 'student3@tecapro.com.vn');
INSERT INTO `anchor_students` VALUES ('15', '121', 'Admin User', 'admin@nccsoft.vn');
INSERT INTO `anchor_students` VALUES ('16', '122', 'Admin User', 'host253@nccsoft.vn');
INSERT INTO `anchor_students` VALUES ('17', '141', 'Teacher Anna', 'tieppt12@gmail.com');
INSERT INTO `anchor_students` VALUES ('18', '142', 'student user1', 'student@gmail.com');
INSERT INTO `anchor_students` VALUES ('19', '143', 'teacher usser1', 'teacher1@gmail.com');
INSERT INTO `anchor_students` VALUES ('20', '144', 'Student One', 'student1@nccsoft.vn');
INSERT INTO `anchor_students` VALUES ('21', '145', 'Teacher One', 'teacher1@nccsoft.vn');
INSERT INTO `anchor_students` VALUES ('22', '146', 'Student One', 'student1@nccsoft.vn');
INSERT INTO `anchor_students` VALUES ('23', '147', 'Teacher One', 'teacher1@nccsoft.vn');
INSERT INTO `anchor_students` VALUES ('24', '148', 'Peter Han', 'tieppt1.2@gmail.com');
INSERT INTO `anchor_students` VALUES ('25', '149', 'student one', 'student@student.com');
INSERT INTO `anchor_students` VALUES ('26', '150', 'student1 Nguyen', 'student1@tecapro.com.vn');
INSERT INTO `anchor_students` VALUES ('27', '151', 'student2 nguyen', 'student2@tecapro.com');
INSERT INTO `anchor_students` VALUES ('28', '162', 'Teacher Tuynh', 'tuynh.nguyen@nccsoft.vn');
INSERT INTO `anchor_students` VALUES ('29', '163', 'Hanv Teacher1 Nguyen', 'teacher1@tecapro.com.vn');
INSERT INTO `anchor_students` VALUES ('30', '164', 'Teacher_1 Nguyen', 'teacher1@hanv.com.vn');
INSERT INTO `anchor_students` VALUES ('31', '165', 'Teacher_2 Nguyen', 'teacher2@hanv.com');
INSERT INTO `anchor_students` VALUES ('32', '166', 'Student N1', 'student_n1@nccsoft.vn');
INSERT INTO `anchor_students` VALUES ('33', '167', 'Hanv Student1 Nguyen', 'hanv_student1@hanv.com');
INSERT INTO `anchor_students` VALUES ('34', '168', 'Teacher N1', 'teacher_n1@nccsoft.vn');
INSERT INTO `anchor_students` VALUES ('35', '170', 'user 2', 'user@user2.user');
INSERT INTO `anchor_students` VALUES ('36', '172', 'van vu', 'van@gmail.com');
INSERT INTO `anchor_students` VALUES ('37', '173', 'teacher van', 'van1@gmail.com');
INSERT INTO `anchor_students` VALUES ('38', '180', 'teacher my', 'mydt@gmail.com');
INSERT INTO `anchor_students` VALUES ('39', '181', 'Student Tuynh', 'nguyenvantuynh95@gmail.com');
INSERT INTO `anchor_students` VALUES ('40', '183', 'student my', 'mdydf@gmail.com');
INSERT INTO `anchor_students` VALUES ('41', '201', 'Teacher dao', 'dao1234@gmail.com');
INSERT INTO `anchor_students` VALUES ('42', '221', 'Admin User', 'admin@admin.admin');
INSERT INTO `anchor_students` VALUES ('43', '222', 'teacher van1', 'vuvan1@gmail.com');
INSERT INTO `anchor_students` VALUES ('44', '223', 'ncc teacher', 'hao.nguyen1@nccsoft.vn');
INSERT INTO `anchor_students` VALUES ('45', '226', 'teacher 249_1', 'teacher249_1@gmail.com');
INSERT INTO `anchor_students` VALUES ('46', '229', 'Student dao12345', 'dao12345@gmail.com');
INSERT INTO `anchor_students` VALUES ('47', '241', 'teacher tunganh', 'tunganhmta@gmail.com');
INSERT INTO `anchor_students` VALUES ('48', '242', 'studen tunganh', 'tunganhmta123@gmail.com');
INSERT INTO `anchor_students` VALUES ('49', '243', 'student tunganh123', 'tunganhmta2@gmail.com');
INSERT INTO `anchor_students` VALUES ('50', '244', 'student van1', 'student_van1@gmail.com');
INSERT INTO `anchor_students` VALUES ('51', '245', 'student tunganh', 'tunganh123@gmail.com');
INSERT INTO `anchor_students` VALUES ('52', '246', 'teacher van4', 'van4@gmail.com');
INSERT INTO `anchor_students` VALUES ('53', '247', 'teacher my', 'my@gmail.com');
INSERT INTO `anchor_students` VALUES ('54', '248', 'my doan', 'mystd@gmail.com');
INSERT INTO `anchor_students` VALUES ('55', '249', 'Teacher dao', 'dao333@gmail.com');
INSERT INTO `anchor_students` VALUES ('56', '250', 'Student 1995', 'dao1995@gmail.com');
INSERT INTO `anchor_students` VALUES ('57', '261', 'thuong thuong', 'thuongthuong95pt@gmail.com');
INSERT INTO `anchor_students` VALUES ('58', '262', 'student 123', 'nhungtuyet9525@gmail.com');
INSERT INTO `anchor_students` VALUES ('59', '263', 'Nhung student', 'nhung@gmail.com');
INSERT INTO `anchor_students` VALUES ('60', '264', 'giao vien', 'nhimlicy@gmail.com');
INSERT INTO `anchor_students` VALUES ('61', '265', 'Student Tuynh', 'tuynh.nguyen@nccsoft.vn');
INSERT INTO `anchor_students` VALUES ('62', '266', 'Teacher Tuynh', 'nguyenvantuynh95@gmail.com');
INSERT INTO `anchor_students` VALUES ('63', '267', 'hoc sinh', 'ngrn@gmail.com');
INSERT INTO `anchor_students` VALUES ('64', '268', 'nhung teacher', 'nhunguet123@gmail.com');
INSERT INTO `anchor_students` VALUES ('65', '269', 'Admin User', 'thiennccsoft@gmail.com');
INSERT INTO `anchor_students` VALUES ('66', '281', 'student tunganh', 'mta@gmail.com');
INSERT INTO `anchor_students` VALUES ('67', '282', 'student tung anhh', 'mtas@gmail.com');
INSERT INTO `anchor_students` VALUES ('68', '283', 'Dao Teacher', 'Teacher006@gmail.com');
INSERT INTO `anchor_students` VALUES ('69', '301', 'ht ht', 'th@gmail.com');
INSERT INTO `anchor_students` VALUES ('70', '302', 'hoc sinh', 'th@gmail.com');
INSERT INTO `anchor_students` VALUES ('71', '303', 'giao vien', 'ewf@gmail.com');
INSERT INTO `anchor_students` VALUES ('72', '304', 'teacher _N', 'nhung@gmail.com');
INSERT INTO `anchor_students` VALUES ('73', '305', 'student _N', 'nhung123@gmail.com');
INSERT INTO `anchor_students` VALUES ('74', '306', 'student tunganh', '12234@gmail.com');
INSERT INTO `anchor_students` VALUES ('75', '321', 'student 139_1', 'student_139_1@gmail.com');
INSERT INTO `anchor_students` VALUES ('76', '322', 'teacher 139_1', 'teacher_139_1@gmail.com');
INSERT INTO `anchor_students` VALUES ('77', '323', 'student tunglam', 'tunganh1234@yahoo.com');
INSERT INTO `anchor_students` VALUES ('78', '341', 'name sub name', 'st1@gmail.com');
INSERT INTO `anchor_students` VALUES ('79', '342', 'thien thien', 'thien1@gmail.com');
INSERT INTO `anchor_students` VALUES ('80', '361', 'hoc sinh 2', 'thuong@gmail.com');
INSERT INTO `anchor_students` VALUES ('81', '362', 'student 253', 'student_253@gmail.com');
INSERT INTO `anchor_students` VALUES ('82', '363', 'student 253_1', 'student_253_1@gmail.com');
INSERT INTO `anchor_students` VALUES ('83', '401', 'hoc sinh 1', 'thh@gmail.com');
INSERT INTO `anchor_students` VALUES ('84', '421', 'teacher teacher', 'teacher1@gmail.com');
INSERT INTO `anchor_students` VALUES ('85', '481', 'teacher tunganh123', 'tunganh123@gmail.com');
INSERT INTO `anchor_students` VALUES ('86', '501', 'TPhan Adm', 'tiepphan@nccsoft.vn');
INSERT INTO `anchor_students` VALUES ('87', '523', 'Tiep Phan', 'tiep.phan@nccsoft.vn');
INSERT INTO `anchor_students` VALUES ('88', '543', 'Tuynh Student', 'nguyenvantuynh95@gmail.com');
INSERT INTO `anchor_students` VALUES ('89', '584', 'cuong vu', 'cuong@gmail.com');
INSERT INTO `anchor_students` VALUES ('90', '604', 'student 253_v', 'huongh3k45@gmail.com');
INSERT INTO `anchor_students` VALUES ('91', '624', 'student 253_t', 'thiennccsoft@gmail.com');
INSERT INTO `anchor_students` VALUES ('92', '664', 'student 253_2', 'student_253_2@gmail.com');
INSERT INTO `anchor_students` VALUES ('93', '667', 'student 253_2', 'student_253_5@gmail.com');
