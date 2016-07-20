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

class local_mod_chat_external extends external_api {
    
    public static function get_chat_by_id_parameters() {
        return new external_function_parameters(
            array('id' => new external_value(PARAM_TEXT, 'Role id'))
        );
    }

    public static function get_chat_by_id($id) {
        global $DB;

        //validate parameter
        $params = self::validate_parameters(self::get_chat_by_id_parameters(),
            array('id' => $id));
                
        return $DB->get_record("chat", array('id' => $id), "*", MUST_EXIST);
    }

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
     */
    public static function get_chat_by_id_returns() {
        return new external_single_structure(
			array(
				'id' => new external_value(PARAM_INT, 'course id'),
				'name'  => new external_value(PARAM_TEXT, 'course id'),
				'course' => new external_value(PARAM_INT, 'longtext'),
				'intro' => new external_value(PARAM_RAW, 'course id'),
				'introformat'  => new external_value(PARAM_INT, 'course id'),
				'keepdays' => new external_value(PARAM_INT, 'longtext'),
				'studentlogs' => new external_value(PARAM_INT, 'course id'),
				'chattime'  => new external_value(PARAM_INT, 'course id'),
				'schedule' => new external_value(PARAM_INT, 'longtext'),
				'timemodified' => new external_value(PARAM_INT, 'course id'),
			)
		);
    }

    public static function local_mod_get_chat_users_parameters() {
        return new external_function_parameters(
            array(
                'groupingjoin' => new external_value(PARAM_RAW, ' the groupingjoin'),
                'groupselect' => new external_value(PARAM_RAW, ' the groupselect'),
                'data' => new external_single_structure(
                    array(
                        'name' => new external_value(PARAM_RAW, 'data name'),
                        'value' => new external_value(PARAM_RAW, 'data value'),
                    )
                )
            )
        );
    }

    public static function local_mod_get_chat_users($groupingjoin, $groupselect, $data) {
        global $DB;

        //validate parameter
        $params = self::validate_parameters(self::local_mod_get_chat_users_parameters(),
            array(
                'groupingjoin' => $groupingjoin,
                'groupselect' => $groupselect,
                'data' => $data,
            )
        );

        return $DB->get_records_sql("SELECT DISTINCT u.picture, c.lastmessageping, c.firstping
                               FROM {chat_users} c
                               JOIN {user} u ON u.id = c.userid". $params['groupingjoin']."
                              WHERE c.chatid = :chatid " .$params['groupselect'] . "
                           ORDER BY c.firstping ASC", $params['data']);
    }

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
     */
    public static function local_mod_get_chat_users_returns() {
        return new external_single_structure(
            array(
                'u.picture' => new external_value(PARAM_INT, 'user picture'),
                'c.lastmessageping'  => new external_value(PARAM_INT, 'last message ping'),
                'c.firstping' => new external_value(PARAM_INT, 'first ping')
            )
        );
    }
}
