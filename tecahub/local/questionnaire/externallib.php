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

    public static function questionnaire_get_user_by_id_parameters() {
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
    public static function questionnaire_get_user_by_id($id) {
        global $CFG, $DB;

        //validate parameter
        $params = self::validate_parameters(self::questionnaire_get_user_by_id_parameters(),
            array('id' => $id));
        return $DB->get_record('user', array('id' => $params['id']), '*', MUST_EXIST);
    }

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
     */
    public static function questionnaire_get_user_by_id_returns() {
        return new external_single_structure(
            array(
                'id' => new external_value(PARAM_INT, 'Standard Moodle primary key.'),
                'username' => new external_value(PARAM_TEXT, 'course'),
                'firstname' => new external_value(PARAM_TEXT, 'Question name'),
                'lastname' => new external_value(PARAM_TEXT, 'Question introduction text'),
                'email' => new external_value(PARAM_TEXT, 'questiontext format'),
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
     * get record questionnaire_quest_choice by condition
     */
    public static function get_questionnaire_quest_choice_by_condition_parameters() {
        return self::get_questionnaire_attempts_parameters();
    }

    public function get_questionnaire_quest_choice_by_condition($condition, $sort){
        global $CFG, $DB;

        //validate parameter
        $params = self::validate_parameters(self::get_questionnaire_quest_choice_by_condition_parameters(),
            array('condition' => $condition, 'sort' => $sort));

        return $DB->get_records_select('questionnaire_quest_choice', $params['condition'], null, $params['sort']);
    }

    public static function get_questionnaire_quest_choice_by_condition_returns()
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

        $params = self::validate_parameters(self::save_response_by_mbl_parameters(), $params);

        $data = new stdClass();

        foreach ($params['data'] as $element) {
            $data->$element['name'] = $element['value'];
        }

        $transaction = $DB->start_delegated_transaction();

        $id = $DB->insert_record($tablename, $data);

        $transaction->allow_commit();

        return $id;
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
                'id' => new external_value(PARAM_INT, 'key id'),
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
            $data->$value['name'] = $value['value'];
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
    /**
     * delete response
     */
    public static function delete_response_by_mbl_parameters()
    {
        return new external_function_parameters (
            array(
                'tablename' => new external_value(PARAM_TEXT, ' the table name'),
                'select' => new external_value(PARAM_RAW, 'condition'),
                'sort' => new external_value(PARAM_RAW, 'sort')
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
    public static function delete_response_by_mbl($tablename, $select, $sort)
    {
        global $DB;

        $warnings = array();

        $params = array(
            'tablename' => $tablename,
            'select' => $select,
            'sort' => $sort
        );

        $params = self::validate_parameters(self::delete_response_by_mbl_parameters(), $params);

        $result = array();

        $DB->delete_records_select($params['tablename'], $params['select'] . $params['sort']);

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
    public static function delete_response_by_mbl_returns()
    {
        return new external_single_structure(
            array(
                'status' => new external_value(PARAM_BOOL, 'status: true if success'),
                'warnings' => new external_warnings(),
            )
        );
    }

    /**
     * get record questionnaire attempts by condition
     */
    public static function get_questionnaire_attempts_parameters()
    {
        return new external_function_parameters(
            array(
                'condition' => new external_value(PARAM_RAW, 'condition'),
                'sort'      => new external_value(PARAM_RAW, 'sort')
            )
        );
    }

    public function get_questionnaire_attempts($condition, $sort)
    {
        global $CFG, $DB;

        //validate parameter
        $params = self::validate_parameters(self::get_questionnaire_attempts_parameters(),
            array('condition' => $condition, 'sort' => $sort));

        return $DB->get_records_select('questionnaire_attempts', $params['condition'], null, $params['sort']);
    }

    public static function get_questionnaire_attempts_returns()
    {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'id' => new external_value(PARAM_INT, 'Standard Moodle primary key.'),
                    'qid' => new external_value(PARAM_INT, 'question_id'),
                    'userid' => new external_value(PARAM_INT, 'content'),
                    'rid' => new external_value(PARAM_INT, 'value'),
                    'timemodified' => new external_value(PARAM_INT, 'value'),
                )
            )
        );
    }
    /**
     * get record questionnaire response by condition
     */
    public static function get_questionnaire_response_parameters()
    {
        return self::get_questionnaire_attempts_parameters();
    }

    public function get_questionnaire_response($condition, $sort)
    {
        global $CFG, $DB;
        //validate parameter
        $params = self::validate_parameters(self::get_questionnaire_response_parameters(),
            array('condition' => $condition, 'sort' => $sort));

        $sql = 'SELECT * '.
            'FROM {questionnaire_response} '.
            'WHERE '.$params['condition'];

        if(!empty($params['sort'])){
            $sql .= ' ORDER BY '.$params['sort'];
        }
        $res = array();
        $res = $DB->get_records_sql($sql);
        return $res;
    }

    public static function get_questionnaire_response_returns()
    {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'id' => new external_value(PARAM_INT, 'Standard Moodle primary key.'),
                    'survey_id' => new external_value(PARAM_INT, 'question_id'),
                    'submitted' => new external_value(PARAM_INT, 'content'),
                    'complete' => new external_value(PARAM_TEXT, 'value'),
                    'grade' => new external_value(PARAM_INT, 'value'),
                    'username' => new external_value(PARAM_TEXT, 'value'),
                )
            )
        );
    }
    /**
     * get record questionnaire response by condition
     */
    public static function get_questionnaire_response_group_username_parameters()
    {
        return self::get_questionnaire_attempts_parameters();
    }

    public function get_questionnaire_response_group_username($condition, $sort)
    {
        global $CFG, $DB;
        //validate parameter
        $params = self::validate_parameters(self::get_questionnaire_response_group_username_parameters(),
            array('condition' => $condition, 'sort' => $sort));

        $sql = 'SELECT username '.
            'FROM {questionnaire_response} '.
            'WHERE '.$params['condition'] . ' GROUP BY username ';
        if(!empty($params['sort'])){
            $sql .= ' ORDER BY '.$params['sort'];
        }
        $res = array();
        $res = $DB->get_records_sql($sql);
        return $res;
    }

    public static function get_questionnaire_response_group_username_returns()
    {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'username' => new external_value(PARAM_TEXT, 'value'),
                )
            )
        );
    }
    /**
     * get record questionnaire response by condition
     */
    public static function get_questionnaire_response_user_parameters()
    {
        return self::get_questionnaire_attempts_parameters();
    }

    public function get_questionnaire_response_user($condition, $sort)
    {
        global $CFG, $DB;
        //validate parameter
        $params = self::validate_parameters(self::get_questionnaire_response_user_parameters(),
            array('condition' => $condition, 'sort' => $sort));

        $sql = 'SELECT R.id AS responseid, R.submitted AS submitted, U.username AS username, U.id as userid '.
            'FROM {questionnaire_response} R, {user} U, {mnet_host} M '.
            ' WHERE M.id = U.MNETHOSTID AND '.$params['condition'];
        if(!empty($params['sort'])){
            $sql .= ' ORDER BY '.$params['sort'];
        }
        $res = array();
        $res = $DB->get_records_sql($sql);
        return $res;
    }

    public static function get_questionnaire_response_user_returns()
    {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'responseid' => new external_value(PARAM_INT, 'Standard Moodle primary key.'),
                    'userid' => new external_value(PARAM_INT, 'Standard Moodle primary key.'),
                    'submitted' => new external_value(PARAM_INT, 'content'),
                    'username' => new external_value(PARAM_TEXT, 'value'),
                )
            )
        );
    }
    /**
     * get record questionnaire response by condition
     */
    public static function get_questionnaire_bool_question_parameters()
    {
        return self::get_questionnaire_attempts_parameters();
    }

    public function get_questionnaire_bool_question($condition, $sort)
    {
        global $CFG, $DB;
        //validate parameter
        $params = self::validate_parameters(self::get_questionnaire_bool_question_parameters(),
            array('condition' => $condition, 'sort' => $sort));
        $sql = 'SELECT q.id, q.type_id as q_type, q.content, a.choice_id '.
            'FROM {questionnaire_response_bool} a, {questionnaire_question} q '.
            'WHERE '.$params['condition'];
        if(!empty($params['sort'])){
            $sql .= ' ORDER BY '.$params['sort'];
        }
        $res = array();
        $res = $DB->get_records_sql($sql);
        return $res;
    }

    public static function get_questionnaire_bool_question_returns()
    {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'id' => new external_value(PARAM_INT, 'question id'),
                    'q_type' => new external_value(PARAM_INT, 'question_type id'),
                    'content' => new external_value(PARAM_RAW, 'content'),
                    'choice_id' => new external_value(PARAM_TEXT, 'choice id'),
                )
            )
        );
    }
    /**
 * get record questionnaire response by condition
 */
    //continue
    public static function get_questionnaire_single_question_choice_parameters()
    {
        return self::get_questionnaire_attempts_parameters();
    }

    public function get_questionnaire_single_question_choice($condition, $sort)
    {
        global $CFG, $DB;
        //validate parameter
        $params = self::validate_parameters(self::get_questionnaire_single_question_choice_parameters(),
            array('condition' => $condition, 'sort' => $sort));

        $sql = 'SELECT q.id, q.content, q.type_id as q_type, c.content as ccontent,c.id as cid, c.value as score '.
            'FROM {questionnaire_resp_single} a, {questionnaire_question} q, {questionnaire_quest_choice} c '.
            'WHERE '.$params['condition'];
        if(!empty($params['sort'])){
            $sql .= ' ORDER BY '.$params['sort'];
        }
        $res = array();
        $res = $DB->get_records_sql($sql);
        return $res;
    }

    public static function get_questionnaire_single_question_choice_returns()
    {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'id' => new external_value(PARAM_INT, 'question id'),
                    'cid' => new external_value(PARAM_INT, 'choice id'),
                    'q_type' => new external_value(PARAM_INT, 'question type id'),
                    'content' => new external_value(PARAM_RAW, 'question content'),
                    'ccontent' => new external_value(PARAM_RAW, 'choice content'),
                    'score' => new external_value(PARAM_RAW, 'choice content'),
                )
            )
        );
    }
    /**
 * get record questionnaire response by condition
 */
    //continue
    public static function get_questionnaire_multiple_question_choice_parameters()
    {
        return self::get_questionnaire_attempts_parameters();
    }

    public function get_questionnaire_multiple_question_choice($condition, $sort)
    {
        global $CFG, $DB;
        //validate parameter
        $params = self::validate_parameters(self::get_questionnaire_multiple_question_choice_parameters(),
            array('condition' => $condition, 'sort' => $sort));

        $sql = 'SELECT a.id as aid, q.id as qid, q.content, c.content as ccontent,c.id as cid '.
            'FROM {questionnaire_resp_multiple} a, {questionnaire_question} q, {questionnaire_quest_choice} c '.
            'WHERE '.$params['condition'];
        if(!empty($params['sort'])){
            $sql .= ' ORDER BY '.$params['sort'];
        }
        $res = array();
        $res = $DB->get_records_sql($sql);
        return $res;
    }

    public static function get_questionnaire_multiple_question_choice_returns()
    {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'aid' => new external_value(PARAM_INT, 'Standard Moodle primary key.'),
                    'qid' => new external_value(PARAM_INT, 'question_id'),
                    'cid' => new external_value(PARAM_INT, 'question_id'),
                    'content' => new external_value(PARAM_RAW, 'content'),
                    'ccontent' => new external_value(PARAM_RAW, 'value'),
                )
            )
        );
    }
    /**
 * get record questionnaire response by condition
 */
    //continue
    public static function get_questionnaire_gest_choice_parameters()
    {
        return self::get_questionnaire_attempts_parameters();
    }

    public function get_questionnaire_gest_choice($condition, $sort)
    {
        global $CFG, $DB;
        //validate parameter
        $params = self::validate_parameters(self::get_questionnaire_gest_choice_parameters(),
            array('condition' => $condition, 'sort' => $sort));

        return $DB->get_records_select('questionnaire_response', $params['condition'], null, $params['sort']);
    }

    public static function get_questionnaire_gest_choice_returns()
    {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'id' => new external_value(PARAM_INT, 'Standard Moodle primary key.'),
                    'survey_id' => new external_value(PARAM_INT, 'question_id'),
                    'submitted' => new external_value(PARAM_INT, 'content'),
                    'complete' => new external_value(PARAM_TEXT, 'value'),
                    'grade' => new external_value(PARAM_INT, 'value'),
                    'username' => new external_value(PARAM_TEXT, 'value'),
                )
            )
        );
    }
    /**
 * get record questionnaire response by condition
 */
    //continue
    public static function get_questionnaire_other_question_choice_parameters()
    {
        return self::get_questionnaire_attempts_parameters();
    }

    public function get_questionnaire_other_question_choice($condition, $sort)
    {
        global $CFG, $DB;
        //validate parameter
        $params = self::validate_parameters(self::get_questionnaire_other_question_choice_parameters(),
            array('condition' => $condition, 'sort' => $sort));

        $sql = 'SELECT c.id as cid, c.content as content, a.response as aresponse, q.id as qid, q.position as position,
                                    q.type_id as type_id, q.name as name '.
            'FROM {questionnaire_response_other} a, {questionnaire_question} q, {questionnaire_quest_choice} c '.
            'WHERE '.$params['condition'];
        if(!empty($params['sort'])){
            $sql .= ' ORDER BY '.$params['sort'];
        }
        $res = array();
        $res = $DB->get_records_sql($sql);
        return $res;
    }

    public static function get_questionnaire_other_question_choice_returns()
    {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'cid' => new external_value(PARAM_INT, 'Standard Moodle primary key.'),
                    'qid' => new external_value(PARAM_INT, 'Standard Moodle primary key.'),
                    'type_id' => new external_value(PARAM_INT, 'question_id'),
                    'position' => new external_value(PARAM_INT, 'question_id'),
                    'name' => new external_value(PARAM_TEXT, 'content'),
                    'content' => new external_value(PARAM_RAW, 'content'),
                    'aresponse' => new external_value(PARAM_RAW, 'value'),
                )
            )
        );
    }
    /**
 * get record questionnaire response by condition
 */
    //continue
    public static function get_questionnaire_rank_question_choice_parameters()
    {
        return self::get_questionnaire_attempts_parameters();
    }

    public function get_questionnaire_rank_question_choice($condition, $sort)
    {
        global $CFG, $DB;
        //validate parameter
        $params = self::validate_parameters(self::get_questionnaire_rank_question_choice_parameters(),
            array('condition' => $condition, 'sort' => $sort));

        $sql = 'SELECT a.id as aid, q.id AS qid, q.precise AS precise, c.id AS cid, q.content, c.content as ccontent,
                                a.rank as arank '.
            'FROM {questionnaire_response_rank} a, {questionnaire_question} q, {questionnaire_quest_choice} c '.
            'WHERE '.$params['condition'];
        if(!empty($params['sort'])){
            $sql .= ' ORDER BY '.$params['sort'];
        }
        $res = array();
        $res = $DB->get_records_sql($sql);
        return $res;
    }

    public static function get_questionnaire_rank_question_choice_returns()
    {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'aid' => new external_value(PARAM_INT, 'Standard Moodle primary key.'),
                    'qid' => new external_value(PARAM_INT, 'Standard Moodle primary key.'),
                    'precise' => new external_value(PARAM_INT, 'question_id'),
                    'cid' => new external_value(PARAM_INT, 'question_id'),
                    'content' => new external_value(PARAM_RAW, 'content'),
                    'ccontent' => new external_value(PARAM_RAW, 'value'),
                    'arank' => new external_value(PARAM_INT, 'content'),
                )
            )
        );
    }
    /**
 * get record questionnaire response by condition
 */
    //continue
    public static function get_questionnaire_text_question_parameters()
    {
        return self::get_questionnaire_attempts_parameters();
    }

    public function get_questionnaire_text_question($condition, $sort)
    {
        global $CFG, $DB;
        //validate parameter
        $params = self::validate_parameters(self::get_questionnaire_text_question_parameters(),
            array('condition' => $condition, 'sort' => $sort));

        $sql = 'SELECT q.id, q.content, a.response as aresponse '.
            'FROM {questionnaire_response_text} a, {questionnaire_question} q '.
            'WHERE '.$params['condition'];
        if(!empty($params['sort'])){
            $sql .= ' ORDER BY '.$params['sort'];
        }
        $res = array();
        $res = $DB->get_records_sql($sql);
        return $res;
    }

    public static function get_questionnaire_text_question_returns()
    {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'id' => new external_value(PARAM_INT, 'Standard Moodle primary key.'),
                    'content' => new external_value(PARAM_RAW, 'question_id'),
                    'aresponse' => new external_value(PARAM_RAW, 'content'),
                )
            )
        );
    }
    /**
    * get record questionnaire response by condition
    */
    public static function get_questionnaire_date_question_parameters()
    {
        return self::get_questionnaire_attempts_parameters();
    }

    public function get_questionnaire_date_question($condition, $sort)
    {
        global $CFG, $DB;
        //validate parameter
        $params = self::validate_parameters(self::get_questionnaire_date_question_parameters(),
            array('condition' => $condition, 'sort' => $sort));

        $sql = 'SELECT q.id, q.content, a.response as aresponse '.
            'FROM {questionnaire_response_date} a, {questionnaire_question} q '.
            'WHERE '.$params['condition'];
        if(!empty($params['sort'])){
            $sql .= ' ORDER BY '.$params['sort'];
        }
        $res = array();
        $res = $DB->get_records_sql($sql);
        return $res;
    }

    public static function get_questionnaire_date_question_returns()
    {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'id' => new external_value(PARAM_INT, 'Standard Moodle primary key.'),
                    'content' => new external_value(PARAM_RAW, 'question_id'),
                    'aresponse' => new external_value(PARAM_RAW, 'content'),
                )
            )
        );
    }
    /**
    * get record questionnaire choice and single
    */
    public static function get_questionnaire_choice_single_parameters()
    {
        return self::get_questionnaire_attempts_parameters();
    }

    public function get_questionnaire_choice_single($condition, $sort)
    {
        global $CFG, $DB;
        //validate parameter
        $params = self::validate_parameters(self::get_questionnaire_choice_single_parameters(),
            array('condition' => $condition, 'sort' => $sort));

        $sql = 'SELECT rt.id, qc.id as cid, qc.content '.
            'FROM {questionnaire_quest_choice} qc, '.
            '{questionnaire_resp_single} rt '.
            'WHERE '.$params['condition'];
        if(!empty($params['sort'])){
            $sql .= ' ORDER BY '.$params['sort'];
        }
        $res = array();
        $res = $DB->get_records_sql($sql);
        return $res;
    }

    public static function get_questionnaire_choice_single_returns()
    {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'id' => new external_value(PARAM_INT, 'Standard Moodle primary key.'),
                    'content' => new external_value(PARAM_RAW, 'question_id'),
                    'cid' => new external_value(PARAM_INT, 'content'),
                )
            )
        );
    }
    /**
    * get record questionnaire choice and single
    */
    public static function get_questionnaire_choice_multiple_parameters()
    {
        return self::get_questionnaire_attempts_parameters();
    }

    public function get_questionnaire_choice_multiple($condition, $sort)
    {
        global $CFG, $DB;
        //validate parameter
        $params = self::validate_parameters(self::get_questionnaire_choice_multiple_parameters(),
            array('condition' => $condition, 'sort' => $sort));

        $sql = 'SELECT rt.id, qc.id as cid, qc.content '.
            'FROM {questionnaire_quest_choice} qc, '.
            '{questionnaire_resp_multiple} rt '.
            'WHERE '.$params['condition'];
        if(!empty($params['sort'])){
            $sql .= ' ORDER BY '.$params['sort'];
        }
        $res = array();
        $res = $DB->get_records_sql($sql);
        return $res;
    }

    public static function get_questionnaire_choice_multiple_returns()
    {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'id' => new external_value(PARAM_INT, 'Standard Moodle primary key.'),
                    'content' => new external_value(PARAM_RAW, 'question_id'),
                    'cid' => new external_value(PARAM_INT, 'content'),
                )
            )
        );
    }
    /**
    * get record questionnaire choice and other
    */
    public static function get_questionnaire_choice_other_parameters()
    {
        return self::get_questionnaire_attempts_parameters();
    }

    public function get_questionnaire_choice_other($condition, $sort)
    {
        global $CFG, $DB;
        //validate parameter
        $params = self::validate_parameters(self::get_questionnaire_gest_choice_parameters(),
            array('condition' => $condition, 'sort' => $sort));

        $sql = 'SELECT rt.id, rt.response, qc.content ' .
            'FROM {questionnaire_response_other} rt, ' .
            '{questionnaire_quest_choice} qc ' .
            'WHERE '.$params['condition'];
        if(!empty($params['sort'])){
            $sql .= ' ORDER BY '.$params['sort'];
        }
        $res = array();
        $res = $DB->get_records_sql($sql);
        return $res;
    }

    public static function get_questionnaire_choice_other_returns()
    {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'id' => new external_value(PARAM_INT, 'Standard Moodle primary key.'),
                    'content' => new external_value(PARAM_RAW, 'question_id'),
                    'response' => new external_value(PARAM_RAW, 'content'),
                )
            )
        );
    }
    /**
    * get record questionnaire fb selects by condition
    */
    public static function get_questionnaire_fb_sections_parameters()
    {
        return self::get_questionnaire_attempts_parameters();
    }

    public function get_questionnaire_fb_sections($condition, $sort)
    {
        global $CFG, $DB;
        //validate parameter
        $params = self::validate_parameters(self::get_questionnaire_fb_sections_parameters(),
            array('condition' => $condition, 'sort' => $sort));

        return $DB->get_records_select('questionnaire_fb_sections', $params['condition'], null, $params['sort']);
    }

    public static function get_questionnaire_fb_sections_returns()
    {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'id' => new external_value(PARAM_INT, 'Standard Moodle primary key.'),
                    'survey_id' => new external_value(PARAM_INT, 'question_id'),
                    'section' => new external_value(PARAM_INT, 'content'),
                    'scorecalculation' => new external_value(PARAM_RAW, 'content'),
                    'sectionlabel' => new external_value(PARAM_TEXT, 'content'),
                    'sectionheading' => new external_value(PARAM_RAW, 'content'),
                    'sectionheadingformat' => new external_value(PARAM_INT, 'content'),
                )
            )
        );
    }
    /**
    * get record questionnaire feedback by condition
    */
    public static function get_questionnaire_feedback_parameters()
    {
        return self::get_questionnaire_attempts_parameters();
    }

    public function get_questionnaire_feedback($condition, $sort)
    {
        global $CFG, $DB;
        //validate parameter
        $params = self::validate_parameters(self::get_questionnaire_gest_choice_parameters(),
            array('condition' => $condition, 'sort' => $sort));

        return $DB->get_records_select('questionnaire_feedback', $params['condition'], null, $params['sort']);
    }

    public static function get_questionnaire_feedback_returns()
    {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'id' => new external_value(PARAM_INT, 'Standard Moodle primary key.'),
                    'section_id' => new external_value(PARAM_INT, 'question_id'),
                    'feedbacklabel' => new external_value(PARAM_TEXT, 'content'),
                    'feedbacktext' => new external_value(PARAM_RAW, 'content'),
                    'feedbacktextformat' => new external_value(PARAM_INT, 'content'),
                    'minscore' => new external_value(PARAM_INT, 'content'),
                    'maxscore' => new external_value(PARAM_INT, 'content'),
                )
            )
        );
    }
    /**
    * get record questionnaire question by condition
    */
    public static function get_questionnaire_question_parameters()
    {
        return self::get_questionnaire_attempts_parameters();
    }

    public function get_questionnaire_question($condition, $sort)
    {
        global $CFG, $DB;
        //validate parameter
        $params = self::validate_parameters(self::get_questionnaire_question_parameters(),
            array('condition' => $condition, 'sort' => $sort));
        $sql = 'SELECT a.name as name, a.type_id as q_type, a.position as pos ' .
            'FROM {questionnaire_question} a WHERE ' . $params['condition'];
        if(!empty($params['sort'])){
            $sql .= ' ORDER BY '.$params['sort'];
        }
        $res = array();
        $res = $DB->get_records_sql($sql);
        return $res;
    }

    public static function get_questionnaire_question_returns()
    {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'name' => new external_value(PARAM_TEXT, 'content'),
                    'q_type' => new external_value(PARAM_INT, 'content'),
                    'pos' => new external_value(PARAM_INT, 'content'),
                )
            )
        );
    }
    /**
    * get record questionnaire date by condition
    */
    public static function get_questionnaire_date_parameters()
    {
        return self::get_questionnaire_attempts_parameters();
    }

    public function get_questionnaire_date($condition, $sort)
    {
        global $CFG, $DB;
        //validate parameter
        $params = self::validate_parameters(self::get_questionnaire_date_parameters(),
            array('condition' => $condition, 'sort' => $sort));

        $sql = 'SELECT id, response ' .
            'FROM {questionnaire_response_date} ' .
            'WHERE ' . $params['condition'];
        if(!empty($params['sort'])){
            $sql .= ' ORDER BY '.$params['sort'];
        }
        $res = array();
        $res = $DB->get_records_sql($sql);
        return $res;
    }

    public static function get_questionnaire_date_returns()
    {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'id' => new external_value(PARAM_INT, 'Standard Moodle primary key.'),
                    'response' => new external_value(PARAM_RAW, 'content'),
                )
            )
        );
    }
    /**
    * get record questionnaire bool count questionnaire choice
    */
    public static function get_questionnaire_bool_count_choice_parameters()
    {
        return self::get_questionnaire_attempts_parameters();
    }

    public function get_questionnaire_bool_count_choice($condition, $sort)
    {
        global $CFG, $DB;
        //validate parameter
        $params = self::validate_parameters(self::get_questionnaire_bool_count_choice_parameters(),
            array('condition' => $condition, 'sort' => $sort));

        $sql = 'SELECT choice_id, COUNT(response_id) AS num ' .
            'FROM {questionnaire_response_bool} ' .
            'WHERE ' . $params['condition'];
        $sql .= ' GROUP BY choice_id';
        if(!empty($params['sort'])){
            $sql .= ' ORDER BY '.$params['sort'];
        }
        $res = array();
        $res = $DB->get_records_sql($sql);
        return $res;
    }

    public static function get_questionnaire_bool_count_choice_returns()
    {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'choice_id' => new external_value(PARAM_TEXT, 'Standard Moodle primary key.'),
                    'num' => new external_value(PARAM_INT, 'question_id'),
                )
            )
        );
    }
    /**
    * get record questionnaire text, response and user
    */
    public static function get_questionnaire_text_response_user_parameters()
    {
        return self::get_questionnaire_attempts_parameters();
    }

    public function get_questionnaire_text_response_user($condition, $sort)
    {
        global $CFG, $DB;
        //validate parameter
        $params = self::validate_parameters(self::get_questionnaire_text_response_user_parameters(),
            array('condition' => $condition, 'sort' => $sort));

        $sql = 'SELECT t.id, t.response, r.submitted AS submitted, r.username, u.username AS username, ' .
            'u.id as userid, ' .
            'r.survey_id, r.id AS rid ' .
            'FROM {questionnaire_response_text} t, ' .
            '{questionnaire_response} r, ' .
            '{user} u ' .
            'WHERE ' . $params['condition'];
        if(!empty($params['sort'])){
            $sql .= ' ORDER BY '.$params['sort'];
        }
        $res = array();
        $res = $DB->get_records_sql($sql);
        return $res;
    }

    public static function get_questionnaire_text_response_user_returns()
    {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'id' => new external_value(PARAM_INT, 'Standard Moodle primary key.'),
                    'response' => new external_value(PARAM_RAW, 'question_id'),
                    'submitted' => new external_value(PARAM_INT, 'content'),
                    'username' => new external_value(PARAM_TEXT, 'content'),
                    'userid' => new external_value(PARAM_INT, 'content'),
                    'survey_id' => new external_value(PARAM_INT, 'content'),
                    'rid' => new external_value(PARAM_INT, 'content'),
                )
            )
        );
    }
    /**
    * get record questionnaire choice and rank
    */
    public static function get_questionnaire_choice_rank_parameters()
    {
        return self::get_questionnaire_attempts_parameters();
    }

    public function get_questionnaire_choice_rank($condition, $sort)
    {
        global $CFG, $DB;
        //validate parameter
        $params = self::validate_parameters(self::get_questionnaire_choice_rank_parameters(),
            array('condition' => $condition, 'sort' => $sort));

        $sql = "SELECT r.id, c.content, r.rank, c.id AS choiceid
            FROM {questionnaire_quest_choice} c, {questionnaire_response_rank} r
            WHERE " . $params['condition'];
        if(!empty($params['sort'])){
            $sql .= ' ORDER BY '.$params['sort'];
        }
        $res = array();
        $res = $DB->get_records_sql($sql);
        return $res;
    }

    public static function get_questionnaire_choice_rank_returns()
    {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'id' => new external_value(PARAM_INT, 'Standard Moodle primary key.'),
                    'content' => new external_value(PARAM_RAW, 'question_id'),
                    'rank' => new external_value(PARAM_INT, 'content'),
                    'choiceid' => new external_value(PARAM_INT, 'content'),
                )
            )
        );
    }
    /**
    * get record questionnaire choice, rank and average
    */
    public static function get_questionnaire_choice_rank_average_parameters()
    {
        return self::get_questionnaire_attempts_parameters();
    }

    public function get_questionnaire_choice_rank_average($condition, $sort)
    {
        global $CFG, $DB;
        //validate parameter
        $params = self::validate_parameters(self::get_questionnaire_choice_rank_average_parameters(),
            array('condition' => $condition, 'sort' => $sort));

        $sql = "SELECT c.id, c.content, a.average, a.num
                        FROM {questionnaire_quest_choice} c
                        INNER JOIN
                             (SELECT c2.id, AVG(a2.rank+1) AS average, COUNT(a2.response_id) AS num
                              FROM {questionnaire_quest_choice} c2, {questionnaire_response_rank} a2
                              WHERE " . $params['condition'] . "
                              GROUP BY c2.id) a ON a.id = c.id";
        if(!empty($params['sort'])){
            $sql .= ' ORDER BY '.$params['sort'];
        }
        $res = array();
        $res = $DB->get_records_sql($sql);
        return $res;
    }

    public static function get_questionnaire_choice_rank_average_returns()
    {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'id' => new external_value(PARAM_INT, 'Standard Moodle primary key.'),
                    'content' => new external_value(PARAM_RAW, 'question_id'),
                    'average' => new external_value(PARAM_FLOAT, 'content'),
                    'num' => new external_value(PARAM_INT, 'content'),
                )
            )
        );
    }
    /**
    * get record questionnaire choice, rank and sum
    */
    public static function get_questionnaire_choice_rank_sum_parameters()
    {
        return self::get_questionnaire_attempts_parameters();
    }

    public function get_questionnaire_choice_rank_sum($condition, $sort)
    {
        global $CFG, $DB;
        //validate parameter
        $params = self::validate_parameters(self::get_questionnaire_choice_rank_sum_parameters(),
            array('condition' => $condition, 'sort' => $sort));

        $sql = "SELECT c.id, c.content, a.sum, a.num
                        FROM {questionnaire_quest_choice} c
                        INNER JOIN
                             (SELECT c2.id, SUM(a2.rank+1) AS sum, COUNT(a2.response_id) AS num
                              FROM {questionnaire_quest_choice} c2, {questionnaire_response_rank} a2
                              WHERE " . $params['condition'] . "
                              GROUP BY c2.id) a ON a.id = c.id";
        if(!empty($params['sort'])){
            $sql .= ' ORDER BY '.$params['sort'];
        }
        $res = array();
        $res = $DB->get_records_sql($sql);
        return $res;
    }

    public static function get_questionnaire_choice_rank_sum_returns()
    {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'id' => new external_value(PARAM_INT, 'Standard Moodle primary key.'),
                    'content' => new external_value(PARAM_RAW, 'question_id'),
                    'sum' => new external_value(PARAM_INT, 'content'),
                    'num' => new external_value(PARAM_INT, 'content'),
                )
            )
        );
    }
    /**
    * get record questionnaire rank and count response
    */
    public static function get_questionnaire_rank_count_response_parameters()
    {
        return self::get_questionnaire_attempts_parameters();
    }

    public function get_questionnaire_rank_count_response($condition, $sort)
    {
        global $CFG, $DB;
        //validate parameter
        $params = self::validate_parameters(self::get_questionnaire_rank_count_response_parameters(),
            array('condition' => $condition, 'sort' => $sort));

        $sql = 'SELECT A.rank, COUNT(A.response_id) AS num ' .
            'FROM {questionnaire_response_rank} A ' .
            'WHERE' . $params['condition'] . ' ' .
            'GROUP BY A.rank';
        if(!empty($params['sort'])){
            $sql .= ' ORDER BY '.$params['sort'];
        }
        $res = array();
        $res = $DB->get_records_sql($sql);
        return $res;
    }

    public static function get_questionnaire_rank_count_response_returns()
    {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'rank' => new external_value(PARAM_INT, 'Standard Moodle primary key.'),
                    'num' => new external_value(PARAM_INT, 'question_id'),
                )
            )
        );
    }

    public static function questionnaire_get_attempts_course_parameters() {
        return self::get_questionnaire_attempts_parameters();
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
    public static function questionnaire_get_attempts_course($condition, $sort) {
        global $CFG, $DB;
        //validate parameter
        $params = self::validate_parameters(self::questionnaire_get_attempts_course_parameters(),
            array('condition' => $condition, 'sort' => $sort));

        $sql = 'SELECT q.id, q.course, c.fullname '.
            'FROM {questionnaire} q, {questionnaire_attempts} qa, {course} c '.
            'WHERE ' . $params['condition'];
        if(!empty($params['sort'])){
            $sql .= ' ORDER BY '.$params['sort'];
        }
        $res = array();
        $res = $DB->get_records_sql($sql);
        return $res;
    }

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
     */
    public static function questionnaire_get_attempts_course_returns() {
        return new external_single_structure(
            array(
                'id' => new external_value(PARAM_INT, 'Standard Moodle primary key.'),
                'course' => new external_value(PARAM_INT, 'course'),
                'fullname' => new external_value(PARAM_TEXT, 'Question name'),
            )
        );
    }
}
