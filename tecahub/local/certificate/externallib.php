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

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/externallib.php');

/**
 * Course external functions
 *
 * @package    core_course
 * @category   external
 * @copyright  2011 Jerome Mouneyrac
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since Moodle 2.2
 */
class local_certificate_external extends external_api
{

    public static function get_certificate_by_id_parameters() {
        return new external_function_parameters(
            array('id' => new external_value(PARAM_INT, 'id')
            )
        );
    }

    /**
     * Get Question object
     *
     * @param int $id id
     * @param array $options Options for filtering the results, used since Moodle 2.9
     * @return array
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
     */
    public static function get_certificate_by_id($id) {
        global $CFG, $DB;

        //validate parameter
        $params = self::validate_parameters(self::get_certificate_by_id_parameters(),
            array('id' => $id));
        return $DB->get_record('certificate', array('id' => $params['id']), '*', MUST_EXIST);
    }

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
     */
    public static function get_certificate_by_id_returns() {
        return new external_single_structure(
            array(
                'id' => new external_value(PARAM_INT, 'Standard Moodle primary key.'),
                'course' => new external_value(PARAM_INT, 'course'),
                'name' => new external_value(PARAM_TEXT, 'Question name'),
                'intro' => new external_value(PARAM_RAW, 'Question introduction text'),
                'introformat' => new external_value(PARAM_INT, 'Question introduction text'),
                'emailteachers' => new external_value(PARAM_INT, 'questiontext format'),
                'emailothers' => new external_value(PARAM_RAW, 'qtype'),
                'savecert' => new external_value(PARAM_TEXT, 'respondenttype'),
                'reportcert' => new external_value(PARAM_TEXT, 'resp_eligible'),
                'delivery' => new external_value(PARAM_INT, 'resp_view'),
                'requiredtime' => new external_value(PARAM_INT, 'opendate'),
                'certificatetype' => new external_value(PARAM_TEXT, 'closedate'),
                'orientation' => new external_value(PARAM_TEXT, 'resume'),
                'borderstyle' => new external_value(PARAM_TEXT, 'navigate'),
                'bordercolor' => new external_value(PARAM_TEXT, '	grade'),
                'printwmark' => new external_value(PARAM_TEXT, 'sid'),
                'printdate' => new external_value(PARAM_INT, 'Last modified time'),
                'datefmt' => new external_value(PARAM_INT, 'completionsubmit'),
                'printnumber' => new external_value(PARAM_INT, 'autonum'),
                'printgrade' => new external_value(PARAM_INT, 'autonum'),
                'gradefmt' => new external_value(PARAM_INT, 'autonum'),
                'printoutcome' => new external_value(PARAM_INT, 'autonum'),
                'printhours' => new external_value(PARAM_TEXT, 'autonum'),
                'printteacher' => new external_value(PARAM_INT, 'autonum'),
                'customtext' => new external_value(PARAM_RAW, 'autonum'),
                'printsignature' => new external_value(PARAM_TEXT, 'autonum'),
                'printseal' => new external_value(PARAM_TEXT, 'autonum'),
                'timecreated' => new external_value(PARAM_INT, 'autonum'),
                'timemodified' => new external_value(PARAM_INT, 'autonum'),
            )
        );
    }
}
