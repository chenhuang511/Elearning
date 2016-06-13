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
require_once($CFG->dirroot . '/mod/quiz/locallib.php');
require_once($CFG->dirroot . '/question/engine/lib.php');

/**
 * Course external functions
 *
 * @package    core_course
 * @category   external
 * @copyright  2011 Jerome Mouneyrac
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since Moodle 2.2
 */
class local_mod_page_external extends external_api {
    /**
     * Hanv 24/05/2016
     * Return all the information about a quiz by quizid or by cm->instance from course_module
     *
     * @return external_function_parameters
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
     *
     */
    public static function get_mod_page_by_id_parameters() {
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
    public static function get_mod_page_by_id($id) {
        global $CFG, $DB;

        //validate parameter
        $params = self::validate_parameters(self::get_mod_page_by_id_parameters(),
            array('id' => $id);

        $page =  $DB->get_record('page', array('id' => $params['id']), '*', MUST_EXIST);
        return $page;
    }

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
     */
    public static function get_mod_page_by_id_returns() {
        return  new external_single_structure(
            array(
                'id' => new external_value(PARAM_INT, 'Standard Moodle primary key.'),
                'course' => new external_value(PARAM_INT, 'Foreign key reference to the course this quiz is part of.'),
                'name' => new external_value(PARAM_RAW, 'Quiz name.'),
                'intro' => new external_value(PARAM_RAW, 'Quiz introduction text.', VALUE_OPTIONAL),
                'introformat' => new external_format_value('intro', VALUE_OPTIONAL),
				'content' => new external_value(PARAM_RAW, 'Standard Moodle primary key.'),
                'contentformat' => new external_value(PARAM_INT, 'Foreign key reference to the course this quiz is part of.'),
                'legacyfiles' => new external_value(PARAM_INT, 'Quiz name.'),
                'legacyfileslast' => new external_value(PARAM_RAW, 'Quiz introduction text.', VALUE_OPTIONAL),
                'display' => new external_format_value(PARAM_INT, 'Display or Not'),
				'displayoptions' => new external_value(PARAM_RAW, 'Quiz name.'),
                'revision' => new external_value(PARAM_INT, 'Quiz introduction text.'),
                'timemodified' => new external_format_value('intro', VALUE_OPTIONAL)						
            )
        );
    }
}
