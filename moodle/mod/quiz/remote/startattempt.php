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

if (!$cm = get_remote_course_module_by_cmid("quiz", $id)) {
    print_error('invalidcoursemodule');
}
if (!$course = get_local_course_record($cm->course)) {
    print_error("coursemisconf");
}

//$quizobj = quiz::create($cm->instance, $USER->id);
$quiz = get_remote_quiz_by_id($cm->instance);
$quizobj = new quiz($quiz, $cm, $course);
$preview = $quizobj->is_preview_user();
//var_dump($preview);die;

// This script should only ever be posted to, so set page URL to the view page.
$PAGE->set_url($quizobj->view_remote_url());

// Check login and sesskey.
require_login($quizobj->get_course(), false, $quizobj->get_cm());
require_sesskey();
$PAGE->set_heading($quizobj->get_course()->fullname);

// Check questions.
if (!$quizobj->has_questions()) {
    print_error('cannotstartnoquestions', 'quiz', $quizobj->view_url());
}

// Create an object to manage all the other (non-roles) access rules.
$timenow = time();
$accessmanager = $quizobj->get_remote_access_manager($timenow);

// Validate permissions for creating a new attempt and start a new preview attempt if required.
list($currentattemptid, $attemptnumber, $lastattempt, $messages, $page) =
    quiz_remote_validate_new_attempt($quizobj, $accessmanager, $forcenew, $page, true);

// Check access.
if (!$quizobj->is_preview_user() && $messages) {
    $output = $PAGE->get_renderer('mod_quiz');
    print_error('attempterror', 'quiz', $quizobj->view_url(),
        $output->access_messages($messages));
}

if ($accessmanager->is_preflight_check_required($currentattemptid)) {
    // Need to do some checks before allowing the user to continue.
    $mform = $accessmanager->get_preflight_check_form(
        $quizobj->start_remote_attempt_url($page), $currentattemptid);

    if ($mform->is_cancelled()) {
        $accessmanager->back_to_view_page($PAGE->get_renderer('mod_quiz'));

    } else if (!$mform->get_data()) {

        // Form not submitted successfully, re-display it and stop.
        $PAGE->set_url($quizobj->start_remote_attempt_url($page));
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
        redirect($quizobj->attempt_remote_url($currentattemptid, $page));
    }
}

$user = get_remote_mapping_user();
$attemptremote = get_remote_quiz_start_attempt($quiz->id, $user[0]->id, $preview);
if($attemptremote->errorcode == 'attemptstillinprogress'){
    print_error('attemptstillinprogress', 'quiz', $quizobj->view_url());
}
$attempt = $attemptremote->attempt;
// Redirect to the attempt page.
redirect($quizobj->attempt_remote_url($attempt->id, $page));
