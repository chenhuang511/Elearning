<?php
/**
 * Created by PhpStorm.
 * User: Vivi
 * Date: 5/26/2016
 * Time: 4:41 PM
 */

require_once('../../../config.php');
require_once('locallib.php');

$cmid = optional_param('id', 0, PARAM_INT);
$action = optional_param('action', "", PARAM_TEXT);
$o = new assign_mod($cmid);
$o->setOUTPUT($OUTPUT);
$o->setPAGE($PAGE);
$PAGE->set_title($o->name);
$PAGE->set_heading($o->name);
$html .= $OUTPUT->header();


switch ($action){
    case 'grading':
        
        break;
    default:
        $html .= $o->view_summary();
        break;
}
$html .= $OUTPUT->footer();
echo $html;