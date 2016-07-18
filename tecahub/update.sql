CREATE TABLE M_COURSE_INFO 
(
  ID NUMBER(12) NOT NULL 
, COURSE NUMBER(12) DEFAULT 0 NOT NULL 
, INFO CLOB 
, INFOFORMAT INT DEFAULT 0 NOT NULL 
, VALIDATETIME NUMBER(10) DEFAULT 0 NOT NULL 
, TIMECREATED NUMBER(10) DEFAULT 0 NOT NULL 
, TIMEMODIFIED NUMBER(10) DEFAULT 0 NOT NULL 
, NOTE VARCHAR2(255) DEFAULT '' NOT NULL 
, CONSTRAINT M_COURSE_INFO_PK PRIMARY KEY 
  (
    ID 
  )
  ENABLE 
);

