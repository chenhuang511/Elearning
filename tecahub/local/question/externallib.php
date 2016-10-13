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
    public static function get_question_categories_by_id_parameters()
    {
        return new external_function_parameters(
            array(
                'id' => new external_value(PARAM_INT, 'the id of question categories')
            )
        );
    }

    public static function get_question_categories_by_id($id)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::get_question_categories_by_id_parameters(), array(
            'id' => $id
        ));

        $qc = $DB->get_record('question_categories', array('id' => $params['id']));

        if(!$qc) {
            $qc = new stdClass();
        }

        $result = array();
        $result['qc'] = $qc;
        $result['warnings'] = $warnings;
        return $result;
    }

    public static function get_question_categories_by_id_returns()
    {
        return new external_single_structure(
            array(
                'qc' => new external_single_structure(
                    array(
                        'id' => new external_value(PARAM_INT, 'the id of question categories', VALUE_OPTIONAL),
                        'name' => new external_value(PARAM_TEXT, 'the name of question categories', VALUE_OPTIONAL),
                        'contextid' => new external_value(PARAM_INT, 'the id of context', VALUE_OPTIONAL),
                        'info' => new external_value(PARAM_RAW, 'the information of question categories', VALUE_OPTIONAL),
                        'infoformat' => new external_value(PARAM_INT, 'the information format', VALUE_OPTIONAL),
                        'stamp' => new external_value(PARAM_TEXT, 'the stamp', VALUE_OPTIONAL),
                        'parent' => new external_value(PARAM_INT, 'the parent id', VALUE_OPTIONAL),
                        'sortorder' => new external_value(PARAM_INT, 'the sort order', VALUE_OPTIONAL),
                    )
                ),
                'warnings' => new external_warnings()
            )
        );
    }
}
