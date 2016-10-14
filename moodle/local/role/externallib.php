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

require_once($CFG->libdir . '/externallib.php');

class local_role_external extends external_api {
    
    public static function host_assign_role_to_user_parameters() {
        return new external_function_parameters(
            array('roleid' => new external_value(PARAM_INT, 'Role id'),
                'userid' => new external_value(PARAM_INT, 'user id'),
                'courseid' => new external_value(PARAM_INT, 'Course id')
            )
        );
    }

    public static function host_assign_role_to_user($roleid, $userid, $courseid) {
        global $CFG, $DB, $PAGE;

        //validate parameter
        $params = self::validate_parameters(self::host_assign_role_to_user_parameters(),
            array('roleid' => $roleid, 'userid' => $userid, 'courseid' => $courseid));

        //need mapping id
        $course = $DB->get_record('course', array('remoteid' => $courseid));
        $hostuser = get_remote_mapping_localuserid($userid);

        //userid and courseid is remoteid
        $context = context_course::instance($course->id);
        //userid and courseid is remoteid
        remote_assign_role_to_user($roleid, $hostuser, $course->id);

        return role_assign($roleid, $hostuser, $context->id, '', NULL);
    }

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
     */
    public static function host_assign_role_to_user_returns() {
        return new external_value(PARAM_INT, 'role assignments id');
    }

    public static function host_unassign_role_to_user_parameters() {
        return new external_function_parameters(
            array('roleid' => new external_value(PARAM_INT, 'Role id'),
                'userid' => new external_value(PARAM_INT, 'user id'),
                'courseid' => new external_value(PARAM_INT, 'Course id')
            )
        );
    }
    public static function host_unassign_role_to_user($roleid, $userid, $courseid) {
        global $CFG, $DB, $PAGE;

        //validate parameter
        $params = self::validate_parameters(self::host_unassign_role_to_user_parameters(),
            array('roleid' => $roleid, 'userid' => $userid, 'courseid' => $courseid));

        //need mapping id
        $course = $DB->get_record('course', array('remoteid' => $courseid));
        $hostuser = get_remote_mapping_localuserid($userid);

        //userid and courseid is remoteid
        $context = context_course::instance($course->id);
        remote_unassign_role_to_user($roleid, $hostuser, $course->id);
        return role_unassign($roleid, $hostuser, $context->id, '', NULL);
    }

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
     */
    public static function host_unassign_role_to_user_returns() {
        return new external_value(PARAM_INT, 'role assignments id');
    }

    public static function host_enrol_user_to_course_parameters() {
        return new external_function_parameters(
            array(
                'userid' => new external_value(PARAM_INT, 'user id'),
                'courseid' => new external_value(PARAM_INT, 'Course id'))
        );
    }

    public static function host_enrol_user_to_course($userid, $courseid)
    {
        global $DB;
        //validate parameter
        $params = self::validate_parameters(self::host_enrol_user_to_course_parameters(),
            array('userid' => $userid, 'courseid' => $courseid));
        $service = mnetservice_enrol::get_instance();
        $host = $DB->get_record('mnet_host', array('wwwroot' => HUB_URL), '*', MUST_EXIST);
        $course = $DB->get_record('course', array('remoteid' => $courseid, 'hostid' => $host->id), '*', MUST_EXIST);
        // user selectors
        $currentuserselector = new mnetservice_enrol_existing_users_selector('removeselect', array('hostid' => $host->id, 'remotecourseid' => $course->remoteid));
        $potentialuserselector = new mnetservice_enrol_potential_users_selector('addselect', array('hostid' => $host->id, 'remotecourseid' => $course->remoteid));

        // process incoming enrol request
        $error = 'successful';
        $localusserid = get_remote_mapping_localuserid($userid);
        if (!empty($userid)) {
            $user = $DB->get_record('user', array('id' => $localusserid));
            $result = $service->req_enrol_user($user, $course);
            if ($result !== true) {
                $error .= $service->format_error_message($result);
            }

            $potentialuserselector->invalidate_selected_users();
            $currentuserselector->invalidate_selected_users();
        }
        return $error;
    }

    public static function host_enrol_user_to_course_returns() {
        return new external_value(PARAM_TEXT, 'role assignments id');
    }
}
