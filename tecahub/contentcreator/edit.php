<?php
require_once("../config.php");
require_once ('./lib.php');
defined('MOODLE_INTERNAL') || die();

$slideid = required_param('id', PARAM_INT);        // slide id

$slideobj = getslidesbyid($slideid);

$PAGE->set_url('/contentcreator/index.php');
$PAGE->set_context(context_system::instance());
$site = get_site();

$PAGE->set_pagelayout('contentcreator');

require_login();

$PAGE->set_heading($site->fullname);
$PAGE->set_title("Update Slide");


echo $OUTPUT->header();
echo $OUTPUT->skip_link_target();
echo printscriptpresentationdata(json_encode($slideobj->content_json), $slideid, $slideobj->filename);
echo $OUTPUT->footer();
