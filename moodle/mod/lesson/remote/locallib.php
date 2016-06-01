<?php
defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot . '/lib/remote/lib.php');
require_once($CFG->dirroot . '/lib/additionallib.php');
require_once($CFG->dirroot . '/mnet/service/enrol/locallib.php');

/**
 * get lesson by id
 *
 * @param int $lessonid. the id of lesson
 * @param array $options. the options
 *
 * @return stdClass $lesson
*/
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

/**
 * get lesson page by lessonid and previous page id
 *
 * @param int $lessonid. The id of lesson
 * @param int $prevpageid. The previous page id
 * @param array $options
 *
 * @return stdClass $lesson_page
*/
function get_remote_lesson_page($lessonid, $prevpageid, $options = array())
{
    return moodle_webservice_client(array_merge($options,
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_lesson_page',
            'params' => array('lessonid' => $lessonid, 'prevpageid' => $prevpageid)
        )
    ));
}

/**
 * get lesson page by pageid and lessonid
 *
 * @param int @pageid. The id of lesson page
 * @param int @lessonid. The id of lesson
 * @param array $options
 *
 * @return stdClass $lesson_page
*/
function get_remote_lessonpage_by_pageid_and_lessonid($pageid, $lessonid, $options = array())
{
    return moodle_webservice_client(array_merge($options,
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_lessonpage_by_pageid_and_lessonid',
            'params' => array('pageid' => $pageid, 'lessonid' => $lessonid)
        )
    ));
}

/**
 * get lesson timer by userid and lessonid
 *
 * @param int $userid. the id of user
 * @param int $lessonid. the id of lesson
 * @param array $options
 *
 * @return stdClass $lesson_timer
*/
function get_remote_lesson_timer_by_userid_and_lessonid($userid, $lessonid, $options = array())
{
    return moodle_webservice_client(array_merge($options,
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_get_lesson_timer_by_userid_and_lessonid',
            'params' => array('userid' => $userid, 'lessonid' => $lessonid)
        )
    ));
}

/**
 * get lesson grade by userid and lessonid
 *
 * @param int $userid. the id of user
 * @param int $lessonid. the id of lesson
 * @param array $options
 *
 * @return stdClass $lesson_grade
*/
function get_remote_lesson_grades_by_userid_and_lessonid($userid, $lessonid, $options = array())
{
    return moodle_webservice_client(array_merge($options,
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_get_lesson_grades_by_userid_and_lessonid',
            'params' => array('userid' => $userid, 'lessonid' => $lessonid)
        )
    ));
}