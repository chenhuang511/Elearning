<?php
defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot . '/lib/remote/lib.php');
require_once($CFG->dirroot . '/lib/additionallib.php');
require_once($CFG->dirroot . '/mnet/service/enrol/locallib.php');
require_once($CFG->dirroot . '/lib/dml/json_moodle_recordset.php');

/**
 * get lesson by id
 *
 * @param int $lessonid . the id of lesson
 * @param array $options . the options
 *
 * @return stdClass $lesson
 */
function get_remote_lesson_by_id($lessonid)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_lesson_by_id',
            'params' => array('lessonid' => $lessonid),
        )
    );

    return $result->lesson;
}

/**
 * get lesson page by lessonid and previous page id
 *
 * @param int $lessonid . The id of lesson
 * @param int $prevpageid . The previous page id
 * @param array $options
 *
 * @return stdClass $lesson_page
 */
function get_remote_lesson_pages_by_lessonid_and_prevpageid($lessonid, $prevpageid)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_lesson_pages_by_lessonid_and_prevpageid',
            'params' => array('lessonid' => $lessonid, 'prevpageid' => $prevpageid)
        )
    );

    return $result->page;
}

/**
 * get lesson page by pageid and lessonid
 *
 * @param int @id. The id of lesson page
 * @param int @lessonid. The id of lesson
 * @param array $options
 *
 * @return stdClass $lesson_page
 */
function get_remote_lesson_pages_by_id_and_lessonid($id, $lessonid)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_lesson_pages_by_id_and_lessonid',
            'params' => array('id' => $id, 'lessonid' => $lessonid)
        )
    );

    return $result->page;
}

function get_remote_list_lesson_timer_by($parameters, $sort = '', $limitfrom = 0, $limitnum = 0)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_list_lesson_timer_by',
            'params' => array_merge(array('sort' => $sort, 'limitfrom' => $limitfrom, 'limitnum' => $limitnum), $parameters)
        )
    );

    $timers = array();

    foreach ($result->timers as $timer) {
        $timers[$timer->id] = $timer;
    }

    return $timers;
}

function get_remote_list_lesson_branch_by($parameters, $sort = '', $limitfrom = 0, $limitnum = 0)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_list_lesson_branch_by',
            'params' => array_merge(array('sort' => $sort, 'limitfrom' => $limitfrom, 'limitnum' => $limitnum), $parameters)
        )
    );

    $branches = array();

    foreach ($result->branches as $branch) {
        $branches[$branch->id] = $branch;
    }

    return $branches;
}

function get_remote_list_pageid_lesson_branch_by($parameters, $sort = '', $limitfrom = 0, $limitnum = 0)
{
    $branches = $this->get_remote_list_lesson_branch_by($parameters, $sort, $limitfrom, $limitnum);

    $brs = array();

    foreach ($branches as $arr) {
        $branch = new stdClass();
        $branch->id = $arr->id;
        $branch->pageid = $arr->pageid;

        $brs = array_merge($brs, $branch);
    }

    return $brs;
}

function get_remote_list_lesson_attempts_by($parameters, $sort = '', $limitfrom = 0, $limitnum = 0)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_list_lesson_attempts_by',
            'params' => array_merge(array('sort' => $sort, 'limitfrom' => $limitfrom, 'limitnum' => $limitnum), $parameters)
        )
    );

    $attempts = array();

    foreach ($result->attempts as $attempt) {
        $attempts[$attempt->id] = $attempt;
    }

    return $attempts;
}

function get_remote_list_lesson_answers_by($parameters, $sort = '', $limitfrom = 0, $limitnum = 0)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_list_lesson_answers_by',
            'params' => array_merge(array('sort' => $sort, 'limitfrom' => $limitfrom, 'limitnum' => $limitnum), $parameters)
        )
    );

    $attempts = array();

    foreach ($result->attempts as $attempt) {
        $attempts[$attempt->id] = $attempt;
    }

    return $attempts;
}

function get_remote_list_lesson_grades_by($parameters, $sort = '', $limitfrom = 0, $limitnum = 0)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_list_lesson_grades_by',
            'params' => array_merge(array('sort' => $sort, 'limitfrom' => $limitfrom, 'limitnum' => $limitnum), $parameters)
        )
    );

    $grades = array();

    foreach ($result->grades as $grade) {
        $grades[$grade->id] = $grade;
    }

    return $grades;
}

function get_remote_list_lesson_pages_by($parameters, $sort = '', $limitfrom = 0, $limitnum = 0)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_list_lesson_pages_by',
            'params' => array_merge(array('sort' => $sort, 'limitfrom' => $limitfrom, 'limitnum' => $limitnum), $parameters)
        )
    );

    $pages = array();

    foreach ($result->pages as $page) {
        $pages[$page->id] = $page;
    }

    return $pages;
}

function get_remote_list_lesson_overrides_by($parameters, $sort = '', $limitfrom = 0, $limitnum = 0)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_list_lesson_overrides_by',
            'params' => array_merge(array('sort' => $sort, 'limitfrom' => $limitfrom, 'limitnum' => $limitnum), $parameters)
        )
    );

    $overrides = array();

    foreach ($result->overrides as $override) {
        $overrides[$override->id] = $override;
    }

    return $overrides;
}

/**
 * get lesson answers by id
 *
 * @param $id
 * @param array $options
 * @return false|mixed
 */
function get_remote_lesson_answers_by_id($id)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_lesson_answers_by_id',
            'params' => array('id' => $id)
        )
    );

    return $result->answer;
}

/**
 * create new a lesson branch
 *
 * @param $branch
 * @return false|mixed
 */
function save_remote_lesson_branch($branch)
{
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_save_lesson_branch',
            'params' => array_merge($branch)
        )
    );
}

/**
 * Get maxgrade of lesson grades by userid and lessonid
 *
 * @param $userid
 * @param $lessonid
 * @param array $options
 * @return false|mixed
 */
function get_remote_maxgrade_lesson_grades_by_userid_and_lessonid($userid, $lessonid)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_maxgrade_lesson_grades_by_userid_and_lessonid',
            'params' => array('userid' => $userid, 'lessonid' => $lessonid)
        )
    );
    return $result->grade;
}

/**
 * Get lesson overrides by id
 *
 * @param $id
 * @param array $options
 * @return false|mixed
 */
function get_remote_lesson_overrides_by_id($id)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_lesson_overrides_by_id',
            'params' => array('id' => $id)
        )
    );

    return $result->override;
}

/**
 * Get lesson overrides by lessonid and userid
 *
 * @param $lessonid
 * @param $userid
 * @param array $options
 * @return false|mixed
 */
function get_remote_lesson_overrides_by_lessonid_and_userid($lessonid, $userid)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_lesson_overrides_by_lessonid_and_userid',
            'params' => array('lessonid' => $lessonid, 'userid' => $userid)
        )
    );

    return $result->override;
}

/**
 * Get lesson pages by id
 *
 * @param $id
 * @param bool $mustexist
 * @return false|mixed
 */
function get_remote_lesson_pages_by_id($id, $mustexist = false)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_lesson_pages_by_id',
            'params' => array('id' => $id, 'mustexist' => $mustexist)
        )
    );

    return $result->page;
}

/**
 * Delete a lesson object. Exam: lesson, lesson_branch, ...
 *
 * @param $tablename
 * @param $columnname
 * @param $value
 * @return false|mixed
 */
function delete_remote_moodle_table($tablename, $data)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_delete_mdl_table',
            'params' => array_merge(array('tablename' => $tablename), $data)
        )
    );

    return $result->status;
}

/**
 * Get list of events by modulename and instace
 *
 * @param $modulename
 * @param $instance
 * @return false|mixed
 */
function get_remote_list_events_by_modulename_and_instance($modulename, $instance, $userid = 0, $groupid = 0)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_events_by_modulename_and_instance',
            'params' => array('modulename' => $modulename, 'instance' => $instance, 'userid' => $userid, 'groupid' => $groupid)
        )
    );

    $events = array();
    foreach ($result->events as $event) {
        $events[$event->id] = $event;
    }

    return $events;
}

/**
 * create new a lesson pages
 *
 * @param $newpage
 * @return false|mixed
 */
function save_remote_lesson_pages($newpage)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_save_lesson_pages',
            'params' => array_merge($newpage)
        )
    );

    return $result->newpageid;
}

/**
 * create new a lesson attempts
 *
 * @param $attempt
 * @return false|mixed
 */
function save_remote_lesson_attempts($attempt)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_save_lesson_attempts',
            'params' => array_merge($attempt)
        )
    );

    return $result->status;
}

function save_remote_lesson_answers($answer)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_save_lesson_answers',
            'params' => array_merge($answer)
        )
    );

    return $result->newanswerid;
}

function update_remote_lesson_answers($id, $answer)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_update_lesson_answers',
            'params' => array_merge(array('id' => $id), $answer)
        )
    );

    return $result->status;
}

function save_remote_lesson_timer($timer)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_save_lesson_timer',
            'params' => array_merge($timer)
        )
    );

    return $result->status;
}

function update_remote_lesson_timer($id, $timer)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_update_lesson_timer',
            'params' => array_merge(array('id' => $id), $timer)
        )
    );

    return $result->status;
}

function get_remote_duration_lesson_timer_by_lessonid_and_userid($lessonid, $userid)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_duration_lesson_timer_by_lessonid_and_userid',
            'params' => array('lessonid' => $lessonid, 'userid' => $userid)
        )
    );

    return $result->duration;
}

function get_remote_lesson_attempts_by_id($id)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_lesson_attempts_by_id',
            'params' => array('id' => $id)
        )
    );

    return $result->attempt;
}

function get_remote_list_lesson_by_courseid($courseid = 0)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_list_lesson_by_courseid',
            'params' => array('courseid' => $courseid)
        )
    );

    return $result->lessons;
}

function check_remote_record_exists($tablename, $name, $value)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_check_record_exists',
            'params' => array('tablename' => $tablename, 'name' => $name, 'value' => $value)
        )
    );

    return $result->status;
}

function update_remote_lesson_pages($id, $page)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_update_lesson_pages',
            'params' => array_merge(array('id' => $id), $page)
        )
    );

    return $result->status;
}

function get_remote_user_by_lessonid($params, $esql)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_user_by_lessonid',
            'params' => array('params' => $params, 'esql' => $esql),
        )
    );

    return new json_moodle_recordset($result->users);
}

function get_remote_recordset_lesson_attempts_by_lessonid($parameters, $sort = '', $limitfrom = 0, $limitnum = 0)
{
    $attempts = $this->get_remote_list_lesson_attempts_by($parameters, $sort, $limitfrom, $limitnum);

    return new json_moodle_recordset($attempts);
}

function get_remote_recordset_lesson_branch_by_lessonid($parameters, $sort = '', $limitfrom = 0, $limitnum = 0)
{
    $branches = $this->get_remote_list_lesson_branch_by($parameters, $sort, $limitfrom, $limitnum);

    return new json_moodle_recordset($branches);
}

function update_remote_mdl_table($tablename, $params, $data)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_update_mdl_table',
            'params' => array_merge(array_merge(array('tablename' => $tablename), $params), $data),
        )
    );

    return $result->status;
}

function get_remote_field_by($modname, $parameters, $field = 'name')
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_field_by',
            'params' => array_merge(array('modname' => $modname, 'field' => $field), $parameters),
        )
    );

    return $result->field;
}

function get_remote_count_by($modname, $parameters, $sort = '')
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_count_by',
            'params' => array_merge(array('modname' => $modname, 'sort' => $sort), $parameters),
        )
    );

    return $result->count;
}