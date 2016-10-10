
-- update users table --
ALTER TABLE anchor_users DROP role_id;
ALTER TABLE `anchor_users` ADD `role_id` enum('1','2','3','4','5', '6') NOT NULL AFTER `auth`;
--------------------------------------------------------

-- create curriculum table --
DROP TABLE IF EXISTS `anchor_user_role`;
CREATE TABLE `anchor_user_role` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `role` VARCHAR(100) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;
INSERT INTO `anchor_user_role` VALUES ('1', 'administrator');
INSERT INTO `anchor_user_role` VALUES ('2', 'user');
INSERT INTO `anchor_user_role` VALUES ('3', 'student');
INSERT INTO `anchor_user_role` VALUES ('4', 'school');
INSERT INTO `anchor_user_role` VALUES ('5', 'instructor');
INSERT INTO `anchor_user_role` VALUES ('6', 'contract');



-- ----------------------------
-- Table structure for `anchor_router`
-- ----------------------------

DROP TABLE IF EXISTS `anchor_router`;
CREATE TABLE `anchor_router` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `router` VARCHAR(100) NOT NULL,
  `action` VARCHAR(200) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;


-- ----------------------------
-- Test anchor_router
-- ----------------------------

INSERT INTO `anchor_router` VALUES (NULL , "admin/advance","user,instructor,contract,school");
INSERT INTO `anchor_router` VALUES (NULL , "admin/posts","user,instructor,contract,school");
INSERT INTO `anchor_router` VALUES (NULL , "admin/posts/edit/(:num)","instructor,contract,school");
INSERT INTO `anchor_router` VALUES (NULL , "admin/grade/course/(:num)/(:num)","contract,school");
INSERT INTO `anchor_router` VALUES (NULL , "admin/grade/course/(:num)","contract,school");
INSERT INTO `anchor_router` VALUES (NULL , "admin/advance/course/(:num)/search","contract,school");
INSERT INTO `anchor_router` VALUES (NULL , "admin/advance/course/status/(:any)/(:num)","contract,school");

-- ----------------------------
-- add user into table anchor_users
-- ----------------------------

INSERT INTO `anchor_users` VALUES (NULL , 'user', '$2y$12$1u63bVoSKtuIHtTUNRlHguqlHq1D6Ome8yhR57mUnuDEfWr2/ue4e', 'thien@gmail.com', 'Administrator', 'The bouse', 'active', 'user', NULL , 'manua', 2, NULL );
INSERT INTO `anchor_users` VALUES (NULL , 'student', '$2y$12$1u63bVoSKtuIHtTUNRlHguqlHq1D6Ome8yhR57mUnuDEfWr2/ue4e', 'thien@gmail.com', 'Administrator', 'The bouse', 'active', 'user', NULL , 'manua', 3, NULL );
INSERT INTO `anchor_users` VALUES (NULL , 'school', '$2y$12$1u63bVoSKtuIHtTUNRlHguqlHq1D6Ome8yhR57mUnuDEfWr2/ue4e', 'thien@gmail.com', 'Administrator', 'The bouse', 'active', 'user', NULL , 'manua', 4, NULL );
INSERT INTO `anchor_users` VALUES (NULL , 'instructor', '$2y$12$1u63bVoSKtuIHtTUNRlHguqlHq1D6Ome8yhR57mUnuDEfWr2/ue4e', 'thien@gmail.com', 'Administrator', 'The bouse', 'active', 'user', NULL , 'manua', 5, NULL );
INSERT INTO `anchor_users` VALUES (NULL , 'contract', '$2y$12$1u63bVoSKtuIHtTUNRlHguqlHq1D6Ome8yhR57mUnuDEfWr2/ue4e', 'thien@gmail.com', 'Administrator', 'The bouse', 'active', 'user', NULL , 'manua', 6, NULL );

