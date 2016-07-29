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
	'local_mod_quiz_get_quiz_by_id' => array(
		'classname'	  => 'local_mod_quiz_external',
		'methodname'  => 'get_mod_quiz_by_id',
		'classpath'	  => 'local/quiz/externallib.php',
		'description' => "Get quiz by quiz id or by coursemodul instance",
		'type'		  => 'read',
		'ajax'		  => true
	),
	'local_mod_quiz_get_questions_by_quizid' => array(
		'classname'	  => 'local_mod_quiz_external',
		'methodname'  => 'get_mod_questions_by_quizid',
		'classpath'	  => 'local/quiz/externallib.php',
		'description' => "Given a list of ids, load the basic information about a set of questions from the questions table.",
		'type'		  => 'read',
		'ajax'		  => true
	),
	'local_mod_quiz_get_attempt_by_attemptid' => array(
		'classname'	  => 'local_mod_quiz_external',
		'methodname'  => 'get_mod_attempt_by_attemptid',
		'classpath'	  => 'local/quiz/externallib.php',
		'description' => "Get attempt data from attemptid.",
		'type'		  => 'read',
		'ajax'		  => true
	),
	'local_mod_quiz_load_questions_usage_by_activity' => array(
		'classname'	  => 'local_mod_quiz_external',
		'methodname'  => 'get_mod_load_questions_usage_by_activity',
		'classpath'	  => 'local/quiz/externallib.php',
		'description' => "Get quba for quiz_attempt _construct.",
		'type'		  => 'read',
		'ajax'		  => true
	),
	'local_mod_quiz_get_slots_by_quizid' => array(
		'classname'	  => 'local_mod_quiz_external',
		'methodname'  => 'get_mod_slots_by_quizid',
		'classpath'	  => 'local/quiz/externallib.php',
		'description' => "Get slots for quiz_attempt _construct.",
		'type'		  => 'read',
		'ajax'		  => true
	),
	'local_mod_quiz_get_sections_by_quizid' => array(
		'classname'	  => 'local_mod_quiz_external',
		'methodname'  => 'get_mod_sections_by_quizid',
		'classpath'	  => 'local/quiz/externallib.php',
		'description' => "Get sections for quiz_attempt _construct.",
		'type'		  => 'read',
		'ajax'		  => true
	),
	'local_mod_quiz_start_remote_attempt' => array(
		'classname'	  => 'local_mod_quiz_external',
		'methodname'  => 'start_remote_attempt',
		'classpath'	  => 'local/quiz/externallib.php',
		'description' => "Start quiz attempt for remote user.",
		'type'		  => 'read',
		'ajax'		  => true
	),
	'local_mod_quiz_count_attempt_summary' => array(
		'classname'	  => 'local_mod_quiz_external',
		'methodname'  => 'count_attempt_summary',
		'classpath'	  => 'local/quiz/externallib.php',
		'description' => "Show quiz number of attempts summary to those who can view report.",
		'type'		  => 'read',
		'ajax'		  => true
	),
	'local_mod_quiz_report_get_significant_questions' => array(
		'classname'	  => 'local_mod_quiz_external',
		'methodname'  => 'report_get_significant_questions',
		'classpath'	  => 'local/quiz/externallib.php',
		'description' => "Get the slots of real questions (not descriptions) in this quiz, in order.",
		'type'		  => 'read',
		'ajax'		  => true
	),
	'local_mod_quiz_report_get_grand_total' => array(
		'classname'	  => 'local_mod_quiz_external',
		'methodname'  => 'get_grand_total',
		'classpath'	  => 'local/quiz/externallib.php',
		'description' => "Querry db with sql params to get grand total",
		'type'		  => 'read',
		'ajax'		  => true
	),
	'local_mod_quiz_report_get_rowdata_for_tableview' => array(
		'classname'	  => 'local_mod_quiz_external',
		'methodname'  => 'get_rowdata_for_tableview',
		'classpath'	  => 'local/quiz/externallib.php',
		'description' => "Show quiz number of attempts summary to those who can view report.",
		'type'		  => 'read',
		'ajax'		  => true
	),
	'local_mod_quiz_load_questions_usages_latest_steps' => array(
		'classname'	  => 'local_mod_quiz_external',
		'methodname'  => 'load_questions_usages_latest_steps',
		'classpath'	  => 'local/quiz/externallib.php',
		'description' => "Load information about the latest state of each question from the database.",
		'type'		  => 'read',
		'ajax'		  => true
	),
	'local_mod_quiz_get_report_avg_record' => array(
	'classname'	  => 'local_mod_quiz_external',
	'methodname'  => 'get_report_avg_record',
	'classpath'	  => 'local/quiz/externallib.php',
	'description' => "Get average grade and count numaverage for table view.",
	'type'		  => 'read',
	'ajax'		  => true
	),
	'local_mod_quiz_report_check_quiz_grade' => array(
	'classname'	  => 'local_mod_quiz_external',
	'methodname'  => 'check_exist_quiz_grade',
	'classpath'	  => 'local/quiz/externallib.php',
	'description' => "Check exist quiz grade record in DB.",
	'type'		  => 'read',
	'ajax'		  => true
	),
	'local_mod_quiz_report_get_grade_bands' => array(
	'classname'	  => 'local_mod_quiz_external',
	'methodname'  => 'get_grade_bands',
	'classpath'	  => 'local/quiz/externallib.php',
	'description' => "get grade bands data by sql and param from host.",
	'type'		  => 'read',
	'ajax'		  => true
	),
	'local_mod_quiz_load_questions_usages_question_state_summary' => array(
		'classname'	  => 'local_mod_quiz_external',
		'methodname'  => 'load_questions_usages_question_state_summary',
		'classpath'	  => 'local/quiz/externallib.php',
		'description' => "Load information about the number of attempts at various questions in each summarystate.",
		'type'		  => 'read',
		'ajax'		  => true
	),
	'local_mod_quiz_load_questions_usages_where_question_in_state' => array(
	'classname'	  => 'local_mod_quiz_external',
	'methodname'  => 'load_questions_usages_where_question_in_state',
	'classpath'	  => 'local/quiz/externallib.php',
	'description' => "Get a list of usage ids where the question with slot. Also return the total count of such states.",
	'type'		  => 'read',
	'ajax'		  => true
	),
	'local_mod_quiz_get_remote_attempts_byid' => array(
		'classname'	  => 'local_mod_quiz_external',
		'methodname'  => 'grading_get_remote_attempts_byid',
		'classpath'	  => 'local/quiz/externallib.php',
		'description' => "Get grading attempts data by select fields, where asql, params.",
		'type'		  => 'read',
		'ajax'		  => true
	),
	'local_mod_quiz_grading_process_submitted_data' => array(
		'classname'	  => 'local_mod_quiz_external',
		'methodname'  => 'grading_process_submitted_data',
		'classpath'	  => 'local/quiz/externallib.php',
		'description' => "Process any submitted data.",
		'type'		  => 'read',
		'ajax'		  => true
	),
	'local_mod_quiz_get_essay_question_options' => array(
	'classname'	  => 'local_mod_quiz_external',
	'methodname'  => 'get_essay_question_options',
	'classpath'	  => 'local/quiz/externallib.php',
	'description' => "Get essay question options.",
	'type'		  => 'read',
	'ajax'		  => true
	),
	'local_mod_quiz_get_question_options_answer' => array(
		'classname'	  => 'local_mod_quiz_external',
		'methodname'  => 'get_question_options_answer',
		'classpath'	  => 'local/quiz/externallib.php',
		'description' => "Get question option answer. Don't check for success or failure because some question types do not use the answers table.",
		'type'		  => 'read',
		'ajax'		  => true
	),
	'local_mod_quiz_get_question_hints' => array(
		'classname'	  => 'local_mod_quiz_external',
		'methodname'  => 'get_question_hints',
		'classpath'	  => 'local/quiz/externallib.php',
		'description' => "Get remote question hints.",
		'type'		  => 'read',
		'ajax'		  => true
	),
	'local_mod_quiz_get_question_preload_question' => array(
		'classname'	  => 'local_mod_quiz_external',
		'methodname'  => 'get_question_preload_question',
		'classpath'	  => 'local/quiz/externallib.php',
		'description' => "Given a list of ids, load the basic information about a set of questions from the questions table.",
		'type'		  => 'read',
		'ajax'		  => true
	),
	'local_mod_quiz_get_multichoice_question_options' => array(
		'classname'	  => 'local_mod_quiz_external',
		'methodname'  => 'get_multichoice_question_options',
		'classpath'	  => 'local/quiz/externallib.php',
		'description' => "Get multichoice question options.",
		'type'		  => 'read',
		'ajax'		  => true
	),
	'local_mod_quiz_get_statistic_questions_usages' => array(
		'classname'	  => 'local_mod_quiz_external',
		'methodname'  => 'get_statistic_questions_usages',
		'classpath'	  => 'local/quiz/externallib.php',
		'description' => "Get the latest step data from the db, from which we will calculate stats.",
		'type'		  => 'read',
		'ajax'		  => true
	),
	'local_mod_quiz_get_userlocal_by_userhubid' => array(
		'classname'	  => 'local_mod_quiz_external',
		'methodname'  => 'get_userlocal_by_userhubid',
		'classpath'	  => 'local/quiz/externallib.php',
		'description' => "Get user email by userhub id to get userinfo in host.",
		'type'		  => 'read',
		'ajax'		  => true
	),
);