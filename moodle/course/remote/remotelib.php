<?php
defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot . '/lib/zend/Zend/Http/Client.php');
require_once($CFG->dirroot . '/mnet/service/enrol/locallib.php');

/*define('MOBILE_SERVICE_TOKEN', 'ac52a223f8589b3f26fa456a5dc20bde');
define('NCC_SERVICE_TOKEN', 'a75634b66a82dd8f42f99baedf2690a1');
define('NCC_DOMAIN_NAME', 'http://192.168.1.252');*/


function get_remote_enrol_course_by_host()
{
    global $DB;
    $service = mnetservice_enrol::get_instance();

    if (!$service->is_available()) {
        print_error('mnetserviceisnotenabled', 'mnetservice_enrol');
        return null;
    }

    // remote hosts that may publish remote enrolment service and we are subscribed to it
    $hosts = $service->get_remote_publishers();
    $host = new StdClass();
    foreach ($hosts as $h) {
        $host = $h;
        break;
    }

    $courseids = $DB->get_records('course', array('hostid' => $host->id), '', 'remoteid');
    $opids = array();
    $i = 0;
    foreach ($courseids as $key => $val) {
        $opids['options[ids][' . $i++ . ']'] = $key;
    }
    $options = array('params' => $opids);

    return get_remote_courses($options);
}

function moodle_webservice_client($options = [], $usercache = true)
{
    if (isset($options['domain']) &&
        isset($options['token']) &&
        isset($options['function_name'])
    ) {
        if ($usercache) {
            $webservicecache = cache::make(cache_store::MODE_APPLICATION, 'core', 'webservice');
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

function get_remote_courses($options = [])
{
    return moodle_webservice_client(array_merge($options, array('domain' => HUB_URL,
        'token' => HOST_TOKEN,
        'function_name' => 'core_course_get_courses'
    )));
}

function get_remote_course_content($courseid, $options = [])
{
    return moodle_webservice_client(array_merge($options, array('domain' => HUB_URL,
        'token' => HOST_TOKEN_M,
        'function_name' => 'core_course_get_contents',
        'params' => array('courseid' => $courseid),
    )));
}

function get_remote_course_category($ccatid, $options = [])
{
    return moodle_webservice_client(array_merge($options, array('domain' => HUB_URL,
        'token' => HOST_TOKEN,
        'function_name' => 'core_course_get_categories',
        'params' => array('criteria[0][key]' => 'id', 'criteria[0][value]' => $ccatid),
    )));
}

/*
 * @param int $cmid the course module id
 */
function get_remote_course_module($cmid, $options = [])
{
    $result = moodle_webservice_client(array_merge($options, array('domain' => HUB_URL,
        'token' => HOST_TOKEN_M,
        'function_name' => 'core_course_get_course_module',
        'params' => array('cmid' => $cmid),
    )));

    return $result->cm;
}

function get_remote_label_content($labelid, $options = [])
{
    return moodle_webservice_client(array_merge($options, array('domain' => HUB_URL,
        'token' => HOST_TOKEN,
        'function_name' => 'local_mod_get_label_by_id',
        'params' => array('labelid' => $labelid),
    )));
}


function get_remote_page_content($pageid, $options = [])
{
    return moodle_webservice_client(array_merge($options, array('domain' => HUB_URL,
        'token' => HOST_TOKEN,
        'function_name' => 'local_mod_get_page_by_id',
        'params' => array('pageid' => $pageid),
    )));
}

function get_remote_book_content($bookid, $options = [])
{
    return moodle_webservice_client(array_merge($options, array('domain' => HUB_URL,
        'token' => HOST_TOKEN,
        'function_name' => 'local_mod_get_book_by_id',
        'params' => array('bookid' => $bookid),
    )));
}

function get_remote_quiz_content($quizid, $options = [])
{
    return moodle_webservice_client(array_merge($options, array('domain' => HUB_URL,
        'token' => HOST_TOKEN,
        'function_name' => 'local_mod_get_quiz_by_id',
        'params' => array('quizid' => $quizid),
    )));
}

function get_remote_url_content($urlid, $options = [])
{
    return moodle_webservice_client(array_merge($options,
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_url_by_id',
            'params' => array('urlid' => $urlid),
        )
    ));
}

function get_remote_lesson_content($lessonid, $options = [])
{
    return moodle_webservice_client(array_merge($options,
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_lesson_by_id',
            'params' => array('lessonid' => $lessonid),
        )
    ));
}

function get_remote_lesson_page_content($lessonid, $options = [])
{

    if ((isset($options['returntype']) && $options['returntype'] === 'fieldtype') && isset($options['prevpageid'])) {
        $params = array('lessonid' => $lessonid, 'prevpageid' => $options['prevpageid']);
    }
    if ((isset($options['returntype']) && $options['returntype'] === 'recordtype') && isset($options['pageid'])) {
        $params = array('id' => $options['pageid'], 'lessonid' => $lessonid);
    }

    return moodle_webservice_client(array_merge($options,
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_lesson_page_by_id',
            'params' => $params
        )
    ));
}