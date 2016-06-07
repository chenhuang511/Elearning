<?php

require_once(dirname(__FILE__) . '/config.php');
require_once($CFG->libdir . '/additionallib.php');
require_once($CFG->dirroot .'/course/lib.php');
require_once($CFG->libdir .'/filelib.php');
require_once($CFG->dirroot . '/remote/renderer.php');

$urlparams = array();
$PAGE->set_url('/', $urlparams);

// Prevent caching of this page to stop confusion when changing page after making AJAX changes.
$PAGE->set_cacheable(false);

if ($CFG->forcelogin) {
    require_login();
} else {
    user_accesstime_log();
}

$PAGE->set_pagetype('site-index');
$PAGE->set_docs_path('');
$PAGE->set_title($SITE->fullname);
$PAGE->set_heading($SITE->fullname);

echo $OUTPUT->header();

$type = 'available';
if (isloggedin() and !isguestuser() and isset($CFG->frontpageloggedin)) {
    $frontpagelayout = $CFG->frontpageloggedin;
    $course = get_local_enrol_course();
    $type = 'enrol';
} else {
    $course = get_local_courses_record();
    $frontpagelayout = $CFG->frontpage;
}

foreach (explode(',', $frontpagelayout) as $v) {
    switch ($v) {
        case FRONTPAGEALLCOURSELIST:
            if (!empty($course)) {
                render($course, $type);
            }
            break;
        default:
            break;
    }
}

//$courses = get_local_courses_record();
//render($courses);

echo $OUTPUT->footer();