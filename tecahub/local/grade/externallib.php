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

    public static function get_grade_complete_course_parameters() {
        return new external_function_parameters(
            array(
                'userid' => new external_value(PARAM_INT, 'user id in remote'),
                'courseid' => new external_value(PARAM_INT, 'course id in remote'),
            )
        );
    }

    public static function get_grade_complete_course($userid, $courseid) {
        global $DB;

        //validate parameter
        $params = self::validate_parameters(self::get_grade_complete_course_parameters(),
            array('userid' => $userid, 'courseid' => $courseid));

        $itemid = $DB->get_field('grade_items', 'id', array('courseid' => $courseid, 'itemtype' => 'mod', 'itemmodule' => 'quiz'));
        $grade  = $DB->get_field('grade_grade', 'finalgrade', array('userid' => $userid, 'itemid' => $itemid));

        return $grade;
    }

    public static function get_grade_complete_course_returns() {
        return new external_value(PARAM_FLOAT, 'grade');
    }

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

    public static function get_list_grade_categories_by_parameters()
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

    public static function get_list_grade_categories_by($parameters, $sort, $limitfrom, $limitnum)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::get_list_grade_categories_by_parameters(), array(
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
            $categories = $DB->get_records("grade_categories", $arr);
        } else if (($params['limitfrom'] == 0 && $params['limitnum'] == 0) && $params['sort'] != '') {
            $categories = $DB->get_records("grade_categories", $arr, $params['sort']);
        } else {
            $categories = $DB->get_records("grade_categories", $arr, $params['sort'], '*', $params['limitfrom'], $params['limitnum']);
        }

        if (!$categories) {
            $categories = array();
        }

        $result['categories'] = $categories;
        $result['warnings'] = $warnings;

        return $result;
    }

    public static function get_list_grade_categories_by_returns()
    {
        return new external_single_structure(
            array(
                'categories' => new external_multiple_structure(
                    new external_single_structure(
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
                    ), 'grade category'
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

    public static function update_mdl_grade_sql_parameters()
    {
        return new external_function_parameters(
            array(
                'sql' => new external_value(PARAM_RAW, 'the mod name'),
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

    public static function update_mdl_grade_sql($sql, $data)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::update_mdl_grade_sql_parameters(), array(
            'sql' => $sql,
            'data' => $data
        ));

        $result = array();
        $arr = array();
        foreach ($params['data'] as $element) {
            $arr = array_merge($arr, array($element['value']));
        }

        $result['status'] = false;

        $transaction = $DB->start_delegated_transaction();

        $DB->execute($params['sql'], $arr);

        $transaction->allow_commit();

        $result['status'] = true;
        $result['warnings'] = $warnings;

        return $result;
    }

    public static function update_mdl_grade_sql_returns()
    {
        return new external_single_structure(
            array(
                'status' => new external_value(PARAM_BOOL, 'the status. true is successfull'),
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

    public static function get_count_mdl_grade_sql_parameters()
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

    public static function get_count_mdl_grade_sql($sql, $parameters)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::get_count_mdl_grade_sql_parameters(), array(
            'sql' => $sql,
            'parameters' => $parameters
        ));

        $arr = array();
        foreach ($params['parameters'] as $p) {
            $arr = array_merge($arr, array($p['value']));
        }

        $result = array();

        $count = $DB->count_records_sql($params['sql'], $arr);

        $result['count'] = $count;
        $result['warnings'] = $warnings;

        return $result;
    }

    public static function get_count_mdl_grade_sql_returns()
    {
        return new external_single_structure(
            array(
                'count' => new external_value(PARAM_INT, 'count'),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function get_field_mdl_grade_sql_parameters()
    {
        return new external_function_parameters(
            array(
                'sql' => new external_value(PARAM_RAW, 'query'),
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

    public static function get_field_mdl_grade_sql($sql, $parameters)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::get_field_mdl_grade_sql_parameters(), array(
            'sql' => $sql,
            'parameters' => $parameters
        ));

        $arr = array();
        foreach ($params['parameters'] as $p) {
            $arr = array_merge($arr, array($p['value']));
        }

        $result = array();

        $field = $DB->get_field_sql($params['sql'], $arr);

        $result['field'] = $field;
        $result['warnings'] = $warnings;

        return $result;

    }

    public static function get_field_mdl_grade_sql_returns()
    {
        return new external_single_structure(
            array(
                'field' => new external_value(PARAM_RAW, 'field'),
                'warnings' => new external_warnings()
            )
        );
    }

    /**
     * @return external_function_parameters
     */
    public static function get_list_grade_categories_raw_data_parameters() {
        return new external_function_parameters (
            array(
                'sql' => new external_value(PARAM_RAW, 'sql'),
                'param' => new  external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'name'),
                            'value' => new external_value(PARAM_RAW, 'value'),
                        )
                    )
                ),
                'pagestart' => new external_value(PARAM_INT, 'pagestart', VALUE_DEFAULT, 0),
                'pagesize' => new external_value(PARAM_INT, 'pagesize', VALUE_DEFAULT, 0),
            )
        );
    }

    /**
     * Get list grade categories as raw data
     * @param $sql
     * @param $param
     * @param $pagestart
     * @param $pagesize
     * @return array
     */
    public static function get_list_grade_categories_raw_data($sql, $param, $pagestart, $pagesize) {
        global $DB;

        //validate parameter
        $params = self::validate_parameters(self::get_list_grade_categories_raw_data_parameters(),
            array('sql' => $sql, 'param' => $param, 'pagestart' => $pagestart, 'pagesize' => $pagesize));

        $branch = array();
        foreach ($params['param'] as $element) {
            $branch[$element['name']] = $element['value'];
        }

        $rawdata = $DB->get_records_sql($params['sql'], $branch, $params['pagestart'], $params['pagesize']);
        return $rawdata;
    }

    /**
     * @return external_multiple_structure
     */
    public static function get_list_grade_categories_raw_data_returns() {
        return new external_multiple_structure(
            new external_single_structure(
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
            ), 'grade category'
        );
    }

    public static function get_sum_grader_report_by_sql_query_parameters() {
        return new external_function_parameters (
            array(
                'sql' => new external_value(PARAM_RAW, 'sql'),
                'param' => new  external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'name'),
                            'value' => new external_value(PARAM_RAW, 'value'),
                        )
                    )
                ),
            )
        );
    }

    public static function get_sum_grader_report_by_sql_query($sql, $param) {
        global $DB;

        //validate parameter
        $params = self::validate_parameters(self::get_sum_grader_report_by_sql_query_parameters(),
            array('sql' => $sql, 'param' => $param));

        $branch = array();
        foreach ($params['param'] as $element) {
            $branch[$element['name']] = $element['value'];
        }

        $rawdata = $DB->get_records_sql($params['sql'], $branch);
        return $rawdata;
    }

    public static function get_sum_grader_report_by_sql_query_returns() {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'itemid' => new external_value(PARAM_INT, 'the grade item id'),
                    'sum' => new external_value(PARAM_FLOAT, 'sum'),
                )
            ), 'grader sum'
        );
    }

    public static function get_count_grader_report_by_sql_query_parameters() {
        return new external_function_parameters (
            array(
                'sql' => new external_value(PARAM_RAW, 'sql'),
                'param' => new  external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'name'),
                            'value' => new external_value(PARAM_RAW, 'value'),
                        )
                    )
                ),
            )
        );
    }

    public static function get_count_grader_report_by_sql_query($sql, $param) {
        global $DB;

        //validate parameter
        $params = self::validate_parameters(self::get_count_grader_report_by_sql_query_parameters(),
            array('sql' => $sql, 'param' => $param));

        $branch = array();
        foreach ($params['param'] as $element) {
            $branch[$element['name']] = $element['value'];
        }

        $rawdata = $DB->get_records_sql($params['sql'], $branch);
        return $rawdata;
    }

    public static function get_count_grader_report_by_sql_query_returns() {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'id' => new external_value(PARAM_INT, 'the grade item id'),
                    'count' => new external_value(PARAM_FLOAT, 'count'),
                )
            ), 'grader count'
        );
    }
}
