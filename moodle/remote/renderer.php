<?php

require_once($CFG->dirroot . '/course/renderer.php');

function frontpage_enrol_course($courses) {
    foreach ($courses as $key => $course) {
        echo "<div class='courses' style='border: 1px solid #f5f5f5; margin-top: 10px'>";
                echo "<table>";
        echo"<tr>";
        echo"<td rowspan='4'>";
        echo "<img src='https://www.google.com/intl/en_com/images/srpr/logo3w.png' style='padding:10px 10px' >";
        echo "</td>";
        echo "<td class='box1' style='color:dodgerblue; font-size:18px;width:60%;'>";
        echo "$course->fullname";
        echo"</td>";
        echo"</tr>";
        echo"<tr>";
        echo"<td class='box2' style='font-size:12px;text-align:left;'>";
        echo "$course->summary";
        echo"</td>";
        echo"<td>";
        echo "<button style='background-color: #00a3f4; color: white'>";
        echo "Hoc Ngay";
        echo "</button>";
        echo"</td>";
        echo"</tr>";
        echo "</table>";

        echo "</div>";
        echo "<hr style='border: solid 2px #f5f5f5'>";
    }
}

function frontpage_courses($courses)
{
    // Wrap frontpage course list in div container.
    $content = html_writer::start_tag('div', array('id' => 'frontpage-course-list', 'class' => 'container'));

    $chelper = new coursecat_helper();

    $coursecount = 0;
    foreach ($courses as $course) {
        $coursecount++;
        $classes = 'coursebox col-sm-3 ';
        $classes .= ($coursecount % 2) ? 'odd' : 'even';
        if ($coursecount == 1) {
            $classes .= ' first';
        }
        if ($coursecount >= count($courses)) {
            $classes .= ' last';
        }

        //$classes = trim('coursebox clearfix ');
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
            $content .= html_writer::tag('p', ncc_extend_plugin::truncate($course->summary, 60));
            $content .= html_writer::end_tag('div'); // .summary
        }

        $content .= html_writer::end_tag('div'); // .moreinfo
    }
    $content .= html_writer::end_tag('div');
    return $content;
}

class ncc_extend_plugin
{
    public static function truncate($text, $limit)
    {
        $strings = $text;
        if (strlen($text) > $limit) {
            $words = str_word_count($text, 2);
            $pos = array_keys($words);
            if (sizeof($pos) > $limit) {
                $text = substr($text, 0, $pos[$limit]) . '...';
            }
            return $text;
        }
        return $text;
    }
}