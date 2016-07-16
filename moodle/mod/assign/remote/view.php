<?php

require_once('../../../config.php');
require_once($CFG->dirroot.'/mod/assign/locallib.php');
require_once('locallib.php');

global $DB;

$id = required_param('id', PARAM_INT);
$nonajax = optional_param('nonajax', true, PARAM_BOOL);

if (!$cm = get_remote_course_module_by_cmid("assign", $id)) {
    print_error('invalidcoursemodule');
}
if (!$course = get_local_course_record($cm->course)) {
    print_error('coursemisconf');
}
if (!$DB->get_record('assign', array('remoteid' => $cm->instance))){
    // Get remote assign
    $remoteassign = get_remote_assign_by_id($cm->instance);
    // Check if not exist then insert local DB
    unset($remoteassign->id);
    $remoteassign->course = $course->id;
    $remoteassign->remoteid = $cm->instance;
    // From this point we make database changes, so start transaction.
    $transaction = $DB->start_delegated_transaction();
    // Insert assign config
    $aid = $DB->insert_record('assign', $remoteassign);
    // Insert plugin config
    $pluginconfigs = get_remote_assign_plugin_config($cm->instance);
    foreach ($pluginconfigs as $pluginconfig){
        $pluginconfig->assignment = $aid;
    }
    $DB->insert_records('assign_plugin_config', $pluginconfigs);

    $transaction->allow_commit();
}

require_login($course, false, $cm);

$context = context_module::instance($id);

require_capability('mod/assign:view', $context);

if (!has_capability('moodle/course:manageactivities', $context) && $nonajax == false) {
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
