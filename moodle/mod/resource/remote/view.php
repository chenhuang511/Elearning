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
 * Resource module version information
 *
 * @package    mod_resource
 * @copyright  2009 Petr Skoda  {@link http://skodak.org}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(__FILE__) . '/../../../config.php');
require_once($CFG->dirroot . '/course/remote/locallib.php');
require_once($CFG->dirroot . '/mod/resource/remote/locallib.php');
require_once($CFG->dirroot . '/mod/resource/lib.php');
require_once($CFG->dirroot . '/mod/resource/locallib.php');
require_once($CFG->libdir . '/completionlib.php');

$id = optional_param('id', 0, PARAM_INT); // Course Module ID
$r = optional_param('r', 0, PARAM_INT);  // Resource instance ID
$redirect = optional_param('redirect', 0, PARAM_BOOL);

if ($r) {
    $params = array();
    $params['parameters[0][name]'] = "id";
    $params['parameters[0][value]'] = $r;

    if (!$resource = get_remote_resource_by($params)) {
        resource_redirect_if_migrated($r, 0);
        print_error('invalidaccessparameter');
    }
    $cm = get_remote_course_module_by_instance('resource', $resource->id);

} else {
    if (!$cm = get_remote_course_module_by_cmid('resource', $id)) {
        resource_redirect_if_migrated(0, $id);
        print_error('invalidcoursemodule');
    }
    $params = array();
    $params['parameters[0][name]'] = "id";
    $params['parameters[0][value]'] = $cm->instance;
    $resource = get_remote_resource_by($params, '', true);
}

$course = get_local_course_record($cm->course, true);

require_course_login($course, true, $cm);
$context = context_module::instance($cm->id);
require_capability('mod/resource:view', $context);

$nonajax = optional_param('nonajax', true, PARAM_BOOL);
if (!has_capability('moodle/course:manageactivities', $context) && $nonajax != true) {
    $CFG->nonajax = false;
} else {
    $CFG->nonajax = true;
}

// Completion and trigger events.
resource_view($resource, $course, $cm, $context);

$PAGE->set_url('/mod/resource/remote/view.php', array('id' => $cm->id));

if ($resource->tobemigrated) {
    resource_print_tobemigrated($resource, $cm, $course);
    die;
}

$file = get_remote_files_resource_by_cm($cm->id);

$resource->mainfile = $file->filename;
$displaytype = resource_get_final_display_type($resource);
if ($displaytype == RESOURCELIB_DISPLAY_OPEN || $displaytype == RESOURCELIB_DISPLAY_DOWNLOAD) {
    // For 'open' and 'download' links, we always redirect to the content - except
    // if the user just chose 'save and display' from the form then that would be
    // confusing
    if (strpos(get_local_referer(false), 'modedit.php') === false) {
        $redirect = true;
    }
}

// Don't redirect teachers, otherwise they can not access course or module settings.
if ($redirect && !course_get_format($course)->has_view_page() &&
    (has_capability('moodle/course:manageactivities', $context) ||
        has_capability('moodle/course:update', context_course::instance($course->id)))
) {
    $redirect = false;
}

if ($redirect) {
    // coming from course page or url index page
    // this redirect trick solves caching problems when tracking views ;-)
    $fullurl = $file->url;
    redirect($fullurl);
}

remote_resource_print_workaround($resource, $cm, $course, $file);
