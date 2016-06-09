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
 * URL module main user interface
 *
 * @package    mod_url
 * @copyright  2009 Petr Skoda  {@link http://skodak.org}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require('../../../config.php');
require_once($CFG->dirroot .'/mod/url/lib.php');
require_once($CFG->dirroot .'/mod/url/locallib.php');
require_once($CFG->libdir . '/completionlib.php');
require_once($CFG->dirroot .'/course/remote/locallib.php');
require_once('locallib.php');

$id       = optional_param('id', 0, PARAM_INT);        // Course module ID
$redirect = optional_param('redirect', 0, PARAM_BOOL);


if (!$cm = get_remote_course_module_by_cmid('url', $id)) {
    print_error('invalidcoursemodule');
}
if (!$url = get_remote_url_by_id($cm->instance)) {
    print_error('invalidurl');
}

if (!$course = get_local_course_record($cm->course)) {
    print_error('invalidcourse');
}

//require_login($course, false, $cm);
$context = context_module::instance($cm->id);
require_capability('mod/url:view', $context);

// Completion and trigger events.
url_view($url, $course, $cm, $context);

$PAGE->set_url('/mod/url/remote/view.php', array('id' => $cm->id));

// Make sure URL exists before generating output - some older sites may contain empty urls
// Do not use PARAM_URL here, it is too strict and does not support general URIs!
$exturl = trim($url->externalurl);
if (empty($exturl) or $exturl === 'http://') {
    url_print_header($url, $cm, $course);
    url_print_heading($url, $cm, $course);
    url_print_intro($url, $cm, $course);
    notice(get_string('invalidstoredurl', 'url'), new moodle_url('/course/view.php', array('id'=>$cm->course)));
    die;
}
unset($exturl);

$displaytype = url_get_final_display_type($url);
if ($displaytype == RESOURCELIB_DISPLAY_OPEN) {
    // For 'open' links, we always redirect to the content - except if the user
    // just chose 'save and display' from the form then that would be confusing
    if (strpos(get_local_referer(false), 'modedit.php') === false) {
        $redirect = true;
    }
}

if ($redirect) {
    // coming from course page or url index page,
    // the redirection is needed for completion tracking and logging
    $fullurl = str_replace('&amp;', '&', url_get_full_url($url, $cm, $course));

    if (!course_get_format($course)->has_view_page()) {
        // If course format does not have a view page, add redirection delay with a link to the edit page.
        // Otherwise teacher is redirected to the external URL without any possibility to edit activity or course settings.
        $editurl = null;
        if (has_capability('moodle/course:manageactivities', $context)) {
            $editurl = new moodle_url('/course/modedit.php', array('update' => $cm->id));
            $edittext = get_string('editthisactivity');
        } else if (has_capability('moodle/course:update', $context->get_course_context())) {
            $editurl = new moodle_url('/course/edit.php', array('id' => $course->id));
            $edittext = get_string('editcoursesettings');
        }
        if ($editurl) {
            redirect($fullurl, html_writer::link($editurl, $edittext)."<br/>".
                    get_string('pageshouldredirect'), 10);
        }
    }
    redirect($fullurl);
}

switch ($displaytype) {
    case RESOURCELIB_DISPLAY_EMBED:
//        url_display_embed($url, $cm, $course);
        $mimetype = resourcelib_guess_url_mimetype($url->externalurl);
        $fullurl  = url_get_full_url($url, $cm, $course);
        $title    = $url->name;

        $link = html_writer::tag('a', $fullurl, array('href'=>str_replace('&amp;', '&', $fullurl)));
        $clicktoopen = get_string('clicktoopen', 'url', $link);
        $moodleurl = new moodle_url($fullurl);

        $extension = resourcelib_get_extension($url->externalurl);

        $mediarenderer = $PAGE->get_renderer('core', 'media');
        $embedoptions = array(
            core_media::OPTION_TRUSTED => true,
            core_media::OPTION_BLOCK => true
        );

        if (in_array($mimetype, array('image/gif','image/jpeg','image/png'))) {  // It's an image
            $code = resourcelib_embed_image($fullurl, $title);

        } else if ($mediarenderer->can_embed_url($moodleurl, $embedoptions)) {
            // Media (audio/video) file.
            $code = $mediarenderer->embed_url($moodleurl, $title, 0, 0, $embedoptions);

        } else {
            // anything else - just try object tag enlarged as much as possible
            $code = resourcelib_embed_general($fullurl, $title, $clicktoopen, $mimetype);
        }

        echo $code;

        url_print_intro($url, $cm, $course);
        break;
    default:
        $fullurl = url_get_full_url($url, $cm, $course);

        $display = url_get_final_display_type($url);
        if ($display == RESOURCELIB_DISPLAY_POPUP) {
            $jsfullurl = addslashes_js($fullurl);
            $options = empty($url->displayoptions) ? array() : unserialize($url->displayoptions);
            $width  = empty($options['popupwidth'])  ? 620 : $options['popupwidth'];
            $height = empty($options['popupheight']) ? 450 : $options['popupheight'];
            $wh = "width=$width,height=$height,toolbar=no,location=no,menubar=no,copyhistory=no,status=no,directories=no,scrollbars=yes,resizable=yes";
            $extra = "onclick=\"window.open('$jsfullurl', '', '$wh'); return false;\"";

        } else if ($display == RESOURCELIB_DISPLAY_NEW) {
            $extra = "onclick=\"this.target='_blank';\"";

        } else {
            $extra = '';
        }

        echo '<div class="urlworkaround">';
        print_string('clicktoopen', 'url', "<a href=\"$fullurl\" $extra>$fullurl</a>");
        echo '</div>';
        break;
}
