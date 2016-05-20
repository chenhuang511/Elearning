<?php
defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot . '/lib/zend/Zend/Http/Client.php');

define('MOBILE_SERVICE_TOKEN', 'b873223d07fa8adb1eed6abe53ce9bd0');
define('NCC_SERVICE_TOKEN', '37fce0f9524ba6a641ca6788cee62f9e');
define('NCC_DOMAIN_NAME', 'http://10.0.0.19:9333/');

function get_remote_course_content($courseid, $options=[]) {
    if(isset($options['function_name']) && $options['function_name'] === 'core_course_get_contents') {
        $serverUrl = NCC_DOMAIN_NAME . '/webservice/rest/server.php'. '?wstoken=' . MOBILE_SERVICE_TOKEN . '&wsfunction='.$options['function_name'].'&moodlewsrestformat=json';

        $client = new Zend_Http_Client($serverUrl);
        $client->setParameterPost('courseid', $courseid);
        $response = $client->request(Zend_Http_Client::POST);

        return json_decode($response->getBody());
    }
    return null;
}

/*
 * @param int $cmid the course module id
 */
function get_remote_course_module($cmid, $options = []) {
    if(isset($options['function_name']) && $options['function_name'] === 'core_course_get_course_module') {
        $serverUrl = NCC_DOMAIN_NAME . '/webservice/rest/server.php'. '?wstoken=' . MOBILE_SERVICE_TOKEN . '&wsfunction='.$options['function_name'].'&moodlewsrestformat=json';

        $client = new Zend_Http_Client($serverUrl);
        $client->setParameterPost('cmid', $cmid);
        $response = $client->request(Zend_Http_Client::POST);

        return json_decode($response->getBody());
    }

    return null;
}

function get_remote_label_content($labelid, $options = []) {
    if(isset($options['function_name']) && $options['function_name'] === 'local_mod_get_label_by_id') {
        $serverUrl = NCC_DOMAIN_NAME . '/webservice/rest/server.php'. '?wstoken=' . NCC_SERVICE_TOKEN . '&wsfunction='.$options['function_name'].'&moodlewsrestformat=json';

        $client = new Zend_Http_Client($serverUrl);
        $client->setParameterPost('labelid', $labelid);
        $response = $client->request(Zend_Http_Client::POST);

        return json_decode($response->getBody());
    }

    return null;
}