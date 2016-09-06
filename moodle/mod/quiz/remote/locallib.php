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
 * Library of functions used by the quiz module.
 *
 * This contains functions that are called from within the quiz module only
 * Functions that are also called by core Moodle are in {@link lib.php}
 * This script also loads the code in {@link questionlib.php} which holds
 * the module-indpendent code for handling questions and which in turn
 * initialises all the questiontype classes.
 *
 * @package    mod_quiz
 * @copyright  1999 onwards Martin Dougiamas and others {@link http://moodle.com}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/lib/dml/json_moodle_recordset.php');
require_once($CFG->dirroot . '/lib/dml/mysqli_native_moodle_recordset.php');

require_once($CFG->dirroot . '/lib/remote/lib.php');
require_once($CFG->dirroot . '/lib/additionallib.php');
require_once($CFG->dirroot . '/mnet/service/enrol/locallib.php');
require_once($CFG->dirroot . '/mnet/lib.php');

function get_remote_quiz_by_id($id) {
    global $DB;
    $res = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_quiz_get_quiz_by_id',
            'params' => array('id'=>$id)
        ), false
    );
    $fields = ' remoteid,
                timeopen,
                timeclose,
                timelimit,
                overduehandling,
                graceperiod,
                attempts,
                grademethod,
                timecreated,
                timemodified,
                completionpass,
                completionattemptsexhausted';
    $local_quiz_data = $DB->get_record('quiz', array('remoteid' => $res->id), $fields);
    if(empty($local_quiz_data)){ // check data quiz in local db
        $res->remoteid = $res->id;
        //not get completion setting from hub
        $res->completionpass = 0;
        $res->completionattemptsexhausted = 0;
        $res->settinglocal = false;
    } else {
        foreach ($local_quiz_data as $key => $value){
            $res->$key = $value;
        }
        $res->settinglocal = true;
    }
    return $res;
}

function get_remote_user_attemps($quizid, $userid, $status, $includepreviews) {
    $remote_attempts = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN_M,
            'function_name' => 'mod_quiz_get_user_attempts',
            'params' => array('quizid' => $quizid, 'userid' => $userid, 'status' => $status, 'includepreviews' => $includepreviews)
        ), false
    );
    $result = array();
    foreach ($remote_attempts->attempts as $attempt){
        $result[$attempt->id] = $attempt;
    }
    return $result;
}

function get_remote_quiz_access_information($quizid) {
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN_M,
            'function_name' => 'mod_quiz_get_quiz_access_information',
            'params' => array('quizid'=>$quizid)
        ), false
    );
}

function get_remote_quiz_view_quiz($quizid) {
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN_M,
            'function_name' => 'mod_quiz_view_quiz',
            'params' => array('quizid'=>$quizid)
        ), false
    );
}

function get_remote_user_best_grade($quizid,  $userid) {
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN_M,
            'function_name' => 'mod_quiz_get_user_best_grade',
            'params' => array('quizid' => $quizid, 'userid' => $userid)
        ), false
    );
}

function get_remote_question($quizid) {
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_quiz_get_questions_by_quizid',
            'params' => array('id' => $quizid)
        ), false
    );
}

// Sử dụng API có sẵn mod_quiz_start_attempt để thay thế cho hàm xử lý quiz_prepare_and_start_new_attempt trong startattempt.php
function get_remote_quiz_start_attempt($quizid, $remoteuserid, $preview, $setting) {
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_quiz_start_remote_attempt',
            'params' => array_merge(array('quizid' => $quizid, 'remoteuserid' => $remoteuserid, 'preview' => $preview,
                'preflightdata' => array(), 'forcenew' => true), $setting)
        ), false
    );
}

function get_remote_attempt_by_attemptid($attemptid) {
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_quiz_get_attempt_by_attemptid',
            'params' => array('attemptid' => $attemptid)
        ), false
    );
}

function get_remote_load_questions_usage_by_activity($unique) {
    $record =  moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_quiz_load_questions_usage_by_activity',
            'params' => array('unique' => $unique)
        ), false
    );

    return new json_moodle_recordset($record);
}

function get_remote_get_slots_by_quizid($quizid) {
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_quiz_get_slots_by_quizid',
            'params' => array('quizid' => $quizid)
        ), false
    );
}

function get_remote_get_sections_by_quizid($quizid) {
    $results = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_quiz_get_sections_by_quizid',
            'params' => array('quizid' => $quizid)
        ), false
    );
    $sections = array();
    foreach ($results as $result){
        $sections[$result->id] = $result;
    }
    return $sections;
}

function get_remote_get_attempt_data($attemptid, $page = null, $setting) {
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN_M,
            'function_name' => 'mod_quiz_get_attempt_data',
            'params' => array_merge(array('attemptid' => $attemptid, 'page' => $page), $setting)
        ), false
    );
}

function get_remote_get_attempt_review($attemptid, $page = null, $grading = null) {
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN_M,
            'function_name' => 'mod_quiz_get_attempt_review',
            'params' => array('attemptid' => $attemptid, 'page' => $page, 'grading' => $grading)
        ), false
    );
}

function get_remote_view_attempt_review($attemptid) {
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN_M,
            'function_name' => 'mod_quiz_view_attempt_review',
            'params' => array('attemptid' => $attemptid)
        ), false
    );
}

function get_mod_quiz_process_attempt($attemptid, $data, $finishattempt, $timeup, $setting) {
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN_M,
            'function_name' => 'mod_quiz_process_attempt',
            'params' => array_merge(array('attemptid' => $attemptid, 'finishattempt' => $finishattempt, 'timeup' => $timeup), $data, $setting)
        ), false
    );
}

function get_remote_get_attempt_summary($attemptid, $settinglocal) {
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN_M,
            'function_name' => 'mod_quiz_get_attempt_summary',
            'params' => array_merge(array('attemptid' => $attemptid, 'preflightdata' => array()), $settinglocal)
        ), false
    );
}

function get_remote_quiz_view_attempt_summary($attemptid, $preflightdata = null) {
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN_M,
            'function_name' => 'mod_quiz_view_attempt_summary',
            'params' => array('attemptid' => $attemptid, 'preflightdata' => array())
        ), false
    );
}

function get_remote_view_attempt($attemptid, $page = null, $preflightdata = null) {
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN_M,
            'function_name' => 'mod_quiz_view_attempt',
            'params' => array('attemptid' => $attemptid, 'page' => $page, 'preflightdata' => array())
        ), false
    );
}

function get_remote_count_attempts($quizid) {
    global $CFG;
    $hostname = mnet_get_hostname_from_uri($CFG->wwwroot);
    $hostip = gethostbyname($hostname);
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_quiz_count_attempt_summary',
            'params' => array('quizid' => $quizid, 'ipaddress' => $hostip)
        ), false
    );
}

function get_remote_significant_questions($quizid) {
    $questions = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_quiz_report_get_significant_questions',
            'params' => array('quizid' => $quizid)
        ), false
    );
    $sigquestions = array();
    foreach($questions as $question){
        $sigquestions[$question->slot] = $question;
    }
    return $sigquestions;
}

function get_remote_report_get_grand_total($countsql, $countparam) {
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_quiz_report_get_grand_total',
            'params' => array_merge(array('countsql' => $countsql), $countparam)
        ), false
    );
    
}

function get_remote_report_get_rowdata($sql, $param, $pagestart, $pagesize) {
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_quiz_report_get_rowdata_for_tableview',
            'params' => array_merge(array('sql' => $sql, 'pagestart' => $pagestart, 'pagesize' => $pagesize), $param)
        ), false
    );
}

function get_remote_report_questions_usages($data, $fields = null) {
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_quiz_load_questions_usages_latest_steps',
            'params' => $data
        ), false
    );
}

function get_remote_report_avg_record($from, $where, $question, $params) {
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_quiz_get_report_avg_record',
            'params' => array_merge(array('from' => $from, 'where' => $where), $question, $params)
        ), false
    );
}

function get_remote_check_quiz_grade_by_quizid($quizid) {
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_quiz_report_check_quiz_grade',
            'params' => array('quizid' => $quizid)
        ), false
    );
}

function get_remote_report_grade_bands($sql, $params) {
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_quiz_report_get_grade_bands',
            'params' => array_merge(array('sql' => $sql), $params)
        ), false
    );
}

function get_remote_load_questions_usages_question_state_summary($questions, $params, $where) {
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_quiz_load_questions_usages_question_state_summary',
            'params' => array_merge(array('where' => $where), $questions, $params)
        ), false
    );
}

function get_remote_load_questions_usages_where_question_in_state($qubaparam, $qubawhere, $summarystate,
                                                                  $slot, $questionid, $orderby, $limitfrom, $pagesize) {
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_quiz_load_questions_usages_where_question_in_state',
            'params' => array_merge(array('summarystate' => $summarystate, 'slot' => $slot, 'questionid' => $questionid,
                'orderby' => $orderby, 'limitfrom' => $limitfrom, 'pagesize' => $pagesize, "where" => $qubawhere), $qubaparam)
        ), false
    );
}

function get_remote_attempts_byid($paramdata, $asql, $fields) {
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_quiz_get_remote_attempts_byid',
            'params' => array_merge(array('fields' => $fields, 'asql' => $asql), $paramdata)
        ), false
    );
}

function remote_process_submitted_data($attemptids, $data) {
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_quiz_grading_process_submitted_data',
            'params' => array_merge($attemptids, $data)
        ), false
    );
}

function get_remote_qtype_essay_question_options($questionid) {
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_quiz_get_essay_question_options',
            'params' => array('questionid' => $questionid)
        ), false
    );
}

function get_remote_question_option_answer($questionid) {
    $results = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_quiz_get_question_options_answer',
            'params' => array('questionid' => $questionid)
        ), false
    );
    $optionanswer = array();
    foreach ($results as $result){
        $optionanswer[$result->id] = $result;
    }
    return $optionanswer;
}

function get_remote_question_hints($questionid) {
    $results = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_quiz_get_question_hints',
            'params' => array('questionid' => $questionid)
        ), false
    );
    $questionhints = array();
    foreach ($results as $result){
        $questionhints[$result->id] = $result;
    }
    return $questionhints;
}

function get_remote_question_preload_questions($questionids = null, $extrafields = '', $join = '',
                                               $extraparams = array(), $orderby = '') {
    $results = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_quiz_get_question_preload_question',
            'params' => array_merge(array('extrafields' => $extrafields, 'join' => $join,
                'orderby' => $orderby), $questionids, $extraparams)
        ), false
    );
    $questions = array();
    foreach ($results as $result) {
        $questions[$result->id] = $result;
    }
    return $questions;
}

function get_remote_qtype_multichoice_question_options($questionid) {
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_quiz_get_multichoice_question_options',
            'params' => array('questionid' => $questionid)
        ), false
    );
}

function get_remote_statistic_questions_usages($from, $where, $params, $slots, $fields) {
    $results = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_quiz_get_statistic_questions_usages',
            'params' => array_merge(array('from' => $from, 'where' => $where, 'fields' => $fields), $params, $slots)
        ), false
    );
    $res = array();
    foreach ($results as $result){
        $res[$result->id] = $result;
    }
    return $res;
}

function get_userlocal_by_userhubid($userhubid) {
    global $DB;
    $userinfo = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_quiz_get_userlocal_by_userhubid',
            'params' => array('userid' => $userhubid)
        ), false
    );
    $user = $DB->get_record('user', array('email' => $userinfo->email), 'id', MUST_EXIST);
    return $user->id;
}

function remote_handle_if_time_expired($quizid, $attemptid, $studentisonline, $setting){
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_quiz_handle_if_time_expired',
            'params' => array_merge(array('quizid' => $quizid, 'attemptid' => $attemptid, 'studentisonline' => $studentisonline), $setting)
        ), false
    );
    return $result;
}

function get_statistic_attempt_counts_and_averages($from, $where, $param){
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_quiz_get_statistic_attempt_counts_and_averages',
            'params' => array_merge(array('from' => $from, 'where' => $where), $param)
        ), false
    );
    return $result;
}

function get_statistic_median_mark($sql, $limitoffset, $limit, $param){
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_quiz_get_statistic_median_mark',
            'params' => array_merge(array('sql' => $sql, 'limitoffset' => $limitoffset, 'limit' => $limit), $param)
        ), false
    );
    $res = array();
    foreach($result as $element){
        $res[$element->key] = $element->value;
    }
    return $res;
}

function get_remote_ques_by_category($category){
    $results = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_quiz_get_ques_by_category',
            'params' => array('category' => $category)
        ), false
    );
    $data = array();
    foreach($results as $result){
        $data[$result->id] = $result;
    }
    return $data;
}

function remote_db_get_record($table, $conditions, $fields='*', $strictness=IGNORE_MISSING){
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_quiz_db_get_record',
            'params' => array_merge(array('table' => $table, 'fields' => $fields, 'strictness' => $strictness), $conditions)
        ), false
    );

    $res = new stdClass();
    foreach($result as $element){
        if(!$element->key){
            throw new coding_exception('Invalid local_mod_quiz_db_get_record_sql API. Please check your API');
        }
        $key = $element->key;
        $res->$key= $element->value;
    }
    return $res;
}

/**
 * using API insert_record from host to hub
 */
function remote_db_insert_record($tablename, $dataencode)
{
    $res = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_quiz_db_insert_record',
            'params' => array('tablename' => $tablename, 'dataencode' => $dataencode)
        ), false
    );

    if(!$res or !is_number($res)){
        throw new coding_exception('Invalid local_mod_quiz_db_insert_record API. Please check your API');
    }
    return $res;

}

/**
 * using API delete_records from host to hub
 */
function remote_db_delete_records($table, $condition)
{
    $res = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_quiz_db_delete_records',
            'params' => array_merge(array('table' => $table), $condition)
        ), false
    );
    if($res->status !== true){
        throw new coding_exception('Invalid local_mod_delete_response_by_mbl API. Please check your API');
    }
    return $res;
}

/**
 * using API get_record_sql from host to hub
 */
function remote_db_get_record_sql($sql, $param, $strictness=IGNORE_MISSING)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_quiz_db_get_record_sql',
            'params' => array_merge(array('sql' => $sql, 'strictness' => $strictness), $param)
        ), false
    );
    $res = new stdClass();
    foreach($result as $element){
        if(!$element->key){
            throw new coding_exception('Invalid local_mod_quiz_db_get_record_sql API. Please check your API');
        }
        $key = $element->key;
        $res->$key= $element->value;
    }
    return $res;
}

/**
 * using API record_exists from host to hub
 */
function remote_db_record_exists($table, $condition)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_quiz_db_record_exists',
            'params' => array_merge(array('table' => $table), $condition)
        ), false
    );
    if(!is_bool($result->status)){
        throw new coding_exception('Invalid local_mod_delete_response_by_mbl API. Please check your API');
    }
    return $result->status;
}

