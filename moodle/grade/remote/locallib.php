<?php

defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot . '/lib/remote/lib.php');
require_once($CFG->dirroot . '/lib/additionallib.php');
require_once($CFG->dirroot . '/mnet/service/enrol/locallib.php');

function get_remote_grade_settings_by($parameters, $sort = '', $mustexists = FALSE)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_get_grade_settings_by',
            'params' => array_merge(array('sort' => $sort, 'mustexists' => $mustexists), $parameters),
        )
    );

    return $result->setting;
}

function get_remote_grade_categories_by($parameters, $sort = '', $mustexists = FALSE)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_get_grade_categories_by',
            'params' => array_merge(array('sort' => $sort, 'mustexists' => $mustexists), $parameters),
        )
    );

    return $result->category;
}

function get_remote_list_grade_settings_by($parameters, $sort = '', $limitfrom = 0, $limitnum = 0)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_get_list_grade_settings_by',
            'params' => array_merge(array('sort' => $sort, 'limitfrom' => $limitfrom, 'limitnum' => $limitnum), $parameters)
        )
    );

    $settings = array();

    foreach ($result->settings as $setting) {
        $settings[$setting->id] = $setting;
    }

    return $settings;
}

function get_remote_list_grade_categories_by($parameters, $sort = '', $limitfrom = 0, $limitnum = 0)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_get_list_grade_categories_by',
            'params' => array_merge(array('sort' => $sort, 'limitfrom' => $limitfrom, 'limitnum' => $limitnum), $parameters)
        )
    );

    $categories = array();

    foreach ($result->categories as $category) {
        $categories[$category->id] = $category;
    }

    return $categories;
}

function save_remote_mdl_grade($modname, $data)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_save_mdl_grade',
            'params' => array_merge(array('modname' => $modname), $data),
        )
    );

    return $result->newid;
}

function update_remote_mdl_grade($modname, $id, $data)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_update_mdl_grade',
            'params' => array_merge(array('modname' => $modname, "id" => $id), $data),
        )
    );

    return $result->id;
}

function update_remote_mdl_grade_sql($sql, $data)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_update_mdl_grade_sql',
            'params' => array_merge(array('sql' => $sql), $data),
        )
    );

    return $result->status;
}

function delete_remote_mdl_grade($modname, $parameters)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_delete_mdl_grade',
            'params' => array_merge(array('modname' => $modname), $parameters),
        )
    );

    return $result->status;
}

function check_remote_record_grade_exists($modname, $parameters)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_check_record_mdl_grade_exists_by',
            'params' => array_merge(array('modname' => $modname), $parameters)
        )
    );

    return $result->status;
}

function count_remote_mdl_grade($sql, $parameters)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_count_mdl_grade_sql',
            'params' => array_merge(array('sql' => $sql), $parameters)
        )
    );

    return $result->count;
}

function get_field_remote_mdl_grade($sql, $parameters)
{
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_get_field_mdl_grade_sql',
            'params' => array_merge(array('sql' => $sql), $parameters)
        )
    );

    return $result->field;
}

function get_remote_list_grade_categories_raw_data($sql, $param, $pagestart = 0, $pagesize = 0) {
    $resp = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_grade_get_list_grade_categories_raw_data',
            'params' => array_merge(array('sql' => $sql, 'pagestart' => $pagestart, 'pagesize' => $pagesize), $param)
        ), false
    );
    return $resp;
}

function get_remote_sum_grader_report_by_sql_query($sql, $param) {
    $resp = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_grade_get_sum_grader_report_by_sql_query',
            'params' => array('sql' => $sql, 'param' => $param)
        ), false
    );
    return $resp;
}

function get_remote_count_grader_report_by_sql_query($sql, $param) {
    $resp = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_grade_get_count_grader_report_by_sql_query',
            'params' => array('sql' => $sql, 'param' => $param)
        ), false
    );
    return $resp;
}
