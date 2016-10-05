-- update course table --
ALTER TABLE anchor_courses MODIFY COLUMN remoteid BIGINT(10) NULL;
ALTER TABLE anchor_courses add startdate TIMESTAMP NULL;
ALTER TABLE anchor_courses add enddate TIMESTAMP NULL;
--------------------------------------------------------

-- create curriculum table --
DROP TABLE IF EXISTS anchor_curriculum;
CREATE TABLE anchor_curriculum (
  id BIGINT(10) NOT NULL AUTO_INCREMENT,
  course BIGINT(10) NOT NULL,
  topicday TIMESTAMP NOT NULL,
  topictime VARCHAR(20) NULL DEFAULT NULL,
  topicname VARCHAR(512) NOT NULL,
  lecturer VARCHAR(512) NOT NULL,
  userid BIGINT(10) NOT NULL,
  timecreated INT DEFAULT 0,
  timemodified INT DEFAULT 0,
  usermodified BIGINT(10) NULL,
  note VARCHAR(255) NULL,
  PRIMARY KEY(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;