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
 * Book module local lib functions
 *
 * @package    mod_book
 * @copyright  2010-2011 Petr Skoda {@link http://skodak.org}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;
require_once($CFG->dirroot . '/lib/remote/lib.php');
require_once($CFG->dirroot . '/lib/additionallib.php');

require_once(dirname(__FILE__) . '/../lib.php');
require_once($CFG->libdir . '/filelib.php');

/**
 * The following defines are used to define how the chapters and subchapters of a book should be displayed in that table of contents.
 * BOOK_NUM_NONE        No special styling will applied and the editor will be able to do what ever thay want in the title
 * BOOK_NUM_NUMBERS     Chapters and subchapters are numbered (1, 1.1, 1.2, 2, ...)
 * BOOK_NUM_BULLETS     Subchapters are indented and displayed with bullets
 * BOOK_NUM_INDENTED    Subchapters are indented
 */
define('BOOK_NUM_NONE', '0');
define('BOOK_NUM_NUMBERS', '1');
define('BOOK_NUM_BULLETS', '2');
define('BOOK_NUM_INDENTED', '3');

/**
 * The following defines are used to define the navigation style used within a book.
 * BOOK_LINK_TOCONLY    Only the table of contents is shown, in a side region.
 * BOOK_LINK_IMAGE      Arrows link to previous/next/exit pages, in addition to the TOC.
 * BOOK_LINK_TEXT       Page names and arrows link to previous/next/exit pages, in addition to the TOC.
 */
define('BOOK_LINK_TOCONLY', '0');
define('BOOK_LINK_IMAGE', '1');
define('BOOK_LINK_TEXT', '2');

/**
 * Preload book chapters and fix toc structure if necessary.
 *
 * Returns array of chapters with standard 'pagenum', 'id, pagenum, subchapter, title, hidden'
 * and extra 'parent, number, subchapters, prev, next'.
 * Please note the content/text of chapters is not included.
 *
 * @param  stdClass $book
 * @return array of id=>chapter
 */
function remote_book_preload_chapters($book)
{
    $chapters = get_remote_book_chapters_content($book->id);
    if (!$chapters) {
        return array();
    }

    $prev = null;
    $prevsub = null;

    $first = true;
    $hidesub = true;
    $parent = null;
    $pagenum = 0; // chapter sort
    $i = 0;       // main chapter num
    $j = 0;       // subchapter num
    foreach ($chapters as $id => $ch) {
        $oldch = clone($ch);
        $pagenum++;
        $ch->pagenum = $pagenum;
        if ($first) {
            // book can not start with a subchapter
            $ch->subchapter = 0;
            $first = false;
        }
        if (!$ch->subchapter) {
            if ($ch->hidden) {
                if ($book->numbering == BOOK_NUM_NUMBERS) {
                    $ch->number = 'x';
                } else {
                    $ch->number = null;
                }
            } else {
                $i++;
                $ch->number = $i;
            }
            $j = 0;
            $prevsub = null;
            $hidesub = $ch->hidden;
            $parent = $ch->id;
            $ch->parent = null;
            $ch->subchapters = array();
        } else {
            $ch->parent = $parent;
            $ch->subchapters = null;
            $chapters[$parent]->subchapters[$ch->id] = $ch->id;
            if ($hidesub) {
                // all subchapters in hidden chapter must be hidden too
                $ch->hidden = 1;
            }
            if ($ch->hidden) {
                if ($book->numbering == BOOK_NUM_NUMBERS) {
                    $ch->number = 'x';
                } else {
                    $ch->number = null;
                }
            } else {
                $j++;
                $ch->number = $j;
            }
        }

        $chapters[$id] = $ch;
    }
    
    if ($oldch->subchapter != $ch->subchapter or $oldch->pagenum != $ch->pagenum or $oldch->hidden != $ch->hidden) {
        // update only if something changed
        update_remote_book_chapters($ch);
    }
    
    return $chapters;
}

function mod_book_get_chapter_title($chid, $chapters, $book)
{
    $ch = $chapters[$chid];
    $title = trim(format_string($ch->title, true));
    $numbers = array();
    if ($book->numbering == BOOK_NUM_NUMBERS) {
        if ($ch->parent and $chapters[$ch->parent]->number) {
            $numbers[] = $chapters[$ch->parent]->number;
        }
        if ($ch->number) {
            $numbers[] = $ch->number;
        }
    }

    if ($numbers) {
        $title = implode('.', $numbers) . ' ' . $title;
    }

    return $title;
}

function get_remote_book_content($bookid, $options = [])
{
    return moodle_webservice_client(array_merge($options, array('domain' => HUB_URL,
        'token' => HOST_TOKEN,
        'function_name' => 'local_mod_get_book_by_id',
        'params' => array('bookid' => $bookid),
    )));
}

function get_remote_book_chapters_content($bookid, $options = [])
{
    $bookchapters = moodle_webservice_client(array_merge($options, array('domain' => HUB_URL,
        'token' => HOST_TOKEN,
        'function_name' => 'local_mod_get_book_chapters_by_id',
        'params' => array('bookid' => $bookid),
    )));
    $result = array();
    foreach ($bookchapters as $ch) {
        $result[$ch->id] = $ch;
    }

    return $result;
}

function get_remote_book_chapters_content_by_chapterid($bookid, $chapterid, $options = [])
{
    return moodle_webservice_client(array_merge($options, array('domain' => HUB_URL,
        'token' => HOST_TOKEN,
        'function_name' => 'local_mod_get_book_chapters_by_bookid_chapterid',
        'params' => array(
            'bookid' => $bookid,
            'chapterid' => $chapterid
        ),
    )));
}

function get_remote_course_by_id($courseid, $options = array())
{
    $courses = moodle_webservice_client(array_merge($options, array('domain' => HUB_URL,
        'token' => HOST_TOKEN_M,
        'function_name' => 'core_course_get_courses',
        'params' => array('options[ids][0]' => $courseid)
    )));
    return reset($courses);
}

function get_remote_course_sections_by_id($sectionid, $options = array())
{
    return moodle_webservice_client(array_merge($options, array('domain' => HUB_URL,
        'token' => HOST_TOKEN,
        'function_name' => 'local_get_course_sections_by_id',
        'params' => array(
            'sectionid' => $sectionid,
        ),
    )));
}

function create_remote_book_chapters($data, $options = array())
{
    return moodle_webservice_client(array_merge($options, array('domain' => HUB_URL,
        'token' => HOST_TOKEN,
        'function_name' => 'local_mod_create_book_chapters',
        'params' => array(
            'data[title]' => $data->title,
            'data[subchapter]' => $data->subchapter,
            'data[id]' => $data->id,
            'data[cmid]' => $data->cmid,
            'data[pagenum]' => $data->pagenum,
            'data[bookid]' => $data->bookid,
            'data[hidden]' => $data->hidden,
            'data[timecreated]' => $data->timecreated,
            'data[timemodified]' => $data->timemodified,
            'data[importsrc]' => $data->importsrc,
            'data[content]' => $data->content,
            'data[contentformat]' => $data->contentformat,
        ),
    )));
}

function update_remote_book_chapters($data, $options = array())
{
    return moodle_webservice_client(array_merge($options, array('domain' => HUB_URL,
        'token' => HOST_TOKEN,
        'function_name' => 'local_mod_update_book_chapters',
        'params' => array(
            'data[title]' => $data->title,
            'data[subchapter]' => $data->subchapter,
            'data[id]' => $data->id,
            'data[cmid]' => $data->cmid,
            'data[pagenum]' => $data->pagenum,
            'data[bookid]' => $data->bookid,
            'data[hidden]' => $data->hidden,
            'data[timecreated]' => $data->timecreated,
            'data[timemodified]' => $data->timemodified,
            'data[importsrc]' => $data->importsrc,
            'data[content]' => $data->content,
            'data[contentformat]' => $data->contentformat,
            'data[contenttrust]' => $data->contenttrust,
        ),
    )));
}
