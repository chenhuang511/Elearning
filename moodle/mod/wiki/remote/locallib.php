<?php

function get_remote_wiki_by_id($id) {
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN_M,
            'function_name' => 'local_mod_wiki_get_wiki_by_id',
            'params' => array('id' => $id)
        )
    );
}