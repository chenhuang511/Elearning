<?php

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

