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

/**
 * Course external functions
 *
 * @package    core_course
 * @category   external
 * @copyright  2011 Jerome Mouneyrac
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since Moodle 2.2
 */
class local_questionnaire_external extends external_api {

    /**
     * Hanv 04/06/2016
     * Return a list of ids, load the basic information about a set of questions from the questions table.
     *
     * @return external_function_parameters
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
     *
     */
    public static function questionnaire_get_question_by_id_parameters() {
        return new external_function_parameters(
            array('id' => new external_value(PARAM_INT, 'id')
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
    public static function questionnaire_get_question_by_id($id) {
        global $CFG, $DB;

        //validate parameter
        $params = self::validate_parameters(self::questionnaire_get_question_by_id_parameters(),
            array('id' => $id));
        return $DB->get_record('questionnaire', array('id' => $params['id']), '*', MUST_EXIST);
    }

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
     */
    public static function questionnaire_get_question_by_id_returns() {
        return new external_single_structure(
            array(
                'id' => new external_value(PARAM_INT, 'Standard Moodle primary key.'),
                'course' => new external_value(PARAM_INT, 'course'),
                'name' => new external_value(PARAM_TEXT, 'Question name'),
                'intro' => new external_value(PARAM_TEXT, 'Question introduction text'),
                'introformat' => new external_value(PARAM_INT, 'questiontext format'),
                'qtype' => new external_value(PARAM_INT, 'qtype'),
                'respondenttype' => new external_value(PARAM_TEXT, 'respondenttype'),
                'resp_eligible' => new external_value(PARAM_TEXT, 'resp_eligible'),
                'resp_view' => new external_value(PARAM_INT, 'resp_view'),
                'opendate' => new external_value(PARAM_INT, 'opendate'),
                'closedate' => new external_value(PARAM_INT, 'closedate'),
                'resume' => new external_value(PARAM_INT, 'resume'),
                'navigate' => new external_value(PARAM_INT, 'navigate'),
                'grade' => new external_value(PARAM_INT, '	grade'),
                'sid' => new external_value(PARAM_INT, 'sid'),
                'timemodified' => new external_value(PARAM_INT, 'Last modified time'),
                'createdby' => new external_value(PARAM_INT, 'created by'),
                'completionsubmit' => new external_value(PARAM_INT, 'completionsubmit'),
                'autonum' => new external_value(PARAM_INT, 'autonum'),
            )
        );
    }    
}
