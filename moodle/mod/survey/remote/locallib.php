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