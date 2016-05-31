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

/**
 * HUB_URL - expected numbers, letters only and _-.
 */
define('MOODLE_MODE_HOST', 0);

/**
 * HUB_URL - expected numbers, letters only and _-.
 */
define('MOODLE_MODE_HUB', 1);

/**
 * HUB_URL - expected numbers, letters only and _-.
 */
define('MOODLE_RUN_MODE', MOODLE_MODE_HUB);

/**
 * HUB_URL - expected numbers, letters only and _-.
 */
define('HUB_URL', 'http://192.168.1.252');
//define('HUB_URL', 'http://10.0.0.252:10001');
/**
 * HUB TOKEN - Nccsoft External services
 */
define('HOST_TOKEN',  'a75634b66a82dd8f42f99baedf2690a1');

/**
 *  HUB TOKEN - Mobile services
*/
define('HOST_TOKEN_M',  'ac52a223f8589b3f26fa456a5dc20bde');


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
