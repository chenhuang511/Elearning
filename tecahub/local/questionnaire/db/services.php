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
	'local_questionnaire_get_question_by_id' => array(
		'classname'	  => 'local_questionnaire_external',
		'methodname'  => 'questionnaire_get_question_by_id',
		'classpath'	  => 'local/questionnaire/externallib.php',
		'description' => "Get question by id",
		'type'		  => 'read',
		'ajax'		  => true
	),
	'local_questionnaire_get_field_owner_questionnaire_by_id' => array(
		'classname'	  => 'local_questionnaire_external',
		'methodname'  => 'questionnaire_get_field_owner_questionnaire_by_id',
		'classpath'	  => 'local/questionnaire/externallib.php',
		'description' => "Get field owner by id",
		'type'		  => 'read',
		'ajax'		  => true
	),
	'local_questionnaire_get_questionnaire_survey_by_id' => array(
		'classname'	  => 'local_questionnaire_external',
		'methodname'  => 'questionnaire_get_questionnaire_survey_by_id',
		'classpath'	  => 'local/questionnaire/externallib.php',
		'description' => "Get questionnaire_survey by id",
		'type'		  => 'read',
		'ajax'		  => true
	),
	'local_questionnaire_get_questionnaire_question_by_sid' => array(
		'classname'	  => 'local_questionnaire_external',
		'methodname'  => 'questionnaire_get_questionnaire_question_by_sid',
		'classpath'	  => 'local/questionnaire/externallib.php',
		'description' => "Get questionnaire_question by condition",
		'type'		  => 'read',
		'ajax'		  => true
	),
	'local_questionnaire_get_questionnaire_quest_choice_by_question_id' => array(
		'classname'	  => 'local_questionnaire_external',
		'methodname'  => 'questionnaire_get_questionnaire_quest_choice_by_question_id',
		'classpath'	  => 'local/questionnaire/externallib.php',
		'description' => "Get questionnaire_question by condition",
		'type'		  => 'read',
		'ajax'		  => true
	),
	'local_mod_save_response_by_mbl' => array(
		'classname'	  => 'local_questionnaire_external',
		'methodname'  => 'save_response_by_mbl',
		'classpath'	  => 'local/questionnaire/externallib.php',
		'description' => "Save response by table name",
		'type'		  => 'read',
		'ajax'		  => true
	),
	'local_mod_update_response_by_mbl' => array(
		'classname'	  => 'local_questionnaire_external',
		'methodname'  => 'update_response_by_mbl',
		'classpath'	  => 'local/questionnaire/externallib.php',
		'description' => "update response by table name",
		'type'		  => 'read',
		'ajax'		  => true
	),
	'local_mod_get_questionnaire_attempts' => array(
		'classname'	  => 'local_questionnaire_external',
		'methodname'  => 'get_questionnaire_attempts',
		'classpath'	  => 'local/questionnaire/externallib.php',
		'description' => "Get questionnaire attempts by condition",
		'type'		  => 'read',
		'ajax'		  => true
	),
	'local_mod_get_questionnaire_response' => array(
		'classname'	  => 'local_questionnaire_external',
		'methodname'  => 'get_questionnaire_response',
		'classpath'	  => 'local/questionnaire/externallib.php',
		'description' => "Get questionnaire response by condition",
		'type'		  => 'read',
		'ajax'		  => true
	), ////continue
	'local_mod_get_questionnaire_bool_question' => array(
		'classname'	  => 'local_questionnaire_external',
		'methodname'  => 'get_questionnaire_bool_question',
		'classpath'	  => 'local/questionnaire/externallib.php',
		'description' => "Get questionnaire_bool and question",
		'type'		  => 'read',
		'ajax'		  => true
	),
	'local_mod_get_questionnaire_single_question_choice' => array(
		'classname'	  => 'local_questionnaire_external',
		'methodname'  => 'get_questionnaire_single_question_choice',
		'classpath'	  => 'local/questionnaire/externallib.php',
		'description' => "Get single and question and choice",
		'type'		  => 'read',
		'ajax'		  => true
	),
	'local_mod_get_questionnaire_multiple_question_choice' => array(
		'classname'	  => 'local_questionnaire_external',
		'methodname'  => 'get_questionnaire_multiple_question_choice',
		'classpath'	  => 'local/questionnaire/externallib.php',
		'description' => "Get multiple and question and choice",
		'type'		  => 'read',
		'ajax'		  => true
	),
	'local_mod_get_questionnaire_gest_choice' => array(
		'classname'	  => 'local_questionnaire_external',
		'methodname'  => 'get_questionnaire_gest_choice',
		'classpath'	  => 'local/questionnaire/externallib.php',
		'description' => "Get questionnaire gest choice",
		'type'		  => 'read',
		'ajax'		  => true
	),
	'local_mod_get_questionnaire_other_question_choice' => array(
		'classname'	  => 'local_questionnaire_external',
		'methodname'  => 'get_questionnaire_other_question_choice',
		'classpath'	  => 'local/questionnaire/externallib.php',
		'description' => "Get questionnaire other and question and choice",
		'type'		  => 'read',
		'ajax'		  => true
	),
	'local_mod_get_questionnaire_rank_question_choice' => array(
		'classname'	  => 'local_questionnaire_external',
		'methodname'  => 'get_questionnaire_rank_question_choice',
		'classpath'	  => 'local/questionnaire/externallib.php',
		'description' => "Get questionnaire rank and question and choice",
		'type'		  => 'read',
		'ajax'		  => true
	),
	'local_mod_get_questionnaire_text_question' => array(
		'classname'	  => 'local_questionnaire_external',
		'methodname'  => 'get_questionnaire_text_question',
		'classpath'	  => 'local/questionnaire/externallib.php',
		'description' => "Get questionnaire text and question",
		'type'		  => 'read',
		'ajax'		  => true
	),
	'local_mod_get_questionnaire_date_question' => array(
		'classname'	  => 'local_questionnaire_external',
		'methodname'  => 'get_questionnaire_date_question',
		'classpath'	  => 'local/questionnaire/externallib.php',
		'description' => "Get questionnaire date and question",
		'type'		  => 'read',
		'ajax'		  => true
	),
);
