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
 * Subscribe to or unsubscribe from a forum discussion.
 *
 * @package    mod_forum
 * @copyright  2014 Andrew Nicols <andrew@nicols.co.uk>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

define('AJAX_SCRIPT', true);
require_once(dirname(__FILE__) . '/../../../config.php');
require_once($CFG->dirroot . '/mod/forum/lib.php');
require_once($CFG->dirroot . '/mod/forum/remote/locallib.php');
require_once($CFG->dirroot . '/course/remote/locallib.php');

$forumid = required_param('forumid', PARAM_INT);             // The forum to subscribe or unsubscribe.
$discussionid = optional_param('discussionid', null, PARAM_INT);  // The discussionid to subscribe.
$includetext = optional_param('includetext', false, PARAM_BOOL);

$params = array();
$params['parameters[0][name]'] = "id";
$params['parameters[0][value]'] = $forumid;
$forum = get_remote_forum_by($params, '', true);
$course = get_local_course_record($forum->course);

$params = array();
$params['parameters[0][name]'] = "id";
$params['parameters[0][value]'] = $discussionid;
$params['parameters[1][name]'] = "forum";
$params['parameters[1][value]'] = $forumid;
if (!$discussion = get_remote_forum_discussions_by($params)) {
    print_error('invaliddiscussionid', 'forum');
}
$cm = get_remote_course_module_by_instance('forum', $forum->id)->cm;
$context = context_module::instance($cm->id);

require_sesskey();
require_login($course, false, $cm);
require_capability('mod/forum:viewdiscussion', $context);

$return = new stdClass();

if (is_guest($context, $USER)) {
    // is_guest should be used here as this also checks whether the user is a guest in the current course.
    // Guests and visitors cannot subscribe - only enrolled users.
    throw new moodle_exception('noguestsubscribe', 'mod_forum');
}

if (!\mod_forum\subscriptions::is_subscribable($forum)) {
    // Nothing to do. We won't actually output any content here though.
    echo json_encode($return);
    die;
}

if (\mod_forum\subscriptions::is_subscribed($USER->id, $forum, $discussion->id, $cm)) {
    // The user is subscribed, unsubscribe them.
    \mod_forum\subscriptions::unsubscribe_user_from_discussion($USER->id, $discussion, $context);
} else {
    // The user is unsubscribed, subscribe them.
    \mod_forum\subscriptions::subscribe_user_to_discussion($USER->id, $discussion, $context);
}

// Now return the updated subscription icon.
$return->icon = forum_get_discussion_subscription_icon($forum, $discussion->id, null, $includetext);
echo json_encode($return);
die;
