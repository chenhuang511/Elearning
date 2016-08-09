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
    $result = merge_local_course_module($resp->cm);
    return $result;
}

function get_remote_course_module_by_cmid($modulename, $cmid, $options = array())
{
    $resp = moodle_webservice_client(array_merge($options,
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_get_course_module_by_cmid',
            'params' => array('module' => $modulename, 'id' => $cmid)
        )
    ));
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
 * @param int $cmid        - The id of course module
 * @return bool $result    - Return true if success
 */
function delete_remote_course_modules_completion($cmid) {
    $hostip = gethostip();
    $rcmid = get_local_course_modules_record($cmid, true)->remoteid;

    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_delete_remote_course_modules_completion_by_cmid_hostip',
            'params' => array(
                'coursemoduleid' => $rcmid,
                'hostip' => $hostip
            ),
        ), false
    );

    return $result;
}

/**
 * Convert email user to id user.
 *
 * @param $email        - The email of user
 * @return int          - The id of user
 */
function change_email_to_userid($email){
    global $DB;

    $userid = $DB->get_record('user', array('email'=>$email))->id;

    return $userid;
}

/**
 * Get course module completion by mode and course module id
 *
 * @param int $cmid        - The id of course module
 * @param string $mode     - The mode to get data. 3 mode: normal, singlerc, wholecourse
 * @param string $field    - The field to get data.
 * @param int $userid      - The id of user
 * @return mixed $result   - The information of course module completion
 */
function get_remote_course_modules_completion_by_mode($cmid, $mode = 'normal', $field = '*', $userid = -1) {
    $hostip = gethostip();
    $rcm = get_local_course_modules_record($cmid);

    if ($userid != -1) {
        $ruser = get_remote_mapping_user($userid)[0]->id;
    }

    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_get_remote_course_modules_completion',
            'params' => array(
                'coursemoduleid' => $rcm->remoteid,
                'courseid' => $rcm->course,
                'hostip' => $hostip,
                'field' => $field,
                'mode' => $mode,
                'userid' => $ruser
            ),
        ), false
    );

    if (isset($result->cmc) && !empty($result->cmc)){
        foreach ($result->cmc as $cmc){
            if (!isset($cmc->email)){
                continue;
            }
            $cmc->userid = change_email_to_userid($cmc->email);
            unset($cmc->email);
        }
        return $result->cmc;
    } else if (isset($result->scmc) && !empty($result->scmc)) {
        if (isset($result->scmc->email)) {
            $result->scmc->userid = change_email_to_userid($result->scmc->email);
            unset($result->scmc->email);
        }
        return $result->scmc;
    } else {
        return 0;
    }
}

/**
 * Insert table "course_modules_completion"  on hub
 *
 * @param array $cmc    - The information course modules completion to insert $DB
 *        int   $cmc['coursemoduleid']    - The id of course module
 *        int   $cmc['userid']            - The id of user
 *        int   $cmc['completionstate']   - The completion state
 *        int   $cmc['viewed']            - The view state
 *        int   $cmc['timemodified']      - The time modified
 * @return mixed
 */
function create_remote_course_modules_completion($cmc){
    if (isset($cmc->id)){
        unset($cmc->id);
    }
    //Convert userid on hub
    $userid = $cmc->userid;
    $cmc->userid = get_remote_mapping_user($userid)[0]->id;

    $cmc = (array)$cmc;

    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_create_remote_course_modules_completion',
            'params' => $cmc,
        ), false
    );

    return $result;

}
/**
 * Update table "course_modules_completion"  on hub
 *
 * @param array $cmc    - The information course modules completion to insert $DB
 *        int   $cmc['id']                - The id of course module completion
 *        int   $cmc['coursemoduleid']    - The id of course module
 *        int   $cmc['userid']            - The id of user
 *        int   $cmc['completionstate']   - The completion state
 *        int   $cmc['viewed']            - The view state
 *        int   $cmc['timemodified']      - The time modified
 * @return mixed
 */
function update_remote_course_modules_completion($cmc){
    //Convert userid on hub
    $userid = $cmc->userid;
    $cmc->userid = get_remote_mapping_user($userid)[0]->id;

    $cmc = (array)$cmc;

    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_update_remote_course_modules_completion',
            'params' => $cmc,
        ), false
    );

    return $result;

}
