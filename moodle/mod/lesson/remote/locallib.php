<?php
defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot . '/lib/remote/lib.php');
require_once($CFG->dirroot . '/lib/additionallib.php');
require_once($CFG->dirroot . '/mnet/service/enrol/locallib.php');
require_once($CFG->dirroot . '/lib/dml/json_moodle_recordset.php');


function get_remote_lesson_by($parameters, $sort = '', $mustexists = FALSE)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_lesson_by',
            'params' => array_merge(array('sort' => $sort, 'mustexists' => $mustexists), $parameters),
        )
    );

    return $result->lesson;
}

function get_remote_lesson_pages_by($parameters, $sort = '', $mustexists = FALSE)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_lesson_pages_by',
            'params' => array_merge(array('sort' => $sort, 'mustexists' => $mustexists), $parameters),
        )
    );

    return $result->page;
}

function get_remote_lesson_grades_by($parameters, $sort = '', $mustexists = FALSE)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_lesson_grades_by',
            'params' => array_merge(array('sort' => $sort, 'mustexists' => $mustexists), $parameters),
        )
    );

    return $result->grade;
}

function get_remote_lesson_answers_by($parameters, $sort = '', $mustexists = FALSE)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_lesson_answers_by',
            'params' => array_merge(array('sort' => $sort, 'mustexists' => $mustexists), $parameters),
        )
    );

    return $result->answer;
}

function get_remote_lesson_overrides_by($parameters, $sort = '', $mustexists = FALSE)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_lesson_overrides_by',
            'params' => array_merge(array('sort' => $sort, 'mustexists' => $mustexists), $parameters),
        )
    );

    return $result->override;
}

function get_remote_lesson_attempts_by($parameters, $sort = '', $mustexists = FALSE)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_lesson_attempts_by',
            'params' => array_merge(array('sort' => $sort, 'mustexists' => $mustexists), $parameters),
        )
    );

    return $result->attempt;
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
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_list_lesson_branch_by',
            'params' => array_merge(array('sort' => $sort, 'limitfrom' => $limitfrom, 'limitnum' => $limitnum), $parameters)
        )
    );

    $branches = array();

    foreach ($result->branches as $arr) {
        $branch = new stdClass();
        $branch->id = $arr->id;
        $branch->pageid = $arr->pageid;

        $branches = array_merge($branches, $branch);
    }

    return $branches;
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

    $answers = array();

    foreach ($result->answers as $answer) {
        $answers[$answer->id] = $answer;
    }

    return $answers;
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

function get_remote_list_lesson_by($parameters = array(), $sort = '', $limitfrom = 0, $limitnum = 0)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_list_lesson_by',
            'params' => array_merge(array('sort' => $sort, 'limitfrom' => $limitfrom, 'limitnum' => $limitnum), $parameters)
        )
    );

    $lessons = array();

    foreach ($result->lessons as $lesson) {
        $lessons[$lesson->id] = $lesson;
    }

    return $lessons;
}

function get_remote_list_ids_lesson_by($parameters = array(), $sort = '', $limitfrom = 0, $limitnum = 0)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_list_lesson_by',
            'params' => array_merge(array('sort' => $sort, 'limitfrom' => $limitfrom, 'limitnum' => $limitnum), $parameters)
        )
    );

    $lessons = array();

    foreach ($result->lessons as $l) {
        $lesson = new stdClass();
        $lesson->id = $l->id;
        $lessons[$l->id] = $lesson;
    }

    return $lessons;
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

function check_remote_record_exists($modname, $parameters)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_check_record_exists',
            'params' => array_merge(array('modname' => $modname), $parameters)
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

    return new json_moodle_recordset($attempts);
}

function get_remote_recordset_lesson_branch_by_lessonid($parameters, $sort = '', $limitfrom = 0, $limitnum = 0)
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