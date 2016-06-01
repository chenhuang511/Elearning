<?php

require(dirname(__FILE__).'/../../../config.php');
//require_once(dirname(__FILE__).'/lib.php');
require_once(dirname(__FILE__).'/locallib.php');
//require_once($CFG->libdir.'/completionlib.php');
echo "<pre>";
$id        = optional_param('modid', 0, PARAM_INT);        // Course Module ID
$bid       = optional_param('b', 0, PARAM_INT);         // Book ID
$chapterid = optional_param('chapterid', 0, PARAM_INT); // Chapter ID
$edit      = optional_param('edit', -1, PARAM_BOOL);    // Edit mode

if($id){
    if(!$cm = get_remote_course_module($id)) {
        throw new coding_exception('Not Found!');
    }
    $course = get_remote_course($cm->course);
    $book = get_remote_book_content($cm->instance);
}
else{
    echo "Not available!!";die;
}

// read chapters
$chapters = remote_book_preload_chapters($book);
var_dump($chapters);die;

// Check chapterid and read chapter data
if ($chapterid == '0') { // Go to first chapter if no given.
    // Trigger course module viewed event.
//    book_view($book, null, false, $course, $cm, $context);

    foreach ($chapters as $ch) {
        if ($edit) {
            $chapterid = $ch->id;
            break;
        }
        if (!$ch->hidden) {
            $chapterid = $ch->id;
            break;
        }
    }
}

$courseurl = new moodle_url('/course/view.php', array('id' => $course->id));

// No content in the book.
if (!$chapterid) {
    $PAGE->set_url('/mod/book/view.php', array('id' => $id));
    notice(get_string('nocontent', 'mod_book'), $courseurl->out(false));
}
// Chapter doesnt exist or it is hidden for students
if ((!$chapter = $DB->get_record('book_chapters', array('id' => $chapterid, 'bookid' => $book->id))) or ($chapter->hidden and !$viewhidden)) {
    print_error('errorchapter', 'mod_book', $courseurl);
}


