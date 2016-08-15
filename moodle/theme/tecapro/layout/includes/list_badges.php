<?php
require_once($CFG->libdir . '/badgeslib.php');
$type       = optional_param('type', 2, PARAM_INT);
$courseid   = optional_param('id', 0, PARAM_INT);
$sortby     = optional_param('sort', 'name', PARAM_ALPHA);
$sorthow    = optional_param('dir', 'DESC', PARAM_ALPHA);
$page       = optional_param('page', 0, PARAM_INT);

if (empty($CFG->enablebadges)) {
    print_error('badgesdisabled', 'badges');
}

if (empty($CFG->badges_allowcoursebadges) && $courseid != 0) {
    print_error('coursebadgesdisabled', 'badges');
}

if (!in_array($sortby, array('name', 'dateissued'))) {
    $sortby = 'name';
}

if ($sorthow != 'ASC' && $sorthow != 'DESC') {
    $sorthow = 'ASC';
}

if ($page < 0) {
    $page = 0;
}

    $coursename = format_string($COURSE->fullname, true, array('context' => $context));
    $title = $coursename . ': ' . get_string('coursebadges', 'badges');
    $PAGE->set_context($context);
    $PAGE->set_pagelayout('incourse');
    $PAGE->set_heading($coursename);

require_capability('moodle/badges:viewbadges', $PAGE->context);

$PAGE->set_title($title);
$output = $PAGE->get_renderer('core', 'badges');

echo $OUTPUT->heading($title);

$totalcount = count(badges_get_badges($type, $courseid, '', '', '', '', $USER->id));
$records = badges_get_badges($type, $courseid, $sortby, $sorthow, $page, BADGE_PERPAGE, $USER->id);

if ($totalcount) {
    echo $output->heading(get_string('badgestoearn', 'badges', $totalcount), 4);

    if ($COURSE && $COURSE->startdate > time()) {
        echo $OUTPUT->box(get_string('error:notifycoursedate', 'badges'), 'generalbox notifyproblem');
    }

    $badges             = new badge_collection($records);
    $badges->sort       = $sortby;
    $badges->dir        = $sorthow;
    $badges->page       = $page;
    $badges->perpage    = BADGE_PERPAGE;
    $badges->totalcount = $totalcount;

    echo $output->render($badges);
} else {
    echo $output->notification(get_string('nobadges', 'badges'));
}
