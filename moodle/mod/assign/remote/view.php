<?php
/**
 * Created by PhpStorm.
 * User: Vivi
 * Date: 5/26/2016
 * Time: 4:41 PM
 */
require_once('../../../config.php');
require_once('remotelib.php');
$courseid = optional_param('courseid', 0, PARAM_INT);
$assignid = optional_param('modid', 0, PARAM_INT);

$coursemodule = null;
$html = '';
$courseobject = get_assign_summary_remote($courseid);
print_r($courseobject);
if(count($courseobject)>0) $courseobject = $courseobject[0];
$listassignment = $courseobject->assignments;
$assignobject = new stdClass();
foreach($listassignment as $assignment){
    print_r($assignment);
   // if($assignment->id==$assignid) $assignobject = $assignment;
}
die;
$PAGE->set_title($assignobject->name);
$PAGE->set_heading($assignobject->name);


$html .= $OUTPUT->header();


$html .= $OUTPUT->box_start('assign-detail', "assign_{$assignid}");
$html .= html_writer::tag('h3', $assignobject->name, array('class' => 'gradingsummary'));
$table = new html_table();
$table->head = array('Name', 'Value');
$table->data[] = array("Participants",$assignobject->participantcount);
$table->data[] = array("Submitted",$assignobject->submissionssubmittedcount);
$table->data[] = array("Needs grading",$assignobject->submissionsneedgradingcount);
$table->data[] = array("Due date",date("l, d M Y h:i A",$assignobject->duedate));
$table->data[] = array("Time remaining",$assignobject->cutoffdate);
$html .= html_writer::table($table);
$html .= $OUTPUT->box_end();


$html .= $OUTPUT->footer();

echo $html;