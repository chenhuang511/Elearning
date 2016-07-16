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

$id         = required_param('id',PARAM_INT);                           // The forum to subscribe or unsubscribe to
$returnpage = optional_param('returnpage', 'index.php', PARAM_FILE);    // Page to return to.

require_sesskey();

$params = array();
$params['parameters[0][name]'] = "id";
$params['parameters[0][value]'] = $id;
if (! $forum = get_remote_forum_by($params)) {
    print_error('invalidforumid', 'forum');
}

if (! $course = get_local_course_record($forum->course)) {
    print_error('invalidcoursemodule');
}

if (! $cm = get_remote_course_module_by_instance("forum", $forum->id)->cm) {
    print_error('invalidcoursemodule');
}
require_login($course, false, $cm);
$returnpageurl = new moodle_url('/mod/forum/remote/' . $returnpage, array('id' => $course->id, 'f' => $forum->id));
$returnto = forum_go_back_to($returnpageurl);

if (!forum_tp_can_track_forums($forum)) {
    redirect($returnto);
}

$info = new stdClass();
$info->name  = fullname($USER);
$info->forum = format_string($forum->name);

$eventparams = array(
    'context' => context_module::instance($cm->id),
    'relateduserid' => $USER->id,
    'other' => array('forumid' => $forum->id),
);

if (forum_tp_is_tracked($forum) ) {
    if (forum_tp_stop_tracking($forum->id)) {
        $event = \mod_forum\event\readtracking_disabled::create($eventparams);
        $event->trigger();
        redirect($returnto, get_string("nownottracking", "forum", $info), 1);
    } else {
        print_error('cannottrack', '', get_local_referer(false));
    }

} else { // subscribe
    if (forum_tp_start_tracking($forum->id)) {
        $event = \mod_forum\event\readtracking_enabled::create($eventparams);
        $event->trigger();
        redirect($returnto, get_string("nowtracking", "forum", $info), 1);
    } else {
        print_error('cannottrack', '', get_local_referer(false));
    }
}