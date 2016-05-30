<?php
defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot . '/lib/remote/lib.php');
require_once($CFG->dirroot . '/lib/additionallib.php');
require_once($CFG->dirroot . '/mnet/service/enrol/locallib.php');
function get_assign_summary_remote($courseid, $options = []){
    return moodle_webservice_client(array_merge($options,array('domain' => HUB_URL,
        'token' => HOST_TOKEN,
        'function_name' => 'local_mod_get_assignments',
        'params' => array('courseids[0]' => $courseid,"ip_address"=>"10.0.0.254","username"=>"admin"),
    )));
}