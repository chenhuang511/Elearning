<?php

require_once(dirname(__FILE__) . '/config.php');
require_once($CFG->libdir . '/additionallib.php');
require_once($CFG->dirroot . '/remote/renderer.php');

echo $OUTPUT->header();

$courses = get_local_courses_record();
render($courses);

echo $OUTPUT->footer();
