<?php
/**
 * Created by PhpStorm.
 * User: vanha
 * Date: 27/05/2016
 * Time: 2:52 CH
 */

require_once(dirname(__FILE__) . '/../../../config.php');
require_once($CFG->dirroot . '/course/remote/remotelib.php');

require_once($CFG->libdir.'/gradelib.php');
require_once($CFG->dirroot.'/mod/quiz/locallib.php');
require_once($CFG->libdir . '/completionlib.php');
require_once($CFG->dirroot . '/course/format/lib.php');

$id = optional_param('id', 0, PARAM_INT); // Course Module ID, or ...
$q = optional_param('q',  0, PARAM_INT);  // Quiz ID.
$cm = get_remote_course_module($id); //đang không giống với dl trên hub show ra
//var_dump($cm);die;
//$course = $DB->get_record('course', array('id' => $cm->course));
//var_dump($course);die;

if ($id) {
    if (!$cm = get_remote_course_module($id)) {
        print_error('invalidcoursemodule');
    }
    if (!$course = $DB->get_record('course', array('id' => $cm->course))) {
        print_error('coursemisconf');
    }
} else {
    if (!$quiz = $DB->get_record('quiz', array('id' => $q))) {
        print_error('invalidquizid', 'quiz');
    }
    if (!$course = $DB->get_record('course', array('id' => $quiz->course))) {
        print_error('invalidcourseid');
    }
    if (!$cm = get_coursemodule_from_instance("quiz", $quiz->id, $course->id)) {//bỏ phần này, hiện tại nếu không truyền cmid-> báo lỗi đã.
        print_error('invalidcoursemodule');
    }
}