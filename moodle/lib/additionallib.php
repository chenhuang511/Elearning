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
 * moodlelib.php - Moodle main library
 *
 * Main library file of miscellaneous general-purpose Moodle functions.
 * Other main libraries:
 *  - weblib.php      - functions that produce web output
 *  - datalib.php     - functions that access the database
 *
 * @package    core
 * @subpackage lib
 * @copyright  1999 onwards Martin Dougiamas  http://dougiamas.com
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/fdefine.php');

function convert_remote_course_record(&$course) {
    global $DB;
    $cat = $DB->get_record("course_categories", array("remoteid" => $course->category), "id, remoteid", MUST_EXIST);
    $course->category = $cat->id;
    $course->id       = $course->remoteid;
}

function get_local_course_record($courseid) {
    global $DB;
    if (is_object($courseid)) {
        convert_remote_course_record($courseid);
        return $courseid;
    }
    $idfield = ((int)$courseid === 1) ? "id" : "remoteid";
    $course = $DB->get_record("course", array($idfield => $courseid), "*", MUST_EXIST);
    convert_remote_course_record($course);
    return $course;
}
