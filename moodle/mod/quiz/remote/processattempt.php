<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * This page deals with processing responses during an attempt at a quiz.
 *
 * People will normally arrive here from a form submission on attempt.php or
 * summary.php, and once the responses are processed, they will be redirected to
 * attempt.php or summary.php.
 *
 * This code used to be near the top of attempt.php, if you are looking for CVS history.
 *
 * @package   mod_quiz
 * @copyright 2009 Tim Hunt
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(__FILE__) . '/../../../config.php');
require_once($CFG->dirroot . '/mod/quiz/locallib.php');

// Remember the current time as the time any responses were submitted
// (so as to make sure students don't get penalized for slow processing on this page).
$timenow = time();

// Get submitted parameters.
$attemptid     = required_param('attempt',  PARAM_INT);
$thispage      = optional_param('thispage', 0, PARAM_INT);
$nextpage      = optional_param('nextpage', 0, PARAM_INT);
$previous      = optional_param('previous',      false, PARAM_BOOL);
$next          = optional_param('next',          false, PARAM_BOOL);
$finishattempt = optional_param('finishattempt', false, PARAM_BOOL);
$timeup        = optional_param('timeup',        0,      PARAM_BOOL); // True if form was submitted by timer.
$scrollpos     = optional_param('scrollpos',     '',     PARAM_RAW);

$attemptobj = quiz_attempt::create($attemptid);

// Set $nexturl now.
if ($next) {
    $page = $nextpage;
} else if ($previous && $thispage > 0) {
    $page = $thispage - 1;
} else {
    $page = $thispage;
}
if ($page == -1) {
    $nexturl = $attemptobj->summary_url();
} else {
    $nexturl = $attemptobj->attempt_url(null, $page);
    if ($scrollpos !== '') {
        $nexturl->param('scrollpos', $scrollpos);
    }
}

// Check login.
require_login($attemptobj->get_course(), false, $attemptobj->get_cm());
require_sesskey();

// Check that this attempt belongs to this user.
$user = get_remote_mapping_user();
if ($attemptobj->get_userid() != $USER->id) {
    throw new moodle_quiz_exception($attemptobj->get_quizobj(), 'notyourattempt');
}

// Check capabilities.
if (!$attemptobj->is_preview_user()) {
    $attemptobj->require_capability('mod/quiz:attempt');
}

// If the attempt is already closed, send them to the review page.
if ($attemptobj->is_finished()) {
    throw new moodle_quiz_exception($attemptobj->get_quizobj(),
            'attemptalreadyclosed', null, $attemptobj->review_url());
}

$cm = $attemptobj->get_cm();
$context = context_module::instance($cm->id);
if (!has_capability('moodle/course:manageactivities', $context)) {
    $CFG->nonajax = false;
} else {
    $CFG->nonajax = true;
}

$data = array();
$i = 0;
foreach ($_POST as $key => $value) {
    $data["data[$i][name]"]=$key;
    $data["data[$i][value]"]=$value;
    $i++;
}

// get quiz local setting if isset quiz->settinglocal
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

// Process the attempt, getting the new status for the attempt.
$status = get_mod_quiz_process_attempt($attemptid, $data, $finishattempt, $timeup, $setting);
$status = $status->state;

// update completion state if done this attempt
if($status == quiz_attempt::FINISHED){
    $completion = new completion_info($course);
    if ($completion->is_enabled()) {
        if($cm->completion != COMPLETION_TRACKING_MANUAL){
            $data = $completion->get_data($cm, false);
            $data->viewed = COMPLETION_VIEWED;
            $completion->internal_set_data($cm, $data);
            $completion->update_state($cm, COMPLETION_COMPLETE);
        }
    }
}

if ($status == quiz_attempt::OVERDUE) {
    redirect($attemptobj->summary_url());
} else if ($status == quiz_attempt::IN_PROGRESS) {
    redirect($nexturl);
} else {
    // Attempt abandoned or finished.
    redirect($attemptobj->review_url());
}
