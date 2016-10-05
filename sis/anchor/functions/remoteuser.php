<?php
// userid is of remote
function remote_get_user_link_profile($schoolid, $userid)
{
    //var_dump('remoteuser.php');
    $school = School::find($schoolid);
    $domain = $school->wwwroot;
    $token = $school->token;

    $resp = RestClient::dorest(
        array(
            'domain' => $domain,
            'token' => $token,
            'function_name'=>'local_host_get_user_link_profile',
            'params'=>array(
                'userid' => $userid)
        ));
    return $resp;
}