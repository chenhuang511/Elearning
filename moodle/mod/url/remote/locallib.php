<?php
defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot . '/lib/remote/lib.php');
require_once($CFG->dirroot . '/lib/additionallib.php');
require_once($CFG->dirroot . '/mnet/service/enrol/locallib.php');

function get_remote_url_by_id($urlid, $options = array()) {
    $resp = moodle_webservice_client(array_merge($options,
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_url_by_id',
            'params' => array('id' => $urlid)
        )
    ));

    return $resp->url;
}
