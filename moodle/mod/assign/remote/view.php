<?php
/**
 * Created by PhpStorm.
 * User: Vivi
 * Date: 5/26/2016
 * Time: 4:41 PM
 */
require_once('../../../config.php');
require_once('locallib.php');

$courseid = optional_param('courseid', 0, PARAM_INT);
$assignid = optional_param('modid', 0, PARAM_INT);
$action = optional_param('action', "", PARAM_TEXT);
$o = new assign_mod($courseid,$assignid);
$o->setOUTPUT($OUTPUT);
$o->setPAGE($PAGE);



switch ($action){
    case 'grading':
        
        break;
    default:
        list ($course, $assignobject) = $o->get_assign_summary_remote();
        $PAGE->set_title($assignobject->name);
        $PAGE->set_heading($assignobject->name);
        $html .= $OUTPUT->header();
        $html .= $o->view_summary();
        break;
}
$html .= $OUTPUT->footer();
echo $html;