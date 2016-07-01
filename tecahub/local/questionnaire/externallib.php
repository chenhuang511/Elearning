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
		require_once($CFG->dirroot . '/question/engine/bank.php');
        return load_for_cache($id);
    }

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
     */
    public static function questionnaire_get_question_by_id_returns() {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'id' => new external_value(PARAM_INT, 'Standard Moodle primary key.'),
                    'category' => new external_value(PARAM_INT, 'category',VALUE_OPTIONAL),
                    'parent' => new external_value(PARAM_INT, 'parent', VALUE_OPTIONAL),
                    'name' => new external_value(PARAM_RAW, 'Question name'),
                    'questiontext' => new external_value(PARAM_RAW, 'Question introduction text.', VALUE_OPTIONAL),
                    'questiontextformat' => new external_value(PARAM_INT, 'questiontext format.', VALUE_OPTIONAL),
                    'generalfeedback' => new external_value(PARAM_RAW, 'generalfeedback.', VALUE_OPTIONAL),
                    'generalfeedbackformat' => new external_value(PARAM_INT, 'general feedback format.', VALUE_OPTIONAL),
                    'defaultmark' => new external_value(PARAM_FLOAT, 'default mark.', VALUE_OPTIONAL),
                    'penalty' => new external_value(PARAM_FLOAT, 'penalty.', VALUE_OPTIONAL),
                    'qtype' => new external_value(PARAM_RAW, 'qtype', VALUE_OPTIONAL),
                    'length' => new external_value(PARAM_INT, 'length', VALUE_OPTIONAL),
                    'stamp' => new external_value(PARAM_RAW, 'stamp'),
                    'version' => new external_value(PARAM_RAW, 'Question version'),
                    'hidden' => new external_value(PARAM_INT, '	hidden', VALUE_OPTIONAL),
                    'timecreated' => new external_value(PARAM_INT, 'The time when the question was added to the question bank.', VALUE_OPTIONAL),
                    'timemodified' => new external_value(PARAM_INT, 'Last modified time.', VALUE_OPTIONAL),
                    'createdby' => new external_value(PARAM_INT, 'created by.', VALUE_OPTIONAL),
                    'modifiedby' => new external_value(PARAM_INT, 'modified by.', VALUE_OPTIONAL),
                    'contextid' => new external_value(PARAM_INT, 'comtext id.'),
                )
            )
        );
    }    
}
