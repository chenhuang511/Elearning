<?php

//var_dump('user externallib.php');

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/externallib.php');


class local_certificate_external extends external_api
{

    public static function get_user_link_profile_parameters() {
        return new external_function_parameters(
            array(
                'userid' => new external_value(PARAM_INT, 'user id in remote'),
            )
        );
    }

    public static function get_user_link_profile($userid) {
        global $CFG;

        //validate parameter
        $params = self::validate_parameters(self::get_user_link_profile_parameters(),
            array('userid' => $userid));

        $userlocal = get_remote_mapping_localuserid($userid);
        if(!$userlocal) {return 'false';};
        $url = $CFG->wwwroot . '/user/profile.php?id='. $userlocal;
        return $url;
    }

    public static function get_user_link_profile_returns() {
        return new external_value(PARAM_TEXT, 'userid');
    }
}
