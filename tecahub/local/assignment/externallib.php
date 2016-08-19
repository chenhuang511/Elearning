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

require_once($CFG->libdir . '/externallib.php');
require_once($CFG->dirroot . '/user/externallib.php');
require_once($CFG->dirroot . '/mod/assign/locallib.php');
require_once($CFG->dirroot . '/mod/assign/externallib.php');

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
     * @param int $assignid     - The id of assignment
     * @param int $userid       - The id of user (empty for current user)
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
                        'submissiondraftsenabled' => new external_value(PARAM_INT, 'Number of submissions in draft status.'),
                        'submissiondraftscount' => new external_value(PARAM_INT, 'Number of submissions in draft status.'),
                        'submissionsenabled' => new external_value(PARAM_BOOL, 'Whether submissions are enabled or not.'),
                        'submissionssubmittedcount' => new external_value(PARAM_INT, 'Number of submissions in submitted status.'),
                        'submissionsneedgradingcount' => new external_value(PARAM_INT, 'Number of submissions that need grading.'),
                        'duedate' => new external_value(PARAM_INT, 'Number of submissions that need grading.'),
                        'cutoffdate' => new external_value(PARAM_INT, 'Number of submissions that need grading.'),
                        'coursemoduleid' => new external_value(PARAM_INT, 'Number of submissions that need grading.'),
                        'teamsubmission' => new external_value(PARAM_INT, 'Number of submissions that need grading.'),
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
                'grade'             => new external_value(PARAM_FLOAT, 'grade'),
                'gradefordisplay'   => new external_value(PARAM_RAW, 'grade rendered into a format suitable for display',
                    VALUE_OPTIONAL),
            ), 'grade information', $required
        );
    }

    /**
     * Returns description of method get_mod_assign_by_id_instanceid parameters
     *
     * @return external_function_parameters
     * @since Moodle 3.0
     */
    public static function get_mod_assign_by_id_instanceid_parameters()
    {
        return new external_function_parameters(
            array(
                'assignid' => new external_value(PARAM_INT, 'the assign id'),
                'instanceid' => new external_value(PARAM_INT, 'the instance id'),
            )
        );
    }

    /**
     * Return information about a assignment.
     *
     * @param int $assignid the assign id
     * @param int $instanceid the instance id
     * @return array of warnings and the assignment
     * @since Moodle 3.0
     * @throws moodle_exception
     */
    public static function get_mod_assign_by_id_instanceid($assignid, $instanceid)
    {
        global $DB;

        // Build result
        $warnings = array();

        $result = array();

        // validate params
        $params = self::validate_parameters(self::get_mod_assign_by_id_instanceid_parameters(),
            array(
                'assignid' => $assignid,
                'instanceid' => $instanceid
            )
        );

        $result['assignment'] = $DB->get_record('assign', array('id' => $params['assignid']), '*', MUST_EXIST);
        $result['warning'] = $warnings;

        $context = context_module::instance($params['instanceid']);
        self::validate_context($context);

        $fs = get_file_storage();
        if ($files = $fs->get_area_files($context->id, 'mod_assign', ASSIGN_INTROATTACHMENT_FILEAREA,
            0, 'timemodified', false)
        ) {

            $assignment['introattachments'] = array();
            foreach ($files as $file) {
                $filename = $file->get_filename();

                $assignment['introattachments'][] = array(
                    'filename' => $filename,
                    'mimetype' => $file->get_mimetype(),
                    'fileurl' => moodle_url::make_pluginfile_url(
                        $context->id, 'mod_assign', ASSIGN_INTROATTACHMENT_FILEAREA, 0, '/', $filename, true)->out(false)
                );
            }
            $result['assignment']->introattachments = $assignment['introattachments'];
        }

        return $result;
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     * @since Moodle 3.0
     */
    public static function get_mod_assign_by_id_instanceid_returns()
    {
        return new external_single_structure(
            array(
                'assignment' => new external_single_structure(
                    array(
                        'id' => new external_value(PARAM_INT, 'assign id'),
                        'course' => new external_value(PARAM_INT, 'course id'),
                        'name' => new external_value(PARAM_RAW, 'assign name'),
                        'intro' => new external_value(PARAM_RAW, 'assign intro'),
                        'introformat' => new external_value(PARAM_INT, 'intro format', VALUE_DEFAULT),
                        'alwaysshowdescription' => new external_value(PARAM_INT, 'always show description'),
                        'nosubmissions' => new external_value(PARAM_INT, 'no submissions'),
                        'submissiondrafts' => new external_value(PARAM_INT, 'submission drafts'),
                        'sendnotifications' => new external_value(PARAM_INT, 'send notifications'),
                        'sendlatenotifications' => new external_value(PARAM_INT, 'send late notifications'),
                        'duedate' => new external_value(PARAM_INT, 'Due date'),
                        'allowsubmissionsfromdate' => new external_value(PARAM_INT, 'allow submissions from date'),
                        'grade' => new external_value(PARAM_FLOAT, 'grade'),
                        'timemodified' => new external_value(PARAM_INT, 'time modified'),
                        'requiresubmissionstatement' => new external_value(PARAM_INT, 'required submission statement'),
                        'completionsubmit' => new external_value(PARAM_INT, 'completetion submit'),
                        'cutoffdate' => new external_value(PARAM_INT, 'cut off date'),
                        'teamsubmission' => new external_value(PARAM_INT, 'team submission'),
                        'requireallteammemberssubmit' => new external_value(PARAM_INT, 'require all team members submits'),
                        'teamsubmissiongroupingid' => new external_value(PARAM_INT, 'team submission grouping id'),
                        'blindmarking' => new external_value(PARAM_INT, 'blind marking'),
                        'revealidentities' => new external_value(PARAM_INT, 'reveal identities'),
                        'attemptreopenmethod' => new external_value(PARAM_RAW, 'attempt reopen method'),
                        'maxattempts' => new external_value(PARAM_INT, 'maxattempts'),
                        'markingworkflow' => new external_value(PARAM_INT, 'marking workflow'),
                        'markingallocation' => new external_value(PARAM_INT, 'marking all location'),
                        'sendstudentnotifications' => new external_value(PARAM_INT, 'send student notifications'),
                        'preventsubmissionnotingroup' => new external_value(PARAM_INT, 'prevent submission not in group'),
                        'introattachments' => new external_multiple_structure(
                            new external_single_structure(
                                array (
                                    'filename' => new external_value(PARAM_FILE, 'file name'),
                                    'mimetype' => new external_value(PARAM_RAW, 'mime type'),
                                    'fileurl'  => new external_value(PARAM_URL, 'file download url')
                                )
                            ), 'intro attachments files', VALUE_OPTIONAL
                        )
                    )
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    /**
     * Returns description of method get_mod_assign_by_id parameters
     *
     * @return external_function_parameters
     * @since Moodle 3.0
     */
    public static function get_mod_assign_by_id_parameters()
    {
        return new external_function_parameters(
			array(
                'assignid' => new external_value(PARAM_INT, 'the assign id')
            )
        );
    }

    /**
     * Return information about a assign.
     *
     * @param int $assignid the id of assignment
     * @return array of warnings and the assign
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

        return $DB->get_record('assign', array('id' => $params['assignid']), '*', MUST_EXIST);
    }

    /**
     * Returns description of method get_mod_assign_by_id result value
     *
     * @return external_description
     * @since Moodle 3.0
     */
    public static function get_mod_assign_by_id_returns()
    {
        return new external_single_structure(
            array(
                'id' => new external_value(PARAM_INT, 'assign id'),
                'course' => new external_value(PARAM_INT, 'course id'),
                'name' => new external_value(PARAM_RAW, 'assign name'),
                'intro' => new external_value(PARAM_RAW, 'assign intro'),
                'introformat' => new external_value(PARAM_INT, 'intro format', VALUE_DEFAULT),
				'alwaysshowdescription' => new external_value(PARAM_INT, 'always show description'),
				'nosubmissions' => new external_value(PARAM_INT, 'no submissions'),
				'submissiondrafts' => new external_value(PARAM_INT, 'submission drafts'),
				'sendnotifications' => new external_value(PARAM_INT, 'send notifications'),
				'sendlatenotifications' => new external_value(PARAM_INT, 'send late notifications'),
				'duedate' => new external_value(PARAM_INT, 'Due date'),
				'allowsubmissionsfromdate' => new external_value(PARAM_INT, 'allow submissions from date'),
				'grade' => new external_value(PARAM_FLOAT, 'grade'),
				'timemodified' => new external_value(PARAM_INT, 'time modified'),
				'requiresubmissionstatement' => new external_value(PARAM_INT, 'required submission statement'),
				'completionsubmit' => new external_value(PARAM_INT, 'completetion submit'),
				'cutoffdate' => new external_value(PARAM_INT, 'cut off date'),
				'teamsubmission' => new external_value(PARAM_INT, 'team submission'),
				'requireallteammemberssubmit' => new external_value(PARAM_INT, 'require all team members submits'),
				'teamsubmissiongroupingid' => new external_value(PARAM_INT, 'team submission grouping id'),
				'blindmarking' => new external_value(PARAM_INT, 'blind marking'),
				'revealidentities' => new external_value(PARAM_INT, 'reveal identities'),
				'attemptreopenmethod' => new external_value(PARAM_RAW, 'attempt reopen method'),
				'maxattempts' => new external_value(PARAM_INT, 'maxattempts'),
				'markingworkflow' => new external_value(PARAM_INT, 'marking workflow'),
				'markingallocation' => new external_value(PARAM_INT, 'marking all location'),
				'sendstudentnotifications' => new external_value(PARAM_INT, 'send student notifications'),
				'preventsubmissionnotingroup' => new external_value(PARAM_INT, 'prevent submission not in group'),
            )
        );
    }
    
    /**
     * Describes the parameters for get_onlinetext_submission
     *
     * @return external_external_function_parameters
     */
    public static function get_onlinetext_submission_parameters(){
        return new external_function_parameters(
            array(
                'submissionid' => new external_value(PARAM_INT, 'the submission id')
            )
        );
    }
    
    /**
     * Returns Object onlinetext submission by submission id.
     *
     * @param int $submissionid   -   The id of submission
     *
     * @return array of warnings and onlinetext information
     */
    public static function get_onlinetext_submission($submissionid){
        global $DB;

        $warnings = array();

        $result = array();

        // validate params
        $params = self::validate_parameters(self::get_onlinetext_submission_parameters(),
            array(
                'submissionid' => $submissionid
            )
        );
        
        $result['onlinetext'] = $DB->get_record('assignsubmission_onlinetext', array('submission'=>$params['submissionid']));
        if (!$result['onlinetext']){
            $result['onlinetext'] = array();
        }

        $result['warnings'] = $warnings;
        return $result;
    }

    /**
     * Describes the get_onlinetext_submission return value.
     *
     * @return external_single_structure
     * @since Moodle 3.1
     */
    public static function get_onlinetext_submission_returns(){
        return new external_single_structure(
            array(
                'onlinetext' => new external_single_structure(
                    array(
                        'id' => new external_value(PARAM_INT, 'onlinetext id', VALUE_OPTIONAL),
                        'assignment' => new external_value(PARAM_INT, 'assignment id', VALUE_OPTIONAL),
                        'submission' => new external_value(PARAM_INT, 'submission id', VALUE_OPTIONAL),
                        'onlinetext' => new external_value(PARAM_RAW, 'online text', VALUE_OPTIONAL),
                        'onlineformat' => new external_value(PARAM_INT, 'online text format', VALUE_OPTIONAL),
                    )
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    /**
     * Describes the parameters for create_onlinetext_submission
     *
     * @return external_external_function_parameters
     */
    public static function create_onlinetext_submission_parameters(){
        return new external_function_parameters(
            array(
                'assignment' => new external_value(PARAM_INT, 'assignment id'),
                'submission' => new external_value(PARAM_INT, 'submission id'),
                'onlinetext' => new external_value(PARAM_RAW, 'online text'),
                'onlineformat' => new external_value(PARAM_INT, 'online text format'),
            )  
        );
    }

    /**
     * Returns id of new onlinetext just created.
     *
     * @param int $assignment   -   The id of asssignment
     * @param int $submission   -   The id of submission
     * @param string $onlinetext -    The content of onlinetext
     * @param int $onlineformat -   The format of onlinetext
     * 
     * @return array of warnings and onlinetext information
     */
    public static function create_onlinetext_submission($assignment, $submission, $onlinetext, $onlineformat){

        global $DB;
        
        //build result
        $result = array();
        $warnings = array();
        
        //Validate param
        $params = self::validate_parameters(self::create_onlinetext_submission_parameters(),
            array(
                'assignment' => $assignment,
                'submission' => $submission,
                'onlinetext' => $onlinetext,
                'onlineformat' => $onlineformat
            )
        );

        $onlinetextsubmission = (object)$params;
        $transaction = $DB->start_delegated_transaction();

        $result['oid'] = $DB->insert_record('assignsubmission_onlinetext', $onlinetextsubmission);

        $transaction->allow_commit();

        $result['warnings'] = $warnings;

        return $result;
    }

    /**
     * Describes the create_onlinetext_submission_parameters return value.
     *
     * @return external_single_structure
     * @since Moodle 3.1
     */
    public static function create_onlinetext_submission_returns(){
        return new external_single_structure(
            array(
                'oid' =>  new external_value(PARAM_INT, 'The id of onlinetext'),
                'warnings' => new external_warnings()
            )
        );
    }
    
    /**
     * Describes the parameters for update_onlinetext_submission
     *
     * @return external_external_function_parameters
     */
    public static function update_onlinetext_submission_parameters(){
        return new external_function_parameters(
            array(
                'id' => new external_value(PARAM_INT, 'onlinetext id'),
                'assignment' => new external_value(PARAM_INT, 'assignment id'),
                'submission' => new external_value(PARAM_INT, 'submission id'),
                'onlinetext' => new external_value(PARAM_RAW, 'online text'),
                'onlineformat' => new external_value(PARAM_INT, 'online text format'),
            )  
        );
    }

    /**
     * Returns id of new onlinetext just updated.
     *
     * @param int $assignment   -   The id of asssignment
     * @param int $submission   -   The id of submission
     * @param string $onlinetext -    The content of onlinetext
     * @param int $onlineformat -   The format of onlinetext
     * 
     * @return array of warnings and grades information
     */
    public static function update_onlinetext_submission($id, $assignment, $submission, $onlinetext, $onlineformat){

        global $DB;
        
        //build result
        $result = array();
        $warnings = array();
        
        //Validate param
        $params = self::validate_parameters(self::update_onlinetext_submission_parameters(),
            array(
                'id' => $id,
                'assignment' => $assignment,
                'submission' => $submission,
                'onlinetext' => $onlinetext,
                'onlineformat' => $onlineformat
            )
        );

        $onlinetextsubmission = (object)$params;

        $transaction = $DB->start_delegated_transaction();

        $result['bool'] = $DB->update_record('assignsubmission_onlinetext', $onlinetextsubmission);

        $transaction->allow_commit();

        $result['warnings'] = $warnings;
        
        return $result;
    }

    /**
     * Describes the update_onlinetext_submission_parameters return value.
     *
     * @return external_single_structure
     * @since Moodle 3.1
     */
    public static function update_onlinetext_submission_returns(){
        return new external_single_structure(
            array(
                'bool' =>  new external_value(PARAM_INT, 'True if success'),
                'warnings' => new external_warnings()
            )
        );
    }

    /**
     * Describes the parameters for get_assignfeedback_comments
     *
     * @return external_external_function_parameters
     */
    public static function get_assignfeedback_comments_parameters(){
        return new external_function_parameters(
            array(
                'gradeid' => new external_value(PARAM_INT, 'the grade id')
            )
        );
    }

    /**       
     * Get assign feedback comments of teacher by grade id 
     * 
     * @param int $gradeid  -  The id of grade   
     * 
     * @return array of feedback comments and warnings
     * @throws invalid_parameter_exception
     */
    public static function get_assignfeedback_comments($gradeid){
        global $DB;
        
        $result = array();

        $warnings = array();

        // validate params
        $params = self::validate_parameters(self::get_assignfeedback_comments_parameters(),
            array(
                'gradeid' => $gradeid
            )
        );

        $result['feedbackcomments'] = $DB->get_record('assignfeedback_comments', array('grade'=>$params['gradeid']));
        if (!$result['feedbackcomments']){
            $result['feedbackcomments'] = array();
        }

        $result['warnings'] = $warnings;

        return $result;
    }

    /**
     * Describes the get_assignfeedback_comments returns value.
     *
     * @return external_single_structure
     * @since Moodle 3.1
     */
    public static function get_assignfeedback_comments_returns(){
        return new external_single_structure(
            array(
                'feedbackcomments' => new external_single_structure(
                    array(
                        'id' => new external_value(PARAM_INT, 'grade id', VALUE_OPTIONAL),
                        'assignment' => new external_value(PARAM_INT, 'assignment id', VALUE_OPTIONAL),
                        'grade' => new external_value(PARAM_FLOAT, 'grade id', VALUE_OPTIONAL),
                        'commenttext' => new external_value(PARAM_RAW, 'feedback comment text', VALUE_OPTIONAL),
                        'commentformat' => new external_value(PARAM_INT, 'feedback comment format', VALUE_OPTIONAL),
                    )
                ),
                'warnings' => new external_warnings(),
            )
        );
    }
    
    /**
     * Describes the parameters for update_assignfeedback_comments
     *
     * @return external_external_function_parameters
     */
    public static function update_assignfeedback_comments_parameters(){
        return new external_function_parameters(
            array(
                'id' => new external_value(PARAM_INT, 'the grade id'),
                'assignment' => new external_value(PARAM_INT, 'the assign id'),
                'grade' => new external_value(PARAM_INT, 'the grade id'),
                'commenttext' => new external_value(PARAM_RAW, 'the content of commenttext'),
                'commentformat' => new external_value(PARAM_INT, 'the format of commenttext'),
            )
        );
    }

    /**         
     * Update assignfeedback comments by id, assignment, grade, comment text, and comment format 
     * 
     * @param int $id           -  The id of grade   
     * @param int $assignment   -  The id of assignment   
     * @param int $grade        -  The id of grade   
     * @param int $commenttext  -  The content of commenttext   
     * @param int $commentformat-  The format of commenttext   
     * 
     * @return array of bool and warning
     * @throws invalid_parameter_exception
     */
    public static function update_assignfeedback_comments($id, $assignment, $grade, $commenttext, $commentformat){
        global $DB;
        
        $result = array();

        $warnings = array();

        // validate params
        $params = self::validate_parameters(self::update_assignfeedback_comments_parameters(),
            array(
                'id' => $id,
                'assignment' => $assignment,
                'grade' => $grade,
                'commenttext' => $commenttext,
                'commentformat' => $commentformat
            )
        );
        $feedbackcomment = (object)$params;
        
        $transaction = $DB->start_delegated_transaction();
        $result['bool'] = $DB->update_record('assignfeedback_comments', $feedbackcomment);
        $transaction->allow_commit();

        $result['warnings'] = $warnings;

        return $result;
    }

    /**
     * Describes the update_assignfeedback_comments value.
     *
     * @return external_single_structure
     * @since Moodle 3.1
     */
    public static function update_assignfeedback_comments_returns(){
        return new external_single_structure(
            array(
                'bool' =>  new external_value(PARAM_INT, 'Check if success'),
                'warnings' => new external_warnings()
            )
        );
    }
    
    /**
     * Describes the parameters for create_assignfeedback_comments
     *
     * @return external_external_function_parameters
     */
    public static function create_assignfeedback_comments_parameters(){
        return new external_function_parameters(
            array(
                'assignment' => new external_value(PARAM_INT, 'the assign id'),
                'grade' => new external_value(PARAM_INT, 'the grade id'),
                'commenttext' => new external_value(PARAM_RAW, 'the content of commenttext'),
                'commentformat' => new external_value(PARAM_INT, 'the format of commenttext'),
            )
        );
    }

    /**   
     * Create assignment feedback comment with params
     * 
     * @param int $assignment   -  The id of assignment   
     * @param int $grade        -  The id of grade   
     * @param int $commenttext  -  The content of commenttext   
     * @param int $commentformat-  The format of commenttext   
     * 
     * @return array of feedback comments if just created and warnings.
     * @throws invalid_parameter_exception
     */
    public static function create_assignfeedback_comments($assignment, $grade, $commenttext, $commentformat){
        global $DB;
        
        $result = array();

        $warnings = array();

        // validate params
        $params = self::validate_parameters(self::create_assignfeedback_comments_parameters(),
            array(
                'assignment' => $assignment,
                'grade' => $grade,
                'commenttext' => $commenttext,
                'commentformat' => $commentformat
            )
        );
        
        $feedbackcomment = (object)$params;
        
        $transaction = $DB->start_delegated_transaction();
        $result['fcid'] = $DB->insert_record('assignfeedback_comments', $feedbackcomment);
        $transaction->allow_commit();

        $result['warnings'] = $warnings;

        return $result;
    }

    /**
     * Describes the create_assignfeedback_comments returns value.
     *
     * @return external_single_structure
     * @since Moodle 3.1
     */
    public static function create_assignfeedback_comments_returns(){
        return new external_single_structure(
            array(
                'fcid' =>  new external_value(PARAM_INT, 'The id of feedback commnent'),
                'warnings' => new external_warnings()
            )
        );
    }

    /**
     * Describes the parameters for get_assign_plugin_config
     *
     * @return external_external_function_parameters
     */
    public static function get_assign_plugin_config_parameters(){
        return new external_function_parameters(
            array(
                'assignment' => new external_value(PARAM_INT, 'assignment id'),
            )
        );
    }

    /**             
     * Get assign plugin config on hub by assignment id
     * 
     * @param int $assignment  -  The id of assignment
     * @return array of assign plugin config and warnings  
     * @throws invalid_parameter_exception
     */
    public static function get_assign_plugin_config($assignment){
        global $DB;
        
        $result = array();
        
        $warnings = array();
        
        //Validate param
        $params = self::validate_parameters(self::get_assign_plugin_config_parameters(),
            array(
                'assignment' => $assignment,
            )
        );
        
        $assignconfig = $DB->get_records('assign_plugin_config', array('assignment' => $params['assignment']));
        
        if ($assignconfig){
            $result['pluginconfig'] = $assignconfig;
        } else{
            $result['pluginconfig'] = array();
        }
        
        $result['warnings'] = $warnings;
        
        return $result;
    }

    /**
     * Describes the get_assign_plugin_config returns value.
     *
     * @return external_single_structure
     * @since Moodle 3.1
     */
    public static function get_assign_plugin_config_returns(){
        return new external_single_structure(
            array(
                'pluginconfig' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'assignment' => new external_value(PARAM_INT, 'The id of assignment'),
                            'plugin' => new external_value(PARAM_RAW, 'the type of plugin'),
                            'subtype' => new external_value(PARAM_RAW, 'the subtype of plugin'),
                            'name' => new external_value(PARAM_RAW, 'the name of plugin'),
                            'value' => new external_value(PARAM_INT, 'the value of plugin'),
                        )
                    )
                ),
                'warnings'  => new external_warnings(),
            )
        );
    }

    /**
     * Describes the parameters for count_submissions_with_status_by_host_id
     *
     * @return external_external_function_parameters
     */
    public static function count_submissions_with_status_by_host_id_parameters(){
        return new external_function_parameters(
            array(
                'assignid' => new external_value(PARAM_INT, 'asssign ID'),
                'hostip' => new external_value(PARAM_RAW, 'host ip', VALUE_REQUIRED),
                'status' => new external_value(PARAM_RAW, 'status'),
            )
        );
    }

    /**      
     * Count submission with status by assignment id and host ip
     * 
     * @param int $assignid     - The id of assignmn
     * @param string $hostip    - The string of hostip
     * @param string $status    - The string of status
     * @return int The numbers of submissions wwith params
     * @throws invalid_parameter_exception
     * @throws restricted_context_exception
     */
    public static function count_submissions_with_status_by_host_id($assignid, $hostip, $status){
        global $DB;
        
        //Validate param
        $params = self::validate_parameters(self::count_submissions_with_status_by_host_id_parameters(),
            array(
                'assignid' => $assignid,
                'hostip' => $hostip,
                'status' => $status,
            )
        );

        // Request and permission validation.
        list($course, $cm) = get_course_and_cm_from_instance($params['assignid'], 'assign');

        $mnethostid =  $DB->get_record('mnet_host', array('ip_address' => $params['hostip']), 'id', MUST_EXIST);

        $context = context_module::instance($cm->id);
        self::validate_context($context);

        $assign = new assign($context, $cm, $course);

        $currentgroup = groups_get_activity_group($assign->get_course_module(), true);
        list($esql, $dbparams) = get_enrolled_sql($assign->get_context(), 'mod/assign:submit', $currentgroup, true);

        $dbparams['assignid'] = $assign->get_instance()->id;
        $dbparams['assignid2'] = $assign->get_instance()->id;
        $dbparams['mnethostid'] = $mnethostid->id;
        $dbparams['submissionstatus'] = $params['status'];

        $sql = 'SELECT COUNT(s.userid)
                        FROM {assign_submission} s
                        JOIN(' . $esql . 'AND eu1_u.mnethostid = :mnethostid ) e ON e.id = s.userid
                        WHERE
                            s.latest = 1 AND
                            s.assignment = :assignid AND
                            s.timemodified IS NOT NULL AND
                            s.status = :submissionstatus';
        return $DB->count_records_sql($sql, $dbparams);
    }

    /**
     * Describes the count_submissions_with_status_by_host_id returns value.
     *
     * @return external_single_structure
     * @since Moodle 3.1
     */
    public static function count_submissions_with_status_by_host_id_returns(){
        return new external_value(PARAM_INT, 'count submission with status by host id');
    }

    /**
     * Describes the parameters for count_submissions_need_grading_by_host_id
     *
     * @return external_external_function_parameters
     */
    public static function count_submissions_need_grading_by_host_id_parameters(){
        return new external_function_parameters(
            array(
                'assignid' => new external_value(PARAM_INT, 'asssign ID'),
                'hostip' => new external_value(PARAM_RAW, 'host ip', VALUE_REQUIRED),
            )
        );
    }

    /**       
     * Count submission need grading filter on hostip
     * 
     * @param int $assignid     - The id of assignment
     * @param string $hostip    - The string if hostip like: 192.168.1.88 ...
     * @return int The numbers of submissions need grading 
     * @throws invalid_parameter_exception
     * @throws restricted_context_exception
     */
    public static function count_submissions_need_grading_by_host_id($assignid, $hostip){
        global $DB;

        //Validate param
        $params = self::validate_parameters(self::count_submissions_need_grading_by_host_id_parameters(),
            array(
                'assignid' => $assignid,
                'hostip' => $hostip,
            )
        );

        // Request and permission validation.
        list($course, $cm) = get_course_and_cm_from_instance($params['assignid'], 'assign');

        $mnethostid =  $DB->get_record('mnet_host', array('ip_address' => $params['hostip']), 'id', MUST_EXIST);

        $context = context_module::instance($cm->id);
        self::validate_context($context);

        $assign = new assign($context, $cm, $course);

        $currentgroup = groups_get_activity_group($assign->get_course_module(), true);
        list($esql, $dbparams) = get_enrolled_sql($assign->get_context(), 'mod/assign:submit', $currentgroup, true);

        $dbparams['assignid'] = $assign->get_instance()->id;
        $dbparams['mnethostid'] = $mnethostid->id;
        $dbparams['submitted'] = 'submitted';

        $sql = 'SELECT COUNT(s.userid)
                   FROM {assign_submission} s
                   LEFT JOIN {assign_grades} g ON
                        s.assignment = g.assignment AND
                        s.userid = g.userid AND
                        g.attemptnumber = s.attemptnumber
                   JOIN(' . $esql . ' AND eu1_u.mnethostid = :mnethostid) e ON e.id = s.userid
                   WHERE
                        s.latest = 1 AND
                        s.assignment = :assignid AND
                        s.timemodified IS NOT NULL AND
                        s.status = :submitted AND
                        (s.timemodified >= g.timemodified OR g.timemodified IS NULL OR g.grade IS NULL)';

        return $DB->count_records_sql($sql, $dbparams);
    }

    /**
     * Describes the count_submissions_need_grading_by_host_id returns value.
     *
     * @return external_single_structure
     * @since Moodle 3.1
     */
    public static function count_submissions_need_grading_by_host_id_returns(){
        return new external_value(PARAM_INT, 'count submission need grading by host id');
    }

    /**
     * Describes the parameters for count_remote_all_submission_and_grade
     *
     * @return external_external_function_parameters
     */
    public static function count_remote_all_submission_and_grade_parameters(){
        return new external_function_parameters(
            array(
                'assignid' => new external_value(PARAM_INT, 'asssign ID'),
                'hostip' => new external_value(PARAM_TEXT, 'host ip', VALUE_REQUIRED),
                'mode' => new external_value(PARAM_TEXT, 'mode to operate'),
            )
        );
    }

    /**
     * Count all submission and grade by assign id, host ip address and mode
     *
     * @param int $assignid     - The id of assignment.
     * @param string $hostip    - The ip address of host.
     * @param string $mode      - Mode grade or submission to count
     * @return int Count the number of submission or grade
     * @throws restricted_context_exception
     */
    public static function count_remote_all_submission_and_grade($assignid, $hostip, $mode){
        global $DB;

        //Validate param
        $params = self::validate_parameters(self::count_remote_all_submission_and_grade_parameters(),
            array(
                'assignid' => $assignid,
                'hostip' => $hostip,
                'mode' => $mode,
            )
        );

        // Request and permission validation.
        list($course, $cm) = get_course_and_cm_from_instance($params['assignid'], 'assign');

        $mnethostid =  $DB->get_record('mnet_host', array('ip_address' => $params['hostip']), 'id', MUST_EXIST);

        $context = context_module::instance($cm->id);
        self::validate_context($context);

        $assign = new assign($context, $cm, $course);

        $currentgroup = groups_get_activity_group($assign->get_course_module(), true);
        list($esql, $dbparams) = get_enrolled_sql($assign->get_context(), 'mod/assign:submit', $currentgroup, true);
        $dbparams['mnethostid'] = $mnethostid->id;
        $dbparams['assignid'] = $assign->get_instance()->id;

        if ($params['mode'] == 'GRADES'){
            $sql = 'SELECT COUNT(g.userid)
                   FROM {assign_grades} g
                   JOIN(' . $esql . ' AND eu1_u.mnethostid = :mnethostid) e ON e.id = g.userid
                   WHERE g.assignment = :assignid';
        } else if ($params['mode'] == 'SUBMISSIONS') {
            $sql = 'SELECT COUNT(DISTINCT s.userid)
                       FROM {assign_submission} s
                       JOIN(' . $esql . ' AND eu1_u.mnethostid = :mnethostid) e ON e.id = s.userid
                       WHERE
                            s.assignment = :assignid AND
                            s.timemodified IS NOT NULL';
        }

        return $DB->count_records_sql($sql, $dbparams);
    }

    /**
     * Describes the count_remote_all_submission_and_grade returns value.
     *
     * @return external_single_structure
     * @since Moodle 3.1
     */
    public static function count_remote_all_submission_and_grade_returns(){
        return new external_value(PARAM_INT, 'Count the number of submission or grade');
    }

    /**
     * Describes the parameters for get_submission_by_assignid_userid_groupid
     *
     * @return external_external_function_parameters
     */
    public static function get_submission_by_assignid_userid_groupid_parameters(){
        return new external_function_parameters(
            array(
                'assignment' => new external_value(PARAM_INT, 'asssign ID'),
                'userid' => new external_value(PARAM_INT, 'user ID'),
                'groupid' => new external_value(PARAM_INT, 'group ID'),
                'attemptnumber' => new external_value(PARAM_INT, 'attempnumber'),
                'mode' => new external_value(PARAM_RAW, 'order by DESC or ASC'),
            )
        );
    }

    /**  
     * Get assign submission with params based on mode
     * 
     * @param int $assignment   - The id of assignment   
     * @param int $userid       - The id of user
     * @param int $groupid      - The id of group
     * @param int $attempnumber - The number of attempnumber
     * @param string $mode      - The string of mode to chooice the way call DB
     * @return array of submissions and warnings
     * @throws invalid_parameter_exception
     */
    public static function get_submission_by_assignid_userid_groupid($assignment, $userid, $groupid, $attempnumber, $mode){
        global $DB;

        $warnings = array();

        $result = array();

        //Validate param
        $params = self::validate_parameters(self::get_submission_by_assignid_userid_groupid_parameters(),
            array(
                'assignment' => $assignment,
                'userid' => $userid,
                'groupid' => $groupid,
                'attemptnumber' => $attempnumber,
                'mode' => $mode
            )
        );

        if ($params["attemptnumber"] < 0){
            unset($params["attemptnumber"]);
        }
        if ($params['mode'] == 'DESC'){
            unset($params['mode']);
            $result['submissions'] = $DB->get_records('assign_submission', $params, 'attemptnumber DESC', '*', 0, 1);
        } else {
            unset($params['mode']);
            $result['submissions'] = $DB->get_records('assign_submission', $params, 'attemptnumber ASC');
        }
        
        $result['warnings'] = $warnings;       
        
        return $result;
    }

    /**
     * Describes the get_submission_by_assignid_userid_groupid returns value.
     *
     * @return external_single_structure
     * @since Moodle 3.1
     */
    public static function get_submission_by_assignid_userid_groupid_returns(){
        return new external_single_structure(
            array(
                'submissions' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'id' => new external_value(PARAM_INT, 'submissions ID'),
                            'assignment' => new external_value(PARAM_INT, 'assignment ID'),
                            'userid' => new external_value(PARAM_INT, 'user ID'),
                            'timecreated' => new external_value(PARAM_INT, 'time created'),
                            'timemodified' => new external_value(PARAM_INT, 'time modified'),
                            'status' => new external_value(PARAM_RAW, 'status'),
                            'groupid' => new external_value(PARAM_INT, 'group ID'),
                            'attemptnumber' => new external_value(PARAM_INT, 'attempnumber'),
                            'latest' => new external_value(PARAM_INT, 'lastest')
                        )
                    )
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    /**
     * Describes the parameters for get_attemptnumber_by_assignid_userid_groupid
     *
     * @return external_external_function_parameters
     */
    public static function get_attemptnumber_by_assignid_userid_groupid_parameters(){
        return new external_function_parameters(
            array(
                'assignment' => new external_value(PARAM_INT, 'asssign ID'),
                'userid' => new external_value(PARAM_INT, 'user ID'),
                'groupid' => new external_value(PARAM_INT, 'group ID'),
            )
        );
    }

    /**   
     * Get the number of attemptnumber submission by assigment id, user id, group id
     * 
     * @param int $assignment  - The id of assignment
     * @param int $userid      - The id of user
     * @param int $groupid     - The id of group
     * @return array of attemptnumber and warnings
     * @throws invalid_parameter_exception
     */
    public static function get_attemptnumber_by_assignid_userid_groupid($assignment, $userid, $groupid){
        global $DB;

        $warnings = array();

        $result = array();

        //Validate param
        $params = self::validate_parameters(self::get_attemptnumber_by_assignid_userid_groupid_parameters(),
            array(
                'assignment' => $assignment,
                'userid' => $userid,
                'groupid' => $groupid,
            )
        );

        $result['result'] = $DB->get_records('assign_submission', $params, 'attemptnumber DESC', 'attemptnumber', 0, 1);

        $result['warnings'] = $warnings;

        return $result;
    }

    /**
     * Describes the get_attemptnumber_by_assignid_userid_groupid returns value.
     *
     * @return external_single_structure
     * @since Moodle 3.1
     */
    public static function get_attemptnumber_by_assignid_userid_groupid_returns(){
        return new external_single_structure(
            array(
                'result' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'attemptnumber' => new external_value(PARAM_INT, 'attempnumber'),
                        )
                    )
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    /**
     * Describes the parameters for get_user_flags_by_assignid_userid
     *
     * @return external_external_function_parameters
     */
    public static function get_user_flags_by_assignid_userid_parameters(){
        return new external_function_parameters(
            array(
                'assignment' => new external_value(PARAM_INT, 'asssign ID'),
                'userid' => new external_value(PARAM_INT, 'user ID'),
            )
        );
    }

    /**     
     * Get user flags by assignment id and user id
     * 
     * @param int $assignment  -  The id of assignment.
     * @param int $userid      -  The id of user.
     * @return array of user flags and warnings
     * @throws invalid_parameter_exception
     */
    public static function get_user_flags_by_assignid_userid($assignment, $userid){
        global $DB;

        $warnings = array();

        // Build array result
        $result = array();

        //Validate param
        $params = self::validate_parameters(self::get_user_flags_by_assignid_userid_parameters(),
            array(
                'assignment' => $assignment,
                'userid' => $userid,
            )
        );
        
        $result['userflags'] = $DB->get_record('assign_user_flags', $params);
        if(!$result['userflags']){
            $result['userflags'] = array();
        }
        $result['warnings'] = $warnings;

        return $result;
    }

    /**
     * Describes the get_user_flags_by_assignid_userid returns value.
     *
     * @return external_single_structure
     * @since Moodle 3.1
     */
    public static function get_user_flags_by_assignid_userid_returns(){
        return new external_single_structure(
            array(
                'userflags' => new external_single_structure(
                    array(
                        'id' => new external_value(PARAM_INT, 'assign user flags ID', VALUE_OPTIONAL),
                        'userid' => new external_value(PARAM_INT, 'user ID', VALUE_OPTIONAL),
                        'assignment' => new external_value(PARAM_INT, 'assignment ID', VALUE_OPTIONAL),
                        'locked' => new external_value(PARAM_INT, 'locked', VALUE_OPTIONAL),
                        'mailed' => new external_value(PARAM_INT, 'mailed', VALUE_OPTIONAL),
                        'extensionduedate' => new external_value(PARAM_INT, 'extension due date', VALUE_OPTIONAL),
                        'workflowstate' => new external_value(PARAM_RAW, 'work flow state', VALUE_OPTIONAL),
                        'allocatedmarker' => new external_value(PARAM_INT, 'allocated maker', VALUE_OPTIONAL),
                    )
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    /**
     * Describes the parameters for set_submission_lastest
     *
     * @return external_external_function_parameters
     */
    public static function set_submission_lastest_parameters(){
        return new external_function_parameters(
            array(
                'assignment' => new external_value(PARAM_INT, 'asssign ID'),
                'userid' => new external_value(PARAM_INT, 'user id'),
                'groupid' => new external_value(PARAM_INT, 'group ID'),
            )
        );
    }

    /**      
     * Set submission lastest on hub with params 
     * 
     * @param int $assignment   - The id of assignment
     * @param int $userid       - The id of user 
     * @param int $groupid      - The id of group
     * @return array of result and warnings
     * @throws invalid_parameter_exception
     */
    public static function set_submission_lastest($assignment, $userid, $groupid){
        global $DB;

        $warnings = array();

        $result = array();

        //Validate param
        $params = self::validate_parameters(self::set_submission_lastest_parameters(),
            array(
                'assignment' => $assignment,
                'userid' => $userid,
                'groupid' => $groupid,
            )
        );
        
        $result['result'] = $DB->set_field('assign_submission', 'latest', 0, $params);

        $result['warnings'] = $warnings;

        return $result;
    }

    /**
     * Describes the set_submission_lastest return value.
     *
     * @return external_single_structure
     * @since Moodle 3.1
     */
    public static function set_submission_lastest_returns(){
        return new external_single_structure(
            array(
                'result' => new external_value(PARAM_BOOL, 'True if success'),
                'warnings' => new external_warnings()
            )
        );
    }

    /**
     * Describes the parameters for create_submission_parameters
     *
     * @return external_external_function_parameters
     */
    public static function create_submission_parameters(){
        return new external_function_parameters(
            array(
                'assignment' => new external_value(PARAM_INT, 'asssign ID'),
                'userid' => new external_value(PARAM_INT, 'userid'),
                'timecreated' => new external_value(PARAM_INT, 'time created'),
                'timemodified' => new external_value(PARAM_INT, 'time modified'),
                'status' => new external_value(PARAM_RAW, 'status'),
                'attemptnumber' => new external_value(PARAM_INT, 'attempnumber'),
                'latest' => new external_value(PARAM_INT, 'latest'),
            )
        );
    }

    /**       
     * Create new submission with params received from host            
     * 
     * @param int $assignment   - The id of assignment
     * @param int $userid       - The id of user
     * @param int $timecreated  - The created time
     * @param int $timemodified - The modified time
     * @param string $status    - The string of status
     * @param int $attemptnumber- The number of attemptnumber
     * @param int $latest       - Lastest
     * @return array of id number submission just created and warnings
     * @throws invalid_parameter_exception
     */
    public static function create_submission($assignment, $userid, $timecreated, $timemodified, $status, $attemptnumber, $latest){
        global $DB;

        $warnings = array();

        $result = array();

        //Validate param
        $params = self::validate_parameters(self::create_submission_parameters(),array(
            'assignment' => $assignment,
            'userid' => $userid,
            'timecreated' => $timecreated,
            'timemodified' => $timemodified,
            'status' => $status,
            'attemptnumber' => $attemptnumber,
            'latest' => $latest,
        ));
        
        $submission = new stdClass();
        $submission->assignment   = $params['assignment'];
        $submission->userid       = $params['userid'];
        $submission->timecreated = $params['timecreated'];
        $submission->timemodified = $params['timemodified'];
        $submission->status = $params['status'];
        $submission->attemptnumber = $params['attemptnumber'];
        $submission->latest = $params['latest'];

        $transaction = $DB->start_delegated_transaction();
        $result['sid'] = $DB->insert_record('assign_submission', $submission);
        $transaction->allow_commit();

        $result['warnings'] = $warnings;

        return $result;
    }

    /**
     * Describes the create_submission return value.
     *
     * @return external_single_structure
     * @since Moodle 3.1
     */
    public static function create_submission_returns(){
        return new external_single_structure(
            array(
                'sid' => new external_value(PARAM_INT, 'submission ID'),
                'warnings' => new external_warnings()
            )
        );
    }
    
    /**
     * Describes the parameters for update_submission
     *
     * @return external_external_function_parameters
     */
    public static function update_submission_parameters(){
        return new external_function_parameters(
            array(
                'id' => new external_value(PARAM_INT, 'submission ID'),
                'assignment' => new external_value(PARAM_INT, 'asssign ID'),
                'userid' => new external_value(PARAM_INT, 'user ID'),
                'timecreated' => new external_value(PARAM_INT, 'time created'),
                'timemodified' => new external_value(PARAM_INT, 'time modified'),
                'status' => new external_value(PARAM_RAW, 'status'),
                'attemptnumber' => new external_value(PARAM_INT, 'attemptnumber'),
                'latest' => new external_value(PARAM_INT, 'latest'),
            )
        );
    }

    /**          
     * Update assign submission wwith id and params from host
     * 
     * @param int $id           - The id of submission
     * @param int $assignment   - The id of assignment
     * @param int $userid       - The id of user
     * @param int $timecreated  - The created time
     * @param int $timemodified - The modified time
     * @param string $status    - The string of status
     * @param int $attemptnumber- The number of attempnumber
     * @param int $latest       - Is lastest?
     * @return array of bool check update DB and warnings
     * @throws invalid_parameter_exception
     */
    public static function update_submission($id, $assignment, $userid, $timecreated, $timemodified, $status, $attemptnumber, $latest){
        global $DB;

        $warnings = array();

        $result = array();

        //Validate param
        $params = self::validate_parameters(self::update_submission_parameters(),array(
            'id' => $id,
            'assignment' => $assignment,
            'userid' => $userid,
            'timecreated' => $timecreated,
            'timemodified' => $timemodified,
            'status' => $status,
            'attemptnumber' => $attemptnumber,
            'latest' => $latest,
        ));

        $submission = (object)$params;
        
        $transaction = $DB->start_delegated_transaction();
        $result['bool'] = $DB->update_record('assign_submission', $submission);
        $transaction->allow_commit();

        $result['warnings'] = $warnings;

        return $result;
    }

    /**
     * Describes the update_submission return value.
     *
     * @return external_single_structure
     * @since Moodle 3.1
     */
    public static function update_submission_returns(){
        return new external_single_structure(
            array(
                'bool' => new external_value(PARAM_INT, 'true if success'),
                'warnings' => new external_warnings()
            )
        );
    }

    /**
     * Describes the parameters for get_submission_by_id
     *
     * @return external_external_function_parameters
     */
    public static function get_submission_by_id_parameters(){
        return new external_function_parameters(
            array(
                'id' => new external_value(PARAM_INT, 'submission ID'),
            )
        );
    }

    /** 
     * Get assign submission by id
     * 
     * @param int $id   - The id of submission
     * @return array of assign submission and warnings
     * @throws invalid_parameter_exception
     */
    public static function get_submission_by_id($id){
        global $DB;

        $warnings = array();

        $result = array();

        //Validate param
        $params = self::validate_parameters(self::get_submission_by_id_parameters(),array(
            "id" => $id
        ));

        $assignsubmisison = $DB->get_record('assign_submission', array('id' => $params['id']));
        
        $user = $DB->get_record('user', array('id' => $assignsubmisison->userid));
        $assignsubmisison->useremail = $user->email;

        $result['assignsubmisison'] = $assignsubmisison;

        $result['warnings'] = $warnings;

        return $result;
    }

    public static function get_submission_by_id_returns(){
        return new external_single_structure(
            array(
                'assignsubmisison' => new external_single_structure(
                    array(
                        'id' => new external_value(PARAM_INT, 'submission ID'),
                        'assignment' => new external_value(PARAM_INT, 'assignment'),
                        'userid' => new external_value(PARAM_INT, 'user ID'),
                        'useremail' => new external_value(PARAM_RAW, 'user email'),
                        'timecreated' => new external_value(PARAM_INT, 'time created'),
                        'timemodified' => new external_value(PARAM_INT, 'time modified'),
                        'status' => new external_value(PARAM_RAW, 'status'),
                        'groupid' => new external_value(PARAM_INT, 'groupid'),
                        'attemptnumber' => new external_value(PARAM_INT, 'attempnumber'),
                        'latest' => new external_value(PARAM_INT, 'latest'),
                    )
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    /**
     * Describes the parameters for save_submission
     * @return external_external_function_parameters
     * @since  Moodle 2.6
     */
    public static function save_remote_submission_parameters() {
        global $CFG;
        $instance = new assign(null, null, null);
        $pluginsubmissionparams = array();

        foreach ($instance->get_submission_plugins() as $plugin) {
            if ($plugin->is_visible()) {
                $pluginparams = $plugin->get_external_parameters();
                if (!empty($pluginparams)) {
                    $pluginsubmissionparams = array_merge($pluginsubmissionparams, $pluginparams);
                }
            }
        }

        return new external_function_parameters(
            array(
                'assignmentid' => new external_value(PARAM_INT, 'The assignment id to operate on'),
                'userid' => new external_value(PARAM_INT, 'The userid'),
                'plugindata' => new external_single_structure(
                    $pluginsubmissionparams
                )
            )
        );
    }

    /**
     * Save a student submission for a single assignment
     *
     * @param int $assignmentid The id of the assignment
     * @param int $userid - The id of user
     * @param array $plugindata - The submitted data for plugins
     * @return array of warnings to indicate any errors
     * @since Moodle 2.6
     */
    public static function save_remote_submission($assignmentid, $userid, $plugindata) {
        global $CFG, $USER;

        $params = self::validate_parameters(self::save_remote_submission_parameters(),
            array('assignmentid' => $assignmentid,
                'userid' => $userid,
                'plugindata' => $plugindata));

        $USER->id = $userid;

        $cm = get_coursemodule_from_instance('assign', $params['assignmentid'], 0, false, MUST_EXIST);

        $context = context_module::instance($cm->id);
        self::validate_context($context);

        $assignment = new assign($context, $cm, null);

        $notices = array();

        if (!$assignment->submissions_open($USER->id)) {
            $notices[] = get_string('duedatereached', 'assign');
        } else {
            $submissiondata = (object)$params['plugindata'];
            $assignment->save_submission($submissiondata, $notices);
        }

        $warnings = array();
        foreach ($notices as $notice) {
            $warnings[] = self::generate_warning($params['assignmentid'],
                'couldnotsavesubmission',
                $notice);
        }

        return $warnings;
    }

    /**
     * Describes the return value for save_submission
     *
     * @return external_single_structure
     * @since Moodle 2.6
     */
    public static function save_remote_submission_returns() {
        return new external_warnings();
    }
    
    /**
     * Describes the parameters for submit_remote_for_grading
     * @return external_external_function_parameters
     * @since  Moodle 2.6
     */
    public static function submit_remote_for_grading_parameters() {
        return new external_function_parameters(
            array(
                'assignment' => new external_value(PARAM_INT, 'The assignment id to operate on'),
                'userid' => new external_value(PARAM_INT, 'The userid'),
                'data' => new external_single_structure(
                    array(
                        'submissionstatement' => new external_value(PARAM_INT, 'Accept the assignment submission statement', VALUE_OPTIONAL),
                        'id' => new external_value(PARAM_INT, 'The course module id to operate'),
                        'action' => new external_value(PARAM_RAW, 'The action to show'),
                        'submitbutton' => new external_value(PARAM_RAW, 'Name of submissbutton', VALUE_OPTIONAL),
                    )
                )
            )
        );
    }

    /**
     * Submit submission for grading
     *
     * @param int $assignmentid The id of the assignment
     * @param int $userid The id of the user
     * @param array $data - The submitted data for grading
     * @return array of warnings to indicate any errors
     * @since Moodle 2.6
     */
    public static function submit_remote_for_grading($assignment, $userid, $data) {
        global $CFG, $USER;

        $params = self::validate_parameters(self::submit_remote_for_grading_parameters(),
            array('assignment' => $assignment,
                'userid' => $userid,
                'data' => $data));

        $USER->id = $params['userid'];

        $cm = get_coursemodule_from_instance('assign', $params['assignment'], 0, false, MUST_EXIST);

        $context = context_module::instance($cm->id);
        self::validate_context($context);

        $assignment = new assign($context, $cm, null);

        $notices = array();

        if (!$assignment->submissions_open($USER->id)) {
            $notices[] = get_string('submissionsclosed', 'assign');
        } else {
            $submitdata = (object)$params['data'];
            $assignment->submit_for_grading($submitdata, $notices);
        }

        $warnings = array();
        foreach ($notices as $notice) {
            $warnings[] = self::generate_warning($params['assignment'],
                'couldnotsavesubmission',
                $notice);
        }

        return $warnings;
    }

    /**
     * Describes the return value for submit_remote_for_grading
     *
     * @return external_single_structure
     * @since Moodle 2.6
     */
    public static function submit_remote_for_grading_returns() {
        return new external_warnings();
    }


    /**
     * Describes the parameters for get_submission_status.
     *
     * @return external_external_function_parameters
     * @since Moodle 3.1
     */
    public static function get_remote_submission_status_parameters() {
        return new external_function_parameters (
            array(
                'assignid' => new external_value(PARAM_INT, 'assignment instance id'),
                'userid' => new external_value(PARAM_INT, 'user id (empty for current user)', VALUE_DEFAULT, 0),
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
    public static function get_remote_submission_status($assignid, $userid = 0) {
        global $USER, $DB;

        $warnings = array();

        $params = array(
            'assignid' => $assignid,
            'userid' => $userid,
        );
        $params = self::validate_parameters(self::get_remote_submission_status_parameters(), $params);

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

        $USER = $user;

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
                if (isset($feedback->grade->grader)) {
                    $grader = $DB->get_record('user', array('id' => $feedback->grade->grader));
                }
                $feedback->grade->grader = $grader->email;
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
                    $grade->grader = $grade->grader->email;
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
    public static function get_remote_submission_status_returns() {
        return new external_single_structure(
            array(
                'gradingsummary' => new external_single_structure(
                    array(
                        'participantcount' => new external_value(PARAM_INT, 'Number of users who can submit.'),
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
                        'submission' => self::get_submission_structure(VALUE_OPTIONAL),
                        'teamsubmission' => self::get_submission_structure(VALUE_OPTIONAL),
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
                        'gradingcontrollerpreview' => new external_value(PARAM_RAW, 'Whether grading controller preview.'),
                        'gradingstatus' => new external_value(PARAM_ALPHANUMEXT, 'Grading status.'),
                        'usergroups' => new external_multiple_structure(
                            new external_value(PARAM_INT, 'Group id.'), 'User groups in the course.'
                        ),
                    ), 'Last attempt information.', VALUE_OPTIONAL
                ),
                'feedback' => new external_single_structure(
                    array(
                        'grade' => self::get_grade_structure(VALUE_OPTIONAL),
                        'gradefordisplay' => new external_value(PARAM_RAW, 'Grade rendered into a format suitable for display.'),
                        'gradeddate' => new external_value(PARAM_INT, 'The date the user was graded.'),
                        'plugins' => new external_multiple_structure(self::get_plugin_structure(), 'Plugins info.', VALUE_OPTIONAL),
                    ), 'Feedback for the last attempt.', VALUE_OPTIONAL
                ),
                'previousattempts' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'attemptnumber' => new external_value(PARAM_INT, 'Attempt number.'),
                            'submission' => self::get_submission_structure(VALUE_OPTIONAL),
                            'grade' => self::get_grade_structure(VALUE_OPTIONAL),
                            'feedbackplugins' => new external_multiple_structure(self::get_plugin_structure(), 'Feedback info.',
                                VALUE_OPTIONAL),
                        )
                    ), 'List all the previous attempts did by the user.', VALUE_OPTIONAL
                ),
                'warnings' => new external_warnings(),
            )
        );
    }

    /**
     * Creates a submission structure.
     *
     * @return external_single_structure the submission structure
     */
    private static function get_submission_structure($required = VALUE_REQUIRED) {
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
                'plugins' => new external_multiple_structure(self::get_plugin_structure(), 'plugins', VALUE_OPTIONAL)
            ), 'submission info', $required
        );
    }

    /**
     * Creates an assignment plugin structure.
     *
     * @return external_single_structure the plugin structure
     */
    private static function get_plugin_structure() {
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
     * Creates a grade single structure.
     *
     * @return external_single_structure a grade single structure.
     * @since  Moodle 3.1
     */
    private static function get_grade_structure($required = VALUE_REQUIRED) {
        return new external_single_structure(
            array(
                'id'                => new external_value(PARAM_INT, 'grade id'),
                'assignment'        => new external_value(PARAM_INT, 'assignment id', VALUE_OPTIONAL),
                'userid'            => new external_value(PARAM_INT, 'student id'),
                'attemptnumber'     => new external_value(PARAM_INT, 'attempt number'),
                'timecreated'       => new external_value(PARAM_INT, 'grade creation time'),
                'timemodified'      => new external_value(PARAM_INT, 'grade last modified time'),
                'grader'            => new external_value(PARAM_RAW, 'email grader'),
                'grade'             => new external_value(PARAM_FLOAT, 'grade'),
                'gradefordisplay'   => new external_value(PARAM_RAW, 'grade rendered into a format suitable for display',
                    VALUE_OPTIONAL),
            ), 'grade information', $required
        );
    }

    /**
     * Describes the parameters for get grades by assignid & userid
     * 
     * @return external_external_function_parameters
     */
    public static function get_grades_by_assignid_userid_parameters(){
        return new external_function_parameters(
            array(
                'assignment' => new external_value(PARAM_INT, 'The assignment id to operate on'),
                'userid' => new external_value(PARAM_INT, 'The user ID'),
                'attemptnumber' => new external_value(PARAM_INT, 'The attemptnumber'),
                'mode' => new external_value(PARAM_RAW, 'The mode to return')
            )
        );
    }

    /**
     * Returns information about a list array assign grades.
     *
     * @param int $assignmentid  -  The id of assignment
     * @param int $userid        -  The id of user
     * @param int $attemptnumber -  The id of attemptnumber
     * @return array of warnings and grades information
     * @throws required_capability_exception
     */
    public static function get_grades_by_assignid_userid($assignmentid, $userid, $attemptnumber, $mode){
        global $DB;

        $warnings = array();

        $result = array();

        //Validate param
        $params = self::validate_parameters(self::get_grades_by_assignid_userid_parameters(),
            array(
                'assignment' => $assignmentid,
                'userid' => $userid,
                'attemptnumber' => $attemptnumber,
                'mode' => $mode,
            )
        );
        if ($params["attemptnumber"] < 0){
            unset($params["attemptnumber"]);
        }
        
        if ($params['mode'] == 'DESC'){
            unset($params['mode']);
            $result['grades'] = $DB->get_records('assign_grades', $params, 'attemptnumber DESC', '*', 0, 1);
        } else if ($params['mode'] == 'ASC') {
            unset($params['mode']);
            $result['grades'] = $DB->get_records('assign_grades', $params, 'attemptnumber ASC');
        }
        
        if ($result['grades']){
            foreach ($result['grades'] as $grade) {
                if (isset($grade->grader)) {
                    $grader = $DB->get_record('user', array('id' => $grade->grader));
                    $grade->grader = $grader->email;
                }
            }
        }

        $result['warnings'] = $warnings;

        return $result;
    }

    /**
     * Describes the get_grades_by_assignid_userid return value.
     *
     * @return external_single_structure
     * @since Moodle 3.1
     */
    public static function get_grades_by_assignid_userid_returns(){
        return new external_single_structure(
            array(
                'grades' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'id' => new external_value(PARAM_INT, 'assign user flags ID'),
                            'assignment' => new external_value(PARAM_INT, 'assignment ID'),
                            'userid' => new external_value(PARAM_INT, 'user ID'),
                            'timecreated' => new external_value(PARAM_INT, 'time created'),
                            'timemodified' => new external_value(PARAM_INT, 'time modified'),
                            'grader' => new external_value(PARAM_RAW, 'email grader'),
                            'grade' => new external_value(PARAM_FLOAT, 'grade number'),
                            'attemptnumber' => new external_value(PARAM_INT, 'attemptnumber'),
                        )
                    )
                ),
                'warnings' => new external_warnings()
            )
        );
    }
    
    /**
     * Describes the parameters for get grades by id  
     * 
     * @return external_external_function_parameters
     */
    public static function get_grades_by_id_parameters(){
        return new external_function_parameters(
            array(
                'id' => new external_value(PARAM_INT, 'The id of grade'),
            )
        );
    }

    /**
     * Returns information about a list array assign grades.
     *
     * @param int $id the id of assign grade
     * @return array of warnings and assign grades information
     * @throws required_capability_exception
     */
    public static function get_grades_by_id($id){
        global $DB;

        $warnings = array();

        $result = array();

        //Validate param
        $params = self::validate_parameters(self::get_grades_by_id_parameters(),
            array(
                'id' => $id,
            )
        );
        
        $result['grades'] = $DB->get_record('assign_grades', array('id' => $params['id']), 'userid,assignment', MUST_EXIST);
        if (!$result['grades']){
            $result['grades'] = array();
        }
        
        $result['warnings'] = $warnings;

        return $result;
    }

    /**
     * Describes the get_grades_by_id return value.
     *
     * @return external_single_structure
     * @since Moodle 3.1
     */
    public static function get_grades_by_id_returns(){
        return new external_single_structure(
            array(
                'grades' => new external_single_structure(
                    array(
                        'userid' => new external_value(PARAM_INT, 'user ID'),
                        'assignment' => new external_value(PARAM_INT, 'assignment ID'),
                    )
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    /**
     * Describes the parameters for create grade  
     * 
     * @return external_external_function_parameters
     */
    public static function create_grade_parameters(){
        return new external_function_parameters(
            array(
                'assignment' => new external_value(PARAM_INT, 'asssign ID'),
                'userid' => new external_value(PARAM_INT, ' userid'),
                'timecreated' => new external_value(PARAM_INT, 'time created'),
                'timemodified' => new external_value(PARAM_INT, 'time modified'),
                'grader' => new external_value(PARAM_INT, 'grader id'),
                'grade' => new external_value(PARAM_FLOAT, 'grade score'),
                'attemptnumber' => new external_value(PARAM_INT, 'attempnumber'),
            )
        );
    }

    /**
     * Returns assign grade id.
     *
     * @param int $assignmentid assignment id
     * @param int $userid userid
     * @param int $timecreated time created
     * @param int $timemodified time modified
     * @param int $grader grader id
     * @param int $grade number score
     * @param int $attemptnumber attemp number
     * @return idnumber of assign grade just created
     */
    public static function create_grade($assignment, $userid, $timecreated, $timemodified, $grader, $grade, $attemptnumber){
        global $DB;

        $warnings = array();

        $result = array();

        //Validate param
        $params = self::validate_parameters(self::create_grade_parameters(),array(
            'assignment' => $assignment,
            'userid' => $userid,
            'timecreated' => $timecreated,
            'timemodified' => $timemodified,
            'grader' => $grader,
            'grade' => $grade,
            'attemptnumber' => $attemptnumber,
        ));

        $grade = new stdClass();
        $grade->assignment   = $params['assignment'];
        $grade->userid       = $params['userid'];
        $grade->timecreated = $params['timecreated'];
        $grade->timemodified = $params['timemodified'];
        $grade->grader = $params['grader'];
        $grade->grade = $params['grade'];
        $grade->attemptnumber = $params['attemptnumber'];
        
        $transaction = $DB->start_delegated_transaction();
        $result['gid'] = $DB->insert_record('assign_grades', $grade);
        $transaction->allow_commit();

        $result['warnings'] = $warnings;

        return $result;
    }

    /**
     * Describes the create_gradereturn value.
     *
     * @return external_single_structure
     * @since Moodle 3.1
     */
    public static function create_grade_returns(){
        return new external_single_structure(
            array(
                'gid' => new external_value(PARAM_INT, 'assign grade ID'),
                'warnings' => new external_warnings()
            )
        );
    }
    
    /**
     * Describes the parameters for update grade
     * @return external_external_function_parameters
     */
    public static function update_grade_parameters(){
        return new external_function_parameters(
            array(
                'id' => new external_value(PARAM_INT, 'grade ID'),
                'assignment' => new external_value(PARAM_INT, 'asssign ID'),
                'userid' => new external_value(PARAM_INT, ' user ID'),
                'timecreated' => new external_value(PARAM_INT, 'time created'),
                'timemodified' => new external_value(PARAM_INT, 'time modified'),
                'grader' => new external_value(PARAM_INT, 'grader id'),
                'grade' => new external_value(PARAM_FLOAT, 'grade score'),
                'attemptnumber' => new external_value(PARAM_INT, 'attempnumber'),
            )
        );
    }

    /**
     * Returns assign grade id.
     *
     * @param int $id grade id
     * @param int $assignmentid assignment id
     * @param int $userid the id of user
     * @param int $timecreated time created
     * @param int $timemodified time modified
     * @param int $grader grader id
     * @param int $grade number score
     * @param int $attemptnumber attemp number
     * @return idnumber of assign grade just created
     */
    public static function update_grade($id, $assignment, $userid, $timecreated, $timemodified, $grader, $grade, $attemptnumber){
        global $DB;

        $warnings = array();

        $result = array();

        //Validate param
        $params = self::validate_parameters(self::update_grade_parameters(),array(
            'id' => $id,
            'assignment' => $assignment,
            'userid' => $userid,
            'timecreated' => $timecreated,
            'timemodified' => $timemodified,
            'grader' => $grader,
            'grade' => $grade,
            'attemptnumber' => $attemptnumber,
        ));

        $grade = (object)$params;

        $transaction = $DB->start_delegated_transaction();

        $result['bool'] =  $DB->update_record('assign_grades', $grade);

        $transaction->allow_commit();

        $result['warnings'] = $warnings;

        return $result;
    }

    /**
     * Describes the update_grade return value.
     *
     * @return external_single_structure
     * @since Moodle 3.1
     */
    public static function update_grade_returns(){
        return new external_single_structure(
            array(
                'bool' => new external_value(PARAM_INT, 'check if success'),
                'warnings' => new external_warnings()
            )
        );
    }
    
    /**
     * Describes the parameters for get remote submission info for participant
     *
     * @return external_external_function_parameters
     */
    public static function get_remote_submission_info_for_participants_parameters(){
        return new external_function_parameters(
            array(
                'assignment' => new external_value(PARAM_INT, 'The assignment id to operate on'),
                'emails' => new external_multiple_structure(
                    new external_value(PARAM_RAW, 'The email of user')
                )
            )
        );
    }

    /**
     * Returns a list submission info for participant.
     *
     * @param int $assignment - the id of assignment
     * @param array $emails - The list of email participants
     * 
     * @return array of warnings and submission information for participants
     * @throws required_capability_exception
     */
    public static function get_remote_submission_info_for_participants($assignment, $emails){
        global $DB;

        $warnings = array();

        $result = array();

        //Validate param
        $params = self::validate_parameters(self::get_remote_submission_info_for_participants_parameters(),
            array(
                'assignment' => $assignment,
                'emails' => $emails,
            )
        );

        $participants = $DB->get_records_list('user', 'email', $params['emails']);

        if (empty($participants)) {
            $result['ret'] = array();
            $result['warnings'] = $warnings;
            return $result;
        }
        
        list($insql, $qparams) = $DB->get_in_or_equal(array_keys($participants), SQL_PARAMS_NAMED);


        $qparams['assignmentid1'] = $params['assignment'];
        $qparams['assignmentid2'] = $params['assignment'];

        $sql = 'SELECT u.id, s.status, s.timemodified AS stime, g.timemodified AS gtime, g.grade FROM {user} u
                         LEFT JOIN {assign_submission} s
                                ON u.id = s.userid
                               AND s.assignment = :assignmentid1
                               AND s.latest = 1
                         LEFT JOIN {assign_grades} g
                                ON u.id = g.userid
                               AND g.assignment = :assignmentid2
                               AND g.attemptnumber = s.attemptnumber
                         WHERE u.id ' . $insql;

        $result['ret'] = $DB->get_records_sql($sql, $qparams);

        foreach ($result['ret'] as $parinfo){
            $user = $DB->get_record('user', array('id' => $parinfo->id));
            $parinfo->email = $user->email;
        }

        $result['warnings'] = $warnings;

        return $result;
    }

    /**
     * Describes the get_remote_submission_info_for_participant return value.
     *
     * @return external_single_structure
     * @since Moodle 3.1
     */
    public static function get_remote_submission_info_for_participants_returns(){
        return new external_single_structure(
            array(
                'ret' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'email' => new external_value(PARAM_RAW, 'The email of user'),
                            'status' => new external_value(PARAM_RAW, 'The status of submission'),
                            'stime' => new external_value(PARAM_INT, 'Start time'),
                            'gtime' => new external_value(PARAM_INT, 'Grade time'),
                            'grade' => new external_value(PARAM_FLOAT, 'Grade score'),
                        )
                    )
                ),
                'warnings' => new external_warnings()
            )
        );
    }

    /**
     * Describes the parameters for submit_grading_form webservice.
     * @return external_external_function_parameters
     * @since  Moodle 3.1
     */
    public static function submit_grading_form_parameters() {
        return new external_function_parameters(
            array(
                'assignmentid' => new external_value(PARAM_INT, 'The assignment id to operate on'),
                'userid' => new external_value(PARAM_INT, 'The user id the submission belongs to'),
                'guserid' => new external_value(PARAM_INT, 'The teacher user id the submission belongs to'),
                'jsonformdata' => new external_value(PARAM_RAW, 'The data from the grading form, encoded as a json array')
            )
        );
    }

    /**
     * Submit the logged in users assignment for grading.
     *
     * @param int $assignmentid The id of the assignment
     * @param int $userid The id of the user the submission belongs to.
     * @param string $jsonformdata The data from the form, encoded as a json array.
     * @return array of warnings to indicate any errors.
     * @since Moodle 2.6
     */
    public static function submit_grading_form($assignmentid, $userid, $guserid, $jsonformdata) {
        global $DB, $CFG, $USER;

        require_once($CFG->dirroot . '/mod/assign/locallib.php');
        require_once($CFG->dirroot . '/mod/assign/gradeform.php');

        $warnings = array();

        $params = self::validate_parameters(self::submit_grading_form_parameters(),
            array(
                'assignmentid' => $assignmentid,
                'userid' => $userid,
                'guserid' => $guserid,
                'jsonformdata' => $jsonformdata
            ));

        $grader = $DB->get_record('user', array('id' => $params['guserid']));
        $USER = $grader;
        
        $cm = get_coursemodule_from_instance('assign', $params['assignmentid'], 0, false, MUST_EXIST);
        $context = context_module::instance($cm->id);
        self::validate_context($context);

        $assignment = new assign($context, $cm, null);

        $serialiseddata = json_decode($params['jsonformdata']);

        $data = array();
        parse_str($serialiseddata, $data);
        unset($data['sesskey']);
        
        $customdata = (object)$data;

        $result = $assignment->save_grade($params['userid'], $customdata);
        
        if(!$result){
            $warnings[] = self::generate_warning($params['assignmentid'],
                'couldnotsavegrade',
                'Could not save grade!.');

        }
        
        return $warnings;
    }

    /**
     * Describes the return for submit_grading_form
     * @return external_external_function_parameters
     * @since  Moodle 3.1
     */
    public static function submit_grading_form_returns() {
        return new external_warnings();
    }

    /**
     * @return external_function_parameters
     */
    public static function get_raw_data_query_db_parameters() {
        return new external_function_parameters (
            array(
                'sql' => new external_value(PARAM_RAW, 'sql'),
                'param' => new  external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'name'),
                            'value' => new external_value(PARAM_RAW, 'value'),
                        )
                    )
                ),
                'pagestart' => new external_value(PARAM_INT, 'page start'),
                'pagesize' => new external_value(PARAM_INT, 'page size'),
            )
        );
    }

    /**
     * Get raw data via ajax
     * @param $sql
     * @param $param
     * @param $pagestart
     * @param $pagesize
     * @return array
     * @throws invalid_parameter_exception
     */
    public static function get_raw_data_query_db($sql, $param, $pagestart, $pagesize) {
        global $DB;

        //validate parameter
        $params = self::validate_parameters(self::get_raw_data_query_db_parameters(),
            array('sql' => $sql, 'param' => $param, 'pagestart' => $pagestart, 'pagesize' => $pagesize));

        $branch = array();
        foreach ($params['param'] as $element) {
            $branch[$element['name']] = $element['value'];
        }

        $rawdata = $DB->get_records_sql($params['sql'], $branch, $params['pagestart'], $params['pagesize']);
        return $rawdata;
    }

    /**
     * @return external_multiple_structure
     */
    public static function get_raw_data_query_db_returns() {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'id' => new external_value(PARAM_INT, 'grand total', VALUE_OPTIONAL),
                    'picture' => new external_value(PARAM_INT, 'picture', VALUE_OPTIONAL),
                    'firstname' => new external_value(PARAM_RAW, 'first name', VALUE_OPTIONAL),
                    'lastname' => new external_value(PARAM_RAW, 'last name', VALUE_OPTIONAL),
                    'firstnamephonetic' => new external_value(PARAM_RAW, 'firstname phonetic', VALUE_OPTIONAL),
                    'lastnamephonetic' => new external_value(PARAM_RAW, 'lastname phonetic', VALUE_OPTIONAL),
                    'middlename' => new external_value(PARAM_RAW, 'middlename', VALUE_OPTIONAL),
                    'alternatename' => new external_value(PARAM_RAW, 'alternate name', VALUE_OPTIONAL),
                    'imagealt' => new external_value(PARAM_RAW, 'image alt', VALUE_OPTIONAL),
                    'email' => new external_value(PARAM_RAW, 'email', VALUE_OPTIONAL),
                    'userid' => new external_value(PARAM_INT, 'userid', VALUE_OPTIONAL),
                    'status' => new external_value(PARAM_RAW, 'submission status', VALUE_OPTIONAL),
                    'submissionid' => new external_value(PARAM_INT, 'submissionid', VALUE_OPTIONAL),
                    'firstsubmission' => new external_value(PARAM_INT, 'firstsubmission', VALUE_OPTIONAL),
                    'timesubmitted' => new external_value(PARAM_INT, 'timesubmitted', VALUE_OPTIONAL),
                    'attemptnumber' => new external_value(PARAM_INT, 'attemptnumber', VALUE_OPTIONAL),
                    'gradeid' => new external_value(PARAM_INT, 'gradeid', VALUE_OPTIONAL),
                    'grade' => new external_value(PARAM_FLOAT, 'grade', VALUE_OPTIONAL),
                    'timemarked' => new external_value(PARAM_INT, 'timemarked', VALUE_OPTIONAL),
                    'firstmarked' => new external_value(PARAM_INT, 'firstmarked', VALUE_OPTIONAL),
                    'mailed' => new external_value(PARAM_INT, 'mailed', VALUE_OPTIONAL),
                    'locked' => new external_value(PARAM_INT, 'locked', VALUE_OPTIONAL),
                    'extensionduedate' => new external_value(PARAM_INT, 'extensionduedate', VALUE_OPTIONAL),
                    'workflowstate' => new external_value(PARAM_RAW, 'workflowstate', VALUE_OPTIONAL),
                    'allocatedmarker' => new external_value(PARAM_INT, 'allocatedmarker', VALUE_OPTIONAL),
                )
            )
        );
    }

    /**
     * validation
     * @return external_function_parameters
     */
    public static function get_grade_raw_data_infomation_parameters()
    {
        return new external_function_parameters (
            array(
                'sql' => new external_value(PARAM_RAW, 'sql'),
                'param' => new  external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'name'),
                            'value' => new external_value(PARAM_RAW, 'value'),
                        )
                    )
                ),
                'pagestart' => new external_value(PARAM_INT, 'pagestart', VALUE_DEFAULT, 0),
                'pagesize' => new external_value(PARAM_INT, 'pagesize', VALUE_DEFAULT, 0),
            )
        );
    }

    /**
     * Get grade raw data infomation
     * @param $sql
     * @param $param
     * @param $pagestart
     * @param $pagesize
     * @return array
     * @throws invalid_parameter_exception
     */
    public static function get_grade_raw_data_infomation($sql, $param, $pagestart, $pagesize)
    {
        global $DB;

        //validate parameter
        $params = self::validate_parameters(self::get_grade_raw_data_infomation_parameters(),
            array('sql' => $sql, 'param' => $param, 'pagestart' => $pagestart, 'pagesize' => $pagesize));

        $branch = array();
        foreach ($params['param'] as $element) {
            $branch[$element['name']] = $element['value'];
        }

        $rawdata = $DB->get_records_sql($params['sql'], $branch, $params['pagestart'], $params['pagesize']);
        return $rawdata;
    }

    /**
     * return value for get_grade_raw_data_infomation
     * @return external_multiple_structure
     */
    public static function get_grade_raw_data_infomation_returns()
    {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'userid' => new external_value(PARAM_INT, 'userid', VALUE_OPTIONAL),
                    'grade' => new external_value(PARAM_INT, 'grade', VALUE_OPTIONAL),
                    'lastmodified' => new external_value(PARAM_INT, 'lastmodified', VALUE_OPTIONAL),
                    'workflowstate' => new external_value(PARAM_RAW, 'workflowstate', VALUE_OPTIONAL),
                    'allocatedmarker' => new external_value(PARAM_INT, 'allocatedmarker', VALUE_OPTIONAL),
                    'attemptnumber' => new external_value(PARAM_INT, 'attemptnumber', VALUE_OPTIONAL),
                )
            )
        );
    }

    /**
     * Describes the parameters for get_scale_by_id
     *
     * @return external_external_function_parameters
     */
    public static function get_scale_by_id_parameters(){
        return new external_function_parameters(
            array(
                'sid' => new external_value(PARAM_INT, 'Scale ID'),
            )
        );
    }

    /**
     * @param $sid  - The id of scale
     * @return array
     * @throws invalid_parameter_exception
     */
    public static function get_scale_by_id($sid){
        global $DB;

        $warnings = array();

        $result = array();

        //Validate param
        $params = self::validate_parameters(self::get_scale_by_id_parameters(),array(
            'sid' => $sid,
        ));

        $result['scale'] = $DB->get_record('scale', array('id' => $params['sid']));
        if (!$result['scale']){
            $result['scale'] = array();
        }       
        
        $result['warnings'] = $warnings;

        return $result;
    }

    /**
     * Describes the update_submission_parameters return value.
     *
     * @return external_single_structure
     * @since Moodle 3.1
     */
    public static function get_scale_by_id_returns(){
        return new external_single_structure(
            array(
                'scale' => new external_single_structure(
                    array(
                        'id' => new external_value(PARAM_INT, 'scale ID'),
                        'courseid' => new external_value(PARAM_INT, 'course ID'),
                        'userid' => new external_value(PARAM_INT, 'user ID'),
                        'name' => new external_value(PARAM_RAW, 'Name'),
                        'scale' => new external_value(PARAM_RAW, 'Scale'),
                        'description' => new external_value(PARAM_RAW, 'description'),
                        'descriptionformat' => new external_value(PARAM_INT, 'description format'),
                        'timemodified' => new external_value(PARAM_INT, 'Time modified'),
                    )
                ),
                'warnings' => new external_warnings()
            )
        );
    }


    /**
     * validate data for get_grade_items_raw_data api
     * @return external_function_parameters
     */
    public static function get_grade_items_raw_data_parameters() {
        return new external_function_parameters (
            array(
                'sql' => new external_value(PARAM_RAW, 'sql'),
                'param' => new  external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'name'),
                            'value' => new external_value(PARAM_RAW, 'value'),
                        )
                    )
                ),
                'pagestart' => new external_value(PARAM_INT, 'pagestart', VALUE_DEFAULT, 0),
                'pagesize' => new external_value(PARAM_INT, 'pagesize', VALUE_DEFAULT, 0),
            )
        );
    }

    /**
     * @param $sql
     * @param $param
     * @param $pagestart
     * @param $pagesize
     * @return array
     */
    public static function get_grade_items_raw_data($sql, $param, $pagestart, $pagesize) {
        global $DB;

        //validate parameter
        $params = self::validate_parameters(self::get_grade_items_raw_data_parameters(),
            array('sql' => $sql, 'param' => $param, 'pagestart' => $pagestart, 'pagesize' => $pagesize));

        $branch = array();
        foreach ($params['param'] as $element) {
            $branch[$element['name']] = $element['value'];
        }

        $rawdata = $DB->get_records_sql($params['sql'], $branch, $params['pagestart'], $params['pagesize']);
        return $rawdata;
    }

    /**
     * @return external_multiple_structure
     */
    public static function get_grade_items_raw_data_returns() {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'id' => new external_value(PARAM_INT, 'grade items id', VALUE_OPTIONAL),
                    'courseid' => new external_value(PARAM_INT, 'courseid', VALUE_OPTIONAL),
                    'categoryid' => new external_value(PARAM_INT, 'categoryid', VALUE_OPTIONAL),
                    'itemname' => new external_value(PARAM_RAW, 'itemname', VALUE_OPTIONAL),
                    'itemtype' => new external_value(PARAM_RAW, 'itemtype', VALUE_OPTIONAL),
                    'itemmodule' => new external_value(PARAM_RAW, 'itemmodule', VALUE_OPTIONAL),
                    'iteminstance' => new external_value(PARAM_INT, 'iteminstance', VALUE_OPTIONAL),
                    'itemnumber' => new external_value(PARAM_INT, 'itemnumber', VALUE_OPTIONAL),
                    'iteminfo' => new external_value(PARAM_RAW, 'itemmodule', VALUE_OPTIONAL),
                    'idnumber' => new external_value(PARAM_RAW, 'idnumber', VALUE_OPTIONAL),
                    'calculation' => new external_value(PARAM_RAW, 'calculation', VALUE_OPTIONAL),
                    'gradetype' => new external_value(PARAM_INT, 'gradetype', VALUE_OPTIONAL),
                    'grademax' => new external_value(PARAM_FLOAT, 'grademax', VALUE_OPTIONAL),
                    'grademin' => new external_value(PARAM_FLOAT, 'grademin', VALUE_OPTIONAL),
                    'scaleid' => new external_value(PARAM_INT, 'scaleid', VALUE_OPTIONAL),
                    'outcomeid' => new external_value(PARAM_INT, 'outcomeid', VALUE_OPTIONAL),
                    'gradepass' => new external_value(PARAM_FLOAT, 'gradepass', VALUE_OPTIONAL),
                    'multfactor' => new external_value(PARAM_FLOAT, 'multfactor', VALUE_OPTIONAL),
                    'plusfactor' => new external_value(PARAM_FLOAT, 'plusfactor', VALUE_OPTIONAL),
                    'aggregationcoef' => new external_value(PARAM_FLOAT, 'aggregationcoef', VALUE_OPTIONAL),
                    'aggregationcoef2' => new external_value(PARAM_FLOAT, 'aggregationcoef2', VALUE_OPTIONAL),
                    'sortorder' => new external_value(PARAM_INT, 'sortorder', VALUE_OPTIONAL),
                    'display' => new external_value(PARAM_INT, 'display', VALUE_OPTIONAL),
                    'decimals' => new external_value(PARAM_BOOL, 'decimals', VALUE_OPTIONAL),
                    'hidden' => new external_value(PARAM_INT, 'hidden', VALUE_OPTIONAL),
                    'locked' => new external_value(PARAM_INT, 'locked', VALUE_OPTIONAL),
                    'locktime' => new external_value(PARAM_INT, 'locktime', VALUE_OPTIONAL),
                    'needsupdate' => new external_value(PARAM_INT, 'needsupdate', VALUE_OPTIONAL),
                    'weightoverride' => new external_value(PARAM_BOOL, 'weightoverride', VALUE_OPTIONAL),
                    'timecreated' => new external_value(PARAM_INT, 'timecreated', VALUE_OPTIONAL),
                    'timemodified' => new external_value(PARAM_INT, 'timemodified', VALUE_OPTIONAL),
                )
            )
        );
    }

    /**
     * Describes the parameters for get_files_submission
     *
     * @return external_external_function_parameters
     */
    public static function get_files_submission_parameters(){
        return new external_function_parameters(
            array('submissionid' => new external_value(PARAM_INT, 'the submission id'))
        );
    }

    /**
     * Returns Object file submission.
     *
     * @param int $submissionid   -   The id of submission
     *
     * @return array of warnings and file submission information
     */
    public static function get_files_submission($submissionid){
        global $DB;

        $result = array();
        $warnings = array();
        
        // validate params
        $params = self::validate_parameters(self::get_files_submission_parameters(),
            array(
                'submissionid' => $submissionid
            )
        );

        $result['filesubmission'] = $DB->get_record('assignsubmission_file', array('submission'=>$params['submissionid']));
        if (!$result['filesubmission']){
            $result['filesubmission'] = array(); 
        }
        
        $result['warnings'] = $warnings;
        
        return $result;
    }

    /**
     * Describes the get_files_submission return value.
     *
     * @return external_single_structure
     * @since Moodle 3.1
     */
    public static function get_files_submission_returns(){
        return new external_single_structure(
            array(
                'filesubmission' => new external_single_structure(
                    array(
                        'id' => new external_value(PARAM_INT, 'file submisison id', VALUE_OPTIONAL),
                        'assignment' => new external_value(PARAM_INT, 'assignment id', VALUE_OPTIONAL),
                        'submission' => new external_value(PARAM_INT, 'submission id', VALUE_OPTIONAL),
                        'numfiles' => new external_value(PARAM_INT, 'the number of file', VALUE_OPTIONAL)
                    )
                ),
                'warnings' => new external_warnings(),
            )
        );
    }

    /**
     * Describes the parameters for create_files_submission_parameters
     *
     * @return external_external_function_parameters
     */
    public static function create_files_submission_parameters(){
        return new external_function_parameters(
            array(
                'assignment' => new external_value(PARAM_INT, 'assignment id'),
                'submission' => new external_value(PARAM_INT, 'submission id'),
                'numfiles' => new external_value(PARAM_INT, 'the number of file'),
            )
        );
    }

    /**
     * Returns id of new file submisison just created.
     *
     * @param int $assignment   -   The id of asssignment
     * @param int $submission   -   The id of submission
     * @param int $numfiles -    The number of files
     *
     * @return array of warnings and id of file submission 
     */
    public static function create_files_submission($assignment, $submission, $numfiles){

        global $DB;

        //build result
        $result = array();
        $warnings = array();

        //Validate param
        $params = self::validate_parameters(self::create_files_submission_parameters(),
            array(
                'assignment' => $assignment,
                'submission' => $submission,
                'numfiles' => $numfiles
            )
        );

        $filesubmission = (object)$params;
        $transaction = $DB->start_delegated_transaction();

        $result['fsid'] = $DB->insert_record('assignsubmission_file', $filesubmission);

        $transaction->allow_commit();

        $result['warnings'] = $warnings;

        return $result;
    }

    /**
     * Describes the create_files_submission return value.
     *
     * @return external_single_structure
     * @since Moodle 3.1
     */
    public static function create_files_submission_returns(){
        return new external_single_structure(
            array(
                'fsid' =>  new external_value(PARAM_INT, 'The id of file submission'),
                'warnings' => new external_warnings()
            )
        );
    }

    /**
     * Describes the parameters for update_files_submission
     *
     * @return external_external_function_parameters
     */
    public static function update_files_submission_parameters(){
        return new external_function_parameters(
            array(
                'id' => new external_value(PARAM_INT, 'The id of file submission'),
                'assignment' => new external_value(PARAM_INT, 'The id of assignment'),
                'submission' => new external_value(PARAM_INT, 'The id of submission'),
                'numfiles' => new external_value(PARAM_INT, 'the number of file'),
            )
        );
    }

    /**
     * Returns true if success and false if fails.
     *
     * @param int $id   -   The id of file submission
     * @param int $assignment   -   The id of asssignment
     * @param int $submission   -   The id of submission
     * @param int $numfiles -   The number of files
     *
     * @return array of warnings and bool for update
     */
    public static function update_files_submission($id, $assignment, $submission, $numfiles){

        global $DB;

        //build result
        $result = array();
        $warnings = array();

        //Validate param
        $params = self::validate_parameters(self::update_files_submission_parameters(),
            array(
                'id' => $id,
                'assignment' => $assignment,
                'submission' => $submission,
                'numfiles' => $numfiles
            )
        );

        $filesubmission = (object)$params;

        $transaction = $DB->start_delegated_transaction();

        $result['bool'] = $DB->update_record('assignsubmission_file', $filesubmission);

        $transaction->allow_commit();

        $result['warnings'] = $warnings;
        
        return $result;
    }

    /**
     * Describes the update_files_submission return value.
     *
     * @return external_single_structure
     * @since Moodle 3.1
     */
    public static function update_files_submission_returns(){
        return new external_single_structure(
            array(
                'bool' =>  new external_value(PARAM_INT, 'Check if success'),
                'warnings' => new external_warnings()
            )
        );
    }

    /**
     * @return external_function_parameters
     */
    public static function get_grade_grades_raw_data_parameters() {
        return new external_function_parameters (
            array(
                'sql' => new external_value(PARAM_RAW, 'sql'),
                'param' => new  external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'name'),
                            'value' => new external_value(PARAM_RAW, 'value'),
                        )
                    )
                ),
                'pagestart' => new external_value(PARAM_INT, 'pagestart', VALUE_DEFAULT, 0),
                'pagesize' => new external_value(PARAM_INT, 'pagesize', VALUE_DEFAULT, 0),
            )
        );
    }

    /**
     * @param $sql
     * @param $param
     * @param $pagestart
     * @param $pagesize
     * @return array
     */
    public static function get_grade_grades_raw_data($sql, $param, $pagestart, $pagesize) {
        global $DB;

        //validate parameter
        $params = self::validate_parameters(self::get_grade_grades_raw_data_parameters(),
            array('sql' => $sql, 'param' => $param, 'pagestart' => $pagestart, 'pagesize' => $pagesize));

        $branch = array();
        foreach ($params['param'] as $element) {
            $branch[$element['name']] = $element['value'];
        }

        $rawdata = $DB->get_records_sql($params['sql'], $branch, $params['pagestart'], $params['pagesize']);
        return $rawdata;
    }

    /**
     * @return external_multiple_structure
     */
    public static function get_grade_grades_raw_data_returns() {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'id' => new external_value(PARAM_INT, 'grade grades id', VALUE_OPTIONAL),
                    'itemid' => new external_value(PARAM_INT, 'itemid', VALUE_OPTIONAL),
                    'userid' => new external_value(PARAM_INT, 'userid', VALUE_OPTIONAL),
                    'rawgrade' => new external_value(PARAM_FLOAT, 'rawgrade', VALUE_OPTIONAL),
                    'rawgrademax' => new external_value(PARAM_FLOAT, 'rawgrademax', VALUE_OPTIONAL),
                    'rawgrademin' => new external_value(PARAM_FLOAT, 'rawgrademin', VALUE_OPTIONAL),
                    'rawscaleid' => new external_value(PARAM_INT, 'rawscaleid', VALUE_OPTIONAL),
                    'usermodified' => new external_value(PARAM_INT, 'usermodified', VALUE_OPTIONAL),
                    'finalgrade' => new external_value(PARAM_FLOAT, 'finalgrade', VALUE_OPTIONAL),
                    'hidden' => new external_value(PARAM_INT, 'hidden', VALUE_OPTIONAL),
                    'locked' => new external_value(PARAM_INT, 'locked', VALUE_OPTIONAL),
                    'locktime' => new external_value(PARAM_INT, 'locktime', VALUE_OPTIONAL),
                    'exported' => new external_value(PARAM_INT, 'exported', VALUE_OPTIONAL),
                    'overridden' => new external_value(PARAM_INT, 'overridden', VALUE_OPTIONAL),
                    'excluded' => new external_value(PARAM_INT, 'excluded', VALUE_OPTIONAL),
                    'feedback' => new external_value(PARAM_RAW, 'feedback', VALUE_OPTIONAL),
                    'feedbackformat' => new external_value(PARAM_INT, 'feedbackformat', VALUE_OPTIONAL),
                    'information' => new external_value(PARAM_RAW, 'information', VALUE_OPTIONAL),
                    'informationformat' => new external_value(PARAM_INT, 'informationformat', VALUE_OPTIONAL),
                    'timecreated' => new external_value(PARAM_INT, 'timecreated', VALUE_OPTIONAL),
                    'timemodified' => new external_value(PARAM_INT, 'timemodified', VALUE_OPTIONAL),
                    'aggregationstatus' => new external_value(PARAM_RAW, 'gradepaggregationstatusass', VALUE_OPTIONAL),
                    'aggregationweight' => new external_value(PARAM_FLOAT, 'aggregationweight', VALUE_OPTIONAL),
                )
            )
        );
    }

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 2.7
     */
    public static function get_grades_parameters() {
        return new external_function_parameters(
            array(
                'courseid' => new external_value(PARAM_INT, 'id of course'),
                'component' => new external_value(
                    PARAM_COMPONENT, 'A component, for example mod_forum or mod_quiz', VALUE_DEFAULT, ''),
                'activityid' => new external_value(PARAM_INT, 'The activity ID', VALUE_DEFAULT, null),
                'userids' => new external_multiple_structure(
                    new external_value(PARAM_INT, 'user ID'),
                    'An array of user IDs, leave empty to just retrieve grade item information', VALUE_DEFAULT, array()
                )
            )
        );
    }

    /**
     * Returns student course total grade and grades for activities.
     * This function does not return category or manual items.
     * This function is suitable for managers or teachers not students.
     *
     * @param  int $courseid        Course id
     * @param  string $component    Component name
     * @param  int $activityid      Activity id
     * @param  array  $userids      Array of user ids
     * @return array                Array of grades
     * @since Moodle 2.7
     */
    public static function get_grades($courseid, $component = null, $activityid = null, $userids = array()) {
        global $CFG, $USER, $DB;

        $params = self::validate_parameters(self::get_grades_parameters(),
            array('courseid' => $courseid, 'component' => $component, 'activityid' => $activityid, 'userids' => $userids));

        $gradesarray = array(
            'items'     => array(),
            'outcomes'  => array()
        );

        $coursecontext = context_course::instance($params['courseid']);

        try {
            self::validate_context($coursecontext);
        } catch (Exception $e) {
            $exceptionparam = new stdClass();
            $exceptionparam->message = $e->getMessage();
            $exceptionparam->courseid = $params['courseid'];
            throw new moodle_exception('errorcoursecontextnotvalid' , 'webservice', '', $exceptionparam);
        }

        require_capability('moodle/grade:viewhidden', $coursecontext);

        $course = $DB->get_record('course', array('id' => $params['courseid']), '*', MUST_EXIST);

        $access = false;
        if (has_capability('moodle/grade:viewall', $coursecontext)) {
            // Can view all user's grades in this course.
            $access = true;

        } else if ($course->showgrades && count($params['userids']) == 1) {
            // Course showgrades == students/parents can access grades.

            if ($params['userids'][0] == $USER->id and has_capability('moodle/grade:view', $coursecontext)) {
                // Student can view their own grades in this course.
                $access = true;

            } else if (has_capability('moodle/grade:viewall', context_user::instance($params['userids'][0]))) {
                // User can view the grades of this user. Parent most probably.
                $access = true;
            }
        }

        if (!$access) {
            throw new moodle_exception('nopermissiontoviewgrades', 'error');
        }

        $itemtype = null;
        $itemmodule = null;
        $iteminstance = null;

        if (!empty($params['component'])) {
            list($itemtype, $itemmodule) = normalize_component($params['component']);
        }

        $cm = null;
        if (!empty($itemmodule) && !empty($params['activityid'])) {
            if (!$cm = get_coursemodule_from_id($itemmodule, $params['activityid'])) {
                throw new moodle_exception('invalidcoursemodule');
            }
            $iteminstance = $cm->instance;
        }

        // Load all the module info.
        $modinfo = get_fast_modinfo($params['courseid']);
        $activityinstances = $modinfo->get_instances();

        $gradeparams = array('courseid' => $params['courseid']);
        if (!empty($itemtype)) {
            $gradeparams['itemtype'] = $itemtype;
        }
        if (!empty($itemmodule)) {
            $gradeparams['itemmodule'] = $itemmodule;
        }
        if (!empty($iteminstance)) {
            $gradeparams['iteminstance'] = $iteminstance;
        }

        if ($activitygrades = grade_item::fetch_all($gradeparams)) {
            $canviewhidden = has_capability('moodle/grade:viewhidden', context_course::instance($params['courseid']));

            foreach ($activitygrades as $activitygrade) {

                if ($activitygrade->itemtype != 'course' and $activitygrade->itemtype != 'mod') {
                    // This function currently only supports course and mod grade items. Manual and category not supported.
                    continue;
                }

                $context = $coursecontext;

                if ($activitygrade->itemtype == 'course') {
                    $item = grade_get_course_grades($course->id, $params['userids']);
                    $item->itemnumber = 0;

                    $grades = new stdClass;
                    $grades->items = array($item);
                    $grades->outcomes = array();

                } else {
                    $cm = $activityinstances[$activitygrade->itemmodule][$activitygrade->iteminstance];
                    $instance = $cm->instance;
                    $context = context_module::instance($cm->id, IGNORE_MISSING);

                    $grades = grade_get_grades($params['courseid'], $activitygrade->itemtype,
                        $activitygrade->itemmodule, $instance, $params['userids']);
                }

                // Convert from objects to arrays so all web service clients are supported.
                // While we're doing that we also remove grades the current user can't see due to hiding.
                foreach ($grades->items as $gradeitem) {
                    // Switch the stdClass instance for a grade item instance so we can call is_hidden() and use the ID.
                    $gradeiteminstance = self::get_grade_item(
                        $course->id, $activitygrade->itemtype, $activitygrade->itemmodule, $activitygrade->iteminstance, 0);
                    if (!$canviewhidden && $gradeiteminstance->is_hidden()) {
                        continue;
                    }

                    // Format mixed bool/integer parameters.
                    $gradeitem->hidden = (empty($gradeitem->hidden)) ? 0 : $gradeitem->hidden;
                    $gradeitem->locked = (empty($gradeitem->locked)) ? 0 : $gradeitem->locked;

                    $gradeitemarray = (array)$gradeitem;
                    $gradeitemarray['grades'] = array();

                    if (!empty($gradeitem->grades)) {
                        foreach ($gradeitem->grades as $studentid => $studentgrade) {
                            if (!$canviewhidden) {
                                // Need to load the grade_grade object to check visibility.
                                $gradegradeinstance = grade_grade::fetch(
                                    array(
                                        'userid' => $studentid,
                                        'itemid' => $gradeiteminstance->id
                                    )
                                );
                                // The grade grade may be legitimately missing if the student has no grade.
                                if (!empty($gradegradeinstance) && $gradegradeinstance->is_hidden()) {
                                    continue;
                                }
                            }
                            $user = $DB->get_record('user', array('id' => $studentid));
                            // Format mixed bool/integer parameters.
                            $studentgrade->useremail = $user->email;
                            $studentgrade->hidden = (empty($studentgrade->hidden)) ? 0 : $studentgrade->hidden;
                            $studentgrade->locked = (empty($studentgrade->locked)) ? 0 : $studentgrade->locked;
                            $studentgrade->overridden = (empty($studentgrade->overridden)) ? 0 : $studentgrade->overridden;

                            if ($gradeiteminstance->itemtype != 'course' and !empty($studentgrade->feedback)) {
                                list($studentgrade->feedback, $studentgrade->feedbackformat) =
                                    external_format_text($studentgrade->feedback, $studentgrade->feedbackformat,
                                        $context->id, $params['component'], 'feedback', null);
                            }

                            $gradeitemarray['grades'][$studentid] = (array)$studentgrade;
                            // Add the student ID as some WS clients can't access the array key.
                            $gradeitemarray['grades'][$studentid]['userid'] = $studentid;
                        }
                    }

                    if ($gradeiteminstance->itemtype == 'course') {
                        $gradesarray['items']['course'] = $gradeitemarray;
                        $gradesarray['items']['course']['activityid'] = 'course';
                    } else {
                        $gradesarray['items'][$cm->id] = $gradeitemarray;
                        // Add the activity ID as some WS clients can't access the array key.
                        $gradesarray['items'][$cm->id]['activityid'] = $cm->id;
                    }
                }

                foreach ($grades->outcomes as $outcome) {
                    // Format mixed bool/integer parameters.
                    $outcome->hidden = (empty($outcome->hidden)) ? 0 : $outcome->hidden;
                    $outcome->locked = (empty($outcome->locked)) ? 0 : $outcome->locked;

                    $gradesarray['outcomes'][$cm->id] = (array)$outcome;
                    $gradesarray['outcomes'][$cm->id]['activityid'] = $cm->id;

                    $gradesarray['outcomes'][$cm->id]['grades'] = array();
                    if (!empty($outcome->grades)) {
                        foreach ($outcome->grades as $studentid => $studentgrade) {
                            if (!$canviewhidden) {
                                // Need to load the grade_grade object to check visibility.
                                $gradeiteminstance = self::get_grade_item($course->id, $activitygrade->itemtype,
                                    $activitygrade->itemmodule, $activitygrade->iteminstance,
                                    $activitygrade->itemnumber);
                                $gradegradeinstance = grade_grade::fetch(
                                    array(
                                        'userid' => $studentid,
                                        'itemid' => $gradeiteminstance->id
                                    )
                                );
                                // The grade grade may be legitimately missing if the student has no grade.
                                if (!empty($gradegradeinstance ) && $gradegradeinstance->is_hidden()) {
                                    continue;
                                }
                            }

                            // Format mixed bool/integer parameters.
                            $studentgrade->hidden = (empty($studentgrade->hidden)) ? 0 : $studentgrade->hidden;
                            $studentgrade->locked = (empty($studentgrade->locked)) ? 0 : $studentgrade->locked;

                            if (!empty($studentgrade->feedback)) {
                                list($studentgrade->feedback, $studentgrade->feedbackformat) =
                                    external_format_text($studentgrade->feedback, $studentgrade->feedbackformat,
                                        $context->id, $params['component'], 'feedback', null);
                            }

                            $gradesarray['outcomes'][$cm->id]['grades'][$studentid] = (array)$studentgrade;

                            // Add the student ID into the grade structure as some WS clients can't access the key.
                            $gradesarray['outcomes'][$cm->id]['grades'][$studentid]['userid'] = $studentid;
                        }
                    }
                }
            }
        }

        return $gradesarray;
    }

    /**
     * Get a grade item
     * @param  int $courseid        Course id
     * @param  string $itemtype     Item type
     * @param  string $itemmodule   Item module
     * @param  int $iteminstance    Item instance
     * @param  int $itemnumber      Item number
     * @return grade_item           A grade_item instance
     */
    private static function get_grade_item($courseid, $itemtype, $itemmodule = null, $iteminstance = null, $itemnumber = null) {
        $gradeiteminstance = null;
        if ($itemtype == 'course') {
            $gradeiteminstance = grade_item::fetch(array('courseid' => $courseid, 'itemtype' => $itemtype));
        } else {
            $gradeiteminstance = grade_item::fetch(
                array('courseid' => $courseid, 'itemtype' => $itemtype,
                    'itemmodule' => $itemmodule, 'iteminstance' => $iteminstance, 'itemnumber' => $itemnumber));
        }
        return $gradeiteminstance;
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     * @since Moodle 2.7
     */
    public static function get_grades_returns() {
        return new external_single_structure(
            array(
                'items'  => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'activityid' => new external_value(
                                PARAM_ALPHANUM, 'The ID of the activity or "course" for the course grade item'),
                            'itemnumber'  => new external_value(PARAM_INT, 'Will be 0 unless the module has multiple grades'),
                            'scaleid' => new external_value(PARAM_INT, 'The ID of the custom scale or 0'),
                            'name' => new external_value(PARAM_RAW, 'The module name'),
                            'grademin' => new external_value(PARAM_FLOAT, 'Minimum grade'),
                            'grademax' => new external_value(PARAM_FLOAT, 'Maximum grade'),
                            'gradepass' => new external_value(PARAM_FLOAT, 'The passing grade threshold'),
                            'locked' => new external_value(PARAM_INT, '0 means not locked, > 1 is a date to lock until'),
                            'hidden' => new external_value(PARAM_INT, '0 means not hidden, > 1 is a date to hide until'),
                            'grades' => new external_multiple_structure(
                                new external_single_structure(
                                    array(
                                        'userid' => new external_value(
                                            PARAM_INT, 'Student ID'),
                                        'useremail' => new external_value(
                                            PARAM_RAW, 'Student Email'),
                                        'grade' => new external_value(
                                            PARAM_FLOAT, 'Student grade'),
                                        'locked' => new external_value(
                                            PARAM_INT, '0 means not locked, > 1 is a date to lock until'),
                                        'hidden' => new external_value(
                                            PARAM_INT, '0 means not hidden, 1 hidden, > 1 is a date to hide until'),
                                        'overridden' => new external_value(
                                            PARAM_INT, '0 means not overridden, > 1 means overridden'),
                                        'feedback' => new external_value(
                                            PARAM_RAW, 'Feedback from the grader'),
                                        'feedbackformat' => new external_value(
                                            PARAM_INT, 'The format of the feedback'),
                                        'usermodified' => new external_value(
                                            PARAM_INT, 'The ID of the last user to modify this student grade'),
                                        'datesubmitted' => new external_value(
                                            PARAM_INT, 'A timestamp indicating when the student submitted the activity'),
                                        'dategraded' => new external_value(
                                            PARAM_INT, 'A timestamp indicating when the assignment was grades'),
                                        'str_grade' => new external_value(
                                            PARAM_RAW, 'A string representation of the grade'),
                                        'str_long_grade' => new external_value(
                                            PARAM_RAW, 'A nicely formatted string representation of the grade'),
                                        'str_feedback' => new external_value(
                                            PARAM_RAW, 'A formatted string representation of the feedback from the grader'),
                                    )
                                )
                            ),
                        )
                    )
                ),
                'outcomes'  => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'activityid' => new external_value(
                                PARAM_ALPHANUM, 'The ID of the activity or "course" for the course grade item'),
                            'itemnumber'  => new external_value(PARAM_INT, 'Will be 0 unless the module has multiple grades'),
                            'scaleid' => new external_value(PARAM_INT, 'The ID of the custom scale or 0'),
                            'name' => new external_value(PARAM_RAW, 'The module name'),
                            'locked' => new external_value(PARAM_INT, '0 means not locked, > 1 is a date to lock until'),
                            'hidden' => new external_value(PARAM_INT, '0 means not hidden, > 1 is a date to hide until'),
                            'grades' => new external_multiple_structure(
                                new external_single_structure(
                                    array(
                                        'userid' => new external_value(
                                            PARAM_INT, 'Student ID'),
                                        'grade' => new external_value(
                                            PARAM_FLOAT, 'Student grade'),
                                        'locked' => new external_value(
                                            PARAM_INT, '0 means not locked, > 1 is a date to lock until'),
                                        'hidden' => new external_value(
                                            PARAM_INT, '0 means not hidden, 1 hidden, > 1 is a date to hide until'),
                                        'feedback' => new external_value(
                                            PARAM_RAW, 'Feedback from the grader'),
                                        'feedbackformat' => new external_value(
                                            PARAM_INT, 'The feedback format'),
                                        'usermodified' => new external_value(
                                            PARAM_INT, 'The ID of the last user to modify this student grade'),
                                        'str_grade' => new external_value(
                                            PARAM_RAW, 'A string representation of the grade'),
                                        'str_feedback' => new external_value(
                                            PARAM_RAW, 'A formatted string representation of the feedback from the grader'),
                                    )
                                )
                            ),
                        )
                    ), 'An array of outcomes associated with the grade items', VALUE_OPTIONAL
                )
            )
        );

    }

    /**
     * Describes the count_remote_grade_grades_by_itemid parameters
     *
     * @return external_external_function_parameters
     */
    public static function count_remote_grade_grades_by_itemid_parameters(){
        return new external_function_parameters(
            array(
                'gradeitemid' => new external_value(PARAM_INT, 'The id of grade item'),
                'hostip' => new external_value(PARAM_TEXT, 'The ip address on host')
            )
        );
    }

    /**
     * Count record mdl_grade_grades by itemid to check any existing grades
     *
     * @param int $gradeitemid  -  The id of grade item
     * @param int $hostip       -  The ip address of host
     *
     * @return array of count number record and warnings
     * @throws invalid_parameter_exception
     */
    public static function count_remote_grade_grades_by_itemid($gradeitemid, $hostip){
        global $DB;

        $result = array();

        $warnings = array();

        // validate params
        $params = self::validate_parameters(self::count_remote_grade_grades_by_itemid_parameters(),
            array(
                'gradeitemid' => $gradeitemid,
                'hostip' => $hostip
            )
        );

        $result['count'] = $DB->count_records_sql('SELECT COUNT(gg.finalgrade) FROM {grade_grades} gg 
                                                LEFT JOIN {user} u
                                                ON gg.userid = u.id
                                                JOIN {mnet_host} mh
                                                ON u.mnethostid = mh.id
                                                AND mh.ip_address = :hostip
                                                WHERE gg.itemid = :gradeitemid
                                                AND gg.finalgrade IS NOT NULL',
                                                    array( 
                                                        'hostip' => $params['hostip'], 
                                                        'gradeitemid' => $params['gradeitemid']));
                                                    
        
        $result['warnings'] = $warnings;

        return $result;
    }

    /**
     * Describes the count_remote_grade_grades_by_itemid returns value.
     *
     * @return external_single_structure
     * @since Moodle 3.1
     */
    public static function count_remote_grade_grades_by_itemid_returns(){
        return new external_single_structure(
            array(
                'count' => new external_value(PARAM_INT, 'Count record table grade grades'), 
                'warnings' => new external_warnings(),
            )
        );
    }

    /**
     * Describes the get_all_grade_by_userid_courseid parameters
     *
     * @return external_external_function_parameters
     */
    public static function get_all_grade_by_userid_courseid_parameters(){
        return new external_function_parameters(
            array(
                'gradeitemid' => new external_value(PARAM_INT, 'The id of grade item'),
                'grabthelot' => new external_value(PARAM_BOOL, 'If true, grabs all scores for current user on 
                                        this course, so that later ones come from cache'),
                'userid' => new external_value(PARAM_INT, 'The id of user'),
                'courseid' => new external_value(PARAM_INT, 'The id of course')
            )
        );
    }

    /**
     * Retrieve all grades for the current course of just get current grade
     *
     * @param int $gradeitemid     - The id of grade item
     * @param bool $grabthelot     - If true, grabs all scores for current user on
     *   this course, so that later ones come from cache
     * @param int $userid          - The id of user on host
     * @param int $courseid        - The id of course on host
     *
     * @return mixes
     * @throws invalid_parameter_exception
     */
    public static function get_all_grade_by_userid_courseid($gradeitemid, $grabthelot, $userid, $courseid){
        global $DB;

        $result = array();

        $warnings = array();

        // validate params
        $params = self::validate_parameters(self::get_all_grade_by_userid_courseid_parameters(),
            array(
                'gradeitemid' => $gradeitemid,
                'grabthelot' => $grabthelot,
                'userid' => $userid,
                'courseid' => $courseid
            )
        );

        if ($params['grabthelot']) {
            $result['hasgrab'] = $DB->get_records_sql('
                        SELECT
                            gi.id,gg.finalgrade,gg.rawgrademin,gg.rawgrademax
                        FROM
                            {grade_items} gi
                            LEFT JOIN {grade_grades} gg ON gi.id=gg.itemid AND gg.userid=?
                        WHERE
                            gi.courseid = ?', array($params['userid'], $params['courseid']));

            if (!$result['hasgrab']) {
                return $result['hasgrab'] = array();
            }
        } else {
            $result['notgrab'] = $DB->get_record('grade_grades', array(
                'userid' => $params['userid'], 'itemid' => $params['gradeitemid']));
            if (!$result['notgrab']) {
                return $result['notgrab'] = array();
            }
        }

        $result['warnings'] = $warnings;

        return $result;
    }

    /**
     * Describes the get_all_grade_by_userid_courseid returns value.
     *
     * @return external_single_structure
     * @since Moodle 3.1
     */
    public static function get_all_grade_by_userid_courseid_returns(){
        return new external_single_structure(
            array(
                'hasgrab' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'id' => new external_value(PARAM_INT, 'grade grades id', VALUE_OPTIONAL),
                            'finalgrade' => new external_value(PARAM_FLOAT, 'finalgrade', VALUE_OPTIONAL),
                            'rawgrademax' => new external_value(PARAM_FLOAT, 'rawgrademax', VALUE_OPTIONAL),
                            'rawgrademin' => new external_value(PARAM_FLOAT, 'rawgrademin', VALUE_OPTIONAL),
                        )
                    ), 'grades grade info', VALUE_OPTIONAL
                ),
                'notgrab' => self::get_mod_assign_grade_grades_structure(VALUE_OPTIONAL),
                'warnings' => new external_warnings(),
            )
        );
    }

    /**
     * Creates a grade_grades structure.
     *
     * @return external_single_structure the grade_grades structure
     */
    private static function get_mod_assign_grade_grades_structure($required = VALUE_REQUIRED) {
        return new external_single_structure(
            array(
                'id' => new external_value(PARAM_INT, 'grade grades id', VALUE_OPTIONAL),
                'itemid' => new external_value(PARAM_INT, 'itemid', VALUE_OPTIONAL),
                'userid' => new external_value(PARAM_INT, 'userid', VALUE_OPTIONAL),
                'rawgrade' => new external_value(PARAM_FLOAT, 'rawgrade', VALUE_OPTIONAL),
                'rawgrademax' => new external_value(PARAM_FLOAT, 'rawgrademax', VALUE_OPTIONAL),
                'rawgrademin' => new external_value(PARAM_FLOAT, 'rawgrademin', VALUE_OPTIONAL),
                'rawscaleid' => new external_value(PARAM_INT, 'rawscaleid', VALUE_OPTIONAL),
                'usermodified' => new external_value(PARAM_INT, 'usermodified', VALUE_OPTIONAL),
                'finalgrade' => new external_value(PARAM_FLOAT, 'finalgrade', VALUE_OPTIONAL),
                'hidden' => new external_value(PARAM_INT, 'hidden', VALUE_OPTIONAL),
                'locked' => new external_value(PARAM_INT, 'locked', VALUE_OPTIONAL),
                'locktime' => new external_value(PARAM_INT, 'locktime', VALUE_OPTIONAL),
                'exported' => new external_value(PARAM_INT, 'exported', VALUE_OPTIONAL),
                'overridden' => new external_value(PARAM_INT, 'overridden', VALUE_OPTIONAL),
                'excluded' => new external_value(PARAM_INT, 'excluded', VALUE_OPTIONAL),
                'feedback' => new external_value(PARAM_RAW, 'feedback', VALUE_OPTIONAL),
                'feedbackformat' => new external_value(PARAM_INT, 'feedbackformat', VALUE_OPTIONAL),
                'information' => new external_value(PARAM_RAW, 'information', VALUE_OPTIONAL),
                'informationformat' => new external_value(PARAM_INT, 'informationformat', VALUE_OPTIONAL),
                'timecreated' => new external_value(PARAM_INT, 'timecreated', VALUE_OPTIONAL),
                'timemodified' => new external_value(PARAM_INT, 'timemodified', VALUE_OPTIONAL),
                'aggregationstatus' => new external_value(PARAM_RAW, 'gradepaggregationstatusass', VALUE_OPTIONAL),
                'aggregationweight' => new external_value(PARAM_FLOAT, 'aggregationweight', VALUE_OPTIONAL),
            ), 'grades grade info', $required
        );
    }

}
