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
INSERT INTO `anchor_user_role` VALUES ('2', 'users');
INSERT INTO `anchor_user_role` VALUES ('3', 'students');
INSERT INTO `anchor_user_role` VALUES ('4', 'schools');
INSERT INTO `anchor_user_role` VALUES ('5', 'instructor');
INSERT INTO `anchor_user_role` VALUES ('6', 'contract');

