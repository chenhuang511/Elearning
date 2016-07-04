<?php

require(dirname(__FILE__) . '/../../../config.php');
require_once(dirname(__FILE__) . '/locallib.php');


global $PAGE;

$id = optional_param('id', 0, PARAM_INT);        // Course Module ID

if ($id) {
    if (!$cm = get_remote_course_module($id)) {
        throw new coding_exception('Not Found!');
    }
    $course = get_remote_course_by_id($cm->course);
    $book = get_remote_book_content($cm->instance);
} else {
    echo "Not available!!";
}

// read chapters
$chapters = get_remote_book_chapters_content($book->id);

// Unset all page parameters.
unset($id);
unset($bid);
unset($chapterid);

// Read standard strings.
$strbooks = get_string('modulenameplural', 'mod_book');
$strbook = get_string('modulename', 'mod_book');

// =====================================================
// Book display HTML code
// =====================================================

echo $OUTPUT->heading(format_string($book->name));

if (!$chapters) {
    echo "<h1> Not Content</h1>";
}
// The chapter itself.
$hidden = $chapter->hidden ? ' dimmed_text' : null;

echo $OUTPUT->box_start('generalbox book_content' . $hidden);

foreach ($chapters as $chapter) {
    if (!$chapter->subchapter) {
        $currtitle = mod_book_get_chapter_title($chapter->id, $chapters, $book);
        echo $OUTPUT->heading($currtitle, 3);
    } else {
        $currtitle = mod_book_get_chapter_title($chapters[$chapter->id]->parent, $chapters, $book);
        $currsubtitle = mod_book_get_chapter_title($chapter->id, $chapters, $book);
        echo $OUTPUT->heading($currtitle, 3);
        echo $OUTPUT->heading($currsubtitle, 4);
    }

    $chaptertext = file_rewrite_pluginfile_urls($chapter->content, 'pluginfile.php', null, 'mod_book', 'chapter', $chapter->id);
    echo format_text($chaptertext, $chapter->contentformat, array('noclean' => true, 'overflowdiv' => true));
}
echo $OUTPUT->box_end();
