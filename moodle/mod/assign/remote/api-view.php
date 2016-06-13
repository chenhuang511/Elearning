<?php

require_once('../../../config.php');
require_once('locallib.php');

$cmid = optional_param('id', 0, PARAM_INT);
$action = optional_param('action', "", PARAM_TEXT);


// get course module
$cm = get_remote_course_module_by_cmid('assign', $cmid);
$course = get_local_course_record($cm->course);

require_login($course, false, $cm);

$o = new remote_assign_mod($cm);
$o->setOUTPUT($OUTPUT);
$o->setPAGE($PAGE);
$html = '';

switch ($action){
    case 'grading':
        $html = 'do grading';
        break;
    case 'editsubmission':
        $html = 'do edit';
        break;
    default:
        $html = $o->view_summary();
        break;
}
echo $html;
