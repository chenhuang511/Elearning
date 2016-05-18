<?php

require_once('../../config.php');
require_once('remotelib.php');

$courseid = optional_param('remoteid', 0, PARAM_INT);
$sectionid   = optional_param('sectionid', 0, PARAM_INT);
$section     = optional_param('section', 0, PARAM_INT);

$options = [];

$options['function_name'] = 'core_course_get_contents';

$course = get_remote_course_content($courseid, $options);

$coursemodules = null;

$PAGE->set_heading($course[0]?$course[0]->name:"nccsoft vietnam");
echo $OUTPUT->header();

echo "<div class='course-detail'>";
if(isset($course[0]->name) && !empty($course[0]->name)) {
	echo "<h2 class='course-name'>" . $course[0]->name . "</h2>";
}
if(isset($course[0]->summary) && !empty($course[0]->summary)) {
	echo "<p class='course-sumary'>".$course[0]->summary ."</p>";
}

foreach($course as $key => $section) {
	if ($key == 0) continue;

	echo "<div class='course-section'>";
	if(isset($section->name) && !empty($section->name)) {
		echo "<h3 class='course-section-name'>" . $section->name . "</h3>";
	}
	if(isset($section->summary) && !empty($section->summary)) {
		echo "<p class='course-section-sumary'>" . $section->summary . "</p>";
	}

//	echo "course";
//	echo "<pre>";
//	print_r($section);
//	echo "</pre>";

	$coursemodules = $section->modules;
	$labelcontent = null;

	if(isset($coursemodules[0]->instance) && !is_null($coursemodules[0]->instance)) {
		$labelcontent = get_remote_label_content($coursemodules[0]->instance, ['function_name' => 'local_mod_get_label_by_id']);
	}

	if(!is_null($labelcontent)) {

		echo "<div class='course-label-box'>";
		if(isset($labelcontent->intro) && !empty($labelcontent->intro)) {
			echo "<div class='label-intro'>" . $labelcontent->intro . "</div>";
		}
		echo "</div>";
	}
	echo "</div>";
}

echo "</div>";

echo $OUTPUT->footer();