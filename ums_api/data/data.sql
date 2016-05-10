-- MySQL dump 10.13  Distrib 5.7.9, for Win64 (x86_64)
--
-- Host: localhost    Database: usermanagement
-- ------------------------------------------------------
-- Server version	5.7.12-log

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `permissions`
--

DROP TABLE IF EXISTS `permissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `permissions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `key` varchar(150) DEFAULT NULL,
  `note` varchar(450) DEFAULT NULL,
  `parentid` int(11) DEFAULT NULL,
  `status` smallint(6) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parentid` (`parentid`),
  KEY `status` (`status`),
  KEY `key` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `permissions`
--

LOCK TABLES `permissions` WRITE;
/*!40000 ALTER TABLE `permissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `permissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `rolegroup`
--

DROP TABLE IF EXISTS `rolegroup`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `rolegroup` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(250) DEFAULT NULL,
  `level` int(11) DEFAULT NULL,
  `datecreate` int(11) DEFAULT NULL,
  `usercreate` int(11) DEFAULT NULL,
  `permissions` text,
  `manageid` text,
  PRIMARY KEY (`id`),
  KEY `level` (`level`),
  KEY `sort` (`datecreate`),
  KEY `usercreate_idx` (`usercreate`),
  CONSTRAINT `ucreate` FOREIGN KEY (`usercreate`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `rolegroup`
--

LOCK TABLES `rolegroup` WRITE;
/*!40000 ALTER TABLE `rolegroup` DISABLE KEYS */;
INSERT INTO `rolegroup` VALUES (1,'Super Administrator',1,NULL,1,'loginsystem,news,news_view,news_add,news_update,news_updatestatus,news_delete,albummedia,albummedia_view,albummedia_add,albummedia_update,albummedia_delete,category,category_view,category_add,category_update,category_delete,rolegroup,rolegroup_view,rolegroup_add,rolegroup_update,rolegroup_delete,user,user_view,user_add,user_update,user_delete,user_role,categoryview,categoryview_view,categoryview_add,categoryview_update,categoryview_delete,articleview,articleview_view,articleview_add,articleview_update,articleview_delete,config,config_view,config_add,config_update,config_delete,classobj,classobj_view,classobj_add,classobj_update,classobj_delete,pupil,pupil_view,pupil_add,pupil_update,pupil_delete,pupil_report,pupil_reportupdatestatus,pupil_notify,teacher,teacher_view,teacher_add,teacher_update,teacher_delete,rprequestlog,rprequestlog_view,rprequestlog_add,rprequestlog_update,rprequestlog_delete,timetable,timetable_view,timetable_add,timetable_update,timetable_delete,tuition,tuition_view,tuition_add,tuition_update,tuition_delete,event,event_view,event_add,event_update,event_delete,userpostition,userpostition_view,userpostition_add,userpostition_update,userpostition_delete',NULL),(2,'Mod',2,NULL,1,NULL,NULL),(3,'Nhóm quyền test',3,1461122848,1,'loginsystem,categoryview,categoryview_view,categoryview_add,categoryview_update,categoryview_delete,event,event_view,event_add,event_update,event_delete',NULL);
/*!40000 ALTER TABLE `rolegroup` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(45) DEFAULT NULL,
  `password` varchar(45) DEFAULT NULL,
  `firstname` varchar(65) DEFAULT NULL,
  `lastname` varchar(65) DEFAULT NULL,
  `type` smallint(6) DEFAULT NULL,
  `avatar` text,
  `dob` int(11) DEFAULT NULL,
  `email` varchar(250) DEFAULT NULL,
  `address` text,
  `phone` varchar(20) DEFAULT NULL,
  `datecreate` int(11) DEFAULT NULL,
  `usercreate` int(11) DEFAULT NULL,
  `gender` smallint(6) DEFAULT NULL,
  `private_permission` text,
  `flags` varchar(45) DEFAULT NULL,
  `classid` int(11) DEFAULT NULL,
  `activekey` varchar(45) DEFAULT NULL,
  `fbid` varchar(45) DEFAULT NULL,
  `fbemail` varchar(255) DEFAULT NULL,
  `status` smallint(6) DEFAULT NULL,
  `active_register` varchar(32) DEFAULT NULL,
  `phone2` varchar(25) DEFAULT NULL,
  `father_name` varchar(150) DEFAULT NULL,
  `mother_name` varchar(150) DEFAULT NULL,
  `captions` text,
  `pos_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username_2` (`username`),
  KEY `login` (`username`,`password`),
  KEY `username` (`username`),
  KEY `type` (`type`),
  KEY `phone` (`phone`),
  KEY `sorts` (`datecreate`),
  KEY `gender` (`gender`),
  KEY `usercreate` (`usercreate`),
  KEY `email` (`email`),
  KEY `sort_firstname` (`firstname`),
  KEY `sort_lastname` (`lastname`),
  KEY `flag` (`flags`),
  KEY `classid` (`classid`),
  KEY `status` (`status`),
  KEY `activeregister` (`active_register`),
  KEY `pos_id` (`pos_id`),
  CONSTRAINT `usercreate` FOREIGN KEY (`usercreate`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'admin','14e1b600b1fd579f47433b88e8d85291','Nguyễn Hoàng','Việt',1,'uploads/2016/03/16//picture/f3a369bb43b7c13bcc7693250c6c40c5_desert.jpg',655146000,'vietpiano@gmail.com','Thanh Quang - An Thượng - Hoài Đức - Hà Nội','0983488938',NULL,1,1,NULL,'system',NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(2,'spcom','113969485f92c6e5c66b9e503222d7fd','Nguyen The','Co',1,'uploads/2016/03/17//picture/7e4051c64ba1a14a1f2c04f84f839742_desert.jpg',709833600,'nhacdjchamvn@gmail.com','Xóm Chợ - An Khánh - Hoài Đức - Hà Nội','0903430692',1458199036,NULL,1,NULL,'system',NULL,'EKBw1sU9g9','','',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(3,'vietnh','14e1b600b1fd579f47433b88e8d85291','Nguyễn Hoàng','Việt',NULL,NULL,655146000,'vietpiano2@gmail.com','Thanh Quang - An Thượng - Hoài Đức - Hà Nội','0983488938',1458887064,NULL,1,NULL,'pupil',1461582251,NULL,'804967039637268','vietpiano@gmail.com',1,NULL,NULL,NULL,NULL,NULL,NULL),(4,'anhnv','14e1b600b1fd579f47433b88e8d85291','Nguyễn Văn','Anh',NULL,NULL,567968400,NULL,'','',1459066743,1,1,NULL,'teacher',NULL,NULL,'','',NULL,NULL,NULL,NULL,NULL,'<p>Giới thiệu g&igrave; đ&oacute;</p>',NULL),(5,'supercom','b2e4e43a490cd5f632b1a19b2c5b555a','Nguyễn Thế','Cơ',NULL,NULL,709837200,'nhacdjchamvn@gmail.com','164 Khuất Duy Tiến','0903430692',1459247994,NULL,1,NULL,'pupil',1461582251,NULL,'1143800532305944','nhacdjchamvn@gmail.com',1,'6c43b8d08efb4a6bcf2281dad6f8a00b','','','',NULL,NULL),(7,'DarkMatar','b2e4e43a490cd5f632b1a19b2c5b555a','Nguyen','Van Ben',NULL,NULL,-25200,'dark@gmail.com','Ha Noi','0909090909',1459427637,NULL,1,NULL,'pupil',1461582251,NULL,'','',NULL,NULL,NULL,NULL,NULL,NULL,NULL),(8,'viet','14e1b600b1fd579f47433b88e8d85291','AA','AAÂ',NULL,NULL,631126800,'abc@gmail.com','','',1461122878,NULL,NULL,NULL,'system',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(31,'annt','123456','Nguyễn Văn','An',NULL,NULL,788893200,'annt@gmail.com','Hà Tây Cũ','988888888',1461744193,NULL,NULL,NULL,'pupil',1461744193,NULL,NULL,NULL,NULL,'d7234383efe0e583aedeb474829d0c40','433333333','Nguyễn Văn Bố','Nguyễn Thị Mẹ',NULL,NULL),(32,'cont','123456','Nguyễn Thế','Cơ',NULL,NULL,-2147483648,'supercom@outlook.com','Hà Nội Mới','966666666',1461744193,NULL,NULL,NULL,'pupil',1461744194,NULL,NULL,NULL,NULL,'3c118b5f289427aca6e1bcb820dad0f8','8888888','Trần Thế Anh','Nguyễn Phương Mai',NULL,NULL),(37,'vinhquangle','165d564ef9fa6c1f7df49ac1f4de375c','Lê Quang','Vinh',NULL,NULL,497725200,'vinhquangle@wsi.vn','C4, Tập thể Trung Tự, Q. Đống Đa, Hà Nội','0915160631',1461754244,NULL,1,NULL,'guest',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL),(39,'abcdef','14e1b600b1fd579f47433b88e8d85291','Nguyễn Văn','ANC',NULL,NULL,915123600,'vietpiano@gmail.com','','',1461769915,1,1,NULL,'pupil',1461744193,NULL,NULL,NULL,1,'fe59c6844d00c85c13e642a4918e05c1','','','',NULL,NULL),(40,'wsii3.3','a9065ea6028515adb4d8bdbc7e12bf27','Nguyễn An','Bình',NULL,NULL,1082653200,'anbinh2304@gmail.com','phòng 211, phố Ngụy Như Kon Tum','01646483912',1462075609,NULL,0,NULL,'guest',NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_role`
--

DROP TABLE IF EXISTS `user_role`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userid` int(11) DEFAULT NULL,
  `roleid` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `userid` (`userid`),
  KEY `roleid` (`roleid`),
  KEY `combine` (`userid`,`roleid`),
  CONSTRAINT `roleid` FOREIGN KEY (`roleid`) REFERENCES `rolegroup` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `userid` FOREIGN KEY (`userid`) REFERENCES `user` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_role`
--

LOCK TABLES `user_role` WRITE;
/*!40000 ALTER TABLE `user_role` DISABLE KEYS */;
INSERT INTO `user_role` VALUES (19,1,1),(20,8,3);
/*!40000 ALTER TABLE `user_role` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-05-10 12:00:41
