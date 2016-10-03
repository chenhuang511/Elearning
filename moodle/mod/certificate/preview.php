<?php

require_once("../../config.php");
require_once("$CFG->dirroot/mod/certificate/locallib.php");
require_once("$CFG->dirroot/mod/certificate/remote/locallib.php");
require_once("$CFG->dirroot/mod/certificate/deprecatedlib.php");
require_once("$CFG->libdir/pdflib.php");

$id = required_param('id', PARAM_INT);    // Course Module ID
$userid = required_param('uid', PARAM_INT);

if (!$cm = get_coursemodule_from_id('certificate', $id)) {
    print_error('Course Module ID was incorrect');
}
if (!$course = $DB->get_record('course', array('id'=> $cm->course))) {
    print_error('course is misconfigured');
}
if (!$certificate = get_remote_certificate_by_id($cm->instance)) {
    print_error('course module is incorrect');
}

$context = context_module::instance($cm->id);

// Initialize $PAGE, compute blocks
$PAGE->set_url('/mod/certificate/view.php', array('id' => $cm->id));
$PAGE->set_context($context);
$PAGE->set_cm($cm);
$PAGE->set_title(format_string($certificate->name));
$PAGE->set_heading(format_string($course->fullname));

// Check if the user can view the certificate
if ($certificate->requiredtime && !has_capability('mod/certificate:manage', $context)) {
    if (certificate_get_course_time($course->id) < ($certificate->requiredtime * 60)) {
        $a = new stdClass;
        $a->requiredtime = $certificate->requiredtime;
        notice(get_string('requiredtimenotmet', 'certificate', $a), "$CFG->wwwroot/course/view.php?id=$course->id");
        die;
    }
}

// Create new certificate record, or return existing record
//$userid = get_remote_mapping_localuserid($userid);
$showuser = $DB->get_record('user', array('id' => $userid), '*', MUST_EXIST);

$certrecord = certificate_get_issue($course, $showuser, $certificate, $cm);

make_cache_directory('tcpdf');
// Load the specific certificate type.

$certificate->certificatetype = "A4_embedded"; // Fix type hardcode
require_once("$CFG->dirroot/mod/certificate/type/$certificate->certificatetype/certificate.php");
// Output to pdf
// No debugging here, sorry.
$CFG->debugdisplay = 0;
@ini_set('display_errors', '0');
@ini_set('log_errors', '1');

$filename = certificate_get_certificate_filename($certificate, $cm, $course) . '.pdf';

// PDF contents are now in $file_contents as a string.
$filecontents = $pdf->Output('', 'S');


// Open in browser.
send_file($filecontents, $filename, 0, 0, true, false, 'application/pdf');
