<?php
defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot . '/lib/remote/lib.php');
function get_assign_summary_remote($courseid,$assignid, $options = []){

    // Get course from last parameter if supplied.
    $responedata = moodle_webservice_client(array_merge($options,array('domain' => "http://10.0.0.252:10001",
        'token' => "552b1ca988aebfffb80f6f63404fbb7a",
        'function_name' => 'local_mod_get_assignments',
        'params' => array('courseids[0]' => $courseid,"ip_address"=>"10.0.0.254","username"=>"admin"),
    )));
     if (isset($responedata->courses)) $course = $responedata->courses[0];
    $listassignment = $course->assignments;
    $assignobject = new stdClass();
    foreach ($listassignment as $assignment) {
        if ($assignment->cmid == $assignid) {
            $assignobject = $assignment;
            break;
        }
    }

    return array($course, $assignobject);
  
}