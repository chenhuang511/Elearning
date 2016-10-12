<?php

//var_dump('user externallib.php');

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/externallib.php');



class local_grade_complete_course extends external_api
{

    public static function get_grade_complete_course_parameters() {
        return new external_function_parameters(
            array(
                'userid' => new external_value(PARAM_INT, 'user id in remote'),
                'courseid' => new external_value(PARAM_INT, 'course id in remote'),
            )
        );
    }

    public static function get_grade_complete_course($userid, $courseid) {
        global $CFG, $DB;

        //validate parameter
        $params = self::validate_parameters(self::get_grade_complete_course_parameters(),
            array('userid' => $userid, 'courseid' => $courseid));

        $itemid = $DB->get_field('grade_items', 'id', array('courseid' => $courseid, 'itemtype' => 'mod', 'itemmodule' => 'quiz'));
        $grade  = $DB->get_field('grade_grade', 'finalgrade', array('userid' => $userid, 'itemid' => $itemid));
        //$userlocal = get_remote_mapping_localuserid($userid);

        //$percent = get_local_course_completion_progress($course, $userlocal);
        return $grade;
    }

    public static function get_grade_complete_returns() {
        return new external_value(PARAM_INT, 'grade');
    }
}
