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
                'intro' => new external_value(PARAM_RAW, 'Question introduction text'),
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
                'completionsubmit' => new external_value(PARAM_INT, 'completionsubmit'),
                'autonum' => new external_value(PARAM_INT, 'autonum'),
            )
        );
    }
    /**
     * get field owner in questionnaire_survey
     */
    public static function questionnaire_get_field_owner_questionnaire_by_id_parameters() {
        return new external_function_parameters(
            array('id' => new external_value(PARAM_INT, 'id')
            )
        );
    }

    public function questionnaire_get_field_owner_questionnaire_by_id($id){
        global $CFG, $DB;

        //validate parameter
        $params = self::validate_parameters(self::questionnaire_get_field_owner_questionnaire_by_id_parameters(),
            array('id' => $id));
        return $DB->get_field('questionnaire_survey', 'owner', array('id' => $params['id']));
    }

    public static function questionnaire_get_field_owner_questionnaire_by_id_returns()
    {
        return new external_value(PARAM_TEXT, 'owner');
    }

    /**
     * get record questionnaire_survey
     */
    public static function questionnaire_get_questionnaire_survey_by_id_parameters() {
        return new external_function_parameters(
            array('id' => new external_value(PARAM_INT, 'id'))
        );
    }

    public function questionnaire_get_questionnaire_survey_by_id($id){
        global $CFG, $DB;

        //validate parameter
        $params = self::validate_parameters(self::questionnaire_get_questionnaire_survey_by_id_parameters(),
            array('id' => $id));
        return $DB->get_record('questionnaire_survey', array('id' => $params['id']), '*', MUST_EXIST);
    }

    public static function questionnaire_get_questionnaire_survey_by_id_returns()
    {
        return new external_single_structure(
            array(
                'id' => new external_value(PARAM_INT, 'Standard Moodle primary key.'),
                'name' => new external_value(PARAM_TEXT, 'Question name'),
                'owner' => new external_value(PARAM_TEXT, 'Question introduction text'),
                'realm' => new external_value(PARAM_TEXT, 'questiontext format'),
                'status' => new external_value(PARAM_INT, 'qtype'),
                'title' => new external_value(PARAM_TEXT, 'respondenttype'),
                'email' => new external_value(PARAM_TEXT, 'resp_eligible'),
                'subtitle' => new external_value(PARAM_RAW, 'resp_view'),
                'info' => new external_value(PARAM_RAW, 'opendate'),
                'theme' => new external_value(PARAM_TEXT, 'closedate'),
                'thanks_page' => new external_value(PARAM_TEXT, 'resume'),
                'thank_head' => new external_value(PARAM_TEXT, 'navigate'),
                'thank_body' => new external_value(PARAM_RAW, '	grade'),
                'feedbacksections' => new external_value(PARAM_INT, 'sid'),
                'feedbacknotes' => new external_value(PARAM_RAW, 'Last modified time'),
                'feedbackscores' => new external_value(PARAM_INT, 'completionsubmit'),
                'chart_type' => new external_value(PARAM_TEXT, 'autonum'),
            )
        );
    }
    /**
     * get record questionnaire_question by sid
     */
    public static function questionnaire_get_questionnaire_question_by_sid_parameters() {
        return new external_function_parameters(
            array('sid' => new external_value(PARAM_INT, 'sid'))
        );
    }

    public function questionnaire_get_questionnaire_question_by_sid($sid){
        global $CFG, $DB;

        //validate parameter
        $params = self::validate_parameters(self::questionnaire_get_questionnaire_question_by_sid_parameters(),
            array('sid' => $sid));
        $select = 'survey_id = '.$params['sid'].' AND deleted != \'y\'';
        return $DB->get_records_select('questionnaire_question', $select, null, 'position');
    }

    public static function questionnaire_get_questionnaire_question_by_sid_returns()
    {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'id' => new external_value(PARAM_INT, 'Standard Moodle primary key.'),
                    'survey_id' => new external_value(PARAM_INT, 'survey_id'),
                    'name' => new external_value(PARAM_TEXT, 'name'),
                    'type_id' => new external_value(PARAM_INT, 'type_id'),
                    'result_id' => new external_value(PARAM_INT, 'result_id'),
                    'length' => new external_value(PARAM_INT, 'length'),
                    'precise' => new external_value(PARAM_INT, 'precise'),
                    'position' => new external_value(PARAM_INT, 'position'),
                    'content' => new external_value(PARAM_RAW, 'content'),
                    'required' => new external_value(PARAM_TEXT, 'required'),
                    'deleted' => new external_value(PARAM_TEXT, 'deleted'),
                    'dependquestion' => new external_value(PARAM_INT, 'dependquestion'),
                    'dependchoice' => new external_value(PARAM_INT, 'dependchoice'),
                )
            )
        );
    }
    
    /**
     * get record questionnaire_quest_choice by question id
     */
    public static function questionnaire_get_questionnaire_quest_choice_by_question_id_parameters() {
        return new external_function_parameters(
            array('question_id' => new external_value(PARAM_INT, 'question_id'))
        );
    }

    public function questionnaire_get_questionnaire_quest_choice_by_question_id($question_id){
        global $CFG, $DB;

        //validate parameter
        $params = self::validate_parameters(self::questionnaire_get_questionnaire_quest_choice_by_question_id_parameters(),
            array('question_id' => $question_id));

        return $DB->get_records('questionnaire_quest_choice', array('question_id' => $params['question_id']), 'id ASC');
    }

    public static function questionnaire_get_questionnaire_quest_choice_by_question_id_returns()
    {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'id' => new external_value(PARAM_INT, 'Standard Moodle primary key.'),
                    'question_id' => new external_value(PARAM_INT, 'question_id'),
                    'content' => new external_value(PARAM_RAW, 'content'),
                    'value' => new external_value(PARAM_RAW, 'value'),
                )
            )
        );
    }
    /**
     * save response
     */
    public static function save_response_by_mbl_parameters()
    {
        return new external_function_parameters (
            array(
                'tablename' => new external_value(PARAM_TEXT, ' the table name'),
                'data' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'data name'),
                            'value' => new external_value(PARAM_RAW, 'data value'),
                        )
                    ), 'the data to be saved'
                )
            )
        );
    }

    /**
     * create new a response
     *
     * @param $data
     * @return array
     * @throws invalid_parameter_exception
     */
    public static function save_response_by_mbl($tablename, $data)
    {
        global $DB;

        $params = array(
            'tablename' => $tablename,
            'data' => $data
        );

        $params = self::validate_parameters(self::save_response_questionnaire_parameters(), $params);

        $data = new stdClass();

        foreach ($params['data'] as $element) {
            $data->$element['name'] = $element['value'];
        }

        $transaction = $DB->start_delegated_transaction();

        $id = $DB->insert_record($tablename, $data);

        $transaction->allow_commit();

        $result = array();
        $result['id'] = $id;

        return $result;
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     * @since Moodle 3.0
     */
    public static function save_response_by_mbl_returns()
    {
        return new external_value(PARAM_INT, 'Standard Moodle primary key.');
    }
    /**
     * update response
     */
    public static function update_response_by_mbl_parameters()
    {
        return new external_function_parameters (
            array(
                'tablename' => new external_value(PARAM_TEXT, ' the table name'),
                'id' => new external_value(PARAM_TEXT, 'key id'),
                'data' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'data name'),
                            'value' => new external_value(PARAM_RAW, 'data value'),
                        )
                    ), 'the data to be saved'
                )
            )
        );
    }

    /**
     * create new a response
     *
     * @param $data
     * @return array
     * @throws invalid_parameter_exception
     */
    public static function update_response_by_mbl($tablename, $id, $data)
    {
        global $DB;

        $warnings = array();

        $params = array(
            'tablename' => $tablename,
            'id' => $id,
            'data' => $data
        );

        $params = self::validate_parameters(self::update_response_by_mbl_parameters(), $params);

        $result = array();

        $data = $DB->get_record($tablename, array('id' => $params['id']), '*', MUST_EXIST);

        if (!$data) {
            $result['status'] = false;
            $warnings['message'] = "Cannot find data record";
            $result['warnings'] = $warnings;

            return $result;
        }

        foreach ($params['data'] as $key => $value) {
            $data->$key = $value;
        }

        $transaction = $DB->start_delegated_transaction();

        $DB->update_record($tablename, $data);

        $transaction->allow_commit();

        $result['status'] = true;
        $result['warnings'] = $warnings;

        return $result;
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     * @since Moodle 3.0
     */
    public static function update_response_by_mbl_returns()
    {
        return new external_single_structure(
            array(
                'status' => new external_value(PARAM_BOOL, 'status: true if success'),
                'warnings' => new external_warnings(),
            )
        );
    }
}
