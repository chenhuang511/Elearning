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
}