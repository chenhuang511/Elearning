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
 * This is the main renderer for the enrol section.
 *
 * @package    core_enrol
 * @copyright  2010 Sam Hemelryk
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once($CFG->dirroot . '/course/renderer.php');

/**
 * This is the core renderer
 *
 * @copyright 2010 Sam Hemelryk
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class core_remote_renderer extends plugin_renderer_base
{

    /**
     * Renders a user enrolment action
     * @param user_enrolment_action $icon
     * @return string
     */
    protected function render_user_enrolment_action(user_enrolment_action $icon)
    {
        return html_writer::link($icon->get_url(), $this->output->render($icon->get_icon()), $icon->get_attributes());
    }

    public function render_enrol_course($courses)
    {
        $content = html_writer::start_tag('div', array('id' => 'enrol-course-list', 'class' => 'container'));

        $chelper = new coursecat_helper();

        $coursecount = 0;
        foreach ($courses as $course) {
            $coursecount++;
            $classes = 'coursebox col-sm-3 ';

            // .coursebox
            $content .= html_writer::start_tag('div', array(
                'class' => $classes,
                'data-courseid' => $course->id,
            ));

            //$classes = trim('coursebox clearfix ');
            if ($chelper->get_show_courses() >= core_course_renderer::COURSECAT_SHOW_COURSES_EXPANDED) {
                $nametag = 'h3';
            } else {
                $nametag = 'div';
            }

            // thumbnail
            global $CFG;
            require_once($CFG->libdir . '/remote/lib.php');
            $thumbOjb = get_remote_course_thumb($course->remoteid);

            if ($thumbOjb) {
                $thumbnail = $thumbOjb[0]->thumbnail_image;

                if ($thumbnail) {
                    $imgthumb = html_writer::empty_tag('img', array('class' => 'course_img', 'src' => $thumbOjb[0]->thumbnail_image));
                    $thumblink = html_writer::link(new moodle_url('/course/remote/view.php', array('id' => $course->id)),
                        $imgthumb, array('class' => $course->visible ? '' : 'course-thumbnail'));

                    $content .= html_writer::tag('div', $thumblink, array('class' => 'course-image'));
                }

            }
                $content .= html_writer::start_tag('div', array('class' => 'course_content')); // start tag course_content
                    // course name
                    $coursename = $course->fullname;
                    $coursenamelink = html_writer::link(new moodle_url('/course/remote/view.php', array('id' => $course->id)),
                        $coursename, array('class' => $course->visible ? '' : 'dimmed'));
                    $content .= html_writer::tag($nametag, $coursenamelink, array('class' => 'coursename'));
                    // display course summary
                    if (isset($course->summary) && !empty($course->summary)) {
                        $content .= html_writer::start_tag('div', array('class' => 'summary'));
                        $content .= html_writer::tag('p', render_helper::truncate($course->summary, 60));
                        $content .= html_writer::end_tag('div'); // .summary
                    }

                    $content .= html_writer::start_tag('div', array('class' => 'btn-register'));
                    $content .= html_writer::tag('button', 'Học ngay', array('class' => 'btn-reg-now'));
                    $content .= html_writer::end_tag('div'); // .summary
                $content .= html_writer::end_tag('div'); //end tag course_content

            $content .= html_writer::end_tag('div'); // .moreinfo
            $content .= html_writer::tag('hr', '');

        }

        $content .= html_writer::end_tag('div');
        echo $content;
    }

    public function render_available_course($courses)
    {
        // Wrap frontpage course list in div container.
        $content = html_writer::start_tag('div', array('id' => 'frontpage-course-list', 'class' => 'container'));
        $content .= html_writer::start_tag('div', array('class' => 'row'));

        $chelper = new coursecat_helper();

        $coursecount = 0;
        foreach ($courses as $course) {
            $coursecount++;
            $classes = 'coursebox ';
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
                'class' => 'col-sm-3',
            ));
            $content .= html_writer::start_tag('div', array(
                'class' => $classes,
                'data-courseid' => $course->id,
            ));

            // thumbnail
            global $CFG;
            require_once($CFG->libdir . '/remote/lib.php');
            $thumbOjb = get_remote_course_thumb($course->remoteid);

            if ($thumbOjb) {
                $thumbnail = $thumbOjb[0]->thumbnail_image;

                if ($thumbnail) {
                    $imgthumb = html_writer::empty_tag('img', array('class' => 'course_img', 'src' => $thumbOjb[0]->thumbnail_image));
                    $thumblink = html_writer::link(new moodle_url('/course/remote/view.php', array('id' => $course->id)),
                        $imgthumb, array('class' => $course->visible ? '' : 'course-thumbnail'));

                    $content .= html_writer::tag('div', $thumblink, array('class' => 'course-image'));
                }

            }

            // course name
            $coursename = $course->fullname;
            $coursenamelink = html_writer::link(new moodle_url('/course/remote/view.php', array('id' => $course->id)),
                $coursename, array('class' => $course->visible ? '' : 'dimmed'));
            $content .= html_writer::tag($nametag, $coursenamelink, array('class' => 'coursename'));
            // display course summary
            if (isset($course->summary) && !empty($course->summary)) {
                $content .= html_writer::start_tag('div', array('class' => 'summary'));
                $content .= html_writer::tag('p', render_helper::truncate($course->summary, 60));
                $content .= html_writer::end_tag('div'); // .summary
            }

            $content .= html_writer::end_tag('div'); // .moreinfo
            $content .= html_writer::end_tag('div');
        }
        $content .= html_writer::end_tag('div');
        $content .= html_writer::end_tag('div');
        echo $content;
    }

    /**
     * @param $course
     * @param string $type can be 'available' or 'enrol' course
     */
    public function render_remote_course($course, $type = 'available')
    {
        if ($type == 'available') {
            $this->render_available_course($course);
        } else if ($type == 'enrol') {
            $this->render_enrol_course($course);
        }
    }

    public function render_course($course)
    {
        $tabname = array('Courseware', 'Course Info', 'Discussion', 'Progress');
        $courseinfo = $this->render_course_info($course);
        $tabcontens = array('<p>tab content 1</p>', $courseinfo, '<p>tab content 3</p>', '<p>tab content 4</p>');

        // div course-detail-tabs block contain all content of course
        $content = html_writer::start_tag('div', array('class' => 'course-detail-tabs'));
        // div coursetabs
        $content .= html_writer::start_tag('ul', array('id' => 'coursetabs', 'class' => 'nav nav-tabs', 'role' => 'tablist'));
        $content .= $this->render_tabs($tabname);
        $content .= html_writer::end_tag('ul'); // the end coursetabs
        $content .= html_writer::start_tag('div', array('id' => 'courseTabContent', 'class' => 'tab-content'));
        // them cac content tab tai day
        $content .= $this->render_tab_content($tabname, $tabcontens);
        $content .= html_writer::end_tag('div');
        $content .= html_writer::end_tag('div'); // the end course-detail-tabs
        echo $content;
    }

    private function render_tabs($tabnames)
    {
        $html = '';
        if ($tabnames) {
            $tabcount = 0;
            foreach ($tabnames as $tab) {
                $tabcount++;
                $classes = '';
                $expanded = 'false';

                if ($tabcount === 1) {
                    $classes .= 'active';
                    $expanded = 'true';
                }

                $tabid = 'tab-content-' . $tabcount;
                $tablink = html_writer::link(new moodle_url('#' . $tabid), $tab, array('role' => 'tab', 'data-toggle' => 'tab', 'aria-controls' => $tabid, 'aria-expanded' => $expanded));
                $html .= html_writer::tag('li', $tablink, array('role' => 'presentation', 'class' => $classes));
            }
        }

        return $html;
    }

    private function render_tab_content($tabnames, $contents)
    {
        $html = '';
        $tabcount = 0;
        foreach ($tabnames as $key => $tab) {
            $tabcount++;
            $classes = 'tab-pane fade ';
            if ($tabcount === 1) {
                $classes .= 'active in';
            }
            $tabid = 'tab-content-' . $tabcount;
            $html .= html_writer::start_tag('div', array('role' => 'tabpanel', 'id' => $tabid, 'class' => $classes, 'aria-labelledby' => $key . '-tab'));
            $html .= $contents[$key];
            $html .= html_writer::end_tag('div');
        }
        return $html;
    }

    private function render_course_info($course) {
        ob_start();
        include_once('render-course-info.php');
        return ob_get_clean();
    }
}

class render_helper
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
