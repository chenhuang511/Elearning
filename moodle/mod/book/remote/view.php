<?php

require(dirname(__FILE__).'/../../../config.php');
//require_once(dirname(__FILE__).'/lib.php');
require_once(dirname(__FILE__).'/locallib.php');
//require_once($CFG->libdir.'/completionlib.php');

global $PAGE;

$id        = optional_param('modid', 0, PARAM_INT);        // Course Module ID
$bid       = optional_param('b', 0, PARAM_INT);         // Book ID
$chapterid = optional_param('chapterid', 0, PARAM_INT); // Chapter ID
//$edit      = optional_param('edit', -1, PARAM_BOOL);    // Edit mode

if($id){
    if(!$cm = get_remote_course_module($id)) {
        throw new coding_exception('Not Found!');
    }
    $course = get_remote_course_by_id($cm->course);
    $book = get_remote_book_content($cm->instance);
}
else{
    echo "Not available!!";
}

// read chapters
$chapters = remote_book_preload_chapters($book);

if (!$chapters) {
    redirect('edit.php?modid='.$cm->id); // No chapters - add new one.
}
// Check chapterid and read chapter data
if ($chapterid == '0') { // Go to first chapter if no given.
    // Trigger course module viewed event.
    
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

if ((!$chapter = get_remote_book_chapters_content_by_chapterid($book->id, $chapterid)) or ($chapter->hidden)) {
    print_error('errorchapter', 'mod_book', $courseurl);
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

// prepare chapter navigation icons
$previd = null;
$prevtitle = null;
$nextid = null;
$nexttitle = null;
$last = null;
foreach ($chapters as $ch) {
    if ($ch->hidden) {
        continue;
    }
    if ($last == $chapter->id) {
        $nextid = $ch->id;
        $nexttitle = mod_book_get_chapter_title($ch->id, $chapters, $book);
        break;
    }
    if ($ch->id != $chapter->id) {
        $previd = $ch->id;
        $prevtitle = mod_book_get_chapter_title($ch->id, $chapters, $book);
    }
    $last = $ch->id;
}

$islastchapter = false;
if ($book->navstyle) {
    $navprevicon = right_to_left() ? 'nav_next' : 'nav_prev';
    $navnexticon = right_to_left() ? 'nav_prev' : 'nav_next';
    $navprevdisicon = right_to_left() ? 'nav_next_dis' : 'nav_prev_dis';

    $chnavigation = '';
    if ($previd) {
        $navprev = get_string('navprev', 'book');
        if ($book->navstyle == 1) {
            $chnavigation .= '<a title="' . $navprev . '" class="bookprev" href="view.php?courseid=' .$cm->course. '&amp;modid=' .
                $cm->id . '&amp;chapterid=' . $previd .  '">' .
                '<img src="' . $OUTPUT->pix_url($navprevicon, 'mod_book') . '" class="icon" alt="' . $navprev . '"/></a>';
        } else {
            $chnavigation .= '<a title="' . $navprev . '" class="bookprev" href="view.php?courseid=' .$cm->course. '&amp;modid=' .
                $cm->id . '&amp;chapterid=' . $previd . '">' .
                '<span class="chaptername"><span class="arrow">' . $OUTPUT->larrow() . '&nbsp;</span></span>' .
                $navprev . ':&nbsp;<span class="chaptername">' . $prevtitle . '</span></a>';
        }
    } else {
        if ($book->navstyle == 1) {
            $chnavigation .= '<img src="' . $OUTPUT->pix_url($navprevdisicon, 'mod_book') . '" class="icon" alt="" />';
        }
    }
    if ($nextid) {
        $navnext = get_string('navnext', 'book');
        if ($book->navstyle == 1) {
            $chnavigation .= '<a title="' . $navnext . '" class="booknext" href="view.php?courseid=' .$cm->course. '&amp;modid=' .
                $cm->id . '&amp;chapterid='.$nextid.'">' .
                '<img src="' . $OUTPUT->pix_url($navnexticon, 'mod_book').'" class="icon" alt="' . $navnext . '" /></a>';
        } else {
            $chnavigation .= ' <a title="' . $navnext . '" class="booknext" href="view.php?courseid=' .$cm->course. '&amp;modid=' .
                $cm->id . '&amp;chapterid='.$nextid.'">' .
                $navnext . ':<span class="chaptername">&nbsp;' . $nexttitle.
                '<span class="arrow">&nbsp;' . $OUTPUT->rarrow() . '</span></span></a>';
        }
    } else {
        $navexit = get_string('navexit', 'book');
        $sec = get_remote_course_sections_by_id($cm->section);

        if ($book->navstyle == 1) {
            $chnavigation .= '<a title="' . $navexit . '" class="bookexit"  href="'.$courseurl.'#course_section_'.$sec->section.'">' .
                '<img src="' . $OUTPUT->pix_url('nav_exit', 'mod_book') . '" class="icon" alt="' . $navexit . '" /></a>';
        } else {
            $chnavigation .= ' <a title="' . $navexit . '" class="bookexit"  href="'.$courseurl.'#course_section_'.$sec->section.'">' .
                '<span class="chaptername">' . $navexit . '&nbsp;' . $OUTPUT->uarrow() . '</span></a>';
        }

        $islastchapter = true;
    }
}

// =====================================================
// Book display HTML code
// =====================================================

echo $OUTPUT->header();
echo $OUTPUT->heading(format_string($book->name));

$navclasses = book_get_nav_classes();

if ($book->navstyle) {
    // Upper navigation.
    echo '<div class="navtop clearfix ' . $navclasses[$book->navstyle] . '">' . $chnavigation . '</div>';
}

// The chapter itself.
$hidden = $chapter->hidden ? ' dimmed_text' : null;
echo $OUTPUT->box_start('generalbox book_content' . $hidden);

if (!$book->customtitles) {
    if (!$chapter->subchapter) {
        $currtitle = mod_book_get_chapter_title($chapter->id, $chapters, $book);
        echo $OUTPUT->heading($currtitle, 3);
    } else {
        $currtitle = mod_book_get_chapter_title($chapters[$chapter->id]->parent, $chapters, $book);
        $currsubtitle = mod_book_get_chapter_title($chapter->id, $chapters, $book);
        echo $OUTPUT->heading($currtitle, 3);
        echo $OUTPUT->heading($currsubtitle, 4);
    }
}
$chaptertext = file_rewrite_pluginfile_urls($chapter->content, 'pluginfile.php', null, 'mod_book', 'chapter', $chapter->id);
echo format_text($chaptertext, $chapter->contentformat, array('noclean'=>true, 'overflowdiv'=>true));

echo $OUTPUT->box_end();

if ($book->navstyle) {
    // Lower navigation.
    echo '<div class="navbottom clearfix ' . $navclasses[$book->navstyle] . '">' . $chnavigation . '</div>';
}

echo $OUTPUT->footer();
