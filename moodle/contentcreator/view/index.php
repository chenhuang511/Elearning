<?php
require_once("../../config.php");

$slideid = required_param('id', PARAM_INT);

/**
 * get data from database
 * check if slide id public
 * if not, check slide owner
 * or throw new exception
 */

function getcontenthtmlbyslideid($id) {
    global $DB, $USER;
    $slideobj = $DB->get_record('slide_storage', ['id' => $id]);
    if ($slideobj === false) {
        throw new dml_missing_record_exception('slide_storage');
    }
    if ($slideobj->userid !== $USER->id && $slideobj->visibility != true) {
        throw new moodle_exception('nopermissions', 'error', '', 'view slide');
    }
    return $slideobj->content_html;
}

$contenthtml = getcontenthtmlbyslideid($slideid);

include_once './includes/bespoke.php';
