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
 * External course API
 *
 * @package    core_course
 * @category   external
 * @copyright  2009 Petr Skodak
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

require_once("$CFG->libdir/externallib.php");
require_once($CFG->dirroot . '/course/externallib.php');

/**
 * Course external functions
 *
 * @package    core_course
 * @category   external
 * @copyright  2011 Jerome Mouneyrac
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since Moodle 2.2
 */
class local_course_external extends external_api
{

    /**
     * enrol course for host
     * @return external_function_parameters
     */
    public static function host_fetch_course_parameters() {
        return new external_function_parameters(
            array( 'hubid' => new external_value(PARAM_TEXT, 'hub id') )
        );
    }

    public static function host_fetch_course($hubid) {
        global $DB;

        //validate parameter
        $params = self::validate_parameters(self::host_fetch_course_parameters(),
            array('hubid' => $hubid));

        if($hubid == false) {
            $hubid =  $DB->get_field('mnet_host', 'id', array('wwwroot' => HUB_URL));
        }

        $service = mnetservice_enrol::get_instance();

        $msg = 'successfully';
        if (!$service->is_available()) {
            $msg = 'Service is invalid';
        }

        // remote hosts that may publish remote enrolment service and we are subscribed to it
        $hosts = $service->get_remote_publishers();

        if (empty($hosts[$hubid])) {
            $msg = 'not found host id';
        }
        $host = $hosts[$hubid];

        $courses = $service->get_remote_courses($host->id, 0);
        if (is_string($courses)) {
            $msg = $courses;
        }

        return $msg;
    }

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
     */
    public static function host_fetch_course_returns() {
        return new external_value(PARAM_TEXT, 'notice');
    }
}