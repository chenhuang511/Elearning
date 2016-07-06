
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

function get_remote_questionnaire_response_by_rid($rid) {
    $condition = 'id = \''.$rid.'\'';
    $sort = '';
    $res = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_questionnaire_response',
            'params' => array('condition' => $condition, 'sort' => $sort)
        )
    );
    return $res;
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
/**
 * create new a mbl
 *
 * @param $branch
 * @return false|mixed
 */
function save_remote_response_by_mbl($tablename, $data)
{
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_save_response_by_mbl',
            'params' => array_merge(array('tablename' => $tablename), $data)
        )
    );
}

/**
 * create update a mbl
 *
 * @param $branch
 * @return false|mixed
 */
function update_remote_response_by_mbl($tablename, $id, $data)
{
    $res = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_update_response_by_mbl',
            'params' => array_merge(array('tablename' => $tablename, 'id' => $id), $data)
        )
    );
    return $res;
}
/**
 * get questionnaire_attempts by condition
 *
 * @param $branch
 * @return false|mixed
 */
function get_remote_questionnaire_attempts($condition, $sort='')
{
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_questionnaire_attempts',
            'params' => array('condition' => $condition, 'sort' => $sort)
        )
    );
}
/**
 * get questionnaire_response
 *
 * @param $branch
 * @return false|mixed
 */
function get_remote_questionnaire_response($condition, $sort='')
{
    $res = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_questionnaire_response',
            'params' => array('condition' => $condition, 'sort' => $sort)
        )
    );
    return $res;
}
