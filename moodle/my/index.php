<?php

require_once('../config.php');
require_once($CFG->dirroot . '/course/remote/locallib.php');
require_once($CFG->dirroot . '/remote/renderer.php');

$courseid = required_param('id', 0, PARAM_INT);
$sectionid = optional_param('sectionid', 0, PARAM_INT);
$section = optional_param('section', 0, PARAM_INT);
$coursemodule = null;
$html = '';

$course = (empty($courseid)) ? null : get_local_course_record($courseid, true);

$coursename = $course->fullname;
$coursesummary = $course->summary;

context_helper::preload_course($course->id);
$context = context_course::instance($course->id, MUST_EXIST);

require_login($course);

require_capability('moodle/course:view', $context);

// Switchrole - sanity check in cost-order...
$reset_user_allowed_editing = false;
if ($switchrole > 0 && confirm_sesskey() &&
    has_capability('moodle/role:switchroles', $context)
) {
    // is this role assignable in this context?
    // inquiring minds want to know...
    $aroles = get_switchable_roles($context);
    if (is_array($aroles) && isset($aroles[$switchrole])) {
        role_switch($switchrole, $context);
        // Double check that this role is allowed here
        require_login($course);
    }
    // reset course page state - this prevents some weird problems ;-)
    $USER->activitycopy = false;
    $USER->activitycopycourse = NULL;
    unset($USER->activitycopyname);
    unset($SESSION->modform);
    $USER->editing = 0;
    $reset_user_allowed_editing = true;
}

$course = get_remote_course_content($course->remoteid);


$PAGE->set_title($coursename);
$PAGE->set_heading($coursesummary);
$PAGE->set_url("/course/remote/view.php", array('id' => $courseid, 'sectionid' => $sectionid, 'section' => $section));

echo $OUTPUT->header();

echo '<input id="hidden-coursename" type="hidden" value="'  . htmlspecialchars($coursename) . '">';
echo '<input id="hidden-summary" type="hidden" value="'  . htmlspecialchars($coursesummary) . '">';
$renderer = $PAGE->get_renderer('core_remote');
$renderer->render_course_detail($course);

echo $OUTPUT->footer();