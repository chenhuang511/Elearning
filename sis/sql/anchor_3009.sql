ALTER TABLE `anchor_user_course`
MODIFY  COLUMN `userid` int(11) NOT NULL,
ADD  COLUMN `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `anchor_student_course`
MODIFY  COLUMN `studentid` int(11) NOT NULL,
ADD  COLUMN `id` int(11) NOT NULL AUTO_INCREMENT;