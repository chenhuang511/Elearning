<?php
defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot . '/lib/remote/lib.php');


/**
 * get assign by id
 *
 * @param int $assignid . the id of assign
 * @param array $options . the options
 *
 * @return stdClass $asssign
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

/**
 * get assign submission status
 *
 * @param int $assignid - the id of assign
 * @param int $userid - the id of user - default is null
 *
 * @return stdClass $submissionstatus
 */
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

/**
 * get assign onlinetext submission on hub
 *
 * @param int $submissionid . the id of submission
 * @param array $options . the options
 *
 * @return stdClass $onlinetextsubmission
 */
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

/**
 * get assign assignment feedback comment on hub
 *
 * @param int $gradeid . the id of grade
 *
 * @return stdClass feedbackcomments
 */
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

/**
 * get value of assign plugin config
 *
 * @param int $dbparams['assignment'] . the assignment id
 * @param string $dbparams['plugin'] . plugin name
 * @param string $dbparams['subtype'] . subtype
 * @param string $dbparams['name'] . name
 *
 * @return stdClass $value
 */
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

/**
 * Get assign comment status include: countcomment, getcomment
 *
 * @param int $params['itemid'] . the item id
 * @param string $params['commentarea'] . the comment area
 * @param int $params['contextid'] . the context id
 * @param int $params['instanceid'] . the instance id
 * @param int $params['courseid'] . the course id
 *
 * @return stdClass object include: countcomment, getcomment, warnning
 */
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

/**
 * Count submission with status by host id.
 *
 * @param int $params['assignid'] . the assign id
 * @param string $params['hostip'] . the hostip, eg: 192.168.1.88 ...
 * @param string $params['status'] . the status of submission
 *
 * @return int value . count submission with status
 */
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

/**
 * Count submission need grading by host id.
 *
 * @param int $params['assignid'] . the assign id
 * @param string $params['hostip'] . the hostip, eg: 192.168.1.88 ...
 * @param string $params['status'] . the status of submission
 *
 * @return int value . count submission need grading
 */
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

/**
 * Get submission by assignid, userid, groupid.
 *
 * @param int $params['assignment'] . the assign id
 * @param string $params['userid'] . the user id
 * @param int $params['groupid'] . the group id
 * @param int $params['attemptnumber'] . the attemptnumber submisison
 * @param int $params['mode'] . order by DESC or ASC
 *
 * @return stdClass $results . list submissions
 */
function get_submission_by_assignid_userid_groupid($params){
    
    if(!isset($params['attemptnumber'])){
        $params['attemptnumber'] = -1;
    }
    if(!isset($params['groupid'])){
        $params['groupid'] = 0;
    }

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

/**
 * Get grades by assignid, userid.
 *
 * @param int $params['assignment'] . the assign id
 * @param string $params['useremail'] . the email user
 * @param int $params['attemptnumber'] . the attemptnumber
 *
 * @return stdClass $results . list grades
 */
function get_assign_grades_by_assignid_userid($params){
    
    if(!isset($params['attemptnumber'])){
        $params['attemptnumber'] = -1;
    }

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

/**
 * Get attemptnumber by assignid, userid, groupid.
 *
 * @param int $params['assignment'] . the assign id
 * @param string $params['useremail'] . the email user
 * @param int $params['groupid'] . the group id
 *
 * @return stdClass $attemptnumbers->result . list attemptnumbers
 */
function get_attemptnumber_by_assignid_userid_groupid($params){
    
    $attemptnumbers = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_assign_get_attemptnumber_by_assignid_userid_groupid',
            'params' => $params
        ), false
    );

    return $attemptnumbers->result;
}

/**
 * Get user flags by assignid, userid.
 *
 * @param int $params['assignment'] . the assign id
 * @param string $params['useremail'] . the email user
 *
 * @return stdClass $flags->userflags .
 */
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

/**
 * Set submission lastest.
 *
 * @param int $params['assignment'] . the assign id
 * @param string $params['useremail'] . the email user
 * @param int $params['groupid'] . the group id
 *
 * @return boolean $resp->result . check if success
 */
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

/**
 * Create submission on hub.
 *
 * @param int $submission['assignment'] . the assign id
 * @param string $submission['useremail'] . the email user
 * @param int $submission['timecreated'] . the time created
 * @param int $submission['timemodified'] . the time modified
 * @param string $submission['status'] . the status
 * @param int $submission['attemptnumber'] . the attempnumber
 * @param int $submission['latest'] . the lastest
 *
 * @return int $resp->sid . the new submission id just created
 */
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

/**
 * Create grade on hub.
 *
 * @param int $grade['assignment'] . the assign id
 * @param string $grade['useremail'] . the email user
 * @param int $grade['timecreated'] . the time created
 * @param int $grade['timemodified'] . the time modified
 * @param int $grade['grader'] . the grader
 * @param int $grade['grade'] . the grade score
 * @param int $grade['attemptnumber'] . the attemptnumber
 *
 * @return int $resp->gid . the new grade id just created
 */
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

/**
 * Get submissions by submission id.
 *
 * @param int $sid . the submission id
 *
 * @return stdClass $resp->assignsubmisison . the assign submission
 */
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

/**
 * Save submission on hub
 *
 * @param int $assignmentid . the id of assign
 * @param int $userid . the id of user on hub 
 * @param array plugindata[onlinetext_editor][text] . content in onlinetext editor
 * @param array plugindata[onlinetext_editor][format] . format of onlinetext editor
 * @param array plugindata[onlinetext_editor][itemid] . the id of submission
 * @param array plugindata[files_filemanager] . The id of a draft area containing files for this submission 
 * @param array plugindata[id] . the id of course module
 * @param array plugindata[userid] . the id of user on hub
 * @param array plugindata[files] . the numbers of file send to hub
 *
 * @return bool . check if success
 */
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

/**
 * Create fakefile on hub.
 *
 * @param string $fakefile['contenthash'] . the contenthash
 * @param string $fakefile['pathnamehash'] . the pathnamehash
 * @param int $fakefile['instanceid'] . the instance id
 * @param string $fakefile['component'] . the component of file
 * @param string $fakefile['filearea'] . the filearea of file
 * @param int $fakefile['itemid'] . the assign id
 * @param string $fakefile['filepath'] . the filepath of file
 * @param string $fakefile['filename'] . the filename of file
 * @param int $fakefile['userid'] . the id of user on hub
 * @param int $fakefile['filesize'] . the filesize of file
 * @param string $fakefile['mimetype'] . the type of file
 * @param string $fakefile['author'] . the author of file
 * @param string $fakefile['license'] . the license of file
 * @param int $fakefile['timecreated'] . the time created
 * @param int $fakefile['timemodified'] . the time modified
 *
 * @return int $resp->fid . the new fakefile id just created
 */
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

    return $resp->fid;
}

/**
 * Delete fakefile on hub.
 *
 * @param string $rparams['component'] . the component of file
 * @param string $rparams['filearea'] . the filearea of file
 * @param int $rparams['itemid'] . the id of assgin
 * @param int $rparams['userid'] . the id of user on hub
 *
 * @return bool $resp->ret . check if success
 */
function delete_fakefile_on_hub($rparams){
    $resp = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_assign_delete_fakefile_on_hub',
            'params' => $rparams,
        ), false
    );

    return $resp->ret;
}

/**
 * Submit submission for grading.
 *
 * @param int $assignment . the id of assignment
 * @param int $userid . the id of user
 * @param int $data['id'] . the id of course module
 * @param string $data['action'] . the action of form
 * @param string $data['submitbutton'] . the name of button submit
 *
 * @return bool $resp . check if success
 */
function submit_remote_for_grading($assignment, $userid, $data){
    $resp = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_assign_submit_remote_for_grading',
            'params' => array(
                'assignment' => $assignment,
                'userid' => $userid,
                'data' => $data
            ),
        ), false
    );

    if (empty($resp))
        return true;
    return false;
}

/**
 * Get submission info for participants on hub.
 *
 * @param int $assignment - the id of assignment
 * @param array $emailparticipants['email'] - list email participant send to hub
 *
 * @return stdClass $resp - list submission info for participants 
 */
function get_remote_submission_info_for_participants($assignment, $emailparticipants){
    $resp = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_assign_get_remote_submission_info_for_participants',
            'params' => array(
                'assignment' => $assignment,
                'emails' => $emailparticipants,
            ),
        ), false
    );
    return $resp->ret;
}

/**
 * Returns student course total grade and grades for activities. 
 * This function does not return category or manual items. 
 * This function is suitable for managers or teachers not students.
 *
 * @param int $courseid - the id of assignment
 * @param int $component - the id of assignment
 * @param int $activityid - the id of assignment
 * @param array $userids - list array user
 *
 * @return stdClass $resp - Returns student course total grade and grades for activities
 */
function core_grades_get_grades($courseid, $component, $activityid, $userids){
    $resp = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'core_grades_get_grades',
            'params' => array(
                'courseid' => $courseid,
                'component' => $component,
                'activityid' => $activityid,
                'userids[0]' => $userids,
            ),
        ), false
    );
    return $resp;
}

/**
 * Submit the grading form data via ajax
 *
 * @param int $args['assignmentid'] - The assignment id to operate on
 * @param int $args['userid'] - The user id the submission belongs to
 * @param string $args['data'] - The data from the grading form, encoded as a json
 *
 * @return array $resp - list of warnings
 */
function mod_remote_assign_submit_grading_form($args){
    $resp = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_assign_submit_grading_form',
            'params' => $args
        ), false
    );

    return $resp;
}

function update_remote_user_flags($assignmentid, $userflags) {
    $resp = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN_M,
            'function_name' => 'mod_assign_set_user_flags',
            'params' => array(
                'assignmentid' => $assignmentid,
                'userflags' => $userflags,
            ),
        ), false
    );
    return $resp;
}
