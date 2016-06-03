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
        global $USER;
        //validate parameter
        $params = self::validate_parameters(self::get_survey_by_id_parameters(),
            array('id' => $id));
        $warnings = array();
        // Entry to return.
        $surveydetails = array();
        if (!empty($params['id'])) {
            $query = "SELECT cm.id AS coursemodule, s.*, cw.section, cm.visible AS visible,
                            cm.groupmode, cm.groupingid 
                        FROM {survey} AS s
                        INNER JOIN {course_modules} AS cm
                        ON s.course = cm.course
                        INNER JOIN {course_sections} AS cw
                        ON cm.section = cw.id
                        WHERE s.id = :id";
            $result = $DB->get_records_sql($query, $params);
            $survey = null;
            if (count($result) > 0) {
                $survey = $result[0];
            }
            if (!$survey) {
                $context = context_module::instance($survey->coursemodule);

                // First, we return information that any user can see in the web interface.
                $surveydetails['id'] = $survey->id;
                $surveydetails['coursemodule']      = $survey->coursemodule;
                $surveydetails['course']            = $survey->course;
                $surveydetails['name']              = external_format_string($survey->name, $context->id);

                if (has_capability('mod/survey:participate', $context)) {
                    $trimmedintro = trim($survey->intro);
                    if (empty($trimmedintro)) {
                        $tempo = $DB->get_field("survey", "intro", array("id" => $survey->template));
                        $survey->intro = get_string($tempo, "survey");
                    }

                    // Format intro.
                    list($surveydetails['intro'], $surveydetails['introformat']) =
                        external_format_text($survey->intro, $survey->introformat, $context->id, 'mod_survey', 'intro', null);

                    $surveydetails['template']  = $survey->template;
                    $surveydetails['days']      = $survey->days;
                    $surveydetails['questions'] = $survey->questions;
                    $surveydetails['surveydone'] = survey_already_done($survey->id, $USER->id) ? 1 : 0;

                }

                if (has_capability('moodle/course:manageactivities', $context)) {
                    $surveydetails['timecreated']   = $survey->timecreated;
                    $surveydetails['timemodified']  = $survey->timemodified;
                    $surveydetails['section']       = $survey->section;
                    $surveydetails['visible']       = $survey->visible;
                    $surveydetails['groupmode']     = $survey->groupmode;
                    $surveydetails['groupingid']    = $survey->groupingid;
                }
            }
        }
        $result = array();
        $result['survey'] = $surveydetails;
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
