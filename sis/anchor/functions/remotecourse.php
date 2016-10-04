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


function remote_fetch_course($schoolid, $hubid = '')
{
    $shool = School::find($schoolid);
    $domain = $shool->wwwroot;
    $token = $shool->token;

    $resp = RestClient::dorest(
        array(
            'domain' => $domain,
            'token' => $token,
            'function_name'=>'local_host_fetch_course',
            'params'=>array('hubid' => $hubid)
        ));
    return $resp;
}

// userid and course id is of remote
function remote_enrol_user($schoolid, $roleid, $userid, $courseid)
{
    $shool = School::find($schoolid);
    $domain = $shool->wwwroot;
    $token = $shool->token;
    $userremoteid = User::find($userid)->remoteid;
    $remotecoursid = Course::find($courseid)->remoteid;

    $resp = RestClient::dorest(
        array(
            'domain' => $domain,
            'token' => $token,
            'function_name'=>'local_host_assign_role_to_user',
            'params'=>array('roleid' => $roleid,
                'userid' => $userremoteid,
                'courseid' => $remotecoursid)
        ));
    return $resp;
}