<?php

set_include_path(get_include_path().PATH_SEPARATOR.$CFG->dirroot . '/lib/zend/');
require_once($CFG->dirroot . '/lib/zend/Zend/Http/Client.php');

function moodle_webservice_client($options = [], $usercache = true)
{
    if (isset($options['domain']) &&
        isset($options['token']) &&
        isset($options['function_name'])
    ) {
        if ($usercache) {
            $webservicecache = cache::make_from_params(cache_store::MODE_APPLICATION, 'core', 'webservice');
            $cachekey = 'wes-' . $options['domain'] . $options['token'] . $options['function_name'];
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

/*
 * @param int $cmid the course module id
 */
function get_remote_course_module($cmid, $options = array())
{
    $coursemodule = moodle_webservice_client(array_merge($options, array('domain' => HUB_URL,
        'token' => HOST_TOKEN_M,
        'function_name' => 'core_course_get_course_module',
        'params' => array('cmid' => $cmid),
    )));

    $cm = $coursemodule->cm;

    if($cm) {
        $info = new stdClass();
        $info->id = strval($cm->id);
        $info->course = strval($cm->course);
        $info->module = strval($cm->module);
        $info->instance = strval($cm->instance);
        $info->section = strval($cm->section);
        $info->idnumber = strval($cm->idnumber);
        $info->added = strval($cm->added);
        $info->score = strval($cm->score);
        $info->indent = strval($cm->indent);
        $info->visible = strval($cm->visible);
        $info->visibleold = strval($cm->visibleold);
        $info->groupmode = strval($cm->groupmode);
        $info->groupingid = strval($cm->groupingid);
        $info->completion = strval($cm->completion);
        $info->completiongradeitemnumber = strval($cm->completiongradeitemnumber);
        $info->completionview = strval($cm->completionview);
        $info->completionexpected = strval($cm->completionexpected);
        $info->showdescription = strval($cm->showdescription);
        $info->availability = strval($cm->availability);
        $info->name = strval($cm->name);
        $info->modname = strval($cm->modname);
    }

    return $info;
}
// create function to get api local_get_thumbnail_by_id
function get_remote_course_thumb($courseid, $options = [])
{
    return moodle_webservice_client(array_merge($options,
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_get_thumbnail_by_id',
            'params' => array('courseid[0]' => $courseid)
        )
    ));
}