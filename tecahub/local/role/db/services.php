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
	'local_remote_assign_role_to_user' => array(
		'classname'	  => 'local_role_external',
		'methodname'  => 'remote_assign_role_to_user',
		'classpath'	  => 'local/role/externallib.php',
		'description' => "Assign role to user",
		'type'		  => 'read',
		'ajax'		  => true
	),
	'local_remote_unassign_role_to_user' => array(
		'classname'	  => 'local_role_external',
		'methodname'  => 'remote_unassign_role_to_user',
		'classpath'	  => 'local/role/externallib.php',
		'description' => "Unassign role to user",
		'type'		  => 'read',
		'ajax'		  => true
	),
);