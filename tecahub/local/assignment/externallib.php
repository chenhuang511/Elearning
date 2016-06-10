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
require_once("$CFG->dirroot/user/externallib.php");
require_once("$CFG->dirroot/mod/assign/locallib.php");

/**
 * Course external functions
 *
 * @package    core_course
 * @category   external
 * @copyright  2011 Jerome Mouneyrac
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since Moodle 2.2
 */
class local_mod_assign_external extends external_api {

    /**
     * Describes the parameters for get_submission_status.
     *
     * @return external_external_function_parameters
     * @since Moodle 3.1
     */
    public static function get_mod_assign_submission_status_parameters() {
        return new external_function_parameters (
            array(
                'assignid' => new external_value(PARAM_INT, 'assignid'),
                'ip_address' => new external_value(PARAM_TEXT, 'ip address'),
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
     * Returns information about an assignment submission status for a given user.
     *
     * @param int $assignid assignment instance id
     * @param int $userid user id (empty for current user)
     * @return array of warnings and grading, status, feedback and previous attempts information
     * @since Moodle 3.1
     * @throws required_capability_exception
     */
    public static function get_mod_assign_submission_status($assignid=0, $ip_address="",$username = "",$options=array()) {
        global  $DB;
        $params = self::validate_parameters(self::get_mod_assign_submission_status_parameters(),
            array('assignid' => $assignid,"ip_address"=>$ip_address,"username"=>$username,'options' => $options));

        $mnethostid =  $DB->get_record('mnet_host', array('ip_address' => $params['ip_address']), 'id', MUST_EXIST);
        $USER =  $DB->get_record('user', array('username' => $params['username'], 'mnethostid' => $mnethostid->id), 'id', MUST_EXIST);

        $warnings = array();

        // Request and permission validation.
        $assign = $DB->get_record('assign', array('id' => $params['assignid']), 'id', MUST_EXIST);
        list($course, $cm) = get_course_and_cm_from_instance($assign, 'assign');

        $context = context_module::instance($cm->id);
        self::validate_context($context);

        $assign = new assign($context, $cm, $course);

        // Default value for userid.
        if (empty($params['userid'])) {
            $params['userid'] = $USER->id;
        }
        $user = core_user::get_user($params['userid'], '*', MUST_EXIST);
        core_user::require_active_user($user);

        if (!$assign->can_view_submission($user->id)) {
            throw new required_capability_exception($context, 'mod/assign:viewgrades', 'nopermission', '');
        }

        $gradingsummary = $lastattempt = $feedback = $previousattempts = null;

        // Get the renderable since it contais all the info we need.
        if ($assign->can_view_grades()) {
            $gradingsummary = $assign->get_assign_grading_summary_renderable();
        }

        // Retrieve the rest of the renderable objects.
        if (has_capability('mod/assign:submit', $assign->get_context(), $user)) {
            $lastattempt = $assign->get_assign_submission_status_renderable($user, true);
        }

        $feedback = $assign->get_assign_feedback_status_renderable($user);

        $previousattempts = $assign->get_assign_attempt_history_renderable($user);

        // Now, build the result.
        $result = array();

        // First of all, grading summary, this is suitable for teachers/managers.
        if ($gradingsummary) {
            $result['gradingsummary'] = $gradingsummary;
        }

        // Did we submit anything?
        if ($lastattempt) {
            $submissionplugins = $assign->get_submission_plugins();

            if (empty($lastattempt->submission)) {
                unset($lastattempt->submission);
            } else {
                $lastattempt->submission->plugins = self::get_plugins_data($assign, $submissionplugins, $lastattempt->submission);
            }

            if (empty($lastattempt->teamsubmission)) {
                unset($lastattempt->teamsubmission);
            } else {
                $lastattempt->teamsubmission->plugins = self::get_plugins_data($assign, $submissionplugins,
                    $lastattempt->teamsubmission);
            }

            // We need to change the type of some of the structures retrieved from the renderable.
            if (!empty($lastattempt->submissiongroup)) {
                $lastattempt->submissiongroup = $lastattempt->submissiongroup->id;
            }
            if (!empty($lastattempt->usergroups)) {
                $lastattempt->usergroups = array_keys($lastattempt->usergroups);
            }
            // We cannot use array_keys here.
            if (!empty($lastattempt->submissiongroupmemberswhoneedtosubmit)) {
                $lastattempt->submissiongroupmemberswhoneedtosubmit = array_map(
                    function($e){
                        return $e->id;
                    },
                    $lastattempt->submissiongroupmemberswhoneedtosubmit);
            }

            $result['lastattempt'] = $lastattempt;
        }

        // The feedback for our latest submission.
        if ($feedback) {
            if ($feedback->grade) {
                $feedbackplugins = $assign->get_feedback_plugins();
                $feedback->plugins = self::get_plugins_data($assign, $feedbackplugins, $feedback->grade);
            } else {
                unset($feedback->plugins);
                unset($feedback->grade);
            }

            $result['feedback'] = $feedback;
        }

        // Retrieve only previous attempts.
        if ($previousattempts and count($previousattempts->submissions) > 1) {
            // Don't show the last one because it is the current submission.
            array_pop($previousattempts->submissions);

            // Show newest to oldest.
            $previousattempts->submissions = array_reverse($previousattempts->submissions);

            foreach ($previousattempts->submissions as $i => $submission) {
                $attempt = array();

                $grade = null;
                foreach ($previousattempts->grades as $onegrade) {
                    if ($onegrade->attemptnumber == $submission->attemptnumber) {
                        $grade = $onegrade;
                        break;
                    }
                }

                $attempt['attemptnumber'] = $submission->attemptnumber;

                if ($submission) {
                    $submission->plugins = self::get_plugins_data($assign, $previousattempts->submissionplugins, $submission);
                    $attempt['submission'] = $submission;
                }

                if ($grade) {
                    // From object to id.
                    $grade->grader = $grade->grader->id;
                    $feedbackplugins = self::get_plugins_data($assign, $previousattempts->feedbackplugins, $grade);

                    $attempt['grade'] = $grade;
                    $attempt['feedbackplugins'] = $feedbackplugins;
                }
                $result['previousattempts'][] = $attempt;
            }
        }

        $result['warnings'] = $warnings;
        return $result;
    }

    /**
     * Describes the get_submission_status return value.
     *
     * @return external_single_structure
     * @since Moodle 3.1
     */
    public static function get_mod_assign_submission_status_returns() {
        return new external_single_structure(
            array(
                'gradingsummary' => new external_single_structure(
                    array(
                        'participantcount' => new external_value(PARAM_INT, 'Number of users who can submit.'),
                        'submissiondraftscount' => new external_value(PARAM_INT, 'Number of submissions in draft status.'),
                        'submissiondraftscount' => new external_value(PARAM_INT, 'Number of submissions in draft status.'),
                        'submissionsenabled' => new external_value(PARAM_BOOL, 'Whether submissions are enabled or not.'),
                        'submissionssubmittedcount' => new external_value(PARAM_INT, 'Number of submissions in submitted status.'),
                        'submissionsneedgradingcount' => new external_value(PARAM_INT, 'Number of submissions that need grading.'),
                        'warnofungroupedusers' => new external_value(PARAM_BOOL, 'Whether we need to warn people that there
                                                                        are users without groups.'),
                    ), 'Grading information.', VALUE_OPTIONAL
                ),
                'lastattempt' => new external_single_structure(
                    array(
                        'submission' => self::get_mod_assign_submission_structure(VALUE_OPTIONAL),
                        'teamsubmission' => self::get_mod_assign_submission_structure(VALUE_OPTIONAL),
                        'submissiongroup' => new external_value(PARAM_INT, 'The submission group id (for group submissions only).',
                            VALUE_OPTIONAL),
                        'submissiongroupmemberswhoneedtosubmit' => new external_multiple_structure(
                            new external_value(PARAM_INT, 'USER id.'),
                            'List of users who still need to submit (for group submissions only).',
                            VALUE_OPTIONAL
                        ),
                        'submissionsenabled' => new external_value(PARAM_BOOL, 'Whether submissions are enabled or not.'),
                        'locked' => new external_value(PARAM_BOOL, 'Whether new submissions are locked.'),
                        'graded' => new external_value(PARAM_BOOL, 'Whether the submission is graded.'),
                        'canedit' => new external_value(PARAM_BOOL, 'Whether the user can edit the current submission.'),
                        'cansubmit' => new external_value(PARAM_BOOL, 'Whether the user can submit.'),
                        'extensionduedate' => new external_value(PARAM_INT, 'Extension due date.'),
                        'blindmarking' => new external_value(PARAM_BOOL, 'Whether blind marking is enabled.'),
                        'gradingstatus' => new external_value(PARAM_ALPHANUMEXT, 'Grading status.'),
                        'usergroups' => new external_multiple_structure(
                            new external_value(PARAM_INT, 'Group id.'), 'User groups in the course.'
                        ),
                    ), 'Last attempt information.', VALUE_OPTIONAL
                ),
                'feedback' => new external_single_structure(
                    array(
                        'grade' => self::get_mod_assign_grade_structure(VALUE_OPTIONAL),
                        'gradefordisplay' => new external_value(PARAM_RAW, 'Grade rendered into a format suitable for display.'),
                        'gradeddate' => new external_value(PARAM_INT, 'The date the user was graded.'),
                        'plugins' => new external_multiple_structure(self::get_mod_assign_plugin_structure(), 'Plugins info.', VALUE_OPTIONAL),
                    ), 'Feedback for the last attempt.', VALUE_OPTIONAL
                ),
                'previousattempts' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'attemptnumber' => new external_value(PARAM_INT, 'Attempt number.'),
                            'submission' => self::get_mod_assign_submission_structure(VALUE_OPTIONAL),
                            'grade' => self::get_mod_assign_grade_structure(VALUE_OPTIONAL),
                            'feedbackplugins' => new external_multiple_structure(self::get_mod_assign_plugin_structure(), 'Feedback info.',
                                VALUE_OPTIONAL),
                        )
                    ), 'List all the previous attempts did by the user.', VALUE_OPTIONAL
                ),
                'warnings' => new external_warnings(),
            )
        );
    }


    /**
     * Return information (files and text fields) for the given plugins in the assignment.
     *
     * @param  assign $assign the assignment object
     * @param  array $assignplugins array of assignment plugins (submission or feedback)
     * @param  stdClass $item the item object (submission or grade)
     * @return array an array containing the plugins returned information
     */
    private static function get_plugins_data($assign, $assignplugins, $item) {
        global $CFG;

        $plugins = array();
        $fs = get_file_storage();

        foreach ($assignplugins as $assignplugin) {

            if (!$assignplugin->is_enabled() or !$assignplugin->is_visible()) {
                continue;
            }

            $plugin = array(
                'name' => $assignplugin->get_name(),
                'type' => $assignplugin->get_type()
            );
            // Subtype is 'assignsubmission', type is currently 'file' or 'onlinetext'.
            $component = $assignplugin->get_subtype().'_'.$assignplugin->get_type();

            $fileareas = $assignplugin->get_file_areas();
            foreach ($fileareas as $filearea => $name) {
                $fileareainfo = array('area' => $filearea);
                $files = $fs->get_area_files(
                    $assign->get_context()->id,
                    $component,
                    $filearea,
                    $item->id,
                    "timemodified",
                    false
                );
                foreach ($files as $file) {
                    $filepath = $file->get_filepath().$file->get_filename();
                    $fileurl = file_encode_url($CFG->wwwroot . '/webservice/pluginfile.php', '/' . $assign->get_context()->id .
                        '/' . $component. '/'. $filearea . '/' . $item->id . $filepath);
                    $fileinfo = array(
                        'filepath' => $filepath,
                        'fileurl' => $fileurl
                    );
                    $fileareainfo['files'][] = $fileinfo;
                }
                $plugin['fileareas'][] = $fileareainfo;
            }

            $editorfields = $assignplugin->get_editor_fields();
            foreach ($editorfields as $name => $description) {
                $editorfieldinfo = array(
                    'name' => $name,
                    'description' => $description,
                    'text' => $assignplugin->get_editor_text($name, $item->id),
                    'format' => $assignplugin->get_editor_format($name, $item->id)
                );
                $plugin['editorfields'][] = $editorfieldinfo;
            }
            $plugins[] = $plugin;
        }
        return $plugins;
    }
   

    /**
     * Creates an assignment plugin structure.
     *
     * @return external_single_structure the plugin structure
     */
    private static function get_mod_assign_plugin_structure() {
        return new external_single_structure(
            array(
                'type' => new external_value(PARAM_TEXT, 'submission plugin type'),
                'name' => new external_value(PARAM_TEXT, 'submission plugin name'),
                'fileareas' => new external_multiple_structure(
                    new external_single_structure(
                        array (
                            'area' => new external_value (PARAM_TEXT, 'file area'),
                            'files' => new external_multiple_structure(
                                new external_single_structure(
                                    array (
                                        'filepath' => new external_value (PARAM_TEXT, 'file path'),
                                        'fileurl' => new external_value (PARAM_URL, 'file download url',
                                            VALUE_OPTIONAL)
                                    )
                                ), 'files', VALUE_OPTIONAL
                            )
                        )
                    ), 'fileareas', VALUE_OPTIONAL
                ),
                'editorfields' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_TEXT, 'field name'),
                            'description' => new external_value(PARAM_TEXT, 'field description'),
                            'text' => new external_value (PARAM_RAW, 'field value'),
                            'format' => new external_format_value ('text')
                        )
                    )
                    , 'editorfields', VALUE_OPTIONAL
                )
            )
        );
    }

    /**
     * Creates a submission structure.
     *
     * @return external_single_structure the submission structure
     */
    private static function get_mod_assign_submission_structure($required = VALUE_REQUIRED) {
        return new external_single_structure(
            array(
                'id' => new external_value(PARAM_INT, 'submission id'),
                'userid' => new external_value(PARAM_INT, 'student id'),
                'attemptnumber' => new external_value(PARAM_INT, 'attempt number'),
                'timecreated' => new external_value(PARAM_INT, 'submission creation time'),
                'timemodified' => new external_value(PARAM_INT, 'submission last modified time'),
                'status' => new external_value(PARAM_TEXT, 'submission status'),
                'groupid' => new external_value(PARAM_INT, 'group id'),
                'assignment' => new external_value(PARAM_INT, 'assignment id', VALUE_OPTIONAL),
                'latest' => new external_value(PARAM_INT, 'latest attempt', VALUE_OPTIONAL),
                'plugins' => new external_multiple_structure(self::get_mod_assign_plugin_structure(), 'plugins', VALUE_OPTIONAL)
            ), 'submission info', $required
        );
    }



    /**
     * Creates a grade single structure.
     *
     * @return external_single_structure a grade single structure.
     * @since  Moodle 3.1
     */
    private static function get_mod_assign_grade_structure($required = VALUE_REQUIRED) {
        return new external_single_structure(
            array(
                'id'                => new external_value(PARAM_INT, 'grade id'),
                'assignment'        => new external_value(PARAM_INT, 'assignment id', VALUE_OPTIONAL),
                'userid'            => new external_value(PARAM_INT, 'student id'),
                'attemptnumber'     => new external_value(PARAM_INT, 'attempt number'),
                'timecreated'       => new external_value(PARAM_INT, 'grade creation time'),
                'timemodified'      => new external_value(PARAM_INT, 'grade last modified time'),
                'grader'            => new external_value(PARAM_INT, 'grader'),
                'grade'             => new external_value(PARAM_TEXT, 'grade'),
                'gradefordisplay'   => new external_value(PARAM_RAW, 'grade rendered into a format suitable for display',
                    VALUE_OPTIONAL),
            ), 'grade information', $required
        );
    }

	/**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 3.0
     */
    public static function get_mod_assign_by_id_parameters()
    {
        return new external_function_parameters(
			array('assignid' => new external_value(PARAM_INT, 'the assign id'))
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
    public static function get_mod_assign_by_id($assignid)
    {
        global $DB;

        // validate params
        $params = self::validate_parameters(self::get_mod_assign_by_id_parameters(),
            array(
                'assignid' => $assignid
            )
        );

        return $DB->get_record('assign', array('id' => $params['assignid']), 'id, name, alwaysshowdescription, allowsubmissionsfromdate, intro, introformat', MUST_EXIST);
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     * @since Moodle 3.0
     */
    public static function get_mod_assign_by_id_returns()
    {
        return new external_single_structure(
            array(
                'id' => new external_value(PARAM_INT, 'assign id'),
                'name' => new external_value(PARAM_RAW, 'assign name'),
                'intro' => new external_value(PARAM_RAW, 'assign intro'),
                'introformat' => new external_value(PARAM_INT, 'intro format', VALUE_DEFAULT),
				'alwaysshowdescription' => new external_value(PARAM_INT, 'always show description'),
				'allowsubmissionsfromdate' => new external_value(PARAM_INT, 'allow submissions from date'),
            )
        );
    }	
}
