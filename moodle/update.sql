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
alter table mdl_forum add remoteid bigint(10) not null default 0;

drop table mdl_mnetservice_enrol_courses;
