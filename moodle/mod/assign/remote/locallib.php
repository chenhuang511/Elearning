<?php
defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot . '/lib/remote/lib.php');
require_once($CFG->dirroot . '/lib/additionallib.php');


/**
 * get assign by id
 *
 * @param int $assignid . the id of assign
 * @param array $options . the options
 *
 * @return stdClass $asssign
 */
function get_remote_assign_by_id($assignid, $options = array()){
    $resp = moodle_webservice_client(array_merge($options,
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_assign_get_assign_by_id',
            'params' => array('assignid' => $assignid),
        )
    ));

    return $resp;
}

/**
 * get assign by id
 *
 * @param int $assignid . the id of assign
 * @param array $options . the options
 *
 * @return stdClass $asssign
 */
function get_remote_assign_by_id_instanceid($assignid, $instanceid, $options = array())
{
    $resp = moodle_webservice_client(array_merge($options,
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_assign_get_assign_by_id_instanceid',
            'params' => array(
                'assignid' => $assignid,
                'instanceid' => $instanceid
            ),
        )
    ));

    if ($resp->assignment){
        $resp->assignment->id = (int)get_local_assign_record($resp->assignment->id)->id;
    }

    return $resp->assignment;
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
    $ruser = get_remote_mapping_user($userid);
    $rassignment = get_local_assign_record($assignid, true)->remoteid;
    
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_assign_get_remote_submission_status',
            'params' => array(
                'assignid' => $rassignment,
                'userid' => $ruser[0]->id
            ),
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
    $resp = moodle_webservice_client(array_merge($options,
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_assign_get_onlinetext_submission',
            'params' => array('submissionid' => $submissionid),
        )
    ), false);

    if (!isset($resp->onlinetext)){
        return 0;
    }

    return $resp->onlinetext;
}

/**
 * get assign assignment feedback comment on hub
 *
 * @param int $gradeid . the id of grade
 *
 * @return stdClass feedbackcomments
 */
function get_assignfeedback_comments($gradeid) {
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
 * @param int $assignment . the assignment id
 *
 * @return stdClass $value
 */
function get_remote_assign_plugin_config($assignment){
    $resp = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_assign_get_plugin_config',
            'params' => array(
                'assignment' => $assignment
            )
        ), false
    );

    return $resp->pluginconfig;
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

    $userid = $params['userid'];
    $params['userid'] = get_remote_mapping_user($params['userid'])[0]->id;
    $assignment = $params['assignment'];
    $params['assignment'] = get_local_assign_record($params['assignment'], true)->remoteid;

    $results = array();

    $resp =  moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_assign_get_submission_by_assignid_userid_groupid',
            'params' => $params
        ), false
    );

    foreach ($resp->submissions as $submission){
        $submission->userid = $userid;
        $submission->assignment = $assignment;
        $results[$submission->id] = $submission;
    }

    return $results;
}

/**
 * Get grades by id.
 *
 * @param int $id . the id of grade assign.
 *
 * @return stdClass $results . list grades
 */
function get_remote_assign_grades_by_id($id){
    global $USER;
    $resp =  moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_assign_get_grades_by_id',
            'params' => array(
                'id' => $id
            )
        ), false
    );
    
    $resp->grades->assignment =  get_local_assign_record($resp->grades->assignment)->id;
    $ruser = get_remote_mapping_user();

    if ($resp->grades->userid == $ruser[0]->id){
        $resp->grades->userid = $USER->id;
    }
    return $resp->grades;
}

/**
 * Get grades by assignid, userid.
 *
 * @param int $params['assignment'] . the assign id
 * @param int $params['userid'] . the userid
 * @param int $params['attemptnumber'] . the attemptnumber
 *
 * @return stdClass $results . list grades
 */
function get_remote_assign_grades_by_assignid_userid($params){
    global $DB;

    if(!isset($params['attemptnumber'])){
        $params['attemptnumber'] = -1;
    }

    $userid = $params['userid'];
    $params['userid'] = get_remote_mapping_user($params['userid'])[0]->id;
    $assignment = $params['assignment'];
    $params['assignment'] = get_local_assign_record($params['assignment'], true)->remoteid;

    $results = array();

    $resp =  moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_assign_get_grades_by_assignid_userid',
            'params' => $params
        ), false
    );

    foreach ($resp->grades as $grade){
        $grade->userid = $userid;
        $grade->assignment = $assignment;
        if (isset($grade->grader)) {
            $grader = $DB->get_record('user', array('email' => $grade->grader));
            $grade->grader = $grader->id;
        }
        $results[$grade->id] = $grade;
    }
    
    return $results;
}

/**
 * Get attemptnumber by assignid, userid, groupid.
 *
 * @param int $params['assignment'] . the assign id
 * @param int $params['userid'] . the userid
 * @param int $params['groupid'] . the group id
 *
 * @return stdClass $attemptnumbers->result . list attemptnumbers
 */
function get_attemptnumber_by_assignid_userid_groupid($params){
    $params['userid'] = get_remote_mapping_user($params['userid'])[0]->id;
    $params['assignment'] = get_local_assign_record($params['assignment'], true)->remoteid;

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
 * @param int $params['userid'] . the userid
 *
 * @return stdClass $flags->userflags .
 */
function get_user_flags_by_assignid_userid($params){
    $userid = $params['userid'];
    $params['userid'] = get_remote_mapping_user($params['userid'])[0]->id;
    $assignment = $params['assignment'];
    $params['assignment'] = get_local_assign_record($params['assignment'], true)->remoteid;
    
    $resp =  moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_assign_get_user_flags_by_assignid_userid',
            'params' => $params
        ), false
    );

    if($resp->exception)  {
        return 0;
    }
    $resp->userflags->userid = $userid;
    $resp->userflags->assignment = $assignment;

    return $resp->userflags;
}

/**
 * Set submission lastest.
 *
 * @param int $params['assignment'] . the assign id
 * @param int $params['userid'] . the userid
 * @param int $params['groupid'] . the group id
 *
 * @return boolean $resp->result . check if success
 */
function set_submission_lastest($params){
    $params['userid'] = get_remote_mapping_user($params['userid'])[0]->id;
    $params['assignment'] = get_local_assign_record($params['assignment'], true)->remoteid;

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
 * @param int $submission['userid'] . the id of user
 * @param int $submission['timecreated'] . the time created
 * @param int $submission['timemodified'] . the time modified
 * @param string $submission['status'] . the status
 * @param int $submission['attemptnumber'] . the attempnumber
 * @param int $submission['latest'] . the lastest
 *
 * @return int $resp->sid . the new submission id just created
 */
function create_remote_submission($submission){
    $ruser = get_remote_mapping_user($submission->userid);
    $rassignment = get_local_assign_record($submission->assignment, true)->remoteid;
    $resp = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_assign_create_submission',
            'params' => array(
                'assignment' => $rassignment,
                'userid' => $ruser[0]->id,
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
 * Update submission on hub.
 *
 * @param int $submission['id'] . the submission id
 * @param int $submission['assignment'] . the assign id
 * @param int $submission['userid'] . the user id
 * @param int $submission['timecreated'] . the time created
 * @param int $submission['timemodified'] . the time modified
 * @param string $submission['status'] . the status
 * @param int $submission['attemptnumber'] . the attempnumber
 * @param int $submission['latest'] . the lastest
 *
 * @return int $resp->sid . the new submission id just created
 */
function update_remote_submission($submission){
    $ruser = get_remote_mapping_user($submission->userid)[0]->id;
    $rassignment = get_local_assign_record($submission->assignment, true)->remoteid;

    $resp = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_assign_update_submission',
            'params' => array(
                'id' => $submission->id,
                'assignment' => $rassignment,
                'userid' => $ruser,
                'timecreated' => $submission->timecreated,
                'timemodified' => $submission->timemodified,
                'status' => $submission->status,
                'attemptnumber' => $submission->attemptnumber,
                'latest' => $submission->latest,
            )
        ), false
    );
    
    return $resp->bool;
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
    $suserid = get_remote_mapping_user($grade->userid)[0]->id;
    $grader = get_remote_mapping_user($grade->grader)[0]->id;
    $rassignment = get_local_assign_record($grade->assignment, true)->remoteid;
    
    $resp = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_assign_create_grade',
            'params' => array(
                'assignment' => $rassignment,
                'userid' => $suserid,
                'timecreated' => $grade->timecreated,
                'timemodified' => $grade->timemodified,
                'grader' => $grader,
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
    global $DB;
    $resp = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_assign_get_submission_by_id',
            'params' => array('id' => $sid)
        ), false
    );

    $user = $DB->get_record('user', array('email' => $resp->assignsubmisison->useremail));
    $resp->assignsubmisison->userid = $user->id;
    $resp->assignsubmisison->assignment = get_local_assign_record($resp->assignsubmisison->assignment)->id;
    unset($resp->assignsubmisison->useremail);

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
    $resp = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_assign_save_remote_submission',
            'params' => array(
                'assignmentid' => $assignmentid,
                'userid' => $userid,
                'plugindata' => $data,
            )
        ), false
    );

    if (empty($resp))
        return true;
    return false;
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
    $rassignment = get_local_assign_record($assignment, true)->remoteid;
    
    $resp = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_assign_submit_remote_for_grading',
            'params' => array(
                'assignment' => $rassignment,
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
    $rassignment = get_local_assign_record($assignment, true)->remoteid;

    $resp = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_assign_get_remote_submission_info_for_participants',
            'params' => array(
                'assignment' => $rassignment,
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
function get_remote_assign_grades_get_grades($courseid, $component, $activityid, $userids){
    global $DB;
    $ruserids = array();
    foreach ($userids as $userid) {
        $ruserids[] = get_remote_mapping_user($userid)[0]->id;
    }

    $resp = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_assign_grades_get_grades',
            'params' => array(
                'courseid' => $courseid,
                'component' => $component,
                'activityid' => $activityid,
                'userids' => $ruserids,
            ),
        ), false
    );


    foreach ($resp->items as $item){
        foreach ($item->grades as $studentid => $studentgrades){
            $student = $DB->get_record('user', array('email' => $studentgrades->useremail));
            $studentgrades->userid = $student->id;
            unset($studentgrades->useremail);

            $item->grades[$student->id] = $studentgrades;
            unset($item->grades[$studentid]);
        }
    }
     
    return $resp;
}

/**
 * Update a grade item and associated student grades.
 *
 * @param string $source - the name of source
 * @param int $courseid - the id of course
 * @param string $component - the name of component
 * @param string $activityid - the id of activity
 * @param array $grades - the array of grades
 * @param array $itemdetails - the array of itemdetails   
 * 
 * @param array $userids - list array user
 *
 * @return stdClass $resp - Returns student course total grade and grades for activities
 */
function core_grades_update_grades($source, $courseid, $component, $activityid, $itemnumber, $rgrades, $itemdetails){
    $resp = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'core_grades_update_grades',
            'params' => array(
                'source' => $source,
                'courseid' => $courseid,
                'component' => $component,
                'activityid' => $activityid,
                'itemnumber' => $itemnumber,
                'grades' => array($rgrades),
                'itemdetails' => $itemdetails,
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

/**
 * Create the assignsubmission_onlinetext record
 * @param int $onlinetextsubmission['assignment']   -   The id of assignment
 * @param int $onlinetextsubmission['submission']   -   The id of submission
 * @param string $onlinetextsubmission['onlinetext']-   The content of onlinetext
 * @param int $onlinetextsubmission['onlineformat'] -   The onlinetext format
 *
 * @return false|mixed
 */
function create_onlinetext_submission($onlinetextsubmission){
    $resp = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_assign_create_onlinetext_submission',
            'params' => array(
                'assignment' => $onlinetextsubmission->assignment,
                'submission' => $onlinetextsubmission->submission,
                'onlinetext' => $onlinetextsubmission->onlinetext,
                'onlineformat' => $onlinetextsubmission->onlineformat,
            ),
        ), false
    );
    return $resp;
}

/**
 * Update the assignsubmission_onlinetext record
 * @param int $onlinetextsubmission['assignment']   -   The id of assignment
 * @param int $onlinetextsubmission['submission']   -   The id of submission
 * @param string $onlinetextsubmission['onlinetext']-   The content of onlinetext
 * @param int $onlinetextsubmission['onlineformat'] -   The onlinetext format
 *
 * @return false|mixed
 */
function update_onlinetext_submission($onlinetextsubmission){
    $resp = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_assign_update_onlinetext_submission',
            'params' => array(
                'id' => $onlinetextsubmission->id,
                'assignment' => $onlinetextsubmission->assignment,
                'submission' => $onlinetextsubmission->submission,
                'onlinetext' => $onlinetextsubmission->onlinetext,
                'onlineformat' => $onlinetextsubmission->onlineformat,
            ),
        ), false
    );
    return $resp;
}

function get_remote_assign_raw_data_query_db($sql, $param, $pagestart, $pagesize) {
    $resp = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_assign_get_raw_data_query_db',
            'params' => array_merge(array('sql' => $sql, 'pagestart' => $pagestart, 'pagesize' => $pagesize), $param)
        ), false
    );
    return $resp;
}

function get_remote_assign_grade_raw_data_infomation($sql, $param, $pagestart = 0, $pagesize = 0) {
    $resp = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_assign_get_grade_raw_data_infomation',
            'params' => array_merge(array('sql' => $sql, 'pagestart' => $pagestart, 'pagesize' => $pagesize), $param)
        ), false
    );
    return $resp;
}

/**
 * Get Scale by remote scale id
 *
 * @param $sid  -  The id of scale
 * @return false|mixed
 */
function get_scale_by_id($sid){
    $resp = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_assign_get_scale_by_id',
            'params' => array(
                'sid' => $sid
            )
        ), false
    );
    return $resp->scale;
}

function get_remote_assign_grade_items_raw_data($sql, $param, $pagestart = 0, $pagesize = 0) {
    $resp = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_assign_get_grade_items_raw_data',
            'params' => array_merge(array('sql' => $sql, 'pagestart' => $pagestart, 'pagesize' => $pagesize), $param)
        ), false
    );
    return $resp;
}

/**
 * Update assign assignment feedback comment on hub
 *
 * @param int $id . the id of feedback comment
 * @param int $assignment . the id of assignment
 * @param int $grade . the id of grade
 * @param string $commenttext . the content of commenttext
 * @param int $commentformat . the format of commenttext
 *
 * @return bool $resp->bool . check if success
 */
 function update_assignfeedback_comments($feedbackcomment) {
     $resp = moodle_webservice_client(
         array(
             'domain' => HUB_URL,
             'token' => HOST_TOKEN,
             'function_name' => 'local_mod_assign_update_assignfeedback_comments',
             'params' => array(
                 'id' => $feedbackcomment->id,
                 'assignment' => $feedbackcomment->assignment,
                 'grade' => $feedbackcomment->grade,
                 'commenttext' => $feedbackcomment->commenttext,
                 'commentformat' => $feedbackcomment->commentformat,
             ),
         ), false
     );
     return $resp->bool;
 }
 
 /**
  * Create assign assignment feedback comment on hub
  *
  * @param int $assignment . the id of assignment
  * @param int $grade . the id of grade
  * @param string $commenttext . the content of commenttext
  * @param int $commentformat . the format of commenttext
  * 
  *
  * @return bool $resp->bool . check if success
  */
 function create_assignfeedback_comments($feedbackcomment) {
     $resp = moodle_webservice_client(
         array(
             'domain' => HUB_URL,
             'token' => HOST_TOKEN,
             'function_name' => 'local_mod_assign_create_assignfeedback_comments',
             'params' => array(
                 'assignment' => $feedbackcomment->assignment,
                 'grade' => $feedbackcomment->grade,
                 'commenttext' => $feedbackcomment->commenttext,
                 'commentformat' => $feedbackcomment->commentformat,
             ),
         ), false
     );
     return $resp->fcid;
 }

/**
* Update grade on hub.
*
* @param int $grade['id'] . the grade id
* @param int $grade['assignment'] . the assign id
* @param string $grade['useremail'] . the email user
* @param int $grade['timecreated'] . the time created
* @param int $grade['timemodified'] . the time modified
* @param int $grade['grader'] . the grader
* @param int $grade['grade'] . the grade score
* @param int $grade['attemptnumber'] . the attemptnumber
* @return int $resp->gid . the new grade id just created 
 */
function update_remote_grade($grade){
    $suserid = get_remote_mapping_user($grade->userid)[0]->id;
    $grader = get_remote_mapping_user($grade->grader)[0]->id;
    $rassignment = get_local_assign_record($grade->assignment, true)->remoteid;

    $resp = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_assign_update_grade',
            'params' => array(
                    'id' => $grade->id,
                    'assignment' => $rassignment,
                    'userid' => $suserid,
                    'timecreated' => $grade->timecreated,
                    'timemodified' => $grade->timemodified,
                    'grader' => $grader, 
                    'grade' => $grade->grade,
                    'attemptnumber' => $grade->attemptnumber,
                )
        ), false
    );
    return $resp->bool;
 }

/**
 * Get remote files submission by submissionid.
 * 
 * @param int $submissionid  -  The id of submission  
 * 
 * @return stdClass return object files submission
 */
function get_remote_files_submission($submissionid){
    $resp = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_assign_get_files_submission',
            'params' => array(
                'submissionid' => $submissionid
            ),
        ), false
    );

    return $resp->filesubmission;
}

/**
 * Create the file_submission record  
 * 
 * @param int $filesubmission['assignment']   -   The id of assignment
 * @param int $filesubmission['submission']   -   The id of submission
 * @param int $filesubmission['numfiles']-   The number of file uploaded
 *
 * @return false|mixed
 */
function create_remote_files_submission($filesubmission){
    $resp = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_assign_create_files_submission',
            'params' => array(
                'assignment' => $filesubmission->assignment,
                'submission' => $filesubmission->submission,
                'numfiles' => $filesubmission->numfiles,
            ),
        ), false
    );
    return $resp;
}

/**
 * Update the file_submission record   
 * 
 * @param int $filesubmission['id']   -   The id of assignment
 * @param int $filesubmission['assignment']   -   The id of assignment
 * @param int $filesubmission['submission']  -  The id of submission
 * @param int $filesubmission['numfiles']  -  The onlinetext format
 *                        
 * @return false|mixed   -- Check if success
 */
function update_remote_files_submission($filesubmission){
    $resp = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_assign_update_files_submission',
            'params' => array(
                'id' => $filesubmission->id,
                'assignment' => $filesubmission->assignment,
                'submission' => $filesubmission->submission,
                'numfiles' => $filesubmission->numfiles,
            ),
        ), false
    );
    return $resp;
}

function get_remote_assign_grade_grades_raw_data($sql, $param, $pagestart = 0, $pagesize = 0) {
    $resp = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_assign_get_grade_grades_raw_data',
            'params' => array_merge(array('sql' => $sql, 'pagestart' => $pagestart, 'pagesize' => $pagesize), $param)
        ), false
    );
    return $resp;
}
