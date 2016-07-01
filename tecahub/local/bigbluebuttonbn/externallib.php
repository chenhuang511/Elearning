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
require_once($CFG->dirroot . '/question/engine/lib.php');
require_once($CFG->dirroot . '/mod/quiz/classes/external.php');

/**
 * Course external functions
 *
 * @package    core_course
 * @category   external
 * @copyright  2011 Jerome Mouneyrac
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since Moodle 2.2
 */
class local_mod_bigbluebuttonbn_external extends external_api {
    /**
     * Hanv 24/05/2016
     * Return all the information about a quiz by quizid or by cm->instance from course_module
     *
     * @return external_function_parameters
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
     *
     */
    public static function get_mod_bigbluebuttonbn_by_id_parameters() {
        return new external_function_parameters(
            array('id' => new external_value(PARAM_INT, 'id'))
        );
    }

    /**
     * Get Quiz object
     *
     * @param int $id id
     * @param array $options Options for filtering the results, used since Moodle 2.9
     * @return array
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
     */
    public static function get_mod_bigbluebuttonbn_by_id($id) {
        global $CFG, $DB;

        //validate parameter
        $params = self::validate_parameters(self::get_mod_bigbluebuttonbn_by_id_parameters(), array('id' => $id));

        return $DB->get_record('bigbluebuttonbn', array('id' => $params['id']), '*', MUST_EXIST);
    }

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
     */
    public static function get_mod_quiz_by_id_returns() {
        return  new external_single_structure(
            array(
                'id' => new external_value(PARAM_INT, 'Standard Moodle primary key.'),
                'course' => new external_value(PARAM_INT, 'Foreign key reference to the course this quiz is part of.'),
				'name' => new external_value(PARAM_TEXT, 'Standard Moodle primary key.'),
                'intro' => new external_value(PARAM_RAW, 'Standard Moodle primary key.'),
				'introformat' => new external_value(PARAM_INT, 'Standard Moodle primary key.'),
				'meetingid' => new external_value(PARAM_TEXT, 'Standard Moodle primary key.'),
				'moderatorpass' => new external_value(PARAM_TEXT, 'Standard Moodle primary key.'),
				'viewerpass' => new external_value(PARAM_INT, 'Standard Moodle primary key.'),
				'newwindow' => new external_value(PARAM_INT, 'Standard Moodle primary key.'),
				'wait' => new external_value(PARAM_INT, 'Standard Moodle primary key.'),
				'record' => new external_value(PARAM_INT, 'Standard Moodle primary key.'),
				'tagging' => new external_value(PARAM_INT, 'Standard Moodle primary key.'),
				'welcome' => new external_value(PARAM_RAW, 'Standard Moodle primary key.'),
				'voicebridge' => new external_value(PARAM_INT, 'Standard Moodle primary key.'),
				'openingtime' => new external_value(PARAM_INT, 'Standard Moodle primary key.'),
				'closingtime' => new external_value(PARAM_INT, 'Standard Moodle primary key.'),
				'timecreated' => new external_value(PARAM_INT, 'Standard Moodle primary key.'),
				'timemodified' => new external_value(PARAM_INT, 'Standard Moodle primary key.'),
				'presentation' => new external_value(PARAM_RAW, 'Standard Moodle primary key.'),
				'participants' => new external_value(PARAM_RAW, 'Standard Moodle primary key.'),
				'userlimit' => new external_value(PARAM_INT, 'Standard Moodle primary key.')
            )
        );
    }
}
