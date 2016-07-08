<?php

/**
 * External lesson API
 *
 * @package    core_local_lesson
 * @category   external
 * @copyright  2009 Petr Skodak
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

require_once("$CFG->libdir/externallib.php");


/**
 * Lesson external functions
 *
 * @package    core_local_lesson
 * @category   external
 * @copyright  2016 Nccsoft Vietnam
 * @license    http://nccsoft.vn
 * @since Moodle 3.0
 */
class local_mod_lesson_external extends external_api
{
    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 3.0
     */
    public static function get_maxgrade_lesson_grades_by_userid_and_lessonid_parameters()
    {
        return new external_function_parameters(
            array('userid' => new external_value(PARAM_INT, 'the user id'),
                'lessonid' => new external_value(PARAM_INT, 'the lesson id')
            )
        );
    }

    /**
     * Get userid and maxgrade from lesson grades by userid and lessonid
     *
     * @param $userid
     * @param $lessonid
     * @param array $options
     * @return mixed
     * @throws invalid_parameter_exception
     */
    public static function get_maxgrade_lesson_grades_by_userid_and_lessonid($userid, $lessonid)
    {
        global $DB;

        $warnings = array();

        // validate params
        $params = self::validate_parameters(self::get_max_lesson_grades_by_userid_and_lessonid_parameters(),
            array(
                'userid' => $userid,
                'lessonid' => $lessonid
            )
        );

        $result = array();

        $grade = $DB->get_record_sql('SELECT userid, MAX(grade) AS maxgrade FROM {lesson_grades} WHERE userid = :userid AND lessonid = :lessonid GROUP BY userid',
            array('userid' => $params['userid'], 'lessonid' => $params['lessonid']));

        if (!$grade) {
            $grade = new stdClass();
        }

        $result['grade'] = $grade;
        $result['warnings'] = $warnings;

        return $result;
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     * @since Moodle 3.0
     */
    public static function get_maxgrade_lesson_grades_by_userid_and_lessonid_returns()
    {
        return new external_single_structure(
            array(
                'grade' => new external_single_structure(
                    array(
                        'userid' => new external_value(PARAM_INT, 'the user id'),
                        'maxgrade' => new external_value(PARAM_INT, 'the max grade')
                    )
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 3.0
     */
    public static function get_events_by_modulename_and_instace_parameters()
    {
        return new external_function_parameters(
            array(
                'modulename' => new external_value(PARAM_TEXT, ' the module name'),
                'instance' => new external_value(PARAM_INT, ' the instance'),
                'userid' => new external_value(PARAM_INT, ' the userid'),
                'groupid' => new external_value(PARAM_INT, ' the groupid')
            )
        );
    }

    /**
     * Get list of events by modulename and instance
     *
     * @param $modulename
     * @param $instance
     * @return array
     * @throws invalid_parameter_exception
     */
    public static function get_events_by_modulename_and_instace($modulename, $instance, $userid, $groupid)
    {
        global $DB;

        $warnings = array();

        $arr = array(
            'modulename' => $modulename,
            'instance' => $instance
        );

        if ($userid !== 0) {
            $arr = array_merge($arr, array('userid' => $userid));
        }

        if ($groupid !== 0) {
            $arr = array_merge($arr, array('groupid' => $groupid));
        }

        // validate params
        $params = self::validate_parameters(self::get_events_by_modulename_and_instace_parameters(),
            $arr
        );

        $parameters = array("modulename" => $params['modulename'], "instance" => $params['instance']);

        if (isset($params['userid'])) {
            $parameters = array_merge($parameters, array('userid' => $params['userid']));
        }
        if (isset($params['groupid'])) {
            $parameters = array_merge($parameters, array('groupid' => $params['groupid']));
        }

        $result = array();

        $events = $DB->get_records('event', $parameters);

        if (!$events) {
            $events = array();
        }

        $result['events'] = $events;
        $result['warnings'] = $warnings;

        return $result;
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     * @since Moodle 3.0
     */
    public static function get_events_by_modulename_and_instace_returns()
    {
        return new external_single_structure(
            array(
                'events' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'id' => new external_value(PARAM_INT, 'the id'),
                            'name' => new external_value(PARAM_RAW, 'the name'),
                            'description' => new external_value(PARAM_RAW, 'the description'),
                            'format' => new external_value(PARAM_INT, 'format'),
                            'courseid' => new external_value(PARAM_INT, 'the courseid'),
                            'groupid' => new external_value(PARAM_INT, 'the groupid'),
                            'userid' => new external_value(PARAM_INT, 'the user id'),
                            'repeatid' => new external_value(PARAM_INT, 'the repeat id'),
                            'modulename' => new external_value(PARAM_TEXT, 'the module name'),
                            'instance' => new external_value(PARAM_INT, 'the instance'),
                            'eventtype' => new external_value(PARAM_TEXT, 'the event type'),
                            'timestart' => new external_value(PARAM_INT, 'time start'),
                            'timeduration' => new external_value(PARAM_INT, 'time duration'),
                            'visible' => new external_value(PARAM_INT, 'visible'),
                            'uuid' => new external_value(PARAM_TEXT, 'the uuid'),
                            'sequence' => new external_value(PARAM_INT, 'the sequence'),
                            'timemodified' => new external_value(PARAM_INT, 'time modified'),
                            'subscriptionid' => new external_value(PARAM_INT, 'subscriptionid')
                        )
                    )
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function get_duration_lesson_timer_by_lessonid_and_userid_parameters()
    {
        return new external_function_parameters(
            array(
                'lessonid' => new external_value(PARAM_INT, 'the lesson id'),
                'userid' => new external_value(PARAM_INT, 'the user id')
            )
        );
    }

    public static function get_duration_lesson_timer_by_lessonid_and_userid($lessonid, $userid)
    {
        global $DB;

        $warnings = array();

        $params = self::validate_parameters(self::get_duration_lesson_timer_by_lessonid_and_userid_parameters(), array(
            'lessonid' => $lessonid,
            'userid' => $userid
        ));

        $result = array();

        $duration = $DB->get_field_sql(
            "SELECT SUM(lessontime - starttime)
                                   FROM {lesson_timer}
                                  WHERE lessonid = :lessonid
                                    AND userid = :userid",
            array('userid' => $params['userid'], 'lessonid' => $params['lessonid']));

        if (!$duration) {
            $duration = 0;
        }

        $result['duration'] = $duration;
        $result['warnings'] = $warnings;

        return $result;
    }

    public static function get_duration_lesson_timer_by_lessonid_and_userid_returns()
    {
        return new external_single_structure(
            array(
                'duration' => new external_value(PARAM_INT, 'the duration'),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function check_record_exists_parameters()
    {
        return new external_function_parameters(
            array(
                'modname' => new external_value(PARAM_RAW, ' the mod name'),
                'parameters' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the params'
                )
            )
        );
    }

    public static function check_record_exists($modname, $parameters)
    {
        global $DB;

        $warnings = array();

        $params = self::validate_parameters(self::check_record_exists_parameters(), array(
            'modname' => $modname,
            'parameters' => $parameters,
        ));

        $result = array();

        $arr = array();
        foreach ($params['parameters'] as $p) {
            $arr = array_merge($arr, array($p['name'] => $p['value']));
        }

        $result['status'] = $DB->record_exists($params['modname'], $arr);
        $result['warnings'] = $warnings;

        return $result;
    }

    public static function check_record_exists_returns()
    {
        return new external_single_structure(
            array(
                'status' => new external_value(PARAM_BOOL, 'status'),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function get_user_by_lessonid_parameters()
    {
        return new external_function_parameters(
            array(
                'sql' => new external_value(PARAM_RAW, "sql"),
                'parameters' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the params'
                )
            )
        );
    }

    public static function get_user_by_lessonid($sql, $parameters)
    {
        global $DB;

        $warnings = array();

        $params = self::validate_parameters(self::get_user_by_lessonid_parameters(), array(
            'sql' => $sql,
            'parameters' => $parameters
        ));

        $arr = array();
        foreach ($params['parameters'] as $p) {
            $arr = array_merge($arr, array($p['name'] => $p['value']));
        }

        $result = array();

        $users = $DB->get_records_sql($params['sql'], $arr);

        if (!$users) {
            $users = array();
        }

        $result['users'] = $users;
        $result['warnings'] = $warnings;

        return $result;
    }

    public static function get_user_by_lessonid_returns()
    {
        return new external_single_structure(
            array(
                'users' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'id' => new external_value(PARAM_INT, 'id'),
                            'picture' => new external_value(PARAM_INT, 'picture'),
                            'firstname' => new external_value(PARAM_RAW, 'first name'),
                            'lastname' => new external_value(PARAM_RAW, 'last name'),
                            'firstnamephonetic' => new external_value(PARAM_RAW, 'first name phonetic'),
                            'lastnamephonetic' => new external_value(PARAM_RAW, 'last name phonetic'),
                            'middlename' => new external_value(PARAM_RAW, 'middle name'),
                            'alternatename' => new external_value(PARAM_RAW, 'alternate name'),
                            'imagealt' => new external_value(PARAM_RAW, 'image alt'),
                            'email' => new external_value(PARAM_RAW, 'email')
                        )
                    ), ' user data'
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function get_field_by_parameters()
    {
        return new external_function_parameters(
            array(
                'modname' => new external_value(PARAM_RAW, 'mod name'),
                'parameters' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the params'
                ),
                'field' => new external_value(PARAM_RAW, 'field')
            )
        );
    }

    public static function get_field_by($modname, $parameters, $field)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::get_field_by_parameters(), array(
            'modname' => $modname,
            'parameters' => $parameters,
            'field' => $field
        ));

        $arr = array();
        foreach ($params['parameters'] as $p) {
            $arr = array_merge($arr, array($p['name'] => $p['value']));
        }

        $result = array();

        $f = $DB->get_field($params['modname'], $params['field'], $arr);

        if (!$f) {
            $f = 0;
        }

        $result['field'] = $f;
        $result['warnings'] = $warnings;

        return $result;

    }

    public static function get_field_by_returns()
    {
        return new external_single_structure(
            array(
                'field' => new external_value(PARAM_RAW, 'field'),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function get_count_by_parameters()
    {
        return new external_function_parameters(
            array(
                'modname' => new external_value(PARAM_RAW, 'mod name'),
                'parameters' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the params'
                ),
                'sort' => new external_value(PARAM_RAW, 'sort')
            )
        );
    }

    public static function get_count_by($modname, $parameters, $sort)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::get_count_by_parameters(), array(
            'modname' => $modname,
            'parameters' => $parameters,
            'sort' => $sort
        ));

        $arr = array();
        foreach ($params['parameters'] as $p) {
            $arr = array_merge($arr, array($p['name'] => $p['value']));
        }

        $result = array();
        if ($params['sort'] === '') {
            $count = $DB->count_records($params['modname'], $arr);
        } else {
            $count = $DB->count_records($params['modname'], $arr, $params['sort']);
        }

        $result['count'] = $count;
        $result['warnings'] = $warnings;

        return $result;
    }

    public static function get_count_by_returns()
    {
        return new external_function_parameters(
            array(
                'count' => new external_value(PARAM_INT, 'count row'),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function get_lesson_by_parameters()
    {
        return new external_function_parameters(
            array(
                'parameters' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the params'
                ),
                'sort' => new external_value(PARAM_RAW, 'sort'),
                'mustexists' => new external_value(PARAM_BOOL, 'must exists')
            )
        );
    }

    public static function get_lesson_by($parameters, $sort, $mustxists)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::get_lesson_by_parameters(), array(
            'parameters' => $parameters,
            'sort' => $sort,
            'mustexists' => $mustxists
        ));

        $arr = array();
        foreach ($params['parameters'] as $p) {
            $arr = array_merge($arr, array($p['name'] => $p['value']));
        }

        $result = array();

        if ($params['mustexists'] === FALSE && $params['sort'] === '') {
            $lesson = $DB->get_record("lesson", $arr);
        } else if ($params['mustexists'] === FALSE && $params['sort'] != '') {
            $lesson = $DB->get_record("lesson", $arr, $params['sort']);
        } else {
            $lesson = $DB->get_record("lesson", $arr, '*', MUST_EXIST);
        }

        if (!$lesson) {
            $lesson = new stdClass();
        }

        $result['lesson'] = $lesson;
        $result['warnings'] = $warnings;
        return $result;
    }

    public static function get_lesson_by_returns()
    {
        return new external_single_structure(
            array(
                'lesson' => new external_single_structure(
                    array(
                        'id' => new external_value(PARAM_INT, 'lesson id'),
                        'course' => new external_value(PARAM_INT, 'course id', VALUE_DEFAULT),
                        'name' => new external_value(PARAM_RAW, 'lesson name'),
                        'intro' => new external_value(PARAM_RAW, 'lesson intro'),
                        'introformat' => new external_value(PARAM_INT, 'intro format', VALUE_DEFAULT),
                        'practice' => new external_value(PARAM_INT, 'practice', VALUE_DEFAULT),
                        'modattempts' => new external_value(PARAM_INT, 'mod attempts', VALUE_DEFAULT),
                        'usepassword' => new external_value(PARAM_INT, 'use password', VALUE_DEFAULT),
                        'password' => new external_value(PARAM_TEXT, 'password'),
                        'dependency' => new external_value(PARAM_INT, 'dependency', VALUE_DEFAULT),
                        'conditions' => new external_value(PARAM_RAW, 'condition'),
                        'grade' => new external_value(PARAM_INT, 'grade', VALUE_DEFAULT),
                        'custom' => new external_value(PARAM_INT, 'custom', VALUE_DEFAULT),
                        'ongoing' => new external_value(PARAM_INT, 'on going', VALUE_DEFAULT),
                        'usemaxgrade' => new external_value(PARAM_INT, 'use max grade', VALUE_DEFAULT),
                        'maxanswers' => new external_value(PARAM_INT, 'max answer'),
                        'maxattempts' => new external_value(PARAM_INT, 'max attempts'),
                        'review' => new external_value(PARAM_INT, 'review', VALUE_DEFAULT),
                        'nextpagedefault' => new external_value(PARAM_INT, 'next page default', VALUE_DEFAULT),
                        'feedback' => new external_value(PARAM_INT, 'feedback', VALUE_REQUIRED),
                        'minquestions' => new external_value(PARAM_INT, 'min question', VALUE_DEFAULT),
                        'maxpages' => new external_value(PARAM_INT, 'max page', VALUE_DEFAULT),
                        'timelimit' => new external_value(PARAM_INT, 'time limit', VALUE_DEFAULT),
                        'retake' => new external_value(PARAM_INT, 'retake', VALUE_DEFAULT),
                        'activitylink' => new external_value(PARAM_INT, 'activity link', VALUE_REQUIRED),
                        'mediafile' => new external_value(PARAM_TEXT, 'media file', VALUE_DEFAULT),
                        'mediaheight' => new external_value(PARAM_INT, 'media height'),
                        'mediawidth' => new external_value(PARAM_INT, 'media width'),
                        'mediaclose' => new external_value(PARAM_INT, 'media close'),
                        'slideshow' => new external_value(PARAM_INT, 'slideshow'),
                        'width' => new external_value(PARAM_INT, 'slideshow width'),
                        'height' => new external_value(PARAM_INT, 'slideshow height'),
                        'bgcolor' => new external_value(PARAM_TEXT, 'background color'),
                        'displayleft' => new external_value(PARAM_INT, 'display left'),
                        'displayleftif' => new external_value(PARAM_INT, 'display left if', VALUE_DEFAULT),
                        'progressbar' => new external_value(PARAM_INT, 'progress bar', VALUE_DEFAULT),
                        'available' => new external_value(PARAM_INT, 'available', VALUE_DEFAULT),
                        'deadline' => new external_value(PARAM_INT, 'deadline', VALUE_DEFAULT),
                        'timemodified' => new external_value(PARAM_INT, 'time modified', VALUE_DEFAULT),
                        'completionendreached' => new external_value(PARAM_INT, 'completion end reached', VALUE_DEFAULT),
                        'completiontimespent' => new external_value(PARAM_INT, 'completion time spent', VALUE_DEFAULT)
                    )
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function get_lesson_pages_by_parameters()
    {
        return new external_function_parameters(
            array(
                'parameters' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the params'
                ),
                'sort' => new external_value(PARAM_RAW, 'sort'),
                'mustexists' => new external_value(PARAM_BOOL, 'must exists')
            )
        );
    }

    public static function get_lesson_pages_by($parameters, $sort, $mustxists)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::get_lesson_pages_by_parameters(), array(
            'parameters' => $parameters,
            'sort' => $sort,
            'mustexists' => $mustxists
        ));

        $arr = array();
        foreach ($params['parameters'] as $p) {
            $arr = array_merge($arr, array($p['name'] => $p['value']));
        }

        $result = array();

        if ($params['mustexists'] === FALSE && $params['sort'] === '') {
            $page = $DB->get_record("lesson_pages", $arr);
        } else if ($params['mustexists'] === FALSE && $params['sort'] != '') {
            $page = $DB->get_record("lesson_pages", $arr, $params['sort']);
        } else {
            $page = $DB->get_record("lesson_pages", $arr, '*', MUST_EXIST);
        }

        if (!$page) {
            $page = new stdClass();
        }

        $result['page'] = $page;
        $result['warnings'] = $warnings;
        return $result;
    }

    public static function get_lesson_pages_by_returns()
    {
        return new external_single_structure(
            array(
                'page' => new external_single_structure(
                    array(
                        'id' => new external_value(PARAM_INT, 'the lesson page id'),
                        'lessonid' => new external_value(PARAM_INT, 'lesson id', VALUE_DEFAULT),
                        'prevpageid' => new external_value(PARAM_INT, 'previous page id', VALUE_DEFAULT),
                        'nextpageid' => new external_value(PARAM_INT, 'next page id', VALUE_DEFAULT),
                        'qtype' => new external_value(PARAM_INT, 'qtype', VALUE_DEFAULT),
                        'qoption' => new external_value(PARAM_INT, 'qoption', VALUE_DEFAULT),
                        'layout' => new external_value(PARAM_INT, 'layout', VALUE_REQUIRED),
                        'display' => new external_value(PARAM_INT, 'display', VALUE_REQUIRED),
                        'timecreated' => new external_value(PARAM_INT, 'time created', VALUE_DEFAULT),
                        'timemodified' => new external_value(PARAM_INT, 'time modified', VALUE_DEFAULT),
                        'title' => new external_value(PARAM_RAW, 'title'),
                        'contents' => new external_value(PARAM_RAW, 'contents'),
                        'contentsformat' => new external_value(PARAM_INT, 'contents format', VALUE_DEFAULT)
                    )
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function get_lesson_grades_by_parameters()
    {
        return new external_function_parameters(
            array(
                'parameters' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the params'
                ),
                'sort' => new external_value(PARAM_RAW, 'sort'),
                'mustexists' => new external_value(PARAM_BOOL, 'must exists')
            )
        );
    }

    public static function get_lesson_grades_by($parameters, $sort, $mustxists)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::get_lesson_grades_by_parameters(), array(
            'parameters' => $parameters,
            'sort' => $sort,
            'mustexists' => $mustxists
        ));

        $arr = array();
        foreach ($params['parameters'] as $p) {
            $arr = array_merge($arr, array($p['name'] => $p['value']));
        }

        $result = array();

        if ($params['mustexists'] === FALSE && $params['sort'] === '') {
            $grade = $DB->get_record("lesson_grades", $arr);
        } else if ($params['mustexists'] === FALSE && $params['sort'] != '') {
            $grade = $DB->get_record("lesson_grades", $arr, $params['sort']);
        } else {
            $grade = $DB->get_record("lesson_grades", $arr, '*', MUST_EXIST);
        }

        if (!$grade) {
            $grade = new stdClass();
        }

        $result['grade'] = $grade;
        $result['warnings'] = $warnings;
        return $result;
    }

    public static function get_lesson_grades_by_returns()
    {
        return new external_single_structure(
            array(
                'grade' => new external_single_structure(
                    array(
                        'id' => new external_value(PARAM_INT, 'lesson grade id'),
                        'lessonid' => new external_value(PARAM_INT, 'lesson id', VALUE_DEFAULT),
                        'userid' => new external_value(PARAM_INT, 'user id', VALUE_DEFAULT),
                        'grade' => new external_value(PARAM_INT, 'grade', VALUE_DEFAULT),
                        'late' => new external_value(PARAM_INT, 'late', VALUE_DEFAULT),
                        'completed' => new external_value(PARAM_INT, 'completed', VALUE_DEFAULT)
                    )
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function get_lesson_answers_by_parameters()
    {
        return new external_function_parameters(
            array(
                'parameters' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the params'
                ),
                'sort' => new external_value(PARAM_RAW, 'sort'),
                'mustexists' => new external_value(PARAM_BOOL, 'must exists')
            )
        );
    }

    public static function get_lesson_answers_by($parameters, $sort, $mustxists)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::get_lesson_answers_by_parameters(), array(
            'parameters' => $parameters,
            'sort' => $sort,
            'mustexists' => $mustxists
        ));

        $arr = array();
        foreach ($params['parameters'] as $p) {
            $arr = array_merge($arr, array($p['name'] => $p['value']));
        }

        $result = array();

        if ($params['mustexists'] === FALSE && $params['sort'] === '') {
            $answer = $DB->get_record("lesson_answers", $arr);
        } else if ($params['mustexists'] === FALSE && $params['sort'] != '') {
            $answer = $DB->get_record("lesson_answers", $arr, $params['sort']);
        } else {
            $answer = $DB->get_record("lesson_answers", $arr, '*', MUST_EXIST);
        }

        if (!$answer) {
            $answer = new stdClass();
        }

        $result['answer'] = $answer;
        $result['warnings'] = $warnings;
        return $result;
    }

    public static function get_lesson_answers_by_returns()
    {
        return new external_single_structure(
            array(
                'answer' => new external_single_structure(
                    array(
                        'id' => new external_value(PARAM_INT, 'id'),
                        'lessonid' => new external_value(PARAM_INT, 'lesson id'),
                        'pageid' => new external_value(PARAM_INT, 'page id'),
                        'jumpto' => new external_value(PARAM_INT, 'jumpto'),
                        'grade' => new external_value(PARAM_INT, 'grade'),
                        'score' => new external_value(PARAM_INT, 'score'),
                        'flags' => new external_value(PARAM_INT, 'flags'),
                        'timecreated' => new external_value(PARAM_INT, 'time created'),
                        'timemodified' => new external_value(PARAM_INT, 'time modified'),
                        'answer' => new external_value(PARAM_RAW, 'answer'),
                        'answerformat' => new external_value(PARAM_INT, 'answer format'),
                        'response' => new external_value(PARAM_RAW, 'response'),
                        'responseformat' => new external_value(PARAM_INT, 'response format')
                    )
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function get_lesson_overrides_by_parameters()
    {
        return new external_function_parameters(
            array(
                'parameters' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the params'
                ),
                'sort' => new external_value(PARAM_RAW, 'sort'),
                'mustexists' => new external_value(PARAM_BOOL, 'must exists')
            )
        );
    }

    public static function get_lesson_overrides_by($parameters, $sort, $mustxists)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::get_lesson_overrides_by_parameters(), array(
            'parameters' => $parameters,
            'sort' => $sort,
            'mustexists' => $mustxists
        ));

        $arr = array();
        foreach ($params['parameters'] as $p) {
            $arr = array_merge($arr, array($p['name'] => $p['value']));
        }

        $result = array();

        if ($params['mustexists'] === FALSE && $params['sort'] === '') {
            $override = $DB->get_record("lesson_overrides", $arr);
        } else if ($params['mustexists'] === FALSE && $params['sort'] != '') {
            $override = $DB->get_record("lesson_overrides", $arr, $params['sort']);
        } else {
            $override = $DB->get_record("lesson_overrides", $arr, '*', MUST_EXIST);
        }

        if (!$override) {
            $override = new stdClass();
        }

        $result['override'] = $override;
        $result['warnings'] = $warnings;
        return $result;
    }

    public static function get_lesson_overrides_by_returns()
    {
        return new external_single_structure(
            array(
                'override' => new external_single_structure(
                    array(
                        'id' => new external_value(PARAM_INT, 'the id'),
                        'lessonid' => new external_value(PARAM_INT, 'the lessonid'),
                        'groupid' => new external_value(PARAM_INT, 'the groupid'),
                        'userid' => new external_value(PARAM_INT, 'the userid'),
                        'available' => new external_value(PARAM_INT, 'available'),
                        'deadline' => new external_value(PARAM_INT, 'deadline'),
                        'timelimit' => new external_value(PARAM_INT, 'time limit'),
                        'review' => new external_value(PARAM_INT, 'review'),
                        'maxattempts' => new external_value(PARAM_INT, 'max attempts'),
                        'retake' => new external_value(PARAM_INT, 'retake'),
                        'password' => new external_value(PARAM_TEXT, 'password')
                    )
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function get_lesson_attempts_by_parameters()
    {
        return new external_function_parameters(
            array(
                'parameters' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the params'
                ),
                'sort' => new external_value(PARAM_RAW, 'sort'),
                'mustexists' => new external_value(PARAM_BOOL, 'must exists')
            )
        );
    }

    public static function get_lesson_attempts_by($parameters, $sort, $mustxists)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::get_lesson_attempts_by_parameters(), array(
            'parameters' => $parameters,
            'sort' => $sort,
            'mustexists' => $mustxists
        ));

        $arr = array();
        foreach ($params['parameters'] as $p) {
            $arr = array_merge($arr, array($p['name'] => $p['value']));
        }

        $result = array();

        if ($params['mustexists'] === FALSE && $params['sort'] === '') {
            $attempt = $DB->get_record("lesson_attempts", $arr);
        } else if ($params['mustexists'] === FALSE && $params['sort'] != '') {
            $attempt = $DB->get_record("lesson_attempts", $arr, $params['sort']);
        } else {
            $attempt = $DB->get_record("lesson_attempts", $arr, '*', MUST_EXIST);
        }

        if (!$attempt) {
            $attempt = new stdClass();
        }

        $result['attempt'] = $attempt;
        $result['warnings'] = $warnings;
        return $result;
    }

    public static function get_lesson_attempts_by_returns()
    {
        return new external_single_structure(
            array(
                'attempt' => new external_single_structure(
                    array(
                        'id' => new external_value(PARAM_INT, ''),
                        'lessonid' => new external_value(PARAM_INT, 'lesson id', VALUE_DEFAULT),
                        'pageid' => new external_value(PARAM_INT, 'page id', VALUE_DEFAULT),
                        'userid' => new external_value(PARAM_INT, 'user id', VALUE_DEFAULT),
                        'answerid' => new external_value(PARAM_INT, 'answer id', VALUE_DEFAULT),
                        'retry' => new external_value(PARAM_INT, 'retry', VALUE_DEFAULT),
                        'correct' => new external_value(PARAM_INT, 'correct', VALUE_DEFAULT),
                        'useranswer' => new external_value(PARAM_RAW, 'user answer'),
                        'timeseen' => new external_value(PARAM_INT, 'time seen', VALUE_DEFAULT)
                    )
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function get_list_lesson_grades_by_parameters()
    {
        return new external_function_parameters(
            array(
                'parameters' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the params'
                ),
                'sort' => new external_value(PARAM_RAW, 'sort'),
                'limitfrom' => new external_value(PARAM_INT, 'limit from'),
                'limitnum' => new external_value(PARAM_INT, 'limit num')
            )
        );
    }

    public static function get_list_lesson_grades_by($parameters, $sort, $limitfrom, $limitnum)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::get_list_lesson_grades_by_parameters(), array(
            'parameters' => $parameters,
            'sort' => $sort,
            'limitfrom' => $limitfrom,
            'limitnum' => $limitnum
        ));

        $arr = array();
        foreach ($params['parameters'] as $p) {
            $arr = array_merge($arr, array($p['name'] => $p['value']));
        }

        $result = array();
        if (($params['limitfrom'] == 0 && $params['limitnum'] == 0) && $params['sort'] == '') {
            $grades = $DB->get_records("lesson_grades", $arr);
        } else if (($params['limitfrom'] == 0 && $params['limitnum'] == 0) && $params['sort'] != '') {
            $grades = $DB->get_records("lesson_grades", $arr, $params['sort']);
        } else {
            $grades = $DB->get_records("lesson_grades", $arr, $params['sort'], '*', $params['limitfrom'], $params['limitnum']);
        }

        if (!$grades) {
            $grades = array();
        }

        $result['grades'] = $grades;
        $result['warnings'] = $warnings;

        return $result;
    }

    public static function get_list_lesson_grades_by_returns()
    {
        return new external_single_structure(
            array(
                'grades' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'id' => new external_value(PARAM_INT, 'lesson grade id'),
                            'lessonid' => new external_value(PARAM_INT, 'lesson id'),
                            'userid' => new external_value(PARAM_INT, 'user id'),
                            'grade' => new external_value(PARAM_INT, 'grade'),
                            'late' => new external_value(PARAM_INT, 'late'),
                            'completed' => new external_value(PARAM_INT, 'completed')
                        )
                    ), 'lesson grades'
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function get_list_lesson_timer_by_parameters()
    {
        return new external_function_parameters(
            array(
                'parameters' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the params'
                ),
                'sort' => new external_value(PARAM_RAW, 'sort'),
                'limitfrom' => new external_value(PARAM_INT, 'limit from'),
                'limitnum' => new external_value(PARAM_INT, 'limit num')
            )
        );
    }

    public static function get_list_lesson_timer_by($parameters, $sort, $limitfrom, $limitnum)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::get_list_lesson_timer_by_parameters(), array(
            'parameters' => $parameters,
            'sort' => $sort,
            'limitfrom' => $limitfrom,
            'limitnum' => $limitnum
        ));

        $arr = array();
        foreach ($params['parameters'] as $p) {
            $arr = array_merge($arr, array($p['name'] => $p['value']));
        }

        $result = array();
        if (($params['limitfrom'] == 0 && $params['limitnum'] == 0) && $params['sort'] == '') {
            $timers = $DB->get_records("lesson_timer", $arr);
        } else if (($params['limitfrom'] == 0 && $params['limitnum'] == 0) && $params['sort'] != '') {
            $timers = $DB->get_records("lesson_timer", $arr, $params['sort']);
        } else {
            $timers = $DB->get_records("lesson_timer", $arr, $params['sort'], '*', $params['limitfrom'], $params['limitnum']);
        }

        if (!$timers) {
            $timers = array();
        }

        $result['timers'] = $timers;
        $result['warnings'] = $warnings;

        return $result;
    }

    public static function get_list_lesson_timer_by_returns()
    {
        return new external_single_structure(
            array(
                'timers' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'id' => new external_value(PARAM_INT, 'the lesson timer id'),
                            'lessonid' => new external_value(PARAM_INT, 'the lesson id', VALUE_DEFAULT),
                            'userid' => new external_value(PARAM_INT, 'the user id', VALUE_DEFAULT),
                            'starttime' => new external_value(PARAM_INT, 'start time', VALUE_DEFAULT),
                            'lessontime' => new external_value(PARAM_INT, 'lesson time', VALUE_DEFAULT),
                            'completed' => new external_value(PARAM_INT, 'completed', VALUE_DEFAULT)
                        )
                    ), 'the lesson timer'
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function get_list_lesson_branch_by_parameters()
    {
        return new external_function_parameters(
            array(
                'parameters' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the params'
                ),
                'sort' => new external_value(PARAM_RAW, 'sort'),
                'limitfrom' => new external_value(PARAM_INT, 'limit from'),
                'limitnum' => new external_value(PARAM_INT, 'limit num')
            )
        );
    }

    public static function get_list_lesson_branch_by($parameters, $sort, $limitfrom, $limitnum)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::get_list_lesson_branch_by_parameters(), array(
            'parameters' => $parameters,
            'sort' => $sort,
            'limitfrom' => $limitfrom,
            'limitnum' => $limitnum
        ));

        $arr = array();
        foreach ($params['parameters'] as $p) {
            $arr = array_merge($arr, array($p['name'] => $p['value']));
        }

        $result = array();
        if (($params['limitfrom'] == 0 && $params['limitnum'] == 0) && $params['sort'] == '') {
            $branches = $DB->get_records("lesson_branch", $arr);
        } else if (($params['limitfrom'] == 0 && $params['limitnum'] == 0) && $params['sort'] != '') {
            $branches = $DB->get_records("lesson_branch", $arr, $params['sort']);
        } else {
            $branches = $DB->get_records("lesson_branch", $arr, $params['sort'], '*', $params['limitfrom'], $params['limitnum']);
        }

        if (!$branches) {
            $branches = array();
        }

        $result['branches'] = $branches;
        $result['warnings'] = $warnings;

        return $result;
    }

    public static function get_list_lesson_branch_by_returns()
    {
        return new external_single_structure(
            array(
                'branches' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'id' => new external_value(PARAM_INT, 'id'),
                            'lessonid' => new external_value(PARAM_INT, 'lesson id'),
                            'userid' => new external_value(PARAM_INT, 'user id'),
                            'pageid' => new external_value(PARAM_INT, 'page id'),
                            'retry' => new external_value(PARAM_INT, 'retry'),
                            'flag' => new external_value(PARAM_INT, 'flag'),
                            'timeseen' => new external_value(PARAM_INT, 'time seen'),
                            'nextpageid' => new external_value(PARAM_INT, 'next page id')
                        ), 'lesson branch'
                    )
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function get_list_lesson_attempts_by_parameters()
    {
        return new external_function_parameters(
            array(
                'parameters' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the params'
                ),
                'sort' => new external_value(PARAM_RAW, 'sort'),
                'limitfrom' => new external_value(PARAM_INT, 'limit from'),
                'limitnum' => new external_value(PARAM_INT, 'limit num')
            )
        );
    }

    public static function get_list_lesson_attempts_by($parameters, $sort, $limitfrom, $limitnum)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::get_list_lesson_attempts_by_parameters(), array(
            'parameters' => $parameters,
            'sort' => $sort,
            'limitfrom' => $limitfrom,
            'limitnum' => $limitnum
        ));

        $arr = array();
        foreach ($params['parameters'] as $p) {
            $arr = array_merge($arr, array($p['name'] => $p['value']));
        }

        $result = array();
        if (($params['limitfrom'] == 0 && $params['limitnum'] == 0) && $params['sort'] == '') {
            $attempts = $DB->get_records("lesson_attempts", $arr);
        } else if (($params['limitfrom'] == 0 && $params['limitnum'] == 0) && $params['sort'] != '') {
            $attempts = $DB->get_records("lesson_attempts", $arr, $params['sort']);
        } else {
            $attempts = $DB->get_records("lesson_attempts", $arr, $params['sort'], '*', $params['limitfrom'], $params['limitnum']);
        }

        if (!$attempts) {
            $attempts = array();
        }

        $result['attempts'] = $attempts;
        $result['warnings'] = $warnings;

        return $result;
    }

    public static function get_list_lesson_attempts_by_returns()
    {
        return new external_single_structure(
            array(
                'attempts' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'id' => new external_value(PARAM_INT, ''),
                            'lessonid' => new external_value(PARAM_INT, 'lesson id', VALUE_DEFAULT),
                            'pageid' => new external_value(PARAM_INT, 'page id', VALUE_DEFAULT),
                            'userid' => new external_value(PARAM_INT, 'user id', VALUE_DEFAULT),
                            'answerid' => new external_value(PARAM_INT, 'answer id', VALUE_DEFAULT),
                            'retry' => new external_value(PARAM_INT, 'retry', VALUE_DEFAULT),
                            'correct' => new external_value(PARAM_INT, 'correct', VALUE_DEFAULT),
                            'useranswer' => new external_value(PARAM_RAW, 'user answer'),
                            'timeseen' => new external_value(PARAM_INT, 'time seen', VALUE_DEFAULT)
                        )
                    ), 'lesson attempts'
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function get_list_lesson_attempts_sql_parameters()
    {
        return new external_function_parameters(
            array(
                'sql' => new external_value(PARAM_RAW, 'query'),
                'parameters' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the params'
                )
            )
        );
    }

    public static function get_list_lesson_attempts_sql($sql, $parameters)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::get_list_lesson_attempts_sql_parameters(), array(
            'sql' => $sql,
            'parameters' => $parameters
        ));

        $arr = array();
        foreach ($params['parameters'] as $p) {
            $arr = array_merge($arr, array($p['name'] => $p['value']));
        }

        $result = array();

        $attempts = $DB->get_records_sql($params['sql'], $arr);

        if (!$attempts) {
            $attempts = array();
        }

        $result['attempts'] = $attempts;
        $result['warnings'] = $warnings;

        return $result;
    }

    public static function get_list_lesson_attempts_sql_returns()
    {
        return self::get_list_lesson_attempts_by_returns();
    }

    public static function get_list_lesson_answers_by_parameters()
    {
        return new external_function_parameters(
            array(
                'parameters' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the params'
                ),
                'sort' => new external_value(PARAM_RAW, 'sort'),
                'limitfrom' => new external_value(PARAM_INT, 'limit from'),
                'limitnum' => new external_value(PARAM_INT, 'limit num')
            )
        );
    }

    public static function get_list_lesson_answers_by($parameters, $sort, $limitfrom, $limitnum)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::get_list_lesson_answers_by_parameters(), array(
            'parameters' => $parameters,
            'sort' => $sort,
            'limitfrom' => $limitfrom,
            'limitnum' => $limitnum
        ));

        $arr = array();
        foreach ($params['parameters'] as $p) {
            $arr = array_merge($arr, array($p['name'] => $p['value']));
        }

        $result = array();
        if (($params['limitfrom'] == 0 && $params['limitnum'] == 0) && $params['sort'] == '') {
            $answers = $DB->get_records("lesson_answers", $arr);
        } else if (($params['limitfrom'] == 0 && $params['limitnum'] == 0) && $params['sort'] != '') {
            $answers = $DB->get_records("lesson_answers", $arr, $params['sort']);
        } else {
            $answers = $DB->get_records("lesson_answers", $arr, $params['sort'], '*', $params['limitfrom'], $params['limitnum']);
        }

        if (!$answers) {
            $answers = array();
        }

        $result['answers'] = $answers;
        $result['warnings'] = $warnings;

        return $result;
    }

    public static function get_list_lesson_answers_by_returns()
    {
        return new external_single_structure(
            array(
                'answers' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'id' => new external_value(PARAM_INT, 'id'),
                            'lessonid' => new external_value(PARAM_INT, 'lesson id'),
                            'pageid' => new external_value(PARAM_INT, 'page id'),
                            'jumpto' => new external_value(PARAM_INT, 'jumpto'),
                            'grade' => new external_value(PARAM_INT, 'grade'),
                            'score' => new external_value(PARAM_INT, 'score'),
                            'flags' => new external_value(PARAM_INT, 'flags'),
                            'timecreated' => new external_value(PARAM_INT, 'time created'),
                            'timemodified' => new external_value(PARAM_INT, 'time modified'),
                            'answer' => new external_value(PARAM_RAW, 'answer'),
                            'answerformat' => new external_value(PARAM_INT, 'answer format'),
                            'response' => new external_value(PARAM_RAW, 'response'),
                            'responseformat' => new external_value(PARAM_INT, 'response format')
                        )
                    ), 'lesson answers'
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function get_list_lesson_answers_select_parameters()
    {
        return new external_function_parameters(
            array(
                'usql' => new external_value(PARAM_RAW, 'query sql'),
                'parameters' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the params'
                )
            )
        );
    }

    public static function get_list_lesson_answers_select($usql, $parameters)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::get_list_lesson_answers_select_parameters(), array(
            'usql' => $usql,
            'parameters' => $parameters
        ));

        $arr = array();
        foreach ($params['parameters'] as $p) {
            $arr = array_merge($arr, array($p['name'] => $p['value']));
        }

        $result = array();
        $sql = $params['usql'];

        $answers = $DB->get_records_select("lesson_answers", "lessonid = ? AND pageid $sql", $arr);

        if (!$answers) {
            $answers = array();
        }

        $result['answers'] = $answers;
        $result['warnings'] = $warnings;

        return $result;
    }

    public static function get_list_lesson_answers_select_returns()
    {
        return self::get_list_lesson_answers_by_returns();
    }

    public static function get_list_lesson_pages_by_parameters()
    {
        return new external_function_parameters(
            array(
                'parameters' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the params'
                ),
                'sort' => new external_value(PARAM_RAW, 'sort'),
                'limitfrom' => new external_value(PARAM_INT, 'limit from'),
                'limitnum' => new external_value(PARAM_INT, 'limit num')
            )
        );
    }

    public static function get_list_lesson_pages_by($parameters, $sort, $limitfrom, $limitnum)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::get_list_lesson_pages_by_parameters(), array(
            'parameters' => $parameters,
            'sort' => $sort,
            'limitfrom' => $limitfrom,
            'limitnum' => $limitnum
        ));

        $arr = array();
        foreach ($params['parameters'] as $p) {
            $arr = array_merge($arr, array($p['name'] => $p['value']));
        }

        $result = array();
        if (($params['limitfrom'] == 0 && $params['limitnum'] == 0) && $params['sort'] == '') {
            $pages = $DB->get_records("lesson_pages", $arr);
        } else if (($params['limitfrom'] == 0 && $params['limitnum'] == 0) && $params['sort'] != '') {
            $pages = $DB->get_records("lesson_pages", $arr, $params['sort']);
        } else {
            $pages = $DB->get_records("lesson_pages", $arr, $params['sort'], '*', $params['limitfrom'], $params['limitnum']);
        }

        if (!$pages) {
            $pages = array();
        }

        $result['pages'] = $pages;
        $result['warnings'] = $warnings;

        return $result;
    }

    public static function get_list_lesson_pages_by_returns()
    {
        return new external_single_structure(
            array(
                'pages' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'id' => new external_value(PARAM_INT, 'the lesson page id'),
                            'lessonid' => new external_value(PARAM_INT, 'lesson id', VALUE_DEFAULT),
                            'prevpageid' => new external_value(PARAM_INT, 'previous page id', VALUE_DEFAULT),
                            'nextpageid' => new external_value(PARAM_INT, 'next page id', VALUE_DEFAULT),
                            'qtype' => new external_value(PARAM_INT, 'qtype', VALUE_DEFAULT),
                            'qoption' => new external_value(PARAM_INT, 'qoption', VALUE_DEFAULT),
                            'layout' => new external_value(PARAM_INT, 'layout', VALUE_REQUIRED),
                            'display' => new external_value(PARAM_INT, 'display', VALUE_REQUIRED),
                            'timecreated' => new external_value(PARAM_INT, 'time created', VALUE_DEFAULT),
                            'timemodified' => new external_value(PARAM_INT, 'time modified', VALUE_DEFAULT),
                            'title' => new external_value(PARAM_RAW, 'title'),
                            'contents' => new external_value(PARAM_RAW, 'contents'),
                            'contentsformat' => new external_value(PARAM_INT, 'contents format', VALUE_DEFAULT)
                        )
                    ), 'lesson pages'
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function get_list_lesson_pages_select_parameters()
    {
        return new external_function_parameters(
            array(
                'usql' => new external_value(PARAM_RAW, 'query sql'),
                'parameters' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the params'
                )
            )
        );
    }

    public static function get_list_lesson_pages_select($usql, $parameters)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::get_list_lesson_pages_select_parameters(), array(
            'usql' => $usql,
            'parameters' => $parameters
        ));

        $arr = array();
        foreach ($params['parameters'] as $p) {
            $arr = array_merge($arr, array($p['name'] => $p['value']));
        }

        $result = array();

        $sql = $params['usql'];

        $pages = $DB->get_records_select("lesson_pages", "lessonid = ? AND id $sql", $arr);

        if (!$pages) {
            $pages = array();
        }

        $result['pages'] = $pages;
        $result['warnings'] = $warnings;

        return $result;
    }

    public static function get_list_lesson_pages_select_returns()
    {
        return self::get_list_lesson_pages_by_returns();
    }

    public static function get_list_lesson_overrides_by_parameters()
    {
        return new external_function_parameters(
            array(
                'parameters' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the params'
                ),
                'sort' => new external_value(PARAM_RAW, 'sort'),
                'limitfrom' => new external_value(PARAM_INT, 'limit from'),
                'limitnum' => new external_value(PARAM_INT, 'limit num')
            )
        );
    }

    public static function get_list_lesson_overrides_by($parameters, $sort, $limitfrom, $limitnum)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::get_list_lesson_overrides_by_parameters(), array(
            'parameters' => $parameters,
            'sort' => $sort,
            'limitfrom' => $limitfrom,
            'limitnum' => $limitnum
        ));

        $arr = array();
        foreach ($params['parameters'] as $p) {
            $arr = array_merge($arr, array($p['name'] => $p['value']));
        }

        $result = array();
        if (($params['limitfrom'] == 0 && $params['limitnum'] == 0) && $params['sort'] == '') {
            $overrides = $DB->get_records("lesson_overrides", $arr);
        } else if (($params['limitfrom'] == 0 && $params['limitnum'] == 0) && $params['sort'] != '') {
            $overrides = $DB->get_records("lesson_overrides", $arr, $params['sort']);
        } else {
            $overrides = $DB->get_records("lesson_overrides", $arr, $params['sort'], '*', $params['limitfrom'], $params['limitnum']);
        }

        if (!$overrides) {
            $overrides = array();
        }

        $result['overrides'] = $overrides;
        $result['warnings'] = $warnings;

        return $result;
    }

    public static function get_list_lesson_overrides_by_returns()
    {
        return new external_single_structure(
            array(
                'overrides' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'id' => new external_value(PARAM_INT, 'the id'),
                            'lessonid' => new external_value(PARAM_INT, 'the lessonid', VALUE_DEFAULT),
                            'groupid' => new external_value(PARAM_INT, 'the groupid'),
                            'userid' => new external_value(PARAM_INT, 'the userid'),
                            'available' => new external_value(PARAM_INT, 'available'),
                            'deadline' => new external_value(PARAM_INT, 'deadline'),
                            'timelimit' => new external_value(PARAM_INT, 'time limit'),
                            'review' => new external_value(PARAM_INT, 'review'),
                            'maxattempts' => new external_value(PARAM_INT, 'max attempts'),
                            'retake' => new external_value(PARAM_INT, 'retake'),
                            'password' => new external_value(PARAM_TEXT, 'password')
                        )
                    ), 'lesson overrides'
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function get_list_lesson_by_parameters()
    {
        return new external_function_parameters(
            array(
                'parameters' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the params'
                ),
                'sort' => new external_value(PARAM_RAW, 'sort'),
                'limitfrom' => new external_value(PARAM_INT, 'limit from'),
                'limitnum' => new external_value(PARAM_INT, 'limit num')
            )
        );
    }

    public static function get_list_lesson_by($parameters, $sort, $limitfrom, $limitnum)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::get_list_lesson_by_parameters(), array(
            'parameters' => $parameters,
            'sort' => $sort,
            'limitfrom' => $limitfrom,
            'limitnum' => $limitnum
        ));

        $arr = array();
        foreach ($params['parameters'] as $p) {
            $arr = array_merge($arr, array($p['name'] => $p['value']));
        }

        $result = array();
        if (!$arr) {
            $lessons = $DB->get_records("lesson");
        } else if (($params['limitfrom'] == 0 && $params['limitnum'] == 0) && $params['sort'] == '') {
            $lessons = $DB->get_records("lesson", $arr);
        } else if (($params['limitfrom'] == 0 && $params['limitnum'] == 0) && $params['sort'] != '') {
            $lessons = $DB->get_records("lesson", $arr, $params['sort']);
        } else {
            $lessons = $DB->get_records("lesson", $arr, $params['sort'], '*', $params['limitfrom'], $params['limitnum']);
        }

        if (!$lessons) {
            $lessons = array();
        }

        $result['lessons'] = $lessons;
        $result['warnings'] = $warnings;

        return $result;
    }

    public static function get_list_lesson_by_returns()
    {
        return new external_single_structure(
            array(
                'lessons' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'id' => new external_value(PARAM_INT, 'lesson id'),
                            'course' => new external_value(PARAM_INT, 'course id', VALUE_DEFAULT),
                            'name' => new external_value(PARAM_RAW, 'lesson name'),
                            'intro' => new external_value(PARAM_RAW, 'lesson intro'),
                            'introformat' => new external_value(PARAM_INT, 'intro format', VALUE_DEFAULT),
                            'practice' => new external_value(PARAM_INT, 'practice', VALUE_DEFAULT),
                            'modattempts' => new external_value(PARAM_INT, 'mod attempts', VALUE_DEFAULT),
                            'usepassword' => new external_value(PARAM_INT, 'use password', VALUE_DEFAULT),
                            'password' => new external_value(PARAM_TEXT, 'password'),
                            'dependency' => new external_value(PARAM_INT, 'dependency', VALUE_DEFAULT),
                            'conditions' => new external_value(PARAM_RAW, 'condition'),
                            'grade' => new external_value(PARAM_INT, 'grade', VALUE_DEFAULT),
                            'custom' => new external_value(PARAM_INT, 'custom', VALUE_DEFAULT),
                            'ongoing' => new external_value(PARAM_INT, 'on going', VALUE_DEFAULT),
                            'usemaxgrade' => new external_value(PARAM_INT, 'use max grade', VALUE_DEFAULT),
                            'maxanswers' => new external_value(PARAM_INT, 'max answer'),
                            'maxattempts' => new external_value(PARAM_INT, 'max attempts'),
                            'review' => new external_value(PARAM_INT, 'review', VALUE_DEFAULT),
                            'nextpagedefault' => new external_value(PARAM_INT, 'next page default', VALUE_DEFAULT),
                            'feedback' => new external_value(PARAM_INT, 'feedback', VALUE_REQUIRED),
                            'minquestions' => new external_value(PARAM_INT, 'min question', VALUE_DEFAULT),
                            'maxpages' => new external_value(PARAM_INT, 'max page', VALUE_DEFAULT),
                            'timelimit' => new external_value(PARAM_INT, 'time limit', VALUE_DEFAULT),
                            'retake' => new external_value(PARAM_INT, 'retake', VALUE_DEFAULT),
                            'activitylink' => new external_value(PARAM_INT, 'activity link', VALUE_REQUIRED),
                            'mediafile' => new external_value(PARAM_TEXT, 'media file', VALUE_DEFAULT),
                            'mediaheight' => new external_value(PARAM_INT, 'media height'),
                            'mediawidth' => new external_value(PARAM_INT, 'media width'),
                            'mediaclose' => new external_value(PARAM_INT, 'media close'),
                            'slideshow' => new external_value(PARAM_INT, 'slideshow'),
                            'width' => new external_value(PARAM_INT, 'slideshow width'),
                            'height' => new external_value(PARAM_INT, 'slideshow height'),
                            'bgcolor' => new external_value(PARAM_TEXT, 'background color'),
                            'displayleft' => new external_value(PARAM_INT, 'display left'),
                            'displayleftif' => new external_value(PARAM_INT, 'display left if', VALUE_DEFAULT),
                            'progressbar' => new external_value(PARAM_INT, 'progress bar', VALUE_DEFAULT),
                            'available' => new external_value(PARAM_INT, 'available', VALUE_DEFAULT),
                            'deadline' => new external_value(PARAM_INT, 'deadline', VALUE_DEFAULT),
                            'timemodified' => new external_value(PARAM_INT, 'time modified', VALUE_DEFAULT),
                            'completionendreached' => new external_value(PARAM_INT, 'completion end reached', VALUE_DEFAULT),
                            'completiontimespent' => new external_value(PARAM_INT, 'completion time spent', VALUE_DEFAULT)
                        )
                    ), 'lesson overrides'
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function save_mdl_lesson_parameters()
    {
        return new external_function_parameters(
            array(
                'modname' => new external_value(PARAM_RAW, 'the mod name'),
                'data' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the data saved'
                )
            )
        );
    }

    public static function save_mdl_lesson($modname, $data)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::save_mdl_lesson_parameters(), array(
            'modname' => $modname,
            'data' => $data
        ));

        $obj = new stdClass();

        foreach ($params['data'] as $element) {
            $obj->$element['name'] = $element['value'];
        }

        $result = array();

        $transaction = $DB->start_delegated_transaction();

        $newid = $DB->insert_record($params['modname'], $obj);

        $transaction->allow_commit();

        $result['newid'] = $newid;
        $result['warnings'] = $warnings;

        return $result;
    }

    public static function save_mdl_lesson_returns()
    {
        return new external_single_structure(
            array(
                'newid' => new external_value(PARAM_INT, 'the new id'),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function update_mdl_lesson_parameters()
    {
        return new external_function_parameters(
            array(
                'modname' => new external_value(PARAM_RAW, 'the mod name'),
                'id' => new external_value(PARAM_INT, 'the id'),
                'data' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the data saved'
                )
            )
        );
    }

    public static function update_mdl_lesson($modname, $id, $data)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::update_mdl_lesson_parameters(), array(
            'modname' => $modname,
            'id' => $id,
            'data' => $data
        ));

        $result = array();

        $obj = $DB->get_record($params['modname'], array("id" => $params['id']));

        if (!$obj) {
            $warnings['message'] = "Not found data record";
            $result['id'] = 0;
            $result['warnings'] = $warnings;
            return $result;
        }

        foreach ($params['data'] as $element) {
            $obj->$element['name'] = $element['value'];
        }

        $transaction = $DB->start_delegated_transaction();

        $cid = $DB->update_record($params['modname'], $obj);

        $transaction->allow_commit();

        $result['id'] = $cid;
        $result['warnings'] = $warnings;

        return $result;
    }

    public static function update_mdl_lesson_returns()
    {
        return new external_single_structure(
            array(
                'id' => new external_value(PARAM_INT, 'the id'),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function update_mdl_table_parameters()
    {
        return new external_function_parameters(
            array(
                'tablename' => new external_value(PARAM_RAW, 'tablename'),
                'params' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'op' => new external_value(PARAM_RAW, 'param op'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the params'
                ),
                'data' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'data name'),
                            'value' => new external_value(PARAM_RAW, 'data value'),
                        )
                    ), 'the data to be saved'
                )
            )
        );
    }

    public static function update_mdl_table($tablename, $params, $data)
    {
        global $DB;

        $warnings = array();

        $params = self::validate_parameters(self::update_mdl_table_parameters(), array(
            'tablename' => $tablename,
            'params' => $params,
            'data' => $data
        ));

        $sql = "UPDATE {$params['tablename']} SET ";


        foreach ($params['data'] as $element) {
            $sql .= $element['name'] . "=" . $element['value'];
        }

        $sql .= " WHERE ";
        $parameters = array();

        foreach ($params['params'] as $p) {
            $sql .= $p['name'] . $p['op'] . '?';
            $parameters = array_merge($parameters, array($p['value']));
        }

        $result = array();

        $transaction = $DB->start_delegated_transaction();

        $DB->execute($sql, $parameters);

        $transaction->allow_commit();

        $result['status'] = true;
        $result['warnings'] = $warnings;

        return $result;
    }

    public static function update_mdl_table_returns()
    {
        return new external_single_structure(
            array(
                'status' => new external_value(PARAM_BOOL, 'status'),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function delete_mdl_lesson_parameters()
    {
        return new external_function_parameters(
            array(
                'tablename' => new external_value(PARAM_TEXT, ' the table name'),
                'data' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'data name'),
                            'value' => new external_value(PARAM_RAW, 'data value'),
                        )
                    ), 'the data to be deleted'
                )
            )
        );
    }

    public static function delete_mdl_lesson($tablename, $data)
    {
        global $DB;

        $warnings = array();

        $params = array(
            'tablename' => $tablename,
            'data' => $data
        );

        $params = self::validate_parameters(self::delete_mdl_lesson_parameters(), $params);

        $parameters = array();

        foreach ($params['data'] as $element) {
            $parameters = array_merge($parameters, [$element['name'] => $element['value']]);
        }

        $result = array();

        $transaction = $DB->start_delegated_transaction();

        $DB->delete_records($params['tablename'], $parameters);

        $transaction->allow_commit();

        $result['status'] = true;
        $result['warnings'] = $warnings;

        return $result;
    }

    public static function delete_mdl_lesson_returns()
    {
        return new external_single_structure(
            array(
                'status' => new external_value(PARAM_BOOL, 'status'),
                'warnings' => new external_warnings()
            )
        );
    }
}