<?php


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