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
 * @package    local_mod_question
 * @category   external
 * @copyright  2009 Petr Skodak
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

require_once("$CFG->libdir/externallib.php");


/**
 * Forum external functions
 *
 * @package    local_mod_question
 * @category   external
 * @copyright  2011 Jerome Mouneyrac
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since Moodle 2.2
 */
class local_mod_question_external extends external_api
{
    public static function get_list_question_categories_parameters()
    {
        return new external_function_parameters(
            array(
                'sort' => new external_value(PARAM_TEXT, 'the sort')
            )
        );
    }

    public static function get_list_question_categories($sort)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::get_list_question_categories_parameters(), array(
            'sort' => $sort
        ));

        $sql = "SELECT qc.*, c.contextlevel, c.instanceid FROM m_question_categories qc LEFT JOIN m_context c ON qc.contextid = c.id";

        $categories = $DB->get_records_sql($sql);

        if (!$categories) {
            $categories = array();
        }

        $result = array();
        $result['categories'] = $categories;
        $result['warnings'] = $warnings;
        return $result;
    }

    public static function get_list_question_categories_returns()
    {
        return new external_single_structure(
            array(
                'categories' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'id' => new external_value(PARAM_INT, 'the id of question categories', VALUE_OPTIONAL),
                            'name' => new external_value(PARAM_TEXT, 'the name of question categories', VALUE_OPTIONAL),
                            'contextid' => new external_value(PARAM_INT, 'the id of context', VALUE_OPTIONAL),
                            'info' => new external_value(PARAM_RAW, 'the info of question categories', VALUE_OPTIONAL),
                            'infoformat' => new external_value(PARAM_INT, 'the information format of question categories', VALUE_OPTIONAL),
                            'stamp' => new external_value(PARAM_TEXT, 'the stamp of question categories', VALUE_OPTIONAL),
                            'parent' => new external_value(PARAM_INT, 'the parent of question categories', VALUE_OPTIONAL),
                            'sortorder' => new external_value(PARAM_INT, 'the sort order of question categories', VALUE_OPTIONAL),
                            'contextlevel' => new external_value(PARAM_INT, 'the context level of context', VALUE_OPTIONAL),
                            'instanceid' => new external_value(PARAM_INT, 'the instanceid of context', VALUE_OPTIONAL),
                        )
                    )
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function save_question_parameters()
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
                )
            )
        );
    }

    public static function save_question($data)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::save_question_parameters(), array(
            'data' => $data
        ));

        $question = new stdClass();

        foreach ($params['data'] as $element) {
            $question->$element['name'] = $element['value'];
        }

        $result = array();

        $transaction = $DB->start_delegated_transaction();

        $newid = $DB->insert_record('question', $question);

        $transaction->allow_commit();

        $result['newid'] = $newid;
        $result['warnings'] = $warnings;

        return $result;
    }

    public static function save_question_returns()
    {
        return new external_single_structure(
            array(
                'newid' => new external_value(PARAM_INT, 'the new id'),
                'warnings' => new external_warnings()
            )
        );
    }
}
