<?php

/**
 * External lesson API
 *
 * @package    core_local_lesson
 * @category   external
 * @copyright  2009 Petr Skodak
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

require_once("$CFG->libdir/externallib.php");

/**
 * Lesson external functions
 *
 * @package    core_local_lesson
 * @category   external
 * @copyright  2016 Nccsoft Vietnam
 * @license    http://nccsoft.vn
 * @since Moodle 3.0
 */
class local_mod_lesson_external extends external_api
{
    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 3.0
     */
    public static function get_mod_lesson_by_id_parameters()
    {
        return new external_function_parameters(
            array('lessonid' => new external_value(PARAM_INT, 'the lesson id'),
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
     * Return information about a lesson.
     *
     * @param int $lessonid the lesson id
     * @return array of warnings and the lesson
     * @since Moodle 3.0
     * @throws moodle_exception
     */
    public static function get_mod_lesson_by_id($lessonid, $options = array())
    {
        global $DB;

        // validate params
        $params = self::validate_parameters(self::get_mod_lesson_by_id_parameters(),
            array(
                'lessonid' => $lessonid,
                'options' => $options
            )
        );

        return $DB->get_record('lesson', array('id' => $params['lessonid']), '*', MUST_EXIST);
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     * @since Moodle 3.0
     */
    public static function get_mod_lesson_by_id_returns()
    {
        return new external_single_structure(
            array(
                'id' => new external_value(PARAM_INT, 'lesson id'),
                'course' => new external_value(PARAM_INT, 'course id', VALUE_DEFAULT),
                'name' => new external_value(PARAM_RAW, 'lesson name'),
                'intro' => new external_value(PARAM_RAW, 'lesson intro'),
                'introformat' => new external_value(PARAM_INT, 'intro format', VALUE_DEFAULT),
                'practice' => new external_value(PARAM_INT, 'practice', VALUE_DEFAULT),
                'modattempts' => new external_value(PARAM_INT, 'mod attempts', VALUE_DEFAULT),
                'usepassword' => new external_value(PARAM_INT, 'use password', VALUE_DEFAULT),
                'password' => new external_value(PARAM_TEXT, 'password'),
                'dependency' => new external_value(PARAM_INT, 'dependency', VALUE_DEFAULT),
                'conditions' => new external_value(PARAM_RAW, 'condition'),
                'grade' => new external_value(PARAM_INT, 'grade', VALUE_DEFAULT),
                'custom' => new external_value(PARAM_INT, 'custom', VALUE_DEFAULT),
                'ongoing' => new external_value(PARAM_INT, 'on going', VALUE_DEFAULT),
                'usemaxgrade' => new external_value(PARAM_INT, 'use max grade', VALUE_DEFAULT),
                'maxanswers' => new external_value(PARAM_INT, 'max answer'),
                'maxattempts' => new external_value(PARAM_INT, 'max attempts'),
                'review' => new external_value(PARAM_INT, 'review', VALUE_DEFAULT),
                'nextpagedefault' => new external_value(PARAM_INT, 'next page default', VALUE_DEFAULT),
                'feedback' => new external_value(PARAM_INT, 'feedback', VALUE_REQUIRED),
                'minquestions' => new external_value(PARAM_INT, 'min question', VALUE_DEFAULT),
                'maxpages' => new external_value(PARAM_INT, 'max page', VALUE_DEFAULT),
                'timelimit' => new external_value(PARAM_INT, 'time limit', VALUE_DEFAULT),
                'retake' => new external_value(PARAM_INT, 'retake', VALUE_DEFAULT),
                'activitylink' => new external_value(PARAM_INT, 'activity link', VALUE_REQUIRED),
                'mediafile' => new external_value(PARAM_TEXT, 'media file', VALUE_DEFAULT),
                'mediaheight' => new external_value(PARAM_INT, 'media height'),
                'mediawidth' => new external_value(PARAM_INT, 'media width'),
                'mediaclose' => new external_value(PARAM_INT, 'media close'),
                'slideshow' => new external_value(PARAM_INT, 'slideshow'),
                'width' => new external_value(PARAM_INT, 'slideshow width'),
                'height' => new external_value(PARAM_INT, 'slideshow height'),
                'bgcolor' => new external_value(PARAM_TEXT, 'background color'),
                'displayleft' => new external_value(PARAM_INT, 'display left'),
                'displayleftif' => new external_value(PARAM_INT, 'display left if', VALUE_DEFAULT),
                'progressbar' => new external_value(PARAM_INT, 'progress bar', VALUE_DEFAULT),
                'available' => new external_value(PARAM_INT, 'available', VALUE_DEFAULT),
                'deadline' => new external_value(PARAM_INT, 'deadline', VALUE_DEFAULT),
                'timemodified' => new external_value(PARAM_INT, 'time modified', VALUE_DEFAULT),
                'completionendreached' => new external_value(PARAM_INT, 'completion end reached', VALUE_DEFAULT),
                'completiontimespent' => new external_value(PARAM_INT, 'completion time spent', VALUE_DEFAULT)
            )
        );
    }

    public static function get_context_by_instanceid_and_contextlevel_parameters()
    {
        return new external_function_parameters(
            array('instanceid' => new external_value(PARAM_INT, 'the instance id'),
                'contextlevel' => new external_value(PARAM_INT, 'the context level'),
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

    public static function get_context_by_instanceid_and_contextlevel($instanceid, $contextlevel, $options = array())
    {
        global $DB;

        // validate params
        $params = self::validate_parameters(self::get_context_by_instanceid_and_contextlevel_parameters(),
            array(
                'instanceid' => $instanceid,
                'contextlevel' => $contextlevel,
                'options' => $options
            )
        );

        return $DB->get_record('context', array('contextlevel' => $params['contextlevel'], 'instanceid' => $params['instanceid']), '*', MUST_EXIST);
    }

    public static function get_context_by_instanceid_and_contextlevel_returns()
    {
        return new external_single_structure(
            array(
                'id' => new external_value(PARAM_INT, 'context id'),
                'contextlevel' => new external_value(PARAM_INT, 'context level', VALUE_DEFAULT),
                'instanceid' => new external_value(PARAM_INT, 'instance id', VALUE_DEFAULT),
                'path' => new external_value(PARAM_RAW, 'path'),
                'depth' => new external_value(PARAM_INT, 'depth', VALUE_DEFAULT)
            )
        );
    }

    public static function get_lesson_page_parameters()
    {
        return new external_function_parameters(
            array('lessonid' => new external_value(PARAM_INT, 'the lesson id'),
                'prevpageid' => new external_value(PARAM_INT, 'the previous page id'),
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

    public static function get_lesson_page($lessonid, $prevpageid, $options = array())
    {
        global $DB;

        // validate params
        $params = self::validate_parameters(self::get_lesson_page_parameters(),
            array(
                'lessonid' => $lessonid,
                'prevpageid' => $prevpageid,
                'options' => $options
            )
        );

        return $DB->get_record('lesson_pages', array('lessonid' => $params['lessonid'], 'prevpageid' => $params['prevpageid']), '*', MUST_EXIST);
    }

    public static function get_lesson_page_returns()
    {
        return new external_single_structure(
            array(
                'id' => new external_value(PARAM_INT, 'the lesson page id'),
                'lessonid' => new external_value(PARAM_INT, 'lesson id', VALUE_DEFAULT),
                'prevpageid' => new external_value(PARAM_INT, 'previous page id', VALUE_DEFAULT),
                'nextpageid' => new external_value(PARAM_INT, 'next page id', VALUE_DEFAULT),
                'qtype' => new external_value(PARAM_INT, 'qtype', VALUE_DEFAULT),
                'qoption' => new external_value(PARAM_INT, 'qoption', VALUE_DEFAULT),
                'layout' => new external_value(PARAM_INT, 'layout', VALUE_REQUIRED),
                'display' => new external_value(PARAM_INT, 'display', VALUE_REQUIRED),
                'timecreated' => new external_value(PARAM_INT, 'time created', VALUE_DEFAULT),
                'timemodified' => new external_value(PARAM_INT, 'time modified', VALUE_DEFAULT),
                'title' => new external_value(PARAM_RAW, 'title'),
                'contents' => new external_value(PARAM_RAW, 'contents'),
                'contentsformat' => new external_value(PARAM_INT, 'contents format', VALUE_DEFAULT)
            )
        );
    }

    public static function get_mod_lessonpage_by_pageid_and_lessonid_parameters()
    {
        return new external_function_parameters(
            array('pageid' => new external_value(PARAM_INT, 'the lesson page id'),
                'lessonid' => new external_value(PARAM_INT, 'the lesson id'),
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

    public static function get_mod_lessonpage_by_pageid_and_lessonid($pageid, $lessonid, $options = array())
    {
        global $DB;

        // validate params
        $params = self::validate_parameters(self::get_mod_lessonpage_by_pageid_and_lessonid_parameters(),
            array(
                'lessonid' => $lessonid,
                'pageid' => $pageid,
                'options' => $options
            )
        );

        return $DB->get_record('lesson_pages', array('id' => $params['pageid'], 'lessonid' => $params['lessonid']), '*', MUST_EXIST);
    }

    public static function get_mod_lessonpage_by_pageid_and_lessonid_returns()
    {
        return new external_single_structure(
            array(
                'id' => new external_value(PARAM_INT, 'the lesson page id'),
                'lessonid' => new external_value(PARAM_INT, 'lesson id', VALUE_DEFAULT),
                'prevpageid' => new external_value(PARAM_INT, 'previous page id', VALUE_DEFAULT),
                'nextpageid' => new external_value(PARAM_INT, 'next page id', VALUE_DEFAULT),
                'qtype' => new external_value(PARAM_INT, 'qtype', VALUE_DEFAULT),
                'qoption' => new external_value(PARAM_INT, 'qoption', VALUE_DEFAULT),
                'layout' => new external_value(PARAM_INT, 'layout', VALUE_REQUIRED),
                'display' => new external_value(PARAM_INT, 'display', VALUE_REQUIRED),
                'timecreated' => new external_value(PARAM_INT, 'time created', VALUE_DEFAULT),
                'timemodified' => new external_value(PARAM_INT, 'time modified', VALUE_DEFAULT),
                'title' => new external_value(PARAM_RAW, 'title'),
                'contents' => new external_value(PARAM_RAW, 'contents'),
                'contentsformat' => new external_value(PARAM_INT, 'contents format', VALUE_DEFAULT)
            )
        );
    }

    public static function get_context_by_id_parameters()
    {
        return new external_function_parameters(
            array('id' => new external_value(PARAM_INT, 'the context id'),
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

    public static function get_context_by_id($id, $options = array())
    {
        global $DB;

        // validate params
        $params = self::validate_parameters(self::get_context_by_id_parameters(),
            array(
                'id' => $id,
                'options' => $options
            )
        );

        return $DB->get_record('context', array('id' => $params['id']), '*', MUST_EXIST);
    }

    public static function get_context_by_id_returns()
    {
        return new external_single_structure(
            array(
                'id' => new external_value(PARAM_INT, 'context id'),
                'contextlevel' => new external_value(PARAM_INT, 'context level', VALUE_DEFAULT),
                'instanceid' => new external_value(PARAM_INT, 'instance id', VALUE_DEFAULT),
                'path' => new external_value(PARAM_RAW, 'path'),
                'depth' => new external_value(PARAM_INT, 'depth', VALUE_DEFAULT)
            )
        );
    }
}