<?php

require_once($CFG->libdir . '/additionallib.php');

function moodle_webservice_client($options, $usecache = true, $assoc = false)
{
    global $CFG;

    if (isset($options['domain']) &&
        isset($options['token']) &&
        isset($options['function_name'])
    ) {
        if ($usecache) {
            $webservicecache = cache::make_from_params(cache_store::MODE_APPLICATION, 'core', 'webservice');
            $cachekey = 'wes-' . $options['domain'] . $options['token'] . $options['function_name'];
        }

        $serverUrl = $options['domain'] . '/webservice/rest/server.php' . '?wstoken=' . $options['token'] . '&wsfunction=' . $options['function_name'] . '&moodlewsrestformat=json';
        if (strpos($CFG->libdir . '/zend/', get_include_path()) === false) {
            set_include_path(get_include_path().PATH_SEPARATOR.$CFG->libdir . '/zend/');
        }
        require_once($CFG->libdir . '/zend/Zend/Http/Client.php');
        $client = new Zend_Http_Client($serverUrl);

        if (isset($options['params'])) {
            $client->setParameterPost($options['params']);
            if ($usecache) {
                $cachekey .= implode('-', $options['params']);
            }
        }

        if ($usecache) {
            $result = $webservicecache->get($cachekey);
            if ($result !== false) {
                return $result;
            }
        }

        $response = $client->request(Zend_Http_Client::POST);
        $result = json_decode($response->getBody(), $assoc);
        if ($usecache) {
            $webservicecache->set($cachekey, $result);
        }
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

function get_remote_course_thumb($courseid, $options = [])
{
    return moodle_webservice_client(array_merge($options,
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_get_course_thumbnail_by_id',
            'params' => array('courseids[0]' => $courseid)
        )
    ));
}

function get_remote_course_mods($courseid)
{
    $resp = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_get_course_mods',
            'params' => array('courseid' => $courseid)
        )
    );

    $retval = array();
    foreach($resp as $val) {
        $retval[$val->id] = $val;
    }

    return $retval;
}

function get_remote_course_sections($courseid)
{
    global $DB;

    $sections = new StdClass();
    switch(MOODLE_RUN_MODE) {
        case MOODLE_MODE_HOST:
            // Get section data
            $sections = $DB->get_records('course_sections', array('course' => $courseid), 'section ASC', 'id,section,sequence');
            break;
        case MOODLE_MODE_HUB:
            $sections = moodle_webservice_client(
                array(
                    'domain' => HUB_URL,
                    'token' => HOST_TOKEN,
                    'function_name' => 'local_get_course_sections',
                    'params' => array('courseid' => $courseid)
                )
            );
            break;
        default:
            break;
    }
    $retval = array();
    foreach($sections as $val) {
        $retval[$val->id] = $val;
    }
    return $retval;
}

function get_remote_mapping_user()
{
    global $USER, $CFG;

    require_once($CFG->dirroot . '/mnet/lib.php');
    $hostname = mnet_get_hostname_from_uri($CFG->wwwroot);
    $hostip = gethostbyname($hostname);
    $username = $USER->username;
    $email = $USER->email;

    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_get_remote_mapping_user',
            'params' => array('ipaddress' => $hostip, 'username' => $username, 'email' => $email)
        )
    );
}

function get_remote_cm_info($modname, $instanceid) {
    global $DB;

    $modinfo = new StdClass();
    switch(MOODLE_RUN_MODE) {
        case MOODLE_MODE_HOST:
            // Get section data
            $modinfo = $DB->get_record($modname, array('id' => $instanceid), 'name, intro, introformat');
            break;
        case MOODLE_MODE_HUB:
            $modinfo = moodle_webservice_client(
                array(
                    'domain' => HUB_URL,
                    'token' => HOST_TOKEN,
                    'function_name' => 'local_get_course_module_info',
                    'params' => array('modname' => $modname, 'instanceid' => $instanceid)
                )
            );
            break;
        default:
            break;
    }

    return $modinfo;
}