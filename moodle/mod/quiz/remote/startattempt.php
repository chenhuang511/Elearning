<?php
/**
 * Created by PhpStorm.
 * User: vanhaIT
 * Date: 06/06/2016
 * Time: 8:25 SA
 */

/**
 * This script deals with starting a new attempt at a quiz.
 *
 * Normally, it will end up redirecting to attempt.php - unless a password form is displayed.
 *
 * This code used to be at the top of attempt.php, if you are looking for CVS history.
 *
 * @package   mod_quiz
 * @copyright 2009 The Open University
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(__FILE__) . '/../../../config.php');
require_once($CFG->dirroot . '/mod/quiz/locallib.php');
require_once($CFG->dirroot.'/mod/quiz/remote/locallib.php');

// Get submitted parameters.
$id = required_param('cmid', PARAM_INT); // Course module id
$forcenew = optional_param('forcenew', false, PARAM_BOOL); // Used to force a new preview
$page = optional_param('page', -1, PARAM_INT); // Page to jump to in the attempt.

if (!$cm = get_coursemodule_from_id('quiz', $id)) {
    print_error('invalidcoursemodule');
}
if (!$course = $DB->get_record('course', array('id' => $cm->course))) {
    print_error("coursemisconf");
}

$quizobj = quiz::create($cm->instance, $USER->id);
// This script should only ever be posted to, so set page URL to the view page.
$PAGE->set_url($quizobj->view_url());

// Check login and sesskey.
require_login($quizobj->get_course(), false, $quizobj->get_cm());
require_sesskey();
$PAGE->set_heading($quizobj->get_course()->fullname);

// Check questions.
if (!$quizobj->has_questions($quiz->id)) {
    if ($quizobj->has_capability('mod/quiz:manage')) {
        redirect($quizobj->edit_url());
    } else {
        print_error('cannotstartnoquestions', 'quiz', $quizobj->view_url());
    }
}

// Create an object to manage all the other (non-roles) access rules.
$timenow = time();
$accessmanager = $quizobj->get_access_manager($timenow);

$nonajax = optional_param('nonajax', true, PARAM_BOOL);
$context = context_module::instance($cm->id);
if (!has_capability('moodle/course:manageactivities', $context) && $nonajax == false) {
    $CFG->nonajax = false;
} else {
    $CFG->nonajax = true;
}

// Validate permissions for creating a new attempt and start a new preview attempt if required.
list($currentattemptid, $attemptnumber, $lastattempt, $messages, $page) =
    quiz_validate_new_attempt($quizobj, $accessmanager, $forcenew, $page, true);

// Check access.
if (!$quizobj->is_preview_user() && $messages) {
    $output = $PAGE->get_renderer('mod_quiz');
    print_error('attempterror', 'quiz', $quizobj->view_url(),
        $output->access_messages($messages));
}

if ($accessmanager->is_preflight_check_required($currentattemptid)) {
    // Need to do some checks before allowing the user to continue.
    $mform = $accessmanager->get_preflight_check_form(
        $quizobj->start_attempt_url($page), $currentattemptid);

    if ($mform->is_cancelled()) {
        $accessmanager->back_to_view_page($PAGE->get_renderer('mod_quiz'));

    } else if (!$mform->get_data()) {

        // Form not submitted successfully, re-display it and stop.
        $PAGE->set_url($quizobj->start_attempt_url($page));
        $PAGE->set_title($quizobj->get_quiz_name());
        $accessmanager->setup_attempt_page($PAGE);
        $output = $PAGE->get_renderer('mod_quiz');
        if (empty($quizobj->get_quiz()->showblocks)) {
            $PAGE->blocks->show_only_fake_blocks();
        }

        echo $output->start_attempt_page($quizobj, $mform);
        die();
    }

    // Pre-flight check passed.
    $accessmanager->notify_preflight_check_passed($currentattemptid);
}

if ($currentattemptid) {
    if ($lastattempt->state == quiz_attempt::OVERDUE) {
        redirect($quizobj->summary_url($lastattempt->id));
    } else {
        redirect($quizobj->attempt_url($currentattemptid, $page));
    }
}

$user = get_remote_mapping_user();
$preview = $quizobj->is_preview_user();

$setting = array();
if($quiz->settinglocal){
    $fields =  array(
        'timeopen',
        'timeclose',
        'timelimit',
        'overduehandling',
        'graceperiod',
        'attempts',
        'grademethod',
    );
    $index = 0;
    foreach ($fields as $field){
        $setting["setting[$index][name]"] = $field;
        $setting["setting[$index][value]"] = $quiz->$field;
        $index++;
    }
}
$attemptremote = get_remote_quiz_start_attempt($quiz->remote, $user[0]->id, $preview, $setting);

$attempt = $attemptremote->attempt;
// Redirect to the attempt page.
redirect($quizobj->attempt_url($attempt->id, $page));
