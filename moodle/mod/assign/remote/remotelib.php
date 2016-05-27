<?php
defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot . '/lib/zend/Zend/Http/Client.php');
require_once($CFG->dirroot . '/mnet/service/enrol/locallib.php');

/*define('MOBILE_SERVICE_TOKEN', 'ac52a223f8589b3f26fa456a5dc20bde');
define('NCC_SERVICE_TOKEN', 'a75634b66a82dd8f42f99baedf2690a1');
define('NCC_DOMAIN_NAME', 'http://192.168.1.252');*/


function moodle_webservice_client($options = [], $usercache = true)
{
    if (isset($options['domain']) &&
        isset($options['token']) &&
        isset($options['function_name'])
    ) {
        if ($usercache) {
            $webservicecache = cache::make_from_params(cache_store::MODE_APPLICATION, 'core', 'webservice');
            $cachekey = 'wes-'.$options['domain'].$options['token'].$options['function_name'];
        }

        $serverUrl = $options['domain'] . '/webservice/rest/server.php' . '?wstoken=' . $options['token'] . '&wsfunction=' . $options['function_name'] . '&moodlewsrestformat=json';
        $client = new Zend_Http_Client($serverUrl);
        if (isset($options['params'])) {
            $client->setParameterPost($options['params']);
            if ($usercache) {
                $cachekey .= implode('-', $options['params']);
            }
        }

        if ($usercache) {
            $result = $webservicecache->get($cachekey);
            if ($result !== false) {
                return $result;
            }
        }

        $response = $client->request(Zend_Http_Client::POST);
        $result = json_decode($response->getBody());
        $webservicecache->set($cachekey, $result);

        return $result;
    }

}
function get_assign_summary_remote($assignid, $options = []){
    return moodle_webservice_client(array_merge($options,array('domain' => "http://192.168.1.252",
        'token' => "a75634b66a82dd8f42f99baedf2690a1",
        'function_name' => 'local_mod_get_assign_completion',
        'params' => array('assignid' => $assignid,"ip_address"=>"192.168.1.62","username"=>"admin"),
    )));
}