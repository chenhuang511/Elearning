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
	'local_get_course_content_by_id' => array(
		'classname'   => 'local_course_external',
		'methodname'  => 'get_course_content_by_id',
		'classpath'   => 'local/course/externallib.php',
		'description' => "Get Course Content with courses id",
		'type'        => 'read',
		'ajax'        => true
	),
	'local_get_course_thumbnail_by_id' => array(
		'classname'   => 'local_course_external',
		'methodname'  => 'get_course_thumbnail_by_id',
		'classpath'   => 'local/course/externallib.php',
		'description' => "Get thumbnail url with courses id",
		'type'        => 'read',
		'ajax'        => true
	),
	'local_get_course_mods' => array(
		'classname'   => 'local_course_external',
		'methodname'  => 'get_remote_course_mods',
		'classpath'   => 'local/course/externallib.php',
		'description' => "Get course modules with courses id",
		'type'        => 'read',
		'ajax'        => true
	),
	'local_get_course_sections' => array(
		'classname'   => 'local_course_external',
		'methodname'  => 'get_remote_course_sections',
		'classpath'   => 'local/course/externallib.php',
		'description' => "Get course sessions with courses id",
		'type'        => 'read',
		'ajax'        => true
	),
	'local_get_course_module_info' => array(
		'classname'	  => 'local_course_external',
		'methodname'  => 'get_course_module_info',
		'classpath'	  => 'local/course/externallib.php',
		'description' => "Get course module info by give module name and instance id",
		'type'		  => 'read',
		'ajax'		  => true
	),
	'local_get_course_module_by_cmid' => array(
		'classname'	  => 'local_course_external',
		'methodname'  => 'get_course_module_by_cmid',
		'classpath'	  => 'local/course/externallib.php',
		'description' => "Get course by module name and id of course module",
		'type'		  => 'read',
		'ajax'		  => true
	),	
	'local_get_course_info_by_course_id' => array(
		'classname'     => 'local_course_external',
		'methodname'    => 'get_course_info_by_course_id',
		'classpath'     => 'local/course/externallib.php',
		'description'   => 'Get course information by course id',
		'type'		    => 'read',
		'ajax'		    => true
	),
	'local_get_name_modules_by_id' => array(
		'classname'     => 'local_course_external',
		'methodname'    => 'get_name_modules_by_id',
		'classpath'     => 'local/course/externallib.php',
		'description'   => 'Get name of modules by id',
		'type'		    => 'read',
		'ajax'		    => true
	),
	'local_get_modules_by_id' => array(
		'classname'     => 'local_course_external',
		'methodname'    => 'get_modules_by_id',
		'classpath'     => 'local/course/externallib.php',
		'description'   => 'Get name of modules by id',
		'type'		    => 'read',
		'ajax'		    => true
	),
	'local_get_remote_course_section_nav' => array(
		'classname'     => 'local_course_external',
		'methodname'    => 'get_remote_course_section_nav',
		'classpath'     => 'local/course/externallib.php',
		'description'   => 'Get remote course section for navigation',
		'type'		    => 'read',
		'ajax'		    => true
	),
	'local_get_remote_course_format_options' => array(
		'classname'     => 'local_course_external',
		'methodname'    => 'get_remote_course_format_options',
		'classpath'     => 'local/course/externallib.php',
		'description'   => 'Get remote course section for navigation',
		'type'		    => 'read',
		'ajax'		    => true
	),
    'local_get_remote_modules_by' => array(
        'classname'     => 'local_course_external',
        'methodname'    => 'get_modules_by',
        'classpath'     => 'local/course/externallib.php',
        'description'   => 'Get modules by',
        'type'		    => 'read',
        'ajax'		    => true
    ),
    'local_delete_remote_course_modules_completion_by_cmid_hostip' => array(
        'classname'     => 'local_course_external',
        'methodname'    => 'delete_remote_course_modules_completion_by_cmid_hostip',
        'classpath'     => 'local/course/externallib.php',
        'description'   => 'Delete remote course modules completion',
        'type'		    => 'write',
        'ajax'		    => true
    ),
    'local_detele_course_completions_by_courseid_hostip' => array(
        'classname'     => 'local_course_external',
        'methodname'    => 'delete_remote_course_completions',
        'classpath'     => 'local/course/externallib.php',
        'description'   => 'Delete remote course completions',
        'type'		    => 'write',
        'ajax'		    => true
    ),
    'local_delete_course_completion_crit_compl_by_courseid_hostip' => array(
        'classname'     => 'local_course_external',
        'methodname'    => 'delete_remote_course_completion_crit_compl',
        'classpath'     => 'local/course/externallib.php',
        'description'   => 'Delete remote course completion crit compl',
        'type'		    => 'write',
        'ajax'		    => true
    ),
    'local_get_remote_course_modules_completion' => array(
        'classname'     => 'local_course_external',
        'methodname'    => 'get_remote_course_modules_completion',
        'classpath'     => 'local/course/externallib.php',
        'description'   => 'Get remote course modules completion',
        'type'		    => 'read',
        'ajax'		    => true
    ),
    'local_create_update_remote_course_modules_completion' => array(
        'classname'     => 'local_course_external',
        'methodname'    => 'create_update_remote_course_modules_completion',
        'classpath'     => 'local/course/externallib.php',
        'description'   => 'Update create remote course modules completion',
        'type'		    => 'write',
        'ajax'		    => true
    ),
    'local_get_course_completion_progress' => array(
        'classname'     => 'local_course_external',
        'methodname'    => 'get_course_completion_progress',
        'classpath'     => 'local/course/externallib.php',
        'description'   => 'get course completion',
        'type'		    => 'write',
        'ajax'		    => true
    ),
    'local_get_list_course_completion' => array(
        'classname'     => 'local_course_external',
        'methodname'    => 'get_list_course_completion',
        'classpath'     => 'local/course/externallib.php',
        'description'   => 'get list id of course completion',
        'type'		    => 'write',
        'ajax'		    => true
    ),
    'local_get_course_completion_count_user_data' => array(
        'classname'     => 'local_course_external',
        'methodname'    => 'count_remote_user_data_completion',
        'classpath'     => 'local/course/externallib.php',
        'description'   => 'Count user data completion',
        'type'		    => 'read',
        'ajax'		    => true
    ),
    'local_get_remote_completion_fetch_all_helper' => array(
        'classname'     => 'local_course_external',
        'methodname'    => 'get_remote_completion_fetch_all_helper',
        'classpath'     => 'local/course/externallib.php',
        'description'   => 'Get remote completion',
        'type'		    => 'read',
        'ajax'		    => true
    ),
    'local_get_tbl_modules' => array(
        'classname'     => 'local_course_external',
        'methodname'    => 'get_remote_modules',
        'classpath'     => 'local/course/externallib.php',
        'description'   => 'Get information tbl modules',
        'type'		    => 'read',
        'ajax'		    => true
    ),
    'local_update_course_completions' => array(
        'classname'     => 'local_course_external',
        'methodname'    => 'update_remote_course_completions',
        'classpath'     => 'local/course/externallib.php',
        'description'   => 'Update tbl course completions',
        'type'		    => 'write',
        'ajax'		    => true
    ),
    'local_save_mdl_course' => array(
        'classname'     => 'local_course_external',
        'methodname'    => 'save_mdl_course',
        'classpath'     => 'local/course/externallib.php',
        'description'   => 'save course_',
        'type'		    => 'write',
        'ajax'		    => true
    ),
    'local_update_mdl_course' => array(
        'classname'     => 'local_course_external',
        'methodname'    => 'update_mdl_course',
        'classpath'     => 'local/course/externallib.php',
        'description'   => 'update data course_',
        'type'		    => 'write',
        'ajax'		    => true
    ),
    'local_get_course_sections_by' => array(
        'classname'     => 'local_course_external',
        'methodname'    => 'get_course_sections_by',
        'classpath'     => 'local/course/externallib.php',
        'description'   => 'get course sections obejct',
        'type'		    => 'write',
        'ajax'		    => true
    ),
);
