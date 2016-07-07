<?php

defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot . '/lib/remote/lib.php');
require_once($CFG->dirroot . '/lib/additionallib.php');
require_once($CFG->dirroot . '/mnet/service/enrol/locallib.php');
require_once($CFG->dirroot . '/lib/dml/json_moodle_recordset.php');

function get_remote_forum_by_id($forumid)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_forum_by_id',
            'params' => array('forumid' => $forumid),
        )
    );
    return $result->forum;
}

function get_remote_discussion_by_forumid($forumid, $sort = 'timemodified ASC')
{
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN_M,
            'function_name' => 'mod_forum_get_forum_discussions_paginated',
            'params' => array('forumid' => $forumid, 'sortby' => $sort)
        )
    );
}

function get_remote_forum_post_by_discussion_and_userid($discussion, $userid, $subject, $message)
{
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN_M,
            'function_name' => 'mod_forum_get_forum_discussion_posts',
            'params' => array('discussion' => $discussion, 'userid' => $userid, 'subject' => $subject, 'message' => $message)
        )
    );
}

function get_remote_forum_by($parameters, $sort = '', $mustexists = FALSE)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'get_forum_by',
            'params' => array_merge(array('sort' => $sort, 'mustexists' => $mustexists), $parameters),
        )
    );

    return $result->forum;
}
function get_remote_forum_discussions_by($parameters, $sort = '', $mustexists = FALSE)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_forum_discussions_by',
            'params' => array_merge(array('sort' => $sort, 'mustexists' => $mustexists), $parameters),
        )
    );

    return $result->discussion;
}
function get_remote_forum_posts_by($parameters, $sort = '', $mustexists = FALSE)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_forum_posts_by',
            'params' => array_merge(array('sort' => $sort, 'mustexists' => $mustexists), $parameters),
        )
    );

    return $result->post;
}

function get_remote_list_forum_discussions_by($parameters, $sort = '', $limitfrom = 0, $limitnum = 0)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_list_forum_discussions_by',
            'params' => array_merge(array('sort' => $sort, 'limitfrom' => $limitfrom, 'limitnum' => $limitnum), $parameters)
        )
    );

    $discussions = array();

    foreach ($result->discussions as $discussion) {
        $discussions[$discussion->id] = $discussion;
    }

    return $discussions;
}
function get_remote_list_forum_posts_by($parameters, $sort = '', $limitfrom = 0, $limitnum = 0)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_list_forum_posts_by',
            'params' => array_merge(array('sort' => $sort, 'limitfrom' => $limitfrom, 'limitnum' => $limitnum), $parameters)
        )
    );

    $posts = array();

    foreach ($result->posts as $post) {
        $posts[$post->id] = $post;
    }

    return $posts;
}

