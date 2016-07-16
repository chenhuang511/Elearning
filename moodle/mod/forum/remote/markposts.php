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
 * Set tracking option for the forum.
 *
 * @package   mod_forum
 * @copyright 2005 mchurch
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
require_once(dirname(dirname(__DIR__)) . '/config.php');
require_once($CFG->dirroot.'/mod/forum/lib.php');
require_once($CFG->dirroot . '/mod/forum/remote/locallib.php');
require_once($CFG->dirroot . '/course/remote/locallib.php');

$f          = required_param('f',PARAM_INT); // The forum to mark
$mark       = required_param('mark',PARAM_ALPHA); // Read or unread?
$d          = optional_param('d',0,PARAM_INT); // Discussion to mark.
$returnpage = optional_param('returnpage', 'index.php', PARAM_FILE);    // Page to return to.

$url = new moodle_url('/mod/forum/remote/markposts.php', array('f'=>$f, 'mark'=>$mark));
if ($d !== 0) {
    $url->param('d', $d);
}
if ($returnpage !== 'index.php') {
    $url->param('returnpage', $returnpage);
}
$PAGE->set_url($url);

$params = array();
$params['parameters[0][name]'] = "id";
$params['parameters[0][value]'] = $f;
if (! $forum = get_remote_forum_by($params)) {
    print_error('invalidforumid', 'forum');
}

if (! $course = get_local_course_record($forum->course)) {
    print_error('invalidcourseid');
}

if (!$cm = get_remote_course_module_by_instance("forum", $forum->id)->cm) {
    print_error('invalidcoursemodule');
}

$user = $USER;

require_login($course, false, $cm);
require_sesskey();

if ($returnpage == 'index.php') {
    $returnto = new moodle_url("/mod/forum/remote/$returnpage", array('id' => $course->id));
} else {
    $returnto = new moodle_url("/mod/forum/remote/$returnpage", array('f' => $forum->id));
}

if (isguestuser()) {   // Guests can't change forum
    $PAGE->set_title($course->shortname);
    $PAGE->set_heading($course->fullname);
    echo $OUTPUT->header();
    echo $OUTPUT->confirm(get_string('noguesttracking', 'forum').'<br /><br />'.get_string('liketologin'), get_login_url(), $returnto);
    echo $OUTPUT->footer();
    exit;
}

$info = new stdClass();
$info->name  = fullname($user);
$info->forum = format_string($forum->name);

if ($mark == 'read') {
    if (!empty($d)) {
        $params = array();
        $params['parameters[0][name]'] = "id";
        $params['parameters[0][value]'] = $d;
        $params['parameters[1][name]'] = "forum";
        $params['parameters[1][value]'] = $forum->id;
        if (! $discussion = get_remote_forum_discussions_by($params)) {
            print_error('invaliddiscussionid', 'forum');
        }

        forum_tp_mark_discussion_read($user, $d);
    } else {
        // Mark all messages read in current group
        $currentgroup = groups_get_activity_group($cm);
        if(!$currentgroup) {
            // mark_forum_read requires ===false, while get_activity_group
            // may return 0
            $currentgroup=false;
        }
        forum_tp_mark_forum_read($user, $forum->id, $currentgroup);
    }

/// FUTURE - Add ability to mark them as unread.
//    } else { // subscribe
//        if (forum_tp_start_tracking($forum->id, $user->id)) {
//            redirect($returnto, get_string("nowtracking", "forum", $info), 1);
//        } else {
//            print_error("Could not start tracking that forum", get_local_referer());
//        }
}

redirect($returnto);