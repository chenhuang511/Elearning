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
require_once('/remote/remotelib.php');

$returnurl = optional_param('returnurl', '', PARAM_LOCALURL);
$returnurl = new moodle_url($CFG->wwwroot);

$PAGE->set_pagelayout('admin');

require_login();

// Instantiate simplehtml_form
$mform = new course_edit_hub_form();

//Form processing and displaying is done here
if ($mform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
    redirect($returnurl);
} else if ($data = $mform->get_data()) {
    //In this case you process validated data. $mform->get_data() returns data posted in form.
    !isset($data->completionnotify) ? $data->completionnotify = 0 : '';
    !isset($data->forcetheme) ? $data->forcetheme = '' : '';
    if ($data->format == 'singleactivity') {
        $courseformatoptions_name = "activitytype";
        $courseformatoptions_value = $data->activitytype;
    }

    if ($data->format == 'social') {
        $courseformatoptions_name = "numdiscussions";
        $courseformatoptions_value = $data->numdiscussions;
    }

    if ($data->format == 'topics') {
        $courseformatoptions_name = "numsections";
        $courseformatoptions_value = $data->numsections;
    }

    if ($data->format == 'weeks') {
        $courseformatoptions_name = "numsections";
        $courseformatoptions_value = $data->numsections;
    }


    $response = moodle_webservice_client(array(
        'domain' => HUB_URL,
        'token' => HOST_TOKEN_M,
        'function_name' => 'core_course_create_courses',
        'params' => array(
            'courses[0][fullname]' => $data->fullname,
            'courses[0][shortname]' => $data->shortname,
            'courses[0][categoryid]' => $data->category,
            'courses[0][idnumber]' => $data->idnumber,
            'courses[0][summary]' => $data->summary_editor['text'],
            'courses[0][summaryformat]' => (int)$data->summary_editor['format'],
            'courses[0][format]' => $data->format,
            'courses[0][showgrades]' => (int)$data->showgrades,
            'courses[0][newsitems]' => (int)$data->newsitems,
            'courses[0][startdate]' => $data->startdate,
            'courses[0][numsections]' => (int)$data->numsections,
            'courses[0][maxbytes]' => (int)$data->maxbytes,
            'courses[0][showreports]' => (int)$data->showreports,
            'courses[0][visible]' => (int)$data->visible,
            'courses[0][hiddensections]' => (int)$data->hiddensections,
            'courses[0][groupmode]' => (int)$data->groupmode,
            'courses[0][groupmodeforce]' => (int)$data->groupmodeforce,
            'courses[0][defaultgroupingid]' => (int)$data->defaultgroupingid,
            'courses[0][enablecompletion]' => (int)$data->enablecompletion,
            'courses[0][completionnotify]' => $data->completionnotify,
            'courses[0][forcetheme]' => $data->forcetheme,
            'courses[0][courseformatoptions][0][name]' => $courseformatoptions_name,
            'courses[0][courseformatoptions][0][value]' => $courseformatoptions_value,
        )
    ));
}

if(isset($response->exception)){
    echo $response->message; die;
}

if (isset($data->saveandreturn)) {
    // Redirect user to newly created/updated course.
    redirect($returnurl);
}

echo $OUTPUT->header();

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
