-- update course table --
ALTER TABLE anchor_courses MODIFY COLUMN remoteid BIGINT(10) NULL;
ALTER TABLE anchor_courses add startdate TIMESTAMP NULL;
ALTER TABLE anchor_courses add enddate TIMESTAMP NULL;
--------------------------------------------------------

-- create curriculum table --
CREATE TABLE anchor_curriculum (
  id BIGINT(10) NOT NULL AUTO_INCREMENT,
  course BIGINT(10) NOT NULL,
  time TIMESTAMP NOT NULL,
  topic VARCHAR(512) NOT NULL,
  lecturer VARCHAR(512) NOT NULL,
  userid BIGINT(10) NOT NULL,
  timecreated INT DEFAULT 0,
  timemodified INT DEFAULT 0,
  usermodified BIGINT(10) NULL,
  note VARCHAR(255) NULL,
  PRIMARY KEY(id)
);

select c.*, (SELECT real_name FROM anchor_users WHERE id=c.lecturer) as teacher_name from anchor_curriculum c JOIN anchor_users u ON u.id = c.userid WHERE c.course = 9;