<?php

defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot . '/lib/remote/lib.php');

function get_remote_users_info_by_field($field, $values = array(), $options = array())
{
    return moodle_webservice_client(array_merge($options,
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN_M,
            'function_name' => 'core_user_get_users_by_field',
            'params' => array(
                'field' => $field,
                'values' => $values,
            ),
        )
    ));
}
