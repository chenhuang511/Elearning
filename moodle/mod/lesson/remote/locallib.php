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

function get_remote_list_lesson_attempts_sql($sql, $parameters)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_list_lesson_attempts_sql',
            'params' => array_merge(array('sql' => $sql), $parameters)
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

function get_remote_list_lesson_answers_select($usql, $parameters)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_list_lesson_answers_select',
            'params' => array_merge(array('usql' => $usql), $parameters)
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

function get_remote_list_lesson_pages_select($usql, $parameters)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_list_lesson_pages_select',
            'params' => array_merge(array('usql' => $usql), $parameters)
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

function get_remote_user_by_lessonid($sql, $parameters)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_user_by_lessonid',
            'params' => array_merge(array('sql' => $sql), $parameters),
        )
    );

    $users = array();

    foreach ($result->users as $user) {
        $users[$user->id] = $user;
    }

    //return $users;
    return new json_moodle_recordset($users);
}

function get_remote_recordset_lesson_attempts_by_lessonid($parameters, $sort = '', $limitfrom = 0, $limitnum = 1)
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

function get_remote_recordset_lesson_branch_by_lessonid($parameters, $sort = '', $limitfrom = 0, $limitnum = 1)
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

function save_remote_mdl_lesson($modname, $data)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_save_mdl_lesson',
            'params' => array_merge(array('modname' => $modname), $data),
        )
    );

    return $result->newid;
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

function update_remote_mdl_lesson($modname, $id, $data)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_update_mdl_lesson',
            'params' => array_merge(array('modname' => $modname, "id" => $id), $data),
        )
    );

    return $result->id;
}

function delete_remote_mdl_lesson($modname, $data)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_delete_mdl_lesson',
            'params' => array_merge(array('modname' => $modname), $data)
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