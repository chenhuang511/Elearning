<?php
require_once("../../config.php");
require_once ('../lib.php');
defined('MOODLE_INTERNAL') || die();

$slideid = required_param('id', PARAM_INT);

$contenthtml = getcontenthtmlbyslideid($slideid);

include_once './includes/bespoke.php';
