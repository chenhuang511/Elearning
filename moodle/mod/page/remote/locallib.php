<?php

function get_remote_page_by_id($id) {
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_page_get_page_by_id',
            'params' => array('id' => $id)
        )
    );
}