ALTER TABLE `anchor_instructors` ADD COLUMN `fullname` varchar(140) DEFAULT NULL;
ALTER TABLE `anchor_instructors` ADD COLUMN `type_instructor` enum('contract','official') DEFAULT NULL;
ALTER TABLE `anchor_instructors` ADD COLUMN `thematic_taught` varchar(200) DEFAULT NULL;
ALTER TABLE `anchor_instructors` ADD COLUMN `comment` varchar(1000) DEFAULT NULL;
ALTER TABLE `anchor_instructors `DROP `firstname`;
ALTER TABLE `anchor_instructors `DROP `lastname`;