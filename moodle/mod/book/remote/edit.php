<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Edit book chapter
 *
 * @package    mod_book
 * @copyright  2004-2011 Petr Skoda {@link http://skodak.org}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require(dirname(__FILE__).'/../../../config.php');
require_once(dirname(__FILE__).'/locallib.php');
require_once(dirname(__FILE__).'/../edit_form.php');

$cmid       = required_param('modid', PARAM_INT);  // Book Course Module ID
$chapterid  = optional_param('id', 0, PARAM_INT); // Chapter ID
$pagenum    = optional_param('pagenum', 0, PARAM_INT);
$subchapter = optional_param('subchapter', 0, PARAM_BOOL);

$cm = get_remote_course_module($id);
$course = get_remote_course_by_id($cm->course);
$book = get_remote_book_content($cm->instance);

if ($chapterid) {
    $chapter = get_remote_book_chapters_content_by_chapterid($book->id, $chapterid);
} else {
    $chapter = new stdClass();
    $chapter->id         = null;
    $chapter->subchapter = $subchapter;
    $chapter->pagenum    = $pagenum + 1;
}
$chapter->cmid = $cm->id;

$options = array('noclean'=>true, 'subdirs'=>true, 'maxfiles'=>-1, 'maxbytes'=>0, 'context'=>$context);
$chapter = file_prepare_standard_editor($chapter, 'content', $options, $context, 'mod_book', 'chapter', $chapter->id);

$mform = new book_chapter_edit_form(null, array('chapter'=>$chapter, 'options'=>$options));

// Otherwise fill and print the form.
$PAGE->set_title($book->name);
$PAGE->set_heading($course->fullname);

echo $OUTPUT->header();
echo $OUTPUT->heading($book->name);

$mform->display();

echo $OUTPUT->footer();
