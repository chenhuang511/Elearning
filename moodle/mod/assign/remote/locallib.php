<?php
defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot . '/lib/remote/lib.php');


/**
 * get lesson by id
 *
 * @param int $lessonid . the id of lesson
 * @param array $options . the options
 *
 * @return stdClass $lesson
 */
function get_remote_assign_by_id($assignid, $options = array())
{
    return moodle_webservice_client(array_merge($options,
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_assign_get_assign_by_id',
            'params' => array('assignid' => $assignid),
        )
    ));
}

function get_remote_assign_submission_status($assignid) {
    global $CFG, $USER;

    require_once($CFG->dirroot . '/mnet/lib.php');
    $hostname = mnet_get_hostname_from_uri($CFG->wwwroot);
    // Get the IP address for that host - if this fails, it will return the hostname string
    $hostip = gethostbyname($hostname);

    return moodle_webservice_client(array(
        'domain' => HUB_URL,
        'token' => HOST_TOKEN,
        'function_name' => 'local_mod_assign_get_submission_status',
        'params' => array('assignid' => $assignid, "ip_address" => $hostip, "username" => $USER->username),
    ));
}

function get_remote_submissions_by_assign_id($assignmentids, $options = array())
{
    return moodle_webservice_client(array_merge($options,
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN_M,
            'function_name' => 'mod_assign_get_submissions',
            'params' => array('assignmentids' => $assignmentids),
        )
    ));
}

function get_remote_submissions_by_assign_ids_and_ip($assignmentids, $ip, $options = array()) {
    return moodle_webservice_client(array_merge($options,
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_assign_get_submissions_by_host_ip',
            'params' => array('assignmentids' => $assignmentids, 'ip' => $ip),
        )
    ));
}

function get_list_user_id_from_submissions($submissions = array()) {
    $usersid = array();
    foreach ($submissions as $submission) {
        $usersid[] = $submission->userid;
    }
    return $usersid;
}

//hanv: 16/06/2016
function get_remote_get_submission_status($assignid, $userid = null)
{   
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_assign_get_remote_submission_status',
            'params' => array('assignid' => $assignid,'userid' => $userid),
        ), false
    );
}

function get_remote_enrolled_users($courseid, $options = array()) {
    return moodle_webservice_client(array_merge($options,
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN_M,
            'function_name' => 'core_enrol_get_enrolled_users',
            'params' => array('courseid' => $courseid),
        )
    ));
}

function get_remote_enrolled_users_by_ip($courseid, $ip, $options = array()) {
    $resp = moodle_webservice_client(array_merge($options,
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_enrol_get_enrolled_users_by_hostip',
            'params' => array('courseid' => $courseid, 'ip' => $ip),
        )
    ));
    return $resp;
}
// Minhnd
function get_remote_onlinetext_submission($submissionid, $options = array()) {
    return moodle_webservice_client(array_merge($options,
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_assign_get_onlinetext_submission',
            'params' => array('submissionid' => $submissionid),
        )
    ), false);
}

// Minhnd
function get_remote_assignfeedback_comments($gradeid) {
    $resp = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_assign_get_assignfeedback_comments',
            'params' => array('gradeid' => $gradeid),
        ), false
    );
    
    return $resp->feedbackcomments;
}

function get_remote_assign_plugin_config($dbparams){
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_assign_get_plugin_config',
            'params' => $dbparams
        ), false
    );
}

function get_remote_assign_comment_status($params)
{
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_assign_get_comment_status',
            'params' => $params
        ), false
    );
}

function get_remote_assign_count_file_submission($params){
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_assign_get_count_file_submission',
            'params' => $params
        ), false
    );
}

function get_remote_count_submissions_with_status_by_host_id($params){
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_assign_count_submissions_with_status_by_host_id',
            'params' => $params
        ), false
    );
}

function get_remote_count_submissions_need_grading_by_host_id($params){
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_assign_count_submissions_need_grading_by_host_id',
            'params' => $params
        ), false
    );
}

function get_submission_by_assignid_userid_groupid($params){
    
    if(!isset($params['attemptnumber']))
        $params['attemptnumber'] = 0;
    
    $results = array();
    
    $submissions =  moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_assign_get_submission_by_assignid_userid_groupid',
            'params' => $params
        ), false
    );

    foreach ($submissions->submissions as $submission){
        $results[$submission->id] = $submission;
    }
    return $results;
}

function get_assign_grades_by_assignid_userid($params){
    
    if(!isset($params['attemptnumber']))
        $params['attemptnumber'] = 0;

    $results = array();

    $grades =  moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_assign_get_grades_by_assignid_userid',
            'params' => $params
        ), false
    );

    foreach ($grades->grades as $grade){
        $results[$grade->id] = $grade;
    }
    return $results;
}

function get_attemptnumber_by_assignid_userid_groupid($params){
    
    $attemptnumbers =  moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_assign_get_attemptnumber_by_assignid_userid_groupid',
            'params' => $params
        ), false
    );

    return $attemptnumbers->result;
}

function get_user_flags_by_assignid_userid($params){
    $flags =  moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_assign_get_user_flags_by_assignid_userid',
            'params' => $params
        ), false
    );

    if($flags->exception)
        return 0;

    return $flags->userflags;
}

function set_submission_lastest($params){

    $resp = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_assign_set_submission_lastest',
            'params' => $params
        ), false
    );

    return $resp->result;
}

function create_remote_submission($submission){

    $resp = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_assign_create_submission',
            'params' => array(
                'assignment' => $submission->assignment,
                'useremail' => $submission->useremail,
                'timecreated' => $submission->timecreated,
                'timemodified' => $submission->timemodified,
                'status' => $submission->status,
                'attemptnumber' => $submission->attemptnumber,
                'latest' => $submission->latest,
            )
        ), false
    );

    return $resp->sid;
}

function create_remote_grade($grade){
    if(!isset($grade->attemptnumber))
        $grade->attemptnumber = 0;

    $resp = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_assign_create_grade',
            'params' => array(
                'assignment' => $grade->assignment,
                'useremail' => $grade->useremail,
                'timecreated' => $grade->timecreated,
                'timemodified' => $grade->timemodified,
                'grader' => $grade->grader,
                'grade' => $grade->grade,
                'attemptnumber' => $grade->attemptnumber,
            )
        ), false
    );

    return $resp->gid;
}

function get_remote_submission_by_id($sid){

    $resp = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_assign_get_submission_by_id',
            'params' => array('id' => $sid)
        ), false
    );

    return $resp->assignsubmisison;
}

function save_remote_submission($assignmentid, $userid, $data){

    if ($data->onlinetext_editor){
        $onlinetext_editor = $data->onlinetext_editor;
        $resp = moodle_webservice_client(
            array(
                'domain' => HUB_URL,
                'token' => HOST_TOKEN,
                'function_name' => 'local_mod_assign_save_remote_submission',
                'params' => array(
                    'assignmentid'  =>  $assignmentid,
                    'userid'        =>  $userid,
                    'plugindata[onlinetext_editor][text]' => $onlinetext_editor[text],
                    'plugindata[onlinetext_editor][format]' => (int)$onlinetext_editor[format],
                    'plugindata[onlinetext_editor][itemid]' => $onlinetext_editor[itemid]
                )
            ), false
        );
    }
    if ($data->files_filemanager){
        $resp = moodle_webservice_client(
            array(
                'domain' => HUB_URL,
                'token' => HOST_TOKEN,
                'function_name' => 'local_mod_assign_save_remote_submission',
                'params' => array(
                    'assignmentid'  =>  $assignmentid,
                    'userid'        =>  $userid,
                    'plugindata[files_filemanager]' => $data->files_filemanager,
                    'plugindata[id]' => $data->id,
                    'plugindata[userid]' => $data->userid,
                    'plugindata[files]' => $data->files
                )
            ), false
        );
    }

    if (empty($resp))
        return true;
    return false;
}

function create_fakefile_on_hub($fakefile){
    $rparams = array(
        'contenthash'       =>  $fakefile->contenthash,
        'pathnamehash'      =>  $fakefile->pathnamehash,
        'instanceid'        =>  $fakefile->instanceid,
        'component'         =>  $fakefile->component,
        'filearea'          =>  $fakefile->filearea,
        'itemid'            =>  $fakefile->itemid,
        'filepath'          =>  $fakefile->filepath,
        'filename'          =>  $fakefile->filename,
        'userid'            =>  $fakefile->userid,
        'filesize'          =>  $fakefile->filesize,
        'mimetype'          =>  $fakefile->mimetype,
        'author'            =>  $fakefile->author,
        'license'           =>  $fakefile->license,
        'timecreated'       =>  $fakefile->timecreated,
        'timemodified'      =>  $fakefile->timemodified,
    );

    $resp = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_assign_create_fakefile_on_hub',
            'params' => $rparams,
        ), false
    );

    return $resp;
}

function delete_fakefile_on_hub($rparams){
    $resp = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_assign_delete_fakefile_on_hub',
            'params' => $rparams,
        ), false
    );

    return $resp;
}

