<?php

require_once('../../../config.php');
require_once('../locallib.php');

$cmid = optional_param('id', 0, PARAM_INT);

// get course module
$cm = get_remote_course_module_by_cmid('assign', $cmid);
$course = get_local_course_record($cm->course);

require_login($course, false, $cm);

$context = context_module::instance($cmid);

require_capability('mod/assign:view', $context);

$assign = new assign($context, $cm, $course);

echo $assign->view(optional_param('action', '', PARAM_TEXT));
