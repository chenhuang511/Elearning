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
class local_nccsoft_external extends external_api
{

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
     */
    public static function get_mod_label_by_id_parameters()
    {
        return new external_function_parameters(
            array('labelid' => new external_value(PARAM_INT, 'label id'),
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
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
     */
    public static function get_mod_url_by_id_parameters()
    {
        return new external_function_parameters(
            array('urlid' => new external_value(PARAM_INT, 'url id'),
                'options' => new external_multiple_structure(
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
                    ), 'Options, used since Moodle 2.9', VALUE_DEFAULT, array()
                )
            )
        );
    }

    public static function get_mod_page_by_id_parameters()
    {
        return new external_function_parameters(
            array('pageid' => new external_value(PARAM_INT, 'page id'),
                'options' => new external_multiple_structure(
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
                    ), 'Options, used since Moodle 2.9', VALUE_DEFAULT, array()
                )
            )
        );
    }

    public static function get_mod_assignment_by_id_parameters()
    {
        return new external_function_parameters(
            array('assignmentid' => new external_value(PARAM_INT, 'assignment id'),
                'options' => new external_multiple_structure(
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
                    ), 'Options, used since Moodle 2.9', VALUE_DEFAULT, array()
                )
            )
        );
    }

    public static function get_mod_lesson_by_id_parameters()
    {
        return new external_function_parameters(
            array('lessonid' => new external_value(PARAM_INT, 'lesson id'),
                'options' => new external_multiple_structure(
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
                    ), 'Options, used since Moodle 2.9', VALUE_DEFAULT, array()
                )
            )
        );
    }

    /**
     * Get course contents
     *
     * @param int $courseid course id
     * @param array $options Options for filtering the results, used since Moodle 2.9
     * @return array
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
     */
    public static function get_mod_label_by_id($labelid, $options = array())
    {
        global $CFG, $DB;

        //validate parameter
        $params = self::validate_parameters(self::get_mod_label_by_id_parameters(),
            array('labelid' => $labelid, 'options' => $options));


        //retrieve the course
        return $DB->get_record('label', array('id' => $params['labelid']), '*', MUST_EXIST);
    }

    /**
     * Get url contents
     *
     * @param int $urlid url id
     * @param array $options Options for filtering the results, used since Moodle 2.9
     * @return array
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
     */
    public static function get_mod_url_by_id($urlid, $options = array())
    {
        global $DB;

        //validate parameter
        $params = self::validate_parameters(self::get_mod_url_by_id_parameters(),
            array('urlid' => $urlid, 'options' => $options));

        //retrieve the url
        return $DB->get_record('url', array('id' => $params['urlid']), '*', MUST_EXIST);
    }

    public static function get_mod_page_by_id($pageid, $options = array())
    {
        global $DB;

        //validate parameter
        $params = self::validate_parameters(self::get_mod_page_by_id_parameters(),
            array('pageid' => $pageid, 'options' => $options));

        //retrieve the page
        return $DB->get_record('page', array('id' => $params['pageid']), '*', MUST_EXIST);
    }

    public static function get_mod_assignment_by_id($assignmentid, $options = array())
    {
        global $DB;

        //validate parameter
        $params = self::validate_parameters(self::get_mod_assignment_by_id_parameters(),
            array('assignmentid' => $assignmentid, 'options' => $options));

        //retrieve the page
        return $DB->get_record('assignment', array('id' => $params['assignmentid']), '*', MUST_EXIST);
    }

    public static function get_mod_lesson_by_id($lessonid, $options = array())
    {
        global $DB;

        //validate parameter
        $params = self::validate_parameters(self::get_mod_lesson_by_id_parameters(),
            array('lessonid' => $lessonid, 'options' => $options));

        //retrieve the page
        return $DB->get_record('lesson', array('id' => $params['lessonid']), '*', MUST_EXIST);
    }

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
     */
    public static function get_mod_label_by_id_returns()
    {
        return new external_single_structure(
            array(
                'id' => new external_value(PARAM_INT, 'label id'),
                'course' => new external_value(PARAM_INT, 'course id'),
                'name' => new external_value(PARAM_TEXT, 'label name'),
                'intro' => new external_value(PARAM_RAW, 'intro information'),
                'introformat' => new external_value(PARAM_INT, 'intro format', VALUE_OPTIONAL),
                'timemodified' => new external_value(PARAM_INT, 'date time')
            )
        );
    }

    /*
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
     */
    public static function get_mod_url_by_id_returns()
    {
        return new external_single_structure(
            array(
                'id' => new external_value(PARAM_INT, 'url id'),
                'course' => new external_value(PARAM_INT, 'course id'),
                'name' => new external_value(PARAM_TEXT, 'url name'),
                'intro' => new external_value(PARAM_RAW, 'intro information'),
                'introformat' => new external_value(PARAM_INT, 'intro format', VALUE_OPTIONAL),
                'externalurl' => new external_value(PARAM_RAW, 'external url'),
                'display' => new external_value(PARAM_INT, 'display information'),
                'displayoptions' => new external_value(PARAM_RAW, 'display options'),
                'parameters' => new external_value(PARAM_RAW, 'parameters'),
                'timemodified' => new external_value(PARAM_INT, 'date time')
            )
        );
    }

    /*
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
     */
    public static function get_mod_page_by_id_returns()
    {
        return new external_single_structure(
            array(
                'id' => new external_value(PARAM_INT, 'page id'),
                'course' => new external_value(PARAM_INT, 'course id'),
                'name' => new external_value(PARAM_TEXT, 'page name'),
                'intro' => new external_value(PARAM_RAW, 'intro information'),
                'introformat' => new external_value(PARAM_INT, 'intro format', VALUE_OPTIONAL),
                'content' => new external_value(PARAM_RAW, 'content page'),
                'contentformat' => new external_value(PARAM_INT, 'content format'),
                'legacyfiles' => new external_value(PARAM_INT, 'legacy file'),
                'legacyfileslast' => new external_value(PARAM_INT, 'legacy file last'),
                'display' => new external_value(PARAM_INT, 'display'),
                'displayoptions' => new external_value(PARAM_RAW, 'display options'),
                'revision' => new external_value(PARAM_INT, 'revision'),
                'timemodified' => new external_value(PARAM_INT, 'date time')
            )
        );
    }

    /*
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
     */
    public static function get_mod_assignment_by_id_returns()
    {
        return new external_single_structure(
            array(
                'id' => new external_value(PARAM_INT, 'assignment id'),
                'course' => new external_value(PARAM_INT, 'course id'),
                'name' => new external_value(PARAM_TEXT, 'assignment name'),
                'intro' => new external_value(PARAM_RAW, 'intro information'),
                'introformat' => new external_value(PARAM_INT, 'intro format'),
                'assignmentype' => new external_value(PARAM_TEXT, 'assignment type'),
                'resubmit' => new external_value(PARAM_INT, 'resubmit'),
                'preventlate' => new external_value(PARAM_INT, 'prevent late'),
                'emailteachers' => new external_value(PARAM_INT, 'email teacher'),
                'var1' => new external_value(PARAM_INT, 'var1'),
                'var2' => new external_value(PARAM_INT, 'var2'),
                'var3' => new external_value(PARAM_INT, 'var3'),
                'var4' => new external_value(PARAM_INT, 'var4'),
                'var5' => new external_value(PARAM_INT, 'var5'),
                'maxbytes' => new external_value(PARAM_INT, 'max bytes'),
                'timedue' => new external_value(PARAM_INT, 'time due'),
                'timeavailable' => new external_value(PARAM_INT, 'time available'),
                'grade' => new external_value(PARAM_INT, 'grade'),
                'timemodifield' => new external_value(PARAM_INT, 'time modifield')
            )
        );
    }

    public static function get_mod_lesson_by_id_returns()
    {
        return new external_single_structure(
            array(
                'id' => new external_value(PARAM_INT, 'lesson id'),
                'course' => new external_value(PARAM_INT, 'course id'),
                'name' => new external_value(PARAM_TEXT, 'lesson name'),
                'intro' => new external_value(PARAM_RAW, 'lesson intro'),
                'introformat' => new external_value(PARAM_INT, 'intro format'),
                'practice' => new external_value(PARAM_INT, 'practice'),
                'modattempts' => new external_value(PARAM_INT, 'mod attempts'),
                'usepassword' => new external_value(PARAM_INT, 'use password'),
                'password' => new external_value(PARAM_TEXT, 'password'),
                'dependency' => new external_value(PARAM_INT, 'dependency'),
                'conditions' => new external_value(PARAM_RAW, 'condition'),
                'grade' => new external_value(PARAM_INT, 'grade'),
                'custom' => new external_value(PARAM_INT, 'custom'),
                'ongoing' => new external_value(PARAM_INT, 'on going'),
                'usemaxgrade' => new external_value(PARAM_INT, 'use max grade'),
                'maxanswers' => new external_value(PARAM_INT, 'max answer'),
                'maxattempts' => new external_value(PARAM_INT, 'max attempts'),
                'review' => new external_value(PARAM_INT, 'review'),
                'nextpagedefault' => new external_value(PARAM_INT, 'next page default'),
                'feedback' => new external_value(PARAM_INT, 'feedback'),
                'minquestions' => new external_value(PARAM_INT, 'min question'),
                'maxpages' => new external_value(PARAM_INT, 'max page'),
                'timelimit' => new external_value(PARAM_INT, 'time limit'),
                'retake' => new external_value(PARAM_INT, 'retake'),
                'activitylink' => new external_value(PARAM_INT, 'activity link'),
                'mediafile' => new external_value(PARAM_TEXT, 'media file'),
                'mediaheight' => new external_value(PARAM_INT, 'media height'),
                'mediawidth' => new external_value(PARAM_INT, 'media width'),
                'mediaclose' => new external_value(PARAM_INT, 'media close'),
                'slideshow' => new external_value(PARAM_INT, 'slideshow'),
                'width' => new external_value(PARAM_INT, 'slideshow width'),
                'height' => new external_value(PARAM_INT, 'slideshow height'),
                'bgcolor' => new external_value(PARAM_TEXT, 'background color'),
                'displayleft' => new external_value(PARAM_INT, 'display left'),
                'displayleftif' => new external_value(PARAM_INT, 'display left if'),
                'progressbar' => new external_value(PARAM_INT, 'progress bar'),
                'available' => new external_value(PARAM_INT, 'available'),
                'deadline' => new external_value(PARAM_INT, 'deadline'),
                'timemodified' => new external_value(PARAM_INT, 'time modified'),
                'completionendreached' => new external_value(PARAM_INT, 'completion end reached'),
                'completiontimespent' => new external_value(PARAM_INT, 'completion time spent')
            )
        );
    }

//    public static function get_mod_course_and_module_by_id_returns()
//    {
//        return new external_multiple_structure(
//            new external_single_structure(
//                array(
//                    'id' => new external_value(PARAM_INT, 'course id'),
//                    'category' => new external_value(PARAM_INT, 'course category'),
//                    'sortorder' => new external_value(PARAM_INT, 'course sort order'),
//                    'fullname' => new external_value(PARAM_TEXT, 'course fullname'),
//                    'shortname' => new external_value(PARAM_TEXT, 'course shortname'),
//                    'idnumber' => new external_value(PARAM_TEXT, 'course id number'),
//                    'summary' => new external_value(PARAM_RAW, 'course summary'),
//                    'summaryformat' => new external_value(PARAM_INT, 'course summary format'),
//                    'format' => new external_value(PARAM_TEXT, 'course format'),
//                    'showgrades' => new external_value(PARAM_INT, 'course show grades', VALUE_REQUIRED),
//                    'newsitems' => new external_value(PARAM_INT, 'course news items', VALUE_REQUIRED),
//                    'startdate' => new external_value(PARAM_INT, 'course start date'),
//                    'marker' => new external_value(PARAM_INT, 'course marker'),
//                    'maxbytes' => new external_value(PARAM_INT, 'course max bytes')
//                )
//            )
//        );
//    }
}
