

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

INSERT INTO `anchor_router` VALUES (NULL , "admin/advance","user,instructor,contract,schools");
INSERT INTO `anchor_router` VALUES (NULL , "admin/posts","instructor,contract,schools");
INSERT INTO `anchor_router` VALUES (NULL , "admin/posts/edit/(:num)","user,contract,schools");
INSERT INTO `anchor_router` VALUES (NULL , "admin/grade/course/(:num)/(:num)","contract,schools");
INSERT INTO `anchor_router` VALUES (NULL , "admin/grade/course/(:num)","contract,schools");


INSERT INTO `anchor_users` VALUES (NULL , 'admin10', '$2y$12$1u63bVoSKtuIHtTUNRlHguqlHq1D6Ome8yhR57mUnuDEfWr2/ue4e', 'thien@gmail.com', 'Administrator', 'The bouse', 'active', 'user', NULL , 'manua', 2, NULL );
INSERT INTO `anchor_users` VALUES (NULL , 'admin11', '$2y$12$1u63bVoSKtuIHtTUNRlHguqlHq1D6Ome8yhR57mUnuDEfWr2/ue4e', 'thien@gmail.com', 'Administrator', 'The bouse', 'active', 'user', NULL , 'manua', 3, NULL );
INSERT INTO `anchor_users` VALUES (NULL , 'admin12', '$2y$12$1u63bVoSKtuIHtTUNRlHguqlHq1D6Ome8yhR57mUnuDEfWr2/ue4e', 'thien@gmail.com', 'Administrator', 'The bouse', 'active', 'user', NULL , 'manua', 4, NULL );
INSERT INTO `anchor_users` VALUES (NULL , 'admin13', '$2y$12$1u63bVoSKtuIHtTUNRlHguqlHq1D6Ome8yhR57mUnuDEfWr2/ue4e', 'thien@gmail.com', 'Administrator', 'The bouse', 'active', 'user', NULL , 'manua', 5, NULL );
INSERT INTO `anchor_users` VALUES (NULL , 'admin14', '$2y$12$1u63bVoSKtuIHtTUNRlHguqlHq1D6Ome8yhR57mUnuDEfWr2/ue4e', 'thien@gmail.com', 'Administrator', 'The bouse', 'active', 'user', NULL , 'manua', 6, NULL );


