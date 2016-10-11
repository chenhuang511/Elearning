<?php

function remote_get_percent_course($schoolid, $courseid, $userid)
{
    $school = School::find($schoolid);
    $domain = $school->wwwroot;
    $token = $school->token;

    $resp = RestClient::dorest(
        array(
            'domain' => $domain,
            'token' => $token,
            'function_name'=>'local_host_get_percent_course',
            'params'=>array(
                'courseid' => $courseid,
                'userid' => $userid)
        ));
    return $resp;
}