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
 * This file is responsible for saving the results of a users survey and displaying
 * the final message.
 *
 * @package   mod_survey
 * @copyright 1999 onwards Martin Dougiamas  {@link http://moodle.com}
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(__FILE__) . '/../../../config.php');
require_once("/../lib.php");
require_once($CFG->dirroot . '/lib/remote/lib.php');
require_once($CFG->dirroot . '/course/remote/locallib.php');
require_once($CFG->dirroot . '/mod/survey/remote/locallib.php');


// Make sure this is a legitimate posting

if (!$formdata = data_submitted() or !confirm_sesskey()) {
    print_error('cannotcallscript');
}

$id = required_param('id', PARAM_INT);    // Course Module ID

if (!$cm = get_remote_course_module_by_cmid('survey', $id)) {
    print_error('invalidcoursemodule');
}

if (!$course = get_local_course_record($cm->course)) {
    print_error('coursemisconf');
}

$PAGE->set_url('/mod/survey/remote/save.php', array('id' => $id));
require_login($course, false, $cm);

$context = context_module::instance($cm->id);
require_capability('mod/survey:participate', $context);

if (!$survey = get_remote_survey_by_id($cm->instance)) {
    print_error('invalidsurveyid', 'survey');
}

$strsurveysaved = get_string('surveysaved', 'survey');

$PAGE->set_title($strsurveysaved);
$PAGE->set_heading($course->fullname);
echo $OUTPUT->heading($survey->name);

if (survey_already_done($survey->id, $USER->id)) {
    notice(get_string("alreadysubmitted", "survey"), get_local_referer(false));
    exit;
}

survey_save_answers($survey, $formdata, $course, $context);

$params = array(
    'context' => $context,
    'courseid' => $course->id,
    'other' => array('surveyid' => $survey->id)
);
$event = \mod_survey\event\response_submitted::create($params);
$event->trigger();

// Print the page and finish up.

notice(get_string("thanksforanswers", "survey", $USER->firstname), "$CFG->wwwroot/course/remote/view.php?id=$course->id");

exit;



