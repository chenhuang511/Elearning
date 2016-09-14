<?php

defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot . '/mnet/lib.php');
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
        ), false
    );

    return $result->field;
}

function get_remote_field_forum_sql($sql, $parameters)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_field_forum_sql',
            'params' => array_merge(array('sql' => $sql), $parameters),
        ), false
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
        ), false
    );

    return $result->count;
}

function get_remote_forum_by($parameters, $sort = '', $mustexists = FALSE)
{
    global $DB;

    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_forum_by',
            'params' => array_merge(array('sort' => $sort, 'mustexists' => $mustexists), $parameters),
        ), false
    );
    if (!isset($result->exception))
        $forum = $result->forum;
    else
        $forum = 0;

    if ($forum) {
        $course = $DB->get_record('course', array('remoteid' => $forum->course));
        if ($course) {
            $forum->course = $course->id;
        }
        $localforum = $DB->get_record('forum', array('remoteid' => $forum->id));
        if ($localforum) {
            $info = [
                'maxbytes',
                'maxattachments',
                'displaywordcount',
                'forcesubscribe',
                'trackingtype',
                'blockperiod',
                'blockafter',
                'warnafter',
                'assessed',
                'assesstimestart',
                'assesstimefinish',
                'scale'
            ];

            foreach ($info as $key) {
                $forum->$key = $localforum->$key;
            }
        }
    }
    return $forum;
}

function get_remote_forum_discussions_by($parameters, $sort = '', $mustexists = FALSE)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_forum_discussions_by',
            'params' => array_merge(array('sort' => $sort, 'mustexists' => $mustexists), $parameters),
        ), false
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
        ), false
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
        ), false
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
        ), false
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
        ), false
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
        ), false
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
        ), false
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
        ), false
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
        ), false
    );

    $discussions = array();

    foreach ($result->discussions as $discussion) {
        $discussions[$discussion->id] = $discussion;
    }

    return $discussions;
}

function get_remote_list_forum_discussions_sql($parameters, $sort = '')
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_list_forum_discussions_sql',
            'params' => array_merge(array('hostip' => gethostip(), 'sort' => $sort), $parameters)
        ), false
    );

    $discussions = array();

    foreach ($result->discussions as $discussion) {
        $discussions[$discussion->id] = $discussion;
    }

    return $discussions;
}

function get_remote_list_forum_discussion_subs_by($parameters, $sort = '', $limitfrom = 0, $limitnum = 0)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_list_forum_discussion_subs_by',
            'params' => array_merge(array('sort' => $sort, 'limitfrom' => $limitfrom, 'limitnum' => $limitnum), $parameters)
        ), false
    );

    $subs = array();

    foreach ($result->subs as $sub) {
        $subs[$sub->id] = $sub;
    }

    return $subs;
}

function get_remote_list_forum_posts_by($parameters, $sort = '', $limitfrom = 0, $limitnum = 0)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_list_forum_posts_by',
            'params' => array_merge(array('sort' => $sort, 'limitfrom' => $limitfrom, 'limitnum' => $limitnum), $parameters)
        ), false
    );

    $posts = array();

    foreach ($result->posts as $post) {
        $posts[$post->id] = $post;
    }

    return $posts;
}

function get_remote_list_forum_posts_sql($parameters, $sort = '')
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_list_forum_posts_sql',
            'params' => array_merge(array('hostip' => gethostip(), 'sort' => $sort), $parameters)
        ), false
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
        ), false
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

    return $result->status;
}

function delete_remote_mdl_forum_select($modname, $select, $parameters)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_delete_mdl_forum_select',
            'params' => array_merge(array('modname' => $modname, 'select' => $select), $parameters),
        )
    );

    return $result->status;
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
        ), false
    );

    return $result->status;
}

function get_remote_forum_get_discussions_sql($postdata, $allnames, $umfields, $umtable, $timelimit, $groupselect, $forumsort, $parameters, $limitfrom = 0, $limitnum = 0)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_forum_get_discussions_sql',
            'params' => array_merge(array('postdata' => $postdata, 'allnames' => $allnames, 'umfields' => $umfields, 'umtable' => $umtable, 'timelimit' => $timelimit, 'groupselect' => $groupselect, 'forumsort' => $forumsort, 'hostip' => gethostip(), 'limitfrom' => $limitfrom, 'limitnum' => $limitnum), $parameters)
        ), false
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
            'params' => array_merge(array('sql' => $sql, 'hostip' => gethostip()), $parameters)
        ), false
    );

    return $result->count;
}

function get_remote_forum_count_discussion_replies_sql($parameters, $limitfrom, $limitnum, $forumsort, $orderby, $groupby)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_forum_count_discussion_replies_sql',
            'params' => array_merge(array('limitfrom' => $limitfrom, 'limitnum' => $limitnum, 'forumsort' => $forumsort, 'orderby' => $orderby, 'groupby' => $groupby), $parameters)
        ), false
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
        ), false
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
            'params' => array_merge(array('sql' => $sql, 'hostip' => gethostip(), 'strictness' => $strictness), $parameters)
        ), false
    );

    return $result->neighbour;
}

function get_remote_forum_get_all_discussion_posts_sql($allnames, $tracking, $sort, $parameters)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_forum_get_all_discussion_posts_sql',
            'params' => array_merge(array('allnames' => $allnames, 'tracking' => $tracking, 'sort' => $sort, 'hostip' => gethostip()), $parameters)
        ), false
    );

    $posts = array();

    foreach ($result->posts as $post) {
        $posts[$post->id] = $post;
    }

    return $posts;
}

function get_remote_forum_user_has_posted($sql, $parameters)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_forum_user_has_posted',
            'params' => array_merge(array('sql' => $sql), $parameters)
        ), false
    );

    return $result->status;
}

function get_remote_forum_user_has_posted_discussion($sql, $parameters)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_forum_user_has_posted_discussion',
            'params' => array_merge(array('sql' => $sql), $parameters)
        ), false
    );

    return $result->status;
}

function get_remote_forum_search_posts_sql($fromsql, $selectsql, $allnames, $parameters, $limitfrom = 0, $limitnum = 0)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_forum_search_posts_sql',
            'params' => array_merge(array('fromsql' => $fromsql, 'selectsql' => $selectsql, 'allnames' => $allnames, 'hostip' => gethostip(), 'limitfrom' => $limitfrom, 'limitnum' => $limitnum), $parameters)
        ), false
    );
    $data = new stdClass();
    $data->totalcount = $result->totalcount;
    $rs = array();

    foreach ($result->rs_search as $r) {
        $rs[$r->id] = $r;
    }

    $data->rs = $rs;

    return $data;
}

function save_remote_forum_add_instance($forumdata)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_forum_add_instance',
            'params' => array_merge($forumdata),
        )
    );
    return $result->newid;
}

function save_remote_forum_add_discussions($discussion, $userid)
{
    global $DB;

    if ($discussion) {
        $course = $DB->get_record('course', array("id" => $discussion->course), "id, remoteid");
        if ($course) {
            $discussion->course = $course->remoteid;
        }
        $forum = $DB->get_record('forum', array("id" => $discussion->forum), "id, remoteid");
        if ($forum) {
            $discussion->forum = $forum->remoteid;
        }

        $hubuser = get_remote_mapping_user($discussion->userid);
        if ($hubuser) {
            $discussion->userid = $hubuser[0]->id;
        }
    }

    $data = array();
    $i = 0;
    foreach ($discussion as $key => $val) {
        $data["data[$i][name]"] = $key;
        $data["data[$i][value]"] = $val;
        $i++;
    }

    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_forum_add_discussions',
            'params' => array_merge(array('userid' => $userid), $data),
        )
    );
    return $result;
}

