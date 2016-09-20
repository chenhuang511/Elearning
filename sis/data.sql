
--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staff_id` int(8) NOT NULL,
  `current_school_id` decimal(10,0) DEFAULT NULL,
  `title` varchar(5) DEFAULT NULL,
  `first_name` varchar(100) DEFAULT NULL,
  `last_name` varchar(100) DEFAULT NULL,
  `middle_name` varchar(100) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `phone` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `profile` varchar(30) DEFAULT NULL,
  `homeroom` varchar(5) DEFAULT NULL,
  `last_login` datetime DEFAULT NULL,
  `failed_login` int(3) NOT NULL DEFAULT '0',
  `profile_id` decimal(10,0) DEFAULT NULL,
  `is_disable` varchar(10) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(8) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `name_suffix` varchar(3) DEFAULT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(100) DEFAULT NULL,
  `last_login` date DEFAULT NULL,
  `failed_login` int(3) NOT NULL DEFAULT '0',
  `gender` varchar(255) DEFAULT NULL,
  `ethnicity` varchar(255) DEFAULT NULL,
  `common_name` varchar(255) DEFAULT NULL,
  `social_security` varchar(255) DEFAULT NULL,
  `birthdate` varchar(255) DEFAULT NULL,
  `language` varchar(255) DEFAULT NULL,
  `physician` varchar(255) DEFAULT NULL,
  `physician_phone` varchar(255) DEFAULT NULL,
  `preferred_hospital` varchar(255) DEFAULT NULL,
  `estimated_grad_date` varchar(255) DEFAULT NULL,
  `alt_id` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `phone` varchar(30) DEFAULT NULL,
  `is_disable` varchar(10) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- Stand-in structure for view `student_contacts`
-- (See below for the actual view)
--
CREATE TABLE `student_contacts` (
`student_id` decimal(10,0)
,`alt_id` varchar(50)
,`student_name` varchar(101)
,`contact_type` varchar(9)
,`relation` varchar(100)
,`relation_first_name` varchar(100)
,`relation_last_name` varchar(100)
,`address1` varchar(100)
,`address2` varchar(100)
,`city` varchar(100)
,`state` varchar(100)
,`zip` varchar(100)
,`work_phone` varchar(100)
,`home_phone` varchar(100)
,`cell_phone` varchar(100)
,`email_id` varchar(100)
,`sort` varchar(1)
);


--
-- Table structure for table `student_enrollment`
--

CREATE TABLE `student_enrollment` (
  `id` int(8) NOT NULL,
  `syear` decimal(4,0) DEFAULT NULL,
  `school_id` decimal(10,0) DEFAULT NULL,
  `student_id` decimal(10,0) DEFAULT NULL,
  `grade_id` decimal(10,0) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `enrollment_code` decimal(10,0) DEFAULT NULL,
  `drop_code` decimal(10,0) DEFAULT NULL,
  `next_school` decimal(10,0) DEFAULT NULL,
  `calendar_id` decimal(10,0) DEFAULT NULL,
  `last_school` decimal(10,0) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Table structure for table `student_enrollment_codes`
--

CREATE TABLE `student_enrollment_codes` (
  `id` int(8) NOT NULL,
  `syear` decimal(4,0) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL,
  `short_name` varchar(10) DEFAULT NULL,
  `type` varchar(4) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Table structure for table `student_field_categories`
--

CREATE TABLE `student_field_categories` (
  `id` int(8) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `sort_order` decimal(10,0) DEFAULT NULL,
  `include` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- Table structure for table `student_gpa_calculated`
--

CREATE TABLE `student_gpa_calculated` (
  `student_id` decimal(10,0) DEFAULT NULL,
  `marking_period_id` int(11) DEFAULT NULL,
  `mp` varchar(4) DEFAULT NULL,
  `gpa` decimal(10,2) DEFAULT NULL,
  `weighted_gpa` decimal(10,2) DEFAULT NULL,
  `unweighted_gpa` decimal(10,2) DEFAULT NULL,
  `class_rank` decimal(10,0) DEFAULT NULL,
  `grade_level_short` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- Table structure for table `student_gpa_running`
--

CREATE TABLE `student_gpa_running` (
  `student_id` decimal(10,0) DEFAULT NULL,
  `marking_period_id` int(11) DEFAULT NULL,
  `gpa_points` decimal(10,2) DEFAULT NULL,
  `gpa_points_weighted` decimal(10,2) DEFAULT NULL,
  `divisor` decimal(10,2) DEFAULT NULL,
  `credit_earned` decimal(10,2) DEFAULT NULL,
  `cgpa` decimal(10,2) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- Table structure for table `student_medical`
--

CREATE TABLE `student_medical` (
  `id` int(8) NOT NULL,
  `student_id` decimal(10,0) DEFAULT NULL,
  `type` varchar(25) DEFAULT NULL,
  `medical_date` date DEFAULT NULL,
  `comments` longtext
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- Table structure for table `student_medical_alerts`
--

CREATE TABLE `student_medical_alerts` (
  `id` int(8) NOT NULL,
  `student_id` decimal(10,0) DEFAULT NULL,
  `title` text,
  `alert_date` date DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `student_medical_notes`
--

CREATE TABLE `student_medical_notes` (
  `id` int(8) NOT NULL,
  `student_id` decimal(10,0) NOT NULL,
  `doctors_note_date` date DEFAULT NULL,
  `doctors_note_comments` longtext
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- Table structure for table `student_medical_visits`
--

CREATE TABLE `student_medical_visits` (
  `id` int(8) NOT NULL,
  `student_id` decimal(10,0) DEFAULT NULL,
  `school_date` date DEFAULT NULL,
  `time_in` varchar(20) DEFAULT NULL,
  `time_out` varchar(20) DEFAULT NULL,
  `reason` text,
  `result` text,
  `comments` longtext
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `student_mp_comments`
--

CREATE TABLE `student_mp_comments` (
  `id` int(8) NOT NULL,
  `student_id` decimal(10,0) NOT NULL,
  `syear` decimal(4,0) NOT NULL,
  `marking_period_id` int(11) NOT NULL,
  `staff_id` int(11) DEFAULT NULL,
  `comment` longtext,
  `comment_date` date DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Table structure for table `student_mp_stats`
--

CREATE TABLE `student_mp_stats` (
  `student_id` int(11) NOT NULL,
  `marking_period_id` int(11) NOT NULL,
  `cum_weighted_factor` decimal(10,6) DEFAULT NULL,
  `cum_unweighted_factor` decimal(10,6) DEFAULT NULL,
  `cum_rank` int(11) DEFAULT NULL,
  `mp_rank` int(11) DEFAULT NULL,
  `sum_weighted_factors` decimal(10,6) DEFAULT NULL,
  `sum_unweighted_factors` decimal(10,6) DEFAULT NULL,
  `count_weighted_factors` int(11) DEFAULT NULL,
  `count_unweighted_factors` int(11) DEFAULT NULL,
  `grade_level_short` varchar(3) DEFAULT NULL,
  `class_size` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- Table structure for table `student_report_card_comments`
--

CREATE TABLE `student_report_card_comments` (
  `syear` decimal(4,0) NOT NULL,
  `school_id` decimal(10,0) DEFAULT NULL,
  `student_id` decimal(10,0) NOT NULL,
  `course_period_id` decimal(10,0) NOT NULL,
  `report_card_comment_id` decimal(10,0) NOT NULL,
  `comment` varchar(1) DEFAULT NULL,
  `marking_period_id` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- Table structure for table `student_report_card_grades`
--

CREATE TABLE `student_report_card_grades` (
  `syear` decimal(4,0) DEFAULT NULL,
  `school_id` decimal(10,0) DEFAULT NULL,
  `student_id` decimal(10,0) NOT NULL,
  `course_period_id` decimal(10,0) DEFAULT NULL,
  `report_card_grade_id` decimal(10,0) DEFAULT NULL,
  `report_card_comment_id` decimal(10,0) DEFAULT NULL,
  `comment` longtext,
  `grade_percent` decimal(5,2) DEFAULT NULL,
  `marking_period_id` varchar(10) NOT NULL,
  `grade_letter` varchar(5) DEFAULT NULL,
  `weighted_gp` decimal(10,3) DEFAULT NULL,
  `unweighted_gp` decimal(10,3) DEFAULT NULL,
  `gp_scale` decimal(10,3) DEFAULT NULL,
  `gpa_cal` varchar(2) DEFAULT NULL,
  `credit_attempted` decimal(10,3) DEFAULT NULL,
  `credit_earned` decimal(10,3) DEFAULT NULL,
  `credit_category` varchar(10) DEFAULT NULL,
  `course_code` varchar(100) DEFAULT NULL,
  `course_title` text,
  `id` int(8) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- Table structure for table `system_preference`
--

CREATE TABLE `system_preference` (
  `id` int(8) NOT NULL,
  `school_id` int(8) NOT NULL,
  `full_day_minute` int(8) DEFAULT NULL,
  `half_day_minute` int(8) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- Table structure for table `system_preference_misc`
--

CREATE TABLE `system_preference_misc` (
  `fail_count` decimal(5,0) NOT NULL DEFAULT '3',
  `activity_days` decimal(5,0) NOT NULL DEFAULT '30',
  `system_maintenance_switch` char(1) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


--
-- Table structure for table `teacher_reassignment`
--

CREATE TABLE `teacher_reassignment` (
  `course_period_id` int(11) NOT NULL,
  `teacher_id` int(11) NOT NULL,
  `assign_date` date NOT NULL,
  `modified_date` date NOT NULL,
  `pre_teacher_id` int(11) NOT NULL,
  `modified_by` int(11) NOT NULL,
  `updated` enum('Y','N') NOT NULL DEFAULT 'N'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Stand-in structure for view `transcript_grades`
-- (See below for the actual view)
--
CREATE TABLE `transcript_grades` (
`school_id` int(8)
,`school_name` varchar(100)
,`mp_source` varchar(7)
,`mp_id` int(11)
,`mp_name` varchar(50)
,`syear` decimal(10,0)
,`posted` date
,`student_id` decimal(10,0)
,`gradelevel` varchar(100)
,`grade_letter` varchar(5)
,`gp_value` decimal(10,3)
,`weighting` decimal(10,3)
,`gp_scale` decimal(10,3)
,`credit_attempted` decimal(10,3)
,`credit_earned` decimal(10,3)
,`credit_category` varchar(10)
,`course_period_id` decimal(10,0)
,`course_name` text
,`course_short_name` varchar(25)
,`gpa_cal` varchar(2)
,`weighted_gpa` decimal(10,2)
,`unweighted_gpa` decimal(10,2)
,`gpa` decimal(10,2)
,`class_rank` decimal(10,0)
,`sort_order` decimal(10,0)
);


--
-- Table structure for table `user_profiles`
--

CREATE TABLE `user_profiles` (
  `id` int(8) NOT NULL,
  `profile` varchar(30) DEFAULT NULL,
  `title` varchar(100) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8;



