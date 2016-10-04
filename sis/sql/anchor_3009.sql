ALTER TABLE anchor_user_course MODIFY COLUMN userid INT(11) NOT NULL;
ALTER TABLE anchor_user_course DROP PRIMARY KEY;
ALTER TABLE anchor_user_course MODIFY COLUMN courseid INT(11) NOT NULL;
ALTER TABLE anchor_user_course ADD COLUMN id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT;

ALTER TABLE anchor_student_course MODIFY COLUMN studentid INT(11) NOT NULL;
ALTER TABLE anchor_student_course DROP PRIMARY KEY;
ALTER TABLE anchor_student_course MODIFY  COLUMN studentid int(11) NOT NULL;
ALTER TABLE anchor_student_course ADD COLUMN id INT(11) NOT NULL PRIMARY KEY AUTO_INCREMENT;
