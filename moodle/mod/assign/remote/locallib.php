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
    return moodle_webservice_client(array_merge(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN_M,
            'function_name' => 'mod_assign_get_submission_status',
            'params' => array('assignid' => $assignid,'userid' => $userid),
        )
    ));
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
