<?php
require_once("../config.php");
require_once ('./lib.php');
defined('MOODLE_INTERNAL') || die();

$PAGE->set_url('/contentcreator/index.php');
$PAGE->set_context(context_system::instance());
$site = get_site();

$PAGE->set_pagelayout('standard');

require_login();

$PAGE->set_heading($site->fullname);
$PAGE->set_title("Content Creator");


echo $OUTPUT->header();
echo $OUTPUT->skip_link_target();
echo '<p class="clearfix"><a href="new.php" class="btn btn-primary btn-el-reg">Tạo mới Slide</a></p>';
echo '<p>Các slide đã tạo:</p>';
echo printallslidebelongtouser();
echo $OUTPUT->footer();
