<?php
defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot . '/lib/remote/lib.php');
require_once($CFG->dirroot . '/lib/additionallib.php');
require_once($CFG->dirroot . '/mnet/service/enrol/locallib.php');

function get_remote_enrol_course_by_host()
{
    global $DB;
    $service = mnetservice_enrol::get_instance();

    if (!$service->is_available()) {
        print_error('mnetserviceisnotenabled', 'mnetservice_enrol');
        return null;
    }

    // remote hosts that may publish remote enrolment service and we are subscribed to it
    $hosts = $service->get_remote_publishers();
    $host = new StdClass();
    foreach ($hosts as $h) {
        $host = $h;
        break;
    }

    $courseids = $DB->get_records('course', array('hostid' => $host->id), '', 'remoteid');
    $opids = array();
    $i = 0;
    foreach ($courseids as $key => $val) {
        $opids['options[ids][' . $i++ . ']'] = $key;
    }
    $options = array('params' => $opids);

    return get_remote_courses($options);
}

function get_remote_courses($options = array())
{
    return moodle_webservice_client(array_merge($options, array('domain' => HUB_URL,
        'token' => HOST_TOKEN_M,
        'function_name' => 'core_course_get_courses'
    )));
}

function get_remote_course_content($courseid, $options = array())
{
    $coursedetail = moodle_webservice_client(array_merge($options, array('domain' => HUB_URL,
        'token' => HOST_TOKEN,
        'function_name' => 'local_get_course_content_by_id',
        'params' => array('courseid' => $courseid),
    )));

    return array('courseid' => $courseid, 'content' => $coursedetail);
}

function get_remote_course_category($ccatid, $options = array())
{
    return moodle_webservice_client(array_merge($options, array('domain' => HUB_URL,
        'token' => HOST_TOKEN,
        'function_name' => 'core_course_get_categories',
        'params' => array('criteria[0][key]' => 'id', 'criteria[0][value]' => $ccatid),
    )));
}

function get_remote_label_content($labelid, $options = [])
{
    return moodle_webservice_client(array_merge($options, array('domain' => HUB_URL,
        'token' => HOST_TOKEN,
        'function_name' => 'local_mod_get_label_by_id',
        'params' => array('labelid' => $labelid),
    )));
}

function get_remote_page_content($pageid, $options = [])
{
    return moodle_webservice_client(array_merge($options, array('domain' => HUB_URL,
        'token' => HOST_TOKEN,
        'function_name' => 'local_mod_get_page_by_id',
        'params' => array('pageid' => $pageid),
    )));
}

function get_remote_quiz_content($quizid, $options = [])
{
    return moodle_webservice_client(array_merge($options, array('domain' => HUB_URL,
        'token' => HOST_TOKEN,
        'function_name' => 'local_mod_get_quiz_by_id',
        'params' => array('quizid' => $quizid),
    )));
}

function get_remote_url_content($urlid, $options = [])
{
    return moodle_webservice_client(array_merge($options,
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_url_by_id',
            'params' => array('urlid' => $urlid),
        )
    ));
}

function get_remote_course_module_by_instance($modulename, $instace, $options = array())
{
    $resp = moodle_webservice_client(array_merge($options,
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN_M,
            'function_name' => 'core_course_get_course_module_by_instance',
            'params' => array('module' => $modulename, 'instance' => $instace)
        )
    ));
    if (isset($resp->exception) && $resp->exception) {
        $result = new stdClass();
    } else {
        $result = merge_local_course_module($resp->cm);
    }

    return $result;
}

function get_remote_course_module_by_cmid($modulename = '', $cmid, $courseid = 0, $validate = true, $iscache = true)
{
    $resp = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_get_course_module_by_cmid',
            'params' => array('modulename' => $modulename, 'cmid' => $cmid, 'courseid' => $courseid, 'validate' => $validate)
        ), $iscache
    );
    $result = merge_local_course_module($resp->cm);
    return $result;
}

function get_remote_core_course_get_course_module($cmid)
{
    $resp = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN_M,
            'function_name' => 'core_course_get_course_module',
            'params' => array('cmid' => $cmid)
        )
    );

    $result = merge_local_course_module($resp->cm);
    return $result;
}

function get_remote_course_info($courseid, $options = array())
{
    $courseinfo = moodle_webservice_client(array_merge($options,
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_get_course_info_by_course_id',
            'params' => array('courseid' => $courseid),
        )
    ));

    return $courseinfo;
}

function get_remote_name_modules_by_id($id)
{
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_get_name_modules_by_id',
            'params' => array('id' => $id),
        )
    );
}

function get_remote_modules_by_id($id)
{
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_get_modules_by_id',
            'params' => array('id' => $id),
        )
    );
}

function get_remote_course_section_nav_by_section($sectionid)
{
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_get_remote_course_section_nav',
            'params' => array('sectionid' => $sectionid),
        )
    );
}

function get_remote_course_format_options($courseid, $format, $sectionid, $assockey = false)
{
    global $DB;
    $remotecourseid = $DB->get_field('course', 'remoteid', array('id' => $courseid), MUST_EXIST);
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_get_remote_course_format_options',
            'params' => array('courseid' => $remotecourseid, 'format' => $format, 'sectionid' => $sectionid),
        )
    );

    if ($assockey) {
        $result = change_key_by_value($result, $assockey);
    }

    return $result;
}

function get_remote_modules_by($parameters, $sort = '', $mustexists = FALSE)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_get_remote_modules_by',
            'params' => array_merge(array('sort' => $sort, 'mustexists' => $mustexists), $parameters),
        ), false
    );

    return $result->module;
}


/**
 * Delete remote course module completion by cmid and host ip
 *
 * @param int $cmid - The id of course module
 * @return bool $result    - Return true if success
 */
function delete_remote_course_modules_completion($cmid)
{
    $hostip = gethostip();

    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_delete_remote_course_modules_completion_by_cmid_hostip',
            'params' => array(
                'coursemoduleid' => $cmid,
                'hostip' => $hostip
            ),
        ), false
    );

    return $result;
}

/**
 * Convert email user to id user.
 *
 * @param $email - The email of user
 * @return int          - The id of user
 */
function change_email_to_userid($email)
{
    global $DB;

    $userid = $DB->get_record('user', array('email' => $email))->id;

    return $userid;
}

/**
 * Update & create table "course_modules_completion"  on hub
 *
 * @param array $cmc - The information course modules completion to insert $DB
 *        int   $cmc['id']                - The id of course module completion
 *        int   $cmc['coursemoduleid']    - The id of course module
 *        int   $cmc['userid']            - The id of user
 *        int   $cmc['completionstate']   - The completion state
 *        int   $cmc['viewed']            - The view state
 *        int   $cmc['timemodified']      - The time modified
 * @return mixed
 */
function create_update_remote_course_modules_completion($cmc)
{
    if (isset($cmc['id'])) {
        unset($cmc['id']);
    }

    $cmc['userid'] = get_remote_mapping_user($cmc['userid'])[0]->id;

    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_create_update_remote_course_modules_completion',
            'params' => $cmc,
        ), false
    );

    return $result;
}

/**
 * Get course completion on hub
 * @param int $courseid - The id of course
 * @param int $userid - The id of user
 * @return mixed $result  - The information of course completion
 */
function get_remote_course_completion_progress($course, $userid)
{
    global $DB;
    $completion = new completion_info($course);
    $activities = $completion->get_activities();
    $totalmoduletracking = count($activities);

    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_get_course_completion_progress',
            'params' => array('courseid' => $course->remoteid, 'userid' => $userid, 'totalmoduletracking' => $totalmoduletracking),
        ), false
    );

    if (isset($result->completion) && $result->completion) {
        return $result->completion;
    } else {
        return 0;
    }

}

function get_remote_list_course_completion($userid)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_get_list_course_completion',
            'params' => array('userid' => $userid),
        ), false
    );

    return $result->completions;
}

/**
 * Detele tbl course_completions by courseid & userid on host
 *
 * @param int $courseid - The id of course
 * @return bool $result    - True if success
 */
function delete_remote_course_completions($courseid)
{
    $hostip = gethostip();
    $rcourseid = get_local_course_record($courseid, true)->remoteid;

    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_detele_course_completions_by_courseid_hostip',
            'params' => array(
                'courseid' => $rcourseid,
                'hostip' => $hostip),
        ), false
    );

    return $result;
}

/**
 * Detele tbl course_completion_crit_compl by courseid & userid on host
 *
 * @param int $courseid - The id of course
 * @return bool $result    - True if success
 */
function delete_remote_course_completion_crit_compl($courseid)
{
    $hostip = gethostip();
    $rcourseid = get_local_course_record($courseid, true)->remoteid;

    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_delete_course_completion_crit_compl_by_courseid_hostip',
            'params' => array(
                'courseid' => $rcourseid,
                'hostip' => $hostip),
        ), false
    );

    return $result;
}

/**
 * Determines how much completion data exists for an activity. This is used when
 * deciding whether completion information should be 'locked' in the module
 * editing form.
 *
 * @param int $coursemoduleid - The id of course module
 * @return int $result           - Count user id
 */
function count_remote_user_data_completion($coursemoduleid)
{
    $hostip = gethostip();

    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_get_course_completion_count_user_data',
            'params' => array(
                'coursemoduleid' => $coursemoduleid,
                'hostip' => $hostip),
        ), false
    );

    return $result;
}

/**
 * Factory method - uses the parameters to retrieve all matching instances from the DB.
 *
 * @final
 * @param string $table The table name to fetch from
 * @param int $courseid The id of course
 * @return mixed array of object instances or false if not found
 */
function get_remote_completion_fetch_all_helper($table, $courseid)
{
    $rcourseid = get_local_course_record($courseid, true)->remoteid;


    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_get_remote_completion_fetch_all_helper',
            'params' => array(
                'table' => $table,
                'course' => $rcourseid,
            ),
        ), false
    );

    if ($table == 'course_completion_aggr_methd') {
        if ($result->course_completion_aggr_methd) {
            foreach ($result->course_completion_aggr_methd as $element) {
                $element->course = $courseid;
            }
            return $result->course_completion_aggr_methd;
        } else {
            return false;
        }
    } else if ($table == 'course_completion_criteria') {
        if ($result->course_completion_criteria) {
            foreach ($result->course_completion_criteria as $element) {
                $element->course = $courseid;
            }
            return $result->course_completion_criteria;
        } else {
            return false;
        }
    } else {
        return false;
    }
}

/**
 * Get all information about modules
 *
 * @params string $fields  - The fields of table modules to get
 *
 * @return array $return   - The information tbl modules
 */
function get_remote_modules($fields = '*')
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_get_tbl_modules',
            'params' => array(
                'fields' => $fields
            )
        )
    );

    $result = change_key_by_value($result);

    return $result;
}

/**
 * Update course completion on hub with data
 *
 * @param array $data - Data for update tbl course_completions
 * @return bool $result - Return true if success
 */
function update_remote_course_completions($data)
{
    $data['userid'] = get_remote_mapping_user($data['userid'])[0]->id;
    $data['course'] = get_local_course_record($data['course'], true)->remoteid;

    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_update_course_completions',
            'params' => $data
        ), false
    );

    return $result;
}

function save_remote_mdl_course($modname, $data)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_save_mdl_course',
            'params' => array_merge(array('modname' => $modname), $data),
        )
    );

    return $result->newid;
}

function update_remote_mdl_course($modname, $id, $data)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_update_mdl_course',
            'params' => array_merge(array('modname' => $modname, "id" => $id), $data),
        )
    );

    return $result->id;
}

function get_remote_course_sections_by($parameters, $strictness = IGNORE_MISSING)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_get_course_sections_by',
            'params' => array_merge(array('strictness' => $strictness), $parameters),
        )
    );

    return $result->section;
}

function get_remote_course_modules_by($parameters, $strictness = MUST_EXIST)
{
    global $DB;

    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_get_course_modules_by',
            'params' => array_merge(array('strictness' => $strictness), $parameters),
        )
    );

    $cm = $result->cm;

    if ($cm) {
        $localcourseid = $DB->get_field('course', 'id', array('remoteid' => $cm->course));
        if ($localcourseid) {
            $cm->course = $localcourseid;
        }
    }

    return $cm;
}

function get_remote_course_modules_by_instance($module, $instance)
{
    global $DB;

    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_get_course_modules_by_instance',
            'params' => array('module' => $module, 'instance' => $instance),
        )
    );

    $cm = $result->cm;

    if ($cm) {
        $localcourseid = $DB->get_field('course', 'id', array('remoteid' => $cm->course));
        if ($localcourseid) {
            $cm->course = $localcourseid;
        }
    }

    return $cm;
}

function get_remote_list_course_module_competencies_in_course_module($cmorid)
{
    global $DB;

    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_get_list_course_module_competencies_in_course_module',
            'params' => array('cmorid' => $cmorid),
        ), false
    );

    $cm = $result->cm;

    if ($cm) {
        $course = $DB->get_record('course', array('remoteid' => $cm->course));
        if ($course) {
            $cm->course = $course->id;
        }
    }

    return $cm;
}

function get_remote_can_add_moduleinfo($courseid, $modulename, $section)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_can_add_moduleinfo',
            'params' => array('courseid' => $courseid, 'modulename' => $modulename, 'section' => $section),
        ), false
    );


    return $result->module;
}
