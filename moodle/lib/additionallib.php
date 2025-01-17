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

function get_cat_id_by_remote_id($remotecatid) {
    global $DB;

    $retval = $remotecatid;
    if (MOODLE_RUN_MODE === MOODLE_MODE_HUB) {
        $retval = $DB->get_field('course_categories', 'id', array('remoteid' => $remotecatid));
        if (!$retval) {
            $retval = $remotecatid;
        }
    }

    return $retval;
}

function get_course_id_by_remote_id($remotecourseid) {
    global $DB;

    $retval = $remotecourseid;
    if (MOODLE_RUN_MODE === MOODLE_MODE_HUB && $remotecourseid != SITEID) {
        $retval = $DB->get_field('course', 'id', array('remoteid' => $remotecourseid));
        if (!$retval) {
            $retval = $remotecourseid;
        }
    }

    return $retval;
}

function update_course_category_path($catid) {
    global $DB;
    $result = '';
    $cat = $DB->get_record('course_categories', array('id' => $catid), 'id, path, parent', MUST_EXIST);

    if (!$cat->parent) {
        return $result . "/$catid";
    }

    $result = update_course_category_path($cat->id);
    return $result;
}

function is_remote_course($courseorid) {
    global $DB;

    if (is_object($courseorid)) {
        return ($courseorid->hostid != 0);
    } else {
        return $DB->get_field('course', 'hostid', array('id' => $courseorid), MUST_EXIST);
    }
}

function convert_remote_course_record(&$course, $userid = false) {
    if (!$userid)
        $course->id       = $course->remoteid;
}

function convert_remote_assign_record(&$assign, $userid = false) {
    if (!$userid)  {
        $assign->id       = $assign->remoteid;
    }
}

function get_local_course_record($courseid, $useid = false) {
    global $DB;
    if (is_object($courseid)) {
        convert_remote_course_record($courseid);
        return $courseid;
    }
    $idfield = ($useid) ? "id" : "remoteid";
    if ((int)$courseid === SITEID) $idfield = "id";
    $course = $DB->get_record("course", array($idfield => $courseid), "*", MUST_EXIST);
    //convert_remote_course_record($course, $useid);
    return $course;
}

function get_local_assign_record($assignid, $useid = false) {
    global $DB;
    if (is_object($assignid)) {
        convert_remote_assign_record($assignid);
        return $assignid;
    }
    $idfield = ((int)$assignid === 1 || $useid) ? "id" : "remoteid";
    $assign = $DB->get_record("assign", array($idfield => $assignid), "*", MUST_EXIST);
    return $assign;
}

function get_local_course_modules_record($cmid, $useid = false) {
    global $DB;
    $idfield = ((int)$cmid === 1 || $useid) ? "id" : "remoteid";
    $coursemodule = $DB->get_record("course_modules", array($idfield => $cmid), "*", MUST_EXIST);
    return $coursemodule;
}

function convert_remote_grade_items_record(&$grade, $userid = false) {
    if (!$userid)  {
        $grade->id = $grade->remoteid;
    }
}

function get_local_grade_items_record($gradeid, $useid = false) {
    global $DB;
    if (is_object($gradeid)) {
        convert_remote_grade_items_record($gradeid);
        return $gradeid;
    }
    $idfield = ((int)$gradeid === 1 || $useid) ? "id" : "remoteid";
    $grade = $DB->get_record("grade_items", array($idfield => $gradeid), "*", MUST_EXIST);
    //convert_remote_course_record($course, $useid);
    return $grade;
}

function get_local_courses_record() {
    global $CFG, $DB;

    require_once($CFG->dirroot . '/mnet/lib.php');
    $hubname = mnet_get_hostname_from_uri(HUB_URL);
    // Get the IP address for that host - if this fails, it will return the hostname string
    $hubip = gethostbyname($hubname);
    $hostid = $DB->get_field('mnet_host', 'id', array('ip_address' => $hubip));

    $courses = $DB->get_records_sql('SELECT * FROM {course} WHERE hostid = ?', array('hostid' => $hostid));

    return $courses;
}

function get_local_enrol_course() {
    global $DB, $USER, $CFG;

    require_once($CFG->dirroot . '/mnet/lib.php');
    $hubname = mnet_get_hostname_from_uri(HUB_URL);
    // Get the IP address for that host - if this fails, it will return the hostname string
    $hubip = gethostbyname($hubname);
    $hostid = $DB->get_field('mnet_host', 'id', array('ip_address' => $hubip));

    $sql = 'SELECT * FROM {course} WHERE remoteid in (SELECT remotecourseid FROM {mnetservice_enrol_enrolments} e WHERE e.hostid = ? AND e.userid = ?)';
    
    return $DB->get_records_sql($sql, array('hostid' => $hostid, 'userid' => $USER->id));
}
