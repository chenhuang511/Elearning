
<?php
/**
 * Created by PhpStorm.
 * User: vanhaIT
 * Date: 30/05/2016
 * Time: 3:50 CH
 */

defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot . '/lib/remote/lib.php');
require_once($CFG->dirroot . '/lib/additionallib.php');


function get_remote_questionnaire_by_id($id) {
    $resp = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_questionnaire_get_question_by_id',
            'params' => array('id' => $id)
        )
    );
    return $resp;
}

function get_remote_field_owner_questionnaire_by_id($id) {
    $resp = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_questionnaire_get_field_owner_questionnaire_by_id',
            'params' => array('id' => $id)
        )
    );
    return $resp;
}

function get_remote_questionnaire_survey_by_id($id) {
    $resp = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_questionnaire_get_questionnaire_survey_by_id',
            'params' => array('id' => $id)
        )
    );
    return $resp;
}

function get_remote_questionnaire_question_by_sid($sid) {
    $resp = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_questionnaire_get_questionnaire_question_by_sid',
            'params' => array(
                'sid' => $sid
            )
        )
    );
    return $resp;
}

function get_remote_questionnaire_response_by_rid($sid) {
    $resp = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_questionnaire_get_questionnaire_response_by_rid',
            'params' => array(
                'sid' => $sid
            )
        )
    );
    return $resp;
}

function get_remote_questionnaire_question_type() {
    $resp = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_questionnaire_get_questionnaire_question_type',
            'params' => array()
        )
    );
    return $resp;
}

function get_remote_questionnaire_quest_choice_by_question_id($question_id) {
    $resp = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_questionnaire_get_questionnaire_quest_choice_by_question_id',
            'params' => array('question_id' => $question_id)
        )
    );
    return $resp;
}
