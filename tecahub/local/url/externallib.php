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
 * External Url API
 *
 * @package    core_local_url
 * @category   external
 * @copyright  2009 Petr Skodak
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir . '/externallib.php');
require_once($CFG->dirroot . '/mod/url/lib.php');

/**
 * Url external functions
 *
 * @package    core_local_url
 * @category   external
 * @copyright  2011 Jerome Mouneyrac
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since Moodle 2.2
 */
class local_mod_url_external extends external_api
{
    /**
     * @desc Validate parameters.
     * @return external_function_parameters
     */
    public static function get_url_by_id_parameters()
    {
        return new external_function_parameters (
            array(
                'id' => new external_value(PARAM_INT, 'Url id'),
            )
        );
    }

    /**
     * @desc get url by id
     * @param $id
     * @return array
     * @throws invalid_parameter_exception
     */
    public static function get_url_by_id($id)
    {
        global $DB;
        //validate parameter
        $params = self::validate_parameters(self::get_url_by_id_parameters(),
            array('id' => $id));
        $warnings = array();
        // Entry to return.
        $url = null;
        if (!empty($params['id'])) {
            $query = "SELECT url.id, url.course, url.name, url.intro, url.introformat, 
                        url.externalurl, url.display, url.displayoptions, url.parameters, url.timemodified
                        FROM {url} AS url
                        WHERE url.id = ?";
            $url = $DB->get_record_sql($query, $params);
        }
        $return = array();
        $return['url'] = $url;
        $return['warnings'] = $warnings;
        return $return;
    }

    /**
     * @desc Describes the surveys return value.
     * @return external_single_structure
     * @since Moodle 3.1
     */
    public static function get_url_by_id_returns()
    {
        return new external_single_structure(
            array(
                'url' => new external_single_structure(
                    array(
                        'id' => new external_value(PARAM_INT, 'Url id'),
                        'course' => new external_value(PARAM_INT, 'Course id'),
                        'name' => new external_value(PARAM_RAW, 'Url name'),
                        'intro' => new external_value(PARAM_RAW, 'The Url intro', VALUE_OPTIONAL),
                        'introformat' => new external_format_value('intro'),
                        'externalurl' => new external_value(PARAM_RAW, 'The external url'),
                        'display' => new external_value(PARAM_INT, 'display'),
                        'displayoptions' => new external_value(PARAM_RAW, 'display options', VALUE_OPTIONAL),
                        'parameters' => new external_value(PARAM_RAW, 'parameters', VALUE_OPTIONAL),
                        'timemodified' => new external_value(PARAM_INT, 'timemodified'),
                    )
                ),
                'warnings' => new external_warnings(),
            )
        );
    }
}
