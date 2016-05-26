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
//$course = get_local_course_record($coursemodule->course);

// create new lesson
//$lesson = new lesson(get_remote_lesson_content($coursemodule->instance));
$lesson = get_remote_lesson_content($coursemodule->instance);
echo "<pre>";
print_r($lesson);
echo "</pre>";
die();

//require_login($course, false, $coursemodule);

// back to course page
if ($backtocourse) {
    redirect(new moodle_url('/course/view.php', array('id' => $course->remoteid)));
}

// Mark as viewed
$completion = new completion_info($course);
$completion->set_module_viewed($coursemodule);

if ($pageid !== null) {
    $url->param('pageid', $pageid);
}
//$PAGE->set_url($url);

// generate header html
$html .= $OUTPUT->header();

// start lesson content
$html .= $OUTPUT->box_start('c-lesson', "lesson_{$lesson->id}");

//show lesson information
if ($lesson) {

    $html .= $OUTPUT->heading($lesson->name);

    if (!empty($lesson->intro)) {
        $html .= html_writer::tag('p', $lesson->intro, array('class' => 'c-lesson-intro'));
    }
    

    if (empty($pageid)) {

        echo "<pre>";
        print_r(get_remote_lesson_page_content($lesson->id, null, true));
        echo "</pre>"; die();

        if (!$pageid = get_remote_lesson_page_content($lesson->id, array('returntype' => 'fieldtype', 'prevpageid' => 0))) {
            print_error('cannotfindfirstpage', 'lesson');
        }
    }
}

// end lesson content
$html .= $OUTPUT->box_end();

$html .= $OUTPUT->footer();

// show html content
echo $html;

