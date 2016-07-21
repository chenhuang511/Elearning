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
 * @package    local_grade
 * @category   external
 * @copyright  2009 Petr Skodak
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

require_once("$CFG->libdir/externallib.php");


/**
 * Forum external functions
 *
 * @package    local_grade
 * @category   external
 * @copyright  2011 Jerome Mouneyrac
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since Moodle 2.2
 */
class local_grade_external extends external_api
{
    public static function get_grade_settings_by_parameters()
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

    public static function get_grade_settings_by($parameters, $sort, $mustexists)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::get_grade_settings_by_parameters(), array(
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
            $setting = $DB->get_record("grade_settings", $arr);
        } else if ($params['mustexists'] === FALSE && $params['sort'] != '') {
            $setting = $DB->get_record("grade_settings", $arr, $params['sort']);
        } else {
            $setting = $DB->get_record("grade_settings", $arr, '*', MUST_EXIST);
        }

        if (!$setting) {
            $setting = new stdClass();
        }

        $result['setting'] = $setting;
        $result['warnings'] = $warnings;
        return $result;
    }

    public static function get_grade_settings_by_returns()
    {
        return new external_single_structure(
            array(
                'setting' => new external_single_structure(
                    array(
                        'id' => new external_value(PARAM_INT, 'the id'),
                        'courseid' => new external_value(PARAM_INT, 'the course id'),
                        'name' => new external_value(PARAM_RAW, 'the name'),
                        'value' => new external_value(PARAM_RAW, 'the value')
                    )
                ),
                'warnings' => new external_warnings()
            )
        );
    }
}
