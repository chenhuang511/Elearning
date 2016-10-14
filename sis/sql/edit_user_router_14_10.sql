DROP TABLE IF EXISTS `anchor_router`;
CREATE TABLE `anchor_router` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `action` enum('user','student','school','instructor','contract') NOT NULL,
  `router` TEXT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

INSERT INTO `anchor_router` VALUES (NULL, 'user', NULL);
INSERT INTO `anchor_router` VALUES (NULL, 'student', NULL);
INSERT INTO `anchor_router` VALUES (NULL, 'school', NULL);
INSERT INTO `anchor_router` VALUES (NULL, 'instructor', NULL);
INSERT INTO `anchor_router` VALUES (NULL, 'contract', NULL);