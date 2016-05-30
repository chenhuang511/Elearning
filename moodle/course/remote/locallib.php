<?php
defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot . '/lib/remote/lib.php');
require_once($CFG->dirroot . '/lib/additionallib.php');
require_once($CFG->dirroot . '/mnet/service/enrol/locallib.php');

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

function get_remote_courses($options = array())
{
    return moodle_webservice_client(array_merge($options, array('domain' => HUB_URL,
        'token' => HOST_TOKEN,
        'function_name' => 'core_course_get_courses'
    )));
}

function get_remote_course_content($courseid, $options = array())
{
    return moodle_webservice_client(array_merge($options, array('domain' => HUB_URL,
        'token' => HOST_TOKEN,
        'function_name' => 'local_get_course_content_by_id',
        'params' => array('courseid' => $courseid),
    )));
}

function get_remote_course_category($ccatid, $options = array())
{
    return moodle_webservice_client(array_merge($options, array('domain' => HUB_URL,
        'token' => HOST_TOKEN,
        'function_name' => 'core_course_get_categories',
        'params' => array('criteria[0][key]' => 'id', 'criteria[0][value]' => $ccatid),
    )));
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

function get_remote_lesson_by_id($lessonid, $options = array())
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

function get_remote_lesson_page_content($lessonid, $pageid, $isfield = false, $options = [])
{
    return moodle_webservice_client(array_merge($options,
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_lesson_page_by_id',
            'params' => array('lessonid' => $lessonid, 'pageid' => $pageid, 'isfield' => $isfield)
        )
    ));
}

function get_remote_context_by_instanceid_and_contextlevel($instanceid, $contextlevel, $options = array()) {
    return moodle_webservice_client(array_merge($options,
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_context_by_instanceid_and_contextlevel',
            'params' => array('instanceid' => $instanceid, 'contextlevel' => $contextlevel)
        )
    ));
}

function get_remote_lesson_page($lessonid, $prevpageid, $options = array()) {
    $lessonpage = moodle_webservice_client(array_merge($options,
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_lesson_page',
            'params' => array('lessonid' => $lessonid, 'prevpageid' => $prevpageid)
        )
    ));

    echo "<pre>";
    var_dump($lessonpage);
    echo "</pre>";
    die();
}