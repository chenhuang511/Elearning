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
 * External forum API
 *
 * @package    local_mod_forum
 * @category   external
 * @copyright  2009 Petr Skodak
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

require_once("$CFG->libdir/externallib.php");


/**
 * Forum external functions
 *
 * @package    local_mod_forum
 * @category   external
 * @copyright  2011 Jerome Mouneyrac
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since Moodle 2.2
 */
class local_mod_forum_external extends external_api
{
    public static function get_forum_by_parameters()
    {
        return new external_function_parameters(
            array(
                'parameters' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the params'
                ),
                'sort' => new external_value(PARAM_RAW, 'sort'),
                'mustexists' => new external_value(PARAM_BOOL, 'must exists')
            )
        );
    }

    public static function get_forum_by($parameters, $sort, $mustexists)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::get_forum_by_parameters(), array(
            'parameters' => $parameters,
            'sort' => $sort,
            'mustexists' => $mustexists
        ));

        $arr = array();
        foreach ($params['parameters'] as $p) {
            $arr = array_merge($arr, array($p['name'] => $p['value']));
        }

        $result = array();

        if ($params['mustexists'] === FALSE && $params['sort'] == '') {
            $forum = $DB->get_record("forum", $arr);
        } else if ($params['mustexists'] === FALSE && $params['sort'] != '') {
            $forum = $DB->get_record("forum", $arr, $params['sort']);
        } else {
            $forum = $DB->get_record("forum", $arr, '*', MUST_EXIST);
        }

        if (!$forum) {
            $forum = new stdClass();
        }

        $result['forum'] = $forum;
        $result['warnings'] = $warnings;
        return $result;
    }

    public static function get_forum_by_returns()
    {
        return new external_single_structure(
            array(
                'forum' => new external_single_structure(
                    array(
                        'id' => new external_value(PARAM_INT, 'the forum id', VALUE_OPTIONAL),
                        'course' => new external_value(PARAM_INT, 'the course id', VALUE_OPTIONAL),
                        'type' => new external_value(PARAM_RAW, 'type', VALUE_OPTIONAL),
                        'name' => new external_value(PARAM_RAW, 'forum name', VALUE_OPTIONAL),
                        'intro' => new external_value(PARAM_RAW, 'Page introduction text.', VALUE_OPTIONAL),
                        'introformat' => new external_format_value(PARAM_INT, 'intro format', VALUE_OPTIONAL),
                        'assessed' => new external_value(PARAM_INT, 'assessed', VALUE_OPTIONAL),
                        'assesstimestart' => new external_value(PARAM_INT, 'assess time start', VALUE_OPTIONAL),
                        'assesstimefinish' => new external_value(PARAM_INT, 'assess time finish', VALUE_OPTIONAL),
                        'scale' => new external_format_value(PARAM_INT, 'scale', VALUE_OPTIONAL),
                        'maxbytes' => new external_value(PARAM_INT, 'max bytes', VALUE_OPTIONAL),
                        'maxattachments' => new external_value(PARAM_INT, 'max attachments', VALUE_OPTIONAL),
                        'forcesubscribe' => new external_value(PARAM_INT, 'force subscribe', VALUE_OPTIONAL),
                        'trackingtype' => new external_value(PARAM_INT, 'tracking type', VALUE_OPTIONAL),
                        'rsstype' => new external_value(PARAM_INT, 'rss type', VALUE_OPTIONAL),
                        'rssarticles' => new external_value(PARAM_INT, 'rss articles', VALUE_OPTIONAL),
                        'timemodified' => new external_format_value(PARAM_INT, 'time modified', VALUE_OPTIONAL),
                        'warnafter' => new external_value(PARAM_INT, 'warn after', VALUE_OPTIONAL),
                        'blockafter' => new external_value(PARAM_INT, 'block after', VALUE_OPTIONAL),
                        'blockperiod' => new external_value(PARAM_INT, 'block period', VALUE_OPTIONAL),
                        'completiondiscussions' => new external_value(PARAM_INT, 'completion discussions', VALUE_OPTIONAL),
                        'completionreplies' => new external_value(PARAM_INT, 'completion replies', VALUE_OPTIONAL),
                        'completionposts' => new external_value(PARAM_INT, 'completion posts', VALUE_OPTIONAL),
                        'displaywordcount' => new external_value(PARAM_INT, 'display word count', VALUE_OPTIONAL)
                    )
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function get_forum_discussions_by_parameters()
    {
        return new external_function_parameters(
            array(
                'parameters' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the params'
                ),
                'sort' => new external_value(PARAM_RAW, 'sort'),
                'mustexists' => new external_value(PARAM_BOOL, 'must exists')
            )
        );
    }

    public static function get_forum_discussions_by($parameters, $sort, $mustexists)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::get_forum_discussions_by_parameters(), array(
            'parameters' => $parameters,
            'sort' => $sort,
            'mustexists' => $mustexists
        ));

        $arr = array();
        foreach ($params['parameters'] as $p) {
            $arr = array_merge($arr, array($p['name'] => $p['value']));
        }

        $result = array();

        if ($params['mustexists'] === FALSE) {
            $discussion = $DB->get_record("forum_discussions", $arr);
        } else if ($params['mustexists'] === FALSE && $params['sort'] != '') {
            $discussion = $DB->get_record("forum_discussions", $arr, $params['sort']);
        } else {
            $discussion = $DB->get_record("forum_discussions", $arr, '*', MUST_EXIST);
        }

        if (!$discussion) {
            $discussion = new stdClass();
        }

        $result['discussion'] = $discussion;
        $result['warnings'] = $warnings;
        return $result;
    }

    public static function get_forum_discussions_by_returns()
    {
        return new external_single_structure(
            array(
                'discussion' => new external_single_structure(
                    array(
                        'id' => new external_value(PARAM_INT, 'the id', VALUE_OPTIONAL),
                        'course' => new external_value(PARAM_INT, 'the course id', VALUE_OPTIONAL),
                        'forum' => new external_value(PARAM_INT, 'the forum id', VALUE_OPTIONAL),
                        'name' => new external_value(PARAM_RAW, 'the name', VALUE_OPTIONAL),
                        'firstpost' => new external_value(PARAM_INT, 'first post', VALUE_OPTIONAL),
                        'userid' => new external_value(PARAM_INT, 'the user id', VALUE_OPTIONAL),
                        'groupid' => new external_value(PARAM_INT, 'the group id', VALUE_OPTIONAL),
                        'assessed' => new external_value(PARAM_INT, 'the assessed', VALUE_OPTIONAL),
                        'timemodified' => new external_value(PARAM_INT, 'time modified', VALUE_OPTIONAL),
                        'usermodified' => new external_value(PARAM_INT, 'user modified', VALUE_OPTIONAL),
                        'timestart' => new external_value(PARAM_INT, 'time start', VALUE_OPTIONAL),
                        'timeend' => new external_value(PARAM_INT, 'time end', VALUE_OPTIONAL),
                        'pinned' => new external_value(PARAM_INT, 'pinned', VALUE_OPTIONAL)
                    )
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function get_forum_discussion_subs_by_parameters()
    {
        return new external_function_parameters(
            array(
                'parameters' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the params'
                ),
                'sort' => new external_value(PARAM_RAW, 'sort'),
                'mustexists' => new external_value(PARAM_BOOL, 'must exists')
            )
        );
    }

    public static function get_forum_discussion_subs_by($parameters, $sort, $mustexists)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::get_forum_discussion_subs_by_parameters(), array(
            'parameters' => $parameters,
            'sort' => $sort,
            'mustexists' => $mustexists
        ));

        $arr = array();
        foreach ($params['parameters'] as $p) {
            $arr = array_merge($arr, array($p['name'] => $p['value']));
        }

        $result = array();

        if ($params['mustexists'] === FALSE && $params['sort'] == '') {
            $sub = $DB->get_record("forum_discussion_subs", $arr);
        } else if ($params['mustexists'] === FALSE && $params['sort'] != '') {
            $sub = $DB->get_record("forum_discussion_subs", $arr, $params['sort']);
        } else {
            $sub = $DB->get_record("forum_discussion_subs", $arr, '*', MUST_EXIST);
        }

        if (!$sub) {
            $sub = new stdClass();
        }

        $result['sub'] = $sub;
        $result['warnings'] = $warnings;
        return $result;
    }

    public static function get_forum_discussion_subs_by_returns()
    {
        return new external_single_structure(
            array(
                'sub' => new external_single_structure(
                    array(
                        'id' => new external_value(PARAM_INT, 'the id', VALUE_OPTIONAL),
                        'forum' => new external_value(PARAM_INT, 'the forum', VALUE_OPTIONAL),
                        'userid' => new external_value(PARAM_INT, 'the user id', VALUE_OPTIONAL),
                        'discussion' => new external_value(PARAM_INT, 'the discussion id', VALUE_OPTIONAL),
                        'preference' => new external_value(PARAM_INT, 'preference', VALUE_OPTIONAL)
                    )
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function get_forum_posts_by_parameters()
    {
        return new external_function_parameters(
            array(
                'parameters' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the params'
                ),
                'sort' => new external_value(PARAM_RAW, 'sort'),
                'mustexists' => new external_value(PARAM_BOOL, 'must exists')
            )
        );
    }

    public static function get_forum_posts_by($parameters, $sort, $mustexists)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::get_forum_posts_by_parameters(), array(
            'parameters' => $parameters,
            'sort' => $sort,
            'mustexists' => $mustexists
        ));

        $arr = array();
        foreach ($params['parameters'] as $p) {
            $arr = array_merge($arr, array($p['name'] => $p['value']));
        }

        $result = array();

        if ($params['mustexists'] === FALSE) {
            $post = $DB->get_record("forum_posts", $arr);
        } else if ($params['mustexists'] === FALSE && $params['sort'] != '') {
            $post = $DB->get_record("forum_posts", $arr, $params['sort']);
        } else {
            $post = $DB->get_record("forum_posts", $arr, '*', MUST_EXIST);
        }

        if (!$post) {
            $post = new stdClass();
        }

        $result['post'] = $post;
        $result['warnings'] = $warnings;
        return $result;
    }

    public static function get_forum_posts_by_returns()
    {
        return new external_single_structure(
            array(
                'post' => new external_single_structure(
                    array(
                        'id' => new external_value(PARAM_INT, 'the id', VALUE_OPTIONAL),
                        'discussion' => new external_value(PARAM_INT, 'the discussion id', VALUE_OPTIONAL),
                        'parent' => new external_value(PARAM_INT, 'the parent', VALUE_OPTIONAL),
                        'userid' => new external_value(PARAM_INT, 'the user id', VALUE_OPTIONAL),
                        'created' => new external_value(PARAM_INT, 'created', VALUE_OPTIONAL),
                        'modified' => new external_value(PARAM_INT, 'modified', VALUE_OPTIONAL),
                        'mailed' => new external_value(PARAM_INT, 'mailed', VALUE_OPTIONAL),
                        'subject' => new external_value(PARAM_RAW, 'the subject', VALUE_OPTIONAL),
                        'message' => new external_value(PARAM_RAW, 'the message', VALUE_OPTIONAL),
                        'messageformat' => new external_value(PARAM_INT, 'message format', VALUE_OPTIONAL),
                        'messagetrust' => new external_value(PARAM_INT, 'message trust', VALUE_OPTIONAL),
                        'attachment' => new external_value(PARAM_RAW, 'attachment', VALUE_OPTIONAL),
                        'totalscore' => new external_value(PARAM_INT, 'total score', VALUE_OPTIONAL),
                        'mailnow' => new external_value(PARAM_INT, 'mail now', VALUE_OPTIONAL)
                    )
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function get_forum_digests_by_parameters()
    {
        return new external_function_parameters(
            array(
                'parameters' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the params'
                ),
                'sort' => new external_value(PARAM_RAW, 'sort'),
                'mustexists' => new external_value(PARAM_BOOL, 'must exists')
            )
        );
    }

    public static function get_forum_digests_by($parameters, $sort, $mustexists)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::get_forum_digests_by_parameters(), array(
            'parameters' => $parameters,
            'sort' => $sort,
            'mustexists' => $mustexists
        ));

        $arr = array();
        foreach ($params['parameters'] as $p) {
            $arr = array_merge($arr, array($p['name'] => $p['value']));
        }

        $result = array();

        if ($params['mustexists'] === FALSE) {
            $digest = $DB->get_record("forum_digests", $arr);
        } else if ($params['mustexists'] === FALSE && $params['sort'] != '') {
            $digest = $DB->get_record("forum_digests", $arr, $params['sort']);
        } else {
            $digest = $DB->get_record("forum_digests", $arr, '*', MUST_EXIST);
        }

        if (!$digest) {
            $digest = new stdClass();
        }

        $result['digest'] = $digest;
        $result['warnings'] = $warnings;
        return $result;
    }

    public static function get_forum_digests_by_returns()
    {
        return new external_single_structure(
            array(
                'digest' => new external_single_structure(
                    array(
                        'id' => new external_value(PARAM_INT, 'the id'),
                        'userid' => new external_value(PARAM_INT, 'the user id'),
                        'forum' => new external_value(PARAM_INT, 'created'),
                        'maildigest' => new external_value(PARAM_INT, 'mailed')
                    )
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function get_forum_track_prefs_by_parameters()
    {
        return new external_function_parameters(
            array(
                'parameters' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the params'
                ),
                'sort' => new external_value(PARAM_RAW, 'sort'),
                'mustexists' => new external_value(PARAM_BOOL, 'must exists')
            )
        );
    }

    public static function get_forum_track_prefs_by($parameters, $sort, $mustexists)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::get_forum_track_prefs_by_parameters(), array(
            'parameters' => $parameters,
            'sort' => $sort,
            'mustexists' => $mustexists
        ));

        $arr = array();
        foreach ($params['parameters'] as $p) {
            $arr = array_merge($arr, array($p['name'] => $p['value']));
        }

        $result = array();

        if ($params['mustexists'] === FALSE) {
            $track = $DB->get_record("forum_track_prefs", $arr);
        } else if ($params['mustexists'] === FALSE && $params['sort'] != '') {
            $track = $DB->get_record("forum_track_prefs", $arr, $params['sort']);
        } else {
            $track = $DB->get_record("forum_track_prefs", $arr, '*', MUST_EXIST);
        }

        if (!$track) {
            $track = new stdClass();
        }

        $result['track'] = $track;
        $result['warnings'] = $warnings;
        return $result;
    }

    public static function get_forum_track_prefs_by_returns()
    {
        return new external_single_structure(
            array(
                'track' => new external_single_structure(
                    array(
                        'id' => new external_value(PARAM_INT, 'the id', VALUE_OPTIONAL),
                        'userid' => new external_value(PARAM_INT, 'the user id', VALUE_OPTIONAL),
                        'forumid' => new external_value(PARAM_INT, 'created', VALUE_OPTIONAL)
                    )
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function get_forum_subscriptions_by_parameters()
    {
        return new external_function_parameters(
            array(
                'parameters' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the params'
                ),
                'sort' => new external_value(PARAM_RAW, 'sort'),
                'mustexists' => new external_value(PARAM_BOOL, 'must exists')
            )
        );
    }

    public static function get_forum_subscriptions_by($parameters, $sort, $mustexists)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::get_forum_subscriptions_by_parameters(), array(
            'parameters' => $parameters,
            'sort' => $sort,
            'mustexists' => $mustexists
        ));

        $arr = array();
        foreach ($params['parameters'] as $p) {
            $arr = array_merge($arr, array($p['name'] => $p['value']));
        }

        $result = array();

        if ($params['mustexists'] === FALSE) {
            $subscription = $DB->get_record("forum_subscriptions", $arr);
        } else if ($params['mustexists'] === FALSE && $params['sort'] != '') {
            $subscription = $DB->get_record("forum_subscriptions", $arr, $params['sort']);
        } else {
            $subscription = $DB->get_record("forum_subscriptions", $arr, '*', MUST_EXIST);
        }

        if (!$subscription) {
            $subscription = new stdClass();
        }

        $result['subscription'] = $subscription;
        $result['warnings'] = $warnings;
        return $result;
    }

    public static function get_forum_subscriptions_by_returns()
    {
        return new external_single_structure(
            array(
                'subscription' => new external_single_structure(
                    array(
                        'id' => new external_value(PARAM_INT, 'the id', VALUE_OPTIONAL),
                        'userid' => new external_value(PARAM_INT, 'the user id', VALUE_OPTIONAL),
                        'forum' => new external_value(PARAM_INT, 'the forum', VALUE_OPTIONAL)
                    )
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function get_scale_by_parameters()
    {
        return new external_function_parameters(
            array(
                'parameters' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the params'
                ),
                'sort' => new external_value(PARAM_RAW, 'sort'),
                'mustexists' => new external_value(PARAM_BOOL, 'must exists')
            )
        );
    }

    public static function get_scale_by($parameters, $sort, $mustexists)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::get_scale_by_parameters(), array(
            'parameters' => $parameters,
            'sort' => $sort,
            'mustexists' => $mustexists
        ));

        $arr = array();
        foreach ($params['parameters'] as $p) {
            $arr = array_merge($arr, array($p['name'] => $p['value']));
        }

        $result = array();

        if ($params['mustexists'] === FALSE) {
            $scale = $DB->get_record("scale", $arr);
        } else if ($params['mustexists'] === FALSE && $params['sort'] != '') {
            $scale = $DB->get_record("scale", $arr, $params['sort']);
        } else {
            $scale = $DB->get_record("scale", $arr, '*', MUST_EXIST);
        }

        if (!$scale) {
            $scale = new stdClass();
        }

        $result['scale'] = $scale;
        $result['warnings'] = $warnings;
        return $result;
    }

    public static function get_scale_by_returns()
    {
        return new external_single_structure(
            array(
                'scale' => new external_single_structure(
                    array(
                        'id' => new external_value(PARAM_INT, 'the id', VALUE_OPTIONAL),
                        'courseid' => new external_value(PARAM_INT, 'the course id', VALUE_OPTIONAL),
                        'userid' => new external_value(PARAM_INT, 'the user id', VALUE_OPTIONAL),
                        'name' => new external_value(PARAM_RAW, 'the name', VALUE_OPTIONAL),
                        'scale' => new external_value(PARAM_RAW, 'the scale', VALUE_OPTIONAL),
                        'description' => new external_value(PARAM_RAW, 'the description', VALUE_OPTIONAL),
                        'descriptionformat' => new external_value(PARAM_INT, 'description format', VALUE_OPTIONAL),
                        'timemodified' => new external_value(PARAM_INT, 'time modified', VALUE_OPTIONAL)
                    )
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function get_list_forum_by_parameters()
    {
        return new external_function_parameters(
            array(
                'parameters' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the params'
                ),
                'sort' => new external_value(PARAM_RAW, 'sort'),
                'limitfrom' => new external_value(PARAM_INT, 'limit from'),
                'limitnum' => new external_value(PARAM_INT, 'limit num')
            )
        );
    }

    public static function get_list_forum_by($parameters, $sort, $limitfrom, $limitnum)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::get_list_forum_by_parameters(), array(
            'parameters' => $parameters,
            'sort' => $sort,
            'limitfrom' => $limitfrom,
            'limitnum' => $limitnum
        ));

        $arr = array();
        foreach ($params['parameters'] as $p) {
            $arr = array_merge($arr, array($p['name'] => $p['value']));
        }

        $result = array();
        if (($params['limitfrom'] == 0 && $params['limitnum'] == 0) && $params['sort'] == '') {
            $forums = $DB->get_records("forum", $arr);
        } else if (($params['limitfrom'] == 0 && $params['limitnum'] == 0) && $params['sort'] != '') {
            $forums = $DB->get_records("forum", $arr, $params['sort']);
        } else {
            $forums = $DB->get_records("forum", $arr, $params['sort'], '*', $params['limitfrom'], $params['limitnum']);
        }

        if (!$forums) {
            $forums = array();
        }

        $result['forums'] = $forums;
        $result['warnings'] = $warnings;

        return $result;
    }

    public static function get_list_forum_by_returns()
    {
        return new external_single_structure(
            array(
                'forums' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'id' => new external_value(PARAM_INT, 'the forum id', VALUE_OPTIONAL),
                            'course' => new external_value(PARAM_INT, 'the course id', VALUE_OPTIONAL),
                            'type' => new external_value(PARAM_RAW, 'type', VALUE_OPTIONAL),
                            'name' => new external_value(PARAM_RAW, 'forum name', VALUE_OPTIONAL),
                            'intro' => new external_value(PARAM_RAW, 'Page introduction text.', VALUE_OPTIONAL),
                            'introformat' => new external_format_value(PARAM_INT, 'intro format', VALUE_OPTIONAL),
                            'assessed' => new external_value(PARAM_INT, 'assessed', VALUE_OPTIONAL),
                            'assesstimestart' => new external_value(PARAM_INT, 'assess time start', VALUE_OPTIONAL),
                            'assesstimefinish' => new external_value(PARAM_INT, 'assess time finish', VALUE_OPTIONAL),
                            'scale' => new external_format_value(PARAM_INT, 'scale', VALUE_OPTIONAL),
                            'maxbytes' => new external_value(PARAM_INT, 'max bytes', VALUE_OPTIONAL),
                            'maxattachments' => new external_value(PARAM_INT, 'max attachments', VALUE_OPTIONAL),
                            'forcesubscribe' => new external_value(PARAM_INT, 'force subscribe', VALUE_OPTIONAL),
                            'trackingtype' => new external_value(PARAM_INT, 'tracking type', VALUE_OPTIONAL),
                            'rsstype' => new external_value(PARAM_INT, 'rss type', VALUE_OPTIONAL),
                            'rssarticles' => new external_value(PARAM_INT, 'rss articles', VALUE_OPTIONAL),
                            'timemodified' => new external_format_value(PARAM_INT, 'time modified', VALUE_OPTIONAL),
                            'warnafter' => new external_value(PARAM_INT, 'warn after', VALUE_OPTIONAL),
                            'blockafter' => new external_value(PARAM_INT, 'block after', VALUE_OPTIONAL),
                            'blockperiod' => new external_value(PARAM_INT, 'block period', VALUE_OPTIONAL),
                            'completiondiscussions' => new external_value(PARAM_INT, 'completion discussions', VALUE_OPTIONAL),
                            'completionreplies' => new external_value(PARAM_INT, 'completion replies', VALUE_OPTIONAL),
                            'completionposts' => new external_value(PARAM_INT, 'completion posts', VALUE_OPTIONAL),
                            'displaywordcount' => new external_value(PARAM_INT, 'display word count', VALUE_OPTIONAL)
                        )
                    ), 'forum discussions'
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function get_list_forum_discussions_by_parameters()
    {
        return new external_function_parameters(
            array(
                'parameters' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the params'
                ),
                'sort' => new external_value(PARAM_RAW, 'sort'),
                'limitfrom' => new external_value(PARAM_INT, 'limit from'),
                'limitnum' => new external_value(PARAM_INT, 'limit num')
            )
        );
    }

    public static function get_list_forum_discussions_by($parameters, $sort, $limitfrom, $limitnum)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::get_list_forum_discussions_by_parameters(), array(
            'parameters' => $parameters,
            'sort' => $sort,
            'limitfrom' => $limitfrom,
            'limitnum' => $limitnum
        ));

        $arr = array();
        foreach ($params['parameters'] as $p) {
            $arr = array_merge($arr, array($p['name'] => $p['value']));
        }

        $result = array();
        if (($params['limitfrom'] == 0 && $params['limitnum'] == 0) && $params['sort'] == '') {
            $discussions = $DB->get_records("forum_discussions", $arr);
        } else if (($params['limitfrom'] == 0 && $params['limitnum'] == 0) && $params['sort'] != '') {
            $discussions = $DB->get_records("forum_discussions", $arr, $params['sort']);
        } else {
            $discussions = $DB->get_records("forum_discussions", $arr, $params['sort'], '*', $params['limitfrom'], $params['limitnum']);
        }

        if (!$discussions) {
            $discussions = array();
        }

        $result['discussions'] = $discussions;
        $result['warnings'] = $warnings;

        return $result;
    }

    public static function get_list_forum_discussions_by_returns()
    {
        return new external_single_structure(
            array(
                'discussions' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'id' => new external_value(PARAM_INT, 'the id', VALUE_OPTIONAL),
                            'course' => new external_value(PARAM_INT, 'the course id', VALUE_OPTIONAL),
                            'forum' => new external_value(PARAM_INT, 'the forum id', VALUE_OPTIONAL),
                            'name' => new external_value(PARAM_RAW, 'the name', VALUE_OPTIONAL),
                            'firstpost' => new external_value(PARAM_INT, 'first post', VALUE_OPTIONAL),
                            'userid' => new external_value(PARAM_INT, 'the user id', VALUE_OPTIONAL),
                            'groupid' => new external_value(PARAM_INT, 'the group id', VALUE_OPTIONAL),
                            'assessed' => new external_value(PARAM_INT, 'the assessed', VALUE_OPTIONAL),
                            'timemodified' => new external_value(PARAM_INT, 'time modified', VALUE_OPTIONAL),
                            'usermodified' => new external_value(PARAM_INT, 'user modified', VALUE_OPTIONAL),
                            'timestart' => new external_value(PARAM_INT, 'time start', VALUE_OPTIONAL),
                            'timeend' => new external_value(PARAM_INT, 'time end', VALUE_OPTIONAL),
                            'pinned' => new external_value(PARAM_INT, 'pinned', VALUE_OPTIONAL)
                        )
                    ), 'forum discussions'
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function get_list_forum_discussions_sql_parameters()
    {
        return new external_function_parameters(
            array(
                'hostip' => new external_value(PARAM_RAW, 'host ip'),
                'parameters' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the params'
                ),
                'sort' => new external_value(PARAM_RAW, 'sort')
            )
        );
    }

    public static function get_list_forum_discussions_sql($hostip, $parameters, $sort)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::get_list_forum_discussions_sql_parameters(), array(
            'hostip' => $hostip,
            'parameters' => $parameters,
            'sort' => $sort
        ));

        $sql = "SELECT md.* FROM {forum_discussions} md 
                LEFT JOIN {user} u ON md.userid = u.id WHERE";

        $hostid = $DB->get_field('mnet_host', 'id', array('ip_address' => $params['hostip']));

        if (!$hostid) {
            $warnings['message'] = "not found host";
        }

        $arr = array();
        foreach ($params['parameters'] as $p) {
            $columnname = "md." . $p['name'];
            $sql .= " $columnname = ? AND ";
            $arr = array_merge($arr, array($p['value']));
        }

        $arr = array_merge($arr, array($hostid));

        $sql .= " md.userid IN (SELECT id FROM {user} WHERE mnethostid = ?)";

        if ($params['sort'] != '') {
            $orderby = $params['sort'];
            $sql .= " ORDER BY md.$orderby";
        }


        $result = array();
        $discussions = $DB->get_records_sql($sql, $arr);

        if (!$discussions) {
            $discussions = array();
        }

        $result['discussions'] = $discussions;
        $result['warnings'] = $warnings;

        return $result;
    }

    public static function get_list_forum_discussions_sql_returns()
    {
        return self::get_list_forum_discussions_by_returns();
    }

    public static function get_list_forum_posts_by_parameters()
    {
        return new external_function_parameters(
            array(
                'parameters' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the params'
                ),
                'sort' => new external_value(PARAM_RAW, 'sort'),
                'limitfrom' => new external_value(PARAM_INT, 'limit from'),
                'limitnum' => new external_value(PARAM_INT, 'limit num')
            )
        );
    }

    public static function get_list_forum_posts_by($parameters, $sort, $limitfrom, $limitnum)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::get_list_forum_posts_by_parameters(), array(
            'parameters' => $parameters,
            'sort' => $sort,
            'limitfrom' => $limitfrom,
            'limitnum' => $limitnum
        ));

        $arr = array();
        foreach ($params['parameters'] as $p) {
            $arr = array_merge($arr, array($p['name'] => $p['value']));
        }

        $result = array();
        if (($params['limitfrom'] == 0 && $params['limitnum'] == 0) && $params['sort'] == '') {
            $posts = $DB->get_records("forum_posts", $arr);
        } else if (($params['limitfrom'] == 0 && $params['limitnum'] == 0) && $params['sort'] != '') {
            $posts = $DB->get_records("forum_posts", $arr, $params['sort']);
        } else {
            $posts = $DB->get_records("forum_posts", $arr, $params['sort'], '*', $params['limitfrom'], $params['limitnum']);
        }

        if (!$posts) {
            $posts = array();
        }

        $result['posts'] = $posts;
        $result['warnings'] = $warnings;

        return $result;
    }

    public static function get_list_forum_posts_by_returns()
    {
        return new external_single_structure(
            array(
                'posts' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'id' => new external_value(PARAM_INT, 'the id', VALUE_OPTIONAL),
                            'discussion' => new external_value(PARAM_INT, 'the discussion id', VALUE_OPTIONAL),
                            'parent' => new external_value(PARAM_INT, 'the parent', VALUE_OPTIONAL),
                            'userid' => new external_value(PARAM_INT, 'the user id', VALUE_OPTIONAL),
                            'created' => new external_value(PARAM_INT, 'created', VALUE_OPTIONAL),
                            'modified' => new external_value(PARAM_INT, 'modified', VALUE_OPTIONAL),
                            'mailed' => new external_value(PARAM_INT, 'mailed', VALUE_OPTIONAL),
                            'subject' => new external_value(PARAM_RAW, 'the subject', VALUE_OPTIONAL),
                            'message' => new external_value(PARAM_RAW, 'the message', VALUE_OPTIONAL),
                            'messageformat' => new external_value(PARAM_INT, 'message format', VALUE_OPTIONAL),
                            'messagetrust' => new external_value(PARAM_INT, 'message trust', VALUE_OPTIONAL),
                            'attachment' => new external_value(PARAM_RAW, 'attachment', VALUE_OPTIONAL),
                            'totalscore' => new external_value(PARAM_INT, 'total score', VALUE_OPTIONAL),
                            'mailnow' => new external_value(PARAM_INT, 'mail now', VALUE_OPTIONAL)
                        )
                    ), 'forum posts'
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function get_list_forum_posts_sql_parameters()
    {
        return new external_function_parameters(
            array(
                'hostip' => new external_value(PARAM_RAW, 'host ip'),
                'parameters' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the params'
                ),
                'sort' => new external_value(PARAM_RAW, 'sort')
            )
        );
    }

    public static function get_list_forum_posts_sql($hostip, $parameters, $sort)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::get_list_forum_posts_sql_parameters(), array(
            'hostip' => $hostip,
            'parameters' => $parameters,
            'sort' => $sort
        ));

        $sql = "SELECT fp.* FROM {forum_posts} fp 
                LEFT JOIN {user} u ON u.id = fp.userid ";

        $host = $DB->get_record('mnet_host', array('ip_address' => $params['hostip']), '*', MUST_EXIST);

        if (!$host) {
            $warnings['message'] = "not found host";
        }

        $arr = array();
        foreach ($params['parameters'] as $p) {
            $columnname = "fp." . $p['name'];
            $sql .= "WHERE $columnname = ? AND ";
            $arr = array_merge($arr, array($p['value']));
        }

        $arr = array_merge($arr, array($host->id));

        $sql .= " fp.userid IN (SELECT id FROM {user} WHERE mnethostid = ?)";

        if ($params['sort'] != '') {
            $orderby = $params['sort'];
            $sql .= " ORDER BY $orderby";
        }


        $result = array();
        $posts = $DB->get_records_sql($sql, $arr);

        if (!$posts) {
            $posts = array();
        }

        $result['posts'] = $posts;
        $result['warnings'] = $warnings;

        return $result;
    }

    public static function get_list_forum_posts_sql_returns()
    {
        return self::get_list_forum_posts_by_returns();
    }

    public static function get_list_forum_read_by_parameters()
    {
        return new external_function_parameters(
            array(
                'parameters' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the params'
                ),
                'sort' => new external_value(PARAM_RAW, 'sort'),
                'limitfrom' => new external_value(PARAM_INT, 'limit from'),
                'limitnum' => new external_value(PARAM_INT, 'limit num')
            )
        );
    }

    public static function get_list_forum_read_by($parameters, $sort, $limitfrom, $limitnum)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::get_list_forum_read_by_parameters(), array(
            'parameters' => $parameters,
            'sort' => $sort,
            'limitfrom' => $limitfrom,
            'limitnum' => $limitnum
        ));

        $arr = array();
        foreach ($params['parameters'] as $p) {
            $arr = array_merge($arr, array($p['name'] => $p['value']));
        }

        $result = array();
        if (($params['limitfrom'] == 0 && $params['limitnum'] == 0) && $params['sort'] == '') {
            $reads = $DB->get_records("forum_read", $arr);
        } else if (($params['limitfrom'] == 0 && $params['limitnum'] == 0) && $params['sort'] != '') {
            $reads = $DB->get_records("forum_read", $arr, $params['sort']);
        } else {
            $reads = $DB->get_records("forum_read", $arr, $params['sort'], '*', $params['limitfrom'], $params['limitnum']);
        }

        if (!$reads) {
            $reads = array();
        }

        $result['reads'] = $reads;
        $result['warnings'] = $warnings;

        return $result;
    }

    public static function get_list_forum_read_by_returns()
    {
        return new external_single_structure(
            array(
                'reads' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'id' => new external_value(PARAM_INT, 'the id', VALUE_OPTIONAL),
                            'userid' => new external_value(PARAM_INT, 'the user id', VALUE_OPTIONAL),
                            'forumid' => new external_value(PARAM_INT, 'the forumid', VALUE_OPTIONAL),
                            'discussionid' => new external_value(PARAM_INT, 'the discussion id', VALUE_OPTIONAL),
                            'postid' => new external_value(PARAM_INT, 'the post id', VALUE_OPTIONAL),
                            'firstread' => new external_value(PARAM_INT, 'first read', VALUE_OPTIONAL),
                            'lastread' => new external_value(PARAM_INT, 'last read', VALUE_OPTIONAL)
                        )
                    ), 'forum read'
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function get_list_forum_discussion_subs_by_parameters()
    {
        return new external_function_parameters(
            array(
                'parameters' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the params'
                ),
                'sort' => new external_value(PARAM_RAW, 'sort'),
                'limitfrom' => new external_value(PARAM_INT, 'limit from'),
                'limitnum' => new external_value(PARAM_INT, 'limit num')
            )
        );
    }

    public static function get_list_forum_discussion_subs_by($parameters, $sort, $limitfrom, $limitnum)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::get_list_forum_discussion_subs_by_parameters(), array(
            'parameters' => $parameters,
            'sort' => $sort,
            'limitfrom' => $limitfrom,
            'limitnum' => $limitnum
        ));

        $arr = array();
        foreach ($params['parameters'] as $p) {
            $arr = array_merge($arr, array($p['name'] => $p['value']));
        }

        $result = array();
        if (($params['limitfrom'] == 0 && $params['limitnum'] == 0) && $params['sort'] == '') {
            $subs = $DB->get_records("forum_discussion_subs", $arr);
        } else if (($params['limitfrom'] == 0 && $params['limitnum'] == 0) && $params['sort'] != '') {
            $subs = $DB->get_records("forum_discussion_subs", $arr, $params['sort']);
        } else {
            $subs = $DB->get_records("forum_discussion_subs", $arr, $params['sort'], '*', $params['limitfrom'], $params['limitnum']);
        }

        if (!$subs) {
            $subs = array();
        }

        $result['subs'] = $subs;
        $result['warnings'] = $warnings;

        return $result;
    }

    public static function get_list_forum_discussion_subs_by_returns()
    {
        return new external_single_structure(
            array(
                'subs' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'id' => new external_value(PARAM_INT, 'the id', VALUE_OPTIONAL),
                            'forum' => new external_value(PARAM_INT, 'the forum id', VALUE_OPTIONAL),
                            'userid' => new external_value(PARAM_INT, 'the user id', VALUE_OPTIONAL),
                            'discussion' => new external_value(PARAM_INT, 'the discussion id', VALUE_OPTIONAL),
                            'preference' => new external_value(PARAM_INT, 'the preference', VALUE_OPTIONAL)
                        )
                    ), 'forum discussion subs'
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function delete_mdl_forum_parameters()
    {
        return new external_function_parameters(
            array(
                'modname' => new external_value(PARAM_RAW, 'the mod name'),
                'parameters' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the params'
                )
            )
        );
    }

    public static function delete_mdl_forum($modname, $parameters)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::delete_mdl_forum_parameters(), array(
            'modname' => $modname,
            'parameters' => $parameters
        ));

        $result = array();
        $arr = array();
        foreach ($params['parameters'] as $p) {
            $arr = array_merge($arr, array($p['name'] => $p['value']));
        }

        $transaction = $DB->start_delegated_transaction();
        $result['status'] = $DB->delete_records($params['modname'], $arr);
        $transaction->allow_commit();

        $result['warnings'] = $warnings;

        return $result;
    }

    public static function delete_mdl_forum_returns()
    {
        return new external_single_structure(
            array(
                'status' => new external_value(PARAM_BOOL, 'bool: true if delete success'),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function delete_mdl_forum_select_parameters()
    {
        return new external_function_parameters(
            array(
                'modname' => new external_value(PARAM_RAW, 'the mod name'),
                'select' => new external_value(PARAM_RAW, 'the select'),
                'parameters' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the params'
                )
            )
        );
    }

    public static function delete_mdl_forum_select($modname, $select, $parameters)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::delete_mdl_forum_select_parameters(), array(
            'modname' => $modname,
            'select' => $select,
            'parameters' => $parameters
        ));

        $result = array();
        $arr = array();
        foreach ($params['parameters'] as $p) {
            $arr = array_merge($arr, array($p['name'] => $p['value']));
        }

        $transaction = $DB->start_delegated_transaction();
        $result['status'] = $DB->delete_records_select($params['modname'], $params['select'], $arr);
        $transaction->allow_commit();

        $result['warnings'] = $warnings;

        return $result;
    }

    public static function delete_mdl_forum_select_returns()
    {
        return self::delete_mdl_forum_returns();
    }

    public static function save_mdl_forum_parameters()
    {
        return new external_function_parameters(
            array(
                'modname' => new external_value(PARAM_RAW, 'the mod name'),
                'data' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the data saved'
                )
            )
        );
    }

    public static function save_mdl_forum($modname, $data)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::save_mdl_forum_parameters(), array(
            'modname' => $modname,
            'data' => $data
        ));

        $obj = new stdClass();

        foreach ($params['data'] as $element) {
            if ($element['name'] == "attachments" && $element['value'] == "") {
                $obj->$element['name'] = null;
            }
            $obj->$element['name'] = $element['value'];
        }

        $result = array();

        $transaction = $DB->start_delegated_transaction();

        $newid = $DB->insert_record($params['modname'], $obj);

        $transaction->allow_commit();

        $result['newid'] = $newid;
        $result['warnings'] = $warnings;

        return $result;
    }

    public static function save_mdl_forum_returns()
    {
        return new external_single_structure(
            array(
                'newid' => new external_value(PARAM_INT, 'the new id'),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function update_mdl_forum_parameters()
    {
        return new external_function_parameters(
            array(
                'modname' => new external_value(PARAM_RAW, 'the mod name'),
                'id' => new external_value(PARAM_INT, 'the id'),
                'data' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the data saved'
                )
            )
        );
    }

    public static function update_mdl_forum($modname, $id, $data)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::update_mdl_forum_parameters(), array(
            'modname' => $modname,
            'id' => $id,
            'data' => $data
        ));

        $result = array();

        $obj = $DB->get_record($params['modname'], array("id" => $params['id']));

        if (!$obj) {
            $result['id'] = 0;
            $result['warnings'] = $warnings;
            return $result;
        }

        foreach ($params['data'] as $element) {
            $obj->$element['name'] = $element['value'];
        }

        $transaction = $DB->start_delegated_transaction();

        $cid = $DB->update_record($params['modname'], $obj);

        $transaction->allow_commit();

        $result['id'] = $cid;
        $result['warnings'] = $warnings;

        return $result;
    }

    public static function update_mdl_forum_returns()
    {
        return new external_single_structure(
            array(
                'id' => new external_value(PARAM_INT, 'the id'),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function update_mdl_forum_by_parameters()
    {
        return new external_function_parameters(
            array(
                'modname' => new external_value(PARAM_RAW, 'the mod name'),
                'parameters' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the params'
                ),
                'data' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the data saved'
                )
            )
        );
    }

    public static function update_mdl_forum_by($modname, $parameters, $data)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::update_mdl_forum_by_parameters(), array(
            'modname' => $modname,
            'parameters' => $parameters,
            'data' => $data
        ));

        $result = array();

        $prs = array();
        foreach ($params['parameters'] as $param) {
            $prs = array_merge($prs, array($param['name'] => $param['value']));
        }

        $obj = $DB->get_record($params['modname'], $prs);

        if (!$obj) {
            $result['id'] = 0;
            $result['warnings'] = $warnings;
            return $result;
        } else {

            foreach ($params['data'] as $element) {
                $obj->$element['name'] = $element['value'];
            }

            $transaction = $DB->start_delegated_transaction();

            $cid = $DB->update_record($params['modname'], $obj);

            $transaction->allow_commit();

            $result['id'] = $cid;
            $result['warnings'] = $warnings;

            return $result;
        }
    }

    public static function update_mdl_forum_by_returns()
    {
        return self::update_mdl_forum_returns();
    }

    public static function get_field_forum_by_parameters()
    {
        return new external_function_parameters(
            array(
                'modname' => new external_value(PARAM_RAW, 'mod name'),
                'parameters' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the params'
                ),
                'field' => new external_value(PARAM_RAW, 'field')
            )
        );
    }

    public static function get_field_forum_by($modname, $parameters, $field)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::get_field_forum_by_parameters(), array(
            'modname' => $modname,
            'parameters' => $parameters,
            'field' => $field
        ));

        $arr = array();
        foreach ($params['parameters'] as $p) {
            $arr = array_merge($arr, array($p['name'] => $p['value']));
        }

        $result = array();

        $f = $DB->get_field($params['modname'], $params['field'], $arr);

        if (!$f) {
            $f = 0;
        }

        $result['field'] = $f;
        $result['warnings'] = $warnings;

        return $result;

    }

    public static function get_field_forum_by_returns()
    {
        return new external_single_structure(
            array(
                'field' => new external_value(PARAM_RAW, 'field'),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function get_field_forum_sql_parameters()
    {
        return new external_function_parameters(
            array(
                'sql' => new external_value(PARAM_RAW, 'mod name'),
                'parameters' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the params'
                )
            )
        );
    }

    public static function get_field_forum_sql($sql, $parameters)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::get_field_forum_sql_parameters(), array(
            'sql' => $sql,
            'parameters' => $parameters
        ));

        $arr = array();
        foreach ($params['parameters'] as $p) {
            $arr = array_merge($arr, array($p['name'] => $p['value']));
        }

        $result = array();

        $f = $DB->get_field_sql($params['sql'], $arr);

        if (!$f) {
            $f = 0;
        }

        $result['field'] = $f;
        $result['warnings'] = $warnings;

        return $result;
    }

    public static function get_field_forum_sql_returns()
    {
        return self::get_field_forum_by_returns();
    }

    public static function get_count_forum_by_parameters()
    {
        return new external_function_parameters(
            array(
                'modname' => new external_value(PARAM_RAW, 'mod name'),
                'parameters' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the params'
                ),
                'sort' => new external_value(PARAM_RAW, 'sort')
            )
        );
    }

    public static function get_count_forum_by($modname, $parameters, $sort)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::get_count_forum_by_parameters(), array(
            'modname' => $modname,
            'parameters' => $parameters,
            'sort' => $sort
        ));

        $arr = array();
        foreach ($params['parameters'] as $p) {
            $arr = array_merge($arr, array($p['name'] => $p['value']));
        }

        $result = array();
        if ($params['sort'] == '') {
            $count = $DB->count_records($params['modname'], $arr);
        } else {
            $count = $DB->count_records($params['modname'], $arr, $params['sort']);
        }

        $result['count'] = $count;
        $result['warnings'] = $warnings;

        return $result;
    }

    public static function get_count_forum_by_returns()
    {
        return new external_function_parameters(
            array(
                'count' => new external_value(PARAM_INT, 'count row'),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function check_record_forum_exists_parameters()
    {
        return new external_function_parameters(
            array(
                'modname' => new external_value(PARAM_RAW, ' the mod name'),
                'parameters' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the params'
                )
            )
        );
    }

    public static function check_record_forum_exists($modname, $parameters)
    {
        global $DB;

        $warnings = array();

        $params = self::validate_parameters(self::check_record_forum_exists_parameters(), array(
            'modname' => $modname,
            'parameters' => $parameters,
        ));

        $result = array();

        $arr = array();
        foreach ($params['parameters'] as $p) {
            $arr = array_merge($arr, array($p['name'] => $p['value']));
        }

        $result['status'] = $DB->record_exists($params['modname'], $arr);
        $result['warnings'] = $warnings;

        return $result;
    }

    public static function check_record_forum_exists_returns()
    {
        return new external_single_structure(
            array(
                'status' => new external_value(PARAM_BOOL, 'status'),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function forum_get_discussions_sql_parameters()
    {
        return new external_function_parameters(
            array(
                'postdata' => new external_value(PARAM_RAW, 'the post data'),
                'allnames' => new external_value(PARAM_RAW, 'the all names'),
                'umfields' => new external_value(PARAM_RAW, 'the um fields'),
                'umtable' => new external_value(PARAM_RAW, 'the um table'),
                'timelimit' => new external_value(PARAM_RAW, 'the time limit'),
                'groupselect' => new external_value(PARAM_RAW, 'the group select'),
                'forumsort' => new external_value(PARAM_RAW, 'the forum sort'),
                'parameters' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the params'
                ),
                'hostip' => new external_value(PARAM_RAW, 'host ip'),
                'limitfrom' => new external_value(PARAM_INT, 'the limit from'),
                'limitnum' => new external_value(PARAM_INT, 'the limit num')
            )
        );
    }

    public static function forum_get_discussions_sql($postdata, $allnames, $umfields, $umtable, $timelimit, $groupselect, $forumsort, $parameters, $hostip, $limitfrom, $limitnum)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::forum_get_discussions_sql_parameters(), array(
            'postdata' => $postdata,
            'allnames' => $allnames,
            'umfields' => $umfields,
            'umtable' => $umtable,
            'timelimit' => $timelimit,
            'groupselect' => $groupselect,
            'forumsort' => $forumsort,
            'parameters' => $parameters,
            'hostip' => $hostip,
            'limitfrom' => $limitfrom,
            'limitnum' => $limitnum
        ));

        $hostid = $DB->get_field('mnet_host', 'id', array('ip_address' => $params['hostip']));
        if (!$hostid) {
            $warnings['message'] = "Not found host";
        }

        $postdata_field = $params['postdata'];
        $allnames_field = $params['allnames'];
        $umfields_field = $params['umfields'];
        $umtable_field = $params['umtable'];
        $timelimit_field = $params['timelimit'];
        $groupselect_field = $params['groupselect'];
        $forumsort_field = $params['forumsort'];

        $sql = "SELECT $postdata_field, d.name, d.timemodified, d.usermodified, d.groupid, d.timestart, d.timeend, d.pinned, $allnames_field,
                   u.email, u.picture, u.imagealt $umfields_field
              FROM {forum_discussions} d
                   JOIN {forum_posts} p ON p.discussion = d.id
                   JOIN {user} u ON p.userid = u.id
                   $umtable_field
             WHERE d.forum = ? AND p.parent = 0
                   $timelimit_field $groupselect_field
                   AND p.userid IN (SELECT id FROM {user} WHERE mnethostid = ?)
          ORDER BY $forumsort_field, d.id DESC";

        $arr = array();
        $i = 0;

        foreach ($params['parameters'] as $p) {
            $arr[$i] = $p['value'];
            $i++;
        }

        $arr[$i + 1] = $hostid;

        $result = array();

        $data = $DB->get_records_sql($sql, $arr, $params['limitfrom'], $params['limitnum']);

        if (!$data) {
            $data = array();
        }

        $result['data'] = $data;
        $result['warnings'] = $warnings;

        return $result;
    }

    public static function forum_get_discussions_sql_returns()
    {
        return new external_single_structure(
            array(
                'data' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'id' => new external_value(PARAM_INT, 'the id of post', VALUE_OPTIONAL),
                            'subject' => new external_value(PARAM_RAW, 'the subject of post', VALUE_OPTIONAL),
                            'modified' => new external_value(PARAM_INT, 'the modified of post', VALUE_OPTIONAL),
                            'discussion' => new external_value(PARAM_INT, 'the discussion of post', VALUE_OPTIONAL),
                            'userid' => new external_value(PARAM_INT, 'the userid of post', VALUE_OPTIONAL),
                            'name' => new external_value(PARAM_RAW, 'the name of discussion', VALUE_OPTIONAL),
                            'timemodified' => new external_value(PARAM_INT, 'the time modified of discussion', VALUE_OPTIONAL),
                            'usermodified' => new external_value(PARAM_INT, 'the user modified of discussion', VALUE_OPTIONAL),
                            'groupid' => new external_value(PARAM_INT, 'the groupid of discussion', VALUE_OPTIONAL),
                            'timestart' => new external_value(PARAM_INT, 'the time start of discussion', VALUE_OPTIONAL),
                            'timeend' => new external_value(PARAM_INT, 'the time end of discussion', VALUE_OPTIONAL),
                            'pinned' => new external_value(PARAM_INT, 'the pinned of discussion', VALUE_OPTIONAL),
                            'firstnamephonetic' => new external_value(PARAM_RAW, 'the first name phonetic of user', VALUE_OPTIONAL),
                            'lastnamephonetic' => new external_value(PARAM_RAW, 'the last name phonetic of user', VALUE_OPTIONAL),
                            'middlename' => new external_value(PARAM_RAW, 'the middle name of user', VALUE_OPTIONAL),
                            'alternatename' => new external_value(PARAM_RAW, 'the alternate name of user', VALUE_OPTIONAL),
                            'firstname' => new external_value(PARAM_RAW, 'the first name of user', VALUE_OPTIONAL),
                            'lastname' => new external_value(PARAM_RAW, 'the last name of user', VALUE_OPTIONAL),
                            'email' => new external_value(PARAM_RAW, 'the email of user', VALUE_OPTIONAL),
                            'picture' => new external_value(PARAM_RAW, 'the email of user', VALUE_OPTIONAL),
                            'imagealt' => new external_value(PARAM_RAW, 'the email of user', VALUE_OPTIONAL),
                            'umfirstnamephonetic' => new external_value(PARAM_RAW, 'the first name phonetic of user modified', VALUE_OPTIONAL),
                            'umlastnamephonetic' => new external_value(PARAM_RAW, 'the last name phonetic of user modified', VALUE_OPTIONAL),
                            'ummiddlename' => new external_value(PARAM_RAW, 'the middle name of user modified', VALUE_OPTIONAL),
                            'umalternatename' => new external_value(PARAM_RAW, 'the alternate name of user modified', VALUE_OPTIONAL),
                            'umfirstname' => new external_value(PARAM_RAW, 'the first name of user modified', VALUE_OPTIONAL),
                            'umlastname' => new external_value(PARAM_RAW, 'the last name of user modified', VALUE_OPTIONAL),
                            'umemail' => new external_value(PARAM_RAW, 'the email of user modified', VALUE_OPTIONAL),
                            'umpicture' => new external_value(PARAM_RAW, 'the email of user modified', VALUE_OPTIONAL),
                            'umimagealt' => new external_value(PARAM_RAW, 'the email of user modified', VALUE_OPTIONAL),
                        )
                    ), 'data'
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function get_count_forum_sql_parameters()
    {
        return new external_function_parameters(
            array(
                'sql' => new external_value(PARAM_RAW, 'the query sql'),
                'hostip' => new external_value(PARAM_HOST, 'host ip'),
                'parameters' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the params'
                )
            )
        );
    }

    public static function get_count_forum_sql($sql, $hostip, $parameters)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::get_count_forum_sql_parameters(), array(
            'sql' => $sql,
            'hostip' => $hostip,
            'parameters' => $parameters
        ));

        $hostid = $DB->get_field('mnet_host', 'id', array('ip_address' => $params['hostip']));

        if (!$hostid) {
            $warnings['message'] = "Not found host";
        }

        $arr = array();
        foreach ($params['parameters'] as $p) {
            $arr = array_merge($arr, array($p['value']));
        }

        $arr = array_merge($arr, array($hostid));

        $result = array();

        $count = $DB->get_field_sql($params['sql'], $arr);

        $result['count'] = $count;
        $result['warnings'] = $warnings;

        return $result;
    }

    public static function get_count_forum_sql_returns()
    {
        return self::get_count_forum_by_returns();
    }

    public static function forum_count_discussion_replies_sql_parameters()
    {
        return new external_function_parameters(
            array(
                'parameters' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the params'
                ),
                'limitfrom' => new external_value(PARAM_INT, 'the limit from'),
                'limitnum' => new external_value(PARAM_INT, 'the limit num'),
                'forumsort' => new external_value(PARAM_RAW, 'the forum sort'),
                'orderby' => new external_value(PARAM_RAW, 'the order by'),
                'groupby' => new external_value(PARAM_RAW, 'the group by')
            )
        );
    }

    public static function forum_count_discussion_replies_sql($parameters, $limitfrom, $limitnum, $forumsort, $orderby, $groupby)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::forum_count_discussion_replies_sql_parameters(), array(
            'parameters' => $parameters,
            'limitfrom' => $limitfrom,
            'limitnum' => $limitnum,
            'forumsort' => $forumsort,
            'orderby' => $orderby,
            'groupby' => $groupby
        ));

        $arr = array();
        foreach ($params['parameters'] as $p) {
            $arr = array_merge($arr, array($p['value']));
        }

        $result = array();
        if (($params['limitfrom'] == 0 and $params['limitnum'] == 0) or $params['forumsort'] == "") {
            $sql = "SELECT p.discussion, COUNT(p.id) AS replies, MAX(p.id) AS lastpostid
                  FROM {forum_posts} p
                       JOIN {forum_discussions} d ON p.discussion = d.id
                 WHERE p.parent > 0 AND d.forum = ?
              GROUP BY p.discussion";

            $replies = $DB->get_records_sql($sql, $arr);
        } else {
            $groupby_field = $params['groupby'];
            $orderby_field = $params['orderby'];
            $sql = "SELECT p.discussion, (COUNT(p.id) - 1) AS replies, MAX(p.id) AS lastpostid
                  FROM {forum_posts} p
                       JOIN {forum_discussions} d ON p.discussion = d.id
                 WHERE d.forum = ?
              GROUP BY p.discussion $groupby_field $orderby_field";

            $replies = $DB->get_records_sql($sql, $arr, $params['limitfrom'], $params['limitnum']);
        }


        if (!$replies) {
            $replies = array();
        }

        $result['replies'] = $replies;
        $result['warnings'] = $warnings;

        return $result;
    }

    public static function forum_count_discussion_replies_sql_returns()
    {
        return new external_single_structure(
            array(
                'replies' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'discussion' => new external_value(PARAM_INT, 'the discussion id', VALUE_OPTIONAL),
                            'replies' => new external_value(PARAM_INT, 'the count of reply', VALUE_OPTIONAL),
                            'lastpostid' => new external_value(PARAM_INT, 'the last post id', VALUE_OPTIONAL),
                        )
                    ), 'reply data'
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function forum_get_post_full_sql_parameters()
    {
        return new external_function_parameters(
            array(
                'sql' => new external_value(PARAM_RAW, 'the query sql'),
                'parameters' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the params'
                )
            )
        );
    }

    public static function forum_get_post_full_sql($sql, $parameters)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::forum_get_post_full_sql_parameters(), array(
            'sql' => $sql,
            'parameters' => $parameters
        ));

        $arr = array();
        foreach ($params['parameters'] as $p) {
            $arr = array_merge($arr, array($p['value']));
        }

        $result = array();

        $post = $DB->get_record_sql($params['sql'], $arr);

        if (!$post) {
            $post = new stdClass();
        }

        $result['post'] = $post;
        $result['warnings'] = $warnings;

        return $result;
    }

    public static function forum_get_post_full_sql_returns()
    {
        return new external_single_structure(
            array(
                'post' => new external_single_structure(
                    array(
                        'id' => new external_value(PARAM_INT, 'the id', VALUE_OPTIONAL),
                        'discussion' => new external_value(PARAM_INT, 'the discussion id', VALUE_OPTIONAL),
                        'parent' => new external_value(PARAM_INT, 'the parent', VALUE_OPTIONAL),
                        'userid' => new external_value(PARAM_INT, 'the user id', VALUE_OPTIONAL),
                        'created' => new external_value(PARAM_INT, 'created', VALUE_OPTIONAL),
                        'modified' => new external_value(PARAM_INT, 'modified', VALUE_OPTIONAL),
                        'mailed' => new external_value(PARAM_INT, 'mailed', VALUE_OPTIONAL),
                        'subject' => new external_value(PARAM_RAW, 'the subject', VALUE_OPTIONAL),
                        'message' => new external_value(PARAM_RAW, 'the message', VALUE_OPTIONAL),
                        'messageformat' => new external_value(PARAM_INT, 'message format', VALUE_OPTIONAL),
                        'messagetrust' => new external_value(PARAM_INT, 'message trust', VALUE_OPTIONAL),
                        'attachment' => new external_value(PARAM_RAW, 'attachment', VALUE_OPTIONAL),
                        'totalscore' => new external_value(PARAM_INT, 'total score', VALUE_OPTIONAL),
                        'mailnow' => new external_value(PARAM_INT, 'mail now', VALUE_OPTIONAL),
                        'forum' => new external_value(PARAM_INT, 'the id of forum', VALUE_OPTIONAL),
                        'firstnamephonetic' => new external_value(PARAM_RAW, 'the first name phonetic of user', VALUE_OPTIONAL),
                        'lastnamephonetic' => new external_value(PARAM_RAW, 'the last name phonetic of user', VALUE_OPTIONAL),
                        'middlename' => new external_value(PARAM_RAW, 'the middle name of user', VALUE_OPTIONAL),
                        'alternatename' => new external_value(PARAM_RAW, 'the alternate name of user', VALUE_OPTIONAL),
                        'firstname' => new external_value(PARAM_RAW, 'the first name of user', VALUE_OPTIONAL),
                        'lastname' => new external_value(PARAM_RAW, 'the last name of user', VALUE_OPTIONAL),
                        'email' => new external_value(PARAM_RAW, 'the email of user', VALUE_OPTIONAL),
                        'picture' => new external_value(PARAM_RAW, 'the email of user', VALUE_OPTIONAL),
                        'imagealt' => new external_value(PARAM_RAW, 'the email of user', VALUE_OPTIONAL),
                    )
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function forum_get_discussion_neighbours_sql_parameters()
    {
        return new external_function_parameters(
            array(
                'sql' => new external_value(PARAM_RAW, 'the query sql'),
                'hostip' => new external_value(PARAM_HOST, 'the ip of host'),
                'parameters' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the params'
                ),
                'strictness' => new external_value(PARAM_INT, 'the strictness')
            )
        );
    }

    public static function forum_get_discussion_neighbours_sql($sql, $hostip, $parameters, $strictness)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::forum_get_discussion_neighbours_sql_parameters(), array(
            'sql' => $sql,
            'hostip' => $hostip,
            'parameters' => $parameters,
            'strictness' => $strictness
        ));

        $hostid = $DB->get_field('mnet_host', 'id', array('ip_address' => $params['hostip']));

        $arr = array();
        foreach ($params['parameters'] as $p) {
            $arr = array_merge($arr, array($p['name'] => $p['value']));
        }

        if ($hostid) {
            $arr = array_merge($arr, array('mnethostid' => $hostid));
        }

        $result = array();

        $neighbour = $DB->get_record_sql($params['sql'], $arr, $params['strictness']);

        if (!$neighbour) {
            $neighbour = new stdClass();
        }

        $result['neighbour'] = $neighbour;
        $result['warnings'] = $warnings;

        return $result;
    }

    public static function forum_get_discussion_neighbours_sql_returns()
    {
        return new external_single_structure(
            array(
                'neighbour' => new external_single_structure(
                    array(
                        'id' => new external_value(PARAM_INT, 'the id of discussion', VALUE_OPTIONAL),
                        'name' => new external_value(PARAM_RAW, 'the name of discussion', VALUE_OPTIONAL),
                        'timemodified' => new external_value(PARAM_INT, 'the time modified', VALUE_OPTIONAL),
                        'groupid' => new external_value(PARAM_INT, 'the group id', VALUE_OPTIONAL),
                        'timestart' => new external_value(PARAM_INT, 'time start', VALUE_OPTIONAL),
                        'timeend' => new external_value(PARAM_INT, 'time end', VALUE_OPTIONAL)
                    )
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function forum_get_all_discussion_posts_sql_parameters()
    {
        return new external_function_parameters(
            array(
                'allnames' => new external_value(PARAM_RAW, 'get name field'),
                'tracking' => new external_value(PARAM_INT, 'tracking'),
                'sort' => new external_value(PARAM_RAW, 'order by'),
                'hostip' => new external_value(PARAM_RAW, 'host IP'),
                'parameters' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the params'
                )
            )
        );
    }

    public static function forum_get_all_discussion_posts_sql($allnames, $tracking, $sort, $hostip, $parameters)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::forum_get_all_discussion_posts_sql_parameters(), array(
            'allnames' => $allnames,
            'tracking' => $tracking,
            'sort' => $sort,
            'hostip' => $hostip,
            'parameters' => $parameters
        ));

        $tr_sel = "";
        $tr_join = "";

        if ($params['tracking'] || $params['tracking'] > 0) {
            $tr_sel = ", fr.id AS postread";
            $tr_join = "LEFT JOIN {forum_read} fr ON (fr.postid = p.id AND fr.userid = ?)";
        }

        $allnames_field = $params['allnames'];
        $sort_field = $params['sort'];

        $sql = "SELECT p.*, $allnames_field, u.email, u.picture, u.imagealt $tr_sel
                                     FROM {forum_posts} p
                                          LEFT JOIN {user} u ON p.userid = u.id
                                          $tr_join
                                    WHERE p.discussion = ?";

        $arr = array();
        foreach ($params['parameters'] as $p) {
            $arr = array_merge($arr, array($p['value']));
        }

        $hostid = $DB->get_field('mnet_host', 'id', array('ip_address' => $params['hostip']));

        if ($hostid) {
            $sql .= " AND p.userid IN (SELECT id FROM {user} WHERE mnethostid = ?)";
            $arr = array_merge($arr, array($hostid));
        }

        $sql .= " ORDER BY $sort_field";

        $result = array();

        $posts = $DB->get_records_sql($sql, $arr);

        if (!$posts) {
            $posts = array();
        }

        $result['posts'] = $posts;
        $result['warnings'] = $warnings;

        return $result;
    }

    public static function forum_get_all_discussion_posts_sql_returns()
    {
        return new external_single_structure(
            array(
                'posts' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'id' => new external_value(PARAM_INT, 'the id', VALUE_OPTIONAL),
                            'discussion' => new external_value(PARAM_INT, 'the discussion id', VALUE_OPTIONAL),
                            'parent' => new external_value(PARAM_INT, 'the parent', VALUE_OPTIONAL),
                            'userid' => new external_value(PARAM_INT, 'the user id', VALUE_OPTIONAL),
                            'created' => new external_value(PARAM_INT, 'created', VALUE_OPTIONAL),
                            'modified' => new external_value(PARAM_INT, 'modified', VALUE_OPTIONAL),
                            'mailed' => new external_value(PARAM_INT, 'mailed', VALUE_OPTIONAL),
                            'subject' => new external_value(PARAM_RAW, 'the subject', VALUE_OPTIONAL),
                            'message' => new external_value(PARAM_RAW, 'the message', VALUE_OPTIONAL),
                            'messageformat' => new external_value(PARAM_INT, 'message format', VALUE_OPTIONAL),
                            'messagetrust' => new external_value(PARAM_INT, 'message trust', VALUE_OPTIONAL),
                            'attachment' => new external_value(PARAM_RAW, 'attachment', VALUE_OPTIONAL),
                            'totalscore' => new external_value(PARAM_INT, 'total score', VALUE_OPTIONAL),
                            'mailnow' => new external_value(PARAM_INT, 'mail now', VALUE_OPTIONAL),
                            'firstnamephonetic' => new external_value(PARAM_RAW, 'the first name phonetic of user', VALUE_OPTIONAL),
                            'lastnamephonetic' => new external_value(PARAM_RAW, 'the last name phonetic of user', VALUE_OPTIONAL),
                            'middlename' => new external_value(PARAM_RAW, 'the middle name of user', VALUE_OPTIONAL),
                            'alternatename' => new external_value(PARAM_RAW, 'the alternate name of user', VALUE_OPTIONAL),
                            'firstname' => new external_value(PARAM_RAW, 'the first name of user', VALUE_OPTIONAL),
                            'lastname' => new external_value(PARAM_RAW, 'the last name of user', VALUE_OPTIONAL),
                            'email' => new external_value(PARAM_RAW, 'the email of user', VALUE_OPTIONAL),
                            'picture' => new external_value(PARAM_RAW, 'the email of user', VALUE_OPTIONAL),
                            'imagealt' => new external_value(PARAM_RAW, 'the email of user', VALUE_OPTIONAL),
                        )
                    ), 'data post'
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function forum_user_has_posted_parameters()
    {
        return new external_function_parameters(
            array(
                'sql' => new external_value(PARAM_RAW, 'the query sql'),
                'parameters' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the params'
                )
            )
        );
    }

    public static function forum_user_has_posted($sql, $parameters)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::forum_user_has_posted_parameters(), array(
            'sql' => $sql,
            'parameters' => $parameters
        ));

        $arr = array();
        foreach ($params['parameters'] as $element) {
            $arr = array_merge($arr, array($element['name'] => $element['value']));
        }

        $result = array();

        $result['status'] = $DB->record_exists_sql($sql, $arr);
        $result['warnings'] = $warnings;

        return $result;
    }

    public static function forum_user_has_posted_returns()
    {
        return self::check_record_forum_exists_returns();
    }

    public static function forum_user_has_posted_discussion_parameters()
    {
        return new external_function_parameters(
            array(
                'sql' => new external_value(PARAM_RAW, 'the query sql'),
                'parameters' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the params'
                )
            )
        );
    }

    public static function forum_user_has_posted_discussion($sql, $parameters)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::forum_user_has_posted_discussion_parameters(), array(
            'sql' => $sql,
            'parameters' => $parameters
        ));

        $arr = array();
        foreach ($params['parameters'] as $element) {
            $arr = array_merge($arr, array($element['name'] => $element['value']));
        }

        $result = array();

        $result['status'] = $DB->record_exists_sql($sql, $arr);
        $result['warnings'] = $warnings;

        return $result;
    }

    public static function forum_user_has_posted_discussion_returns()
    {
        return self::check_record_forum_exists_returns();
    }

    public static function forum_search_posts_parameters()
    {
        return new external_function_parameters(
            array(
                'fromsql' => new external_value(PARAM_RAW, 'the query sql'),
                'selectsql' => new external_value(PARAM_RAW, 'the query sql'),
                'allnames' => new external_value(PARAM_RAW, 'the query sql'),
                'parameters' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the params'
                ),
                'hostip' => new external_value(PARAM_HOST, 'the ip of host'),
                'limitfrom' => new external_value(PARAM_INT, 'the limit from'),
                'limitnum' => new external_value(PARAM_INT, 'the limit num')
            )
        );
    }

    public static function forum_search_posts($fromsql, $selectsql, $allnames, $parameters, $hostip, $limitfrom, $limitnum)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::forum_search_posts_parameters(), array(
            'fromsql' => $fromsql,
            'selectsql' => $selectsql,
            'allnames' => $allnames,
            'parameters' => $parameters,
            'hostip' => $hostip,
            'limitfrom' => $limitfrom,
            'limitnum' => $limitnum,
        ));

        $hostid = $DB->get_field('mnet_host', 'id', array('ip_address' => $params['hostip']));

        $fromsql_field = $params['fromsql'];
        $selectsql_field = $params['selectsql'];
        $allnames_field = $params['allnames'];

        $countsql = "SELECT COUNT(*)
                   FROM $fromsql_field
                  WHERE $selectsql_field AND p.userid IN (SELECT id FROM {user} WHERE mnethostid = :hostid)";

        $searchsql = "SELECT p.*,
                         d.forum,
                         $allnames_field,
                         u.email,
                         u.picture,
                         u.imagealt
                    FROM $fromsql_field
                   WHERE $selectsql_field
                   AND p.userid IN (SELECT id FROM {user} WHERE mnethostid = :hostid)
                ORDER BY p.modified DESC";

        $arr = array();
        foreach ($params['parameters'] as $p) {
            $arr = array_merge($arr, array($p['name'] => $p['value']));
        }

        $arr = array_merge($arr, array('hostid' => $hostid));

        $result = array();

        $result['totalcount'] = $DB->count_records_sql($countsql, $arr);

        $rs_search = $DB->get_records_sql($searchsql, $arr, $params['limitfrom'], $params['limitnum']);

        if (!$rs_search) {
            $rs_search = array();
        }

        $result['rs_search'] = $rs_search;
        $result['warnings'] = $warnings;
        return $result;
    }

    public static function forum_search_posts_returns()
    {
        return new external_single_structure(
            array(
                'totalcount' => new external_value(PARAM_INT, 'total count'),
                'rs_search' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'id' => new external_value(PARAM_INT, 'the post id', VALUE_OPTIONAL),
                            'discussion' => new external_value(PARAM_INT, 'the discussion id', VALUE_OPTIONAL),
                            'parent' => new external_value(PARAM_INT, 'the parent', VALUE_OPTIONAL),
                            'userid' => new external_value(PARAM_INT, 'the user id', VALUE_OPTIONAL),
                            'created' => new external_value(PARAM_INT, 'created', VALUE_OPTIONAL),
                            'modified' => new external_value(PARAM_INT, 'modified', VALUE_OPTIONAL),
                            'mailed' => new external_value(PARAM_INT, 'mailed', VALUE_OPTIONAL),
                            'subject' => new external_value(PARAM_RAW, 'the subject', VALUE_OPTIONAL),
                            'message' => new external_value(PARAM_RAW, 'the message', VALUE_OPTIONAL),
                            'messageformat' => new external_value(PARAM_INT, 'message format', VALUE_OPTIONAL),
                            'messagetrust' => new external_value(PARAM_INT, 'message trust', VALUE_OPTIONAL),
                            'attachment' => new external_value(PARAM_RAW, 'attachment', VALUE_OPTIONAL),
                            'totalscore' => new external_value(PARAM_INT, 'total score', VALUE_OPTIONAL),
                            'mailnow' => new external_value(PARAM_INT, 'mail now', VALUE_OPTIONAL),
                            'forum' => new external_value(PARAM_INT, 'forum id', VALUE_OPTIONAL),
                            'firstnamephonetic' => new external_value(PARAM_RAW, 'the first name phonetic of user', VALUE_OPTIONAL),
                            'lastnamephonetic' => new external_value(PARAM_RAW, 'the last name phonetic of user', VALUE_OPTIONAL),
                            'middlename' => new external_value(PARAM_RAW, 'the middle name of user', VALUE_OPTIONAL),
                            'alternatename' => new external_value(PARAM_RAW, 'the alternate name of user', VALUE_OPTIONAL),
                            'firstname' => new external_value(PARAM_RAW, 'the first name of user', VALUE_OPTIONAL),
                            'lastname' => new external_value(PARAM_RAW, 'the last name of user', VALUE_OPTIONAL),
                            'email' => new external_value(PARAM_RAW, 'the email of user', VALUE_OPTIONAL),
                            'picture' => new external_value(PARAM_RAW, 'the email of user', VALUE_OPTIONAL),
                            'imagealt' => new external_value(PARAM_RAW, 'the email of user', VALUE_OPTIONAL)
                        )
                    ), 'result data'
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function forum_add_instance_parameters()
    {
        return new external_function_parameters(
            array(
                'forumdata' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the data saved'
                )
            )
        );
    }

    public static function forum_add_instance($forumdata)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::forum_add_instance_parameters(), array(
            'forumdata' => $forumdata
        ));

        $forum = new stdClass();
        foreach ($params['forumdata'] as $dt) {
            $forum->$dt['name'] = $dt['value'];
        }

        $transaction = $DB->start_delegated_transaction();

        $forum->id = $DB->insert_record('forum', $forum);

        $transaction->allow_commit();

        $modcontext = context_module::instance($forum->coursemodule);

        $result['newid'] = $forum->id;
        $result['warnings'] = $warnings;

        return $result;
    }

    public static function forum_add_instance_returns()
    {
        return self::save_mdl_forum_returns();
    }

    public static function forum_add_discussions_parameters()
    {
        return new external_function_parameters(
            array(
                'data' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the data saved'
                ),
                'userid' => new external_value(PARAM_INT, 'the user id')
            )
        );
    }

    public static function forum_add_discussions($data, $userid)
    {

        global $CFG, $DB;
        $warnings = array();
        $result = array();

        $params = self::validate_parameters(self::forum_add_discussions_parameters(), array(
            'data' => $data,
            'userid' => $userid
        ));

        $discussion = new stdClass();
        foreach ($params['data'] as $dt) {
            if ($dt['name'] == 'messagetrust') {
                $dt['value'] = $dt['value'] === 'false' ? false : true;
            }
            $discussion->$dt['name'] = $dt['value'];
        }

        $timenow = isset($discussion->timenow) ? $discussion->timenow : time();

        // The first post is stored as a real post, and linked
        // to from the discuss entry.

        $forum = $DB->get_record('forum', array('id' => $discussion->forum));
        $cm = get_coursemodule_from_instance('forum', $forum->id);

        $post = new stdClass();
        $post->discussion = 0;
        $post->parent = 0;
        $post->userid = $userid;
        $post->created = $timenow;
        $post->modified = $timenow;
        $post->mailed = 0;
        $post->subject = $discussion->name;
        $post->message = $discussion->message;
        $post->messageformat = $discussion->messageformat;
        $post->messagetrust = $discussion->messagetrust;
        $post->attachments = isset($discussion->attachments) ? $discussion->attachments : null;
        $post->forum = $forum->id;     // speedup
        $post->course = $forum->course; // speedup
        $post->mailnow = $discussion->mailnow;

        $transaction = $DB->start_delegated_transaction();

        $post->id = $DB->insert_record("forum_posts", $post);

        // TODO: Fix the calling code so that there always is a $cm when this function is called
        if (!empty($cm->id) && !empty($discussion->itemid)) {   // In "single simple discussions" this may not exist yet
            $context = context_module::instance($cm->id);
            require_once($CFG->dirroot . '/lib/filelib.php');
            $text = file_save_draft_area_files($discussion->itemid, $context->id, 'mod_forum', 'post', $post->id,
                mod_forum_post_form::editor_options($context, null), $post->message);
            $DB->set_field('forum_posts', 'message', $text, array('id' => $post->id));
        }

        // Now do the main entry for the discussion, linking to this first post

        $discussion->firstpost = $post->id;
        $discussion->timemodified = $timenow;
        $discussion->usermodified = $post->userid;
        $discussion->userid = $userid;
        $discussion->assessed = 0;

        $post->discussion = $DB->insert_record("forum_discussions", $discussion);
        $discussion->id = $post->discussion;

        // Finally, set the pointer on the post.
        $DB->set_field("forum_posts", "discussion", $post->discussion, array("id" => $post->id));

        $transaction->allow_commit();

        if (!$forum)
            $forum = new stdClass();
        if (!$cm)
            $cm = new stdClass();

        $result['forum'] = $forum;
        $result['post'] = $post;
        $result['warnings'] = $warnings;
        return $result;
    }

    public static function forum_add_discussions_returns()
    {
        return new external_single_structure(
            array(
                'forum' => new external_single_structure(
                    array(
                        'id' => new external_value(PARAM_INT, 'the forum id', VALUE_OPTIONAL),
                        'course' => new external_value(PARAM_INT, 'the course id', VALUE_OPTIONAL),
                        'type' => new external_value(PARAM_RAW, 'type', VALUE_OPTIONAL),
                        'name' => new external_value(PARAM_RAW, 'forum name', VALUE_OPTIONAL),
                        'intro' => new external_value(PARAM_RAW, 'Page introduction text.', VALUE_OPTIONAL),
                        'introformat' => new external_format_value(PARAM_INT, 'intro format', VALUE_OPTIONAL),
                        'assessed' => new external_value(PARAM_INT, 'assessed', VALUE_OPTIONAL),
                        'assesstimestart' => new external_value(PARAM_INT, 'assess time start', VALUE_OPTIONAL),
                        'assesstimefinish' => new external_value(PARAM_INT, 'assess time finish', VALUE_OPTIONAL),
                        'scale' => new external_format_value(PARAM_INT, 'scale', VALUE_OPTIONAL),
                        'maxbytes' => new external_value(PARAM_INT, 'max bytes', VALUE_OPTIONAL),
                        'maxattachments' => new external_value(PARAM_INT, 'max attachments', VALUE_OPTIONAL),
                        'forcesubscribe' => new external_value(PARAM_INT, 'force subscribe', VALUE_OPTIONAL),
                        'trackingtype' => new external_value(PARAM_INT, 'tracking type', VALUE_OPTIONAL),
                        'rsstype' => new external_value(PARAM_INT, 'rss type', VALUE_OPTIONAL),
                        'rssarticles' => new external_value(PARAM_INT, 'rss articles', VALUE_OPTIONAL),
                        'timemodified' => new external_format_value(PARAM_INT, 'time modified', VALUE_OPTIONAL),
                        'warnafter' => new external_value(PARAM_INT, 'warn after', VALUE_OPTIONAL),
                        'blockafter' => new external_value(PARAM_INT, 'block after', VALUE_OPTIONAL),
                        'blockperiod' => new external_value(PARAM_INT, 'block period', VALUE_OPTIONAL),
                        'completiondiscussions' => new external_value(PARAM_INT, 'completion discussions', VALUE_OPTIONAL),
                        'completionreplies' => new external_value(PARAM_INT, 'completion replies', VALUE_OPTIONAL),
                        'completionposts' => new external_value(PARAM_INT, 'completion posts', VALUE_OPTIONAL),
                        'displaywordcount' => new external_value(PARAM_INT, 'display word count', VALUE_OPTIONAL)
                    )
                ),
                'post' => new external_single_structure(
                    array(
                        'id' => new external_value(PARAM_INT, 'the id', VALUE_OPTIONAL),
                        'discussion' => new external_value(PARAM_INT, 'the discussion id', VALUE_OPTIONAL),
                        'parent' => new external_value(PARAM_INT, 'the parent', VALUE_OPTIONAL),
                        'userid' => new external_value(PARAM_INT, 'the user id', VALUE_OPTIONAL),
                        'created' => new external_value(PARAM_INT, 'created', VALUE_OPTIONAL),
                        'modified' => new external_value(PARAM_INT, 'modified', VALUE_OPTIONAL),
                        'mailed' => new external_value(PARAM_INT, 'mailed', VALUE_OPTIONAL),
                        'subject' => new external_value(PARAM_RAW, 'the subject', VALUE_OPTIONAL),
                        'message' => new external_value(PARAM_RAW, 'the message', VALUE_OPTIONAL),
                        'messageformat' => new external_value(PARAM_INT, 'message format', VALUE_OPTIONAL),
                        'messagetrust' => new external_value(PARAM_INT, 'message trust', VALUE_OPTIONAL),
                        'attachments' => new external_value(PARAM_RAW, 'attachments', VALUE_OPTIONAL),
                        'forum' => new external_value(PARAM_INT, 'the forum id', VALUE_OPTIONAL),
                        'course' => new external_value(PARAM_INT, 'the course id', VALUE_OPTIONAL),
                        'mailnow' => new external_value(PARAM_INT, 'mail now', VALUE_OPTIONAL)
                    )
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function get_recordset_forum_subscriptions_parameters()
    {
        return new external_function_parameters(
            array(
                'forumid' => new external_value(PARAM_INT, 'the id of forum'),
                'hostip' => new external_value(PARAM_HOST, 'the host ip')
            )
        );
    }

    public static function get_recordset_forum_subscriptions($forumid, $hostip)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::get_recordset_forum_subscriptions_parameters(), array(
            'forumid' => $forumid,
            'hostip' => $hostip
        ));

        $hostid = $DB->get_field('mnet_host', 'id', array('ip_address' => $params['hostip']));

        $sql = "SELECT id, userid FROM {forum_subscriptions} WHERE forum = :forum AND userid IN (SELECT id FROM {user} WHERE mnethostid = :hostid)";

        $subscriptions = $DB->get_records_sql($sql, array(
            'forum' => $params['forumid'],
            'hostid' => $hostid
        ));

        if (!$subscriptions) {
            $subscriptions = array();
        }

        $result = array();
        $result['subscriptions'] = $subscriptions;
        $result['warnings'] = $warnings;
        return $result;
    }

    public static function get_recordset_forum_subscriptions_returns()
    {
        return new external_single_structure(
            array(
                'subscriptions' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'id' => new external_value(PARAM_INT, 'the id of subscription'),
                            'userid' => new external_value(PARAM_INT, 'the id of user')
                        )
                    ), 'data'
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function get_fetch_subscribed_users_parameters()
    {
        return new external_function_parameters(
            array(
                'sql' => new external_value(PARAM_RAW, 'the sql query'),
                'hostip' => new external_value(PARAM_HOST, 'the host ip'),
                'parameters' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the params'
                )
            )
        );
    }

    public static function get_fetch_subscribed_users($sql, $hostip, $parameters)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::get_fetch_subscribed_users_parameters(), array(
            'sql' => $sql,
            'hostip' => $hostip,
            'parameters' => $parameters
        ));

        $hostid = $DB->get_field('mnet_host', 'id', array('ip_address' => $params['hostip']));

        $arr = array();

        foreach ($params['parameters'] as $p) {
            $arr = array_merge($arr, array($p['name'] => $p['value']));
        }

        $arr = array_merge($arr, array('hostid' => $hostid));

        $sqlQuery = $params['sql'];

        $rs = $DB->get_records_sql($sqlQuery, $arr);

        if (!$rs) {
            $rs = array();
        }

        $result = array();
        $result['rs'] = $rs;
        $result['warnings'] = $warnings;
        return $result;
    }

    public static function get_fetch_subscribed_users_returns()
    {
        return new external_single_structure(
            array(
                'rs' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'id' => new external_value(PARAM_INT, 'the id of user', VALUE_OPTIONAL)
                        )
                    ), 'data'
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function forum_get_firstpost_from_discussion_by_parameters()
    {
        return new external_function_parameters(
            array(
                'discussionid' => new external_value(PARAM_INT, 'the id of discussion'),
                'hostip' => new external_value(PARAM_HOST, 'the ip address of host')
            )
        );
    }

    public static function forum_get_firstpost_from_discussion_by($discussionid, $hostip)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::forum_get_firstpost_from_discussion_by_parameters(), array(
            'discussionid' => $discussionid,
            'hostip' => $hostip
        ));

        $hostid = $DB->get_field('mnet_host', 'id', array('ip_address' => $params['hostip']));

        $post = $DB->get_record_sql("SELECT p.*
                             FROM {forum_discussions} d,
                                  {forum_posts} p
                            WHERE d.id = ?
                              AND d.firstpost = p.id 
                              AND p.userid IN (SELECT id FROM m_user WHERE mnethostid = ?)", array($params['discussionid'], $hostid));

        if (!$post) {
            $post = new stdClass();
        }

        $result = array();
        $result['post'] = $post;
        $result['warnings'] = $warnings;
        return $result;
    }

    public static function forum_get_firstpost_from_discussion_by_returns()
    {
        return new external_single_structure(
            array(
                'post' => new external_single_structure(
                    array(
                        'id' => new external_value(PARAM_INT, 'the id', VALUE_OPTIONAL),
                        'discussion' => new external_value(PARAM_INT, 'the discussion id', VALUE_OPTIONAL),
                        'parent' => new external_value(PARAM_INT, 'the parent', VALUE_OPTIONAL),
                        'userid' => new external_value(PARAM_INT, 'the user id', VALUE_OPTIONAL),
                        'created' => new external_value(PARAM_INT, 'created', VALUE_OPTIONAL),
                        'modified' => new external_value(PARAM_INT, 'modified', VALUE_OPTIONAL),
                        'mailed' => new external_value(PARAM_INT, 'mailed', VALUE_OPTIONAL),
                        'subject' => new external_value(PARAM_RAW, 'the subject', VALUE_OPTIONAL),
                        'message' => new external_value(PARAM_RAW, 'the message', VALUE_OPTIONAL),
                        'messageformat' => new external_value(PARAM_INT, 'message format', VALUE_OPTIONAL),
                        'messagetrust' => new external_value(PARAM_INT, 'message trust', VALUE_OPTIONAL),
                        'attachment' => new external_value(PARAM_RAW, 'attachment', VALUE_OPTIONAL),
                        'totalscore' => new external_value(PARAM_INT, 'total score', VALUE_OPTIONAL),
                        'mailnow' => new external_value(PARAM_INT, 'mail now', VALUE_OPTIONAL)
                    )
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function forum_get_forums_user_posted_in_parameters()
    {
        return new external_function_parameters(
            array(
                'coursewhere' => new external_value(PARAM_RAW, 'the query'),
                'join' => new external_value(PARAM_RAW, 'the query'),
                'parameters' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the params'
                ),
                'limitfrom' => new external_value(PARAM_RAW, 'the limit from'),
                'limitnum' => new external_value(PARAM_RAW, 'the limit num')
            )
        );
    }

    public static function forum_get_forums_user_posted_in($coursewhere, $join, $parameters, $limitfrom, $limitnum)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::forum_get_forums_user_posted_in_parameters(), array(
            'coursewhere' => $coursewhere,
            'join' => $join,
            'parameters' => $parameters,
            'limitfrom' => $limitfrom,
            'limitnum' => $limitnum
        ));

        $join_param = $params['join'];
        $coursewhere_param = $params['coursewhere'];

        $sql = "SELECT f.id, cm.id AS cmid
              FROM {forum} f
              JOIN {course_modules} cm ON cm.instance = f.id
              JOIN {modules} m ON m.id = cm.module
              JOIN (
                  SELECT f.id
                    FROM {forum} f
                    {$join_param}
                   WHERE ff.userid = :userid
                GROUP BY f.id
                   ) j ON j.id = f.id
             WHERE m.name = :forum
                 {$coursewhere_param}";

        $arr = array();
        foreach ($params['parameters'] as $p) {
            $arr = array_merge($arr, array($p['name'] => $p['value']));
        }

        $limitfrom_param = $params['limitfrom'] == '' ? null : $params['limitfrom'];
        $limitnum_param = $params['limitnum'] == '' ? null : $params['limitnum'];

        $forums = $DB->get_records_sql($sql, $arr, $limitfrom_param, $limitnum_param);
        if (!$forums) {
            $forums = array();
        }

        $result = array();
        $result['forums'] = $forums;
        $result['warnings'] = $warnings;
        return $result;
    }

    public static function forum_get_forums_user_posted_in_returns()
    {
        return new external_single_structure(
            array(
                'forums' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'id' => new external_value(PARAM_INT, 'the forum id', VALUE_OPTIONAL),
                            'cmid' => new external_value(PARAM_INT, 'the id of course module', VALUE_OPTIONAL)
                        )
                    ), 'data of forum'
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function forum_get_courses_user_posted_in_parameters()
    {
        return new external_function_parameters(
            array(
                'sql' => new external_value(PARAM_RAW, 'the query sql'),
                'userid' => new external_value(PARAM_INT, 'the id of user'),
                'contextlevel' => new external_value(PARAM_INT, 'the context level'),
                'limitfrom' => new external_value(PARAM_RAW, 'the limit from'),
                'limitnum' => new external_value(PARAM_RAW, 'the limit num')
            )
        );
    }

    public static function forum_get_courses_user_posted_in($sql, $userid, $contextlevel, $limitfrom, $limitnum)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::forum_get_courses_user_posted_in_parameters(), array(
            'sql' => $sql,
            'userid' => $userid,
            'contextlevel' => $contextlevel,
            'limitfrom' => $limitfrom,
            'limitnum' => $limitnum
        ));

        $arr = array('userid' => $params['userid'], 'contextlevel' => $params['contextlevel']);
        $limitfrom_param = $params['limitfrom'] == '' ? null : $params['limitfrom'];
        $limitnum_param = $params['limitnum'] == '' ? null : $params['limitnum'];

        $courses = $DB->get_records_sql($params['sql'], $arr, $limitfrom_param, $limitnum_param);

        if (!$courses) {
            $courses = array();
        }

        $result = array();
        $result['courses'] = $courses;
        $result['warnings'] = $warnings;
        return $result;
    }

    public static function forum_get_courses_user_posted_in_returns()
    {
        return new external_single_structure(
            array(
                'courses' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'id' => new external_value(PARAM_INT, 'the id of course', VALUE_OPTIONAL)
                        )
                    )
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function forum_get_posts_user_posted_parameters()
    {
        return new external_function_parameters(
            array(
                'sql' => new external_value(PARAM_RAW, 'the query sql'),
                'parameters' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the params'
                ),
                'limitfrom' => new external_value(PARAM_RAW, 'the limit from'),
                'limitnum' => new external_value(PARAM_RAW, 'the limit num')
            )
        );
    }

    public static function forum_get_posts_user_posted($sql, $parameters, $limitfrom, $limitnum)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::forum_get_posts_user_posted_parameters(), array(
            'sql' => $sql,
            'parameters' => $parameters,
            'limitfrom' => $limitfrom,
            'limitnum' => $limitnum
        ));

        $arr = array();
        foreach ($params['parameters'] as $p) {
            $arr = array_merge($arr, array($p['name'] => $p['value']));
        }

        $limitfrom_param = $params['limitfrom'] == '' ? null : $params['limitfrom'];
        $limitnum_param = $params['limitnum'] == '' ? null : $params['limitnum'];

        $posts = $DB->get_records_sql($params['sql'], $arr, $limitfrom_param, $limitnum_param);

        if(!$posts) {
            $posts = array();
        }

        $result = array();
        $result['posts'] = $posts;
        $result['warnings'] = $warnings;
        return $result;
    }

    public static function forum_get_posts_user_posted_returns()
    {
        return new external_single_structure(
            array(
                'posts' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'id' => new external_value(PARAM_INT, 'the id', VALUE_OPTIONAL),
                            'discussion' => new external_value(PARAM_INT, 'the discussion id', VALUE_OPTIONAL),
                            'parent' => new external_value(PARAM_INT, 'the parent', VALUE_OPTIONAL),
                            'userid' => new external_value(PARAM_INT, 'the user id', VALUE_OPTIONAL),
                            'created' => new external_value(PARAM_INT, 'created', VALUE_OPTIONAL),
                            'modified' => new external_value(PARAM_INT, 'modified', VALUE_OPTIONAL),
                            'mailed' => new external_value(PARAM_INT, 'mailed', VALUE_OPTIONAL),
                            'subject' => new external_value(PARAM_RAW, 'the subject', VALUE_OPTIONAL),
                            'message' => new external_value(PARAM_RAW, 'the message', VALUE_OPTIONAL),
                            'messageformat' => new external_value(PARAM_INT, 'message format', VALUE_OPTIONAL),
                            'messagetrust' => new external_value(PARAM_INT, 'message trust', VALUE_OPTIONAL),
                            'attachment' => new external_value(PARAM_RAW, 'attachment', VALUE_OPTIONAL),
                            'totalscore' => new external_value(PARAM_INT, 'total score', VALUE_OPTIONAL),
                            'mailnow' => new external_value(PARAM_INT, 'mail now', VALUE_OPTIONAL),
                            'forum' => new external_value(PARAM_INT, 'the id of forum', VALUE_OPTIONAL),
                            'discussionname' => new external_value(PARAM_RAW, 'the name of discussion', VALUE_OPTIONAL),
                            'useridx' => new external_value(PARAM_INT, 'the id of user', VALUE_OPTIONAL),
                            'picture' => new external_value(PARAM_RAW, 'picture', VALUE_OPTIONAL),
                            'firstname' => new external_value(PARAM_RAW, 'first name', VALUE_OPTIONAL),
                            'lastname' => new external_value(PARAM_RAW, 'last name', VALUE_OPTIONAL),
                            'firstnamephonetic' => new external_value(PARAM_RAW, 'first name phonetic', VALUE_OPTIONAL),
                            'lastnamephonetic' => new external_value(PARAM_RAW, 'last name phonetic', VALUE_OPTIONAL),
                            'middlename' => new external_value(PARAM_RAW, 'middle name', VALUE_OPTIONAL),
                            'alternatename' => new external_value(PARAM_RAW, 'alternate name', VALUE_OPTIONAL),
                            'imagealt' => new external_value(PARAM_RAW, 'imagealt', VALUE_OPTIONAL),
                            'email' => new external_value(PARAM_RAW, 'email', VALUE_OPTIONAL),
                        )
                    ), 'post data'
                ),
                'warnings' => new external_warnings()
            )
        );
    }
}