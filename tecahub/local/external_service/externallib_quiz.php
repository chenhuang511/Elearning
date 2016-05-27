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
class local_mod_quiz_external extends external_api {
    /**
     * Hanv 24/05/2016
     * Return all the information about a quiz and attempts
     * api code for handling data about quizzes and the current user's attempt.
     *
     * @return external_function_parameters
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
     *
     */
    public static function get_mod_quiz_attempt_parameters() {
        return new external_function_parameters(
            array('quizid' => new external_value(PARAM_INT, 'quiz id'),
                'ip_address' => new external_value(PARAM_TEXT, 'ip_address'),
                'username' => new external_value(PARAM_TEXT, 'username'),
                'options' => new external_multiple_structure (
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_ALPHANUM,
                                'The expected keys (value format) are:
                                                excludemodules (bool) Do not return modules, return only the sections structure
                                                excludecontents (bool) Do not return module contents (i.e: files inside a resource)
                                                sectionid (int) Return only this section
                                                sectionnumber (int) Return only this section with number (order)
                                                cmid (int) Return only this module information (among the whole sections structure)
                                                modname (string) Return only modules with this name "label, forum, etc..."
                                                modid (int) Return only the module with this id (to be used with modname'),
                            'value' => new external_value(PARAM_RAW, 'the value of the option,
                                                                    this param is personaly validated in the external function.')
                        )
                    ), 'Options, used since Moodle 2.9', VALUE_DEFAULT, array())
            )
        );
    }

    /**
     * Get Quiz attempts
     *
     * @param int $quizid quiz id
     * @param int $mnethostid mnethostid
     * @param string $username username
     * @param array $options Options for filtering the results, used since Moodle 2.9
     * @return array
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
     */
    public static function get_mod_quiz_attempt($quizid, $ip_address, $username, $options = array()) {
        global $CFG, $DB;

        //validate parameter
        $params = self::validate_parameters(self::get_mod_quiz_attempt_parameters(),
            array('quizid' => $quizid, 'username' => $username , 'ip_address' => $ip_address ,'options' => $options));

        // Get mnethost by ipadress
        $mnethost =  $DB->get_record('mnet_host', array('ip_address' => $params['ip_address']), '*', MUST_EXIST);

        // Get user by $username and $mnethost->id
        $user =  $DB->get_record('user', array('username' => $params['username'], 'mnethostid' => $mnethost -> id), '*', MUST_EXIST);

        //retrieve the quiz_attempts
        $attempt = $DB->get_record('quiz_attempts',  array('quiz' => $params['quizid'], 'userid' => $user -> id), '*', MUST_EXIST);
        return $attempt;
    }

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
     */
    public static function get_mod_quiz_attempt_returns() {
        return  new external_single_structure(
            array(
                'id' => new external_value(PARAM_INT, 'attempt id'),
                'quiz' => new external_value(PARAM_INT, 'quiz id'),
                'userid' => new external_value(PARAM_INT, 'user id'),
                'uniqueid' => new external_value(PARAM_INT, 'unique id'),
                'layout' => new external_value(PARAM_TEXT, 'layout format'),
                'preview' => new external_value(PARAM_INT, 'preview infomation'),
                'state' => new external_value(PARAM_TEXT, 'state'),
                'timestart' => new external_value(PARAM_INT, 'time start'),
                'timefinish' => new external_value(PARAM_INT, 'time finish'),
                'sumgrades' => new external_value(PARAM_TEXT, 'sum grade'),
            )
        );
    }
}
