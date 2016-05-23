<?php

require_once('../../config.php');
require_once('remotelib.php');

$courseid = optional_param('remoteid', 0, PARAM_INT);
$sectionid   = optional_param('sectionid', 0, PARAM_INT);
$section     = optional_param('section', 0, PARAM_INT);
$coursemodule = null;
$html = '';
$course = get_remote_course_content($courseid, ['function_name' => 'core_course_get_contents']);

$PAGE->set_title($course[0]?$course[0]->name:"nccsoft vietnam");
$PAGE->set_heading($course[0]?$course[0]->name:"nccsoft vietnam");

$html .= $OUTPUT->header();


$html .= $OUTPUT->box_start('course-detail', "course_detail_{$courseid}");

if(isset($course[0]->name) && !empty($course[0]->name)) {
    $html .= $OUTPUT->heading($course[0]->name);
}
if(isset($course[0]->summary) && !empty($course[0]->summary)) {
    $html .= html_writer::tag('p', $course[0]->summary, array('class' => 'course-sumary'));
}

$html .= $OUTPUT->box_end();

foreach($course as $key => $section) {
    if( $key == 0) continue;

    $html .= $OUTPUT->box_start('course-section', "course_section_{$key}");

    $attributes = array('title' => $section->name);
    $sectionurl = new moodle_url('/course/remote/view.php', array('section' => $key));
    $link = html_writer::link($sectionurl,$section->name,$attributes);

    $html .= $OUTPUT->heading($link,3,'course-section-name');

    if(isset($section->summary) && !empty($section->summary)) {
        $html .= html_writer::tag('p',$section->summary, array('class' => 'course-section-summary'));
	}
    $coursemodule = $section->modules;

    foreach($coursemodule as $module){
        $content = null;
        if($module->modname==="page") {
            $content = get_remote_page_content($module->instance, ['function_name' => 'local_mod_get_page_by_id']);
            $html .= $OUTPUT->box_start('course-page-box', "course_page_box_{$content->id}");
            if(isset($content->intro) && !empty($content->intro)) {
                $html .= html_writer::tag('div', $content->intro, array('class' => 'page-intro'));
            }
            $html .= $OUTPUT->box_end();
        }
        else if($module->modname==="quiz"){
            $content = get_remote_quiz_content($module->instance, ['function_name' => 'local_mod_get_quiz_by_id']);
            $html .= $OUTPUT->box_start('course-page-box', "course_page_box_{$content->id}");
            if(isset($content->name) && !empty($content->name)) {
                $html .= html_writer::tag('div', $content->name, array('class' => 'page-intro'));
            }
            $html .= $OUTPUT->box_end();
        }
        else if($module->modname==="label"){
            $content = get_remote_label_content($module->instance, ['function_name' => 'local_mod_get_label_by_id']);
            $html .= $OUTPUT->box_start('course-page-box', "course_page_box_{$content->id}");
            if(isset($content->intro) && !empty($content->intro)) {
                $html .= html_writer::tag('div', $content->intro, array('class' => 'page-intro'));
            }
            $html .= $OUTPUT->box_end();
        }
        else if($module->modname==="book"){
            $content = get_remote_book_content($module->instance, ['function_name' => 'local_mod_get_book_by_id']);
            $html .= $OUTPUT->box_start('course-page-box', "course_page_box_{$content->id}");
            if(isset($content->intro) && !empty($content->intro)) {
                $html .= html_writer::tag('div', $content->intro, array('class' => 'page-intro'));
            }
            $html .= $OUTPUT->box_end();
        }
    }

    $html .= $OUTPUT->box_end();
}

$html .= $OUTPUT->footer();

echo $html;