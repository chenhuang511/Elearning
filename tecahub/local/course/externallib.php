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
class local_mod_course_external extends external_api
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
    public static function get_thumbnail_by_id_parameters()
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
    public static function get_thumbnail_by_id($courseids)
    {
        global $CFG, $COURSE, $DB;
        require_once($CFG->dirroot . "/course/lib.php");
        //validate parameter
        $params = self::validate_parameters(self::get_thumbnail_by_id_parameters(), array('courseids' => $courseids));

        // Clean the values.
        $cleanedvalues = array();
        foreach ($courseids as $courseid){
            $cleanedvalue = clean_param($courseid, PARAM_INT);
            if ( $courseid != $cleanedvalue) {
                throw new invalid_parameter_exception('Courseid is invalid: ' . $courseid . '(cleaned value: '.$cleanedvalue.')');
            }
            $cleanedvalues[] = $cleanedvalue;
        }

        // Retrieve the courses.
        $courses = $DB->get_records_list('course', 'id', $cleanedvalues, 'id');
        $context = context_system::instance();
        self::validate_context($context);
        
        // Finally retrieve each courses information.
        $returnedcourses = array();
        
        foreach ($courses as $course){
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
    public static function get_thumbnail_by_id_returns()
    {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'id'    => new external_value(PARAM_INT, 'ID of the course'),
                    'fullname'    => new external_value(PARAM_RAW, 'The fullname of the course'),
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
                    'id'    => new external_value(PARAM_INT, 'ID of the course'),
                    'course'    => new external_value(PARAM_INT, 'The fullname of the course'),
                    'module' => new external_value(PARAM_INT, 'Thumbnail course URL - small version'),
                    'instance' => new external_value(PARAM_INT, 'Thumbnail course URL - medium version'),
                    'section' => new external_value(PARAM_INT, 'Thumbnail course URL - big version'),
                    'idnumber'    => new external_value(PARAM_TEXT, 'The fullname of the course'),
                    'added' => new external_value(PARAM_INT, 'Thumbnail course URL - small version'),
                    'score' => new external_value(PARAM_INT, 'Thumbnail course URL - medium version'),
                    'indent' => new external_value(PARAM_INT, 'Thumbnail course URL - big version'),
                    'visible'    => new external_value(PARAM_INT, 'The fullname of the course'),
                    'visibleold' => new external_value(PARAM_INT, 'Thumbnail course URL - small version'),
                    'groupmode' => new external_value(PARAM_INT, 'Thumbnail course URL - medium version'),
                    'groupingid' => new external_value(PARAM_INT, 'Thumbnail course URL - big version'),
                    'completion'    => new external_value(PARAM_INT, 'The fullname of the course'),
                    'completiongradeitemnumber' => new external_value(PARAM_INT, 'Thumbnail course URL - small version'),
                    'completionview' => new external_value(PARAM_INT, 'Thumbnail course URL - medium version'),
                    'completionexpected' => new external_value(PARAM_INT, 'Thumbnail course URL - big version'),
                    'showdescription'    => new external_value(PARAM_INT, 'The fullname of the course'),
                    'availability' => new external_value(PARAM_RAW, 'Thumbnail course URL - small version'),
                    'modname' => new external_value(PARAM_TEXT, 'Thumbnail course URL - medium version'),
                )
            )
        );
    }
	
	public static function get_remote_course_sessions_parameters()
    {
        return new external_function_parameters(
            array('courseid' => new external_value(PARAM_INT, 'course id'),
            )
        );
    }
    
    public static function get_remote_course_sessions($courseid)
    {       
        global $DB;

        //validate parameter
        $params = self::validate_parameters(self::get_remote_course_sessions_parameters(), array('courseid' => $courseid));
        return $DB->get_records('course_sections', array('course' => $courseid), 'section ASC', 'id,section,sequence');
    }
    
    public static function get_remote_course_sessions_returns()
    {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'id'    => new external_value(PARAM_INT, 'ID of the course'),
                    'course'    => new external_value(PARAM_INT, 'The fullname of the course'),
                    'section' => new external_value(PARAM_INT, 'Thumbnail course URL - big version'),
                    'name'    => new external_value(PARAM_TEXT, 'The fullname of the course'),
                    'summary' => new external_value(PARAM_RAW, 'Thumbnail course URL - small version'),
                    'summaryformat' => new external_value(PARAM_INT, 'Thumbnail course URL - medium version'),
                    'sequence' => new external_value(PARAM_RAW, 'Thumbnail course URL - big version'),
                    'visible'    => new external_value(PARAM_INT, 'The fullname of the course'),
                    'availability' => new external_value(PARAM_RAW, 'Thumbnail course URL - small version'),
                )
            )
        );
    }
}
