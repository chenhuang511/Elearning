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
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_quiz_get_quiz_by_id',
            'params' => array('id'=>$id)
        ), false
    );
}

function get_remote_user_attemps($quizid, $userid, $status, $includepreviews) {
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN_M,
            'function_name' => 'mod_quiz_get_user_attempts',
            'params' => array('quizid' => $quizid, 'userid' => $userid, 'status' => $status, 'includepreviews' => $includepreviews)
        ), false
    );
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


/**
 * Validate permissions for creating a new attempt and start a new preview attempt if required.
 *
 * @param  quiz $quizobj quiz object
 * @param  quiz_access_manager $accessmanager quiz access manager
 * @param  bool $forcenew whether was required to start a new preview attempt
 * @param  int $page page to jump to in the attempt
 * @param  bool $redirect whether to redirect or throw exceptions (for web or ws usage)
 * @return array an array containing the attempt information, access error messages and the page to jump to in the attempt
 * @throws moodle_quiz_exception
 * @since Moodle 3.1
 */
function quiz_remote_validate_new_attempt(quiz $quizobj, quiz_access_manager $accessmanager, $forcenew, $page, $redirect) {
    $timenow = time();

    if ($quizobj->is_preview_user() && $forcenew) {
        $accessmanager->current_attempt_finished();
    }

    // Check capabilities.
    if (!$quizobj->is_preview_user()) {
        $quizobj->require_capability('mod/quiz:attempt');
    }

    // Look for an existing attempt.
    //get user mapping
    $user = get_remote_mapping_user();
    $attempts = get_remote_user_attemps($quizobj->get_quizid(), $user[0]->id, 'all', true)->attempts;
    $lastattempt = end($attempts);

    $attemptnumber = null;
    // If an in-progress attempt exists, check password then redirect to it.
    if ($lastattempt && ($lastattempt->state == quiz_attempt::IN_PROGRESS ||
            $lastattempt->state == quiz_attempt::OVERDUE)) {
        $currentattemptid = $lastattempt->id;
        $messages = $accessmanager->prevent_access();

        // And, if the attempt is now no longer in progress, redirect to the appropriate place.
        if ($lastattempt->state == quiz_attempt::ABANDONED || $lastattempt->state == quiz_attempt::FINISHED) {
            if ($redirect) {
                redirect($quizobj->review_url($lastattempt->id));
            } else {
                throw new moodle_quiz_exception($quizobj, 'attemptalreadyclosed');
            }
        }

        // If the page number was not explicitly in the URL, go to the current page.
        if ($page == -1) {
            $page = $lastattempt->currentpage;
        }

    } else {
        while ($lastattempt && $lastattempt->preview) {
            $lastattempt = array_pop($attempts);
        }

        // Get number for the next or unfinished attempt.
        if ($lastattempt) {
            $attemptnumber = $lastattempt->attempt + 1;
        } else {
            $lastattempt = false;
            $attemptnumber = 1;
        }
        $currentattemptid = null;

        $messages = $accessmanager->prevent_access() +
            $accessmanager->prevent_new_attempt(count($attempts), $lastattempt);

        if ($page == -1) {
            $page = 0;
        }
    }
    return array($currentattemptid, $attemptnumber, $lastattempt, $messages, $page);
}

// Sử dụng API có sẵn mod_quiz_start_attempt để thay thế cho hàm xử lý quiz_prepare_and_start_new_attempt trong startattempt.php
function get_remote_quiz_start_attempt($quizid, $remoteuserid, $preview) {
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_quiz_start_remote_attempt',
            'params' => array('quizid' => $quizid, 'remoteuserid' => $remoteuserid, 'preview' => $preview, 'preflightdata' => array(), 'forcenew' => true)
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
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_quiz_get_sections_by_quizid',
            'params' => array('quizid' => $quizid)
        ), false
    );
}

function get_remote_get_attempt_data($attemptid, $page = null) {
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN_M,
            'function_name' => 'mod_quiz_get_attempt_data',
            'params' => array('attemptid' => $attemptid, 'page' => $page)
        ), false
    );
}

function get_remote_get_attempt_review($attemptid, $page = null) {
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN_M,
            'function_name' => 'mod_quiz_get_attempt_review',
            'params' => array('attemptid' => $attemptid, 'page' => $page)
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

function get_mod_quiz_process_attempt($attemptid, $data, $finishattempt, $timeup) {
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN_M,
            'function_name' => 'mod_quiz_process_attempt',
            'params' => array_merge(array('attemptid' => $attemptid, 'finishattempt' => $finishattempt, 'timeup' => $timeup), $data)
        ), false
    );
}

function get_remote_get_attempt_summary($attemptid, $preflightdata = null) {
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN_M,
            'function_name' => 'mod_quiz_get_attempt_summary',
            'params' => array('attemptid' => $attemptid, 'preflightdata' => array())
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
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_quiz_report_get_significant_questions',
            'params' => array('quizid' => $quizid)
        ), false
    );
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