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
 * External course API
 *
 * @package    core_course
 * @category   external
 * @copyright  2009 Petr Skodak
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

require_once("$CFG->libdir/externallib.php");


/**
 * Course external functions
 *
 * @package    core_course
 * @category   external
 * @copyright  2011 Jerome Mouneyrac
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since Moodle 2.2
 */
class local_mod_forum_external extends external_api
{
    /**
     * Hanv 24/05/2016
     * Return all the information about a quiz by quizid or by cm->instance from course_module
     *
     * @return external_function_parameters
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
     *
     */
    public static function get_forum_by_id_parameters()
    {
        return new external_function_parameters(
            array('forumid' => new external_value(PARAM_INT, 'forum id'))
        );
    }

    public static function get_forum_by_id($forumid)
    {
        global $CFG, $DB;

        $warnings = array();

        //validate parameter
        $params = self::validate_parameters(self::get_forum_by_id_parameters(),
            array('forumid' => $forumid));
        $result = array();

        $forum = $DB->get_record('forum', array('id' => $params['forumid']), '*', MUST_EXIST);
        if(!$forum) {
            $forum = new stdClass();
        }

        $result['forum'] = $forum;
        $result['warnings'] = $warnings;
        return $result;
    }

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
     */
    public static function get_forum_by_id_returns()
    {
        return new external_single_structure(
            array(
                'forum' => new external_single_structure(
                    array(
                        'id' => new external_value(PARAM_INT, 'Standard Moodle primary key.'),
                        'type' => new external_value(PARAM_TEXT, 'Page title'),
                        'course' => new external_value(PARAM_INT, 'Foreign key reference to the course this page is part of.', VALUE_OPTIONAL),
                        'name' => new external_value(PARAM_TEXT, 'Page name.'),
                        'intro' => new external_value(PARAM_RAW, 'Page introduction text.'),
                        'introformat' => new external_format_value(PARAM_INT, 'intro', VALUE_OPTIONAL),
                        'assessed' => new external_value(PARAM_INT, 'Standard Moodle primary key.'),
                        'assesstimestart' => new external_value(PARAM_INT, 'Foreign key reference to the course this quiz is part of.'),
                        'assesstimefinish' => new external_value(PARAM_INT, 'Page introduction text.'),
                        'scale' => new external_format_value(PARAM_INT, 'Display or Not', VALUE_OPTIONAL),
                        'maxbytes' => new external_value(PARAM_INT, 'Page name.'),
                        'maxattachments' => new external_value(PARAM_INT, 'Page name.'),
                        'forcesubscribe' => new external_value(PARAM_INT, 'Page name.'),
                        'trackingtype' => new external_value(PARAM_INT, 'Page name.'),
                        'rsstype' => new external_value(PARAM_INT, 'Page name.'),
                        'rssarticles' => new external_value(PARAM_INT, 'Page name.'),
                        'warnafter' => new external_value(PARAM_INT, 'Page name.'),
                        'blockafter' => new external_value(PARAM_INT, 'Page name.'),
                        'blockperiod' => new external_value(PARAM_INT, 'Page name.'),
                        'completiondiscussions' => new external_value(PARAM_INT, 'Page name.'),
                        'completionreplies' => new external_value(PARAM_INT, 'Page name.'),
                        'completionposts' => new external_value(PARAM_INT, 'Page name.'),
                        'displaywordcount' => new external_value(PARAM_INT, 'Page name.'),
                        'timemodified' => new external_format_value(PARAM_INT, 'intro', VALUE_OPTIONAL)
                    )
                ),
                'warnings' => new external_warnings()
            )
        );
    }
     public static function get_forum_post_by_discussion_and_userid_parameters()
    {
        return new external_function_parameters(
            array(
                'discussion' => new external_value(PARAM_INT, 'discussion'),
                'userid' => new external_value(PARAM_INT, 'user id'),
            )
        );
         
    }

    public static function get_forum_post_by_discussion_and_userid($discussion,$userid)
    {
        global $CFG, $DB;

        $warnings = array();

        //validate parameter
        $params = self::validate_parameters(self::get_forum_post_by_discussion_and_userid_parameters(),
            array(
                'discussion' => $discussion,
                'userid' => $userid
            )
        );
        
        $result = array();

        $forum = $DB->get_record('forum', array('discussion' => $params['discussion'],'userid' => $params['userid']), '*', MUST_EXIST);

        if(!$forum) {
            $forum = new stdClass();
        }

        $result['forum'] = $forum;
        $result['warnings'] = $warnings;
        
        return $result;
    }
    public static function get_forum_post_by_discussion_and_userid_returns()
    {
        return new external_single_structure(
            array(
                'forum' => new external_single_structure(
                    array(
                        'id' => new external_value(PARAM_INT, 'forum id'),
                        'discussion' => new external_value(PARAM_INT, 'discussion', VALUE_DEFAULT),
                        'parent' => new external_value(PARAM_INT, 'parent'),
                        'userid' => new external_value(PARAM_TEXT, 'userid'),
                        'created' => new external_value(PARAM_INT, 'created', VALUE_DEFAULT),
                        'modified' => new external_value(PARAM_INT, 'modified'),
                        'mailed' => new external_value(PARAM_INT, 'mailed', VALUE_DEFAULT),
                        'subject' => new external_value(PARAM_TEXT, 'subject'),
                        'message' => new external_value(PARAM_RAW, 'message'),
                        'messageformat' => new external_value(PARAM_INT, 'messageformat', VALUE_DEFAULT),
                         'attachment' => new external_value(PARAM_TEXT, 'attachment'),
                        'totalscore' => new external_value(PARAM_INT, 'totalscore'),
                        'mailnow' => new external_value(PARAM_INT, 'mailnow', VALUE_DEFAULT),
                    )
                ),
                'warnings' => new external_warnings()
            )
        );
    }
}
