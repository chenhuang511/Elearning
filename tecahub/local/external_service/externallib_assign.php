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
class local_nccsoft_external_assign extends external_api {

    //region _VIETNH
    /**
     * VietNH 23-05-2016
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
     */
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
        $fs = get_file_storage();
        $files = $fs->get_area_files($assign->get_context()->id, 'mod_assign', ASSIGN_INTROATTACHMENT_FILEAREA,
            0, 'id', false);
        if(count($files)>0){
            // return file variable
        }
        //$assign->has
        $postfix = '';
       /* if ($assign->count()) {
            echo 1;die;
            $postfix = $assign->render_area_files('mod_assign', ASSIGN_INTROATTACHMENT_FILEAREA, 0);
        }*/

        // Display plugin specific headers.
      /*  $plugins = array_merge($assign->get_submission_plugins(), $assign->get_feedback_plugins());
        foreach ($plugins as $plugin) {
            if ($plugin->is_enabled() && $plugin->is_visible()) {
                //$o .= $assign->get_renderer()->render(new assign_plugin_header($plugin));
            }
        }*/

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


}
