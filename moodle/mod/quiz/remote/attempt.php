<?php
/**
 * Created by PhpStorm.
 * User: vanhaIT
 * Date: 06/06/2016
 * Time: 8:26 SA
 */

/**
 * This script displays a particular page of a quiz attempt that is in progress.
 *
 * @package   mod_quiz
 * @copyright 1999 onwards Martin Dougiamas  {@link http://moodle.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(__FILE__) . '/../../../config.php');
require_once($CFG->dirroot . '/mod/quiz/locallib.php');
require_once($CFG->dirroot.'/mod/quiz/remote/locallib.php');

// Look for old-style URLs, such as may be in the logs, and redirect them to startattemtp.php.
if ($id = optional_param('id', 0, PARAM_INT)) {
    redirect($CFG->wwwroot . '/mod/quiz/remote/startattempt.php?cmid=' . $id . '&sesskey=' . sesskey());
} else if ($qid = optional_param('q', 0, PARAM_INT)) {
    if (!$cm = get_coursemodule_from_instance('quiz', $qid)) {
        print_error('invalidquizid', 'quiz');
    }
    redirect(new moodle_url('/mod/quiz/startattempt.php',
        array('cmid' => $cm->id, 'sesskey' => sesskey())));
}

// Get submitted parameters.
$isremote = (MOODLE_RUN_MODE == MOODLE_MODE_HUB)?true:false;
$attemptid = required_param('attempt', PARAM_INT);
$page = optional_param('page', 0, PARAM_INT);

$attemptremote = get_remote_get_attempt_data($attemptid);

$attempt = get_remote_attempt_by_attemptid($attemptid);
$quiz = get_remote_quiz_by_id($attempt->quiz);
$course = get_local_course_record($quiz->course);
$cm = get_remote_course_module_by_instance("quiz", $quiz->id);
$attemptobj = new quiz_attempt($attempt, $quiz, $cm, $course, false, true);
$context = context_module::instance($cm->id);

$nonajax = optional_param('nonajax', true, PARAM_BOOL);
if (!has_capability('moodle/course:manageactivities', $context) && $nonajax == false) {
    $CFG->nonajax = false;
} else {
    $CFG->nonajax = true;
}

$page = $attemptobj->force_page_number_into_range($page);
$PAGE->set_url($attemptobj->attempt_url(null, $page));

// Check login.
require_login($attemptobj->get_course(), false, $attemptobj->get_cm());

// Check that this attempt belongs to this user.
$user = get_remote_mapping_user();
if ($attemptobj->get_userid() != $user[0]->id) {
    if ($attemptobj->has_capability('mod/quiz:viewreports')) {
        redirect($attemptobj->review_url(null, $page));
    } else {
        throw new moodle_quiz_exception($attemptobj->get_quizobj(), 'notyourattempt');
    }
}

// Check capabilities and block settings.
if (!$attemptobj->is_preview_user()) {
    $attemptobj->require_capability('mod/quiz:attempt');
    if (empty($attemptobj->get_quiz()->showblocks)) {
        $PAGE->blocks->show_only_fake_blocks();
    }
} else {
    navigation_node::override_active_url($attemptobj->start_attempt_url());
}

// If the attempt is already closed, send them to the review page.
if ($attemptobj->is_finished()) {
    redirect($attemptobj->review_url(null, $page));
} else if ($attemptobj->get_state() == quiz_attempt::OVERDUE) {
    redirect($attemptobj->summary_url());
}

// Check the access rules.
$accessmanager = $attemptobj->get_access_manager(time());
$accessmanager->setup_attempt_page($PAGE);
$output = $PAGE->get_renderer('mod_quiz');
$messages = $accessmanager->prevent_access();
if (!$attemptobj->is_preview_user() && $messages) {
    print_error('attempterror', 'quiz', $attemptobj->view_url(),
        $output->access_messages($messages));
}
if ($accessmanager->is_preflight_check_required($attemptobj->get_attemptid())) {
    redirect($attemptobj->start_attempt_url(null, $page));
}

// Set up auto-save if required.
$autosaveperiod = get_config('quiz', 'autosaveperiod');
if ($autosaveperiod) {
    $PAGE->requires->yui_module('moodle-mod_quiz-autosave',
        'M.mod_quiz.autosave.init', array($autosaveperiod));
}

// Log this page view. Trigger the attempt viewed event.
if($isremote){
    $attemptobj->fire_attempt_viewed_event();
}else{
    get_remote_view_attempt($attemptid, $page);
}

$slots = $attemptobj->get_slots($page);

// Check.
if (empty($slots)) {
    throw new moodle_quiz_exception($attemptobj->get_quizobj(), 'noquestionsfound');
}

// Update attempt page, redirecting the user if $page is not valid. @TODO: viet lai ham nay
if (!$attemptobj->set_currentpage($page)) {
    redirect($attemptobj->start_attempt_url(null, $attemptobj->get_currentpage()));
}

// Initialise the JavaScript.
$headtags = $attemptobj->get_html_head_contributions($page);
$PAGE->requires->js_init_call('M.mod_quiz.init_attempt_form', null, false, quiz_get_js_module());

// Arrange for the navigation to be displayed in the first region on the page.
$navbc = $attemptobj->get_navigation_panel($output, 'quiz_attempt_nav_panel', $page, false, $attemptremote);
$regions = $PAGE->blocks->get_regions();
$PAGE->blocks->add_fake_block($navbc, reset($regions));

$title = get_string('attempt', 'quiz', $attemptobj->get_attempt_number());
$headtags = $attemptobj->get_html_head_contributions($page);
$PAGE->set_title($attemptobj->get_quiz_name());
$PAGE->set_heading($attemptobj->get_course()->fullname);

if ($attemptobj->is_last_page($page)) {
    $nextpage = -1;
} else {
    $nextpage = $page + 1;
}

echo $output->attempt_page($attemptobj, $page, $accessmanager, $messages, $slots, $id, $nextpage, $attemptremote);
