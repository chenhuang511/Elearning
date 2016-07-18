alter table mdl_course add remoteid bigint(10) not null;
alter table mdl_course add hostid smallint(4) not null default 0;
alter table mdl_course add categoryname varchar(255) NOT NULL DEFAULT '';
alter table mdl_course_categories add remoteid bigint(10) not null;
alter table mdl_course_categories add hostid smallint(4) not null default 0;
alter table mdl_assign add remoteid BIGINT(10) NOT NULL;
alter table mdl_quiz add remoteid bigint(10) not null;

drop table mdl_mnetservice_enrol_courses;
