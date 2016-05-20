<?php
/**
 * Created by PhpStorm.
 * User: Minh Nguyen
 * Date: 5/20/2016
 * Time: 2:27 PM
 */
//defined('MOODLE_INTERNAL') || die;

require_once('../config.php');
require_once('lib.php');
require_once('edit_hub_form.php');

$PAGE->set_pagelayout('admin');

require_login();

// Instantiate simplehtml_form
$mform = new course_edit_hub_form();

//Form processing and displaying is done here
if ($mform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
} else if ($fromform = $mform->get_data()) {
    //In this case you process validated data. $mform->get_data() returns data posted in form.
    
}

echo $OUTPUT->header();
echo $OUTPUT->heading($pagedesc);

$mform->display();

echo $OUTPUT->footer();





//function create_course ($course, $token){
//    $courses = array($course);
//    $params = array('course' => $courses);
//
//    $response = call_moodle('core_course_create_courses', $params, $token);
//
//    print "Response from moodle_course_create_courses: \n";
//    print_r( $response );
//
//    if ( xml_is_exception( $response ) )
//        throw new Exception( $response );
//    else {
//        $course_id = success_xml_to_id( $response );
//        return $course_id;
//    }
//
//}
//
//function call_moodle( $function_name, $params, $token )
//{
//    $domain = 'http://10.0.0.29';
//    $token = '2077e39e91a4cfb85c565c550cae7ca8';
//    $function_name = 'core_course_get_courses';
//    $serverurl = $domain . '/webservice/rest/server.php'. '?wstoken=' . $token . '&wsfunction='.$function_name;
//
//    require_once( './curl.php' );
//    $curl = new curl;
//    $restformat = '&moodlewsrestformat=json';
//    $response = $curl->post( $serverurl . $restformat, $params );
//    return $response;
//}
//
//function xml_is_exception( $xml_string )
//{
//    $xml_tree = new SimpleXMLElement( $xml_string );
//
//    $is_exception = $xml_tree->getName() == 'EXCEPTION';
//    return $is_exception;
//}
//
