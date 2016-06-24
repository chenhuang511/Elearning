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

        return $DB->get_record('assign', array('id' => $params['assignid']), '*', MUST_EXIST);
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
				'grade' => new external_value(PARAM_INT, 'grade'),
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
     * validate params
     * @return external_function_parameters
     */
    public static function get_submissions_by_host_ip_parameters() {
        return new external_function_parameters(
            array(
                'assignmentids' => new external_multiple_structure(
                    new external_value(PARAM_INT, 'assignment id'),
                    '1 or more assignment ids',
                    VALUE_REQUIRED),
                'ip' => new external_value(PARAM_HOST, 'host ip', VALUE_REQUIRED),
                'status' => new external_value(PARAM_ALPHA, 'status', VALUE_DEFAULT, ''),
                'since' => new external_value(PARAM_INT, 'submitted since', VALUE_DEFAULT, 0),
                'before' => new external_value(PARAM_INT, 'submitted before', VALUE_DEFAULT, 0)
            )
        );
    }

    /**
     * get submissions by assignment ids and ip
     * @param $assignmentids
     * @param $ip
     * @param string $status
     * @param int $since
     * @param int $before
     * @return array
     * @throws invalid_parameter_exception
     */
    public static function get_submissions_by_host_ip($assignmentids, $ip, $status = '', $since = 0, $before = 0) {
        global $DB, $CFG;

        $params = self::validate_parameters(self::get_submissions_by_host_ip_parameters(),
            array('assignmentids' => $assignmentids,
                'ip' => $ip,
                'status' => $status,
                'since' => $since,
                'before' => $before));

        $warnings = array();
        $assignments = array();

        // Check the user is allowed to get the submissions for the assignments requested.
        $placeholders = array();
        list($inorequalsql, $placeholders) = $DB->get_in_or_equal($params['assignmentids'], SQL_PARAMS_NAMED);
        $sql = "SELECT cm.id, cm.instance FROM {course_modules} cm JOIN {modules} md ON md.id = cm.module ".
            "WHERE md.name = :modname AND cm.instance ".$inorequalsql;
        $placeholders['modname'] = 'assign';
        $cms = $DB->get_records_sql($sql, $placeholders);
        $assigns = array();
        foreach ($cms as $cm) {
            try {
                $context = context_module::instance($cm->id);
                self::validate_context($context);
                require_capability('mod/assign:grade', $context);
                $assign = new assign($context, null, null);
                $assigns[] = $assign;
            } catch (Exception $e) {
                $warnings[] = array(
                    'item' => 'assignment',
                    'itemid' => $cm->instance,
                    'warningcode' => '1',
                    'message' => 'No access rights in module context'
                );
            }
        }

        foreach ($assigns as $assign) {
            $submissions = array();
            $placeholders = array('assignid1' => $assign->get_instance()->id,
                'assignid2' => $assign->get_instance()->id,
                'ip' => $params['ip'],
            );

            $submissionmaxattempt = 'SELECT mxs.userid, MAX(mxs.attemptnumber) AS maxattempt
                                     FROM {assign_submission} mxs
                                     WHERE mxs.assignment = :assignid1 GROUP BY mxs.userid';

            $sql = "SELECT mas.id, mas.assignment,mas.userid,".
                "mas.timecreated,mas.timemodified,mas.status,mas.groupid,mas.attemptnumber ".
                "FROM {assign_submission} mas ".
                "JOIN ( " . $submissionmaxattempt . " ) smx ON mas.userid = smx.userid ".
                "WHERE mas.assignment = :assignid2 AND mas.attemptnumber = smx.maxattempt".
                " AND mas.userid in ( SELECT u.id FROM {user} u ".
                "JOIN {mnet_host} h ON u.mnethostid = h.id WHERE h.ip_address = :ip ) ";

            if (!empty($params['status'])) {
                $placeholders['status'] = $params['status'];
                $sql = $sql." AND mas.status = :status";
            }
            if (!empty($params['before'])) {
                $placeholders['since'] = $params['since'];
                $placeholders['before'] = $params['before'];
                $sql = $sql." AND mas.timemodified BETWEEN :since AND :before";
            } else {
                $placeholders['since'] = $params['since'];
                $sql = $sql." AND mas.timemodified >= :since";
            }

            $submissionrecords = $DB->get_records_sql($sql, $placeholders);

            if (!empty($submissionrecords)) {
                $submissionplugins = $assign->get_submission_plugins();
                foreach ($submissionrecords as $submissionrecord) {
                    $submission = array(
                        'id' => $submissionrecord->id,
                        'userid' => $submissionrecord->userid,
                        'timecreated' => $submissionrecord->timecreated,
                        'timemodified' => $submissionrecord->timemodified,
                        'status' => $submissionrecord->status,
                        'attemptnumber' => $submissionrecord->attemptnumber,
                        'groupid' => $submissionrecord->groupid,
                        'plugins' => self::get_plugins_data($assign, $submissionplugins, $submissionrecord)
                    );
                    $submissions[] = $submission;
                }
            } else {
                $warnings[] = array(
                    'item' => 'module',
                    'itemid' => $assign->get_instance()->id,
                    'warningcode' => '3',
                    'message' => 'No submissions found'
                );
            }

            $assignments[] = array(
                'assignmentid' => $assign->get_instance()->id,
                'submissions' => $submissions
            );

        }

        $result = array(
            'assignments' => $assignments,
            'warnings' => $warnings
        );
        return $result;
    }

    /**
     * return value
     * @return external_single_structure
     */
    public static function get_submissions_by_host_ip_returns() {
        return mod_assign_external::get_submissions_returns();
    }

    // MINHND
    // Get Onlinetext submission 
    public static function get_onlinetext_submission_parameters(){
        return new external_function_parameters(
            array('submissionid' => new external_value(PARAM_INT, 'the submission id'))
        );
    }

    public static function get_onlinetext_submission($submissionid){
        global $DB;

        // validate params
        $params = self::validate_parameters(self::get_onlinetext_submission_parameters(),
            array(
                'submissionid' => $submissionid
            )
        );
        return $DB->get_record('assignsubmission_onlinetext', array('submission'=>$params['submissionid']));
    }

    public static function get_onlinetext_submission_returns(){
        return new external_single_structure(
            array(
                'assignment' => new external_value(PARAM_INT, 'assignment id'),
                'submission' => new external_value(PARAM_INT, 'submission id'),
                'onlinetext' => new external_value(PARAM_RAW, 'online text'),
                'onlineformat' => new external_value(PARAM_INT, 'online text format'),
            ));
    }
    
    //MINHND 18/6/2016
    public static function get_assign_plugin_config_parameters(){
        return new external_function_parameters(
            array(
                'assignment' => new external_value(PARAM_INT, 'assignment id'),
                'subtype' => new external_value(PARAM_RAW, 'sub type'),
                'plugin' => new external_value(PARAM_RAW, 'plugin'),
                'name' => new external_value(PARAM_RAW, 'name')
            )
        );
    }
    
    public static function get_assign_plugin_config($assignment, $subtype, $plugin, $name){
        global $DB;
        
        //Validate param
        $params = self::validate_parameters(self::get_assign_plugin_config_parameters(),
            array(
                'assignment' => $assignment,
                'subtype' => $subtype,
                'plugin' => $plugin,
                'name' => $name
            )
        );
        
        $result = $DB->get_record('assign_plugin_config', $params, '*', IGNORE_MISSING);
        if ($result->value)
            return $result->value;
        return 0;
    }
    
    public static function get_assign_plugin_config_returns(){
        return new external_value(PARAM_INT, 'value assign plugin');
    }

    // MINHND: Get comment status
    public static function get_comment_status_parameters(){
        return new external_function_parameters(
            array(
                'itemid' => new external_value(PARAM_INT, 'item ID'),
                'commentarea' => new external_value(PARAM_RAW, 'comment area'),
                'component' => new external_value(PARAM_RAW, 'component'),
                'instanceid' => new external_value(PARAM_RAW, 'instance ID'),
                'courseid' => new external_value(PARAM_RAW, 'course ID')
            )
        );
    }

    public static function get_comment_status($itemid, $commentarea, $component, $instanceid, $cousreid){
        global $CFG;

        require_once($CFG->dirroot . '/comment/lib.php');
        
        $warnings = array();

        // Now, build the result.
        $result = array();

        //Validate param
        $params = self::validate_parameters(self::get_comment_status_parameters(),
            array(
                'itemid' => $itemid,
                'commentarea' => $commentarea,
                'component' => $component,
                'instanceid' => $instanceid,
                'courseid' => $cousreid
            )
        );

        $context = context_module::instance($params['instanceid']);

        $options = new stdClass();
        
        $options->area    = $params['commentarea'];
        $options->courseid    = $params['courseid'];
        $options->context = $context;
        $options->itemid  = $params['itemid'];
        $options->component = $params['component'];
        $options->showcount = true;
        $options->displaycancel = true;

        $comment = new comment($options);

        $result['countcomment'] = $comment->count();

        $result['getcomment'] = $comment->get_comments(0);

        $result['warnings'] = $warnings;

        return $result;
    }

    public static function get_comment_status_returns(){
        return new external_single_structure(
            array(
                'countcomment' => new external_value(PARAM_INT, 'count total comment', VALUE_OPTIONAL),
                'getcomment' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'id' => new external_value(PARAM_INT, 'comment ID'),
                            'content' => new external_value(PARAM_RAW, 'content comment'),
                            'format' => new external_value(PARAM_INT, 'format content'),
                            'timecreated' => new external_value(PARAM_INT, 'time created'),
                            'strftimeformat' => new external_value(PARAM_RAW, 'time format'),
                            'fullname' => new external_value(PARAM_RAW, 'full name.'),
                            'time' => new external_value(PARAM_RAW, 'date time'),
                            'delete' => new external_value(PARAM_INT, 'can detele'),
                        )
                    ), 'List comments by the user.', VALUE_OPTIONAL
                ),
                'warnings' => new external_warnings(),
            )
        );
    }

    // MINHND: Get comment status
    public static function get_count_file_submission_parameters(){
        return new external_function_parameters(
            array(
                'instanceid' => new external_value(PARAM_INT, 'instance ID'),
                'submissionid' => new external_value(PARAM_INT, 'submission ID'),
                'area' => new external_value(PARAM_RAW, 'Area'),
            )
        );
    }
    
    public static function get_count_file_submission($instanceid, $submissionid, $area){

        $warnings = array();

        // Now, build the result.
        $result = array();

        //Validate param
        $params = self::validate_parameters(self::get_count_file_submission_parameters(),
            array(
                'instanceid' => $instanceid,
                'submissionid' => $submissionid,
                'area' => $area
            )
        );
        
        $fs = get_file_storage();
        $context = context_module::instance($params['instanceid']);
        
        $files = $fs->get_area_files($context->id,
            'assignsubmission_file',
            $params['area'],
            $params['submissionid'],
            'id',
            false);

        $result['countfile'] = count($files);

        $result['warnings'] = $warnings;
        
        return $result;
    }

    public static function get_count_file_submission_returns(){
        return new external_single_structure(
            array(
                'countfile' => new external_value(PARAM_INT, 'count file', VALUE_OPTIONAL),
                'warnings' => new external_warnings()
            )
        );
    }

    // Get content File submission
    public static function get_content_html_submission_parameters(){
        return new external_function_parameters(
            array(
                'assignid' => new external_value(PARAM_INT, 'asssign ID'),
                'userid' => new external_value(PARAM_INT, 'user ID', VALUE_DEFAULT, 0),
            )
        );
    }

    public static function get_content_html_submission($assignid, $userid){
        global $USER, $DB;
        
        $warnings = array();
        
        // Now, build the result.
        $result = array();

        //Validate param
        $params = self::validate_parameters(self::get_content_html_submission_parameters(),
            array(
                'assignid' => $assignid,
                'userid' => $userid,
            )
        );

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

        $lastattempt = $feedback = $previousattempts = null;

        // Retrieve the rest of the renderable objects.
        if (has_capability('mod/assign:submit', $assign->get_context(), $user)) {
            $lastattempt = $assign->get_assign_submission_status_renderable($user, true);
        }

        $feedback = $assign->get_assign_feedback_status_renderable($user);

        $previousattempts = $assign->get_assign_attempt_history_renderable($user);
        
        if ($lastattempt) {

            $submissionplugins = $assign->get_submission_plugins();
            $showviewlink = false;

            $summary = $submissionplugins[1]->view_summary($lastattempt->submission, $showviewlink);

            $result['viewsummary'] = $summary;
            if($showviewlink){
                $result['view'] = $submissionplugins[1]->plugin->view_summary($lastattempt->submission);
            }
            $result['view'] = null;
        }

        if($feedback){
            $result['feedback'] = $assign->get_renderer()->render($feedback);
        }
        else
            $result['feedback'] = null;

        if($previousattempts and count($previousattempts->submissions) > 1){
            $result['history'] = $assign->get_renderer()->render($previousattempts);
        }
        else
            $result['history'] = null;
        
        $result['warnings'] = $warnings;

        return $result;
    }
    
    public static function get_content_html_submission_returns(){
        return new external_single_structure(
            array(
                'viewsummary' => new external_value(PARAM_RAW, 'HTML View summary submission'),
                'view' => new external_value(PARAM_RAW, 'HTML View submission'),
                'feedback' => new external_value(PARAM_RAW, 'HTML feedback submission'),
                'history' => new external_value(PARAM_RAW, 'HTML previous submission'),
                'warnings' => new external_warnings()
            )
        );
    }

    // MINHD: Count submissions with status by host id
    public static function count_submissions_with_status_by_host_id_parameters(){
        return new external_function_parameters(
            array(
                'assignid' => new external_value(PARAM_INT, 'asssign ID'),
                'hostip' => new external_value(PARAM_RAW, 'host ip', VALUE_REQUIRED),
                'status' => new external_value(PARAM_RAW, 'status'),
            )
        );
    }

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
        $assign = $DB->get_record('assign', array('id' => $params['assignid']), 'id', MUST_EXIST);
        list($course, $cm) = get_course_and_cm_from_instance($assign, 'assign');

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

        if ($assign->get_instance()->teamsubmission) {

            $groupsstr = '';
            if ($currentgroup != 0) {
                // If there is an active group we should only display the current group users groups.
                $participants = $assign->list_participants($currentgroup, true);
                $groups = groups_get_all_groups($assign->get_course()->id,
                    array_keys($participants),
                    $assign->get_instance()->teamsubmissiongroupingid,
                    'DISTINCT g.id, g.name');
                list($groupssql, $groupsparams) = $DB->get_in_or_equal(array_keys($groups), SQL_PARAMS_NAMED);
                $groupsstr = 's.groupid ' . $groupssql . ' AND';
                $params = $params + $groupsparams;
            }
            $sql = 'SELECT COUNT(s.groupid)
                        FROM {assign_submission} s
                        WHERE
                            s.latest = 1 AND
                            s.assignment = :assignid AND
                            s.timemodified IS NOT NULL AND
                            s.userid = :groupuserid AND '
                . $groupsstr . '
                            s.status = :submissionstatus';
            $params['groupuserid'] = 0;
        } else {
            $sql = 'SELECT COUNT(s.userid)
                        FROM {assign_submission} s
                        JOIN(' . $esql . 'AND eu1_u.mnethostid = :mnethostid ) e ON e.id = s.userid
                        WHERE
                            s.latest = 1 AND
                            s.assignment = :assignid AND
                            s.timemodified IS NOT NULL AND
                            s.status = :submissionstatus';
        }
        return $DB->count_records_sql($sql, $dbparams);
    }

    public static function count_submissions_with_status_by_host_id_returns(){
        return new external_value(PARAM_INT, 'count submission with status by host id');
    }
    // MINHD: Count submissions need grading by host id
    public static function count_submissions_need_grading_by_host_id_parameters(){
        return new external_function_parameters(
            array(
                'assignid' => new external_value(PARAM_INT, 'asssign ID'),
                'hostip' => new external_value(PARAM_RAW, 'host ip', VALUE_REQUIRED),
            )
        );
    }

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
        $assign = $DB->get_record('assign', array('id' => $params['assignid']), 'id', MUST_EXIST);
        list($course, $cm) = get_course_and_cm_from_instance($assign, 'assign');

        $mnethostid =  $DB->get_record('mnet_host', array('ip_address' => $params['hostip']), 'id', MUST_EXIST);

        $context = context_module::instance($cm->id);
        self::validate_context($context);

        $assign = new assign($context, $cm, $course);


        if ($assign->get_instance()->teamsubmission) {
            // This does not make sense for group assignment because the submission is shared.
            return 0;
        }
        
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

    public static function count_submissions_need_grading_by_host_id_returns(){
        return new external_value(PARAM_INT, 'count submission need grading by host id');
    }

    //MinhND: get DB assign_submission 
    public static function get_submission_by_assignid_userid_groupid_parameters(){
        return new external_function_parameters(
            array(
                'assignment' => new external_value(PARAM_INT, 'asssign ID'),
                'userid' => new external_value(PARAM_INT, 'user ID'),
                'groupid' => new external_value(PARAM_INT, 'group ID'),
                'attemptnumber' => new external_value(PARAM_INT, 'attempnumber')
            )
        );
    }

    public static function get_submission_by_assignid_userid_groupid($assignment, $userid, $groupid, $attempnumber){
        global $DB;

        $warnings = array();

        $result = array();
        

        //Validate param
        $params = self::validate_parameters(self::get_submission_by_assignid_userid_groupid_parameters(),
            array(
                'assignment' => $assignment,
                'userid' => $userid,
                'groupid' => $groupid,
                'attemptnumber' => $attempnumber
            )
        );
        if (!$params["attemptnumber"]){
            unset($params["attemptnumber"]);
        }

        $result['submissions'] = $DB->get_records('assign_submission', $params, 'attemptnumber DESC', '*', 0, 1);
        
        $result['warnings'] = $warnings;       
        
        return $result;
    }
    
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
}
