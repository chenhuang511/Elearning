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

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/externallib.php');

/**
 * Course external functions
 *
 * @package    core_course
 * @category   external
 * @copyright  2011 Jerome Mouneyrac
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since Moodle 2.2
 */
class local_certificate_external extends external_api
{

    public static function certificate_get_link_parameters() {
        return new external_function_parameters(
            array(
                'userid' => new external_value(PARAM_INT, 'user id in remote'),
                'courseid' => new external_value(PARAM_INT, 'course id in remote'),
            )
        );
    }

    /**
     * Get Question object
     *
     * @param int $id id
     * @param array $options Options for filtering the results, used since Moodle 2.9
     * @return array
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
     */
    public static function certificate_get_link($userid, $courseid) {
        global $CFG, $DB;

        //validate parameter
        $params = self::validate_parameters(self::certificate_get_link_parameters(),
            array('userid' => $userid, 'courseid' => $courseid));

        $course = $DB->get_record('course', array('remoteid' => $courseid));

        $courselocal = $course->id;
        $cm =  reset(get_coursemodules_in_course('certificate', $courselocal));
        $userlocal = get_remote_mapping_localuserid($userid);
        if(!$cm || !$userlocal) {return 'false';};
        $modinfo = get_fast_modinfo($course);
        $cmcheck = $modinfo->get_cm($cm->id);

        if(!$cmcheck->get_user_access($userlocal)){
          return 'false';
        };
        $url = $CFG->wwwroot . '/mod/certificate/preview.php?id=' . $cm->id . '&uid=' . $userlocal;
        return $url;
    }

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
     */
    public static function certificate_get_link_returns() {
        return new external_value(PARAM_TEXT, 'link');
    }

}
