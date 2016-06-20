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
 * External Survey API
 *
 * @package    core_local_survey
 * @category   external
 * @copyright  2009 Petr Skodak
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir . '/externallib.php');
require_once($CFG->dirroot . '/mod/survey/lib.php');

/**
 * Survey external functions
 *
 * @package    core_local_survey
 * @category   external
 * @copyright  2011 Jerome Mouneyrac
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since Moodle 2.2
 */
class local_mod_survey_external extends external_api
{
    /**
     * @desc Validate parameters.
     * @return external_function_parameters
     */
    public static function get_survey_by_id_parameters()
    {
        return new external_function_parameters (
            array(
                'id' => new external_value(PARAM_INT, 'survey id'),
            )
        );
    }

    /**
     * @desc get survey by id
     * @param $id
     * @return array
     * @throws invalid_parameter_exception
     */
    public static function get_survey_by_id($id)
    {
        global $DB;

        $warnings = array();

        //validate parameter
        $params = self::validate_parameters(self::get_survey_by_id_parameters(),
            array('id' => $id));

        $result = array();
        $survey = $DB->get_record("survey", array("id" => $params['id']));

        if(!$survey) {
            $survey = new stdClass();
        }

        $result['survey'] = $survey;
        $result['warnings'] = $warnings;

        return $result;
    }

    /**
     * @desc Describes the surveys return value.
     * @return external_single_structure
     * @since Moodle 3.1
     */
    public static function get_survey_by_id_returns()
    {
        return new external_single_structure(
            array(
                'survey' => new external_single_structure(
                    array(
                        'id' => new external_value(PARAM_INT, 'Survey id'),
                        'coursemodule' => new external_value(PARAM_INT, 'Course module id'),
                        'course' => new external_value(PARAM_INT, 'Course id'),
                        'name' => new external_value(PARAM_RAW, 'Survey name'),
                        'intro' => new external_value(PARAM_RAW, 'The Survey intro', VALUE_OPTIONAL),
                        'introformat' => new external_format_value('intro', VALUE_OPTIONAL),
                        'template' => new external_value(PARAM_INT, 'Survey type', VALUE_OPTIONAL),
                        'days' => new external_value(PARAM_INT, 'Days', VALUE_OPTIONAL),
                        'questions' => new external_value(PARAM_RAW, 'Question ids', VALUE_OPTIONAL),
                        'surveydone' => new external_value(PARAM_INT, 'Did I finish the survey?', VALUE_OPTIONAL),
                        'timecreated' => new external_value(PARAM_INT, 'Time of creation', VALUE_OPTIONAL),
                        'timemodified' => new external_value(PARAM_INT, 'Time of last modification', VALUE_OPTIONAL),
                        'section' => new external_value(PARAM_INT, 'Course section id', VALUE_OPTIONAL),
                        'visible' => new external_value(PARAM_INT, 'Visible', VALUE_OPTIONAL),
                        'groupmode' => new external_value(PARAM_INT, 'Group mode', VALUE_OPTIONAL),
                        'groupingid' => new external_value(PARAM_INT, 'Group id', VALUE_OPTIONAL),
                    )
                ),
                'warnings' => new external_warnings(),
            )
        );
    }
}
