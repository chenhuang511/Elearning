<?php
defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot . '/lib/remote/lib.php');
require_once($CFG->dirroot . '/lib/additionallib.php');
require_once($CFG->dirroot . '/mnet/service/enrol/locallib.php');

function get_remote_survey_by_id($id)
{
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_survey_by_id',
            'params' => array('id' => $id),
        )
    );
}

function get_remote_survey_answers_by_surveyid_and_userid($surveyid, $userid)
{
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_survey_answers_by_surveyid_and_userid',
            'params' => array('surveyid' => $surveyid, 'userid' => $userid),
        )
    );
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
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_survey_response_by_surveyid',
            'params' => array('surveyid' => $surveyid, 'groupid' => $groupid, 'groupingid' => $groupingid),
        )
    );
}