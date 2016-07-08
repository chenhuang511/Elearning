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
class local_mod_wiki_external extends external_api
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
    public static function get_mod_wiki_by_id_parameters()
    {
        return new external_function_parameters(
            array('id' => new external_value(PARAM_INT, 'wiki id'))
        );
    }

    /**
     * Get Quiz object
     *
     * @param int $id id
     * @param array $options Options for filtering the results, used since Moodle 2.9
     * @return array
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
     */
    public static function get_mod_wiki_by_id($id)
    {
        global $CFG, $DB;

        $warnings = array();

        //validate parameter
        $params = self::validate_parameters(self::get_mod_wiki_by_id_parameters(),
            array('id' => $id));
        $result = array();

        $wiki = $DB->get_record('wiki', array('id' => $params['id']), '*', MUST_EXIST);
        if(!$wiki) {
            $wiki = new stdClass();
        }

        $result['wiki'] = $wiki;
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
    public static function get_mod_wiki_by_id_returns()
    {
        return new external_single_structure(
            array(
                'wiki' => new external_single_structure(
                    array(
                        'id' => new external_value(PARAM_INT, 'Standard Moodle primary key.'),
                        'course' => new external_value(PARAM_INT, 'Foreign key reference to the course this page is part of.', VALUE_OPTIONAL),
                        'name' => new external_value(PARAM_TEXT, 'Page name.'),
                        'intro' => new external_value(PARAM_RAW, 'Page introduction text.'),
                        'introformat' => new external_format_value(PARAM_INT, 'intro', VALUE_OPTIONAL),
                        'timecreated' => new external_value(PARAM_INT, 'Standard Moodle primary key.'),
                        'firstpagetitle' => new external_value(PARAM_TEXT, 'Foreign key reference to the course this quiz is part of.', VALUE_OPTIONAL),
                        'wikimode' => new external_value(PARAM_TEXT, 'Page name.', VALUE_OPTIONAL),
                        'defaultformat' => new external_value(PARAM_TEXT, 'Page introduction text.'),
                        'editbegin' => new external_format_value(PARAM_INT, 'Display or Not', VALUE_OPTIONAL),
                        'editend' => new external_value(PARAM_INT, 'Page name.'),
                        'timemodified' => new external_format_value(PARAM_INT, 'intro', VALUE_OPTIONAL)
                    )
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function get_wiki_by_parameters()
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

    public static function get_wiki_by($parameters, $sort, $mustexists)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::get_wiki_by_parameters(), array(
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
            $wiki = $DB->get_record("wiki", $arr);
        } else if ($params['mustexists'] === FALSE && $params['sort'] != '') {
            $wiki = $DB->get_record("wiki", $arr, $params['sort']);
        } else {
            $wiki = $DB->get_record("wiki", $arr, '*', MUST_EXIST);
        }

        if (!$wiki) {
            $wiki = new stdClass();
        }

        $result['wiki'] = $wiki;
        $result['warnings'] = $warnings;
        return $result;
    }

    public static function get_wiki_by_returns()
    {
        return new external_single_structure(
            array(
                'wiki' => new external_single_structure(
                    array(
                        'id' => new external_value(PARAM_INT, 'Standard Moodle primary key.'),
                        'course' => new external_value(PARAM_INT, 'Foreign key reference to the course this page is part of.', VALUE_OPTIONAL),
                        'name' => new external_value(PARAM_TEXT, 'Page name.'),
                        'intro' => new external_value(PARAM_RAW, 'Page introduction text.'),
                        'introformat' => new external_format_value(PARAM_INT, 'intro', VALUE_OPTIONAL),
                        'timecreated' => new external_value(PARAM_INT, 'Standard Moodle primary key.'),
                        'firstpagetitle' => new external_value(PARAM_TEXT, 'Foreign key reference to the course this quiz is part of.', VALUE_OPTIONAL),
                        'wikimode' => new external_value(PARAM_TEXT, 'Page name.', VALUE_OPTIONAL),
                        'defaultformat' => new external_value(PARAM_TEXT, 'Page introduction text.'),
                        'editbegin' => new external_format_value(PARAM_INT, 'Display or Not', VALUE_OPTIONAL),
                        'editend' => new external_value(PARAM_INT, 'Page name.'),
                        'timemodified' => new external_format_value(PARAM_INT, 'intro', VALUE_OPTIONAL)
                    )
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    /**
     * Hanv 24/05/2016
     * Return all the information about a quiz by quizid or by cm->instance from course_module
     *
     * @return external_function_parameters
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
     *
     */
    public static function get_mod_wiki_first_page_parameters()
    {
        return new external_function_parameters(
            array(
                'subwikiid' => new external_value(PARAM_INT, 'wiki id'),
                'module' => new external_value(PARAM_TEXT, 'wiki id', VALUE_OPTIONAL),
            )
        );
    }

    /**
     * Get Quiz object
     *
     * @param int $id id
     * @param array $options Options for filtering the results, used since Moodle 2.9
     * @return array
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
     */
    public static function get_mod_wiki_first_page($subwikid, $module = null)
    {
        global $CFG, $DB;

        //validate parameter
        $params = self::validate_parameters(self::get_mod_wiki_first_page_parameters(),
            array('subwikiid' => $id, 'module' => $module));

        $sql = "SELECT p.*
            FROM {wiki} w, {wiki_subwikis} s, {wiki_pages} p
            WHERE s.id = ? AND
            s.wikiid = w.id AND
            w.firstpagetitle = p.title AND
            p.subwikiid = s.id";
        return $DB->get_record_sql($sql, array($subwikid));
    }

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
     */
    public static function get_mod_wiki_first_page_returns()
    {
        return new external_single_structure(
            array(
                'id' => new external_value(PARAM_INT, 'Standard Moodle primary key.'),
                'subwikiid' => new external_value(PARAM_INT, 'Foreign key reference to the course this page is part of.'),
                'title' => new external_value(PARAM_TEXT, 'Page name.'),
                'userid' => new external_value(PARAM_INT, 'Page introduction text.'),
                'timecreated' => new external_value(PARAM_INT, 'Standard Moodle primary key.'),
                'timemodified' => new external_format_value(PARAM_INT, 'intro'),
                'timerendered' => new external_format_value(PARAM_INT, 'intro'),
                'pageviews' => new external_format_value(PARAM_INT, 'intro'),
                'readonly' => new external_format_value(PARAM_INT, 'intro'),
            )
        );
    }
}
