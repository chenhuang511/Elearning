<?php

require_once($CFG->dirroot . '/course/renderer.php');

function frontpage_courses($courses)
{
    // Wrap frontpage course list in div container.
    $content = html_writer::start_tag('div', array('id' => 'frontpage-course-list'));
    $coursecount = 0;

    $chelper = new coursecat_helper();

    foreach ($courses as $course) {
        $coursecount++;
        $classes = ($coursecount % 2) ? 'odd' : 'even';
        if ($coursecount == 1) {
            $classes .= ' first';
        }
        if ($coursecount >= count($courses)) {
            $classes .= ' last';
        }

        $classes = trim('coursebox clearfix ');
        if ($chelper->get_show_courses() >= core_course_renderer::COURSECAT_SHOW_COURSES_EXPANDED) {
            $nametag = 'h3';
        } else {
            $classes .= ' collapsed';
            $nametag = 'div';
        }

        // .coursebox
        $content .= html_writer::start_tag('div', array(
            'class' => $classes,
            'data-courseid' => $course->id,
        ));

        // course name
        $coursename = $course->fullname;
        $coursenamelink = html_writer::link(new moodle_url('/course/remote/view.php', array('id' => $course->id)),
            $coursename, array('class' => $course->visible ? '' : 'dimmed'));
        $content .= html_writer::tag($nametag, $coursenamelink, array('class' => 'coursename'));
        // display course summary
        if (isset($course->summary) && !empty($course->summary)) {
            $content .= html_writer::start_tag('div', array('class' => 'summary'));
            $content .= html_writer::tag('p', $course->summary);
            $content .= html_writer::end_tag('div'); // .summary
        }

        $content .= html_writer::end_tag('div'); // .moreinfo
    }
    $content .= html_writer::end_tag('div');
    return $content;
}