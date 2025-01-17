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
 * Label module
 *
 * @package mod_label
 * @copyright  2003 onwards Martin Dougiamas  {@link http://moodle.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once("../../../config.php");

$id = optional_param('id',0,PARAM_INT);    // Course Module ID, or
$l = optional_param('l',0,PARAM_INT);     // Label ID

if ($id) {
    $PAGE->set_url('/mod/label/index.php', array('id'=>$id));
    if (! $cm = get_remote_course_module_by_cmid('label', $id)) {
        print_error('invalidcoursemodule');
    }

    if (! $course = get_local_course_record($cm->course, true)) {
        print_error('coursemisconf');
    }

    if (! $label = get_remote_label_by_id($cm->instance)) {
        print_error('invalidcoursemodule');
    }

} else {
    $PAGE->set_url('/mod/label/index.php', array('l'=>$l));
    if (! $label = get_remote_label_by_id($l)) {
        print_error('invalidcoursemodule');
    }
    if (! $course = get_local_course_record($label->course)) {
        print_error('coursemisconf');
    }
    if (! $cm = get_remote_course_module_by_instance("label", $label->id)) {
        print_error('invalidcoursemodule');
    }
}

require_login($course, true, $cm);

redirect("$CFG->wwwroot/course/view.php?id=$course->id");


