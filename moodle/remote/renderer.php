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
    public function __construct()
    {

    }

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
        global $CFG, $USER;
        require_once($CFG->libdir . '/remote/lib.php');
        require_once($CFG->dirroot . '/course/remote/locallib.php');

        $hubuserid = $USER->id;

        $mycoursecompletion = '';

        $hubuser = get_remote_mapping_user($hubuserid);

        if ($hubuser) {
            $hubuserid = $hubuser[0]->id;
        }

        $coursecompletionids = get_remote_list_course_completion($hubuserid);
        $countcompletion = 0;

        if (!$coursecompletionids) {
            $countcompletion = count($coursecompletionids);
            $mycoursecompletion = 'Bạn chưa hoàn thành khóa học nào';
        }

        // start - enrol course list
        $content = html_writer::start_tag('div', array('id' => 'enrol-course-list', 'class' => 'container'));

        // start - container avaiable course
        $content .= html_writer::start_tag('div', array('class' => 'container-available-course col-sm-9'));

        // profile block
        $content .= $this->render_profile_info($USER, $countcompletion);
        //profile block

        $tabnames = array('Chương trình học', 'Khóa học đã hoàn thành');
        // div coursetabs
        $content .= html_writer::start_tag('ul', array('id' => 'enroltabs', 'class' => 'nav nav-tabs', 'role' => 'tablist'));
        $content .= $this->render_tabs($tabnames);
        $content .= html_writer::end_tag('ul'); // the end coursetabs
        $content .= html_writer::start_tag('div', array('id' => 'courseTabContent', 'class' => 'tab-content'));

        $mylearningplan = '';

        foreach ($courses as $course) {
            $course->iscompletion = 0; // default course
            $course->completion = get_remote_course_completion($course, $hubuserid);

            $classes = 'coursebox clearfix';

            $thumbOjb = get_remote_course_thumb($course->remoteid);
            if ($thumbOjb) {
                $course->thumbnail = $thumbOjb[0]->thumbnail_image;
            }

            if ($coursecompletionids) {
                foreach ($coursecompletionids as $completion) {
                    if ($course->remoteid == $completion->course) {
                        $course->iscompletion = 1;
                        $mycoursecompletion .= $this->format_course($course, $classes);
                    }
                }
            }

            if (isset($course->iscompletion) && $course->iscompletion === 0) {
                $mylearningplan .= $this->format_course($course, $classes);
            }
        }

        $tabcontents = array($mylearningplan, $mycoursecompletion);
        // them cac content tab tai day
        $content .= $this->render_tab_content($tabnames, $tabcontents);
        $content .= html_writer::end_tag('div');
        // end tabs

        $content .= html_writer::end_tag('div'); // end -container available course

        $content .= html_writer::start_tag('div', array('class' => 'right-block col-sm-3'));

        $proposecourses = array_slice($courses, 0, 3);
        $content .= $this->render_navigation_course($proposecourses, 'Khóa học đề xuất');

        $content .= html_writer::end_tag('div'); // end - right-block
        $content .= html_writer::end_tag('div'); // end - enrol course list
        echo $content;
    }

    public function render_available_course($courses)
    {
        global $CFG;
        require_once($CFG->libdir . '/remote/lib.php');

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
            $thumbOjb = get_remote_course_thumb($course->remoteid);
            if ($thumbOjb) {
                $thumbnail = $thumbOjb[0]->thumbnail_image;
                if ($thumbnail) {
                    $imgthumb = html_writer::empty_tag('img', array('class' => 'course_img', 'src' => $thumbOjb[0]->thumbnail_image));
                    $thumblink = html_writer::link($this->get_view_course_url($course),
                        $imgthumb, array('class' => $course->visible ? '' : 'course-thumbnail'));
                    $content .= html_writer::tag('div', $thumblink, array('class' => 'course-image'));
                }
            }
            // course name
            $coursename = $course->fullname;
            $coursenamelink = html_writer::link($this->get_view_course_url($course),
                $coursename, array('class' => $course->visible ? '' : 'dimmed'));
            $content .= html_writer::tag($nametag, $coursenamelink, array('class' => 'coursename'));
            // display course summary
            if (isset($course->summary) && !empty($course->summary)) {
                $content .= html_writer::start_tag('div', array('class' => 'summary'));
                $content .= remote_render_helper::token_truncate($course->summary, 200);
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
    public function render_remote_course($courses, $type = 'available')
    {
        if ($type == 'available') {
            $this->render_available_course($courses);
        } else if ($type == 'enrol') {
            $this->render_enrol_course($courses);
        }
    }

    public function render_course_detail($course)
    {
        $courseid = $course['courseid'];
        $tabname = array(
            'coursewaretab' => 'Tổng quan',
            'courseinfotab' => 'Thông tin',
            'forumtab' => 'Diễn đàn',
            'wikitab' => 'Wiki',
            'chattab' => 'Chat',
            'processtab' => 'Tiến độ học'
        );
        $courseinfo = $this->render_course_info($courseid);
        $coursewaretab = $this->render_courseware($course);
        $courseprogress = $this->render_course_progress($course);
        $courseforumtab = $this->render_course_forum($course);
        $coursewikitab = $this->render_course_wiki($course);
        $chattab = $this->render_course_chat();
        $tabcontens = array($coursewaretab, $courseinfo, $courseforumtab, $coursewikitab, $chattab, $courseprogress);

        // div course-detail-tabs block contain all content of course
        $content = html_writer::start_tag('div', array('class' => 'course-detail-tabs container'));
        $content .= html_writer::link(new moodle_url('#'), '<i class="fa fa-pencil-square-o" aria-hidden="true"></i> Bài viết mới', array('id' => 'newpost', 'class' => 'btn btn-primary btn-newpost', 'style' => 'display: none;'));
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

    private function render_profile_info($user, $countcompletion)
    {
        // start create block instance connect to mahara
        global $CFG;
        require_once $CFG->dirroot . "/blocks/moodleblock.class.php";
        require_once $CFG->dirroot . "/blocks/mnet_hosts/block_mnet_hosts.php";
        $block = new block_mnet_hosts;
        // end
        $html = '';
        // start profile block
        $html .= html_writer::start_div('el-profile-block clearfix');
        $html .= html_writer::start_div('col-sm-7 col-md-7 el-profile-info');
        $html .= html_writer::label($user->username, '', true, array('class' => 'profile-info-username'));
        $html .= html_writer::link(new moodle_url("/user/edit.php", array('userid' => $user->id, 'returnto' => 'profile')), '<i class="fa fa-pencil-square-o" aria-hidden="true"></i>' . 'Chỉnh sửa thông tin cá nhân', array('class' => 'profile-info-link'));
        $html .= html_writer::end_div(); // end profile info
        $html .= html_writer::start_div('col-sm-5 col-md-5 el-certificate');
        $html .= html_writer::empty_tag('img', array('class' => 'certificate-img', 'src' => 'theme/tecapro/pix/certificate_icon.png'));
        $html .= html_writer::start_div('certificate-info');
        $cername = '<strong>Thông tin chung</strong>';
        if ($countcompletion > 0) {
            $cerpoint = '<br> ' . $countcompletion . ' khóa học đã hoàn thành';
        } else {
            $cerpoint = '<br> ' . 'Chưa có khóa học hoàn thành';
        }
        $cerhelp = '';
        // create link connect mahara
        if (!empty($mahara = $block->get_content(false, 'mahara', false)->items)) {
            foreach ($mahara as $link) {
                $cerhelp .= '</br>' . $link;
            }
        }
        //end
        $html .= html_writer::tag('p', $cername . $cerpoint . $cerhelp);
        $html .= html_writer::end_div(); // end certificate info
        $html .= html_writer::end_div(); // end profile certificate
        $html .= html_writer::end_div(); // end profile block
        return $html;
    }

    private function format_course($course, $classes)
    {
        $html = '';
        // begin course box
        $html .= html_writer::start_tag('article', array('class' => $classes, 'data-courseid' => $course->id));
        $html .= html_writer::start_tag('header', array('class' => 'coursename'));
        // course name
        $coursename = $course->fullname;
        $coursenamelink = html_writer::link($this->get_view_course_url($course),
            $coursename, array('class' => $course->visible ? '' : 'dimmed'));
        if ((isset($course->enablecompletion) && $course->enablecompletion != 0) && (isset($course->iscompletion) && $course->iscompletion == 0)) {
            $progress = html_writer::span($course->completion . '%', 'badge el-badge');
            $html .= html_writer::tag('h3', $coursenamelink . $progress);
        } else {
            $html .= html_writer::tag('h3', $coursenamelink);
        }

        $html .= html_writer::end_tag('header'); // end header

        $html .= html_writer::start_tag('section', array('class' => 'row'));
        // thumbnail
        if (isset($course->thumbnail) && $course->thumbnail) {
            $imgthumb = html_writer::empty_tag('img', array('class' => 'course-img', 'src' => $course->thumbnail));
            $thumblink = html_writer::link($this->get_view_course_url($course),
                $imgthumb, array('class' => $course->visible ? '' : 'course-thumbnail'));

            $html .= html_writer::tag('div', $thumblink, array('class' => 'course-image col-sm-3'));
        }

        // display course summary
        $html .= html_writer::start_tag('div', array('class' => 'summary col-sm-9')); //start tag summary
        if (isset($course->summary) && !empty($course->summary)) {
            $html .= html_writer::tag('p', remote_render_helper::token_truncate($course->summary, 200));
        }
        // display button
        if (!$course->iscompletion) {
            $html .= html_writer::link($this->get_view_course_url($course),
                'Học ngay', array('class' => 'btn btn-primary btn-el-reg'));
        }
        $html .= html_writer::end_tag('div'); // .summary

        $html .= html_writer::end_tag('section'); // end section
        $html .= html_writer::end_tag('article'); // end course box

        return $html;
    }

    private function render_navigation_course($courses, $heading)
    {
        $html = '';
        $i = 0;
        $html .= html_writer::start_div('el-sidebar');
        $html .= html_writer::tag('h3', $heading, array('class' => 'el-sidebar-heading'));
        foreach ($courses as $course) {
            $i++;
            $classes = 'coursebox';
            $thumbOjb = get_remote_course_thumb($course->remoteid);

            // start article - coursebox
            $html .= html_writer::start_tag('article', array('class' => $classes, 'data-courseid' => $course->id));
            // start section block
            $html .= html_writer::start_tag('section');

            // course name
            $coursename = $course->fullname;
            $coursenamelink = html_writer::link($this->get_view_course_url($course),
                $coursename, array('class' => $course->visible ? '' : 'dimmed'));
            $html .= html_writer::tag('div', $coursenamelink, array('class' => 'coursename'));

            // thumbnail
            if ($thumbOjb) {
                $thumbnail = $thumbOjb[0]->thumbnail_image;

                if ($thumbnail) {
                    $imgthumb = html_writer::empty_tag('img', array('class' => 'course-img', 'src' => $thumbnail));
                    $thumblink = html_writer::link($this->get_view_course_url($course),
                        $imgthumb, array('class' => $course->visible ? '' : 'course-thumbnail'));

                    $html .= html_writer::tag('div', $thumblink, array('class' => 'course-image'));
                }
            }

            $html .= html_writer::end_tag('section');
            $html .= html_writer::end_tag('article'); //end tag coursebox
        }
        $html .= html_writer::start_div('el-buttons'); // buttons
        $html .= html_writer::link(new moodle_url('course.php'), 'xem tất cả ' . '<i class="fa fa-angle-right" aria-hidden="true"></i>', array('class' => 'el-viewall'));
        $html .= html_writer::end_div(); // end buttons
        $html .= html_writer::end_div();
        return $html;
    }

    private function render_tabs($tabnames)
    {
        $html = '';
        if ($tabnames) {
            $tabcount = 0;
            foreach ($tabnames as $key => $tab) {
                $tabcount++;
                $classes = '';
                $expanded = 'false';

                if ($tabcount === 1) {
                    $classes .= 'active';
                    $expanded = 'true';
                }

                $tabid = 'tab-content-' . $tabcount;
                $arr = array('id' => $key, 'role' => 'tab', 'data-toggle' => 'tab', 'aria-controls' => $tabid, 'aria-expanded' => $expanded);
                $tablink = html_writer::link(new moodle_url('#' . $tabid), $tab, $arr);
                $html .= html_writer::tag('li', $tablink, array('role' => 'presentation', 'class' => $classes));
            }
        }

        return $html;
    }

    private function render_tab_content($tabnames, $contents)
    {
        $html = '';
        $tabcount = 0;
        $index = 0;
        foreach ($tabnames as $key => $tab) {
            $tabcount++;
            $classes = 'tab-pane fade ';
            if ($tabcount === 1) {
                $classes .= 'active in';
            }
            $tabid = 'tab-content-' . $tabcount;
            $html .= html_writer::start_tag('div', array('role' => 'tabpanel', 'id' => $tabid, 'class' => $classes, 'aria-labelledby' => $key . '-tab'));
            $html .= $contents[$index];
            $index++;
            $html .= html_writer::end_tag('div');
        }
        return $html;
    }

    private function render_courseware($course)
    {
        global $CFG;

        $html = html_writer::start_tag('div', array('class' => 'courseware-block'));
        $html .= html_writer::start_tag('div', array('class' => 'section-courseware'));
        $html .= html_writer::start_tag('div', array('class' => 'col-sm-3 courseware-menu'));
        $html .= $this->render_module_menu($course);
        $html .= html_writer::end_tag('div');
        $html .= html_writer::start_tag('div', array('class' => 'col-sm-9 course-content-block'));
        $html .= html_writer::start_tag('div', array('id' => 'loading', 'class' => 'clearfix', 'style' => 'display: none'));
        $html .= html_writer::empty_tag('img', array('src' => $CFG->wwwroot . '/loading.gif', 'width' => '40px'));
        $html .= html_writer::end_tag('div');
        $html .= $this->render_module_content($course);
        $html .= html_writer::end_tag('div');
        $html .= html_writer::end_tag('div');
        $html .= html_writer::end_tag('div');

        return $html;
    }

    private function render_course_info($courseid)
    {
        $courseinfo = get_remote_course_info($courseid);
        ob_start();
        include_once('include/render-course-info.php');
        return ob_get_clean();
    }

    private function render_module_menu($course)
    {
        ob_start();
        include_once('include/render-course-detail-menu.php');
        return ob_get_clean();
    }

    private function render_module_content($course)
    {
        ob_start();
        include_once('include/render-course-detail-content.php');
        return ob_get_clean();
    }

    private function render_course_progress($course)
    {
        ob_start();
        include_once('include/render-course-progress.php');
        return ob_get_clean();
    }

    private function render_course_forum($course)
    {
        ob_start();
        include_once('include/render-course-forum.php');
        return ob_get_clean();
    }

    private function render_course_wiki($course)
    {
        ob_start();
        include_once('include/render-course-wiki.php');
        return ob_get_clean();
    }

    private function render_course_chat()
    {
        ob_start();
        include_once('include/render-course-chat.php');
        return ob_get_clean();
    }

    private function get_view_course_url($course)
    {
        global $CFG;

        context_helper::preload_course($course->id);
        $context = context_course::instance($course->id, MUST_EXIST);

        if (isset($CFG->nonajax) && !has_capability('moodle/course:manageactivities', $context)) {
            if ($CFG->nonajax == false)
                return new moodle_url($CFG->loginredir . '/?', array('id' => $course->id));
        }

        return new moodle_url($CFG->wwwroot . '/course/view.php', array('id' => $course->id));
    }
}

class remote_render_helper
{
    public static function token_truncate($string, $width)
    {
        $string = strip_tags($string);
        $parts = preg_split('/([\s\n\r]+)/', $string, null, PREG_SPLIT_DELIM_CAPTURE);
        $parts_count = count($parts);

        $length = 0;
        $last_part = 0;
        for (; $last_part < $parts_count; ++$last_part) {
            $length += strlen($parts[$last_part]);
            if ($length > $width) {
                break;
            }
        }

        $retval = implode(array_slice($parts, 0, $last_part));
        $retval .= ' [...]';
        return $retval;
    }
}
