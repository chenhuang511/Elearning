<?php

require_once($CFG->libdir . '/additionallib.php');


/**
 * func return array with changed key for multiple array[]=obj
 * @param $id
 * @return false|mixed
 */
function change_key_by_value($array = array(), $key = 'id') {
    $keys = array_map(function ($ar) use ($key) {
        return $ar->$key;
    }, $array);
    return array_combine($keys, $array);
}

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

        $serverUrl = $options['domain'] . '/webservice/rest/server.php' . '?wstoken=' .
            $options['token'] . '&wsfunction=' .
            $options['function_name'] . '&moodlewsrestformat=json';

        if (!class_exists('Zend_Http_Client')) {
            set_include_path(get_include_path().PATH_SEPARATOR.$CFG->libdir . '/zend/');
            require_once($CFG->libdir . '/zend/Zend/Http/Client.php');
        }

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
    $result = merge_local_course_module($info);

    return $result;
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
        ), false
    );

    foreach ($resp as $cm){
        merge_local_course_module($cm);
    }

    return change_key_by_value($resp);
}

function get_remote_course_sections($courseid, $usesq = false)
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

    if ($usesq) {
        $retval = change_key_by_value($sections, $usesq);
    } else {
        $retval = $sections;
    }
    return $retval;
}

function get_remote_mapping_user($user = null)
{
    global $DB, $USER, $CFG;

    require_once($CFG->dirroot . '/mnet/lib.php');
    $hostname = mnet_get_hostname_from_uri($CFG->wwwroot);
    $hostip = gethostbyname($hostname);
    if ($user === null) {
        $username = $USER->username;
        $email = $USER->email;
    } else {
        if (!is_object($user)) {
            $user = $DB->get_record('user', array('id' => $user), '*', MUST_EXIST);
        }
        $username = $user->username;
        $email = $user->email;
    }
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_get_remote_mapping_user',
            'params' => array('ipaddress' => $hostip, 'username' => $username, 'email' => $email)
        )
    );
}

function get_remote_mapping_localuserid($userid) {
    global $DB;
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_get_user_by_id',
            'params' => array('userid' => $userid)
        )
    );

    $hubuser = $result->user;

    $localuserid = 0;

    if($hubuser) {
        $localuserid = $DB->get_field('user','id',array('username' => $hubuser->username, 'email' => $hubuser->email));
    }

    return $localuserid;
}

function remote_assign_role_to_user($roleid, $userid, $courseid)
{
    global $DB;

    $user = $DB->get_record('user', array('id'=>$userid), '*', MUST_EXIST);
    $remoteuser = get_remote_mapping_user($user);

    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_remote_assign_role_to_user',
            'params' => array('roleid' => $roleid, 'userid' => $remoteuser[0]->id, 'courseid' => $courseid)
        ), false
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

function merge_local_course_module($cm){
    global $DB;

    $localcourseid = get_local_course_record($cm->course)->id;

    if(!$coursemodule = $DB->get_record('course_modules', array('remoteid' => $cm->id))){
        // Make params to insert DB local
        $cm->remoteid = $cm->id;
        $cm->course = $localcourseid;
        unset($cm->id);

        $transaction = $DB->start_delegated_transaction();
        $cmidhost = $DB->insert_record('course_modules', $cm);
        $transaction->allow_commit();
        unset($cm->remoteid);

        $coursemodule = $DB->get_record('course_modules', array('id' => $cmidhost));
    }

    // Merge course module for settings
    $cm->id                         = $coursemodule->remoteid;
    $cm->course                     = $localcourseid;
    $cm->availability               = $coursemodule->availability;
    $cm->completion                 = $coursemodule->completion;
    $cm->completionview             = $coursemodule->completionview;
    $cm->completionexpected         = $coursemodule->completionexpected;
    $cm->completiongradeitemnumber  = $coursemodule->completiongradeitemnumber;

    return $cm;
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
 * delete a tbl
 *
 * @param $branch
 * @return false|mixed
 */
function delete_remote_response_by_tbl($tablename, $select, $sort = '')
{
    $res = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_delete_response_by_mbl',
            'params' => array('tablename' => $tablename, 'select' => $select, 'sort' => $sort)
        )
    );
    return $res;
}

/**
 * set_field a mbl
 *
 * @param $branch
 * @return false|mixed
 */
function setfield_remote_response_by_tbl($tablename, $field, $value, $data)
{
    $res = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_setfield_response_by_mbl',
            'params' => array_merge(array('tablename' => $tablename, 'field' => $field, 'value' => $value), $data)
        ), false
    );
    return $res;
}
