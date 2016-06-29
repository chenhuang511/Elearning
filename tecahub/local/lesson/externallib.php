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
    public static function get_lesson_by_id_parameters()
    {
        return new external_function_parameters(
            array(
                'lessonid' => new external_value(PARAM_INT, 'the lesson id')
            )
        );
    }

    /**
     * Get lesson by id
     *
     * @param int $id . The lesson id
     * @return array of warnings and the lesson
     * @since Moodle 3.0
     * @throws moodle_exception
     */
    public static function get_lesson_by_id($lessonid)
    {
        global $DB;

        $warnings = array();

        // validate params
        $params = self::validate_parameters(self::get_lesson_by_id_parameters(),
            array(
                'lessonid' => $lessonid,
            )
        );

        $result = array();

        $lesson = $DB->get_record('lesson', array('id' => $params['lessonid']), '*', MUST_EXIST);

        if (!$lesson) {
            $lesson = new stdClass();
        }

        $result['lesson'] = $lesson;
        $result['warnigs'] = $warnings;

        return $result;
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     * @since Moodle 3.0
     */
    public static function get_lesson_by_id_returns()
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

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 3.0
     */
    public static function get_lesson_pages_by_lessonid_and_prevpageid_parameters()
    {
        return new external_function_parameters(
            array('lessonid' => new external_value(PARAM_INT, 'the lesson id'),
                'prevpageid' => new external_value(PARAM_INT, 'the previous page id'),
            )
        );
    }

    /**
     * Get lesson pages by lessonid and prevpageid
     *
     * @param int $lessonid the lesson id
     * @param int $prevpageid . The previous page id
     * @return array of warnings and the lesson
     * @since Moodle 3.0
     * @throws moodle_exception
     */
    public static function get_lesson_pages_by_lessonid_and_prevpageid($lessonid, $prevpageid)
    {
        global $DB;

        $warnings = array();

        // validate params
        $params = self::validate_parameters(self::get_lesson_pages_by_lessonid_and_prevpageid_parameters(),
            array(
                'lessonid' => $lessonid,
                'prevpageid' => $prevpageid
            )
        );

        $result = array();

        $page = $DB->get_record('lesson_pages', array('lessonid' => $params['lessonid'], 'prevpageid' => $params['prevpageid']), '*', MUST_EXIST);

        if (!$page) {
            $page = new stdClass();
        }

        $result['page'] = $page;
        $result['warnings'] = $warnings;

        return $result;
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     * @since Moodle 3.0
     */
    public static function get_lesson_pages_by_lessonid_and_prevpageid_returns()
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

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 3.0
     */
    public static function get_field_lesson_pages_by_lessonid_and_prevpageid_parameters()
    {
        return new external_function_parameters(
            array('lessonid' => new external_value(PARAM_INT, 'the lesson id'),
                'prevpageid' => new external_value(PARAM_INT, 'the previous page id'),
                'options' => new external_multiple_structure (
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_ALPHANUM,
                                'The expected keys (value format) are:
                                                excludemodules (bool) Do not return modules, return only the sections structure
                                                excludecontents (bool) Do not return module contents (i.e: files inside a resource)
                                                sectionid (int) Return only this section
                                                sectionnumber (int) Return only this section with number (order)
                                                cmid (int) Return only this module information (among the whole sections structure)
                                                modname (string) Return only modules with this name "label, forum, etc..."
                                                modid (int) Return only the module with this id (to be used with modname'),
                            'value' => new external_value(PARAM_RAW, 'the value of the option,
                                                                    this param is personaly validated in the external function.')
                        )
                    ), 'Options, used since Moodle 2.9', VALUE_DEFAULT, array())
            )
        );
    }

    /**
     * Get field of lesson pages by lessonid and prevpageid
     *
     * @param $lessonid . The id of lesson
     * @param $prevpageid . The previous page id
     * @param array $options
     * @return mixed
     * @throws invalid_parameter_exception
     */
    public static function get_field_lesson_pages_by_lessonid_and_prevpageid($lessonid, $prevpageid)
    {
        global $DB;

        $warnings = array();

        // validate params
        $params = self::validate_parameters(self::get_field_lesson_pages_by_lessonid_and_prevpageid_parameters(),
            array(
                'lessonid' => $lessonid,
                'prevpageid' => $prevpageid
            )
        );

        $result = array();

        $id = $DB->get_field('lesson_pages', 'id', array('lessonid' => $params['lessonid'], 'prevpageid' => $params['prevpageid']));

        if (!$id) {
            $id = 0;
        }

        $result['id'] = $id;
        $result['warnings'] = $warnings;

        return $result;
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     * @since Moodle 3.0
     */
    public static function get_field_lesson_pages_by_lessonid_and_prevpageid_returns()
    {
        return new external_single_structure(
            array(
                'id' => new external_value(PARAM_INT, 'id'),
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
    public static function get_field_lesson_pages_by_id_parameters()
    {
        return new external_function_parameters(
            array('id' => new external_value(PARAM_INT, 'the id'),
                'field' => new external_value(PARAM_TEXT, 'the field which selected')
            )
        );
    }

    /**
     * Get field of lesson page by id
     *
     * @param $id . The id
     * @param $field . The field in lesson page
     * @param array $options
     * @return mixed
     * @throws invalid_parameter_exception
     */
    public static function get_field_lesson_pages_by_id($id, $field)
    {
        global $DB;

        $warnings = array();

        // validate params
        $params = self::validate_parameters(self::get_field_lesson_pages_by_id_parameters(),
            array(
                'id' => $id,
                'field' => $field
            )
        );

        $result = array();

        $field = $DB->get_field('lesson_pages', $params['field'], array("id" => $params['id']));

        if (!$field) {
            $result['field'] = 0;
        }

        $result['field'] = $field;
        $result['warnings'] = $warnings;

        return $result;
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     * @since Moodle 3.0
     */
    public static function get_field_lesson_pages_by_id_returns()
    {
        return new external_single_structure(
            array(
                'field' => new external_value(PARAM_RAW, 'field'),
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
    public static function get_lesson_pages_by_id_and_lessonid_parameters()
    {
        return new external_function_parameters(
            array('id' => new external_value(PARAM_INT, 'the id'),
                'lessonid' => new external_value(PARAM_INT, 'the lesson id')
            )
        );
    }

    /**
     * Get lesson page by pageid and lessonid
     *
     * @param $pageid
     * @param $lessonid
     * @param array $options
     * @return mixed
     * @throws invalid_parameter_exception
     */
    public static function get_lesson_pages_by_id_and_lessonid($id, $lessonid)
    {
        global $DB;

        $warnings = array();

        // validate params
        $params = self::validate_parameters(self::get_lesson_pages_by_id_and_lessonid_parameters(),
            array(
                'lessonid' => $lessonid,
                'id' => $id
            )
        );

        $result = array();

        $page = $DB->get_record('lesson_pages', array('id' => $params['id'], 'lessonid' => $params['lessonid']), '*', MUST_EXIST);

        if (!$page) {
            $page = new stdClass();
        }

        $result['page'] = $page;
        $result['warnings'] = $warnings;

        return $result;
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     * @since Moodle 3.0
     */
    public static function get_lesson_pages_by_id_and_lessonid_returns()
    {
        return self::get_lesson_pages_by_lessonid_and_prevpageid_returns();
    }

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 3.0
     */
    public static function get_lesson_pages_by_id_parameters()
    {
        return new external_function_parameters(
            array('id' => new external_value(PARAM_INT, 'the id'),
                'mustexist' => new external_value(PARAM_BOOL, 'must exist')
            )
        );
    }

    /**
     * Get lesson pages by id
     *
     * @param $id
     * @param $mustexist
     * @return mixed
     * @throws invalid_parameter_exception
     */
    public static function get_lesson_pages_by_id($id, $mustexist)
    {
        global $DB;

        // validate params
        $params = self::validate_parameters(self::get_lesson_pages_by_id_parameters(),
            array(
                'id' => $id,
                'mustexist' => $mustexist
            )
        );

        if ($params['mustexist']) {
            return $DB->get_record('lesson_pages', array('id' => $params['id']), '*', MUST_EXIST);
        }

        return $DB->get_record('lesson_pages', array('id' => $params['id']));
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     * @since Moodle 3.0
     */
    public static function get_lesson_pages_by_id_returns()
    {
        return self::get_lesson_pages_by_id_and_lessonid_returns();
    }

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 3.0
     */
    public static function get_lesson_timer_by_userid_and_lessonid_parameters()
    {
        return new external_function_parameters(
            array('userid' => new external_value(PARAM_INT, 'user id'),
                'lessonid' => new external_value(PARAM_INT, 'lesson id'),
                'limitfrom' => new external_value(PARAM_INT, 'limit from'),
                'limitnum' => new external_value(PARAM_INT, 'limit num')
            )
        );
    }

    /**
     * get leson timer by userid and lessonid
     *
     * @param $userid
     * @param $lessonid
     * @param array $options
     * @return mixed
     * @throws invalid_parameter_exception
     */
    public static function get_lesson_timer_by_userid_and_lessonid($userid, $lessonid, $limitfrom, $limitnum)
    {
        global $DB;

        $warnings = array();

        $arr = array(
            'userid' => $userid,
            'lessonid' => $lessonid,
            'limitfrom' => $limitfrom,
            'limitnum' => $limitnum
        );


        // validate params
        $params = self::validate_parameters(self::get_lesson_timer_by_userid_and_lessonid_parameters(),
            $arr
        );

        $result = array();
        $timers = $DB->get_records('lesson_timer', array('userid' => $params['userid'], 'lessonid' => $params['lessonid']), 'starttime DESC', '*', $params['limitfrom'], $params['limitnum']);

        if (!$timers) {
            $timers = new stdClass();
        }

        $result['timers'] = $timers;
        $result['warnings'] = $warnings;

        return $result;
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     * @since Moodle 3.0
     */
    public static function get_lesson_timer_by_userid_and_lessonid_returns()
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


    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 3.0
     */
    public static function get_lesson_grades_by_userid_and_lessonid_parameters()
    {
        return new external_function_parameters(
            array('useid' => new external_value(PARAM_INT, 'user id'),
                'lessonid' => new external_value(PARAM_INT, 'lesson id'),
                'options' => new external_multiple_structure (
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_ALPHANUM,
                                'The expected keys (value format) are:
                                                excludemodules (bool) Do not return modules, return only the sections structure
                                                excludecontents (bool) Do not return module contents (i.e: files inside a resource)
                                                sectionid (int) Return only this section
                                                sectionnumber (int) Return only this section with number (order)
                                                cmid (int) Return only this module information (among the whole sections structure)
                                                modname (string) Return only modules with this name "label, forum, etc..."
                                                modid (int) Return only the module with this id (to be used with modname'),
                            'value' => new external_value(PARAM_RAW, 'the value of the option,
                                                                    this param is personaly validated in the external function.')
                        )
                    ), 'Options, used since Moodle 2.9', VALUE_DEFAULT, array())
            )
        );
    }

    /**
     * get lesson grades by userid and lessonid
     *
     * @param $userid
     * @param $lessonid
     * @param array $options
     * @return mixed
     * @throws invalid_parameter_exception
     */
    public static function get_lesson_grades_by_userid_and_lessonid($userid, $lessonid, $options = array())
    {
        global $DB;

        // validate params
        $params = self::validate_parameters(self::get_lesson_grades_by_userid_and_lessonid_parameters(),
            array(
                'userid' => $userid,
                'lessonid' => $lessonid,
                'options' => $options
            )
        );

        return $DB->get_record('lesson_grades', array('userid' => $params['userid'], 'lessonid' => $params['lessonid']), '*', MUST_EXIST);
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     * @since Moodle 3.0
     */
    public static function get_lesson_grades_by_userid_and_lessonid_returns()
    {
        return new external_single_structure(
            array(
                'id' => new external_value(PARAM_INT, 'lesson grade id'),
                'lessonid' => new external_value(PARAM_INT, 'lesson id', VALUE_DEFAULT),
                'userid' => new external_value(PARAM_INT, 'user id', VALUE_DEFAULT),
                'grade' => new external_value(PARAM_INT, 'grade', VALUE_DEFAULT),
                'late' => new external_value(PARAM_INT, 'late', VALUE_DEFAULT),
                'completed' => new external_value(PARAM_INT, 'completed', VALUE_DEFAULT)
            )
        );
    }

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 3.0
     */
    public static function get_lesson_branch_by_lessonid_and_userid_and_retry_parameters()
    {
        return new external_function_parameters(
            array('lessonid' => new external_value(PARAM_INT, 'lesson id'),
                'userid' => new external_value(PARAM_INT, 'user id'),
                'retry' => new external_value(PARAM_INT, 'retry'),
                'sort' => new external_value(PARAM_TEXT, 'sort by')
            )
        );
    }

    /**
     * get lesson branch
     *
     * @param $lessonid
     * @param $userid
     * @param $retry
     * @param array $options
     * @return mixed
     * @throws invalid_parameter_exception
     */
    public static function get_lesson_branch_by_lessonid_and_userid_and_retry($lessonid, $userid, $retry, $sort)
    {
        global $DB;

        $warnings = array();

        // validate params
        $params = self::validate_parameters(self::get_lesson_branch_by_lessonid_and_userid_and_retry_parameters(),
            array(
                'lessonid' => $lessonid,
                'userid' => $userid,
                'retry' => $retry,
                'sort' => $sort
            )
        );

        $sortby = 'timeseen ASC';

        if ($params['sort'] === 'desc') {
            $sortby = "timeseen DESC";
        }

        $result = array();

        $branches = $DB->get_records('lesson_branch', array('lessonid' => $params['lessonid'], 'userid' => $params['userid'], 'retry' => $params['retry']), $sortby);

        if (!$branches) {
            $branches = array();
        }

        $result['branches'] = $branches;
        $result['warnings'] = $warnings;

        return $result;
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     * @since Moodle 3.0
     */
    public static function get_lesson_branch_by_lessonid_and_userid_and_retry_returns()
    {
        return new external_single_structure(
            array(
                'branches' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'id' => new external_value(PARAM_INT, 'id'),
                            'lessonid' => new external_value(PARAM_INT, 'lesson id', VALUE_DEFAULT),
                            'userid' => new external_value(PARAM_INT, 'user id', VALUE_DEFAULT),
                            'pageid' => new external_value(PARAM_INT, 'page id', VALUE_DEFAULT),
                            'retry' => new external_value(PARAM_INT, 'retry', VALUE_DEFAULT),
                            'flag' => new external_value(PARAM_INT, 'flag', VALUE_DEFAULT),
                            'timeseen' => new external_value(PARAM_INT, 'time seen', VALUE_DEFAULT),
                            'nextpageid' => new external_value(PARAM_INT, 'next page id', VALUE_DEFAULT)
                        ), 'lesson branch'
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
    public static function get_lesson_attempts_by_lessonid_and_userid_parameters()
    {
        return new external_function_parameters(
            array('lessonid' => new external_value(PARAM_INT, 'lesson id'),
                'userid' => new external_value(PARAM_INT, 'user id'),
                'correct' => new external_value(PARAM_INT, 'correct', VALUE_DEFAULT, 0),
                'pageid' => new external_value(PARAM_INT, 'page id', VALUE_DEFAULT, -1),
                'retry' => new external_value(PARAM_INT, 'retry'),
                'orderby' => new external_value(PARAM_TEXT, 'timeseen order by', VALUE_DEFAULT, 'asc')
            )
        );
    }

    /**
     * Get lesson attempts by lessonid and userid
     *
     * @param $lessonid
     * @param $userid
     * @param $correct
     * @param $pageid
     * @param $retry
     * @param $orderby
     * @param array $options
     * @return array
     * @throws invalid_parameter_exception
     */
    public static function get_lesson_attempts_by_lessonid_and_userid($lessonid, $userid, $correct, $pageid, $retry, $orderby)
    {
        global $DB;

        $warnings = array();

        $arr = array(
            'lessonid' => $lessonid,
            'userid' => $userid,
            'retry' => $retry
        );

        if (!empty($correct) && $correct != 0) { // 0: false, 1: true
            $arr = array_merge($arr, array('correct' => 1));
        }
        if (!empty($pageid) && $pageid != -1) { // -1: null
            $arr = array_merge($arr, array('pageid' => $pageid));
        }

        // validate params
        $params = self::validate_parameters(self::get_lesson_attempts_by_lessonid_and_userid_parameters(),
            $arr
        );

        $parameters = [
            'lessonid' => $params['lessonid'],
            'userid' => $params['userid'],
            'retry' => $params['retry']
        ];

        if (isset($arr['correct'])) {
            $parameters = array_merge($parameters, array('correct' => $params['correct']));
        }
        if (isset($arr['pageid'])) {
            $parameters = array_merge($parameters, array('pageid' => $params['pageid']));
        }

        $timeseen = 'timeseen ASC';
        if (!empty($orderby) && $orderby === 'desc') {
            $timeseen = 'timeseen DESC';
        }

        $result = array();

        $attempts = $DB->get_records('lesson_attempts', $parameters, $timeseen);

        if (!$attempts) {
            $attempts = array();
        }

        $result['attempts'] = $attempts;
        $result['warnings'] = $warnings;

        return $result;
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     * @since Moodle 3.0
     */
    public static function get_lesson_attempts_by_lessonid_and_userid_returns()
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

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 3.0
     */
    public static function get_lesson_attempts_by_pageid_parameters()
    {
        return new external_function_parameters(
            array('pageid' => new external_value(PARAM_INT, 'page id')
            )
        );
    }

    /**
     * Get list of lesson attempts by pageid
     *
     * @param $pageid
     * @return array
     * @throws invalid_parameter_exception
     */
    public static function get_lesson_attempts_by_pageid($pageid)
    {
        global $DB;

        $warnings = array();

        // validate params
        $params = self::validate_parameters(self::get_lesson_attempts_by_pageid_parameters(),
            array(
                'pageid' => $pageid
            )
        );

        $result = array();

        $attempts = $DB->get_records('lesson_attempts', array('pageid' => $params['pageid']));

        if (!$attempts) {
            $attempts = array();
        }

        $result['attempts'] = $attempts;
        $result['warnings'] = $warnings;

        return $result;
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     * @since Moodle 3.0
     */
    public static function get_lesson_attempts_by_pageid_returns()
    {
        return self::get_lesson_attempts_by_lessonid_and_userid_returns();
    }

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 3.0
     */
    public static function get_count_by_lessonid_and_userid_parameters()
    {
        return new external_function_parameters(
            array('tablename' => new external_value(PARAM_TEXT, 'table name'),
                'lessonid' => new external_value(PARAM_INT, 'the lesson id'),
                'userid' => new external_value(PARAM_INT, 'the user id', VALUE_DEFAULT, 0),
                'retry' => new external_value(PARAM_INT, 'the retry', VALUE_DEFAULT, -1),
                'orderby' => new external_value(PARAM_TEXT, 'order by', VALUE_DEFAULT, '')
            )
        );
    }

    /**
     * get retries of lesson grades
     *
     * @param $lessonid
     * @param $userid
     * @param array $options
     * @return int
     * @throws invalid_parameter_exception
     */
    public static function get_count_by_lessonid_and_userid($tablename, $lessonid, $userid, $retry, $orderby)
    {
        global $DB;

        $warnings = array();

        $arr = array(
            'tablename' => $tablename,
            'lessonid' => $lessonid
        );

        if ($userid > 0) {
            $arr = array_merge($arr, array('userid' => $userid));
        }
        if (($arr['tablename'] === 'lesson_attempts' || $arr['tablename'] === 'lesson_branch') && $retry >= 0) {
            $arr = array_merge($arr, array('retry' => $retry));
        }
        if (!is_null($orderby) || !empty($orderby)) {
            $arr = array_merge($arr, array('orderby' => $orderby));
        }

        // validate params
        $params = self::validate_parameters(self::get_count_by_lessonid_and_userid_parameters(),
            $arr
        );

        $parameters = array("lessonid" => $params['lessonid']);

        if (isset($arr['userid'])) {
            $parameters = array_merge($parameters, array("userid" => $params['userid']));
        }
        if (isset($arr['retry'])) {
            $parameters = array_merge($parameters, array("retry" => $params['retry']));
        }

        if (isset($arr['orderby'])) {
            $show = $arr['orderby'];
        }

        $result = array();

        $retries = $DB->count_records($params['tablename'], $parameters, $show);

        if (!$retries) {
            $result['retries'] = 0;
        }

        $result['retries'] = $retries;
        $result['warnings'] = $warnings;

        return $result;
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     * @since Moodle 3.0
     */
    public static function get_count_by_lessonid_and_userid_returns()
    {
        return new external_single_structure(
            array(
                'retries' => new external_value(PARAM_INT, 'retries'),
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
    public static function get_lesson_answers_by_pageid_and_lessonid_parameters()
    {
        return new external_function_parameters(
            array('pageid' => new external_value(PARAM_INT, 'the page id'),
                'lessonid' => new external_value(PARAM_INT, 'the lesson id')
            )
        );
    }

    /**
     * Get lesson answers by pageid and lessonid
     *
     * @param $pageid
     * @param $lessonid
     * @param array $options
     * @return array
     * @throws invalid_parameter_exception
     */
    public static function get_lesson_answers_by_pageid_and_lessonid($pageid, $lessonid)
    {
        global $DB;

        $warnings = array();

        // validate params
        $params = self::validate_parameters(self::get_lesson_answers_by_pageid_and_lessonid_parameters(),
            array(
                'pageid' => $pageid,
                'lessonid' => $lessonid
            )
        );

        $result = array();

        $answers = $DB->get_records('lesson_answers', array('pageid' => $params['pageid'], 'lessonid' => $params['lessonid']), 'id');

        if (!$answers) {
            $answers = array();
        }

        $result['answers'] = $answers;
        $result['warnings'] = $warnings;

        return $result;
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     * @since Moodle 3.0
     */
    public static function get_lesson_answers_by_pageid_and_lessonid_returns()
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
            ));
    }

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 3.0
     */
    public static function get_lesson_answers_by_id_parameters()
    {
        return new external_function_parameters(
            array(
                'id' => new external_value(PARAM_INT, 'the id')
            )
        );
    }

    /**
     * Get lesson answers by id
     *
     * @param $id
     * @param array $options
     * @return mixed
     * @throws invalid_parameter_exception
     */
    public static function get_lesson_answers_by_id($id)
    {
        global $DB;

        $warnings = array();

        // validate params
        $params = self::validate_parameters(self::get_lesson_answers_by_id_parameters(),
            array(
                'id' => $id
            )
        );

        $result = array();

        $answer = $DB->get_record("lesson_answers", array("id" => $params['id']));

        if (!$answer) {
            $answer = new stdClass();
        }

        $result['answer'] = $answer;
        $result['warnings'] = $warnings;

        return $result;
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     * @since Moodle 3.0
     */
    public static function get_lesson_answers_by_id_returns()
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

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 3.0
     */
    public static function get_lesson_answers_by_lessonid_parameters()
    {
        return new external_function_parameters(
            array('lessonid' => new external_value(PARAM_INT, 'the lesson id')
            )
        );
    }

    /**
     * Get lesson answers by lessonid
     *
     * @param $lessonid
     * @param array $options
     * @return array
     * @throws invalid_parameter_exception
     */
    public static function get_lesson_answers_by_lessonid($lessonid)
    {
        global $DB;

        $warnings = array();

        // validate params
        $params = self::validate_parameters(self::get_lesson_answers_by_lessonid_parameters(),
            array(
                'lessonid' => $lessonid
            )
        );

        $result = array();

        $answers = $DB->get_records_select("lesson_answers", "lessonid = :lessonid", array('lessonid' => $params['lessonid']));

        if (!$answers) {
            $answers = array();
        }

        $result['answers'] = $answers;
        $result['warnings'] = $warnings;

        return $result;
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     * @since Moodle 3.0
     */
    public static function get_lesson_answers_by_lessonid_returns()
    {
        return self::get_lesson_answers_by_pageid_and_lessonid_returns();
    }

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 3.0
     */
    public static function get_lesson_attempts_by_lessonid_and_userid_and_retry_parameters()
    {
        return new external_function_parameters(
            array('lessonid' => new external_value(PARAM_INT, 'the lesson id'),
                'userid' => new external_value(PARAM_INT, 'the user id'),
                'retry' => new external_value(PARAM_INT, 'the retry')
            )
        );
    }

    public static function get_lesson_attempts_by_lessonid_and_userid_and_retry($lessonid, $userid, $retry)
    {
        global $DB;

        $warnings = array();

        // validate params
        $params = self::validate_parameters(self::get_lesson_attempts_by_lessonid_and_userid_and_retry_parameters(),
            array(
                'lessonid' => $lessonid,
                'userid' => $userid,
                'retry' => $retry
            )
        );

        $result = array();
        $attempts = $DB->get_records_select("lesson_attempts", "lessonid = :lessonid AND userid = :userid AND retry = :retry",
            array('lessonid' => $params['lessonid'], 'userid' => $params['userid'], 'retry' => $params['retry']), "timeseen");

        if (!$attempts) {
            $attempts = array();
        }

        $result['attempts'] = $attempts;
        $result['warnings'] = $warnings;

        return $result;
    }

    public static function get_lesson_attempts_by_lessonid_and_userid_and_retry_returns()
    {
        return self::get_lesson_attempts_by_lessonid_and_userid_returns();
    }

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 3.0
     */
    public static function get_lesson_grades_by_lessonid_and_userid_parameters()
    {
        return new external_function_parameters(
            array('lessonid' => new external_value(PARAM_INT, 'the lesson id'),
                'userid' => new external_value(PARAM_INT, 'the user id'),
            )
        );
    }

    /**
     * Get leson grades by lessonid and userid
     *
     * @param $lessonid
     * @param $userid
     * @param array $options
     * @return array
     * @throws invalid_parameter_exception
     */
    public static function get_lesson_grades_by_lessonid_and_userid($lessonid, $userid)
    {
        global $DB;

        $warnings = array();

        // validate params
        $params = self::validate_parameters(self::get_lesson_grades_by_lessonid_and_userid_parameters(),
            array(
                'lessonid' => $lessonid,
                'userid' => $userid
            )
        );

        $result = array();
        $grades = $DB->get_records("lesson_grades", array("lessonid" => $params['lessonid'], "userid" => $params['userid']), "grade DESC");

        if (!$grades) {
            $grades = array();
        }

        $result['grades'] = $grades;
        $result['warnings'] = $warnings;

        return $result;
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     * @since Moodle 3.0
     */
    public static function get_lesson_grades_by_lessonid_and_userid_returns()
    {
        return new external_single_structure(
            array(
                'grades' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'id' => new external_value(PARAM_INT, 'lesson grade id'),
                            'lessonid' => new external_value(PARAM_INT, 'lesson id', VALUE_DEFAULT),
                            'userid' => new external_value(PARAM_INT, 'user id', VALUE_DEFAULT),
                            'grade' => new external_value(PARAM_INT, 'grade', VALUE_DEFAULT),
                            'late' => new external_value(PARAM_INT, 'late', VALUE_DEFAULT),
                            'completed' => new external_value(PARAM_INT, 'completed', VALUE_DEFAULT)
                        )
                    ), 'lesson grades'
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
    public static function save_lesson_branch_parameters()
    {
        return new external_function_parameters (
            array(
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

    /**
     * create new a lesson branch
     *
     * @param $data
     * @return array
     * @throws invalid_parameter_exception
     */
    public static function save_lesson_branch($data)
    {
        global $DB;

        $warnings = array();

        $params = array(
            'data' => $data
        );

        $params = self::validate_parameters(self::save_lesson_branch_parameters(), $params);

        $branch = new stdClass();

        foreach ($params['data'] as $element) {
            $branch->$element['name'] = $element['value'];
        }

        $transaction = $DB->start_delegated_transaction();

        $DB->insert_record("lesson_branch", $branch);

        $transaction->allow_commit();

        $result = array();
        $result['status'] = true;
        $result['warnings'] = $warnings;

        return $result;
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     * @since Moodle 3.0
     */
    public static function save_lesson_branch_returns()
    {
        return new external_single_structure(
            array(
                'status' => new external_value(PARAM_BOOL, 'status: true if success'),
                'warnings' => new external_warnings(),
            )
        );
    }

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 3.0
     */
    public static function get_lesson_pages_by_lessonid_parameters()
    {
        return new external_function_parameters(
            array(
                'lessonid' => new external_value(PARAM_INT, 'the lesson id')
            )
        );
    }

    /**
     * Get list lesson page by lessonid
     *
     * @param $lessonid
     * @param array $options
     * @return array
     * @throws invalid_parameter_exception
     */
    public static function get_lesson_pages_by_lessonid($lessonid)
    {
        global $DB;

        $warnings = array();

        // validate params
        $params = self::validate_parameters(self::get_lesson_pages_by_lessonid_parameters(),
            array(
                'lessonid' => $lessonid
            )
        );

        $result = array();

        $pages = $DB->get_records_select("lesson_pages", "lessonid = :lessonid", array('lessonid' => $params['lessonid']));

        if (!$pages) {
            $pages = array();
        }

        $result['pages'] = $pages;
        $result['warning'] = $warnings;

        return $result;
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     * @since Moodle 3.0
     */
    public static function get_lesson_pages_by_lessonid_returns()
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
    public static function get_lesson_overrides_by_id_parameters()
    {
        return new external_function_parameters(
            array(
                'id' => new external_value(PARAM_INT, 'the id')
            )
        );
    }

    /**
     * Get lesson overrides by id
     *
     * @param $id
     * @param array $options
     * @return mixed
     * @throws invalid_parameter_exception
     */
    public static function get_lesson_overrides_by_id($id)
    {
        global $DB;

        $warnings = arrray();

        // validate params
        $params = self::validate_parameters(self::get_lesson_overrides_by_id_parameters(),
            array(
                'id' => $id
            )
        );

        $result = array();

        $override = $DB->get_record('lesson_overrides', array('id' => $params['id']), '*', MUST_EXIST);

        if (!$override) {
            $override = new stdClass();
        }

        $result['override'] = $override;
        $result['warnings'] = $warnings;

        return $result;
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     * @since Moodle 3.0
     */
    public static function get_lesson_overrides_by_id_returns()
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

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 3.0
     */
    public static function get_lesson_overrides_by_lessonid_and_userid_parameters()
    {
        return new external_function_parameters(
            array('lessonid' => new external_value(PARAM_INT, 'the lesson id'),
                'userid' => new external_value(PARAM_INT, 'the user id')
            )
        );
    }

    /**
     * Get lesson overrides by lessonid and userid
     *
     * @param $lessonid
     * @param $userid
     * @param array $options
     * @return mixed
     * @throws invalid_parameter_exception
     */
    public static function get_lesson_overrides_by_lessonid_and_userid($lessonid, $userid)
    {
        global $DB;

        // validate params
        $params = self::validate_parameters(self::get_lesson_overrides_by_lessonid_and_userid_parameters(),
            array(
                'lessonid' => $lessonid,
                'userid' => $userid
            )
        );

        $warnings = array();

        $override = $DB->get_record('lesson_overrides', array('lessonid' => $params['lessonid'], 'userid' => $params['userid']));

        $result = array();

        if (!$override) {
            $override = new stdClass();
        }

        $result['override'] = $override;
        $result['warnings'] = $warnings;

        return $result;
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     * @since Moodle 3.0
     */
    public static function get_lesson_overrides_by_lessonid_and_userid_returns()
    {
        return new external_single_structure(
            array(
                "override" => new external_single_structure(
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
                        'password' => new external_value(PARAM_RAW, 'password')
                    )
                ),
                "warnings" => new external_warnings()
            )
        );
    }

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 3.0
     */
    public static function get_lesson_overrides_by_lessonid_parameters()
    {
        return new external_function_parameters(
            array('lessonid' => new external_value(PARAM_INT, 'the lesson id')
            )
        );
    }

    /**
     * Get list of lesson overrides by lessonid
     *
     * @param $lessonid
     * @param array $options
     * @return array
     * @throws invalid_parameter_exception
     */
    public static function get_lesson_overrides_by_lessonid($lessonid)
    {
        global $DB;

        $warnings = array();

        // validate params
        $params = self::validate_parameters(self::get_lesson_overrides_by_lessonid_parameters(),
            array(
                'lessonid' => $lessonid,
            )
        );

        $result = array();

        $overrides = $DB->get_records('lesson_overrides', array('lessonid' => $params['lessonid']), 'id');

        if (!$overrides) {
            $overrides = array();
        }

        $result['overrides'] = $overrides;
        $result['warnings'] = $warnings;

        return $result;
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     * @since Moodle 3.0
     */
    public static function get_lesson_overrides_by_lessonid_returns()
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

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 3.0
     */
    public static function delete_lesson_object_parameters()
    {
        return new external_function_parameters(
            array(
                'tablename' => new external_value(PARAM_TEXT, ' the table name'),
                'columnname' => new external_value(PARAM_TEXT, ' the column name'),
                'value' => new external_value(PARAM_RAW, ' the value'),
            )
        );
    }

    /**
     * Delete lesson object
     *
     * @param $tablename
     * @param $columnname
     * @param $value
     * @throws invalid_parameter_exception
     */
    public static function delete_lesson_object($tablename, $columnname, $value)
    {
        global $DB;

        $params = array(
            'tablename' => $tablename,
            'columnname' => $columnname,
            'value' => $value
        );

        $params = self::validate_parameters(self::delete_lesson_object_parameters(), $params);

        $transaction = $DB->start_delegated_transaction();

        $DB->delete_records($params['tablename'], array($params['columnname'] => $params['value']));

        $transaction->allow_commit();
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     * @since Moodle 3.0
     */
    public static function delete_lesson_object_returns()
    {
        return null;
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

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 3.0
     */
    public static function save_lesson_pages_parameters()
    {
        return new external_function_parameters (
            array(
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

    /**
     * create new a lesson pages
     *
     * @param $data
     * @return array
     * @throws invalid_parameter_exception
     */
    public static function save_lesson_pages($data)
    {
        global $DB;

        $warnings = array();

        $params = array(
            'data' => $data
        );

        $params = self::validate_parameters(self::save_lesson_pages_parameters(), $params);

        $newpage = new stdClass();

        foreach ($params['data'] as $element) {
            $newpage->$element['name'] = $element['value'];
        }

        $result = array();

        $transaction = $DB->start_delegated_transaction();

        $newpageid = $DB->insert_record("lesson_pages", $newpage);

        $transaction->allow_commit();

        $result['newpageid'] = $newpageid;
        $result['warnings'] = $warnings;

        return $result;
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     * @since Moodle 3.0
     */
    public static function save_lesson_pages_returns()
    {
        return new external_single_structure(
            array(
                'newpageid' => new external_value(PARAM_INT, 'newpageid'),
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
    public static function save_lesson_attempts_parameters()
    {
        return new external_function_parameters (
            array(
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

    /**
     * create a new lesson attempts
     *
     * @param $data
     * @return bool|int
     * @throws invalid_parameter_exception
     */
    public static function save_lesson_attempts($data)
    {
        global $DB;

        $params = array(
            'data' => $data
        );

        $params = self::validate_parameters(self::save_lesson_attempts_parameters(), $params);

        $attempt = new stdClass();

        foreach ($params['data'] as $element) {
            $attempt->$element['name'] = $element['value'];
        }

        $transaction = $DB->start_delegated_transaction();

        $newattemptid = $DB->insert_record("lesson_attempts", $attempt);

        $transaction->allow_commit();

        return $newattemptid;
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     * @since Moodle 3.0
     */
    public static function save_lesson_attempts_returns()
    {
        return new external_value(PARAM_INT, 'attemptid');
    }

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 3.0
     */
    public static function save_lesson_answers_parameters()
    {
        return new external_function_parameters (
            array(
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

    /**
     * create a new lesson attempts
     *
     * @param $data
     * @return bool|int
     * @throws invalid_parameter_exception
     */
    public static function save_lesson_answers($data)
    {
        global $DB;

        $warnings = array();

        $params = array(
            'data' => $data
        );

        $params = self::validate_parameters(self::save_lesson_answers_parameters(), $params);

        $answer = new stdClass();

        foreach ($params['data'] as $key => $value) {
            $answer->$key = $value;
        }

        $transaction = $DB->start_delegated_transaction();

        $result = array();

        $DB->insert_record("lesson_answers", $answer);
        $transaction->allow_commit();

        $result['status'] = true;
        $result['warnings'] = $warnings;

        return $result;
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     * @since Moodle 3.0
     */
    public static function save_lesson_answers_returns()
    {
        return self::save_lesson_branch_returns();
    }

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 3.0
     */
    public static function save_lesson_timer_parameters()
    {
        return new external_function_parameters (
            array(
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

    /**
     * create a new lesson attempts
     *
     * @param $data
     * @return bool|int
     * @throws invalid_parameter_exception
     */
    public static function save_lesson_timer($data)
    {
        global $DB;

        $warnings = array();

        $params = array(
            'data' => $data
        );

        $params = self::validate_parameters(self::save_lesson_timer_parameters(), $params);

        $timer = new stdClass();

        foreach ($params['data'] as $element) {
            $timer->$element['name'] = $element['value'];
        }

        $transaction = $DB->start_delegated_transaction();

        $result = array();

        $DB->insert_record("lesson_timer", $timer);
        $transaction->allow_commit();

        $result['status'] = true;
        $result['warnings'] = $warnings;

        return $result;
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     * @since Moodle 3.0
     */
    public static function save_lesson_timer_returns()
    {
        return self::save_lesson_branch_returns();
    }

    public static function update_lesson_timer_parameters()
    {
        return new external_function_parameters (
            array(
                'id' => new external_value(PARAM_INT, 'the id'),
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

    public static function update_lesson_timer($id, $data)
    {
        global $DB;

        $warnings = array();

        $params = array(
            'id' => $id,
            'data' => $data
        );

        $params = self::validate_parameters(self::update_lesson_timer_parameters(), $params);

        $timer = $DB->get_record('lesson_timer', array('id' => $params['id']), '*', MUST_EXIST);

        var_dump($timer);

        $result = array();

        if (!$timer) {
            $result['status'] = false;
            $warnings['message'] = 'have no data record';
            return $result;
        }

        foreach ($params['data'] as $element) {
            $timer->$element['name'] = $element['value'];
        }

        $transaction = $DB->start_delegated_transaction();

        $DB->update_record('lesson_timer', $timer);
        $transaction->allow_commit();

        $result['status'] = true;
        $result['warnings'] = $warnings;

        return $result;
    }

    public static function update_lesson_timer_returns()
    {
        return self::save_lesson_timer_returns();
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

    public static function get_count_lesson_attempts_parameters()
    {
        return new external_function_parameters(
            array(
                'lessonid' => new external_value(PARAM_INT, 'the lesson id'),
                'userid' => new external_value(PARAM_INT, 'the user id'),
                'pageid' => new external_value(PARAM_INT, 'the page id'),
                'retry' => new external_value(PARAM_INT, 'retry'),
                'correct' => new external_value(PARAM_INT, 'correct')
            )
        );
    }

    public static function get_count_lesson_attempts($lessonid, $userid, $pageid, $retry, $correct)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::get_count_lesson_attempts_parameters(), array(
            'lessonid' => $lessonid,
            'userid' => $userid,
            'pageid' => $pageid,
            'retry' => $retry,
            'correct' => $correct
        ));

        $parameters = array(
            'userid' => $params['userid'],
            'retry' => $params['retry']
        );

        if ($params['lessonid'] > 0) {
            $parameters = array_merge($parameters, array('lessonid' => $params['lessonid']));
        }
        if ($params['pageid'] > 0) {
            $parameters = array_merge($parameters, array('pageid' => $params['pageid']));
        }
        if ($params['correct'] > 0) {
            $parameters = array_merge($parameters, array('correct' => $params['correct']));
        }


        $result = array();

        $nattempts = $DB->count_records('lesson_attempts', $parameters);

        $result['nattempts'] = $nattempts;
        $result['warnings'] = $warnings;

        return $result;
    }

    public static function get_count_lesson_attempts_returns()
    {
        return new external_single_structure(
            array(
                'nattempts' => new external_value(PARAM_INT, 'count'),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function get_list_lesson_pages_by_id_and_lessonid_parameters()
    {
        return new external_function_parameters(
            array(
                'id' => new external_value(PARAM_INT, 'the id'),
                'lessonid' => new external_value(PARAM_INT, 'the lesson id')
            )
        );
    }

    public static function get_list_lesson_pages_by_id_and_lessonid($id, $lessonid)
    {
        global $DB;

        $warnings = array();

        $params = self::validate_parameters(self::get_list_lesson_pages_by_id_and_lessonid_parameters(), array(
            'id' => $id,
            'lessonid' => $lessonid
        ));

        $result = array();

        $parameters = array(
            $params['lessonid'],
            $params['id']
        );

        $pages = $DB->get_records_select("lesson_pages", "lessonid = ? AND id = ?", $parameters);

        if(!$pages) {
            $pages = array();
        }

        $result['pages'] = $pages;
        $result['warnings'] = $warnings;

        return $result;
    }

    public static function get_list_lesson_pages_by_id_and_lessonid_returns()
    {
       return self::get_lesson_pages_by_lessonid_returns();
    }

    public static function set_field_lesson_pages_parameters() {
        return new external_function_parameters(
            array(
                'id' => new external_value(PARAM_INT, 'the id'),
                'newfield' => new external_value(PARAM_RAW, 'the new field'),
                'newvalue' => new external_value(PARAM_RAW, 'the new value')
            )
        );
    }

    public static function set_field_lesson_pages($id, $newfield, $newvalue) {
        global $DB;

        $warnings = array();

        $params = self::validate_parameters(self::set_field_lesson_pages(), array(
            'id' => $id,
            'newfield' => $newfield,
            'newvalue' => $newvalue
        ));

        $result = array();

        $transaction = $DB->start_delegated_transaction();
        $result['status'] = false;
        $DB->set_field("lesson_pages", $params['newfield'], $params['newvalue'], array("id" => $params['id']));
        $transaction->allow_commit();

        $result['status'] = true;
        $result['warnings'] = $warnings;
    }

    public static function set_field_lesson_pages_returns() {
        return new external_single_structure(
            array(
                'status' => new external_value(PARAM_BOOL, 'status'),
                'warnings' => new external_warnings()
            )
        );
    }
}
