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
require_once($CFG->dirroot . '/mnet/service/enrol/locallib.php');

function get_remote_quiz_by_id($id) {
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_quiz_by_id',
            'params' => array('id'=>$id)
        )
    );
}

function get_remote_user_mapping_userid() {
    global $USER;
//    $ipaddress = $_SERVER['SERVER_ADDR'];
    $ipaddress = $_SERVER['LOCAL_ADDR'];
    $username = $USER->username;

    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_get_remote_mapping_user',
            'params' => array('ipaddress' => $ipaddress, 'username' => $username)
        )
    );
}

function get_remote_user_attemps($quizid, $userid, $status, $includepreviews) {
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'mod_quiz_get_user_attempts',
            'params' => array('quizid' => $quizid, 'userid' => $userid, 'status' => $status, 'includepreviews' => $includepreviews)
        )
    );
}

function get_remote_coursemodule_from_instance($module, $instance) {
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'core_course_get_course_module_by_instance',
            'params' => array('module'=>$module, 'instance'=>$instance)
        )
    );
}

function get_remote_quiz_access_information($quizid) {
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'mod_quiz_get_quiz_access_information',
            'params' => array('quizid'=>$quizid)
        )
    );
}

function get_remote_user_best_grade($quizid,  $userid) {
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'mod_quiz_get_user_best_grade',
            'params' => array('quizid' => $quizid, 'userid' => $userid)
        )
    );
}

function get_remote_question($quizid) {
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_questions_by_quizid',
            'params' => array('id' => $quizid)
        )
    );
}

