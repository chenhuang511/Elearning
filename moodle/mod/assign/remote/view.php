<?php

require_once('../../../config.php');
require_once($CFG->dirroot.'/mod/assign/locallib.php');
require_once('locallib.php');

$id = required_param('id', PARAM_INT);

if (!$cm = get_remote_course_module_by_cmid("assign", $id)) {
    print_error('invalidcoursemodule');
}
if (!$course = get_local_course_record($cm->course)) {
    print_error('coursemisconf');
}

require_login($course, false, $cm);

$context = context_module::instance($id);

require_capability('mod/assign:view', $context);

if (!has_capability('moodle/course:manageactivities', $context)) {
    // non ajax
    $CFG->nonajax = false;
} else {
    $CFG->nonajax = true;
}

$assign = new assign($context, $cm, $course);
$urlparams = array('id' => $id,
    'action' => optional_param('action', '', PARAM_TEXT),
    'rownum' => optional_param('rownum', 0, PARAM_INT),
    'useridlistid' => optional_param('useridlistid', $assign->get_useridlist_key_id(), PARAM_ALPHANUM));

$url = new moodle_url('/mod/assign/remote/view.php', $urlparams);
$PAGE->set_url($url);

$completion=new completion_info($course);
$completion->set_module_viewed($cm);

// Get the assign class to
// render the page.
echo $assign->view(optional_param('action', '', PARAM_TEXT));
