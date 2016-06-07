<?php

require_once('../../config.php');
require_once('locallib.php');
require_once($CFG->dirroot . '/remote/renderer.php');

$courseid = optional_param('id', 0, PARAM_INT);
$sectionid = optional_param('sectionid', 0, PARAM_INT);
$section = optional_param('section', 0, PARAM_INT);
$coursemodule = null;
$html = '';

$course = (empty($courseid)) ? null : get_local_course_record($courseid, true);

require_login($course);

$course = get_remote_course_content($course->remoteid);

$PAGE->set_title($course[0] ? $course[0]->name : "nccsoft vietnam");
$PAGE->set_heading($course[0] ? $course[0]->name : "nccsoft vietnam");

echo $OUTPUT->header();

$renderer = $PAGE->get_renderer('core_remote');
$renderer->render_course($course);


//$html .= $OUTPUT->box_start('course-detail', "course_detail_{$courseid}");
//
//if (isset($course[0]->name) && !empty($course[0]->name)) {
//    $html .= $OUTPUT->heading($course[0]->name);
//}
//if (isset($course[0]->summary) && !empty($course[0]->summary)) {
//    $html .= html_writer::tag('p', $course[0]->summary, array('class' => 'course-sumary'));
//}
//
//$html .= $OUTPUT->box_end();
//
//foreach ($course as $key => $section) {
//    if ($key == 0) continue;
//
//    $html .= $OUTPUT->box_start('course-section', "course_section_{$key}");
//
//    $attributes = array('title' => $section->name);
//    $link = html_writer::link("#course_section_$key", $section->name, $attributes);
//    $html .= $OUTPUT->heading($link, 3, 'course-section-name');
//
//    if (isset($section->summary) && !empty($section->summary)) {
//        $html .= html_writer::tag('p', $section->summary, array('class' => 'course-section-summary'));
//    }
//    $coursemodule = $section->modules;
//
//    foreach ($coursemodule as $module) {
//        $html .= $OUTPUT->box_start("course-{$module->modname}-box", "course_{$module->modname}_box_{$module->id}");
//        $html .= $OUTPUT->box_start('avatar', "course_{$module->modname}_box_{$module->id}");
//        $html .= html_writer::img($module->modicon, $module->name);
//        $html .= html_writer::span("&nbsp;");
//        $linktag = html_writer::tag('a', $module->name, array('href' => $CFG->wwwroot . "/mod/{$module->modname}/remote/view.php?id={$module->id}"));
//        $html .= html_writer::tag('span', $linktag, array('class' => $module->modname . '-intro'));
//        $html .= $OUTPUT->box_end();
//
//        $html .= html_writer::tag('div', $module->description, array('class' => $module->modname . '-intro'));
//        $html .= $OUTPUT->box_end();
//    }
//
//    $html .= $OUTPUT->box_end();
//}

echo $OUTPUT->footer();