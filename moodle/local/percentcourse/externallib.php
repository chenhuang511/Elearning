<?php

//var_dump('user externallib.php');

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/externallib.php');



class local_percent_course_external extends external_api
{

    public static function get_percent_course_parameters() {
        return new external_function_parameters(
            array(
                'courseid' => new external_value(PARAM_INT, 'course id in remote'),
                'userid' => new external_value(PARAM_INT, 'user id in remote'),
            )
        );
    }

    public static function get_percent_course($courseid,$userid) {
        global $CFG, $DB;

        require_once ($CFG->dirroot.'/course/lib.php');

        //validate parameter
        $params = self::validate_parameters(self::get_percent_course_parameters(),
            array('courseid' => $courseid, 'userid' => $userid));

        $course = $DB->get_record('course', array('remoteid' => $courseid));
        $userlocal = get_remote_mapping_localuserid($userid);

        $percent = get_local_course_completion_progress($course, $userlocal);
        return $percent;

        //$userlocal = get_remote_mapping_localuserid($userid);
        //if(!$userlocal) {return 'false';};
        //$url = $CFG->wwwroot . '/user/profile.php?id='. $userlocal;
        //return $url;
    }

    public static function get_percent_course_returns() {
        return new external_value(PARAM_FLOAT, 'percent');
    }
}
