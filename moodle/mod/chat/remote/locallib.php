<?php
defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot . '/lib/remote/lib.php');
require_once($CFG->dirroot . '/lib/additionallib.php');

function get_remote_chat_latest_messages($chatsid)
{
    $lastmessage = moodle_webservice_client(array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN_M,
            'function_name' => 'mod_chat_get_chat_latest_messages',
            'params' => array('chatsid' => $chatsid)
        ), false
    );
    return $lastmessage->messages;
}

function remote_chat_send_chat_message($chatsid, $messagetext)
{
    $messageid = moodle_webservice_client(array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN_M,
            'function_name' => 'mod_chat_send_chat_message',
            'params' => array('chatsid' => $chatsid, 'messagetext' => $messagetext)
        )
    );
    return $messageid->messageid;
}

function get_remote_chat_user($chatsid)
{
    $user = moodle_webservice_client(array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN_M,
            'function_name' => 'mod_chat_get_chat_users',
            'params' => array('chatsid' => $chatsid)
        ), false
    );
    return $user->users[0];
}

function get_remote_chat_login_user($userid, $chatid, $groupid = 0)
{
    return moodle_webservice_client(array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN_M,
            'function_name' => 'mod_chat_login_user',
            'params' => array('userid' => $userid, 'chatid' => $chatid, 'groupid' => $groupid)
        ) , false
    );
}

function get_remote_chat_by_id($id)
{
    $resp = moodle_webservice_client(array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_chat_get_chat_by_id',
            'params' => array('id' => $id)
        )
    );

    if (isset($resp->exception)) {
        return 0;
    }

    return $resp;
}

function local_get_remote_chat_user($groupingjoin, $groupselect, $data)
{
    $res = moodle_webservice_client(array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_chat_get_chat_users',
            'params' => array(
                'groupingjoin' => $groupingjoin,
                'groupselect' => $groupselect,
                'data[chatid]' => $data['chatid'],
                'data[groupid]' => $data['groupid'],
                'data[groupingid]' => $data['groupingid'],
            )
        )
    );
    return $res;
}

function local_get_remote_chat_current_messages($chatsid, $groupid, $chatid)
{
    $res = moodle_webservice_client(array(
        'domain' => HUB_URL,
        'token' => HOST_TOKEN,
        'function_name' => 'local_mod_chat_get_chat_current_messages',
        'params' => array('chatsid' => $chatsid, 'groupid' => $groupid, 'chatid' => $chatid)
        ), false
    );
    return $res;
}
