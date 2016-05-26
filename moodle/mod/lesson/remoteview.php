<?php

require_once(dirname(__FILE__) . '/../../config.php');
require_once($CFG->dirroot . '/mod/lesson/locallib.php');
require_once($CFG->dirroot . '/course/remote/remotelib.php');
require_once($CFG->dirroot . '/mod/lesson/view_form.php');
require_once($CFG->libdir . '/completionlib.php');
require_once($CFG->libdir . '/grade/constants.php');

$id = required_param('id', PARAM_INT);             // Course Module ID
$pageid = optional_param('pageid', null, PARAM_INT);   // Lesson Page ID
$edit = optional_param('edit', -1, PARAM_BOOL);
$userpassword = optional_param('userpassword', '', PARAM_RAW);
$backtocourse = optional_param('backtocourse', false, PARAM_RAW);

$html = '';


// get course module from hub
$coursemodule = get_remote_course_module($id);

// get course to db local
$course = $DB->get_record('course', array('remoteid' => $coursemodule->course), '*', MUST_EXIST);

// create new lesson
$lesson = new lesson(get_remote_lesson_content($coursemodule->instance));

//require_login($course, false, $coursemodule);

// back to course page
if ($backtocourse) {
    redirect(new moodle_url('/course/view.php', array('id' => $course->remoteid)));
}

// Apply overrides.
//$lesson->update_effective_access($USER->id);

// Mark as viewed
//$completion = new completion_info($course);
//$completion->set_module_viewed($coursemodule);

if ($pageid !== null) {
    $url->param('pageid', $pageid);
}
$PAGE->set_url($url);

$lessonoutput = $PAGE->get_renderer('mod_lesson');

// generate header html
$html .= $lessonoutput->header($lesson, $coursemodule, '', false, null, get_string('notavailable'));

if ($lesson->deadline != 0 && time() > $lesson->deadline) {
    $html .= $lessonoutput->lesson_inaccessible(get_string('lessonclosed', 'lesson', userdate($lesson->deadline)));
} else {
    $html .= $lessonoutput->lesson_inaccessible(get_string('lessonopen', 'lesson', userdate($lesson->available)));
}

// start lesson content
$html .= $OUTPUT->box_start('c-lesson', "lesson_{$lesson->id}");

//show lesson information
if ($lesson) {
    if (!empty($lesson->name)) {
        $html .= $OUTPUT->heading($lesson->name);
    }
    if (!empty($lesson->intro)) {
        $html .= html_writer::tag('p', $lesson->intro, array('class' => 'c-lesson-intro'));
    }

    if (empty($pageid)) {
        if (!$pageid = get_remote_lesson_page_content($lesson->id, array('returntype' => 'fieldtype', 'prevpageid' => 0))) {
            print_error('cannotfindfirstpage', 'lesson');
        }
    }

    if ($pageid != LESSON_EOL) {
        $page = $lesson->load_page($pageid);
    }
}

// end lesson content
$html .= $OUTPUT->box_end();

$html .= $OUTPUT->footer();

// show html content
echo $html;

