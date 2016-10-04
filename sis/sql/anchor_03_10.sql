DROP TABLE IF EXISTS `anchor_virtual_class_equipment`;
CREATE TABLE `anchor_virtual_class_equipment` (
  `id` int(6) NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL,
  `image_url` varchar(150),
  `description` text NOT NULL,
  `quantity` int(6),
  `created` datetime ,
  `category` int(6),
  `status` bool DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

ALTER TABLE anchor_schools ADD COLUMN token varchar(64);