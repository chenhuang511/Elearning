
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

/**
 * func return array with changed key for multiple array[]=obj
 * @param $id
 * @return false|mixed
 */
function change_key_by_value($array = array(), $key = ''){

    if(empty($key)){
       return $array;
    }
    $keys = array_map(function ($ar) use ($key) {
       return $ar->$key;
    }, $array);
    return array_combine($keys, $array);
}

/**
 * func get questionnaire by id
 * @param $id
 * @return false|mixed
 */
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
    $res = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_questionnaire_response',
            'params' => array('condition' => $condition, 'sort' => '')
        )
    );
    if(empty($res[0])){
        return false;
    }
    return $res[0];
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

function get_remote_questionnaire_quest_choice_by_condition($condition, $sort='') {
    $resp = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_questionnaire_quest_choice_by_condition',
            'params' => array('condition' => $condition, 'sort' => $sort)
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
function save_remote_response_by_tbl($tablename, $data)
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
function update_remote_response_by_tbl($tablename, $id, $data)
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
        ), false
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
        ), false
    );
    return $res;
}
/**
 * get questionnaire_response
 *
 * @param $branch
 * @return false|mixed
 */
function get_remote_questionnaire_response_group_username($condition, $sort='')
{
    $res = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_questionnaire_response_group_username',
            'params' => array('condition' => $condition, 'sort' => $sort)
        )
    );
    return $res;
}

/**
 * get questionnaire boolean and question
 *
 * @param $branch
 * @return false|mixed
 */
function get_remote_questionnaire_bool_question($condition, $sort='')
{
    $res = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_questionnaire_bool_question',
            'params' => array('condition' => $condition, 'sort' => $sort)
        )
    );
    return $res;
}

/**
 * get questionnaire boolean and question
 *
 * @param $branch
 * @return false|mixed
 */
function get_remote_questionnaire_single_question_choice($condition, $sort='')
{
    $res = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_questionnaire_single_question_choice',
            'params' => array('condition' => $condition, 'sort' => $sort)
        )
    );
    return $res;
}

/**
 * get questionnaire boolean and question
 *
 * @param $branch
 * @return false|mixed
 */
function get_remote_questionnaire_multiple_question_choice($condition, $sort='')
{
    $res = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_questionnaire_multiple_question_choice',
            'params' => array('condition' => $condition, 'sort' => $sort)
        )
    );
    return $res;
}

/**
 * get questionnaire boolean and question
 *
 * @param $branch
 * @return false|mixed
 */
function get_remote_questionnaire_other_question_choice($condition, $sort='')
{
    $res = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_questionnaire_other_question_choice',
            'params' => array('condition' => $condition, 'sort' => $sort)
        )
    );
    return $res;
}

/**
 * get questionnaire boolean and question
 *
 * @param $branch
 * @return false|mixed
 */
function get_remote_questionnaire_rank_question_choice($condition, $sort='')
{
    $res = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_questionnaire_rank_question_choice',
            'params' => array('condition' => $condition, 'sort' => $sort)
        )
    );
    return $res;
}

/**
 * get questionnaire boolean and question
 *
 * @param $branch
 * @return false|mixed
 */
function get_remote_questionnaire_text_question($condition, $sort='')
{
    $res = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_questionnaire_text_question',
            'params' => array('condition' => $condition, 'sort' => $sort)
        )
    );
    return $res;
}


/**
 * get questionnaire boolean and question
 *
 * @param $branch
 * @return false|mixed
 */
function get_remote_questionnaire_date_question($condition, $sort='')
{
    $res = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_questionnaire_date_question',
            'params' => array('condition' => $condition, 'sort' => $sort)
        )
    );
    return $res;
}

/**
 * get questionnaire fb sections
 *
 * @param $branch
 * @return false|mixed
 */
function get_remote_questionnaire_fb_sections($condition, $sort='')
{
    $res = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_questionnaire_fb_sections',
            'params' => array('condition' => $condition, 'sort' => $sort)
        )
    );
    return $res;
}

/**
 * get questionnaire feedback
 *
 * @param $branch
 * @return false|mixed
 */
function get_remote_questionnaire_feedback($condition, $sort='')
{
    $res = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_questionnaire_feedback',
            'params' => array('condition' => $condition, 'sort' => $sort)
        )
    );
    return $res;
}
/**
 * get questionnaire single and choice
 *
 * @param $branch
 * @return false|mixed
 */
function get_remote_questionnaire_choice_single($condition, $sort='')
{
    $res = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_questionnaire_choice_single',
            'params' => array('condition' => $condition, 'sort' => $sort)
        )
    );
    return $res;
}
/**
 * get questionnaire single and choice
 *
 * @param $branch
 * @return false|mixed
 */
function get_remote_questionnaire_choice_multiple($condition, $sort='')
{
    $res = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_questionnaire_choice_multiple',
            'params' => array('condition' => $condition, 'sort' => $sort)
        )
    );
    return $res;
}
/**
 * get questionnaire other and choice
 *
 * @param $branch
 * @return false|mixed
 */
function get_remote_questionnaire_choice_other($condition, $sort='')
{
    $res = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_questionnaire_choice_other',
            'params' => array('condition' => $condition, 'sort' => $sort)
        )
    );
    return $res;
}
/**
 * get questionnaire question
 *
 * @param $branch
 * @return false|mixed
 */
function get_remote_questionnaire_question($condition, $sort='')
{
    $res = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_questionnaire_question',
            'params' => array('condition' => $condition, 'sort' => $sort)
        )
    );
    return $res;
}
/**
 * get questionnaire question
 *
 * @param $branch
 * @return false|mixed
 */
function get_remote_questionnaire_date($condition, $sort='')
{
    $res = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_questionnaire_date',
            'params' => array('condition' => $condition, 'sort' => $sort)
        )
    );
    return $res;
}
/**
 * get questionnaire bool count questionnaire choice
 *
 * @param $branch
 * @return false|mixed
 */
function get_remote_questionnaire_bool_count_choice($condition, $sort='')
{
    $res = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_questionnaire_bool_count_choice',
            'params' => array('condition' => $condition, 'sort' => $sort)
        )
    );
    return $res;
}

/**
 * get questionnaire text and response and user
 *
 * @param $branch
 * @return false|mixed
 */
function get_remote_questionnaire_text_response_user($condition, $sort='')
{
    $res = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_questionnaire_text_response_user',
            'params' => array('condition' => $condition, 'sort' => $sort)
        )
    );
    return $res;
}
/**
 * get questionnaire text and response and user
 *
 * @param $branch
 * @return false|mixed
 */
function get_remote_questionnaire_choice_rank($condition, $sort='')
{
    $res = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_questionnaire_choice_rank',
            'params' => array('condition' => $condition, 'sort' => $sort)
        )
    );
    return $res;
}
/**
 * get questionnaire choice, rank and average
 *
 * @param $branch
 * @return false|mixed
 */
function get_remote_questionnaire_choice_rank_average($condition, $sort='')
{
    $res = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_questionnaire_choice_rank_average',
            'params' => array('condition' => $condition, 'sort' => $sort)
        )
    );
    return $res;
}
/**
 * get questionnaire choice, rank and sum
 *
 * @param $branch
 * @return false|mixed
 */
function get_remote_questionnaire_choice_rank_sum($condition, $sort='')
{
    $res = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_questionnaire_choice_rank_sum',
            'params' => array('condition' => $condition, 'sort' => $sort)
        )
    );
    return $res;
}

/**
 * get questionnaire rank and count response
 *
 * @param $branch
 * @return false|mixed
 */
function get_remote_questionnaire_rank_count_response($condition, $sort='')
{
    $res = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_questionnaire_rank_count_response',
            'params' => array('condition' => $condition, 'sort' => $sort)
        )
    );
    return $res;
}