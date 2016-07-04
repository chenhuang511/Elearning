<?php

function get_remote_wiki_by_id($id) {
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_wiki_get_wiki_by_id',
            'params' => array('id' => $id)
        )
    );

    return $result->wiki;
}

function get_remote_current_version_wiki_by_pageid($pageid) {
    $result = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN_M,
            'function_name' => 'local_mod_wiki_get_page_for_editing',
            'params' => array('pageid' => $pageid)
        )
    );

    return $result->version;
}

/**
 * @param $wikiid
 * @param $groupid
 * @param int $userid
 */
function get_remote_subwiki_by_group($wikiid, $groupid, $userid = 0)
{
    $subwikis = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN_M,
            'function_name' => 'mod_wiki_get_subwikis',
            'params' => array('wikiid' => $wikiid)
        )
    );

    return $subwikis->subwikis[0];
}

function get_remote_wiki_first_page($subwikiid, $module = null)
{
    return moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_wiki_get_first_page',
            'params' => array('subwikiid' => $subwikiid, 'module' => $module)
        )
    );
}