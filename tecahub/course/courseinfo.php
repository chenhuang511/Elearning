<?php
/**
 * Created by PhpStorm.
 * User: Minh Nguyen
 * Date: 6/7/2016
 * Time: 10:14 AM
 */

require_once('../config.php');
require_once('lib.php');
require_once('courseinfo_form.php');

$courseid = required_param('course',PARAM_INT ); //Course id
$id  = optional_param('id', 0, PARAM_INT); // info ID

$PAGE->set_url('/course/courseinfo.php', array('course' => $courseid));
$PAGE->set_pagelayout('admin');

//Editing course
if ($courseid == SITEID){
    // Don't allow editing of  'site course' using this from.
    print_error('cannoteditsiteform');
}

// Login to the course and retrieve course info.
$course = get_course($courseid);
require_login($course);

// Check if not courseinfo then create new courseinfo
if(!$courseinfo = $DB->get_record('course_info', array('course'=>$courseid), '*')){
    $courseinfo = new stdClass();
    $courseinfo->id = null;
    $courseinfo->course = $courseid;
}

$coursecontext = context_course::instance($courseid);
require_capability('moodle/course:update', $coursecontext);

// Prepare course and the editor.
$editoptions = array(
    'noclean'=>true,
    'subdirs'=>true,
    'maxfiles'=>-1,
    'maxbytes'=> 0,
    'context'=>$coursecontext
);

$courseinfo = file_prepare_standard_editor($courseinfo, 'info', $editoptions, $coursecontext, 'course', 'info', $courseinfo->id);

// First create the form
$args = array(
    'courseinfo'=>$courseinfo,
    'editoptions'=>$editoptions
);

$infoform = new course_info_form(null, $args);

if ($infoform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
    redirect('/course/view.php?id='.$courseid);
} else if ($data = $infoform->get_data()) {
    if($data->id){
        // store the files
        $data->timemodified = time();
        $data = file_postupdate_standard_editor($data, 'info', $editoptions , $coursecontext, 'mod_book', 'chapter', $data->id );
        $DB->update_record('course_info', $data );
            
    } else{
         // adding new course info
        $data->info = '';
        $data->infoformat = FORMAT_HTML;
        $data->timecreated = time();
        $data->timemodified = time();


        $row = $DB->get_record_sql('SELECT id FROM {course_info} WHERE id = ( SELECT max(id) FROM {course_info} )');
        $data->id = (empty($row)) ? 1 : $row->id + 1;
	$insertdata              = new StdClass();
        $insertdata->id          = $data->id;
        $insertdata->course      = $data->course;
        $insertdata->info        = $data->info;
        $insertdata->validatetime= $data->validatetime;
        $insertdata->timecreated = $data->timecreated;
        $insertdata->timemodified= $data->timemodified;
        $insertdata->infoformat  = $data->infoformat;
        $insertdata->note        = $data->note;

        $DB->insert_record_raw('course_info', $insertdata, true, false, true);
        
        // store the files
        $data = file_postupdate_standard_editor($data, 'info', $editoptions, $coursecontext, 'course', 'info', $data->id);
        $DB->update_record('course_info', $data);
    }

    redirect('/course/view.php?id='.$courseid);
}

// Print the form
$site = get_site();

$strcourseinfo = 'Course information';

$pagedesc = $strcourseinfo;
$title = "$site->shortname: $strcourseinfo";
$fullname = $site->fullname;
$PAGE->navbar->add($pagedesc);

$PAGE->set_title($title);
$PAGE->set_heading($fullname);

echo $OUTPUT->header();
echo $OUTPUT->heading($pagedesc);

$infoform->display();

echo $OUTPUT->footer();
