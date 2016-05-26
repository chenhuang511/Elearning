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

    public static function get_mod_assign_completion_parameters() {
        return new external_function_parameters(
            array('assignid' => new external_value(PARAM_INT, 'assignid'),
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
    public static function get_mod_assign_completion($assignid, $ip_address, $username, $options = array()) {
        global $CFG, $DB;

        //validate parameter
        $params = self::validate_parameters(self::get_mod_assign_completion_parameters(),
            array('assignid' => $assignid, 'username' => $username , 'ip_address' => $ip_address ,'options' => $options));

        // Get mnethost by ipadress
        $mnethostid =  $DB->get_record('mnet_host', array('ip_address' => $params['ip_address']), 'id', MUST_EXIST);
        // Get user by $username and $mnethost->id
        //echo $params['username'];die;

        $USER =  $DB->get_record('user', array('username' => $params['username'], 'mnethostid' => $mnethostid->id), 'id', MUST_EXIST);
        //retrieve the Grading Sumary
        require_once($CFG->dirroot . '/mod/assign/locallib.php');
        list ($course, $cm) = get_course_and_cm_from_cmid($assignid, 'assign');
        $context = context_module::instance($cm->id);
        $assign = new assign($context, $cm, $course);
        $instance = $assign->get_instance();

        //$assign->has
        $postfix = '';
       /* if ($assign->count()) {
            echo 1;die;
            $postfix = $assign->render_area_files('mod_assign', ASSIGN_INTROATTACHMENT_FILEAREA, 0);
        }*/

        // Display plugin specific headers.
        $plugins = array_merge($assign->get_submission_plugins(), $assign->get_feedback_plugins());
        foreach ($plugins as $plugin) {
            if ($plugin->is_enabled() && $plugin->is_visible()) {
                //$o .= $assign->get_renderer()->render(new assign_plugin_header($plugin));
            }
        }

        if ($assign->can_view_grades()) {
            $draft = ASSIGN_SUBMISSION_STATUS_DRAFT;
            $submitted = ASSIGN_SUBMISSION_STATUS_SUBMITTED;

            // Group selector will only be displayed if necessary.
            $activitygroup = groups_get_activity_group($assign->get_course_module());

            if ($instance->teamsubmission) {
                $defaultteammembers = $assign->get_submission_group_members(0, true);
                $warnofungroupedusers = (count($defaultteammembers) > 0 && $instance->preventsubmissionnotingroup);

                $summary = new assign_grading_summary($assign->count_teams($activitygroup),
                    $instance->submissiondrafts,
                    $assign->count_submissions_with_status($draft),
                    $assign->is_any_submission_plugin_enabled(),
                    $assign->count_submissions_with_status($submitted),
                    $instance->cutoffdate,
                    $instance->duedate,
                    $assign->get_course_module()->id,
                    $assign->count_submissions_need_grading(),
                    $instance->teamsubmission,
                    $warnofungroupedusers);

            } else {
                // The active group has already been updated in groups_print_activity_menu().
                $countparticipants = $assign->count_participants($activitygroup);
                $summary = new assign_grading_summary($countparticipants,
                    $instance->submissiondrafts,
                    $assign->count_submissions_with_status($draft),
                    $assign->is_any_submission_plugin_enabled(),
                    $assign->count_submissions_with_status($submitted),
                    $instance->cutoffdate,
                    $instance->duedate,
                    $assign->get_course_module()->id,
                    $assign->count_submissions_need_grading(),
                    $instance->teamsubmission,
                    false);

            }
        }
        $grade = $assign->get_user_grade($USER->id, false);
        $submission = $assign->get_user_submission($USER->id, false);

        if ($assign->can_view_submission($USER->id)) {
           // $o .= $this->view_student_summary($USER, true);
        }



        return array($summary);
    }
    public static function get_mod_assign_completion_returns() {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'participantcount' => new external_value(PARAM_INT, 'participantcount'),
                    'submissiondraftsenabled' => new external_value(PARAM_INT, 'submissiondraftsenabled'),
                    'submissiondraftscount' => new external_value(PARAM_INT, 'submissiondraftscount'),
                    'submissionsenabled' => new external_value(PARAM_INT, 'submissionsenabled'),
                    'submissionssubmittedcount' => new external_value(PARAM_INT, 'submissionssubmittedcount'),
                    'submissionsneedgradingcount' => new external_value(PARAM_INT, 'submissionsneedgradingcount'),
                    'duedate' => new external_value(PARAM_INT, 'duedate'),
                    'cutoffdate' => new external_value(PARAM_INT, 'cutoffdate'),
                    'coursemoduleid' => new external_value(PARAM_INT, 'coursemoduleid'),
                    'teamsubmission' => new external_value(PARAM_INT, 'teamsubmission'),
                    'warnofungroupedusers' => new external_value(PARAM_RAW, 'warnofungroupedusers'),
                )
            )
        );
    }

    //endregion

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
                'stage' => new external_value(PARAM_TEXT, 'stage'),
                'timestart' => new external_value(PARAM_INT, 'time start'),
                'timefinish' => new external_value(PARAM_INT, 'time finish'),
                'sumgrades' => new external_value(PARAM_INT, 'sum grade'),
            )
        );
    }
}
