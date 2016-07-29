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
                        'id' => new external_value(PARAM_INT, 'the forum id'),
                        'course' => new external_value(PARAM_INT, 'the course id'),
                        'type' => new external_value(PARAM_RAW, 'type'),
                        'name' => new external_value(PARAM_RAW, 'forum name'),
                        'intro' => new external_value(PARAM_RAW, 'Page introduction text.'),
                        'introformat' => new external_format_value(PARAM_INT, 'intro format', VALUE_OPTIONAL),
                        'assessed' => new external_value(PARAM_INT, 'assessed'),
                        'assesstimestart' => new external_value(PARAM_INT, 'assess time start'),
                        'assesstimefinish' => new external_value(PARAM_INT, 'assess time finish'),
                        'scale' => new external_format_value(PARAM_INT, 'scale'),
                        'maxbytes' => new external_value(PARAM_INT, 'max bytes'),
                        'maxattachments' => new external_value(PARAM_INT, 'max attachments'),
                        'forcesubscribe' => new external_value(PARAM_INT, 'force subscribe'),
                        'trackingtype' => new external_value(PARAM_INT, 'tracking type'),
                        'rsstype' => new external_value(PARAM_INT, 'rss type'),
                        'rssarticles' => new external_value(PARAM_INT, 'rss articles'),
                        'timemodified' => new external_format_value(PARAM_INT, 'time modified'),
                        'warnafter' => new external_value(PARAM_INT, 'warn after'),
                        'blockafter' => new external_value(PARAM_INT, 'block after'),
                        'blockperiod' => new external_value(PARAM_INT, 'block period'),
                        'completiondiscussions' => new external_value(PARAM_INT, 'completion discussions'),
                        'completionreplies' => new external_value(PARAM_INT, 'completion replies'),
                        'completionposts' => new external_value(PARAM_INT, 'completion posts'),
                        'displaywordcount' => new external_value(PARAM_INT, 'display word count')
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
                        'id' => new external_value(PARAM_INT, 'the id'),
                        'course' => new external_value(PARAM_INT, 'the course id'),
                        'forum' => new external_value(PARAM_INT, 'the forum id'),
                        'name' => new external_value(PARAM_RAW, 'the name'),
                        'firstpost' => new external_value(PARAM_INT, 'first post'),
                        'userid' => new external_value(PARAM_INT, 'the user id'),
                        'groupid' => new external_value(PARAM_INT, 'the group id'),
                        'assessed' => new external_value(PARAM_INT, 'the assessed'),
                        'timemodified' => new external_value(PARAM_INT, 'time modified'),
                        'usermodified' => new external_value(PARAM_INT, 'user modified'),
                        'timestart' => new external_value(PARAM_INT, 'time start'),
                        'timeend' => new external_value(PARAM_INT, 'time end'),
                        'pinned' => new external_value(PARAM_INT, 'pinned')
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

        if ($params['mustexists'] === FALSE) {
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
                        'id' => new external_value(PARAM_INT, 'the id'),
                        'forum' => new external_value(PARAM_INT, 'the forum id'),
                        'userid' => new external_value(PARAM_INT, 'the user id'),
                        'discussion' => new external_value(PARAM_INT, 'the discussion id'),
                        'preference' => new external_value(PARAM_INT, 'the preference')
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
                        'id' => new external_value(PARAM_INT, 'the id'),
                        'discussion' => new external_value(PARAM_INT, 'the discussion id'),
                        'parent' => new external_value(PARAM_INT, 'the parent'),
                        'userid' => new external_value(PARAM_INT, 'the user id'),
                        'created' => new external_value(PARAM_INT, 'created'),
                        'modified' => new external_value(PARAM_INT, 'modified'),
                        'mailed' => new external_value(PARAM_INT, 'mailed'),
                        'subject' => new external_value(PARAM_RAW, 'the subject'),
                        'message' => new external_value(PARAM_RAW, 'the message'),
                        'messageformat' => new external_value(PARAM_INT, 'message format'),
                        'messagetrust' => new external_value(PARAM_INT, 'message trust'),
                        'attachment' => new external_value(PARAM_RAW, 'attachment'),
                        'totalscore' => new external_value(PARAM_INT, 'total score'),
                        'mailnow' => new external_value(PARAM_INT, 'mail now')
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
                        'id' => new external_value(PARAM_INT, 'the id'),
                        'userid' => new external_value(PARAM_INT, 'the user id'),
                        'forumid' => new external_value(PARAM_INT, 'created')
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
                        'id' => new external_value(PARAM_INT, 'the id'),
                        'userid' => new external_value(PARAM_INT, 'the user id'),
                        'forum' => new external_value(PARAM_INT, 'the forum')
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
                        'id' => new external_value(PARAM_INT, 'the id'),
                        'courseid' => new external_value(PARAM_INT, 'the course id'),
                        'userid' => new external_value(PARAM_INT, 'the user id'),
                        'name' => new external_value(PARAM_RAW, 'the name'),
                        'scale' => new external_value(PARAM_RAW, 'the scale'),
                        'description' => new external_value(PARAM_RAW, 'the description'),
                        'descriptionformat' => new external_value(PARAM_INT, 'description format'),
                        'timemodified' => new external_value(PARAM_INT, 'time modified')
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
                            'id' => new external_value(PARAM_INT, 'the forum id'),
                            'course' => new external_value(PARAM_INT, 'the course id'),
                            'type' => new external_value(PARAM_RAW, 'type'),
                            'name' => new external_value(PARAM_RAW, 'forum name'),
                            'intro' => new external_value(PARAM_RAW, 'Page introduction text.'),
                            'introformat' => new external_format_value(PARAM_INT, 'intro format', VALUE_OPTIONAL),
                            'assessed' => new external_value(PARAM_INT, 'assessed'),
                            'assesstimestart' => new external_value(PARAM_INT, 'assess time start'),
                            'assesstimefinish' => new external_value(PARAM_INT, 'assess time finish'),
                            'scale' => new external_format_value(PARAM_INT, 'scale'),
                            'maxbytes' => new external_value(PARAM_INT, 'max bytes'),
                            'maxattachments' => new external_value(PARAM_INT, 'max attachments'),
                            'forcesubscribe' => new external_value(PARAM_INT, 'force subscribe'),
                            'trackingtype' => new external_value(PARAM_INT, 'tracking type'),
                            'rsstype' => new external_value(PARAM_INT, 'rss type'),
                            'rssarticles' => new external_value(PARAM_INT, 'rss articles'),
                            'timemodified' => new external_format_value(PARAM_INT, 'time modified'),
                            'warnafter' => new external_value(PARAM_INT, 'warn after'),
                            'blockafter' => new external_value(PARAM_INT, 'block after'),
                            'blockperiod' => new external_value(PARAM_INT, 'block period'),
                            'completiondiscussions' => new external_value(PARAM_INT, 'completion discussions'),
                            'completionreplies' => new external_value(PARAM_INT, 'completion replies'),
                            'completionposts' => new external_value(PARAM_INT, 'completion posts'),
                            'displaywordcount' => new external_value(PARAM_INT, 'display word count')
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
                            'id' => new external_value(PARAM_INT, 'the id'),
                            'course' => new external_value(PARAM_INT, 'the course id'),
                            'forum' => new external_value(PARAM_INT, 'the forum id'),
                            'name' => new external_value(PARAM_RAW, 'the name'),
                            'firstpost' => new external_value(PARAM_INT, 'first post'),
                            'userid' => new external_value(PARAM_INT, 'the user id'),
                            'groupid' => new external_value(PARAM_INT, 'the group id'),
                            'assessed' => new external_value(PARAM_INT, 'the assessed'),
                            'timemodified' => new external_value(PARAM_INT, 'time modified'),
                            'usermodified' => new external_value(PARAM_INT, 'user modified'),
                            'timestart' => new external_value(PARAM_INT, 'time start'),
                            'timeend' => new external_value(PARAM_INT, 'time end'),
                            'pinned' => new external_value(PARAM_INT, 'pinned')
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

        $sql = "SELECT fd.* FROM {forum_discussions} fd 
                LEFT JOIN {user} u ON u.id = fd.userid ";

        $host = $DB->get_record('mnet_host', array('ip_address' => $params['hostip']), '*', MUST_EXIST);

        if (!$host) {
            $warnings['message'] = "not found host";
        }

        $arr = array();
        foreach ($params['parameters'] as $p) {
            $columnname = "fd." . $p['name'];
            $sql .= "WHERE $columnname = ? AND ";
            $arr = array_merge($arr, array($p['value']));
        }

        $arr = array_merge($arr, array($host->id));

        $sql .= " fd.userid IN (SELECT id FROM {user} WHERE mnethostid = ?)";

        if ($params['sort'] != '') {
            $orderby = $params['sort'];
            $sql .= " ORDER BY $orderby";
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
                            'id' => new external_value(PARAM_INT, 'the id'),
                            'discussion' => new external_value(PARAM_INT, 'the discussion id'),
                            'parent' => new external_value(PARAM_INT, 'the parent'),
                            'userid' => new external_value(PARAM_INT, 'the user id'),
                            'created' => new external_value(PARAM_INT, 'created'),
                            'modified' => new external_value(PARAM_INT, 'modified'),
                            'mailed' => new external_value(PARAM_INT, 'mailed'),
                            'subject' => new external_value(PARAM_RAW, 'the subject'),
                            'message' => new external_value(PARAM_RAW, 'the message'),
                            'messageformat' => new external_value(PARAM_INT, 'message format'),
                            'messagetrust' => new external_value(PARAM_INT, 'message trust'),
                            'attachment' => new external_value(PARAM_RAW, 'attachment'),
                            'totalscore' => new external_value(PARAM_INT, 'total score'),
                            'mailnow' => new external_value(PARAM_INT, 'mail now')
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
                            'id' => new external_value(PARAM_INT, 'the id'),
                            'userid' => new external_value(PARAM_INT, 'the user id'),
                            'forumid' => new external_value(PARAM_INT, 'the forumid'),
                            'discussionid' => new external_value(PARAM_INT, 'the discussion id'),
                            'postid' => new external_value(PARAM_INT, 'the post id'),
                            'firstread' => new external_value(PARAM_INT, 'first read'),
                            'lastread' => new external_value(PARAM_INT, 'last read')
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
                            'id' => new external_value(PARAM_INT, 'the id'),
                            'forum' => new external_value(PARAM_INT, 'the forum id'),
                            'userid' => new external_value(PARAM_INT, 'the user id'),
                            'discussion' => new external_value(PARAM_INT, 'the discussion id'),
                            'preference' => new external_value(PARAM_INT, 'the preference')
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
            $warnings['message'] = "Not found data record";
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
            $warnings['message'] = "Not found data record";
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
                            'id' => new external_value(PARAM_INT, 'the id of post'),
                            'subject' => new external_value(PARAM_RAW, 'the subject of post'),
                            'modified' => new external_value(PARAM_INT, 'the modified of post'),
                            'discussion' => new external_value(PARAM_INT, 'the discussion of post'),
                            'userid' => new external_value(PARAM_INT, 'the userid of post'),
                            'name' => new external_value(PARAM_RAW, 'the name of discussion'),
                            'timemodified' => new external_value(PARAM_INT, 'the time modified of discussion'),
                            'usermodified' => new external_value(PARAM_INT, 'the user modified of discussion'),
                            'groupid' => new external_value(PARAM_INT, 'the groupid of discussion'),
                            'timestart' => new external_value(PARAM_INT, 'the time start of discussion'),
                            'timeend' => new external_value(PARAM_INT, 'the time end of discussion'),
                            'pinned' => new external_value(PARAM_INT, 'the pinned of discussion'),
                            'firstnamephonetic' => new external_value(PARAM_RAW, 'the first name phonetic of user'),
                            'lastnamephonetic' => new external_value(PARAM_RAW, 'the last name phonetic of user'),
                            'middlename' => new external_value(PARAM_RAW, 'the middle name of user'),
                            'alternatename' => new external_value(PARAM_RAW, 'the alternate name of user'),
                            'firstname' => new external_value(PARAM_RAW, 'the first name of user'),
                            'lastname' => new external_value(PARAM_RAW, 'the last name of user'),
                            'email' => new external_value(PARAM_RAW, 'the email of user'),
                            'picture' => new external_value(PARAM_RAW, 'the email of user'),
                            'imagealt' => new external_value(PARAM_RAW, 'the email of user'),
                            'umfirstnamephonetic' => new external_value(PARAM_RAW, 'the first name phonetic of user modified'),
                            'umlastnamephonetic' => new external_value(PARAM_RAW, 'the last name phonetic of user modified'),
                            'ummiddlename' => new external_value(PARAM_RAW, 'the middle name of user modified'),
                            'umalternatename' => new external_value(PARAM_RAW, 'the alternate name of user modified'),
                            'umfirstname' => new external_value(PARAM_RAW, 'the first name of user modified'),
                            'umlastname' => new external_value(PARAM_RAW, 'the last name of user modified'),
                            'umemail' => new external_value(PARAM_RAW, 'the email of user modified'),
                            'umpicture' => new external_value(PARAM_RAW, 'the email of user modified'),
                            'umimagealt' => new external_value(PARAM_RAW, 'the email of user modified'),
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
                'sql' => new external_value(PARAM_RAW, 'the query sql'),
                'parameters' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'param name'),
                            'value' => new external_value(PARAM_RAW, 'param value'),
                        )
                    ), 'the params'
                ),
                'limitfrom' => new external_value(PARAM_INT, 'the limit from'),
                'limitnum' => new external_value(PARAM_INT, 'the limit num')
            )
        );
    }

    public static function forum_count_discussion_replies_sql($sql, $parameters, $limitfrom, $limitnum)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::forum_count_discussion_replies_sql_parameters(), array(
            'sql' => $sql,
            'parameters' => $parameters,
            'limitfrom' => $limitfrom,
            'limitnum' => $limitnum
        ));

        $arr = array();
        foreach ($params['parameters'] as $p) {
            $arr = array_merge($arr, array($p['value']));
        }

        $result = array();

        if ($params['limitfrom'] == 0 && $params['limitnum'] == 0) {
            $replies = $DB->get_records_sql($params['sql'], $arr);
        } else {
            $replies = $DB->get_records_sql($params['sql'], $arr, $params['limitfrom'], $params['limitnum']);
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
                            'discussion' => new external_value(PARAM_INT, 'the discussion id'),
                            'replies' => new external_value(PARAM_INT, 'the count of reply'),
                            'lastpostid' => new external_value(PARAM_INT, 'the last post id'),
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
                        'id' => new external_value(PARAM_INT, 'the id'),
                        'discussion' => new external_value(PARAM_INT, 'the discussion id'),
                        'parent' => new external_value(PARAM_INT, 'the parent'),
                        'userid' => new external_value(PARAM_INT, 'the user id'),
                        'created' => new external_value(PARAM_INT, 'created'),
                        'modified' => new external_value(PARAM_INT, 'modified'),
                        'mailed' => new external_value(PARAM_INT, 'mailed'),
                        'subject' => new external_value(PARAM_RAW, 'the subject'),
                        'message' => new external_value(PARAM_RAW, 'the message'),
                        'messageformat' => new external_value(PARAM_INT, 'message format'),
                        'messagetrust' => new external_value(PARAM_INT, 'message trust'),
                        'attachment' => new external_value(PARAM_RAW, 'attachment'),
                        'totalscore' => new external_value(PARAM_INT, 'total score'),
                        'mailnow' => new external_value(PARAM_INT, 'mail now'),
                        'forum' => new external_value(PARAM_INT, 'the id of forum'),
                        'firstnamephonetic' => new external_value(PARAM_RAW, 'the first name phonetic of user'),
                        'lastnamephonetic' => new external_value(PARAM_RAW, 'the last name phonetic of user'),
                        'middlename' => new external_value(PARAM_RAW, 'the middle name of user'),
                        'alternatename' => new external_value(PARAM_RAW, 'the alternate name of user'),
                        'firstname' => new external_value(PARAM_RAW, 'the first name of user'),
                        'lastname' => new external_value(PARAM_RAW, 'the last name of user'),
                        'email' => new external_value(PARAM_RAW, 'the email of user'),
                        'picture' => new external_value(PARAM_RAW, 'the email of user'),
                        'imagealt' => new external_value(PARAM_RAW, 'the email of user'),
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

    public static function forum_get_discussion_neighbours_sql($sql, $parameters, $strictness)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::forum_get_discussion_neighbours_sql_parameters(), array(
            'sql' => $sql,
            'parameters' => $parameters,
            'strictness' => $strictness
        ));

        $arr = array();
        foreach ($params['parameters'] as $p) {
            $arr = array_merge($arr, array($p['name'] => $p['value']));
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
                        'id' => new external_value(PARAM_INT, 'the id of discussion'),
                        'name' => new external_value(PARAM_RAW, 'the name of discussion'),
                        'timemodified' => new external_value(PARAM_INT, 'the time modified'),
                        'groupid' => new external_value(PARAM_INT, 'the group id'),
                        'timestart' => new external_value(PARAM_INT, 'time start'),
                        'timeend' => new external_value(PARAM_INT, 'time end')
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

        if ($params['hostip'] != '') {
            $host = $DB->get_record('mnet_host', array('ip_address' => $params['hostip']), '*', MUST_EXIST);
        }

        if (isset($host) && $host) {
            $sql .= " AND p.userid IN (SELECT id FROM {user} WHERE mnethostid = ?)";
            $arr = array_merge($arr, array($host->id));
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
                            'id' => new external_value(PARAM_INT, 'the id'),
                            'discussion' => new external_value(PARAM_INT, 'the discussion id'),
                            'parent' => new external_value(PARAM_INT, 'the parent'),
                            'userid' => new external_value(PARAM_INT, 'the user id'),
                            'created' => new external_value(PARAM_INT, 'created'),
                            'modified' => new external_value(PARAM_INT, 'modified'),
                            'mailed' => new external_value(PARAM_INT, 'mailed'),
                            'subject' => new external_value(PARAM_RAW, 'the subject'),
                            'message' => new external_value(PARAM_RAW, 'the message'),
                            'messageformat' => new external_value(PARAM_INT, 'message format'),
                            'messagetrust' => new external_value(PARAM_INT, 'message trust'),
                            'attachment' => new external_value(PARAM_RAW, 'attachment'),
                            'totalscore' => new external_value(PARAM_INT, 'total score'),
                            'mailnow' => new external_value(PARAM_INT, 'mail now'),
                            'firstnamephonetic' => new external_value(PARAM_RAW, 'the first name phonetic of user'),
                            'lastnamephonetic' => new external_value(PARAM_RAW, 'the last name phonetic of user'),
                            'middlename' => new external_value(PARAM_RAW, 'the middle name of user'),
                            'alternatename' => new external_value(PARAM_RAW, 'the alternate name of user'),
                            'firstname' => new external_value(PARAM_RAW, 'the first name of user'),
                            'lastname' => new external_value(PARAM_RAW, 'the last name of user'),
                            'email' => new external_value(PARAM_RAW, 'the email of user'),
                            'picture' => new external_value(PARAM_RAW, 'the email of user'),
                            'imagealt' => new external_value(PARAM_RAW, 'the email of user'),
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

    public static function forum_search_posts_by_hostip_parameters()
    {
        return new external_function_parameters(
            array(
                'hubuserid' => new external_value(PARAM_INT, 'hubuserid'),
                'searchterms' => new external_value(PARAM_RAW, 'searchterms'),
                'courseid' => new external_value(PARAM_RAW, 'course id'),
                'limitfrom' => new external_value(PARAM_INT, 'limitfrom'),
                'limitnum' => new external_value(PARAM_INT, 'limitnum'),
                'extrasql' => new external_value(PARAM_RAW, 'extrasql')
            )
        );
    }

    public static function forum_search_posts_by_hostip($hubuserid, $searchterms, $courseid, $limitfrom, $limitnum, $extrasql)
    {
        global $CFG, $DB;
        require_once($CFG->libdir.'/searchlib.php');

        $params = self::validate_parameters(self::forum_search_posts_by_hostip_parameters,
            array(
                'hubuserid' => $hubuserid,
                'searchterms' => $searchterms,
                'courseid' => $courseid,
                'limitfrom' => $limitfrom,
                'limitnum' => $limitnum,
                'extrasql'=> $extrasql
            )
        );
        $result = array();

        $forums = forum_get_readable_forums($hubuserid, $courseid);
        if (count($forums) == 0) {
            $totalcount = 0;
            return false;
        }

        $now = round(time(), -2); // db friendly

        $fullaccess = array();
        $where = array();
        $pars = array();

        foreach ($forums as $forumid => $forum) {
            $select = array();

            if (!$forum->viewhiddentimedposts) {
                $select[] = "(d.userid = :userid{$forumid} OR (d.timestart < :timestart{$forumid} AND (d.timeend = 0 OR d.timeend > :timeend{$forumid})))";
                $pars = array_merge($pars, array('userid'.$forumid=>$hubuserid, 'timestart'.$forumid=>$now, 'timeend'.$forumid=>$now));
            }

            $cm = $forum->cm;
            $context = $forum->context;

            if ($forum->type == 'qanda'
                && !has_capability('mod/forum:viewqandawithoutposting', $context)) {
                if (!empty($forum->onlydiscussions)) {
                    list($discussionid_sql, $discussionid_params) = $DB->get_in_or_equal($forum->onlydiscussions, SQL_PARAMS_NAMED, 'qanda'.$forumid.'_');
                    $pars = array_merge($pars, $discussionid_params);
                    $select[] = "(d.id $discussionid_sql OR p.parent = 0)";
                } else {
                    $select[] = "p.parent = 0";
                }
            }

            if (!empty($forum->onlygroups)) {
                list($groupid_sql, $groupid_params) = $DB->get_in_or_equal($forum->onlygroups, SQL_PARAMS_NAMED, 'grps'.$forumid.'_');
                $pars = array_merge($pars, $groupid_params);
                $select[] = "d.groupid $groupid_sql";
            }

            if ($select) {
                $selects = implode(" AND ", $select);
                $where[] = "(d.forum = :forum{$forumid} AND $selects)";
                $pars['forum'.$forumid] = $forumid;
            } else {
                $fullaccess[] = $forumid;
            }
        }

        if ($fullaccess) {
            list($fullid_sql, $fullid_params) = $DB->get_in_or_equal($fullaccess, SQL_PARAMS_NAMED, 'fula');
            $pars = array_merge($pars, $fullid_params);
            $where[] = "(d.forum $fullid_sql)";
        }

        $selectdiscussion = "(".implode(" OR ", $where).")";

        $messagesearch = '';
        $searchstring = '';

        // Need to concat these back together for parser to work.
        foreach($searchterms as $searchterm){
            if ($searchstring != '') {
                $searchstring .= ' ';
            }
            $searchstring .= $searchterm;
        }

        // We need to allow quoted strings for the search. The quotes *should* be stripped
        // by the parser, but this should be examined carefully for security implications.
        $searchstring = str_replace("\\\"","\"",$searchstring);
        $parser = new search_parser();
        $lexer = new search_lexer($parser);

        if ($lexer->parse($searchstring)) {
            $parsearray = $parser->get_parsed_array();
            list($messagesearch, $msparams) = search_generate_SQL($parsearray, 'p.message', 'p.subject',
                'p.userid', 'u.id', 'u.firstname',
                'u.lastname', 'p.modified', 'd.forum');
            $pars = array_merge($pars, $msparams);
        }

        $fromsql = "{forum_posts} p,
                  {forum_discussions} d,
                  {user} u";

        $selectsql = " $messagesearch
               AND p.discussion = d.id
               AND p.userid = u.id
               AND $selectdiscussion
                   $extrasql";

        $countsql = "SELECT COUNT(*)
                   FROM $fromsql
                  WHERE $selectsql";

        $allnames = get_all_user_name_fields(true, 'u');
        $searchsql = "SELECT p.*,
                         d.forum,
                         $allnames,
                         u.email,
                         u.picture,
                         u.imagealt
                    FROM $fromsql
                   WHERE $selectsql
                ORDER BY p.modified DESC";

        $result['totalcount'] = $DB->count_records_sql($countsql, $pars);
        if (!$result['totalcount']){
            $result['totalcount'] = array();
        }

        $result['ret']->get_records_sql($searchsql, $pars, $limitfrom, $limitnum);
        if (!$result['ret']){
            $result['ret'] = array();
        }

        return $result;
    }

    public static function forum_search_posts_by_hostip_returns()
    {

        return new external_single_structure(
            array(
                'ret' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'id' => new external_value(PARAM_INT, 'the id'),
                            'discussion' => new external_value(PARAM_INT, 'the discussion id'),
                            'parent' => new external_value(PARAM_INT, 'the parent'),
                            'userid' => new external_value(PARAM_INT, 'the user id'),
                            'created' => new external_value(PARAM_INT, 'created'),
                            'modified' => new external_value(PARAM_INT, 'modified'),
                            'mailed' => new external_value(PARAM_INT, 'mailed'),
                            'subject' => new external_value(PARAM_RAW, 'the subject'),
                            'message' => new external_value(PARAM_RAW, 'the message'),
                            'messageformat' => new external_value(PARAM_INT, 'message format'),
                            'messagetrust' => new external_value(PARAM_INT, 'message trust'),
                            'attachment' => new external_value(PARAM_RAW, 'attachment'),
                            'totalscore' => new external_value(PARAM_INT, 'total score'),
                            'mailnow' => new external_value(PARAM_INT, 'mail now'),
                            'forum' => new external_value(PARAM_INT, 'the forum'),
                            'firstnamephonetic' => new external_value(PARAM_RAW, 'the firstnamephonetic'),
                            'lastnamephonetic' => new external_value(PARAM_RAW, 'the lastnamephonetic'),
                            'middlename' => new external_value(PARAM_RAW, 'the middlename'),
                            'alternatename' => new external_value(PARAM_RAW, 'the alternatename'),
                            'firstname' => new external_value(PARAM_RAW, 'the firstname'),
                            'lastname' => new external_value(PARAM_RAW, 'the lastname'),
                            'email' => new external_value(PARAM_RAW, 'the email'),
                            'picture' => new external_value(PARAM_INT, 'the picture'),
                            'imagealt' => new external_value(PARAM_RAW, 'the imagealt'),
                        )
                    )
                ),
                'totalcount' => new external_value(PARAM_INT, 'The totalcount')
            )
        );
    }


}