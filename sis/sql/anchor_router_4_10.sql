

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
-- Records of anchor_router
-- ----------------------------

INSERT INTO `anchor_router` VALUES ("1", "admin/advance","administrator,instructor,contract,schools");

ALTER TABLE
