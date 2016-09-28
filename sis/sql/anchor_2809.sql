ALTER TABLE `anchor_users`
ADD COLUMN `schoolid` int(11) DEFAULT NULL;

ALTER TABLE `anchor_students`
ADD COLUMN `schoolid` int(11) DEFAULT NULL;

ALTER TABLE `anchor_user_course`
ADD COLUMN `remoterole` int(11) DEFAULT NULL;

ALTER TABLE `anchor_student_course`
ADD COLUMN `remoterole` int(11) DEFAULT NULL;

ALTER TABLE `anchor_schools`
ADD COLUMN `wwwroot` varchar(255) DEFAULT NULL;
