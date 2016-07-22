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
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.	 See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.


/**
 * Core external functions and service definitions.
 *
 * The functions and services defined on this file are
 * processed and registered into the Moodle DB after any
 * install or upgrade operation. All plugins support this.
 *
 * For more information, take a look to the documentation available:
 *	   - Webservices API: {@link http://docs.moodle.org/dev/Web_services_API}
 *	   - External API: {@link http://docs.moodle.org/dev/External_functions_API}
 *	   - Upgrade API: {@link http://docs.moodle.org/dev/Upgrade_API}
 *
 * @package	   core_webservice
 * @category   webservice
 * @copyright  2009 Petr Skodak
 * @license	   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$functions = array(
    'local_mod_assign_get_assign_by_id' => array(
		'classname'	  => 'local_mod_assign_external',
		'methodname'  => 'get_mod_assign_by_id',
		'classpath'	  => 'local/assignment/externallib.php',
		'description' => "Get assign Submission status",
		'type'		  => 'read',
		'ajax'		  => true
	),
    'local_mod_assign_get_assign_by_id_instanceid' => array(
		'classname'	  => 'local_mod_assign_external',
		'methodname'  => 'get_mod_assign_by_id_instanceid',
		'classpath'	  => 'local/assignment/externallib.php',
		'description' => "Get assign by id and instanceid",
		'type'		  => 'read',
		'ajax'		  => true
	),
	'local_mod_assign_get_submission_status' => array(
		'classname'	  => 'local_mod_assign_external',
		'methodname'  => 'get_mod_assign_submission_status',
		'classpath'	  => 'local/assignment/externallib.php',
		'description' => "Get assign Submition status",
		'type'		  => 'read',
		'ajax'		  => true
	),
	'local_mod_assign_get_submissions_by_host_ip' => array(
		'classname'	  => 'local_mod_assign_external',
		'methodname'  => 'get_submissions_by_host_ip',
		'classpath'	  => 'local/assignment/externallib.php',
		'description' => "Get assign Submitions by assign ids and host ip",
		'type'		  => 'read',
		'ajax'		  => true
	),
	'local_mod_assign_get_onlinetext_submission' => array(
		'classname'	  => 'local_mod_assign_external',
		'methodname'  => 'get_onlinetext_submission',
		'classpath'	  => 'local/assignment/externallib.php',
		'description' => "Get online text submission",
		'type'		  => 'read',
		'ajax'		  => true
	),
	'local_mod_assign_create_onlinetext_submission' => array(
		'classname'	  => 'local_mod_assign_external',
		'methodname'  => 'create_onlinetext_submission',
		'classpath'	  => 'local/assignment/externallib.php',
		'description' => "Create online text submission",
		'type'		  => 'write',
		'ajax'		  => true
	),
	'local_mod_assign_update_onlinetext_submission' => array(
		'classname'	  => 'local_mod_assign_external',
		'methodname'  => 'update_onlinetext_submission',
		'classpath'	  => 'local/assignment/externallib.php',
		'description' => "Update online text submission",
		'type'		  => 'write',
		'ajax'		  => true
	),
	'local_mod_assign_get_plugin_config' => array(
		'classname'	  => 'local_mod_assign_external',
		'methodname'  => 'get_assign_plugin_config',
		'classpath'	  => 'local/assignment/externallib.php',
		'description' => "Get assign plugin config",
		'type'		  => 'read',
		'ajax'		  => true
	),
	'local_mod_assign_get_comment_status' => array(
		'classname'	  => 'local_mod_assign_external',
		'methodname'  => 'get_comment_status',
		'classpath'	  => 'local/assignment/externallib.php',
		'description' => "Get total comment status",
		'type'		  => 'read',
		'ajax'		  => true
	),
	'local_mod_assign_count_submissions_with_status_by_host_id' => array(
		'classname'	  => 'local_mod_assign_external',
		'methodname'  => 'count_submissions_with_status_by_host_id',
		'classpath'	  => 'local/assignment/externallib.php',
		'description' => "Count submission with status by host id",
		'type'		  => 'read',
		'ajax'		  => true
	),
	'local_mod_assign_count_submissions_need_grading_by_host_id' => array(
		'classname'	  => 'local_mod_assign_external',
		'methodname'  => 'count_submissions_need_grading_by_host_id',
		'classpath'	  => 'local/assignment/externallib.php',
		'description' => "Count submission need grading by host id",
		'type'		  => 'read',
		'ajax'		  => true
	),
	'local_mod_assign_get_submission_by_assignid_userid_groupid' => array(
		'classname'	  => 'local_mod_assign_external',
		'methodname'  => 'get_submission_by_assignid_userid_groupid',
		'classpath'	  => 'local/assignment/externallib.php',
		'description' => "Get submissions by assignid & userid & groupid",
		'type'		  => 'read',
		'ajax'		  => true
	),
	'local_mod_assign_get_attemptnumber_by_assignid_userid_groupid' => array(
		'classname'	  => 'local_mod_assign_external',
		'methodname'  => 'get_attemptnumber_by_assignid_userid_groupid',
		'classpath'	  => 'local/assignment/externallib.php',
		'description' => "Get attemptnumber by assignid & userid & groupid",
		'type'		  => 'read',
		'ajax'		  => true
	),
	'local_mod_assign_get_user_flags_by_assignid_userid' => array(
		'classname'	  => 'local_mod_assign_external',
		'methodname'  => 'get_user_flags_by_assignid_userid',
		'classpath'	  => 'local/assignment/externallib.php',
		'description' => "Get user flags by assignid and userid",
		'type'		  => 'read',
		'ajax'		  => true
	),
	'local_mod_assign_set_submission_lastest' => array(
		'classname'	  => 'local_mod_assign_external',
		'methodname'  => 'set_submission_lastest',
		'classpath'	  => 'local/assignment/externallib.php',
		'description' => "Set latest to 0 for all the other attempts",
		'type'		  => 'read',
		'ajax'		  => true
	),
	'local_mod_assign_create_submission' => array(
		'classname'	  => 'local_mod_assign_external',
		'methodname'  => 'create_submission',
		'classpath'	  => 'local/assignment/externallib.php',
		'description' => "Create new submission",
		'type'		  => 'write',
		'ajax'		  => true
	),
	'local_mod_assign_update_submission' => array(
		'classname'	  => 'local_mod_assign_external',
		'methodname'  => 'update_submission',
		'classpath'	  => 'local/assignment/externallib.php',
		'description' => "Update new submission",
		'type'		  => 'write',
		'ajax'		  => true
	),
	'local_mod_assign_get_submission_by_id' => array(
		'classname'	  => 'local_mod_assign_external',
		'methodname'  => 'get_submission_by_id',
		'classpath'	  => 'local/assignment/externallib.php',
		'description' => "get assign submission by id",
		'type'		  => 'read',
		'ajax'		  => true
	),
	'local_mod_assign_save_remote_submission' => array(
		'classname'	  => 'local_mod_assign_external',
		'methodname'  => 'save_remote_submission',
		'classpath'	  => 'local/assignment/externallib.php',
		'description' => "Update the current students submission",
		'type'		  => 'write',
		'ajax'		  => true
	),
	'local_mod_assign_submit_remote_for_grading' => array(
		'classname'	  => 'local_mod_assign_external',
		'methodname'  => 'submit_remote_for_grading',
		'classpath'	  => 'local/assignment/externallib.php',
		'description' => "Submit submission for grading",
		'type'		  => 'write',
		'ajax'		  => true
	),
	'local_mod_assign_get_remote_submission_status' => array(
		'classname'	  => 'local_mod_assign_external',
		'methodname'  => 'get_remote_submission_status',
		'classpath'	  => 'local/assignment/externallib.php',
		'description' => "Get remote submission status",
		'type'		  => 'read',
		'ajax'		  => true
	),
	'local_mod_assign_get_assignfeedback_comments' => array(
		'classname'	  => 'local_mod_assign_external',
		'methodname'  => 'get_assignfeedback_comments',
		'classpath'	  => 'local/assignment/externallib.php',
		'description' => "Get assignfeedback comment",
		'type'		  => 'read',
		'ajax'		  => true
	),
	'local_mod_assign_update_assignfeedback_comments' => array(
		'classname'	  => 'local_mod_assign_external',
		'methodname'  => 'update_assignfeedback_comments',
		'classpath'	  => 'local/assignment/externallib.php',
		'description' => "Update assignfeedback comment",
		'type'		  => 'write',
		'ajax'		  => true
	),
	'local_mod_assign_create_assignfeedback_comments' => array(
		'classname'	  => 'local_mod_assign_external',
		'methodname'  => 'create_assignfeedback_comments',
		'classpath'	  => 'local/assignment/externallib.php',
		'description' => "Create assignfeedback comment",
		'type'		  => 'write',
		'ajax'		  => true
	),
	'local_mod_assign_get_grades_by_assignid_userid' => array(
		'classname'	  => 'local_mod_assign_external',
		'methodname'  => 'get_grades_by_assignid_userid',
		'classpath'	  => 'local/assignment/externallib.php',
		'description' => "Get assign grade",
		'type'		  => 'read',
		'ajax'		  => true
	),
	'local_mod_assign_get_grades_by_id' => array(
		'classname'	  => 'local_mod_assign_external',
		'methodname'  => 'get_grades_by_id',
		'classpath'	  => 'local/assignment/externallib.php',
		'description' => "Get assign grade by id",
		'type'		  => 'read',
		'ajax'		  => true
	),
	'local_mod_assign_create_grade' => array(
		'classname'	  => 'local_mod_assign_external',
		'methodname'  => 'create_grade',
		'classpath'	  => 'local/assignment/externallib.php',
		'description' => "Create assign grade",
		'type'		  => 'write',
		'ajax'		  => true
	),
	'local_mod_assign_update_grade' => array(
		'classname'	  => 'local_mod_assign_external',
		'methodname'  => 'update_grade',
		'classpath'	  => 'local/assignment/externallib.php',
		'description' => "Update assign grade",
		'type'		  => 'write',
		'ajax'		  => true
	),
	'local_mod_assign_get_remote_submission_info_for_participants' => array(
		'classname'	  => 'local_mod_assign_external',
		'methodname'  => 'get_remote_submission_info_for_participants',
		'classpath'	  => 'local/assignment/externallib.php',
		'description' => "Get submission info for participants",
		'type'		  => 'read',
		'ajax'		  => true
	),
	'local_mod_assign_submit_grading_form' => array(
		'classname'	  => 'local_mod_assign_external',
		'methodname'  => 'submit_grading_form',
		'classpath'	  => 'local/assignment/externallib.php',
		'description' => "Submit the remote grading form data via ajax",
		'type'		  => 'write',
		'ajax'		  => true
	),
	'local_mod_assign_get_raw_data_query_db' => array(
		'classname'	  => 'local_mod_assign_external',
		'methodname'  => 'get_raw_data_query_db',
		'classpath'	  => 'local/assignment/externallib.php',
		'description' => 'Get raw data via ajax',
		'type'		  => 'read',
		'ajax'		  => true
	),
	'local_mod_assign_get_grade_raw_data_infomation' => array(
		'classname'	  => 'local_mod_assign_external',
		'methodname'  => 'get_grade_raw_data_infomation',
		'classpath'	  => 'local/assignment/externallib.php',
		'description' => 'Get grade raw data infomation via ajax',
		'type'		  => 'read',
		'ajax'		  => true
	),
	'local_mod_assign_get_scale_by_id' => array(
		'classname'	  => 'local_mod_assign_external',
		'methodname'  => 'get_scale_by_id',
		'classpath'	  => 'local/assignment/externallib.php',
		'description' => 'Get scale by id',
		'type'		  => 'read',
		'ajax'		  => true
	),
	'local_mod_assign_get_grade_items_raw_data' => array(
		'classname'	  => 'local_mod_assign_external',
		'methodname'  => 'get_grade_items_raw_data',
		'classpath'	  => 'local/assignment/externallib.php',
		'description' => 'Get grade items raw data infomation via ajax',
		'type'		  => 'read',
		'ajax'		  => true
	),
	'local_mod_assign_get_files_submission' => array(
		'classname'	  => 'local_mod_assign_external',
		'methodname'  => 'get_files_submission',
		'classpath'	  => 'local/assignment/externallib.php',
		'description' => "Get file assign submission",
		'type'		  => 'read',
		'ajax'		  => true
	),
	'local_mod_assign_create_files_submission' => array(
		'classname'	  => 'local_mod_assign_external',
		'methodname'  => 'create_files_submission',
		'classpath'	  => 'local/assignment/externallib.php',
		'description' => "Create file assign submission",
		'type'		  => 'write',
		'ajax'		  => true
	),
	'local_mod_assign_update_files_submission' => array(
		'classname'	  => 'local_mod_assign_external',
		'methodname'  => 'update_files_submission',
		'classpath'	  => 'local/assignment/externallib.php',
		'description' => "Update file assign submission",
		'type'		  => 'write',
		'ajax'		  => true
	),
    'local_mod_assign_get_grade_grades_raw_data' => array(
        'classname'	  => 'local_mod_assign_external',
        'methodname'  => 'get_grade_grades_raw_data',
        'classpath'	  => 'local/assignment/externallib.php',
        'description' => 'Get grade grades raw data infomation via ajax',
        'type'		  => 'read',
        'ajax'		  => true
    ),
);

