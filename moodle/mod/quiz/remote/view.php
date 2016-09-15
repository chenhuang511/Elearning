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
    if (!$cm = get_coursemodule_from_id('quiz', $id)) {
        print_error('invalidcoursemodule');
    }
    if (!$course = $DB->get_record('course', array('id' => $cm->course))) {
        print_error('coursemisconf');
    }
} else {
    if (!$quiz = $DB->get_record('quiz', array('id' => $q))) {
        print_error('invalidquizid', 'quiz');
    }
    if (!$course = $DB->get_record('course', array('id' => $quiz->course))) {
        print_error('invalidcourseid');
    }
    if (!$cm = get_coursemodule_from_instance("quiz", $quiz->id, $course->id)) {
        print_error('invalidcoursemodule');
    }
}

// Check login and get context.
require_login($course, false, $cm);
$context = context_module::instance($cm->id);
require_capability('mod/quiz:view', $context);

// Cache some other capabilities we use several times.
$canattempt = has_capability('mod/quiz:attempt', $context);
$canreviewmine = has_capability('mod/quiz:reviewmyattempts', $context);
$canpreview = has_capability('mod/quiz:preview', $context);

$nonajax = optional_param('nonajax', true, PARAM_BOOL);
if (!has_capability('moodle/course:manageactivities', $context) && $nonajax == false) {
    $CFG->nonajax = false;
} else {
    $CFG->nonajax = true;
}

// Create an object to manage all the other (non-roles) access rules.
$timenow = time();
//$quiz = get_remote_quiz_by_id($cm->instance);
//$quizobj = new quiz($quiz, $cm, $course, true, true);
$quizobj = quiz::create($cm->instance, $USER->id);
$accessmanager = new quiz_access_manager($quizobj, $timenow,
    has_capability('mod/quiz:ignoretimelimits', $context, null, false));
$quiz = $quizobj->get_quiz();

// Trigger course_module_viewed event and completion.
quiz_view($quiz, $course, $cm, $context);

// Initialize $PAGE, compute blocks.
$PAGE->set_url('/mod/quiz/remote/view.php', array('id' => $cm->id));

// Create view object which collects all the information the renderer will need.
$viewobj = new mod_quiz_view_object();
$viewobj->accessmanager = $accessmanager;
$viewobj->canreviewmine = $canreviewmine;

// Get this user's attempts. User should map from host to hub
$user = get_remote_mapping_user();
$attempts = get_remote_user_attemps($quiz->remoteid, $user[0]->id, 'finished', true);
$lastfinishedattempt = end($attempts);
$unfinished = false;
$unfinishedattemptid = null;
$unfinishedremote = get_remote_user_attemps($quiz->remoteid, $user[0]->id, 'unfinished', true);
$unfinishedattempt = array_shift($unfinishedremote);

// Get settinglocal to call API handle_if_time_expired
$setting = array();
if($quiz->settinglocal){
    $fields =  array(
        'timeopen',
        'timeclose',
        'timelimit',
        'overduehandling',
        'graceperiod',
        'attempts',
        'grademethod'
    );
    $index = 0;
    foreach ($fields as $field){
        $setting["setting[$index][name]"] = $field;
        $setting["setting[$index][value]"] = $quiz->$field;
        $index++;
    }
}
if(!empty($unfinishedattempt)){
    // If the attempt is now overdue, deal with that - and pass isonline = false.
    // We want the student notified in this case.
    $unfinishedattempt = remote_handle_if_time_expired($quiz->remoteid, $unfinishedattempt->id, false, $setting);
    $attempts[] = $unfinishedattempt;
    $unfinished = $unfinishedattempt->state == quiz_attempt::IN_PROGRESS ||
        $unfinishedattempt->state == quiz_attempt::OVERDUE;

    if (!$unfinished) {
        $lastfinishedattempt = $attempts;
    }
    $unfinishedattemptid = $unfinishedattempt->id;
    $unfinishedattempt = null; // To make it clear we do not use this again.
}
$numattempts = count($attempts);
$viewobj->attempts = $attempts;
$viewobj->attemptobjs = array();
foreach ($attempts as $attempt) {
    $viewobj->attemptobjs[] = new quiz_attempt($attempt, $quiz, $cm, $course, false, true);
}

// Work out the final grade, checking whether it was overridden in the gradebook.
if (!$canpreview) {
    $mygrade = get_remote_user_best_grade($quiz->remoteid, $user[0]->id)->grade;
} else if ($lastfinishedattempt) {
    // Users who can preview the quiz don't get a proper grade, so work out a
    // plausible value to display instead, so the page looks right.
    $mygrade = quiz_rescale_grade($lastfinishedattempt->sumgrades, $quiz, false);
} else {
    $mygrade = null;
}

$mygradeoverridden = false;
$gradebookfeedback = '';

$grading_info = grade_get_grades($course->id, 'mod', 'quiz', $quiz->id, $USER->id);
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

/**
 * override mygrade follow local setting
 * @todo: Need check handel $mygrade
 */
$gradearray = array();
foreach ($viewobj->attemptobjs as $attemptobj) {
    $temp = quiz_rescale_grade($attemptobj->get_sum_marks(), $quiz, false);
    if($attemptobj->get_state() == quiz_attempt::FINISHED && ! is_null($temp)){
        $gradearray[] = quiz_rescale_grade($attemptobj->get_sum_marks(), $quiz, false);
    }
}

if($gradearray){
    switch ($quiz->grademethod) {
        case QUIZ_ATTEMPTFIRST:
            $viewobj->mygrade = $viewobj->mygrade = ($gradearray[0]) ? $gradearray[0] : null;
            break;

        case QUIZ_ATTEMPTLAST:
            $viewobj->mygrade = end($gradearray);
            break;

        case QUIZ_GRADEAVERAGE:
            $viewobj->mygrade = array_sum($gradearray)/count($gradearray);
            break;

        default:
        case QUIZ_GRADEHIGHEST:
            $viewobj->mygrade = max($gradearray);
            break;
    }
} else {
    $viewobj->mygrade = null;
}
/**
 *  end override
 */

$viewobj->moreattempts = $unfinished ||
    !$accessmanager->is_finished($numattempts, $lastfinishedattempt);
$viewobj->mygradeoverridden = $mygradeoverridden;
$viewobj->gradebookfeedback = $gradebookfeedback;
$viewobj->lastfinishedattempt = $lastfinishedattempt;
$viewobj->canedit = has_capability('mod/quiz:manage', $context);
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

// Determine wheter a start attempt button should be displayed.
$viewobj->quizhasquestions = $quizobj->has_questions($quiz->id);

$viewobj->preventmessages = array();
if (!$viewobj->quizhasquestions) {
    $viewobj->buttontext = '';
} else {
    if ($unfinished) {
        if ($canattempt) {
            $viewobj->buttontext = get_string('continueattemptquiz', 'quiz');
        } else if ($canpreview) {
            $viewobj->buttontext = get_string('continuepreview', 'quiz');
        }

    } else {
        if ($canattempt) {
            $viewobj->preventmessages = $viewobj->accessmanager->prevent_new_attempt(
                $viewobj->numattempts, $viewobj->lastfinishedattempt);
            if ($viewobj->preventmessages) {
                $viewobj->buttontext = '';
            } else if ($viewobj->numattempts == 0) {
                $viewobj->buttontext = get_string('attemptquiznow', 'quiz');
            } else {
                $viewobj->buttontext = get_string('reattemptquiz', 'quiz');
            }

        } else if ($canpreview) {
            $viewobj->buttontext = get_string('previewquiznow', 'quiz');
        }
    }

    // If, so far, we think a button should be printed, so check if they will be
    // allowed to access it.
    if ($viewobj->buttontext) {
        if (!$viewobj->moreattempts) {
            $viewobj->buttontext = '';
        } else if ($canattempt
            && $viewobj->preventmessages = $viewobj->accessmanager->prevent_access()) {
            $viewobj->buttontext = '';
        }
    }
}

$viewobj->showbacktocourse = ($viewobj->buttontext === '' &&
    course_get_format($course)->has_view_page());

if ($CFG->nonajax) {
    echo $OUTPUT->header();
}

if (isguestuser()) {
    // Guests can't do a quiz, so offer them a choice of logging in or going back.
    echo $output->view_page_guest($course, $quiz, $cm, $context, $viewobj->infomessages);
} else if (!isguestuser() && !($canattempt || $canpreview
        || $viewobj->canreviewmine)) {
    // If they are not enrolled in this course in a good enough role, tell them to enrol.
    echo $output->view_page_notenrolled($course, $quiz, $cm, $context, $viewobj->infomessages);
} else {
    echo $output->view_page($course, $quiz, $cm, $context, $viewobj);
}

if ($CFG->nonajax) {
    echo $OUTPUT->footer();
}
