<?php
// userid and remote id of remote
function remote_enrol_host($roleid, $hostid, $courseid, $methodname)
{
    $resp = RestClient::dorest(
        array(
            'domain' => HUB_URL,
            'token' => TOKEN,
            'function_name'=>'local_mod_remote_enrol_course',
            'params'=>array('roleid' => $roleid,
                'hostid' => $hostid,
                'courseid' => $courseid,
                'methodname' => $methodname
            )
        ));
    return $resp;
}


function remote_fetch_course($hostid = '')
{
//test
    $resp = RestClient::dorest(
        array(
            'domain' => 'http://192.168.1.17',
            'token' => '503fd23417e674e50775369aff31ae13',
            'function_name'=>'local_host_fetch_course',
            'params'=>array('hostid' => $hostid)
        ));
    return $resp;
}
// userid and remote id of remote
function remote_enrol_user($roleid, $userid, $courseid)
{
//test
    $resp = RestClient::dorest(
        array(
            'domain' => 'http://192.168.1.17',
            'token' => '503fd23417e674e50775369aff31ae13',
            'function_name'=>'local_host_assign_role_to_user',
            'params'=>array('roleid' => $roleid,
                'userid' => $userid,
                'courseid' => $courseid)
        ));
    return $resp;
}