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
 *       - Webservices API: {@link http://docs.moodle.org/dev/Web_services_API}
 *       - External API: {@link http://docs.moodle.org/dev/External_functions_API}
 *       - Upgrade API: {@link http://docs.moodle.org/dev/Upgrade_API}
 *
 * @package       core_webservice
 * @category   webservice
 * @copyright  2009 Petr Skodak
 * @license       http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$functions = array(
    'local_get_grade_settings_by' => array(
        'classname' => 'local_grade_external',
        'methodname' => 'get_grade_settings_by',
        'classpath' => 'local/grade/externallib.php',
        'description' => 'Get grade settings by',
        'type' => 'read',
        'ajax' => true
    ),
    'local_get_grade_categories_by' => array(
        'classname' => 'local_grade_external',
        'methodname' => 'get_grade_categories_by',
        'classpath' => 'local/grade/externallib.php',
        'description' => 'Get grade categories by',
        'type' => 'read',
        'ajax' => true
    ),
    'local_get_list_grade_settings_by' => array(
        'classname' => 'local_grade_external',
        'methodname' => 'get_list_grade_settings_by',
        'classpath' => 'local/grade/externallib.php',
        'description' => 'Get list grade settings by',
        'type' => 'read',
        'ajax' => true
    ),
    'local_get_list_grade_categories_by' => array(
        'classname' => 'local_grade_external',
        'methodname' => 'get_list_grade_categories_by',
        'classpath' => 'local/grade/externallib.php',
        'description' => 'Get list grade categories by',
        'type' => 'read',
        'ajax' => true
    ),
    'local_save_mdl_grade' => array(
        'classname' => 'local_grade_external',
        'methodname' => 'save_mdl_grade',
        'classpath' => 'local/grade/externallib.php',
        'description' => 'save new grade object',
        'type' => 'read',
        'ajax' => true
    ),
    'local_update_mdl_grade' => array(
        'classname' => 'local_grade_external',
        'methodname' => 'update_mdl_grade',
        'classpath' => 'local/grade/externallib.php',
        'description' => 'update grade object',
        'type' => 'read',
        'ajax' => true
    ),
    'local_update_mdl_grade_sql' => array(
        'classname' => 'local_grade_external',
        'methodname' => 'update_mdl_grade_sql',
        'classpath' => 'local/grade/externallib.php',
        'description' => 'update grade object',
        'type' => 'read',
        'ajax' => true
    ),
    'local_delete_mdl_grade' => array(
        'classname' => 'local_grade_external',
        'methodname' => 'delete_mdl_grade',
        'classpath' => 'local/grade/externallib.php',
        'description' => 'delete grade object',
        'type' => 'read',
        'ajax' => true
    ),
    'local_check_record_mdl_grade_exists_by' => array(
        'classname' => 'local_grade_external',
        'methodname' => 'check_record_grade_exists',
        'classpath' => 'local/grade/externallib.php',
        'description' => 'check record exists',
        'type' => 'read',
        'ajax' => true
    ),
    'local_count_mdl_grade_sql' => array(
        'classname' => 'local_grade_external',
        'methodname' => 'get_count_mdl_grade_sql',
        'classpath' => 'local/grade/externallib.php',
        'description' => 'get count',
        'type' => 'read',
        'ajax' => true
    ),
    'local_get_field_mdl_grade_sql' => array(
        'classname' => 'local_grade_external',
        'methodname' => 'get_field_mdl_grade_sql',
        'classpath' => 'local/grade/externallib.php',
        'description' => 'get field',
        'type' => 'read',
        'ajax' => true
    ),
    'local_grade_get_list_grade_categories_raw_data' => array(
        'classname' => 'local_grade_external',
        'methodname' => 'get_list_grade_categories_raw_data',
        'classpath' => 'local/grade/externallib.php',
        'description' => 'Get list grade categories as raw data',
        'type' => 'read',
        'ajax' => true
    ),
);
