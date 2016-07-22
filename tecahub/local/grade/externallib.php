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

    public static function get_grade_categories_by_parameters()
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

    public static function get_grade_categories_by($parameters, $sort, $mustexists)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::get_grade_categories_by_parameters(), array(
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
            $category = $DB->get_record("grade_categories", $arr);
        } else if ($params['mustexists'] === FALSE && $params['sort'] != '') {
            $category = $DB->get_record("grade_categories", $arr, $params['sort']);
        } else {
            $category = $DB->get_record("grade_categories", $arr, '*', MUST_EXIST);
        }

        if (!$category) {
            $category = new stdClass();
        }

        $result['category'] = $category;
        $result['warnings'] = $warnings;
        return $result;
    }

    public static function get_grade_categories_by_returns()
    {
        return new external_single_structure(
            array(
                'category' => new external_single_structure(
                    array(
                        'id' => new external_value(PARAM_INT, 'the id'),
                        'courseid' => new external_value(PARAM_INT, 'the course id'),
                        'parent' => new external_value(PARAM_INT, 'the parent'),
                        'depth' => new external_value(PARAM_INT, 'the depth'),
                        'path' => new external_value(PARAM_RAW, 'the path'),
                        'fullname' => new external_value(PARAM_RAW, 'the fullname'),
                        'aggregation' => new external_value(PARAM_INT, 'the keep high'),
                        'keephigh' => new external_value(PARAM_INT, 'the depth'),
                        'droplow' => new external_value(PARAM_INT, 'the drop low'),
                        'aggregateonlygraded' => new external_value(PARAM_INT, 'the aggregate only graded'),
                        'aggregateoutcomes' => new external_value(PARAM_INT, 'the aggregate out comes'),
                        'timecreated' => new external_value(PARAM_INT, 'the time created'),
                        'timemodified' => new external_value(PARAM_INT, 'the time modified'),
                        'hidden' => new external_value(PARAM_INT, 'the hidden')
                    )
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function get_list_grade_settings_by_parameters()
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

    public static function get_list_grade_settings_by($parameters, $sort, $limitfrom, $limitnum)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::get_list_grade_settings_by_parameters(), array(
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
            $settings = $DB->get_records("grade_settings", $arr);
        } else if (($params['limitfrom'] == 0 && $params['limitnum'] == 0) && $params['sort'] != '') {
            $settings = $DB->get_records("grade_settings", $arr, $params['sort']);
        } else {
            $settings = $DB->get_records("grade_settings", $arr, $params['sort'], '*', $params['limitfrom'], $params['limitnum']);
        }

        if (!$settings) {
            $settings = array();
        }

        $result['settings'] = $settings;
        $result['warnings'] = $warnings;

        return $result;
    }

    public static function get_list_grade_settings_by_returns()
    {
        return new external_single_structure(
            array(
                'settings' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'id' => new external_value(PARAM_INT, 'the id'),
                            'courseid' => new external_value(PARAM_INT, 'the course id'),
                            'name' => new external_value(PARAM_RAW, 'the name'),
                            'value' => new external_value(PARAM_RAW, 'the value')
                        )
                    ), 'grade settings'
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function save_mdl_grade_parameters()
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

    public static function save_mdl_grade($modname, $data)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::save_mdl_grade_parameters(), array(
            'modname' => $modname,
            'data' => $data
        ));

        $obj = new stdClass();

        foreach ($params['data'] as $element) {
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

    public static function save_mdl_grade_returns()
    {
        return new external_single_structure(
            array(
                'newid' => new external_value(PARAM_INT, 'the new id'),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function update_mdl_grade_parameters()
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

    public static function update_mdl_grade($modname, $id, $data)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::update_mdl_grade_parameters(), array(
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

    public static function update_mdl_grade_returns()
    {
        return new external_single_structure(
            array(
                'id' => new external_value(PARAM_INT, 'the id'),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function delete_mdl_grade_parameters()
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

    public static function delete_mdl_grade($modname, $parameters)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::delete_mdl_grade_parameters(), array(
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

    public static function delete_mdl_grade_returns()
    {
        return new external_single_structure(
            array(
                'status' => new external_value(PARAM_BOOL, 'bool: true if delete success'),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function check_record_grade_exists_parameters()
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

    public static function check_record_grade_exists($modname, $parameters)
    {
        global $DB;

        $warnings = array();

        $params = self::validate_parameters(self::check_record_grade_exists_parameters(), array(
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

    public static function check_record_grade_exists_returns()
    {
        return new external_single_structure(
            array(
                'status' => new external_value(PARAM_BOOL, 'status'),
                'warnings' => new external_warnings()
            )
        );
    }
}