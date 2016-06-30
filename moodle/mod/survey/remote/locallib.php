<?php
defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot . '/lib/remote/lib.php');
require_once($CFG->dirroot . '/lib/additionallib.php');
require_once($CFG->dirroot . '/mnet/service/enrol/locallib.php');

function get_remote_survey_by_id($id)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_survey_by_id',
            'params' => array('id' => $id),
        )
    );

    return $result->survey;
}

function get_remote_survey_answers_by_surveyid_and_userid($surveyid, $userid)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_survey_answers_by_surveyid_and_userid',
            'params' => array('surveyid' => $surveyid, 'userid' => $userid),
        )
    );

    return $result->status;
}

function get_remote_list_survey_questions_by_ids($questionids)
{
    $questions = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_list_survey_questions_by_ids',
            'params' => array('questionids' => $questionids),
        )
    );

    $finalquestion = array();

    if ($questions) {
        foreach ($questions->questions as $question) {
            $finalquestion[$question->id] = $question;
        }
    }

    return $finalquestion;
}

function get_remote_survey_responses_by_surveyid($surveyid, $groupid, $groupingid)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_survey_response_by_surveyid',
            'params' => array('surveyid' => $surveyid, 'groupid' => $groupid, 'groupingid' => $groupingid),
        )
    );

    return $result->response;
}

function save_remote_survey_answers($surveyid, $userid, $formdata)
{
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_save_survey_answers',
            'params' => array_merge(array('surveyid' => $surveyid, 'userid' => $userid), $formdata),
        )
    );
}

function get_remote_survey_answers_by_surveyid_and_questionid_and_userid($surveyid, $questionid, $userid)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_survey_answers_by_surveyid_and_questionid_and_userid',
            'params' => array('surveyid' => $surveyid, 'questionid' => $questionid, 'userid' => $userid),
        )
    );

    return $result->answer;
}