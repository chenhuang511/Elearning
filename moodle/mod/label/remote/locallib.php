<?php
defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot . '/lib/remote/lib.php');
require_once($CFG->dirroot . '/lib/additionallib.php');

function get_remote_label_by_id($id)
{
	return moodle_webservice_client(array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_label_get_label_by_id',
            'params' => array('id' => $id)
        )
    );
}