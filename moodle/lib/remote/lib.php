<?php

require_once($CFG->libdir . '/additionallib.php');


/**
 * func return array with changed key for multiple array[]=obj
 * @param $id
 * @return false|mixed
 */
function change_key_by_value($array = array(), $key = 'id')
{
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
            set_include_path(get_include_path() . PATH_SEPARATOR . $CFG->libdir . '/zend/');
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
    $rcmid = get_local_course_modules_record($cmid, true)->remoteid;

    $coursemodule = moodle_webservice_client(array_merge($options, array('domain' => HUB_URL,
        'token' => HOST_TOKEN_M,
        'function_name' => 'core_course_get_course_module',
        'params' => array('cmid' => $rcmid),
    )), false);

    $cm = $coursemodule->cm;

    if ($cm) {
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
    $rcourseid = get_local_course_record($courseid, true)->remoteid;

    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_get_course_mods',
            'params' => array('courseid' => $rcourseid)
        ), false
    );
    $coursemodules = $result->coursemodules;

    if ($coursemodules) {
        foreach ($coursemodules as $cm) {
            add_local_course_module($cm);
        }
    }
    add_local_course_sections($courseid);
}

function get_remote_course_sections($courseid, $usesq = false)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_get_course_sections',
            'params' => array('courseid' => $courseid)
        ), false
    );

    $retval = change_key_by_value($result->sections, $usesq);

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

function get_remote_mapping_localuserid($userid)
{
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

    if ($hubuser) {
        $localuserid = $DB->get_field('user', 'id', array('username' => $hubuser->username, 'email' => $hubuser->email));
    }

    return $localuserid;
}

function remote_assign_role_to_user($roleid, $userid, $courseid)
{
    global $DB;

    $user = $DB->get_record('user', array('id' => $userid), '*', MUST_EXIST);
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

function get_remote_cm_info($modname, $instanceid)
{
    global $DB;

    $modinfo = new StdClass();
    switch (MOODLE_RUN_MODE) {
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

/**
 * Merge course module from hub save to host and merge
 *
 * @param object $cm - The course module info
 * @return object $cm - The course module after merge
 */
function merge_local_course_module($rcm)
{
    $cm = get_local_course_modules_record($rcm->id);

    if (isset($rcm->name)) {
        $cm->name = $rcm->name;
    }
    if (isset($rcm->modname)) {
        $cm->modname = $rcm->modname;
    }

    return $cm;
}

/**
 * Merge course module availability from hub save to host and merge
 *
 * @param object $rcmavailability - The course module availability info
 * @return object $rcmavailability - The course module availability after merge
 */
function merge_local_course_module_availability($rcmavailability, $useid = false)
{
    global $DB;

    $rcmavailabilityobj = json_decode($rcmavailability);

    foreach ($rcmavailabilityobj->c as &$c) {
        if (!isset($c->cm)) {
            continue;
        }
        if (!$useid) {
            $cmid = $DB->get_field('course_modules', 'id', array('remoteid' => $c->cm));
        } else {
            $cmid = $DB->get_field('course_modules', 'remoteid', array('id' => $c->cm));
        }
        if ($cmid) {
            $c->cm = $cmid;
        }
    }

    $rcmavailability = json_encode($rcmavailabilityobj);

    return $rcmavailability;
}

/**
 * Add course module from hub save to host and merge
 *
 * @param object $cm - The course module info
 * @return object $cm - The course module after merge
 */
function add_local_course_module($cm)
{
    global $DB;

    $localcourse = $DB->get_record('course', array('remoteid' => $cm->course));
    if ($localcourse) {
        if (!$coursemodule = $DB->get_record('course_modules', array('remoteid' => $cm->id))) {
            $transaction = $DB->start_delegated_transaction();
            // Make params to insert DB local
            $cm->remoteid = $cm->id;
            unset($cm->id);

            // Change course id on hub
            $cm->course = $localcourse->id;

            // Change module id on host
            $modulehub = get_remote_modules_by_id($cm->module);
            $modulehost = $DB->get_record('modules', array('name' => $modulehub->name));
            if ($modulehost) {
                $cm->module = $modulehost->id;
            }
            $DB->insert_record('course_modules', $cm);
            $transaction->allow_commit();
        }
    }
}

/**
 * Add course section from hub save to host and merge
 *
 * @param int $courseid - The id of course
 * @return void
 */
function add_local_course_sections($courseid)
{
    global $DB;

    $rcourseid = get_local_course_record($courseid, true)->remoteid;
    $sections = get_remote_course_sections($rcourseid, 'id');

    $transaction = $DB->start_delegated_transaction();
    if ($sections) {
        foreach ($sections as $section) {
            // Insert course section from hub to host
            if (!$localsection = $DB->get_record('course_sections', array('remoteid' => $section->id))) {
                $section->remoteid = $section->id;
                $section->course = $courseid;
                unset($section->id);
                // Insert to DB in host.
                $secid = $DB->insert_record('course_sections', $section);
                $localsection = $DB->get_record('course_sections', array('id' => $secid));
            } else {
                $secid = $localsection->id;
            }

            if (!empty($section->sequence)) {
                $localsection->sequence = merge_local_sequence_course_section($section->sequence, true, $secid);
                $DB->set_field('course_sections', 'sequence', $localsection->sequence, array('id' => $secid));
            }
        }
    }
    $transaction->allow_commit();
}

/**
 * Merge local sequence course section & course module
 *
 * @param string $sequence -  List course module id on hub
 * @param bool $updatecm -  Update tbl course module or not
 * @param int $secid -  The id of course section
 *
 * @return string $sequence -  List course module id on host
 */
function merge_local_sequence_course_section($sequence, $updatecm = false, $secid = '')
{
    global $DB;
    if (empty($sequence)) {
        return 0;
    }

    if ($updatecm && empty($secid)) {
        print_error('Cannot update course module when empty section id');
    }

    $sequencesarray = explode(",", $sequence);
    foreach ($sequencesarray as &$seq) {
        $cm = $DB->get_record('course_modules', array('remoteid' => $seq));
        // Update sectionid in course_module tbl
        if ($cm) {
            if ($updatecm) {
                $cm->section = $secid;
                if (!empty($cm->availability)) {
                    //Merge availability
                    $cm->availability = merge_local_course_module_availability($cm->availability);
                }

                // Merge & generate course module instance foreach
                $cm->instance = merge_local_course_module_instance($cm);
                $DB->update_record('course_modules', $cm);
            }
            $seq = $cm->id;
        }
    }
    // Update sequence in course_sections
    $sequence = implode(",", $sequencesarray);

    return $sequence;
}

/**
 * Generate instance activities and change instance course module
 *
 * @param object $coursemodule -  The info of course module to merge.
 * @return bool|int             -  True if success.
 */
function merge_local_course_module_instance($coursemodule)
{
    global $DB, $CFG;

    if (!$modname = $DB->get_field('modules', 'name', array('id' => $coursemodule->module))) {
        return $coursemodule->instance;
    }

    $functionname = $modname . '_get_local_settings_info';

    if (!file_exists("$CFG->dirroot/mod/$modname/lib.php")) {
        return $coursemodule->instance;
    }

    include_once("$CFG->dirroot/mod/$modname/lib.php");
    if (function_exists($functionname)) {
        if ($instance = $functionname($coursemodule)) {
            return $coursemodule->instance = $instance;
        }
    }

    return $coursemodule->instance;
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
        ), false
    );
    if ($res->status !== true) {
        throw new coding_exception('Invalid local_mod_delete_response_by_mbl API. Please check your API');
    }
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
    if ($res->status !== true) {
        throw new coding_exception('Invalid local_mod_setfield_response_by_mbl API. Please check your API');
    }
    return $res;
}

