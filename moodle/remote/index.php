<?php

require_once(dirname(__FILE__) . '/../config.php');
require_once($CFG->libdir . '/additionallib.php');
require_once($CFG->dirroot . '/remote/renderer.php');

require_login();

echo $OUTPUT->header();

$courses = get_local_enrol_course();
render($courses);

echo $OUTPUT->footer();
