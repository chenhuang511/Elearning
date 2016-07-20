<?php

defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot . '/lib/remote/lib.php');
require_once($CFG->dirroot . '/lib/additionallib.php');
require_once($CFG->dirroot . '/mnet/service/enrol/locallib.php');
require_once($CFG->dirroot . '/lib/dml/json_moodle_recordset.php');

function get_remote_field_forum_by($modname, $parameters, $field = 'name')
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_field_forum_by',
            'params' => array_merge(array('modname' => $modname, 'field' => $field), $parameters),
        )
    );

    return $result->field;
}

function get_remote_count_forum_by($modname, $parameters, $sort = '')
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_count_forum_by',
            'params' => array_merge(array('modname' => $modname, 'sort' => $sort), $parameters),
        )
    );

    return $result->count;
}

function get_remote_forum_by($parameters, $sort = '', $mustexists = FALSE)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_forum_by',
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

function get_remote_forum_discussion_subs_by($parameters, $sort = '', $mustexists = FALSE)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_forum_discussion_subs_by',
            'params' => array_merge(array('sort' => $sort, 'mustexists' => $mustexists), $parameters),
        )
    );

    return $result->sub;
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

function get_remote_forum_digests_by($parameters, $sort = '', $mustexists = FALSE)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_forum_digests_by',
            'params' => array_merge(array('sort' => $sort, 'mustexists' => $mustexists), $parameters),
        )
    );

    return $result->digest;
}

function get_remote_forum_track_prefs_by($parameters, $sort = '', $mustexists = FALSE)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_forum_track_prefs_by',
            'params' => array_merge(array('sort' => $sort, 'mustexists' => $mustexists), $parameters),
        )
    );

    return $result->track;
}

function get_remote_forum_subscriptions_by($parameters, $sort = '', $mustexists = FALSE)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_forum_subscriptions_by',
            'params' => array_merge(array('sort' => $sort, 'mustexists' => $mustexists), $parameters),
        )
    );

    return $result->subscription;
}

function get_remote_scale_by($parameters, $sort = '', $mustexists = FALSE)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_scale_by',
            'params' => array_merge(array('sort' => $sort, 'mustexists' => $mustexists), $parameters),
        )
    );

    return $result->scale;
}

function get_remote_list_forum_by($parameters, $sort = '', $limitfrom = 0, $limitnum = 0)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_list_forum_by',
            'params' => array_merge(array('sort' => $sort, 'limitfrom' => $limitfrom, 'limitnum' => $limitnum), $parameters)
        )
    );

    $forums = array();

    foreach ($result->forums as $forum) {
        $forums[$forum->id] = $forum;
    }

    return $forums;
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

function get_remote_list_forum_read_by($parameters, $sort = '', $limitfrom = 0, $limitnum = 0)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_list_forum_read_by',
            'params' => array_merge(array('sort' => $sort, 'limitfrom' => $limitfrom, 'limitnum' => $limitnum), $parameters)
        )
    );

    $reads = array();

    foreach ($result->reads as $read) {
        $reads[$read->id] = $read;
    }

    return $reads;
}

function delete_remote_mdl_forum($modname, $parameters)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_delete_mdl_forum',
            'params' => array_merge(array('modname' => $modname), $parameters),
        )
    );

    return $result->post;
}

function save_remote_mdl_forum($modname, $data)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_save_mdl_forum',
            'params' => array_merge(array('modname' => $modname), $data),
        )
    );

    return $result->newid;
}

function update_remote_mdl_forum($modname, $id, $data)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_update_mdl_forum',
            'params' => array_merge(array('modname' => $modname, "id" => $id), $data),
        )
    );

    return $result->id;
}

function update_remote_mdl_forum_by($modname, $parameters, $data)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_update_mdl_forum',
            'params' => array_merge(array_merge(array('modname' => $modname), $parameters), $data),
        )
    );

    return $result->id;
}

function check_remote_record_forum_exists($modname, $parameters)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_check_record_forum_exists_by',
            'params' => array_merge(array('modname' => $modname), $parameters)
        )
    );

    return $result->status;
}

function get_remote_forum_get_discussions_sql($sql, $parameters, $limitfrom = 0, $limitnum = 0)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_forum_get_discussions_sql',
            'params' => array_merge(array('sql' => $sql, 'limitfrom' => $limitfrom, 'limitnum' => $limitnum), $parameters)
        )
    );

    $data = array();

    foreach ($result->data as $d) {
        $data[$d->id] = $d;
    }

    return $data;
}

function get_remote_count_forum_sql($sql, $parameters)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_count_forum_sql',
            'params' => array_merge(array('sql' => $sql), $parameters)
        )
    );

    return $result->count;
}

function get_remote_forum_count_discussion_replies_sql($sql, $parameters, $limitfrom = 0, $limitnum = 0)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_forum_count_discussion_replies_sql',
            'params' => array_merge(array('sql' => $sql, 'limitfrom' => $limitfrom, 'limitnum' => $limitnum), $parameters)
        )
    );

    $replies = array();
    foreach ($result->replies as $reply) {
        $replies[$reply->discussion] = $reply;
    }

    return $replies;
}

function get_remote_forum_get_post_full_sql($sql, $parameters)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_forum_get_post_full_sql',
            'params' => array_merge(array('sql' => $sql), $parameters)
        )
    );

    return $result->post;
}

function get_remote_forum_get_discussion_neighbours_sql($sql, $parameters, $strictness = IGNORE_MISSING)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_forum_get_discussion_neighbours_sql',
            'params' => array_merge(array('sql' => $sql, 'strictness' => $strictness), $parameters)
        )
    );

    return $result->neighbour;
}

function get_remote_forum_get_all_discussion_posts_sql($sql, $parameters)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_forum_get_all_discussion_posts_sql',
            'params' => array_merge(array('sql' => $sql), $parameters)
        )
    );

    $posts = array();

    foreach ($result->posts as $post) {
        $posts[$post->id] = $post;
    }

    return $posts;
}

