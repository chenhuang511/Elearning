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

$renderer = $PAGE->get_renderer('core_remote');


echo $OUTPUT->header();

$type = 'available';
if (isloggedin() and !isguestuser() and isset($CFG->frontpageloggedin)) {
    $frontpagelayout = $CFG->frontpageloggedin;
    $courses = get_local_enrol_course();
    $type = 'enrol';
} else {
    $courses = get_local_courses_record();
    $frontpagelayout = $CFG->frontpage;
}

foreach (explode(',', $frontpagelayout) as $v) {
    switch ($v) {
        case FRONTPAGEALLCOURSELIST:
            if (!empty($courses)) {
                $renderer->render_remote_course($courses, $type);
            }
            break;
        default:
            break;
    }
}

echo $OUTPUT->footer();