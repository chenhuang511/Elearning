<?php

require(dirname(__FILE__).'/../../../config.php');
//require_once(dirname(__FILE__).'/lib.php');
require_once(dirname(__FILE__).'/locallib.php');
//require_once($CFG->libdir.'/completionlib.php');

global $PAGE;
echo "<pre>";
$id        = optional_param('modid', 0, PARAM_INT);        // Course Module ID
$bid       = optional_param('b', 0, PARAM_INT);         // Book ID
$chapterid = optional_param('chapterid', 0, PARAM_INT); // Chapter ID
$edit      = optional_param('edit', -1, PARAM_BOOL);    // Edit mode

if($id){
    if(!$cm = get_remote_course_module($id)) {
        throw new coding_exception('Not Found!');
    }
    $course = get_remote_course_by_id($cm->course);
    $book = get_remote_book_content($cm->instance);
}
else{
    echo "Not available!!";die;
}

// read chapters
$chapters = remote_book_preload_chapters($book);

// Check chapterid and read chapter data
if ($chapterid == '0') { // Go to first chapter if no given.
    // Trigger course module viewed event.
//    book_view($book, null, false, $course, $cm, $context);

    foreach ($chapters as $ch) {
        if (!$ch->hidden) {
            $chapterid = $ch->id;
            break;
        }
    }
}

$courseurl = new moodle_url('/course/remote/view.php', array('remoteid' => $course->id));

// No content in the book.
if (!$chapterid) {
    $PAGE->set_url('/mod/book/remote/view.php', array('modid' => $id));
    notice(get_string('nocontent', 'mod_book'), $courseurl->out(false));
}

$PAGE->set_url('/mod/book/remote/view.php', array('modid'=>$id, 'chapterid'=>$chapterid));

// Unset all page parameters.
unset($id);
unset($bid);
unset($chapterid);

// Read standard strings.
$strbooks = get_string('modulenameplural', 'mod_book');
$strbook  = get_string('modulename', 'mod_book');
$strtoc   = get_string('toc', 'mod_book');

// prepare header
$pagetitle = $book->name . ": " . $chapter->title;
$PAGE->set_title($pagetitle);
$PAGE->set_heading($course->fullname);



