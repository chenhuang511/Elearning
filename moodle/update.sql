alter table mdl_course add remoteid bigint(10) not null default 0;
alter table mdl_course add hostid smallint(4) not null default 0;
alter table mdl_course add categoryname varchar(255) not null default '';
alter table mdl_course_categories add remoteid bigint(10) not null;
alter table mdl_course_categories add hostid smallint(4) not null default 0;
alter table mdl_assign add remoteid bigint(10) not null default 0;
alter table mdl_quiz add remoteid bigint(10) not null default 0;
alter table mdl_grade_items add remoteid bigint(10) not null default 0;
alter table mdl_questionnaire add remoteid bigint(10) not null default 0;
alter table mdl_certificate add remoteid bigint(10) not null default 0;
alter table mdl_course_modules add remoteid bigint(10) not null default 0;
alter table mdl_course_sections add remoteid bigint(10) not null default 0;
alter table mdl_forum add remoteid bigint(10) not null default 0;
alter table mdl_chat add remoteid bigint(10) not null default 0;
alter table mdl_bigbluebuttonbn add remoteid bigint(10) not null default 0;
alter table mdl_resource add remoteid bigint(10) not null default 0;
alter table mdl_label add remoteid bigint(10) not null default 0;
alter table mdl_url add remoteid bigint(10) not null default 0;
alter table mdl_slide add remoteid bigint(10) not null default 0;

CREATE TABLE mdl_course_modules_createdby (
  id BIGINT(10) NOT NULL AUTO_INCREMENT,
  course BIGINT(10) NOT NULL,
  coursemodule BIGINT(10) NOT NULL,
  userid BIGINT(10) NOT NULL,
  PRIMARY KEY(id)
) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

drop table mdl_mnetservice_enrol_courses;

CREATE TABLE mdl_slide_storage (
  id BIGINT(10) AUTO_INCREMENT NOT NULL PRIMARY KEY,
  userid BIGINT(10) NOT NULL,
  filename VARCHAR(512) NOT NULL,
  content_json TEXT,
  content_html TEXT,
  visibility tinyint(1) DEFAULT 0,
  timecreated INT DEFAULT 0,
  timemodified INT DEFAULT 0
) DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

--------------------------- ----------------------------------------
alter table mdl_question_categories add remoteid bigint(10) not null default 0;