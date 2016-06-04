<?php
/**
 * Created by PhpStorm.
 * User: vanha
 * Date: 27/05/2016
 * Time: 2:52 CH
 */

require_once(dirname(__FILE__) . '/../../../config.php');
require_once($CFG->dirroot . '/course/remote/locallib.php');
require_once($CFG->dirroot.'/mod/quiz/remote/locallib.php');

require_once($CFG->libdir.'/gradelib.php');
require_once($CFG->dirroot.'/mod/quiz/locallib.php');
require_once($CFG->libdir . '/completionlib.php');
require_once($CFG->dirroot . '/course/format/lib.php');

$id = optional_param('id', 0, PARAM_INT); // Course Module ID, or ...
$q = optional_param('q',  0, PARAM_INT);  // Quiz ID.

if ($id) {
    if (!$cm = get_remote_course_module($id)) {
        print_error('invalidcoursemodule');
    }
    if (!$course = $DB->get_record('course', array('remoteid' => $cm->course))) {
        print_error('coursemisconf');
    }
} else {
    if (!$quiz = get_remote_quiz_by_id($q)) {
        print_error('invalidquizid', 'quiz');
    }
    if (!$course = $DB->get_record('course', array('remoteid' => $quiz->course))) {
        print_error('invalidcourseid');
    }
    if (!$cm = get_remote_coursemodule_from_instance("quiz", $quiz->id)) {
        print_error('invalidcoursemodule');
    }
}

// TODO: Check login and get context.
$context = context_module::instance($cm->id);
echo 1; die;

// Cache some other capabilities we use several times.
$canattempt = true;
$canreviewmine = true;
$canpreview = true;

// Create an object to manage all the other (non-roles) access rules.
$timenow = time();
//$quizobj = quiz::create($cm->instance, $USER->id);
// accessmanager.php: load_quiz_and_settings
$rules= get_remote_quiz_access_information($cm->instance);
$quiz = get_remote_quiz_by_id($cm->instance);
$quizobj = new quiz($quiz, $cm, $course, false); //false: not get context
$accessmanager = new quiz_access_manager($quizobj, $timenow,
    false); // set has_capability('mod/quiz:ignoretimelimits', $context, null, false) = false
$quiz = $quizobj->get_quiz();

// @TODO: Trigger course_module_viewed event and completion.

// Initialize $PAGE, compute blocks.
$PAGE->set_url('/mod/quiz/remote/view.php', array('id' => $cm->id));

// Create view object which collects all the information the renderer will need.
$viewobj = new mod_quiz_view_object();
$viewobj->accessmanager = $accessmanager;
$viewobj->canreviewmine = $canreviewmine;

// Get this user's attempts.
//get user mapping
$user = get_remote_user_mapping_userid();
$attempts = get_remote_user_attemps($quiz->id, $user[0]->id, 'finished', true)->attempts;
$lastfinishedattempt = end($attempts);
$unfinished = false;
$unfinishedattemptid = null;
$unfinishedattempt = get_remote_user_attemps($quiz->id, $user[0]->id, 'unfinished', true)->attempts;
if($unfinishedattempt[0]){
    $attempts = $unfinishedattempt;

    // @TODO: $quizobj->create_attempt_object($unfinishedattempt)->handle_if_time_expired(time(), false);

    $unfinished = $unfinishedattempt[0]->state == quiz_attempt::IN_PROGRESS ||
        $unfinishedattempt[0]->state == quiz_attempt::OVERDUE;
    if (!$unfinished) {
        $lastfinishedattempt = $attempts;
    }
    $unfinishedattemptid = $unfinishedattempt[0]->id;
    $unfinishedattempt = null; // To make it clear we do not use this again.
}
$numattempts = count($attempts);

$viewobj->attempts = $attempts;
$viewobj->attemptobjs = array();
foreach ($attempts as $attempt) {
    $viewobj->attemptobjs[] = new quiz_attempt($attempt, $quiz, $cm, $course, false);
}

// Work out the final grade, checking whether it was overridden in the gradebook.
if (!$canpreview) {
    $mygrade = get_remote_user_best_grade($quiz->id, $user[0]->id);
} else if ($lastfinishedattempt) {
    // Users who can preview the quiz don't get a proper grade, so work out a
    // plausible value to display instead, so the page looks right.
    $mygrade = quiz_rescale_grade($lastfinishedattempt->sumgrades, $quiz, false);
} else {
    $mygrade = null;
}

$mygradeoverridden = false;
$gradebookfeedback = '';

// @TODO: ????
$grading_info = grade_get_grades($course->id, 'mod', 'quiz', $quiz->id, $user[0]->id);
if (!empty($grading_info->items)) {
    $item = $grading_info->items[0];
    if (isset($item->grades[$USER->id])) {
        $grade = $item->grades[$USER->id];

        if ($grade->overridden) {
            $mygrade = $grade->grade + 0; // Convert to number.
            $mygradeoverridden = true;
        }
        if (!empty($grade->str_feedback)) {
            $gradebookfeedback = $grade->str_feedback;
        }
    }
}

$title = $course->shortname . ': ' . format_string($quiz->name);
$PAGE->set_title($title);
$PAGE->set_heading($course->fullname);
$output = $PAGE->get_renderer('mod_quiz');

// Print table with existing attempts.
if ($attempts) {
    // Work out which columns we need, taking account what data is available in each attempt.
    list($someoptions, $alloptions) = quiz_get_combined_reviewoptions($quiz, $attempts);

    $viewobj->attemptcolumn  = $quiz->attempts != 1;

    $viewobj->gradecolumn    = $someoptions->marks >= question_display_options::MARK_AND_MAX &&
        quiz_has_grades($quiz);
    $viewobj->markcolumn     = $viewobj->gradecolumn && ($quiz->grade != $quiz->sumgrades);
    $viewobj->overallstats   = $lastfinishedattempt && $alloptions->marks >= question_display_options::MARK_AND_MAX;

    $viewobj->feedbackcolumn = quiz_has_feedback($quiz) && $alloptions->overallfeedback;
}

$viewobj->timenow = $timenow;
$viewobj->numattempts = $numattempts;
$viewobj->mygrade = $mygrade;
$viewobj->moreattempts = $unfinished ||
    !$accessmanager->is_finished($numattempts, $lastfinishedattempt);
$viewobj->mygradeoverridden = $mygradeoverridden;
$viewobj->gradebookfeedback = $gradebookfeedback;
$viewobj->lastfinishedattempt = $lastfinishedattempt;
//$viewobj->canedit = has_capability('mod/quiz:manage', $context);
$viewobj->canedit = true;
$viewobj->editurl = new moodle_url('/mod/quiz/edit.php', array('cmid' => $cm->id));
$viewobj->backtocourseurl = new moodle_url('/course/view.php', array('id' => $course->id));
$viewobj->startattempturl = $quizobj->start_attempt_url();

if ($accessmanager->is_preflight_check_required($unfinishedattemptid)) {
    $viewobj->preflightcheckform = $accessmanager->get_preflight_check_form(
        $viewobj->startattempturl, $unfinishedattemptid);
}
$viewobj->popuprequired = $accessmanager->attempt_must_be_in_popup();
$viewobj->popupoptions = $accessmanager->get_popup_options();

// Display information about this quiz.
$viewobj->infomessages = $viewobj->accessmanager->describe_rules();
if ($quiz->attempts != 1) {
    $viewobj->infomessages[] = get_string('gradingmethod', 'quiz',
        quiz_get_grading_option_name($quiz->grademethod));
}
//var_dump($viewobj);die;

echo $OUTPUT->header();
echo $output->view_page($course, $quiz, $cm, null, $viewobj);
echo $OUTPUT->footer();