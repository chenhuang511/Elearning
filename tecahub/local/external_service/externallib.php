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
class local_nccsoft_external extends external_api {

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
     */
    public static function get_mod_label_by_id_parameters() {
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
     * Get course contents
     *
     * @param int $courseid course id
     * @param array $options Options for filtering the results, used since Moodle 2.9
     * @return array
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
     */
    public static function get_mod_label_by_id($labelid, $options = array()) {
        global $CFG, $DB;

        //validate parameter
        $params = self::validate_parameters(self::get_mod_label_by_id_parameters(),
                        array('labelid' => $labelid, 'options' => $options));


        //retrieve the course
	return $DB->get_record('label', array('id' => $params['labelid']), '*', MUST_EXIST);
    }
    
/**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
     */
    public static function get_mod_label_by_id_returns() {
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


/*--------------------------------------------------*/

    /**
     * VietNH 23-05-2016
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
     */
    public static function get_course_content_by_id_parameters() {
        return new external_function_parameters(
            array('courseid' => new external_value(PARAM_INT, 'course id'),
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
     * Get course contents
     *
     * @param int $courseid course id
     * @param array $options Options for filtering the results, used since Moodle 2.9
     * @return array
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
     */
    public static function get_course_content_by_id($courseid, $options = array()) {
        global $CFG, $DB;
        require_once($CFG->dirroot . "/course/lib.php");

        //validate parameter
        $params = self::validate_parameters(self::get_course_content_by_id_parameters(),
            array('courseid' => $courseid, 'options' => $options));

        $filters = array();
        if (!empty($params['options'])) {
            foreach ($params['options'] as $option) {
                $name = trim($option['name']);
                // Avoid duplicated options.
                if (!isset($filters[$name])) {
                    switch ($name) {
                        case 'excludemodules':
                        case 'excludecontents':
                            $value = clean_param($option['value'], PARAM_BOOL);
                            $filters[$name] = $value;
                            break;
                        case 'sectionid':
                        case 'sectionnumber':
                        case 'cmid':
                        case 'modid':
                            $value = clean_param($option['value'], PARAM_INT);
                            if (is_numeric($value)) {
                                $filters[$name] = $value;
                            } else {
                                throw new moodle_exception('errorinvalidparam', 'webservice', '', $name);
                            }
                            break;
                        case 'modname':
                            $value = clean_param($option['value'], PARAM_PLUGIN);
                            if ($value) {
                                $filters[$name] = $value;
                            } else {
                                throw new moodle_exception('errorinvalidparam', 'webservice', '', $name);
                            }
                            break;
                        default:
                            throw new moodle_exception('errorinvalidparam', 'webservice', '', $name);
                    }
                }
            }
        }

        //retrieve the course
        $course = $DB->get_record('course', array('id' => $params['courseid']), '*', MUST_EXIST);

        if ($course->id != SITEID) {
            // Check course format exist.
            if (!file_exists($CFG->dirroot . '/course/format/' . $course->format . '/lib.php')) {
                throw new moodle_exception('cannotgetcoursecontents', 'webservice', '', null,
                    get_string('courseformatnotfound', 'error', $course->format));
            } else {
                require_once($CFG->dirroot . '/course/format/' . $course->format . '/lib.php');
            }
        }

        // now security checks
        $context = context_course::instance($course->id, IGNORE_MISSING);
        try {
            self::validate_context($context);
        } catch (Exception $e) {
            $exceptionparam = new stdClass();
            $exceptionparam->message = $e->getMessage();
            $exceptionparam->courseid = $course->id;
            throw new moodle_exception('errorcoursecontextnotvalid', 'webservice', '', $exceptionparam);
        }

        $canupdatecourse = has_capability('moodle/course:update', $context);

        //create return value
        $coursecontents = array();

        if ($canupdatecourse or $course->visible
            or has_capability('moodle/course:viewhiddencourses', $context)) {

            //retrieve sections
            $modinfo = get_fast_modinfo($course);

            $sections = $modinfo->get_section_info_all();

            //for each sections (first displayed to last displayed)
            $modinfosections = $modinfo->get_sections();

            foreach ($sections as $key => $section) {

                if (!$section->uservisible) {
                    continue;
                }

                // This becomes true when we are filtering and we found the value to filter with.
                $sectionfound = false;

                // Filter by section id.
                if (!empty($filters['sectionid'])) {
                    if ($section->id != $filters['sectionid']) {
                        continue;
                    } else {
                        $sectionfound = true;
                    }
                }

                // Filter by section number. Note that 0 is a valid section number.
                if (isset($filters['sectionnumber'])) {
                    if ($key != $filters['sectionnumber']) {
                        continue;
                    } else {
                        $sectionfound = true;
                    }
                }

                // reset $sectioncontents
                $sectionvalues = array();
                $sectionvalues['id'] = $section->id;
                $sectionvalues['name'] = get_section_name($course, $section);
                $sectionvalues['visible'] = $section->visible;
                list($sectionvalues['summary'], $sectionvalues['summaryformat']) =
                    external_format_text($section->summary, $section->summaryformat,
                        $context->id, 'course', 'section', $section->id);
                $sectioncontents = array();

                //for each module of the section
                if (empty($filters['excludemodules']) and !empty($modinfosections[$section->section])) {
                    foreach ($modinfosections[$section->section] as $cmid) {
                        $cm = $modinfo->cms[$cmid];

                        // stop here if the module is not visible to the user
                        if (!$cm->uservisible) {
                            continue;
                        }

                        // This becomes true when we are filtering and we found the value to filter with.
                        $modfound = false;

                        // Filter by cmid.
                        if (!empty($filters['cmid'])) {
                            if ($cmid != $filters['cmid']) {
                                continue;
                            } else {
                                $modfound = true;
                            }
                        }

                        // Filter by module name and id.
                        if (!empty($filters['modname'])) {
                            if ($cm->modname != $filters['modname']) {
                                continue;
                            } else if (!empty($filters['modid'])) {
                                if ($cm->instance != $filters['modid']) {
                                    continue;
                                } else {
                                    // Note that if we are only filtering by modname we don't break the loop.
                                    $modfound = true;
                                }
                            } 
                        }

                        $module = array();

                        $modcontext = context_module::instance($cm->id);

                        //common info (for people being able to see the module or availability dates)
                        $module['id'] = $cm->id;
                        $module['name'] = external_format_string($cm->name, $modcontext->id);
                        $module['instance'] = $cm->instance;
                        $module['modname'] = $cm->modname;
                        $module['modplural'] = $cm->modplural;
                        $module['modicon'] = $cm->get_icon_url()->out(false);
                        $module['indent'] = $cm->indent;

                        if (!empty($cm->showdescription) or $cm->modname == 'label') {
                            // We want to use the external format. However from reading get_formatted_content(), $cm->content format is always FORMAT_HTML.
                            list($module['description'], $descriptionformat) = external_format_text($cm->content,
                                FORMAT_HTML, $modcontext->id, $cm->modname, 'intro', $cm->id);
                        }

                        //url of the module
                        $url = $cm->url;
                        if ($url) { //labels don't have url
                            $module['url'] = $url->out(false);
                        }

                        $canviewhidden = has_capability('moodle/course:viewhiddenactivities',
                            context_module::instance($cm->id));
                        //user that can view hidden module should know about the visibility
                        $module['visible'] = $cm->visible;

                        // Availability date (also send to user who can see hidden module).
                        if ($CFG->enableavailability && ($canviewhidden || $canupdatecourse)) {
                            $module['availability'] = $cm->availability;
                        }

                        $baseurl = 'webservice/pluginfile.php';

                        //call $modulename_export_contents
                        //(each module callback take care about checking the capabilities)

                        require_once($CFG->dirroot . '/mod/' . $cm->modname . '/lib.php');
                        $getcontentfunction = $cm->modname.'_export_contents';
                        if (function_exists($getcontentfunction)) {
                            if (empty($filters['excludecontents']) and $contents = $getcontentfunction($cm, $baseurl)) {
                                $module['contents'] = $contents;
                            } else {
                                $module['contents'] = array();
                            }
                        }

                        //assign result to $sectioncontents
                        $sectioncontents[] = $module;

                        // If we just did a filtering, break the loop.
                        if ($modfound) {
                            break;
                        }

                    }
                }
                $sectionvalues['modules'] = $sectioncontents;

                // assign result to $coursecontents
                $coursecontents[] = $sectionvalues;

                // Break the loop if we are filtering.
                if ($sectionfound) {
                    break;
                }
            }
        }
        return $coursecontents;
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     * @since Moodle 2.2
     */
    public static function get_course_content_by_id_returns() {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'id' => new external_value(PARAM_INT, 'Section ID'),
                    'name' => new external_value(PARAM_TEXT, 'Section name'),
                    'visible' => new external_value(PARAM_INT, 'is the section visible', VALUE_OPTIONAL),
                    'summary' => new external_value(PARAM_RAW, 'Section description'),
                    'summaryformat' => new external_format_value('summary'),
                    'modules' => new external_multiple_structure(
                        new external_single_structure(
                            array(
                                'id' => new external_value(PARAM_INT, 'activity id'),
                                'url' => new external_value(PARAM_URL, 'activity url', VALUE_OPTIONAL),
                                'name' => new external_value(PARAM_RAW, 'activity module name'),
                                'instance' => new external_value(PARAM_INT, 'instance id', VALUE_OPTIONAL),
                                'description' => new external_value(PARAM_RAW, 'activity description', VALUE_OPTIONAL),
                                'visible' => new external_value(PARAM_INT, 'is the module visible', VALUE_OPTIONAL),
                                'modicon' => new external_value(PARAM_URL, 'activity icon url'),
                                'modname' => new external_value(PARAM_PLUGIN, 'activity module type'),
                                'modplural' => new external_value(PARAM_TEXT, 'activity module plural name'),
                                'availability' => new external_value(PARAM_RAW, 'module availability settings', VALUE_OPTIONAL),
                                'indent' => new external_value(PARAM_INT, 'number of identation in the site'),
                                'contents' => new external_multiple_structure(
                                    new external_single_structure(
                                        array(
                                            // content info
                                            'type'=> new external_value(PARAM_TEXT, 'a file or a folder or external link'),
                                            'filename'=> new external_value(PARAM_FILE, 'filename'),
                                            'filepath'=> new external_value(PARAM_PATH, 'filepath'),
                                            'filesize'=> new external_value(PARAM_INT, 'filesize'),
                                            'fileurl' => new external_value(PARAM_URL, 'downloadable file url', VALUE_OPTIONAL),
                                            'content' => new external_value(PARAM_RAW, 'Raw content, will be used when type is content', VALUE_OPTIONAL),
                                            'timecreated' => new external_value(PARAM_INT, 'Time created'),
                                            'timemodified' => new external_value(PARAM_INT, 'Time modified'),
                                            'sortorder' => new external_value(PARAM_INT, 'Content sort order'),

                                            // copyright related info
                                            'userid' => new external_value(PARAM_INT, 'User who added this content to moodle'),
                                            'author' => new external_value(PARAM_TEXT, 'Content owner'),
                                            'license' => new external_value(PARAM_TEXT, 'Content license'),
                                        )
                                    ), VALUE_DEFAULT, array()
                                )
                            )
                        ), 'list of module'
                    )
                )
            )
        );
    }

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 2.3
     */

    /**
     * VietNH 20-05-2016
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
     */
    public static function get_mod_module_section_by_id_parameters() {
        return new external_function_parameters(
            array('listmodule' => new external_value("string", 'List module id'),
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
     * Get course contents - page
     *
     * @param int $courseid course id
     * @param array $options Options for filtering the results, used since Moodle 2.9
     * @return array
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
     */
    public static function get_mod_module_section_by_id($listmodule, $options = array()) {
        global $CFG, $DB;

        //validate parameter
        $params = self::validate_parameters(self::get_mod_module_section_by_id_parameters(),
            array('listmodule' => $listmodule, 'options' => $options));


        //retrieve the course
        //return $DB->get_record('page', array('id' => $params['listmodule']), '*', MUST_EXIST);
    }

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
     */
    public static function get_mod_module_section_by_id_returns() {
        return new external_single_structure(
            array(
                'id' => new external_value(PARAM_INT, 'page id'),
                'course' => new external_value(PARAM_INT, 'course id'),
                'name' => new external_value(PARAM_TEXT, 'page name'),
                'intro' => new external_value(PARAM_RAW, 'intro information'),
                'introformat' => new external_value(PARAM_INT, 'intro format', VALUE_OPTIONAL),
                'timemodified' => new external_value(PARAM_INT, 'date time')
            )
        );
    }
    
    
    /**
     * VietNH 20-05-2016
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
     */
    public static function get_mod_page_by_id_parameters() {
        return new external_function_parameters(
            array('pageid' => new external_value(PARAM_INT, 'page id'),
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
     * Get course contents - page
     *
     * @param int $courseid course id
     * @param array $options Options for filtering the results, used since Moodle 2.9
     * @return array
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
     */
    public static function get_mod_page_by_id($pageid, $options = array()) {
        global $CFG, $DB;

        //validate parameter
        $params = self::validate_parameters(self::get_mod_page_by_id_parameters(),
            array('pageid' => $pageid, 'options' => $options));


        //retrieve the course
        return $DB->get_record('page', array('id' => $params['pageid']), '*', MUST_EXIST);
    }

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
     */
    public static function get_mod_page_by_id_returns() {
        return new external_single_structure(
            array(
                'id' => new external_value(PARAM_INT, 'page id'),
                'course' => new external_value(PARAM_INT, 'course id'),
                'name' => new external_value(PARAM_TEXT, 'page name'),
                'intro' => new external_value(PARAM_RAW, 'intro information'),
                'introformat' => new external_value(PARAM_INT, 'intro format', VALUE_OPTIONAL),
                'timemodified' => new external_value(PARAM_INT, 'date time')
            )
        );
    }

    /**
     * VietNH 20-05-2016
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
     */
    public static function get_mod_book_by_id_parameters() {
        return new external_function_parameters(
            array('bookid' => new external_value(PARAM_INT, 'book id'),
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
     * Get course contents - page
     *
     * @param int $courseid course id
     * @param array $options Options for filtering the results, used since Moodle 2.9
     * @return array
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
     */
    public static function get_mod_book_by_id($bookid, $options = array()) {
        global $CFG, $DB;

        //validate parameter
        $params = self::validate_parameters(self::get_mod_book_by_id_parameters(),
            array('bookid' => $bookid, 'options' => $options));


        //retrieve the course
        return $DB->get_record('book', array('id' => $params['bookid']), '*', MUST_EXIST);
    }

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
     */
    public static function get_mod_book_by_id_returns() {
        return new external_single_structure(
            array(
                'id' => new external_value(PARAM_INT, 'book id'),
                'course' => new external_value(PARAM_INT, 'course id'),
                'name' => new external_value(PARAM_TEXT, 'page name'),
                'intro' => new external_value(PARAM_RAW, 'intro information'),
                'introformat' => new external_value(PARAM_INT, 'intro format', VALUE_OPTIONAL),
                'timemodified' => new external_value(PARAM_INT, 'date time')
            )
        );
    }




    /**
     * Hanv 20/05/2016
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
     *
     */
    public static function get_mod_quiz_by_id_parameters() {
        return new external_function_parameters(
            array('quizid' => new external_value(PARAM_INT, 'quiz id'),
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
     * Get Quiz name
     *
     * @param int $courseid course id
     * @param array $options Options for filtering the results, used since Moodle 2.9
     * @return array
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
     */
    public static function get_mod_quiz_by_id($quizid, $options = array()) {
        global $CFG, $DB;

        //validate parameter
        $params = self::validate_parameters(self::get_mod_quiz_by_id_parameters(),
            array('quizid' => $quizid, 'options' => $options));

        //retrieve the quiz
        $quiz =  $DB->get_record('quiz', array('id' => $params['quizid']), '*', MUST_EXIST);
        return $quiz;
    }

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
     */
    public static function get_mod_quiz_by_id_returns() {
        return  new external_single_structure(
            array(
                'id' => new external_value(PARAM_INT, 'quiz id'),
                'course' => new external_value(PARAM_INT, 'course id'),
                'name' => new external_value(PARAM_TEXT, 'quiz name'),
                'intro' => new external_value(PARAM_RAW, 'intro information'),
                'introformat' => new external_value(PARAM_INT, 'intro format', VALUE_OPTIONAL),
                'timemodified' => new external_value(PARAM_INT, 'date time')
            )
        );
    }
}
