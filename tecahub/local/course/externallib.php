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
require_once($CFG->dirroot . '/course/externallib.php');

/**
 * Course external functions
 *
 * @package    core_course
 * @category   external
 * @copyright  2011 Jerome Mouneyrac
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since Moodle 2.2
 */
class local_course_external extends external_api
{

    //region _VIETNH
    /**
     * VietNH 23-05-2016
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
     */
    public static function get_course_content_by_id_parameters()
    {
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

    public static function get_course_content_by_id($courseid, $options = array())
    {
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

        // now security checks
        $context = context_course::instance($course->id, IGNORE_MISSING);

        //create return value
        $coursecontents = array();

        if ($course->visible) {
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
                        $module['description'] = $cm->content;
                        //url of the module
                        $url = $cm->url;
                        if ($url) $module['url'] = $url->out(false);


                        //user that can view hidden module should know about the visibility
                        $module['visible'] = $cm->visible;

                        // Availability date (also send to user who can see hidden module).
                        if ($CFG->enableavailability) $module['availability'] = $cm->availability;

                        $baseurl = 'webservice/pluginfile.php';

                        //call $modulename_export_contents
                        //(each module callback take care about checking the capabilities)

                        require_once($CFG->dirroot . '/mod/' . $cm->modname . '/lib.php');
                        $getcontentfunction = $cm->modname . '_export_contents';
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

    public static function get_course_content_by_id_returns()
    {
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
                                            'type' => new external_value(PARAM_TEXT, 'a file or a folder or external link'),
                                            'filename' => new external_value(PARAM_FILE, 'filename'),
                                            'filepath' => new external_value(PARAM_PATH, 'filepath'),
                                            'filesize' => new external_value(PARAM_INT, 'filesize'),
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

    /** MINHND
     * Returns description of method parameters
     *
     * @return external_function_parameters
     */
    public static function get_course_thumbnail_by_id_parameters()
    {
        return new external_function_parameters(
            array(
                'courseids' => new external_multiple_structure(new external_value(PARAM_INT, 'course ID')),
            )
        );
    }

    /**
     * Get thumbnail course information
     *
     * @param array $courseid array of course ids
     * @return array An array of arrays thumbnail thumbnail
     */
    public static function get_course_thumbnail_by_id($courseids)
    {
        global $CFG, $COURSE, $DB;
        require_once($CFG->dirroot . "/course/lib.php");
        //validate parameter
        $params = self::validate_parameters(self::get_course_thumbnail_by_id_parameters(), array('courseids' => $courseids));

        // Clean the values.
        $cleanedvalues = array();
        foreach ($courseids as $courseid) {
            $cleanedvalue = clean_param($courseid, PARAM_INT);
            if ($courseid != $cleanedvalue) {
                throw new invalid_parameter_exception('Courseid is invalid: ' . $courseid . '(cleaned value: ' . $cleanedvalue . ')');
            }
            $cleanedvalues[] = $cleanedvalue;
        }

        // Retrieve the courses.
        $courses = $DB->get_records_list('course', 'id', $cleanedvalues, 'id');
        $context = context_system::instance();
        self::validate_context($context);

        // Finally retrieve each courses information.
        $returnedcourses = array();

        foreach ($courses as $course) {
            $coursedetails = course_get_thumbnail($course);

            if (!empty($coursedetails)) {
                $returnedcourses[] = $coursedetails;
            }
        }
        return $returnedcourses;
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     * @since Moodle 2.2
     * @deprecated Moodle 2.5 MDL-38030 - Please do not call this function any more.
     * @see core_user_external::get_users_by_field_returns()
     */
    public static function get_course_thumbnail_by_id_returns()
    {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'id' => new external_value(PARAM_INT, 'ID of the course'),
                    'fullname' => new external_value(PARAM_RAW, 'The fullname of the course'),
                    'thumbnail_image' => new external_value(PARAM_URL, 'Thumbnail course URL - small version'),
                )
            )
        );
    }

    public static function get_remote_course_mods_parameters()
    {
        return new external_function_parameters(
            array('courseid' => new external_value(PARAM_INT, 'course id'),
            )
        );
    }

    public static function get_remote_course_mods($courseid)

    {
        global $DB;

        //validate parameter
        $params = self::validate_parameters(self::get_remote_course_mods_parameters(), array('courseid' => $courseid));
        return $DB->get_records_sql("SELECT cm.*, m.name as modname
                                       FROM {modules} m, {course_modules} cm
                                      WHERE cm.course = ? AND cm.module = m.id AND m.visible = 1",
            array($courseid));
    }

    public static function get_remote_course_mods_returns()
    {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'id' => new external_value(PARAM_INT, 'ID of the course'),
                    'course' => new external_value(PARAM_INT, 'The fullname of the course'),
                    'module' => new external_value(PARAM_INT, 'Thumbnail course URL - small version'),
                    'instance' => new external_value(PARAM_INT, 'Thumbnail course URL - medium version'),
                    'section' => new external_value(PARAM_INT, 'Thumbnail course URL - big version'),
                    'idnumber' => new external_value(PARAM_TEXT, 'The fullname of the course'),
                    'added' => new external_value(PARAM_INT, 'Thumbnail course URL - small version'),
                    'score' => new external_value(PARAM_INT, 'Thumbnail course URL - medium version'),
                    'indent' => new external_value(PARAM_INT, 'Thumbnail course URL - big version'),
                    'visible' => new external_value(PARAM_INT, 'The fullname of the course'),
                    'visibleold' => new external_value(PARAM_INT, 'Thumbnail course URL - small version'),
                    'groupmode' => new external_value(PARAM_INT, 'Thumbnail course URL - medium version'),
                    'groupingid' => new external_value(PARAM_INT, 'Thumbnail course URL - big version'),
                    'completion' => new external_value(PARAM_INT, 'The fullname of the course'),
                    'completiongradeitemnumber' => new external_value(PARAM_INT, 'Thumbnail course URL - small version'),
                    'completionview' => new external_value(PARAM_INT, 'Thumbnail course URL - medium version'),
                    'completionexpected' => new external_value(PARAM_INT, 'Thumbnail course URL - big version'),
                    'showdescription' => new external_value(PARAM_INT, 'The fullname of the course'),
                    'availability' => new external_value(PARAM_RAW, 'Thumbnail course URL - small version'),
                    'modname' => new external_value(PARAM_TEXT, 'Thumbnail course URL - medium version'),
                )
            )
        );
    }

    public static function get_remote_course_sections_parameters()
    {
        return new external_function_parameters(
            array('courseid' => new external_value(PARAM_INT, 'course id'),
            )
        );
    }

    public static function get_remote_course_sections($courseid)
    {
        global $DB;
        $warnings = array();

        //validate parameter
        $params = self::validate_parameters(self::get_remote_course_sections_parameters(), array(
            'courseid' => $courseid
        ));

        $sections = $DB->get_records('course_sections', array('course' => $courseid), 'section ASC', 'id,course,section,name,sequence');

        if(!$sections) {
            $sections = array();
        }

        $result = array();
        $result['sections'] = $sections;
        $result['warnings'] = $warnings;
        return $result;
    }

    public static function get_remote_course_sections_returns()
    {
        return new external_single_structure(
            array(
                'sections' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'id' => new external_value(PARAM_INT, 'ID of the course'),
                            'course' => new external_value(PARAM_INT, 'The fullname of the course'),
                            'section' => new external_value(PARAM_INT, 'Thumbnail course URL - big version'),
                            'name' => new external_value(PARAM_TEXT, 'The fullname of the course'),
                            'sequence' => new external_value(PARAM_RAW, 'Thumbnail course URL - big version'),
                        )
                    ), 'section data'
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    /**
     * @author TiepPT
     * @description validation parametters
     * @return external_function_parameters
     */
    public static function get_course_module_by_cmid_parameters()
    {
        return new external_function_parameters(
            array(
                'modulename' => new external_value(PARAM_RAW, 'The module name'),
                'cmid' => new external_value(PARAM_INT, 'The module id'),
                'courseid' => new external_value(PARAM_INT, 'The course id'),
                'validate' => new external_value(PARAM_BOOL, 'The validation for context'),
            )
        );
    }


    public static function get_course_module_by_cmid($modulename, $cmid, $courseid, $validate)
    {
        //validate parameter
        $params = self::validate_parameters(self::get_course_module_by_cmid_parameters(),
            array(
                'modulename' => $modulename,
                'cmid' => $cmid,
                'courseid' => $courseid,
                'validate' => $validate
            ));
        $warnings = array();
        $cm = get_coursemodule_from_id($params['modulename'], $params['cmid'], $params['courseid'], true, MUST_EXIST);
        $info = $cm;

        if ($params['validate']) {
            $context = context_module::instance($cm->id);
            self::validate_context($context);
            // Format name.
            $info->name = external_format_string($cm->name, $context->id);
        }

        $result = array();
        $result['cm'] = $info;
        $result['warnings'] = $warnings;
        return $result;
    }

    /**
     * @description Returns description of method parameters
     * @return external_function_parameters
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
     */
    public static function get_course_module_by_cmid_returns()
    {
        return core_course_external::get_course_module_returns();
    }

    // MINHND
    public static function get_course_info_by_course_id_parameters()
    {
        return new external_function_parameters(
            array('courseid' => new external_value(PARAM_INT, 'course id'),
            )
        );
    }

    public static function get_course_info_by_course_id($courseid)
    {
        global $DB;

        //validate parameter
        $params = self::validate_parameters(self::get_course_info_by_course_id_parameters(), array('courseid' => $courseid));

        $course = $DB->get_record('course', array('id' => $params['courseid']), "*", MUST_EXIST);
        $courseinfo = $DB->get_record('course_info', array('course' => $params['courseid']), "*", MUST_EXIST);

        $results = array(
            'coursename' => $course->fullname,
            'courseinfo' => $courseinfo->info,
            'validatetime' => $courseinfo->validatetime,
            'note' => $courseinfo->note,
        );

        return $results;
    }

    public static function get_course_info_by_course_id_returns()
    {
        return new external_single_structure(
            array(
                'coursename' => new external_value(PARAM_RAW, 'Course fullname'),
                'courseinfo' => new external_value(PARAM_RAW, 'Course infomation'),
                'validatetime' => new external_value(PARAM_INT, 'Validate Time', VALUE_DEFAULT),
                'note' => new external_value(PARAM_RAW, 'Note'),
            )
        );
    }

    public static function get_course_module_info_parameters()
    {
        return new external_function_parameters(
            array('modname' => new external_value(PARAM_TEXT, 'module name'),
                'instanceid' => new external_value(PARAM_TEXT, 'instance id'),
            )
        );
    }

    public static function get_course_module_info($modname, $instanceid)
    {
        global $DB;

        //validate parameter
        $params = self::validate_parameters(self::get_course_module_info_parameters(), array('modname' => $modname, 'instanceid' => $instanceid));

        return $DB->get_record($modname, array('id' => $instanceid), 'name, intro, introformat', MUST_EXIST);
    }

    public static function get_course_module_info_returns()
    {
        return new external_single_structure(
            array(
                'name' => new external_value(PARAM_TEXT, 'The fullname of the course'),
                'intro' => new external_value(PARAM_RAW, 'Thumbnail course URL - big version'),
                'introformat' => new external_value(PARAM_INT, 'The fullname of the course'),
            )
        );
    }

    /**
     * @description Returns description of method parameters
     * @return external_function_parameters
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
     */
    public static function get_name_modules_by_id_parameters()
    {
        return new external_function_parameters(
            array('id' => new external_value(PARAM_INT, 'module id')
            )
        );
    }

    /**
     * Get name of modules by id
     *
     * @param $id
     * @return mixed
     * @throws invalid_parameter_exception
     */
    public static function get_name_modules_by_id($id)
    {
        global $DB;

        //validate parameter
        $params = self::validate_parameters(self::get_name_modules_by_id_parameters(),
            array('id' => $id)
        );

        return $DB->get_field('modules', 'name', array('id' => $params['id']));
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     * @since Moodle 3.0
     */
    public static function get_name_modules_by_id_returns()
    {
        return new external_value(PARAM_RAW, 'name');
    }

    /**
     * Get modules by id
     *
     * @param $id
     * @return mixed
     * @throws invalid_parameter_exception
     */
    public static function get_modules_by_id_parameters()
    {
        return new external_function_parameters(
            array('id' => new external_value(PARAM_INT, 'module id')
            )
        );
    }

    public static function get_modules_by_id($id)
    {
        global $DB;

        //validate parameter
        $params = self::validate_parameters(self::get_modules_by_id_parameters(),
            array('id' => $id)
        );

        return $module = $DB->get_record('modules', array('id' => $params['id']), '*', MUST_EXIST);
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     * @since Moodle 3.0
     */
    public static function get_modules_by_id_returns()
    {
        return new external_single_structure(
            array(
                'id' => new external_value(PARAM_INT, 'course id'),
                'name' => new external_value(PARAM_TEXT, 'course short name'),
                'cron' => new external_value(PARAM_INT, 'category id'),
                'lastcron' => new external_value(PARAM_INT, 'full name'),
                'search' => new external_value(PARAM_TEXT, 'short name'),
                'visible' => new external_value(PARAM_INT, 'short name'),
            )
        );
    }

    public static function get_remote_course_section_nav_parameters()
    {
        return new external_function_parameters(
            array(
                'sectionid' => new external_value(PARAM_INT, 'the section id'),
            )
        );
    }

    public static function get_remote_course_section_nav($sectionid)
    {
        global $DB;

        $params = self::validate_parameters(self::get_remote_course_section_nav_parameters(), array(
            'sectionid' => $sectionid
        ));

        $sql = 'SELECT c.*, cs.section AS sectionnumber
                        FROM {course} c
                        LEFT JOIN {course_sections} cs ON cs.course = c.id
                        WHERE cs.id = ?';
        return $DB->get_record_sql($sql, array($sectionid), MUST_EXIST);
    }

    public static function get_remote_course_section_nav_returns()
    {
        return new external_single_structure(
            array(
                'id' => new external_value(PARAM_INT, 'course id'),
                'shortname' => new external_value(PARAM_TEXT, 'course short name'),
                'category' => new external_value(PARAM_INT, 'category id'),
                'fullname' => new external_value(PARAM_TEXT, 'full name'),
                'shortname' => new external_value(PARAM_TEXT, 'short name'),
                'idnumber' => new external_value(PARAM_RAW, 'id number', VALUE_OPTIONAL),
                'summary' => new external_value(PARAM_RAW, 'summary'),
                'summaryformat' => new external_format_value('summary'),
                'format' => new external_value(PARAM_PLUGIN,
                    'course format: weeks, topics, social, site,..'),
                'showgrades' => new external_value(PARAM_INT,
                    '1 if grades are shown, otherwise 0', VALUE_OPTIONAL),
                'newsitems' => new external_value(PARAM_INT,
                    'number of recent items appearing on the course page', VALUE_OPTIONAL),
                'startdate' => new external_value(PARAM_INT,
                    'timestamp when the course start'),
                'marker' => new external_value(PARAM_INT,
                    '(deprecated, use courseformatoptions) number of weeks/topics',
                    VALUE_OPTIONAL),
                'legacyfiles' => new external_value(PARAM_INT,
                    '(deprecated, use courseformatoptions) number of weeks/topics',
                    VALUE_OPTIONAL),
                'maxbytes' => new external_value(PARAM_INT,
                    'largest size of file that can be uploaded into the course',
                    VALUE_OPTIONAL),
                'showreports' => new external_value(PARAM_INT,
                    'are activity report shown (yes = 1, no =0)', VALUE_OPTIONAL),
                'visible' => new external_value(PARAM_INT,
                    '1: available to student, 0:not available', VALUE_OPTIONAL),
                'visibleold' => new external_value(PARAM_INT,
                    '(deprecated, use courseformatoptions) How the hidden sections in the course are displayed to students',
                    VALUE_OPTIONAL),
                'groupmode' => new external_value(PARAM_INT, 'no group, separate, visible',
                    VALUE_OPTIONAL),
                'groupmodeforce' => new external_value(PARAM_INT, '1: yes, 0: no',
                    VALUE_OPTIONAL),
                'defaultgroupingid' => new external_value(PARAM_INT, 'default grouping id',
                    VALUE_OPTIONAL),
                'timecreated' => new external_value(PARAM_INT,
                    'timestamp when the course have been created', VALUE_OPTIONAL),
                'timemodified' => new external_value(PARAM_INT,
                    'timestamp when the course have been modified', VALUE_OPTIONAL),
                'enablecompletion' => new external_value(PARAM_INT,
                    'Enabled, control via completion and activity settings. Disbaled,
                                    not shown in activity settings.',
                    VALUE_OPTIONAL),
                'completionnotify' => new external_value(PARAM_INT,
                    '1: yes 0: no', VALUE_OPTIONAL),
                'lang' => new external_value(PARAM_SAFEDIR,
                    'forced course language', VALUE_OPTIONAL),
                'theme' => new external_value(PARAM_TEXT,
                    'name of the force theme', VALUE_OPTIONAL),
                'calendartype' => new external_value(PARAM_TEXT,
                    'name of the force theme', VALUE_OPTIONAL),
                'cacherev' => new external_value(PARAM_ALPHANUMEXT, 'course format option name'),
                'sectionnumber' => new external_value(PARAM_INT, 'section number')
            )
        );
    }

    public static function get_remote_course_format_options_parameters()
    {
        return new external_function_parameters(
            array(
                'courseid' => new external_value(PARAM_INT, 'the section id'),
                'format' => new external_value(PARAM_TEXT, 'the section id'),
                'sectionid' => new external_value(PARAM_INT, 'the section id'),
            )
        );
    }

    public static function get_remote_course_format_options($courseid, $format, $sectionid)
    {
        global $DB;

        $params = self::validate_parameters(self::get_remote_course_format_options_parameters(), array(
            'courseid' => $courseid,
            'format' => $format,
            'sectionid' => $sectionid
        ));

        return $DB->get_records('course_format_options',
            array('courseid' => $courseid,
                'sectionid' => $sectionid
            ), '', 'id,name,value');
    }

    public static function get_remote_course_format_options_returns()
    {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'id' => new external_value(PARAM_INT, 'course id'),
                    'name' => new external_value(PARAM_TEXT, 'course id'),
                    'value' => new external_value(PARAM_RAW, 'longtext'),
                )
            )
        );
    }

    public static function get_modules_by_parameters()
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

    public static function get_modules_by($parameters, $sort, $mustexists)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::get_modules_by_parameters(), array(
            'parameters' => $parameters,
            'sort' => $sort,
            'mustexists' => $mustexists
        ));

        $arr = array();
        foreach ($params['parameters'] as $p) {
            $arr = array_merge($arr, array($p['name'] => $p['value']));
        }

        $result = array();

        if ($params['mustexists'] === FALSE) {
            $module = $DB->get_record("modules", $arr);
        } else if ($params['mustexists'] === FALSE && $params['sort'] != '') {
            $module = $DB->get_record("modules", $arr, $params['sort']);
        } else {
            $module = $DB->get_record("modules", $arr, '*', MUST_EXIST);
        }

        if (!$module) {
            $module = new stdClass();
        }

        $result['module'] = $module;
        $result['warnings'] = $warnings;
        return $result;
    }

    public static function get_modules_by_returns()
    {
        return new external_single_structure(
            array(
                'module' => new external_single_structure(
                    array(
                        'id' => new external_value(PARAM_INT, 'module id'),
                        'name' => new external_value(PARAM_RAW, 'module name'),
                        'cron' => new external_value(PARAM_INT, 'cron'),
                        'lastcron' => new external_value(PARAM_INT, 'last cron'),
                        'search' => new external_value(PARAM_RAW, 'search'),
                        'visible' => new external_value(PARAM_INT, 'visible')
                    )
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    /**
     * Describes the parameters for delete_remote_course_modules_completion_by_cmid_hostip
     *
     * @return external_external_function_parameters
     */
    public static function delete_remote_course_modules_completion_by_cmid_hostip_parameters()
    {
        return new external_function_parameters(
            array(
                'coursemoduleid' => new external_value(PARAM_INT, 'The id of course module'),
                'hostip' => new external_value(PARAM_TEXT, 'The ip address on host')
            )
        );
    }

    /**
     * Delete tbl course_modules_completetion by cmid and hostip
     *
     * @param int $coursemoduleid - The id of course modules
     * @param string $hostip - The ip_address on host
     *
     * @return bool $result true if success
     */
    public static function delete_remote_course_modules_completion_by_cmid_hostip($coursemoduleid, $hostip)
    {
        global $DB;

        $params = self::validate_parameters(self::delete_remote_course_modules_completion_by_cmid_hostip_parameters(), array(
            'coursemoduleid' => $coursemoduleid,
            'hostip' => $hostip,
        ));

        $sql = 'SELECT u.id 
                FROM {user} u 
                JOIN {mnet_host} mh 
                ON u.mnethostid = mh.id 
                WHERE mh.ip_address = ?';

        $result = $DB->delete_records_select('course_modules_completion', 'coursemoduleid = ? AND userid IN(' . $sql . ')',
            array($params['coursemoduleid'], $params['hostip']));

        return $result;
    }

    /**
     * Describes the delete_remote_course_modules_completion_by_cmid_hostip returns value.
     *
     * @return external_single_structure
     * @since Moodle 3.1
     */
    public static function delete_remote_course_modules_completion_by_cmid_hostip_returns()
    {
        return new external_value(PARAM_INT, 'true if success');
    }

    /**
     * Describes the parameters for get_remote_course_modules_completion
     *
     * @return external_external_function_parameters
     */
    public static function get_remote_course_modules_completion_parameters()
    {
        return new external_function_parameters(
            array(
                'coursemoduleid' => new external_value(PARAM_INT, 'The id of course module'),
                'courseid' => new external_value(PARAM_INT, 'The id of course'),
                'hostip' => new external_value(PARAM_TEXT, 'The ip address on host'),
                'field' => new external_value(PARAM_TEXT, 'The field of table to get'),
                'mode' => new external_value(PARAM_TEXT, 'The mode to operate. Current: singlerc, wholecourse, normal'),
                'userid' => new external_value(PARAM_INT, 'The id of user')
            )
        );
    }

    /**
     * Get records tbl course_modules_completetion by cmid and hostip
     *
     * @param int $coursemoduleid - The id of course modules
     * @param int $courseid - The id of course
     * @param string $hostip - The ip_address on host
     * @param string $field - The field of table to get
     * @param string $mode - The mode to operate. Current: singlerc, wholecourse, normal
     * @param string $userid - The id of user
     *
     * @return mixed $result list records table and warnings
     */
    public static function get_remote_course_modules_completion($coursemoduleid, $courseid, $hostip, $field, $mode, $userid)
    {
        global $DB;

        $warnings = array();

        $result = array();

        $params = self::validate_parameters(self::get_remote_course_modules_completion_parameters(), array(
            'coursemoduleid' => $coursemoduleid,
            'courseid' => $courseid,
            'hostip' => $hostip,
            'field' => $field,
            'mode' => $mode,
            'userid' => $userid,
        ));

        $sql = "SELECT u.id
                FROM {user} u 
                JOIN {mnet_host} mh 
                ON u.mnethostid = mh.id AND mh.ip_address = ?";

        switch ($params['mode']) {
            case 'normal':
                $result['cmc'] = $DB->get_records_select('course_modules_completion', 'coursemoduleid = ? AND userid IN(' . $sql . ')',
                    array($params['coursemoduleid'], $params['hostip']), '', $params['field']);
                // If result false return empty array
                if (!$result['cmc']) {
                    $result['cmc'] = array();
                } else {
                    foreach ($result['cmc'] as $cmc) {
                        $cmc->email = self::change_userid_to_email($cmc->userid);
                    }
                }
                break;
            case 'wholecourse':
                $result['cmc'] = $DB->get_records_sql("
                    SELECT
                        cmc.*
                    FROM
                        {course_modules} cm
                        INNER JOIN {course_modules_completion} cmc ON cmc.coursemoduleid=cm.id
                    WHERE
                        cm.course=? AND cmc.userid=?",
                    array($params['courseid'], $params['userid']), '', $params['field']);
                // If result false return empty array
                if (!$result['cmc']) {
                    $result['cmc'] = array();
                } else {
                    foreach ($result['cmc'] as $cmc) {
                        $cmc->email = self::change_userid_to_email($cmc->userid);
                    }
                }
                break;
            case 'singlerc':
                $result['scmc'] = $DB->get_record_select('course_modules_completion', 'coursemoduleid = ? AND userid = ?',
                    array($params['coursemoduleid'], $params['userid']), $params['field']);
                // If result false return empty array
                if (!$result['scmc']) {
                    $result['scmc'] = array();
                } else {
                    $result['scmc']->email = self::change_userid_to_email($result['scmc']->userid);
                }
                break;
            default:
                break;
        }

        $result['warnings'] = array();
        return $result;
    }

    /**
     * Describes the get_remote_course_modules_completion returns value.
     *
     * @return external_single_structure
     * @since Moodle 3.1
     */
    public static function get_remote_course_modules_completion_returns()
    {
        return new external_single_structure(
            array(
                'cmc' => new external_multiple_structure(
                    self::get_course_module_completion_structure(VALUE_OPTIONAL), 'course module completion', VALUE_OPTIONAL),
                'scmc' => self::get_course_module_completion_structure(VALUE_OPTIONAL),
                'warnings' => new external_warnings()
            )
        );
    }


    /**
     * Creates a couse_module_completion structure.
     *
     * @return external_single_structure the grade_grades structure
     */
    private static function get_course_module_completion_structure($required = VALUE_REQUIRED)
    {
        return new external_single_structure(
            array(
                'id' => new external_value(PARAM_INT, 'The id of course module completion', VALUE_OPTIONAL),
                'coursemoduleid' => new external_value(PARAM_INT, 'The id of course module', VALUE_OPTIONAL),
                'userid' => new external_value(PARAM_INT, 'The id of user', VALUE_OPTIONAL),
                'email' => new external_value(PARAM_TEXT, 'The email of user', VALUE_OPTIONAL),
                'completionstate' => new external_value(PARAM_INT, 'Completion state', VALUE_OPTIONAL),
                'viewed' => new external_value(PARAM_INT, 'View', VALUE_OPTIONAL),
                'timemodified' => new external_value(PARAM_INT, 'Time viewer', VALUE_OPTIONAL)
            ), 'course module completion', $required
        );
    }

    /**
     * Change userid to email user serve to send data on host
     *
     * @param int $userid - The id of user
     * @return string     - The email of user
     */
    public static function change_userid_to_email($userid)
    {
        global $DB;

        return $DB->get_record('user', array('id' => $userid), 'email')->email;
    }

    /**
     * Describes the parameters for create_update_remote_course_modules_completion
     *
     * @return external_external_function_parameters
     */
    public static function create_update_remote_course_modules_completion_parameters()
    {
        return new external_function_parameters(
            array(
                'coursemoduleid' => new external_value(PARAM_INT, 'The id of course module'),
                'userid' => new external_value(PARAM_INT, 'The id of user'),
                'completionstate' => new external_value(PARAM_INT, 'Completion state'),
                'viewed' => new external_value(PARAM_INT, 'View'),
                'timemodified' => new external_value(PARAM_INT, 'Time viewer')
            )
        );
    }

    /**
     * Update & create table "course_modules_completion" on hub
     *
     * @param int $coursemoduleid - The id of course modules
     * @param int $userid - The id of user on hub
     * @param int $completionstate - The state of completion
     * @param int $viewed - The state of viewed
     * @param int $timemodified - The time modified
     *
     * @return int $result         - The id of course modules completion
     */
    public static function create_update_remote_course_modules_completion($coursemoduleid, $userid, $completionstate, $viewed, $timemodified)
    {
        global $DB;

        $params = self::validate_parameters(self::create_update_remote_course_modules_completion_parameters(), array(
            'coursemoduleid' => $coursemoduleid,
            'userid' => $userid,
            'completionstate' => $completionstate,
            'viewed' => $viewed,
            'timemodified' => $timemodified,
        ));

        $cmc = $DB->get_record('course_modules_completion', array('coursemoduleid' => $params['coursemoduleid'],
            'userid' => $params['userid']));

        $params = (object)$params;

        $trans = $DB->start_delegated_transaction();

        if (!$cmc) {
            $result = $DB->insert_record('course_modules_completion', $params);
        } else {
            $params->id = $cmc->id;
            $result = $DB->update_record('course_modules_completion', $params);
        }

        $trans->allow_commit();

        return $result;
    }

    /**
     * Describes the create_update_remote_course_modules_completion returns value.
     *
     * @return external_single_structure
     * @since Moodle 3.1
     */
    public static function create_update_remote_course_modules_completion_returns()
    {
        return new external_value(PARAM_INT, 'True(1) if success');
    }

    public static function get_course_completion_progress_parameters()
    {
        return new external_function_parameters(
            array(
                'courseid' => new external_value(PARAM_INT, ' the id of course'),
                'userid' => new external_value(PARAM_INT, ' the id of course'),
                'totalmoduletracking' => new external_value(PARAM_INT, 'the total count of couse module is tracking')
            )
        );
    }

    public static function get_course_completion_progress($courseid, $userid, $totalmoduletracking)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::get_course_completion_progress_parameters(), array(
            'courseid' => $courseid,
            'userid' => $userid,
            'totalmoduletracking' => $totalmoduletracking
        ));

        $result = array();

        $arr = array();
        $arr['courseid'] = $params['courseid'];
        $arr['userid'] = $params['userid'];


        $completion = 0;

        if ($params['totalmoduletracking'] > 0) {
            $sql = "SELECT COUNT(*) FROM {course_modules_completion} cmc
                LEFT JOIN {course_modules} cm
                ON cmc.coursemoduleid = cm.id
                WHERE cm.course = :courseid AND cmc.userid = :userid AND cmc.completionstate <> 0";
            $completioncount = $DB->count_records_sql($sql, $arr);

            if ($completioncount > 0) {
                $completion = ($completioncount * 100) / $totalmoduletracking;
            }
        }

        $result['completion'] = intval($completion);
        $result['warnings'] = $warnings;
        return $result;
    }

    public static function get_course_completion_progress_returns()
    {
        return new external_single_structure(
            array(
                'completion' => new external_value(PARAM_TEXT, 'the completion'),
                'warnings' => new external_warnings()
            )
        );
    }

    /**
     * Describes the parameters for delete_remote_course_completions
     *
     * @return external_external_function_parameters
     */
    public static function delete_remote_course_completions_parameters()
    {
        return new external_function_parameters(
            array(
                'courseid' => new external_value(PARAM_INT, 'The id of course'),
                'hostip' => new external_value(PARAM_TEXT, 'The ip address on host')
            )
        );
    }

    /**
     * Delete tbl course_completions by cmid and hostip
     *
     * @param int $courseid - The id of course
     * @param string $hostip - The ip_address on host
     *
     * @return bool $result true if success
     */
    public static function delete_remote_course_completions($courseid, $hostip)
    {
        global $DB;

        $params = self::validate_parameters(self::delete_remote_course_completions_parameters(), array(
            'courseid' => $courseid,
            'hostip' => $hostip,
        ));

        $sql = 'SELECT u.id 
                FROM {user} u 
                JOIN {mnet_host} mh 
                ON u.mnethostid = mh.id 
                WHERE mh.ip_address = ?';

        $result = $DB->delete_records_select('course_completions', 'course = ? AND userid IN(' . $sql . ')',
            array($params['courseid'], $params['hostip']));

        return $result;
    }

    /**
     * Describes the delete_remote_course_completions returns value.
     *
     * @return external_single_structure
     * @since Moodle 3.1
     */
    public static function delete_remote_course_completions_returns()
    {
        return new external_value(PARAM_INT, 'true if success');
    }

    /**
     * Describes the parameters for delete_remote_course_completion_crit_compl
     *
     * @return external_external_function_parameters
     */
    public static function delete_remote_course_completion_crit_compl_parameters()
    {
        return new external_function_parameters(
            array(
                'courseid' => new external_value(PARAM_INT, 'The id of course'),
                'hostip' => new external_value(PARAM_TEXT, 'The ip address on host')
            )
        );
    }

    /**
     * Delete tbl course_completion_crit_compl by cmid and hostip
     *
     * @param int $courseid - The id of course
     * @param string $hostip - The ip_address on host
     *
     * @return bool $result true if success
     */
    public static function delete_remote_course_completion_crit_compl($courseid, $hostip)
    {
        global $DB;

        $params = self::validate_parameters(self::delete_remote_course_completion_crit_compl_parameters(), array(
            'courseid' => $courseid,
            'hostip' => $hostip,
        ));

        $sql = 'SELECT u.id 
                FROM {user} u 
                JOIN {mnet_host} mh 
                ON u.mnethostid = mh.id 
                WHERE mh.ip_address = ?';

        $result = $DB->delete_records_select('course_completion_crit_compl', 'course = ? AND userid IN(' . $sql . ')',
            array($params['courseid'], $params['hostip']));

        return $result;
    }

    /**
     * Describes the delete_remote_course_completion_crit_compl returns value.
     *
     * @return external_single_structure
     * @since Moodle 3.1
     */
    public static function delete_remote_course_completion_crit_compl_returns()
    {
        return new external_value(PARAM_INT, 'true if success');
    }


    public static function get_list_course_completion_parameters()
    {
        return new external_function_parameters(
            array(
                'userid' => new external_value(PARAM_INT, 'the id of user')
            )
        );
    }

    public static function get_list_course_completion($userid)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::get_list_course_completion_parameters(), array(
            'userid' => $userid
        ));

        $result = array();

        $sql = "SELECT cc.course FROM {course} c 
                LEFT JOIN {course_completions} cc ON c.id = cc.course
                WHERE cc.timecompleted IS NOT NULL AND cc.userid = ?";

        $completions = $DB->get_records_sql($sql, array($params['userid']));

        if (!$completions) {
            $completions = array();
        }

        $result['completions'] = $completions;
        $result['warnings'] = $warnings;

        return $result;
    }

    public static function get_list_course_completion_returns()
    {
        return new external_single_structure(
            array(
                'completions' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'course' => new external_value(PARAM_INT, 'the course id'),
                        )
                    ), 'the course id'
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    /**
     * Describes the parameters for count_remote_user_data_completion
     *
     * @return external_external_function_parameters
     */
    public static function count_remote_user_data_completion_parameters()
    {
        return new external_function_parameters(
            array(
                'coursemoduleid' => new external_value(PARAM_INT, 'The id of course module'),
                'hostip' => new external_value(PARAM_TEXT, 'The ip address on host')
            )
        );
    }

    /**
     * Determines how much completion data exists for an activity. This is used when
     * deciding whether completion information should be 'locked' in the module
     * editing form.
     *
     * @param int $courseid - The id of course
     * @param string $hostip - The ip_address on host
     *
     * @return bool $result true if success
     */
    public static function count_remote_user_data_completion($coursemoduleid, $hostip)
    {
        global $DB;

        $params = self::validate_parameters(self::count_remote_user_data_completion_parameters(), array(
            'coursemoduleid' => $coursemoduleid,
            'hostip' => $hostip,
        ));

        $sql = 'SELECT u.id 
                FROM {user} u 
                JOIN {mnet_host} mh 
                ON u.mnethostid = mh.id 
                WHERE mh.ip_address = ?';

        $result = $DB->get_field_sql("
                SELECT
                    COUNT(1)
                FROM
                    {course_modules_completion}
                WHERE
                    coursemoduleid=? AND completionstate<>0 AND userid IN(" . $sql . ") ",
            array($params['coursemoduleid'], $params['hostip']));;

        return $result;
    }

    /**
     * Describes the count_remote_user_data_completion returns value.
     *
     * @return external_single_structure
     * @since Moodle 3.1
     */
    public static function count_remote_user_data_completion_returns()
    {
        return new external_value(PARAM_INT, 'count user data completion');
    }

    /**
     * Describes the parameters for get_remote_completion_fetch_all_helper
     *
     * @return external_external_function_parameters
     */
    public static function get_remote_completion_fetch_all_helper_parameters()
    {
        return new external_function_parameters(
            array(
                'table' => new external_value(PARAM_TEXT, 'The name of table to get'),
                'course' => new external_value(PARAM_INT, 'The id of course'),
            )
        );
    }

    /**
     * Factory method - uses the parameters to retrieve all matching instances from the DB.
     *
     * @final
     * @param string $table The table name to fetch from
     * @param string $classname The class that you want the result instantiated as
     * @param array $params Any params required to select the desired row
     * @return mixed array of object instances or false if not found
     */
    public static function get_remote_completion_fetch_all_helper($table, $course)
    {
        global $DB, $CFG;

        $result = array();

        $warnings = array();

        $params = self::validate_parameters(self::get_remote_completion_fetch_all_helper_parameters(), array(
            'table' => $table,
            'course' => $course,
        ));

        $result[$table] = $DB->get_records($table, array('course' => $params['course']));

        if (!$result[$table]) {
            $result[$table] = array();
        }

        $result['warnings'] = $warnings;
        return $result;
    }

    /**
     * Describes the get_remote_completion_fetch_all_helper returns value.
     *
     * @return external_single_structure
     * @since Moodle 3.1
     */
    public static function get_remote_completion_fetch_all_helper_returns()
    {
        return new external_single_structure(
            array(
                'course_completion_criteria' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'course' => new external_value(PARAM_INT, 'The id of course', VALUE_OPTIONAL),
                            'criteriatype' => new external_value(PARAM_INT, 'The criteria types integer constant', VALUE_OPTIONAL),
                            'module' => new external_value(PARAM_TEXT, 'The name of the module', VALUE_OPTIONAL),
                            'moduleinstance' => new external_value(PARAM_INT, 'The id of the activity/resource module or role', VALUE_OPTIONAL),
                            'courseinstance' => new external_value(PARAM_INT, 'The id of course', VALUE_OPTIONAL),
                            'enrolperiod' => new external_value(PARAM_INT, 'The number of seconds after enrolment', VALUE_OPTIONAL),
                            'timeend' => new external_value(PARAM_INT, 'The timestamp of the date for course completion', VALUE_OPTIONAL),
                            'gradepass' => new external_value(PARAM_FLOAT, 'The course grade required to complete this criteria', VALUE_OPTIONAL),
                            'role' => new external_value(PARAM_INT, 'The role id that can mark \'student\'s as complete in the course', VALUE_OPTIONAL),
                        )
                    )
                    , 'Information table completion_criteria', VALUE_OPTIONAL),
                'course_completion_aggr_methd' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'course' => new external_value(PARAM_INT, 'The id of the course that the course completion aggregation relates to', VALUE_OPTIONAL),
                            'criteriatype' => new external_value(PARAM_INT, 'The criteria type\'s integer constant (\'role\', \'activity\') or null if \'overall\' course aggregation.', VALUE_OPTIONAL),
                            'method' => new external_value(PARAM_INT, '\'1\'=\'all\', \'2\'=\'any\', \'3\'=\'fraction\', \'4\'=\'unit\'', VALUE_OPTIONAL),
                            'value' => new external_value(PARAM_INT, 'null for \'all\' and \'any\', 0..1 for \'fraction\', int > 0 for \'unit\'', VALUE_OPTIONAL),
                        )
                    )
                    , 'Information table completion_aggregation', VALUE_OPTIONAL),
                'course_completions' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'id' => new external_value(PARAM_INT, 'The id of tbl completion_completion', VALUE_OPTIONAL),
                            'userid' => new external_value(PARAM_INT, 'The id of the user who has completed the course', VALUE_OPTIONAL),
                            'course' => new external_value(PARAM_INT, 'The id of the completed course', VALUE_OPTIONAL),
                            'timeenrolled' => new external_value(PARAM_INT, 'Timestamp when the user was enrolled in the course. In the case of multiple enrollments, the earliest timestamp for a current enrollment is used. If this is reported as 0, the current time is used instead.', VALUE_OPTIONAL),
                            'timestarted' => new external_value(PARAM_INT, 'Timestamp when the user first made progress in the course', VALUE_OPTIONAL),
                            'timecompleted' => new external_value(PARAM_INT, 'Timestamp when the user completed the course', VALUE_OPTIONAL),
                            'reaggregate' => new external_value(PARAM_INT, 'Re aggregate', VALUE_OPTIONAL),
                        )
                    )
                    , 'Information table completion_completion', VALUE_OPTIONAL),
                'warnings' => new external_warnings()
            )
        );
    }

    /**
     * Describes the parameters for get_remote_modules
     *
     * @return external_external_function_parameters
     */
    public static function get_remote_modules_parameters()
    {
        return new external_function_parameters(
            array(
                'fields' => new external_value(PARAM_TEXT, 'The fields to get')
            )
        );
    }

    /**
     * Get all information about modules.
     *
     * @params string $fields  - The fields of table modules to get
     *
     * @return array $return   - The information tbl modules
     */
    public static function get_remote_modules($fields)
    {
        global $DB;

        $params = self::validate_parameters(self::get_remote_modules_parameters(), array(
            'fields' => $fields,
        ));
        $result = $DB->get_records('modules', array(), '', $params['fields']);

        return $result;
    }

    /**
     * Describes the get_remote_modules returns value.
     *
     * @return external_single_structure
     * @since Moodle 3.1
     */
    public static function get_remote_modules_returns()
    {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'id' => new external_value(PARAM_INT, 'The id of modules', VALUE_OPTIONAL),
                    'name' => new external_value(PARAM_TEXT, 'The name of modules', VALUE_OPTIONAL),
                    'cron' => new external_value(PARAM_INT, 'The cron', VALUE_OPTIONAL),
                    'lastcron' => new external_value(PARAM_INT, 'The last cron', VALUE_OPTIONAL),
                    'search' => new external_value(PARAM_TEXT, 'The string to search', VALUE_OPTIONAL),
                    'visible' => new external_value(PARAM_INT, 'The visible to see', VALUE_OPTIONAL),
                )
            )
        );
    }

    /**
     * Describes the parameters for update_remote_course_completions
     *
     * @return external_external_function_parameters
     */
    public static function update_remote_course_completions_parameters()
    {
        return new external_function_parameters(
            array(
                'userid' => new external_value(PARAM_INT, 'The id of user'),
                'course' => new external_value(PARAM_INT, 'The id of course'),
                'timeenrolled' => new external_value(PARAM_INT, 'The enrolled time course completion'),
                'timestarted' => new external_value(PARAM_INT, 'The started time course completion'),
                'timecompleted' => new external_value(PARAM_INT, 'The completed time course completion', VALUE_DEFAULT, NULL),
                'reaggregate' => new external_value(PARAM_INT, 'Reaggregate')
            )
        );
    }

    /**
     * Get all information about modules.
     *
     * @params int $userid          - The id of user
     * @params int $course          - The id of course
     * @params int $timeenrolled    - The enrolled time course completion
     * @params int $timestarted     - The started time course completion
     * @params int $timecompleted   - The completed time course completion
     * @params int $reaggregate     - Reaggregate
     *
     * @return bool $return         - True if update success
     */
    public static function update_remote_course_completions($userid, $course, $timeenrolled, $timestarted, $timecompleted, $arrgregate)
    {
        global $DB;

        $params = self::validate_parameters(self::update_remote_course_completions_parameters(), array(
            'userid' => $userid,
            'course' => $course,
            'timeenrolled' => $timeenrolled,
            'timestarted' => $timestarted,
            'timecompleted' => $timecompleted,
            'reaggregate' => $arrgregate,
        ));

        $data = (object)$params;
        $cc = $DB->get_record('course_completions', array('userid' => $params['userid'], 'course' => $params['course']));
        if (!$cc) {
            $result = $DB->insert_record('course_completions', $data);
        } else {
            $data->id = $cc->id;
            $result = $DB->update_record('course_completions', $data);
        }

        return $result;
    }

    /**
     * Describes the update_remote_course_completions returns value.
     *
     * @return external_single_structure
     * @since Moodle 3.1
     */
    public static function update_remote_course_completions_returns()
    {
        return new external_value(PARAM_INT, 'Return true if success');
    }

    public static function save_mdl_course_parameters()
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

    public static function save_mdl_course($modname, $data)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::save_mdl_course_parameters(), array(
            'modname' => $modname,
            'data' => $data
        ));

        $obj = new stdClass();

        foreach ($params['data'] as $element) {
            if ($element['name'] == "availability" && $element['value'] == "") {
                $obj->$element['name'] = null;
            } else {
                $obj->$element['name'] = $element['value'];
            }
        }

        $result = array();

        $transaction = $DB->start_delegated_transaction();

        $newid = $DB->insert_record($params['modname'], $obj);

        $transaction->allow_commit();

        $result['newid'] = $newid;
        $result['warnings'] = $warnings;

        return $result;
    }

    public static function save_mdl_course_returns()
    {
        return new external_single_structure(
            array(
                'newid' => new external_value(PARAM_INT, 'the new id'),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function update_mdl_course_parameters()
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

    public static function update_mdl_course($modname, $id, $data)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::update_mdl_course_parameters(), array(
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
            if ($element['name'] == "availability" && $element['value'] == "") {
                $obj->$element['name'] = null;
            } else {
                $obj->$element['name'] = $element['value'];
            }
        }

        $transaction = $DB->start_delegated_transaction();

        $cid = $DB->update_record($params['modname'], $obj);

        $transaction->allow_commit();

        $result['id'] = $cid;
        $result['warnings'] = $warnings;

        return $result;
    }

    public static function update_mdl_course_returns()
    {
        return new external_single_structure(
            array(
                'id' => new external_value(PARAM_INT, 'the id'),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function get_course_sections_by_parameters()
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
                'strictness' => new external_value(PARAM_INT, 'the strictness')
            )
        );
    }

    public static function get_course_sections_by($parameters, $strictness)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::get_course_sections_by_parameters(), array(
            'parameters' => $parameters,
            'strictness' => $strictness
        ));

        $result = array();
        $arr = array();

        foreach ($params['parameters'] as $p) {
            $arr = array_merge($arr, array($p['name'] => $p['value']));
        }

        $section = $DB->get_record('course_sections', $arr, '*', $params['strictness']);

        if (!$section) {
            $section = new stdClass();
        }

        $result['section'] = $section;
        $result['warnings'] = $warnings;

        return $result;
    }

    public static function get_course_sections_by_returns()
    {
        return new external_single_structure(
            array(
                'section' => new external_single_structure(
                    array(
                        'id' => new external_value(PARAM_INT, 'the id'),
                        'course' => new external_value(PARAM_INT, 'the course id'),
                        'section' => new external_value(PARAM_INT, 'the section'),
                        'name' => new external_value(PARAM_RAW, 'the name'),
                        'summary' => new external_value(PARAM_RAW, 'the summary'),
                        'summaryformat' => new external_value(PARAM_INT, 'the summary format'),
                        'sequence' => new external_value(PARAM_RAW, 'the sequence'),
                        'visible' => new external_value(PARAM_INT, 'the visible'),
                        'availability' => new external_value(PARAM_RAW, 'the availability')
                    )
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function get_course_modules_by_parameters()
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
                'strictness' => new external_value(PARAM_INT, 'the strictness')
            )
        );
    }

    public static function get_course_modules_by($parameters, $strictness)
    {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::get_course_modules_by_parameters(), array(
            'parameters' => $parameters,
            'strictness' => $strictness
        ));

        $result = array();
        $arr = array();

        foreach ($params['parameters'] as $p) {
            $arr = array_merge($arr, array($p['name'] => $p['value']));
        }

        $cm = $DB->get_record('course_modules', $arr, 'id,course', $params['strictness']);

        if (!$cm) {
            $cm = new stdClass();
        }

        $result['cm'] = $cm;
        $result['warnings'] = $warnings;

        return $result;
    }

    public static function get_course_modules_by_returns()
    {
        return new external_single_structure(
            array(
                'cm' => new external_single_structure(
                    array(
                        'id' => new external_value(PARAM_INT, 'The course module id'),
                        'course' => new external_value(PARAM_INT, 'The course id'),
                    )
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    public static function get_course_module_by_instance_parameters()
    {
        return new external_function_parameters(
            array(
                'module' => new external_value(PARAM_COMPONENT, 'The module name'),
                'instance' => new external_value(PARAM_INT, 'The module instance id')
            )
        );
    }

    public static function get_course_module_by_instance($module, $instance)
    {
        $params = self::validate_parameters(self::get_course_module_by_instance_parameters(),
            array(
                'module' => $module,
                'instance' => $instance,
            ));

        $warnings = array();
        $cm = get_coursemodule_from_instance($params['module'], $params['instance']);
        $info = $cm;

        $result = array();
        $result['cm'] = $info;
        $result['warnings'] = $warnings;
        return $result;
    }

    public static function get_course_module_by_instance_returns()
    {
        return core_course_external::get_course_module_returns();
    }
}
