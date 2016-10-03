<?php
// userid and course id is of remote
function remote_get_link_certificate($schoolid, $userid, $courseid)
{
    $school = School::find($schoolid);
    $domain = $school->wwwroot;
    $token = $school->token;
    $userremoteid = Student::find($userid)->remoteid;
    $remotecoursid = Course::find($courseid)->remoteid;

    $resp = RestClient::dorest(
        array(
            'domain' => $domain,
            'token' => $token,
            'function_name'=>'local_host_certificate_get_link',
            'params'=>array(
                'userid' => $userremoteid,
                'courseid' => $remotecoursid)
        ));
    return $resp;
}