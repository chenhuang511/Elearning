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

        if (!$survey) {
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
                        'id' => new external_value(PARAM_INT, 'the id'),
                        'course' => new external_value(PARAM_INT, 'the course id'),
                        'template' => new external_value(PARAM_INT, 'template'),
                        'days' => new external_value(PARAM_INT, 'days'),
                        'timecreated' => new external_value(PARAM_INT, 'time created'),
                        'timemodified' => new external_value(PARAM_INT, 'time modified'),
                        'name' => new external_value(PARAM_RAW, 'name'),
                        'intro' => new external_value(PARAM_RAW, 'intro'),
                        'introformat' => new external_value(PARAM_INT, 'intro format'),
                        'questions' => new external_value(PARAM_RAW, 'questions'),
                    )
                ),
                'warnings' => new external_warnings(),
            )
        );
    }

    public static function get_survey_answers_by_surveyid_and_userid_parameters()
    {
        return new external_function_parameters(
            array(
                'surveyid' => new external_value(PARAM_INT, 'the survey id'),
                'userid' => new external_value(PARAM_INT, 'the user id')
            )
        );
    }

    public static function get_survey_answers_by_surveyid_and_userid($surveyid, $userid)
    {
        global $DB;

        $warnings = array();

        $params = self::validate_parameters(self::get_survey_answers_by_surveyid_and_userid_parameters(), array(
            'surveyid' => $surveyid,
            'userid' => $userid
        ));

        $result = array();

        $answers = $DB->record_exists("survey_answers", array("survey" => $params['surveyid'], "userid" => $params['userid']));

        $result['status'] = $answers;
        $result['warnings'] = $warnings;

        return $result;
    }

    public static function get_survey_answers_by_surveyid_and_userid_returns()
    {
        return new external_single_structure(
            array(
                'status' => new external_value(PARAM_BOOL, 'return true: is exists'),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function get_list_survey_questions_by_ids_parameters()
    {
        return new external_function_parameters(
            array(
                'questionids' => new external_value(PARAM_RAW, ' the question ids')
            )
        );
    }

    public static function get_list_survey_questions_by_ids($questionids)
    {
        global $DB;

        $warnings = array();

        $params = self::validate_parameters(self::get_list_survey_questions_by_ids_parameters(), array('questionids' => $questionids));

        $result = array();

        $qids = explode(',', $params['questionids']);

        $questions = $DB->get_records_list("survey_questions", "id", $qids);

        if (!$questions) {
            $questions = array();
        }

        $result['questions'] = $questions;
        $result['warnings'] = $warnings;

        return $result;
    }

    public static function get_list_survey_questions_by_ids_returns()
    {
        return new external_single_structure(
            array(
                'questions' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'id' => new external_value(PARAM_INT, ''),
                            'text' => new external_value(PARAM_RAW, 'text'),
                            'shorttext' => new external_value(PARAM_RAW, 'short text'),
                            'multi' => new external_value(PARAM_RAW, 'multi'),
                            'intro' => new external_value(PARAM_RAW, 'intro'),
                            'type' => new external_value(PARAM_INT, 'type'),
                            'options' => new external_value(PARAM_RAW, 'options'),
                        )
                    )
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function get_survey_responses_by_surveyid_parameters()
    {
        return new external_function_parameters(
            array(
                'surveyid' => new external_value(PARAM_INT, 'survey id'),
                'groupid' => new external_value(PARAM_INT, 'groupid'),
                'groupingid' => new external_value(PARAM_INT, 'groupingid'),
            )
        );
    }

    public static function get_survey_responses_by_surveyid($surveyid, $groupid, $groupingid)
    {
        global $DB;

        $warnings = array();

        $params = self::validate_parameters(self::get_survey_responses_by_surveyid_parameters(), array(
            'surveyid' => $surveyid, 'groupid' => $groupid, 'groupingid' => $groupingid
        ));

        if ($params['groupid']) {
            $groupsjoin = "JOIN {groups_members} gm ON u.id = gm.userid AND gm.groupid = :groupid ";

        } else if ($params['$groupingid']) {
            $groupsjoin = "JOIN {groups_members} gm ON u.id = gm.userid
                       JOIN {groupings_groups} gg ON gm.groupid = gg.groupid AND gg.groupingid = :groupingid ";
        } else {
            $groupsjoin = "";
        }

        $parameters = array('surveyid' => $params['surveyid'], 'groupid' => $params['groupid'], 'groupingid' => $params['groupingid']);

        $result = array();

        $userfields = user_picture::fields('u');

        $response = $DB->get_records_sql("SELECT $userfields, MAX(a.time) as time
                                   FROM {survey_answers} a
                                   JOIN {user} u ON a.userid = u.id
                            $groupsjoin
                                  WHERE a.survey = :surveyid
                               GROUP BY $userfields
                               ORDER BY time ASC", $parameters);

        if(!$response) {
            $response = new stdClass();
        }

        $result['response'] = $response;
        $result['warnings'] = $warnings;

        return $result;
    }

    public static function get_survey_responses_by_surveyid_returns()
    {
        return new external_single_structure(
            array(
                'response' => new external_single_structure(
                    array(
                        'id' => new external_value(PARAM_INT, 'the id'),
                        'picture' => new external_value(PARAM_INT, 'the picture'),
                        'firstname' => new external_value(PARAM_RAW, 'first name'),
                        'lastname' => new external_value(PARAM_RAW, 'last name'),
                        'firstnamephonetic' => new external_value(PARAM_RAW, 'first name phonetic'),
                        'lastnamephonetic' => new external_value(PARAM_RAW, 'last name phonetic'),
                        'middlename' => new external_value(PARAM_RAW, 'middle name'),
                        'alternatename' => new external_value(PARAM_RAW, 'alternate name'),
                        'imagealt' => new external_value(PARAM_RAW, 'image alt'),
                        'email' => new external_value(PARAM_RAW, 'email'),
                        'time' => new external_value(PARAM_INT, 'time'),
                    )
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function survey_save_answers_parameters() {
        return new external_function_parameters(
            array(
                'surveyid' => new external_value(PARAM_INT, 'the survey id'),
                'userid' => new external_value(PARAM_RAW, 'the user id'),
                'formdata' => new external_single_structure(
                    array(
                        'id' => new external_value(PARAM_INT, 'id'),
                        'sesskey' => new external_value(PARAM_TEXT, 'sesskey'),
                        'data' => new external_multiple_structure(
                            new external_single_structure(
                                array(
                                    'name' => new external_value(PARAM_RAW, 'data name'),
                                    'value' => new external_value(PARAM_RAW, 'data value'),
                                )
                            ), 'the data form'
                        )
                    )
                )
            )
        );
    }

    public static function survey_save_answers($surveyid, $userid, $formdata) {

        global $DB;

        $warnings = array();

        $params = self::validate_parameters(self::survey_save_answers_parameters(),array(
            'surveyid' => $surveyid,
            'userid' => $userid,
            'formdata' => $formdata
        ));

        $frmdata = $params['formdata'];

        $answersrawdata = array();
        $answersrawdata['id'] = $frmdata['id'];
        $answersrawdata['sesskey'] = $frmdata['sesskey'];

        foreach ($frmdata['data'] as $element) {
            $answersrawdata[$element['name']] = $element['value'];
        }

        $answers = array();

        // Sort through the data and arrange it.
        // This is necessary because some of the questions may have two answers, eg Question 1 -> 1 and P1.
        foreach ($answersrawdata as $key => $val) {
            if ($key != "userid" && $key != "id") {
                if (substr($key, 0, 1) == "q") {
                    $key = clean_param(substr($key, 1), PARAM_ALPHANUM);   // Keep everything but the 'q', number or P number.
                }
                if (substr($key, 0, 1) == "P") {
                    $realkey = (int)substr($key, 1);
                    $answers[$realkey][1] = $val;
                } else {
                    $answers[$key][0] = $val;
                }
            }
        }

        // Now store the data.
        $timenow = time();
        $answerstoinsert = array();
        foreach ($answers as $key => $val) {
            if ($key != 'sesskey') {
                $newdata = new stdClass();
                $newdata->time = $timenow;
                $newdata->userid = $userid;
                $newdata->survey = $surveyid;
                $newdata->question = $key;
                if (!empty($val[0])) {
                    $newdata->answer1 = $val[0];
                } else {
                    $newdata->answer1 = "";
                }
                if (!empty($val[1])) {
                    $newdata->answer2 = $val[1];
                } else {
                    $newdata->answer2 = "";
                }

                $answerstoinsert[] = $newdata;
            }
        }
        
        $result = array();

        $result['status'] = false;

        if (!empty($answerstoinsert)) {

            $transaction = $DB->start_delegated_transaction();
            
            $DB->insert_records("survey_answers", $answerstoinsert);
            
            $transaction->allow_commit();

            $result['status'] = true;
        }

        $result['warnings'] = $warnings;

        return $result;
    }

    public static function survey_save_answers_returns(){
        return new external_single_structure(
            array(
                'status' => new external_value(PARAM_BOOL, 'status: true if success'),
                'warnings' => new external_warnings(),
            )
        );
    }

    public static function get_survey_answers_by_surveyid_and_questionid_and_userid_parameters() {
        return new external_function_parameters(
            array(
                'surveyid' => new external_value(PARAM_INT, 'the survey id'),
                'questionid' => new external_value(PARAM_INT, 'the question id'),
                'userid' => new external_value(PARAM_INT, 'the user id')
            )
        );
    }
    public static function get_survey_answers_by_surveyid_and_questionid_and_userid($surveyid, $questionid, $userid) {
        global $DB;

        $warnings = array();

        $params = self::validate_parameters(self::get_survey_answers_by_surveyid_and_questionid_and_userid_parameters(), array(
            'surveyid' => $surveyid,
            'questionid' => $questionid,
            'userid' => $userid
        ));

        $result = array();

        $answer = $DB->get_record_sql("SELECT sa.*
                                  FROM {survey_answers} sa
                                 WHERE sa.survey = ?
                                       AND sa.question = ?
                                       AND sa.userid = ?", array($params['surveyid'], $params['questionid'], $params['userid']));

        if(!$answer) {
            $answer = new stdClass();
        }

        $result['answer'] = $answer;
        $result['warnings'] = $warnings;

        return $result;
    }

    public static function get_survey_answers_by_surveyid_and_questionid_and_userid_returns() {
        return new external_single_structure(
            array(
                'answer' => new external_single_structure(
                    array(
                        'id' => new external_value(PARAM_INT, 'id'),
                        'userid' => new external_value(PARAM_INT, 'user id'),
                        'survey' => new external_value(PARAM_INT, 'survey id'),
                        'question' => new external_value(PARAM_INT, 'question id'),
                        'time' => new external_value(PARAM_INT, 'time'),
                        'answer1' => new external_value(PARAM_RAW, 'answer1'),
                        'answer2' => new external_value(PARAM_RAW, 'answer2')
                    )
                ),
                'warnings' => new external_warnings()
            )
        );
    }
}
