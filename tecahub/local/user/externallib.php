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
require_once($CFG->dirroot . '/mod/quiz/locallib.php');

class local_user_external extends external_api {
	
    public static function get_remote_mapping_user_parameters() {
        return new external_function_parameters(
            array('ipaddress' => new external_value(PARAM_TEXT, 'Host IP address'),
                'username' => new external_value(PARAM_TEXT, 'username'),
				'email' => new external_value(PARAM_TEXT, 'user email'))
        );
    }

    public static function get_remote_mapping_user($ipaddress, $username, $email) {
        global $CFG, $DB;

        //validate parameter
        $params = self::validate_parameters(self::get_remote_mapping_user_parameters(),
            array('ipaddress' => $ipaddress, 'username' => $username));

		return $DB->get_records_sql("SELECT u.id,u.username,u.email,u.auth FROM {user} u
									WHERE u.mnethostid = (SELECT id FROM {mnet_host} m 
									WHERE m.ip_address=?) AND u.username=?",
            array('ip_address' => $params['ipaddress'], 'username' => $params['username'], 'email' => $email));
    }

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
     */
    public static function get_remote_mapping_user_returns() {
        return new external_multiple_structure (
			new external_single_structure(
				array(
					'id' => new external_value(PARAM_INT, 'user id'),
					'username' => new external_value(PARAM_TEXT, 'username'),
					'email' => new external_value(PARAM_TEXT, 'user email'),
					'auth' => new external_value(PARAM_TEXT, 'auth method'),					
				)
			));
    }
}
