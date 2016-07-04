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
 * Get field of lesson pages by lessonid and prevpageid
 *
 * @param $lessonid
 * @param $prevpageid
 * @param array $options
 * @return false|mixed
 */
function get_remote_field_lesson_pages_by_lessonid_and_prevpageid($lessonid, $prevpageid)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_field_lesson_pages_by_lessonid_and_prevpageid',
            'params' => array('lessonid' => $lessonid, 'prevpageid' => $prevpageid)
        )
    );
    return $result->id;
}

function get_remote_field_lesson_pages_by_lessonid_and_nextpageid($lessonid, $nextpageid)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_field_lesson_pages_by_lessonid_and_nextpageid',
            'params' => array('lessonid' => $lessonid, 'nextpageid' => $nextpageid)
        )
    );
    return $result->id;
}

function get_remote_field_lesson_answers_by_pageid_and_lessonid($pageid, $lessonid, $field = 'jumpto')
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_field_lesson_answers_by_pageid_and_lessonid',
            'params' => array('pageid' => $pageid, 'lessonid' => $lessonid, 'field' => $field)
        )
    );
    return $result->field;
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

/**
 * get lesson timer by userid and lessonid
 *
 * @param int $userid . the id of user
 * @param int $lessonid . the id of lesson
 * @param array $options
 *
 * @return stdClass $lesson_timer
 */
function get_remote_list_lesson_timer_by_userid_and_lessonid($userid, $lessonid, $limitfrom = 0, $limitnum = 0, $sort = 'starttime')
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_lesson_timer_by_userid_and_lessonid',
            'params' => array('userid' => $userid, 'lessonid' => $lessonid, 'limitfrom' => $limitfrom, 'limitnum' => $limitnum, 'sort' => $sort)
        )
    );

    $timers = array();

    foreach ($result->timers as $timer) {
        $timers[$timer->id] = $timer;
    }

    return $timers;
}

function get_remote_list_lesson_timer_by_lessonid($lessonid, $sort = 'starttime')
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_lesson_timer_by_lessonid',
            'params' => array('lessonid' => $lessonid, 'sort' => $sort)
        )
    );

    $timers = array();

    foreach ($result->timers as $timer) {
        $timers[$timer->id] = $timer;
    }

    return $timers;
}

/**
 * get lesson branch by lessonid and userid and retry
 *
 * @param int $lessonid . the id of lesson
 * @param int $userid . the id of user
 * @param int $retry
 * @param array $options
 *
 * @return stdClass $lesson_branch
 */
function get_remote_lesson_branch_by_lessonid_and_userid_and_retry($lessonid, $userid, $retry, $sort = 'desc')
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_lesson_branch_by_lessonid_and_userid_and_retry',
            'params' => array('lessonid' => $lessonid, 'userid' => $userid, 'retry' => $retry, 'sort' => $sort)
        )
    );

    return $result->branches;
}

function get_remote_pageid_lesson_branch_by_lessonid_and_userid_and_retry($lessonid, $userid, $retry, $sort = 'asc')
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_lesson_branch_by_lessonid_and_userid_and_retry',
            'params' => array('lessonid' => $lessonid, 'userid' => $userid, 'retry' => $retry, 'sort' => $sort)
        )
    );

    $branches = array();

    if ($result->branches) {
        foreach ($result as $arr) {
            $branch = new stdClass();
            $branch->id = $arr->id;
            $branch->pageid = $arr->pageid;

            $branches = array_merge($branches, $branch);
        }
    }

    return $branches;
}

/**
 * Get retries
 *
 * @param $tablename
 * @param $lessonid
 * @param int $userid
 * @param int $retry
 * @param string $orderby
 * @param array $options
 * @return false|mixed
 */
function get_remote_count_by_lessonid_and_userid($tablename, $lessonid, $userid = 0, $retry = -1, $orderby = '')
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_count_lessonid_and_userid',
            'params' => array('tablename' => $tablename, 'lessonid' => $lessonid, 'userid' => $userid, 'retry' => $retry, 'orderby' => $orderby)
        )
    );

    return $result->retries;
}

/**
 * Get lesson attempts by lessonid and userid
 *
 * @param $lessonid
 * @param $userid
 * @param $retry
 * @param int $correct
 * @param int $pageid
 * @param string $orderby
 * @param array $options
 * @return false|mixed
 */
function get_remote_lesson_attempts_by_lessonid_and_userid($lessonid, $userid, $retry, $correct = 0, $pageid = -1, $orderby = 'asc')
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_lesson_attempts_by_lessonid_and_userid',
            'params' => array('lessonid' => $lessonid, 'userid' => $userid, 'retry' => $retry, 'correct' => $correct, 'pageid' => $pageid, 'orderby' => $orderby)
        )
    );

    return $result->attempts;
}

/**
 * Get lesson answers by pageid and lessonid
 *
 * @param $pageid
 * @param $lessonid
 * @param array $options
 * @return false|mixed
 */
function get_remote_lesson_answers_by_pageid_and_lessonid($pageid, $lessonid)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_list_lesson_answers_by_pageid_and_lessonid',
            'params' => array('pageid' => $pageid, 'lessonid' => $lessonid)
        )
    );

    $answers = array();

    foreach ($result->answers as $answer) {
        $answers[$answer->id] = $answer;
    }

    return $answers;
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
 * Get lesson answers by lessonid
 *
 * @param $lessonid
 * @param array $options
 * @return false|mixed
 */
function get_remote_lesson_answers_by_lessonid($lessonid)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_lesson_answers_by_lessonid',
            'params' => array('lessonid' => $lessonid)
        )
    );

    return $result->answers;
}

/**
 * Get lesson grades by lessonid and userid
 *
 * @param $lessonid
 * @param $userid
 * @param array $options
 * @return false|mixed
 */
function get_remote_list_lesson_grades_by_lessonid_and_userid($lessonid, $userid, $limitfrom = 0, $limitnum = 0, $sort = "grade DESC")
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_lesson_grades_by_lessonid_and_userid',
            'params' => array('lessonid' => $lessonid, 'userid' => $userid, 'limitfrom' => $limitfrom, 'limitnum' => $limitnum, 'sort' => $sort)
        )
    );

    $grades = array();

    foreach ($result->grades as $grade) {
        $grades[$grade->id] = $grade;
    }

    return $grades;
}

function get_remote_list_lesson_grades_by_lessonid($lessonid, $sort = "completed")
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_lesson_grades_by_lessonid_and_userid',
            'params' => array('lessonid' => $lessonid, 'sort' => $sort)
        )
    );

    $grades = array();

    foreach ($result->grades as $grade) {
        $grades[$grade->id] = $grade;
    }

    return $grades;
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
 * Get field of lesson page by id
 *
 * @param $id
 * @param string $field
 * @return false|mixed
 */
function get_remote_field_lesson_pages_by_id($id, $field = 'title')
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_field_lesson_pages_by_id',
            'params' => array('id' => $id, 'field' => $field)
        )
    );

    return $result->field;
}

/**
 * Get list of lesson pages by lessonid
 *
 * @param $lessonid
 * @param array $options
 * @return false|mixed
 */
function get_remote_list_lesson_pages_by_lessonid($lessonid)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_lesson_pages_by_lessonid',
            'params' => array('lessonid' => $lessonid)
        )
    );

    $pages = array();

    foreach ($result->pages as $page) {
        $pages[$page->id] = $page;
    }

    return $pages;
}

/**
 * Get list lesson attempts by lessonid and userid and retry
 *
 * @param $lessonid
 * @param $userid
 * @param $retry
 * @param array $options
 * @return false|mixed
 */
function get_remote_list_lesson_attempts_by_lessonid_and_userid_and_retry($lessonid, $userid, $retry)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_lesson_attempts_by_lessonid_and_userid_and_retry',
            'params' => array('lessonid' => $lessonid, 'userid' => $userid, 'retry' => $retry)
        )
    );

    $attempts = array();

    foreach ($result->attempts as $attempt) {
        $attempts[$attempt->id] = $attempt;
    }

    return $attempts;
}

function get_remote_list_lesson_attempts_by_lessonid_and_userid_and_retry_and_pageid($lessonid, $userid, $retry, $pageid)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_lesson_attempts_by_lessonid_and_userid_and_retry_and_pageid',
            'params' => array('lessonid' => $lessonid, 'userid' => $userid, 'retry' => $retry, 'pageid' => $pageid)
        )
    );

    $attempts = array();

    foreach ($result->attempts as $attempt) {
        $attempts[$attempt->id] = $attempt;
    }

    return $attempts;
}

function get_remote_list_lesson_attempts_by_lessonid_and_pageid($lessonid, $pageid)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_lesson_attempts_by_lessonid_and_pageid',
            'params' => array('lessonid' => $lessonid, 'pageid' => $pageid)
        )
    );

    $attempts = array();

    foreach ($result->attempts as $attempt) {
        $attempts[$attempt->id] = $attempt;
    }

    return $attempts;
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
 * Get list of lesson overrides by lessonid
 *
 * @param $lessonid
 * @param array $options
 * @return false|mixed
 */
function get_remote_lesson_overrides_by_lessonid($lessonid)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_lesson_overrides_by_lessonid',
            'params' => array('lessonid' => $lessonid)
        )
    );

    return $result->overrides;
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
 * Get list of lesson attempts by pageid
 *
 * @param $pageid
 * @return false|mixed
 */
function get_remote_lesson_attempts_by_pageid($pageid)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_lesson_attempts_by_pageid',
            'params' => array('pageid' => $pageid)
        )
    );
    return $result->attempts;
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

function get_remote_count_lesson_attempts($lessonid = 0, $userid, $pageid = 0, $retry, $correct = 0)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_count_lesson_attempts',
            'params' => array('lessonid' => $lessonid, 'userid' => $userid, 'pageid' => $pageid, 'retry' => $retry, 'correct' => $correct)
        )
    );

    return $result->nattempts;
}

function get_remote_count_lesson_attempts_by_lessonid($lessonid)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_count_lesson_attempts_by_lessonid',
            'params' => array('lessonid' => $lessonid)
        )
    );

    return $result->nattempts;
}

function get_remote_list_lesson_pages_by_id_and_lessonid($id, $lessonid)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_list_lesson_pages_by_id_and_lessonid',
            'params' => array('id' => $id, 'lessonid' => $lessonid)
        )
    );

    $pages = array();

    foreach ($result->pages as $page) {
        $pages[$page->id] = $page;
    }

    return $pages;
}

function get_remote_count_lesson_pages_by_lessonid($lessonid, $qtype = 0)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_count_lesson_pages_by_lessonid',
            'params' => array('lessonid' => $lessonid, 'qtype' => $qtype)
        )
    );

    return $result->pagecount;
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

function get_remote_recordset_lesson_attempts_by_lessonid($lessonid)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_lesson_attempts_by_lessonid',
            'params' => array('lessonid' => $lessonid),
        )
    );

    return new json_moodle_recordset($result->attempts);
}

function get_remote_recordset_lesson_branch_by_lessonid($lessonid, $sort = 'timeseen')
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_lesson_branch_by_lessonid',
            'params' => array('lessonid' => $lessonid, 'sort' => $sort),
        )
    );

    return new json_moodle_recordset($result->branches);
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