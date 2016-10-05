<?php
require_once("../config.php");
require_once ('./lib.php');
defined('MOODLE_INTERNAL') || die();

$slideid = optional_param('id', 0, PARAM_INT);        // slide id
if ($slideid !== 0) {
    // validation user permission for this slide
    // render data from slide id
} else {
    // create new slide
    // when save event trigger, save to api endpoint
}

$PAGE->set_url('/contentcreator/index.php');
$PAGE->set_context(context_system::instance());
$site = get_site();

$PAGE->set_pagelayout('contentcreator');

require_login();

$PAGE->set_heading($site->fullname);
$PAGE->set_title("Create Slide");


echo $OUTPUT->header();
echo $OUTPUT->skip_link_target();
echo printscriptnewpresentation();
echo $OUTPUT->footer();
