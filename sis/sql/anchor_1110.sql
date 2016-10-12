ALTER TABLE `anchor_instructor_contract` ADD COLUMN `name_head` varchar(140) DEFAULT NULL;
ALTER TABLE `anchor_instructor_contract` ADD COLUMN `tax_code` varchar(20) DEFAULT NULL;
ALTER TABLE `anchor_instructor_contract` ADD COLUMN `number_phone` varchar(20) DEFAULT NULL;
ALTER TABLE `anchor_instructor_contract` ADD COLUMN `address` varchar(200) DEFAULT NULL;
ALTER TABLE `anchor_instructors `DROP `curriculum_taught`;
ALTER TABLE `anchor_instructors `DROP `comment`;