<?php
/**
 * Created by PhpStorm.
 * User: Vivi
 * Date: 5/26/2016
 * Time: 4:41 PM
 */
require_once('../../../config.php');
require_once('remotelib.php');
$assignid = optional_param('id', 0, PARAM_INT);
$coursemodule = null;
$html = '';
$assignobject = get_assign_summary_remote($assignid);

$PAGE->set_title($assignobject[0]?$assignobject[0]->cmname:"");
$PAGE->set_heading($assignobject[0]?$assignobject[0]->cmname:"");


$html .= $OUTPUT->header();


$html .= $OUTPUT->box_start('assign-detail', "assign_{$assignid}");
$html .= html_writer::tag('h3', $assignobject[0]->cmname, array('class' => 'gradingsummary'));
$table = new html_table();
$table->head = array('Name', 'Value');
$table->data[] = array("Participants",$assignobject[0]->participantcount);
$table->data[] = array("Submitted",$assignobject[0]->submissionssubmittedcount);
$table->data[] = array("Needs grading",$assignobject[0]->submissionsneedgradingcount);
$table->data[] = array("Due date",date("l, d M Y h:i A",$assignobject[0]->duedate));
$table->data[] = array("Time remaining",$assignobject[0]->cutoffdate);
$html .= html_writer::table($table);
$html .= $OUTPUT->box_end();


$html .= $OUTPUT->footer();

echo $html;