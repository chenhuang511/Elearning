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
require_once($CFG->dirroot . '/mod/quiz/locallib.php');
require_once($CFG->dirroot . '/question/engine/lib.php');
require_once($CFG->dirroot . '/mod/quiz/classes/external.php');
require_once($CFG->dirroot . '/mod/quiz/report/reportlib.php');

/**
 * Course external functions
 *
 * @package    core_course
 * @category   external
 * @copyright  2011 Jerome Mouneyrac
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since Moodle 3.1
 */
class local_mod_quiz_external extends external_api {
    /**
     * Hanv 24/05/2016
     * Return all the information about a quiz by quizid or by cm->instance from course_module
     *
     * @return external_function_parameters
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     *
     */
    public static function get_mod_quiz_by_id_parameters() {
        return new external_function_parameters(
            array('id' => new external_value(PARAM_INT, 'id'),
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
                    ), 'Options, used since Moodle 3.1', VALUE_DEFAULT, array())
            )
        );
    }

    /**
     * Get Quiz object
     *
     * @param int $id quizid
     * @param array $options Options for filtering the results, used since Moodle 3.1
     * @return array
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     */
    public static function get_mod_quiz_by_id($id, $options = array()) {
        global $CFG, $DB;

        //validate parameter
        $params = self::validate_parameters(self::get_mod_quiz_by_id_parameters(),
            array('id' => $id,'options' => $options));
        // Get quiz by id
        $quiz =  $DB->get_record('quiz', array('id' => $params['id']), '*', MUST_EXIST);
        return $quiz;
    }

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     */
    public static function get_mod_quiz_by_id_returns() {
        return  new external_single_structure(
            array(
                'id' => new external_value(PARAM_INT, 'Standard Moodle primary key.'),
                'course' => new external_value(PARAM_INT, 'Foreign key reference to the course this quiz is part of.'),
                'name' => new external_value(PARAM_RAW, 'Quiz name.'),
                'intro' => new external_value(PARAM_RAW, 'Quiz introduction text.', VALUE_OPTIONAL),
                'introformat' => new external_format_value('intro', VALUE_OPTIONAL),
                'timeopen' => new external_value(PARAM_INT, 'The time when this quiz opens. (0 = no restriction.)',
                    VALUE_OPTIONAL),
                'timeclose' => new external_value(PARAM_INT, 'The time when this quiz closes. (0 = no restriction.)',
                    VALUE_OPTIONAL),
                'timelimit' => new external_value(PARAM_INT, 'The time limit for quiz attempts, in seconds.',
                    VALUE_OPTIONAL),
                'overduehandling' => new external_value(PARAM_ALPHA, 'The method used to handle overdue attempts.
                                                                    \'autosubmit\', \'graceperiod\' or \'autoabandon\'.',
                    VALUE_OPTIONAL),
                'graceperiod' => new external_value(PARAM_INT, 'The amount of time (in seconds) after the time limit
                                                                runs out during which attempts can still be submitted,
                                                                if overduehandling is set to allow it.', VALUE_OPTIONAL),
                'preferredbehaviour' => new external_value(PARAM_ALPHANUMEXT, 'The behaviour to ask questions to use.',
                    VALUE_OPTIONAL),
                'canredoquestions' => new external_value(PARAM_INT, 'Allows students to redo any completed question
                                                                        within a quiz attempt.', VALUE_OPTIONAL),
                'attempts' => new external_value(PARAM_INT, 'The maximum number of attempts a student is allowed.',
                    VALUE_OPTIONAL),
                'attemptonlast' => new external_value(PARAM_INT, 'Whether subsequent attempts start from the answer
                                                                    to the previous attempt (1) or start blank (0).',
                    VALUE_OPTIONAL),
                'grademethod' => new external_value(PARAM_INT, 'One of the values QUIZ_GRADEHIGHEST, QUIZ_GRADEAVERAGE,
                                                                    QUIZ_ATTEMPTFIRST or QUIZ_ATTEMPTLAST.', VALUE_OPTIONAL),
                'decimalpoints' => new external_value(PARAM_INT, 'Number of decimal points to use when displaying
                                                                    grades.', VALUE_OPTIONAL),
                'questiondecimalpoints' => new external_value(PARAM_INT, 'Number of decimal points to use when
                                                                            displaying question grades.
                                                                            (-1 means use decimalpoints.)', VALUE_OPTIONAL),
                'reviewattempt' => new external_value(PARAM_INT, 'Whether users are allowed to review their quiz
                                                                    attempts at various times. This is a bit field, decoded by the
                                                                    mod_quiz_display_options class. It is formed by ORing together
                                                                    the constants defined there.', VALUE_OPTIONAL),
                'reviewcorrectness' => new external_value(PARAM_INT, 'Whether users are allowed to review their quiz
                                                                        attempts at various times.
                                                                        A bit field, like reviewattempt.', VALUE_OPTIONAL),
                'reviewmarks' => new external_value(PARAM_INT, 'Whether users are allowed to review their quiz attempts
                                                                at various times. A bit field, like reviewattempt.',
                    VALUE_OPTIONAL),
                'reviewspecificfeedback' => new external_value(PARAM_INT, 'Whether users are allowed to review their
                                                                            quiz attempts at various times. A bit field, like
                                                                            reviewattempt.', VALUE_OPTIONAL),
                'reviewgeneralfeedback' => new external_value(PARAM_INT, 'Whether users are allowed to review their
                                                                            quiz attempts at various times. A bit field, like
                                                                            reviewattempt.', VALUE_OPTIONAL),
                'reviewrightanswer' => new external_value(PARAM_INT, 'Whether users are allowed to review their quiz
                                                                        attempts at various times. A bit field, like
                                                                        reviewattempt.', VALUE_OPTIONAL),
                'reviewoverallfeedback' => new external_value(PARAM_INT, 'Whether users are allowed to review their quiz
                                                                            attempts at various times. A bit field, like
                                                                            reviewattempt.', VALUE_OPTIONAL),
                'questionsperpage' => new external_value(PARAM_INT, 'How often to insert a page break when editing
                                                                        the quiz, or when shuffling the question order.',
                    VALUE_OPTIONAL),
                'navmethod' => new external_value(PARAM_ALPHA, 'Any constraints on how the user is allowed to navigate
                                                                around the quiz. Currently recognised values are
                                                                \'free\' and \'seq\'.', VALUE_OPTIONAL),
                'shuffleanswers' => new external_value(PARAM_INT, 'Whether the parts of the question should be shuffled,
                                                                    in those question types that support it.', VALUE_OPTIONAL),
                'sumgrades' => new external_value(PARAM_FLOAT, 'The total of all the question instance maxmarks.',
                    VALUE_OPTIONAL),
                'grade' => new external_value(PARAM_FLOAT, 'The total that the quiz overall grade is scaled to be
                                                            out of.', VALUE_OPTIONAL),
                'timecreated' => new external_value(PARAM_INT, 'The time when the quiz was added to the course.',
                    VALUE_OPTIONAL),
                'timemodified' => new external_value(PARAM_INT, 'Last modified time.',
                    VALUE_OPTIONAL),
                'password' => new external_value(PARAM_RAW, 'A password that the student must enter before starting or
                                                                continuing a quiz attempt.', VALUE_OPTIONAL),
                'subnet' => new external_value(PARAM_RAW, 'Used to restrict the IP addresses from which this quiz can
                                                            be attempted. The format is as requried by the address_in_subnet
                                                            function.', VALUE_OPTIONAL),
                'browsersecurity' => new external_value(PARAM_ALPHANUMEXT, 'Restriciton on the browser the student must
                                                                    use. E.g. \'securewindow\'.', VALUE_OPTIONAL),
                'delay1' => new external_value(PARAM_INT, 'Delay that must be left between the first and second attempt,
                                                            in seconds.', VALUE_OPTIONAL),
                'delay2' => new external_value(PARAM_INT, 'Delay that must be left between the second and subsequent
                                                            attempt, in seconds.', VALUE_OPTIONAL),
                'showuserpicture' => new external_value(PARAM_INT, 'Option to show the user\'s picture during the
                                                                    attempt and on the review page.', VALUE_OPTIONAL),
                'showblocks' => new external_value(PARAM_INT, 'Whether blocks should be shown on the attempt.php and
                                                                review.php pages.', VALUE_OPTIONAL),
                'completionattemptsexhausted' => new external_value(PARAM_INT, 'Mark quiz complete when the student has
                                                                                exhausted the maximum number of attempts',
                    VALUE_OPTIONAL),
                'completionpass' => new external_value(PARAM_INT, 'Whether to require passing grade', VALUE_OPTIONAL),
            )
        );
    }

    /**
     * Hanv 04/06/2016
     * Return a list of ids, load the basic information about a set of questions from the questions table.
     *
     * @return external_function_parameters
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     *
     */
    public static function get_mod_questions_by_quizid_parameters() {
        return new external_function_parameters(
            array('id' => new external_value(PARAM_INT, 'id'),
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
                    ), 'Options, used since Moodle 3.1', VALUE_DEFAULT, array())
            )
        );
    }

    /**
     * Return a list of ids, load the basic information about a set of questions from the questions table.
     *
     * @param int $id quizid
     * @param array $options Options for filtering the results, used since Moodle 3.1
     * @return array
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     */
    public static function get_mod_questions_by_quizid($id, $options = array()) {
        global $CFG, $DB;

        //validate parameter
        $params = self::validate_parameters(self::get_mod_questions_by_quizid_parameters(),
            array('id' => $id,'options' => $options));

        $questions = question_preload_questions(null,
            'slot.maxmark, slot.id AS slotid, slot.slot, slot.page',
            '{quiz_slots} slot ON slot.quizid = :quizid AND q.id = slot.questionid',
            array('quizid' => $params['id']), 'slot.slot');
        return $questions;
    }

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     */
    public static function get_mod_questions_by_quizid_returns() {
        return  new external_multiple_structure(
            new external_single_structure(
                array(
                    'id' => new external_value(PARAM_INT, 'Standard Moodle primary key.'),
                    'category' => new external_value(PARAM_INT, 'category',VALUE_OPTIONAL),
                    'parent' => new external_value(PARAM_INT, 'parent', VALUE_OPTIONAL),
                    'name' => new external_value(PARAM_RAW, 'Question name'),
                    'questiontext' => new external_value(PARAM_RAW, 'Question introduction text.', VALUE_OPTIONAL),
                    'questiontextformat' => new external_value(PARAM_INT, 'questiontext format.', VALUE_OPTIONAL),
                    'generalfeedback' => new external_value(PARAM_RAW, 'generalfeedback.', VALUE_OPTIONAL),
                    'generalfeedbackformat' => new external_value(PARAM_INT, 'general feedback format.', VALUE_OPTIONAL),
                    'defaultmark' => new external_value(PARAM_FLOAT, 'default mark.', VALUE_OPTIONAL),
                    'penalty' => new external_value(PARAM_FLOAT, 'penalty.', VALUE_OPTIONAL),
                    'qtype' => new external_value(PARAM_RAW, 'qtype', VALUE_OPTIONAL),
                    'length' => new external_value(PARAM_INT, 'length', VALUE_OPTIONAL),
                    'stamp' => new external_value(PARAM_RAW, 'stamp'),
                    'version' => new external_value(PARAM_RAW, 'Question version'),
                    'hidden' => new external_value(PARAM_INT, '	hidden', VALUE_OPTIONAL),
                    'timecreated' => new external_value(PARAM_INT, 'The time when the question was added to the question bank.', VALUE_OPTIONAL),
                    'timemodified' => new external_value(PARAM_INT, 'Last modified time.', VALUE_OPTIONAL),
                    'createdby' => new external_value(PARAM_INT, 'created by.', VALUE_OPTIONAL),
                    'modifiedby' => new external_value(PARAM_INT, 'modified by.', VALUE_OPTIONAL),
                    'contextid' => new external_value(PARAM_INT, 'comtext id.'),
                    'maxmark' => new external_value(PARAM_FLOAT, 'max mark.', VALUE_OPTIONAL),
                    'slotid' => new external_value(PARAM_INT, 'slot id.'),
                    'slot' => new external_value(PARAM_INT, 'slot.'),
                    'page' => new external_value(PARAM_INT, 'page.'),
                    '_partiallyloaded' => new external_value(PARAM_BOOL, '_partiallyloaded.'),
                )
            )
        );
    }

    /**
     * Hanv 06/06/2016
     * Return all the information about attempt by atemptid
     *
     * @return external_function_parameters
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     *
     */
    public static function get_mod_attempt_by_attemptid_parameters() {
        return new external_function_parameters(
            array('attemptid' => new external_value(PARAM_INT, 'attemptid'),
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
                    ), 'Options, used since Moodle 3.1', VALUE_DEFAULT, array()
                )
            )
        );
    }

    /**
     * Return all the information about attempt by atemptid
     *
     * @param int $attemptid attemptid
     * @param array $options Options for filtering the results, used since Moodle 3.1
     * @return array
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     */
    public static function get_mod_attempt_by_attemptid($attemptid, $options = array()) {
        global $CFG, $DB;

        //validate parameter
        $params = self::validate_parameters(self::get_mod_attempt_by_attemptid_parameters(),
            array('attemptid' => $attemptid,'options' => $options));
        // Get attempt by attemptid
        $attempt = $DB->get_record('quiz_attempts', array('id' => $params['attemptid']), '*', MUST_EXIST);
        return $attempt;
    }

    /**
     * Describes a single attempt structure.
     *
     * @return external_single_structure the attempt structure
     */
    public static function get_mod_attempt_by_attemptid_returns() {
        return new external_single_structure(
            array(
                'id' => new external_value(PARAM_INT, 'Attempt id.', VALUE_OPTIONAL),
                'quiz' => new external_value(PARAM_INT, 'Foreign key reference to the quiz that was attempted.',
                    VALUE_OPTIONAL),
                'userid' => new external_value(PARAM_INT, 'Foreign key reference to the user whose attempt this is.',
                    VALUE_OPTIONAL),
                'attempt' => new external_value(PARAM_INT, 'Sequentially numbers this students attempts at this quiz.',
                    VALUE_OPTIONAL),
                'uniqueid' => new external_value(PARAM_INT, 'Foreign key reference to the question_usage that holds the
                                                    details of the the question_attempts that make up this quiz
                                                    attempt.', VALUE_OPTIONAL),
                'layout' => new external_value(PARAM_RAW, 'Attempt layout.', VALUE_OPTIONAL),
                'currentpage' => new external_value(PARAM_INT, 'Attempt current page.', VALUE_OPTIONAL),
                'preview' => new external_value(PARAM_INT, 'Whether is a preview attempt or not.', VALUE_OPTIONAL),
                'state' => new external_value(PARAM_ALPHA, 'The current state of the attempts. \'inprogress\',
                                                \'overdue\', \'finished\' or \'abandoned\'.', VALUE_OPTIONAL),
                'timestart' => new external_value(PARAM_INT, 'Time when the attempt was started.', VALUE_OPTIONAL),
                'timefinish' => new external_value(PARAM_INT, 'Time when the attempt was submitted.
                                                    0 if the attempt has not been submitted yet.', VALUE_OPTIONAL),
                'timemodified' => new external_value(PARAM_INT, 'Last modified time.', VALUE_OPTIONAL),
                'timecheckstate' => new external_value(PARAM_INT, 'Next time quiz cron should check attempt for
                                                        state changes.  NULL means never check.', VALUE_OPTIONAL),
                'sumgrades' => new external_value(PARAM_FLOAT, 'Total marks for this attempt.', VALUE_OPTIONAL),
            )
        );
    }

    /**
     * Hanv 07/06/2016
     * Return all the information about question_usages
     *
     * @return external_function_parameters
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     *
     */
    public static function get_mod_load_questions_usage_by_activity_parameters() {
        return new external_function_parameters(
            array('unique' => new external_value(PARAM_INT, 'attempt unique'),
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
     * Return all the information about question_usages
     *
     * @param int $unique unique
     * @param array $options Options for filtering the results, used since Moodle 3.1
     * @return array
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     */
    public static function get_mod_load_questions_usage_by_activity($unique, $options = array()) {
        global $CFG, $DB;

        //validate parameter
        $params = self::validate_parameters(self::get_mod_load_questions_usage_by_activity_parameters(),
            array('unique' => $unique,'options' => $options));

        $records = $DB->get_recordset_sql("
SELECT
    quba.id AS qubaid,
    quba.contextid,
    quba.component,
    quba.preferredbehaviour,
    qa.id AS questionattemptid,
    qa.questionusageid,
    qa.slot,
    qa.behaviour,
    qa.questionid,
    qa.variant,
    qa.maxmark,
    qa.minfraction,
    qa.maxfraction,
    qa.flagged,
    qa.questionsummary,
    qa.rightanswer,
    qa.responsesummary,
    qa.timemodified,
    qas.id AS attemptstepid,
    qas.sequencenumber,
    qas.state,
    qas.fraction,
    qas.timecreated,
    qas.userid,
    qasd.name,
    qasd.value

FROM      {question_usages}            quba
LEFT JOIN {question_attempts}          qa   ON qa.questionusageid    = quba.id
LEFT JOIN {question_attempt_steps}     qas  ON qas.questionattemptid = qa.id
LEFT JOIN {question_attempt_step_data} qasd ON qasd.attemptstepid    = qas.id

WHERE
    quba.id = :qubaid

ORDER BY
    qa.slot,
    qas.sequencenumber
    ", array('qubaid' => $params['unique']));

        $result = array();
        while ($records->valid()) {
            $result[] = $records->current();
            $records->next();
        }
        return $result;
    }

    /**
     * Describes a single attempt structure.
     *
     * @return external_single_structure the attempt structure
     */
    public static function get_mod_load_questions_usage_by_activity_returns() {
        return new external_multiple_structure(
			new external_single_structure(
				array(
					'qubaid' => new external_value(PARAM_INT, 'question_usages id.',
						VALUE_OPTIONAL),
					'contextid' => new external_value(PARAM_INT, 'context id in question_usages.',
						VALUE_OPTIONAL),
					'component' => new external_value(PARAM_RAW, 'component in question_usages.',
						VALUE_OPTIONAL),
					'preferredbehaviour' => new external_value(PARAM_RAW, 'preferredbehaviour in question_usages.',
						VALUE_OPTIONAL),
					'questionattemptid' => new external_value(PARAM_INT, 'questionattemptid is question_attempt id.',
						VALUE_OPTIONAL),
					'questionusageid' => new external_value(PARAM_INT, 'questionusageid.',
						VALUE_OPTIONAL),
					'slot' => new external_value(PARAM_INT, 'slot.', VALUE_OPTIONAL),
					'behaviour' => new external_value(PARAM_RAW, 'behaviour.',
						VALUE_OPTIONAL),
					'questionid' => new external_value(PARAM_INT, 'question id.', VALUE_OPTIONAL),
					'variant' => new external_value(PARAM_INT, 'variant.',
						VALUE_OPTIONAL),
					'maxmark' => new external_value(PARAM_FLOAT, 'max mark.',
						VALUE_OPTIONAL),
					'minfraction' => new external_value(PARAM_FLOAT, 'min fraction.',
						VALUE_OPTIONAL),
					'maxfraction' => new external_value(PARAM_FLOAT, 'max fraction.', VALUE_OPTIONAL),
					'flagged' => new external_value(PARAM_INT, 'flagged.',
						VALUE_OPTIONAL),
					'questionsummary' => new external_value(PARAM_RAW, 'question summary.',
						VALUE_OPTIONAL),
					'rightanswer' => new external_value(PARAM_RAW, 'right answer.',
						VALUE_OPTIONAL),
					'responsesummary' => new external_value(PARAM_RAW, 'response summary.',
						VALUE_OPTIONAL),
					'timemodified' => new external_value(PARAM_INT, 'Last modified time.', VALUE_OPTIONAL),
					'attemptstepid' => new external_value(PARAM_INT, 'question_attempt_steps id.', VALUE_OPTIONAL),
					'sequencenumber' => new external_value(PARAM_INT, 'sequence number.', VALUE_OPTIONAL),
					'state' => new external_value(PARAM_RAW, 'state.', VALUE_OPTIONAL),
					'fraction' => new external_value(PARAM_FLOAT, 'fraction.', VALUE_OPTIONAL),
					'timecreated' => new external_value(PARAM_INT, 'The time create.',
						VALUE_OPTIONAL),
					'userid' => new external_value(PARAM_INT, 'user id.', VALUE_OPTIONAL),
					'name' => new external_value(PARAM_RAW, 'name.', VALUE_OPTIONAL),
					'value' => new external_value(PARAM_RAW, 'value.', VALUE_OPTIONAL),
				)
			)
        );
    }

    /**
     * Hanv 07/06/2016
     * Return all the information about slots by quizid
     *
     * @return external_function_parameters
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     *
     */
    public static function get_mod_slots_by_quizid_parameters() {
        return new external_function_parameters(
            array('quizid' => new external_value(PARAM_INT, 'quiz id'),
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
                    ), 'Options, used since Moodle 3.1', VALUE_DEFAULT, array())
            )
        );
    }

    /**
     * Return all the information about slots by quizid
     *
     * @param int $quizid quizid
     * @param array $options Options for filtering the results, used since Moodle 3.1
     * @return array
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     */
    public static function get_mod_slots_by_quizid($quizid, $options = array()) {
        global $CFG, $DB;

        //validate parameter
        $params = self::validate_parameters(self::get_mod_slots_by_quizid_parameters(),
            array('quizid' => $quizid,'options' => $options));
        // Get slots by quizid
        $slots = $DB->get_records('quiz_slots', array('quizid' => $params['quizid']), 'slot', 'slot, requireprevious, questionid');
        return $slots;
    }

    /**
     * Describes a single attempt structure.
     *
     * @return external_single_structure the attempt structure
     */
    public static function get_mod_slots_by_quizid_returns() {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'slot' => new external_value(PARAM_INT, 'slot.',
                        VALUE_OPTIONAL),
                    'requireprevious' => new external_value(PARAM_INT, 'requireprevious.',
                        VALUE_OPTIONAL),
                    'questionid' => new external_value(PARAM_RAW, 'question id.',
                        VALUE_OPTIONAL),
                )
            )
        );
    }

    /**
     * Hanv 07/06/2016
     * Return all the information about sections by quizid
     *
     * @return external_function_parameters
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     *
     */
    public static function get_mod_sections_by_quizid_parameters() {
        return new external_function_parameters(
            array('quizid' => new external_value(PARAM_INT, 'quiz id'),
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
                    ), 'Options, used since Moodle 3.1', VALUE_DEFAULT, array())
            )
        );
    }

    /**
     * Return all the information about sections by quizid
     *
     * @param int $quizid quizid
     * @param array $options Options for filtering the results, used since Moodle 3.1
     * @return array
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     */
    public static function get_mod_sections_by_quizid($quizid, $options = array()) {
        global $CFG, $DB;

        //validate parameter
        $params = self::validate_parameters(self::get_mod_sections_by_quizid_parameters(),
            array('quizid' => $quizid,'options' => $options));
        // Get sections by quizid
        $sections = array_values($DB->get_records('quiz_sections', array('quizid' => $params['quizid']), 'firstslot'));
        return $sections;
    }

    /**
     * Describes a single attempt structure.
     *
     * @return external_single_structure
     */
    public static function get_mod_sections_by_quizid_returns() {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'id' => new external_value(PARAM_INT, 'id.',
                        VALUE_OPTIONAL),
                    'quizid' => new external_value(PARAM_INT, 'quiz id.',
                        VALUE_OPTIONAL),
                    'firstslot' => new external_value(PARAM_INT, 'firstslot.',
                        VALUE_OPTIONAL),
                    'heading' => new external_value(PARAM_RAW, 'heading.',
                        VALUE_OPTIONAL),
                    'shufflequestions' => new external_value(PARAM_INT, 'shuffle questions.',
                        VALUE_OPTIONAL),
                )
            )
        );
    }
		

	/**
     * Describes the parameters for start_attempt.
     *
     * @return external_external_function_parameters
     * @since Moodle 3.1
     */
    public static function start_remote_attempt_parameters() {
        return new external_function_parameters (
            array(
                'quizid' => new external_value(PARAM_INT, 'quiz instance id'),
				'remoteuserid' => new external_value(PARAM_INT, 'remote user id'),
                'preview' => new external_value(PARAM_BOOL, 'has_capability(\'mod/quiz:preview\') in host.', VALUE_DEFAULT, false),
                'preflightdata' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_ALPHANUMEXT, 'data name'),
                            'value' => new external_value(PARAM_RAW, 'data value'),
                        )
                    ), 'Preflight required data (like passwords)', VALUE_DEFAULT, array()
                ),
                'forcenew' => new external_value(PARAM_BOOL, 'Whether to force a new attempt or not.', VALUE_DEFAULT, false),
                'setting' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'data name'),
                            'value' => new external_value(PARAM_RAW, 'data value'),
                        )
                    ), 'Local quiz setting (like: timelimit, timeopen ...)', VALUE_DEFAULT, array()
                ),
            )
        );
    }

    /**
     * Starts a new attempt at a quiz.
     *
     * @param int $quizid quiz instance id
     * @param array $preflightdata preflight required data (like passwords)
     * @param bool $forcenew Whether to force a new attempt or not.
     * @return array of warnings and the attempt basic data
     * @since Moodle 3.1
     * @throws moodle_quiz_exception
     */
    public static function start_remote_attempt($quizid, $remoteuserid, $preview, $preflightdata = array(), $forcenew = false, $setting = array()) {
        global $DB, $USER;

        $warnings = array();
        $attempt = array();

        $params = array(
            'quizid' => $quizid,
	        'remoteuserid' => $remoteuserid,
            'preview' => $preview,
            'preflightdata' => $preflightdata,
            'forcenew' => $forcenew,
            'setting' => $setting,
        );
        $params = self::validate_parameters(self::start_remote_attempt_parameters(), $params);
        $forcenew = $params['forcenew'];
        list($quiz, $course, $cm, $context) = mod_quiz_external::validate_quiz($params['quizid']);
        
        if($params['setting']){
            $localsetting = array();
            foreach ($params['setting'] as $element) {
                $localsetting[$element['name']] = $element['value'];
            }
        }
        $quizobj = quiz::create($cm->instance, $remoteuserid, $localsetting);
        // Check questions.
        if (!$quizobj->has_questions()) {
            throw new moodle_quiz_exception($quizobj, 'noquestionsfound');
        }

        // Create an object to manage all the other (non-roles) access rules.
        $timenow = time();
        $accessmanager = $quizobj->get_access_manager($timenow);

        // Validate permissions for creating a new attempt and start a new preview attempt if required.
        list($currentattemptid, $attemptnumber, $lastattempt, $messages, $page) =
            quiz_validate_new_attempt($quizobj, $accessmanager, $forcenew, -1, false, $remoteuserid, $preview);

        // Check access.
        $ispreview = ($preview === true)?$preview:$quizobj->is_preview_user();
        if (!$ispreview && $messages) {
            // Create warnings with the exact messages.
            foreach ($messages as $message) {
                $warnings[] = array(
                    'item' => 'quiz',
                    'itemid' => $quiz->id,
                    'warningcode' => '1',
                    'message' => clean_text($message, PARAM_TEXT)
                );
            }
        } else {
            if ($accessmanager->is_preflight_check_required($currentattemptid)) {
                // Need to do some checks before allowing the user to continue.

                $provideddata = array();
                foreach ($params['preflightdata'] as $data) {
                    $provideddata[$data['name']] = $data['value'];
                }

                $errors = $accessmanager->validate_preflight_check($provideddata, [], $currentattemptid);

                if (!empty($errors)) {
                    throw new moodle_quiz_exception($quizobj, array_shift($errors));
                }

                // Pre-flight check passed.
                $accessmanager->notify_preflight_check_passed($currentattemptid);
            }

            if ($currentattemptid) {
                if ($lastattempt->state == quiz_attempt::OVERDUE) {
                    throw new moodle_quiz_exception($quizobj, 'stateoverdue');
                } else {
                    throw new moodle_quiz_exception($quizobj, 'attemptstillinprogress');
                }
            }
            $attempt = quiz_prepare_and_start_new_attempt($quizobj, $attemptnumber, $lastattempt, $remoteuserid, $preview);
        }

        $result = array();
        $result['attempt'] = $attempt;
        $result['warnings'] = $warnings;
        return $result;
    }

    /**
     * Describes the start_attempt return value.
     *
     * @return external_single_structure
     * @since Moodle 3.1
     */
    public static function start_remote_attempt_returns() {
        return new external_single_structure(
            array(
                'attempt' => mod_quiz_external::attempt_structure(),
                'warnings' => new external_warnings(),
            )
        );
    }

    /**
     * Hanv 30/06/2016
     * Show quiz number of attempts summary to those who can view report.
     *
     * @return external_function_parameters
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     *
     */
    public static function count_attempt_summary_parameters() {
        return new external_function_parameters (
            array(
                'quizid' => new external_value(PARAM_INT, 'quiz instance id'),
                'ipaddress' => new external_value(PARAM_RAW, 'host ipaddress'),
            )
        );
    }

    /**
     * Show quiz number of attempts summary to those who can view report.
     *
     * @param int $quizid quizid
     * @param string $ipaddress ipaddress
     * @return array
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     */
    public static function count_attempt_summary($quizid, $ipaddress) {
        global $CFG, $DB;

        //validate parameter
        $params = self::validate_parameters(self::count_attempt_summary_parameters(),
            array('quizid' => $quizid,'ipaddress' => $ipaddress));
        // Get mnethostID from host ipaddress
        $mnethostid = $DB->get_record('mnet_host', array('ip_address' => $params['ipaddress']), '*');

        //get numattempt of quiz with users in host ipaddress
        $sql = " SELECT COUNT(*) num
                    FROM {quiz_attempts} qa
                    JOIN {user} u ON qa.userid = u.id
                 WHERE qa.quiz=:quizid AND qa.preview=0 AND u.mnethostid=:mnetid";
        $numattempts = $DB->get_record_sql($sql, array('quizid' => $params['quizid'], 'mnetid' => $mnethostid->id));
        return $numattempts;
    }

    /**
     * Returns description of method parameters
     *
     * @return external_single_structure
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     */
    public static function count_attempt_summary_returns() {
        return new external_single_structure(
            array(
                'num' => new external_value(PARAM_INT, 'num attempts', VALUE_OPTIONAL),
            )
        );
    }

    /**
     * Hanv 02/07/2016
     * Get the slots of real questions (not descriptions) in this quiz, in order.
     *
     * @return external_function_parameters
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     *
     */
    public static function report_get_significant_questions_parameters() {
        return new external_function_parameters (
            array(
                'quizid' => new external_value(PARAM_INT, 'quiz id'),
            )
        );
    }

    /**
     * Get the slots of real questions (not descriptions) in this quiz, in order.
     *
     * @param int $quizid quizid
     * @return array
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     */
    public static function report_get_significant_questions($quizid) {
        global $DB;

        //validate parameter
        $params = self::validate_parameters(self::report_get_significant_questions_parameters(),
            array('quizid' => $quizid));

        $qsbyslot = $DB->get_records_sql("
            SELECT slot.slot,
                   q.id,
                   q.length,
                   slot.maxmark

              FROM {question} q
              JOIN {quiz_slots} slot ON slot.questionid = q.id

             WHERE slot.quizid = ?
               AND q.length > 0

          ORDER BY slot.slot", array($params['quizid']));

        $number = 1;
        foreach ($qsbyslot as $question) {
            $question->number = $number;
            $number += $question->length;
        }
        return $qsbyslot;
    }

    /**
     * Returns description of method parameters
     *
     * @return external_multiple_structure
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     */
    public static function report_get_significant_questions_returns() {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'slot' => new external_value(PARAM_INT, 'slot.'),
                    'id' => new external_value(PARAM_INT, 'Standard Moodle primary key.'),
                    'length' => new external_value(PARAM_INT, 'length', VALUE_OPTIONAL),
                    'maxmark' => new external_value(PARAM_FLOAT, 'max mark.', VALUE_OPTIONAL),
                    'number' => new external_value(PARAM_INT, 'number', VALUE_OPTIONAL),
                )
            )
        );
    }

    /**
     * Hanv 04/07/2016
     * Querry db with sql params to get grand total.
     *
     * @return external_function_parameters
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     *
     */
    public static function get_grand_total_parameters() {
        return new external_function_parameters (
            array(
                'countsql' => new external_value(PARAM_RAW, 'countsql'),
                'countparam' => new  external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'name'),
                            'value' => new external_value(PARAM_RAW, 'value'),
                        )
                    )
                ),
            )
        );
    }

    /**
     * Querry db with sql params to get grand total.
     *
     * @param int $quizid quizid
     * @param string $ipaddress ipaddress
     * @return array
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     */
    public static function get_grand_total($countsql, $countparam) {
        global $CFG, $DB;

        //validate parameter
        $params = self::validate_parameters(self::get_grand_total_parameters(),
            array('countsql' => $countsql, 'countparam' => $countparam));

        $branch = array();
        foreach ($params['countparam'] as $element) {
            $branch[$element['name']] = $element['value'];
        }

        $grandtotal = $DB->count_records_sql($params['countsql'], $branch);
        $res = array();
        $res['grandtotal'] = $grandtotal;
        return $res;
    }

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     */
    public static function get_grand_total_returns() {
        return new external_single_structure(
            array(
                'grandtotal' => new external_value(PARAM_INT, 'grand total', VALUE_OPTIONAL),
            )
        );
    }

    /**
     * Hanv 04/07/2016
     * Querry db with sql params to get grand total.
     *
     * @return external_function_parameters
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     *
     */
    public static function get_rowdata_for_tableview_parameters() {
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
     * Querry db with sql params to get grand total.
     *
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     */
    public static function get_rowdata_for_tableview($sql, $param, $pagestart, $pagesize) {
        global $CFG, $DB;

        //validate parameter
        $params = self::validate_parameters(self::get_rowdata_for_tableview_parameters(),
            array('sql' => $sql, 'param' => $param, 'pagestart' => $pagestart, 'pagesize' => $pagesize));

        $branch = array();
        foreach ($params['param'] as $element) {
            $branch[$element['name']] = $element['value'];
        }

        $rowdata = $DB->get_records_sql($params['sql'], $branch, $params['pagestart'], $params['pagesize']);
        return $rowdata;
    }

    /**
     * Returns description of method parameters
     *
     * @return external_multiple_structure
     */
    public static function get_rowdata_for_tableview_returns() {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'uniqueid' => new external_value(PARAM_RAW, 'grand total', VALUE_OPTIONAL),
                    'gradedattempt' => new external_value(PARAM_INT, 'graded attempt', VALUE_OPTIONAL),
                    'usageid' => new external_value(PARAM_INT, 'usageid', VALUE_OPTIONAL),
                    'attempt' => new external_value(PARAM_INT, 'attempt id', VALUE_OPTIONAL),
                    'userid' => new external_value(PARAM_INT, 'userid', VALUE_OPTIONAL),
                    'idnumber' => new external_value(PARAM_RAW, 'idnumber', VALUE_OPTIONAL),
                    'firstnamephonetic' => new external_value(PARAM_RAW, 'firstname phonetic', VALUE_OPTIONAL),
                    'lastnamephonetic' => new external_value(PARAM_RAW, 'lastname phonetic', VALUE_OPTIONAL),
                    'middlename' => new external_value(PARAM_RAW, 'middlename', VALUE_OPTIONAL),
                    'alternatename' => new external_value(PARAM_RAW, 'alternate name', VALUE_OPTIONAL),
                    'firstname' => new external_value(PARAM_RAW, 'first name', VALUE_OPTIONAL),
                    'lastname' => new external_value(PARAM_RAW, 'last name', VALUE_OPTIONAL),
                    'picture' => new external_value(PARAM_RAW, 'picture', VALUE_OPTIONAL),
                    'imagealt' => new external_value(PARAM_RAW, 'image alt', VALUE_OPTIONAL),
                    'institution' => new external_value(PARAM_RAW, 'institution', VALUE_OPTIONAL),
                    'department' => new external_value(PARAM_RAW, 'department', VALUE_OPTIONAL),
                    'email' => new external_value(PARAM_RAW, 'email', VALUE_OPTIONAL),
                    'state' => new external_value(PARAM_RAW, 'state', VALUE_OPTIONAL),
                    'sumgrades' => new external_value(PARAM_FLOAT, 'sum grades', VALUE_OPTIONAL),
                    'timefinish' => new external_value(PARAM_INT, 'timefinish', VALUE_OPTIONAL),
                    'timestart' => new external_value(PARAM_INT, 'timestart', VALUE_OPTIONAL),
                    'duration' => new external_value(PARAM_INT, 'duration', VALUE_OPTIONAL),
                    'regraded' => new external_value(PARAM_INT, 'regraded', VALUE_OPTIONAL),
                )
            )
        );
    }

    /**
     * Hanv 07/07/2016
     * Load information about the latest state of each question from the database.
     *
     * @return external_function_parameters
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     *
     */
    public static function load_questions_usages_latest_steps_parameters() {
        return new external_function_parameters(
            array(
                'qubaids' => new external_multiple_structure(new external_value(PARAM_INT, 'quba ID')),
                'questions' => new external_multiple_structure(new external_value(PARAM_INT, 'questions')),
                'fields' => new external_value(PARAM_RAW, 'fields', VALUE_DEFAULT, null),
            )
        );
    }

    /**
     * Load information about the latest state of each question from the database.
     *
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     */
    public static function load_questions_usages_latest_steps($qubaids, $questions, $fields = null) {
        global $CFG, $DB;

        $params = self::validate_parameters(self::load_questions_usages_latest_steps_parameters(),
            array('qubaids' => $qubaids, 'questions' => $questions, 'fields' => $fields));
        $qubavalues = array();
        foreach ($qubaids as $qubaid) {
            $qubavalues[] = $qubaid;
        }

        $quesvalues = array();
        foreach ($questions as $question) {
            $quesvalues[$question] = $question;
        }
        
        $qubaids = new qubaid_list($qubavalues);
        
        $dm = new question_engine_data_mapper();
        $latesstepdata = $dm->load_questions_usages_latest_steps(
            $qubaids, array_keys($quesvalues), $fields);
        return $latesstepdata;
    }

    /**
     * Describes a single attempt structure.
     *
     * @return external_multiple_structure
     */
    public static function load_questions_usages_latest_steps_returns() {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'id' => new external_value(PARAM_INT, 'id.',
                        VALUE_OPTIONAL),
                    'questionattemptid' => new external_value(PARAM_INT, 'questionattemptid is question_attempt id.',
                        VALUE_OPTIONAL),
                    'questionusageid' => new external_value(PARAM_INT, 'questionusageid.',
                        VALUE_OPTIONAL),
                    'slot' => new external_value(PARAM_INT, 'slot.', VALUE_OPTIONAL),
                    'behaviour' => new external_value(PARAM_RAW, 'behaviour.',
                        VALUE_OPTIONAL),
                    'questionid' => new external_value(PARAM_INT, 'question id.', VALUE_OPTIONAL),
                    'variant' => new external_value(PARAM_INT, 'variant.',
                        VALUE_OPTIONAL),
                    'maxmark' => new external_value(PARAM_FLOAT, 'max mark.',
                        VALUE_OPTIONAL),
                    'minfraction' => new external_value(PARAM_FLOAT, 'min fraction.',
                        VALUE_OPTIONAL),
                    'maxfraction' => new external_value(PARAM_FLOAT, 'max fraction.', VALUE_OPTIONAL),
                    'flagged' => new external_value(PARAM_INT, 'flagged.',
                        VALUE_OPTIONAL),
                    'questionsummary' => new external_value(PARAM_RAW, 'question summary.',
                        VALUE_OPTIONAL),
                    'rightanswer' => new external_value(PARAM_RAW, 'right answer.',
                        VALUE_OPTIONAL),
                    'responsesummary' => new external_value(PARAM_RAW, 'response summary.',
                        VALUE_OPTIONAL),
                    'timemodified' => new external_value(PARAM_INT, 'Last modified time.', VALUE_OPTIONAL),
                    'attemptstepid' => new external_value(PARAM_INT, 'question_attempt_steps id.', VALUE_OPTIONAL),
                    'sequencenumber' => new external_value(PARAM_INT, 'sequence number.', VALUE_OPTIONAL),
                    'state' => new external_value(PARAM_RAW, 'state.', VALUE_OPTIONAL),
                    'fraction' => new external_value(PARAM_FLOAT, 'fraction.', VALUE_OPTIONAL),
                    'timecreated' => new external_value(PARAM_INT, 'The time create.',
                        VALUE_OPTIONAL),
                    'userid' => new external_value(PARAM_INT, 'user id.', VALUE_OPTIONAL)
                )
            )
        );
    }

    /**
     * Hanv 08/07/2016
     * Get average grade and count numaverage for table view.
     *
     * @return external_function_parameters
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     *
     */
    public static function get_report_avg_record_parameters() {
        return new external_function_parameters (
            array(
                'from' => new external_value(PARAM_RAW, 'from'),
                'where' => new external_value(PARAM_RAW, 'where'),
                'questions' => new external_multiple_structure(new external_value(PARAM_INT, 'questions')),
                'param' => new  external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'name'),
                            'value' => new external_value(PARAM_INT, 'value'),
                        )
                    )
                ),
            )
        );
    }

    /**
     * Get average grade and count numaverage for table view.
     *
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     */
    public static function get_report_avg_record($from, $where, $questions, $param) {
        global $CFG, $DB;

        //validate parameter
        $params = self::validate_parameters(self::get_report_avg_record_parameters(),
            array('from' => $from, 'where' => $where, 'questions' => $questions, 'param' => $param));

        $branch = array();
        foreach ($params['param'] as $element) {
            $branch[$element['name']] = $element['value'];
        }

        $quesvalues = array();
        foreach ($questions as $question) {
            $quesvalues[$question] = $question;
        }

        $record = $DB->get_record_sql("
                SELECT AVG(quiza.sumgrades) AS grade, COUNT(quiza.sumgrades) AS numaveraged
                  FROM $from
                 WHERE $where", $branch);

        $dm = new question_engine_data_mapper();
        $qubaids = new qubaid_join($from, 'quiza.uniqueid', $where, $branch);
        $avggradebyq = $dm->load_average_marks($qubaids, array_keys($quesvalues));
        $result = array();
        $result['record'] = $record;
        $result['avggradebyq'] = $avggradebyq;
        return $result;
    }

    /**
     * Returns description of method parameters
     *
     * @return external_single_structure
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     */
    public static function get_report_avg_record_returns() {
        return new external_single_structure(
            array(
                'record' => new external_single_structure(
                    array(
                        'grade' => new external_value(PARAM_FLOAT, 'grand total', VALUE_OPTIONAL),
                        'numaveraged' => new external_value(PARAM_INT, 'graded attempt', VALUE_OPTIONAL),
                    )
                ),
                'avggradebyq' => new  external_multiple_structure(
                    new external_single_structure(
                        array(
                            'slot' => new external_value(PARAM_INT, 'slot'),
                            'averagefraction' => new external_value(PARAM_FLOAT, 'averagefraction'),
                            'numaveraged' => new external_value(PARAM_INT, 'numaveraged'),
                        )
                    )
                )
            )
        );
    }

    /**
     * Hanv 08/07/2016
     * Check exist quiz grade record in DB.
     *
     * @return external_function_parameters
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     *
     */
    public static function check_exist_quiz_grade_parameters() {
        return new external_function_parameters (
            array(
                'quizid' => new external_value(PARAM_INT, 'quizid'),
            )
        );
    }

    /**
     * Check exist quiz grade record in DB.
     *
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     */
    public static function check_exist_quiz_grade($quizid) {
        global $CFG, $DB;

        //validate parameter
        $params = self::validate_parameters(self::check_exist_quiz_grade_parameters(),
            array('quizid' => $quizid));
        $checkgrade = $DB->record_exists('quiz_grades', array('quiz'=> $params['quizid']));
        $result = array('check' => $checkgrade);
        return $result;
    }

    /**
     * Returns description of method parameters
     *
     * @return external_single_structure
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     */
    public static function check_exist_quiz_grade_returns() {
        return new external_single_structure(
            array(
                'check' => new external_value(PARAM_BOOL, 'check quiz grade', VALUE_OPTIONAL),
            )
        );
    }

    /**
     * Hanv 08/07/2016
     * get grade bands data by sql and param from host.
     *
     * @return external_function_parameters
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     *
     */
    public static function get_grade_bands_parameters() {
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
                )
            )
        );
    }

    /**
     * Check exist quiz grade record in DB.
     *
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     */
    public static function get_grade_bands($sql, $param) {
        global $CFG, $DB;

        //validate parameter
        $params = self::validate_parameters(self::get_grade_bands_parameters(),
            array('sql' => $sql, 'param' => $param));
        $branch = array();
        foreach ($params['param'] as $element) {
            $branch[$element['name']] = $element['value'];
        }

        $data = $DB->get_records_sql_menu($sql, $branch);
        $result = array();
        $index = 0;
        foreach ($data as $key => $val){
            $result[$index]["key"]=$key;
            $result[$index]["value"]=$val;
            $index++;
        }
        return $result;
    }

    /**
     * Returns description of method parameters
     *
     * @return external_single_structure
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     */
    public static function get_grade_bands_returns() {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'key' => new external_value(PARAM_RAW, 'key', VALUE_OPTIONAL),
                    'value' => new external_value(PARAM_RAW, 'value', VALUE_OPTIONAL),
                )
            )
        );
    }

    /**
     * Hanv 11/07/2016
     * Load information about the number of attempts at various questions in each summarystate.
     *
     * @return external_function_parameters
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     *
     */
    public static function load_questions_usages_question_state_summary_parameters() {
        return new external_function_parameters(
            array(
                'questions' => new external_multiple_structure(new external_value(PARAM_INT, 'questions')),
                'param' => new  external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'name'),
                            'value' => new external_value(PARAM_RAW, 'value'),
                        )
                    )
                ),
                'where' => new external_value(PARAM_RAW, 'where'),
            )
        );
    }

    /**
     * Load information about the latest state of each question from the database.
     *
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     */
    public static function load_questions_usages_question_state_summary($questions, $params, $where) {
        global $CFG, $DB;

        $params = self::validate_parameters(self::load_questions_usages_question_state_summary_parameters(),
            array('questions' => $questions, 'param' => $params, 'where' => $where));

        $quesvalues = array();
        foreach ($questions as $question) {
            $quesvalues[$question] = $question;
        }

        $paramdata = array();
        foreach ($params['param'] as $element) {
            $paramdata[$element['name']] = $element['value'];
        }

        $qubaids = new qubaid_join('{quiz_attempts} quiza', 'quiza.uniqueid', $where, $paramdata);
        $dm = new question_engine_data_mapper();
        $statecounts = $dm->load_questions_usages_question_state_summary(
            $qubaids, array_keys($quesvalues));
        return $statecounts;
    }

    /**
     * Describes a single attempt structure.
     *
     * @return external_multiple_structure
     */
    public static function load_questions_usages_question_state_summary_returns() {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'slot' => new external_value(PARAM_INT, 'slot.', VALUE_OPTIONAL),
                    'questionid' => new external_value(PARAM_INT, 'question id.', VALUE_OPTIONAL),
                    'name' => new external_value(PARAM_RAW, 'name.', VALUE_OPTIONAL),
                    'inprogress' => new external_value(PARAM_BOOL, 'inprogress.', VALUE_OPTIONAL),
                    'needsgrading' => new external_value(PARAM_RAW, 'needsgrading.', VALUE_OPTIONAL),
                    'autograded' => new external_value(PARAM_RAW, 'autograded.', VALUE_OPTIONAL),
                    'manuallygraded' => new external_value(PARAM_RAW, 'manuallygraded.', VALUE_OPTIONAL),
                    'all' => new external_value(PARAM_RAW, 'all.', VALUE_OPTIONAL)
                )
            )
        );
    }

    /**
     * Hanv 19/07/2016
     * Get a list of usage ids where the question with slot. Also return the total count of such states.
     *
     * @return external_function_parameters
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     *
     */
    public static function load_questions_usages_where_question_in_state_parameters() {
        return new external_function_parameters(
            array(
                'param' => new  external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'name'),
                            'value' => new external_value(PARAM_RAW, 'value'),
                        )
                    )
                ),
                'where' => new external_value(PARAM_RAW, 'where'),
                'summarystate' => new external_value(PARAM_RAW, 'summarystate'),
                'slot' => new external_value(PARAM_RAW, 'slot'),
                'questionid' => new external_value(PARAM_RAW, 'questionid'),
                'orderby' => new external_value(PARAM_RAW, 'orderby'),
                'limitfrom' => new external_value(PARAM_RAW, 'limitfrom'),
                'pagesize' => new external_value(PARAM_RAW, 'pagesize'),
            )
        );
    }

    /**
     * Get a list of usage ids where the question with slot. Also return the total count of such states.
     *
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     */
    public static function load_questions_usages_where_question_in_state($qubaparam, $qubawhere, $summarystate,
                                                                         $slot, $questionid, $orderby, $limitfrom, $pagesize) {
        global $CFG, $DB;

        $params = self::validate_parameters(self::load_questions_usages_where_question_in_state_parameters(),
            array('param' => $qubaparam, 'where' => $qubawhere, 'summarystate' => $summarystate, 'slot' => $slot, 'questionid' => $questionid,
                'orderby' => $orderby, 'limitfrom' => $limitfrom, 'pagesize' => $pagesize));

        $paramdata = array();
        foreach ($qubaparam as $element) {
            $paramdata[$element['name']] = $element['value'];
        }
        
        $qubaids = new qubaid_join('{quiz_attempts} quiza', 'quiza.uniqueid', $qubawhere, $paramdata);
        $dm = new question_engine_data_mapper();
        $params = array();
        if ($orderby == 'date') {
            list($statetest, $params) = $dm->in_summary_state_test(
                'manuallygraded', false, 'mangrstate');
            $orderby = "(
                    SELECT MAX(sortqas.timecreated)
                    FROM {question_attempt_steps} sortqas
                    WHERE sortqas.questionattemptid = qa.id
                        AND sortqas.state $statetest
                    )";
        } else if ($orderby == 'studentfirstname' || $orderby == 'studentlastname' || $orderby == 'idnumber') {
            $qubaids->from .= " JOIN {user} u ON quiza.userid = u.id ";
            // For name sorting, map orderby form value to
            // actual column names; 'idnumber' maps naturally
            switch ($orderby) {
                case "studentlastname":
                    $orderby = "u.lastname, u.firstname";
                    break;
                case "studentfirstname":
                    $orderby = "u.firstname, u.lastname";
                    break;
            }
        }
        $result = $dm->load_questions_usages_where_question_in_state($qubaids, $summarystate,
            $slot, $questionid, $orderby, $params, $limitfrom, $pagesize);
        $res = array();
        $res['qubaids'] = $result[0];
        $res['count'] = $result[1];
        return $res;
    }

    /**
     * Describes a single attempt structure.
     *
     * @return external_multiple_structure
     */
    public static function load_questions_usages_where_question_in_state_returns() {
        return new external_single_structure(
            array(
                'qubaids' => new external_multiple_structure(new external_value(PARAM_INT, 'qubaids')),
                'count' =>  new external_value(PARAM_INT, 'count')
            )
        );
    }

    /**
     * Hanv 19/07/2016
     * Get grading attempts data by select fields, where asql, params.
     *
     * @return external_function_parameters
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     *
     */
    public static function grading_get_remote_attempts_byid_parameters() {
        return new external_function_parameters(
            array(
                'param' => new external_multiple_structure(new external_value(PARAM_RAW, 'param')),
                'asql' => new external_value(PARAM_RAW, 'asql'),
                'fields' => new external_value(PARAM_RAW, 'fields'),
            )
        );
    }

    /**
     * Get grading attempts data by select fields, where asql, params.
     *
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     */
    public static function grading_get_remote_attempts_byid($r_params, $asql, $fields) {
        global $CFG, $DB;

        $params = self::validate_parameters(self::grading_get_remote_attempts_byid_parameters(),
            array('param' => $r_params, 'fields' => $fields, 'asql' => $asql));

        $paramdata = array();
        foreach ($r_params as $element) {
            $paramdata[] = $element;
        }
        $attemptsbyid = $DB->get_records_sql("
                SELECT $fields
                FROM {quiz_attempts} quiza
                JOIN {user} u ON u.id = quiza.userid
                WHERE quiza.uniqueid $asql AND quiza.state = ? AND quiza.quiz = ?",
            $paramdata);
        return $attemptsbyid;
    }

    /**
     * Describes a single attempt structure.
     *
     * @return external_multiple_structure
     */
    public static function grading_get_remote_attempts_byid_returns() {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'id' => new external_value(PARAM_INT, 'Attempt id.', VALUE_OPTIONAL),
                    'quiz' => new external_value(PARAM_INT, 'Foreign key reference to the quiz that was attempted.', VALUE_OPTIONAL),
                    'userid' => new external_value(PARAM_INT, 'Foreign key reference to the user whose attempt this is.', VALUE_OPTIONAL),
                    'attempt' => new external_value(PARAM_INT, 'Sequentially numbers this students attempts at this quiz.', VALUE_OPTIONAL),
                    'uniqueid' => new external_value(PARAM_INT, 'Foreign key reference to the question_usage that holds the
                                                    details of the the question_attempts that make up this quiz
                                                    attempt.', VALUE_OPTIONAL),
                    'layout' => new external_value(PARAM_RAW, 'Attempt layout.', VALUE_OPTIONAL),
                    'currentpage' => new external_value(PARAM_INT, 'Attempt current page.', VALUE_OPTIONAL),
                    'preview' => new external_value(PARAM_INT, 'Whether is a preview attempt or not.', VALUE_OPTIONAL),
                    'state' => new external_value(PARAM_ALPHA, 'The current state of the attempts. \'inprogress\',
                                                \'overdue\', \'finished\' or \'abandoned\'.', VALUE_OPTIONAL),
                    'timestart' => new external_value(PARAM_INT, 'Time when the attempt was started.', VALUE_OPTIONAL),
                    'timefinish' => new external_value(PARAM_INT, 'Time when the attempt was submitted.
                                                    0 if the attempt has not been submitted yet.', VALUE_OPTIONAL),
                    'timemodified' => new external_value(PARAM_INT, 'Last modified time.', VALUE_OPTIONAL),
                    'timecheckstate' => new external_value(PARAM_INT, 'Next time quiz cron should check attempt for
                                                        state changes.  NULL means never check.', VALUE_OPTIONAL),
                    'sumgrades' => new external_value(PARAM_FLOAT, 'Total marks for this attempt.', VALUE_OPTIONAL),
                    'idnumber' => new external_value(PARAM_RAW, 'idnumber', VALUE_OPTIONAL),
                    'firstnamephonetic' => new external_value(PARAM_RAW, 'firstname phonetic', VALUE_OPTIONAL),
                    'lastnamephonetic' => new external_value(PARAM_RAW, 'lastname phonetic', VALUE_OPTIONAL),
                    'middlename' => new external_value(PARAM_RAW, 'middlename', VALUE_OPTIONAL),
                    'alternatename' => new external_value(PARAM_RAW, 'alternate name', VALUE_OPTIONAL),
                    'firstname' => new external_value(PARAM_RAW, 'first name', VALUE_OPTIONAL),
                    'lastname' => new external_value(PARAM_RAW, 'last name', VALUE_OPTIONAL),
                )
            )
        );
    }

    /**
     * Hanv 21/07/2016
     * Process any submitted data.
     *
     * @return external_external_function_parameters
     * @since Moodle 3.1
     */
    public static function grading_process_submitted_data_parameters() {
        return new external_function_parameters (
            array(
                'attemptids' => new external_multiple_structure(new external_value(PARAM_RAW, 'attemptids')),
                'data' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'data name'),
                            'value' => new external_value(PARAM_RAW, 'data value'),
                        )
                    ),
                    'the data to be saved', VALUE_DEFAULT, array()
                ),
            )
        );
    }

    /**
     * Process any submitted data.
     *
     * @param array $attemptids attempt id
     * @param array $data the data to be saved
     * @since Moodle 3.1
     */
    public static function grading_process_submitted_data($attemptids, $data) {
        global $CFG, $DB;
        $warnings = array();

        $params = array(
            'attemptids' => $attemptids,
            'data' => $data,
        );
        $params = self::validate_parameters(self::grading_process_submitted_data_parameters(), $params);

        // Create the $_POST object required by the question engine.
        $_POST = array();
        foreach ($params['data'] as $element) {
            $_POST[$element['name']] = $element['value'];
        }

        $qubaids = optional_param('qubaids', null, PARAM_SEQUENCE);
        $assumedslotforevents = optional_param('slot', null, PARAM_INT);

//        $events = array();

        $transaction = $DB->start_delegated_transaction();

        foreach ($attemptids as $element) {
            $attemptobj = quiz_attempt::create($element);
            $attemptobj->process_submitted_actions(time());

//            // Add the event we will trigger later.
//            $params = array(
//                'objectid' => $attemptobj->get_question_attempt($assumedslotforevents)->get_question()->id,
//                'courseid' => $attemptobj->get_courseid(),
//                'context' => context_module::instance($attemptobj->get_cmid()),
//                'other' => array(
//                    'quizid' => $attemptobj->get_quizid(),
//                    'attemptid' => $attemptobj->get_attemptid(),
//                    'slot' => $assumedslotforevents
//                )
//            );
//            var_dump($params);die;
//            $events[] = \mod_quiz\event\question_manually_graded::create($params);
        }
        $transaction->allow_commit();


        $result = array();
        $result['state'] = 'success';
        return $result;
    }

    /**
     * Describes the process_attempt return value.
     *
     * @return external_single_structure
     * @since Moodle 3.1
     */
    public static function grading_process_submitted_data_returns() {
        return new external_single_structure(
            array(
                'state' => new external_value(PARAM_ALPHANUMEXT, 'success or unsuccess'),
            )
        );
    }

    /**
     * Hanv 23/07/2016
     * Get essay question options.
     *
     * @return external_function_parameters
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     *
     */
    public static function get_essay_question_options_parameters() {
        return new external_function_parameters(
            array(
                'questionid' => new external_value(PARAM_INT, 'questionid'),
            )
        );
    }

    /**
     * Get essay question options.
     *
     * @return array
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     */
    public static function get_essay_question_options($questionid) {
        global $CFG, $DB;

        //validate parameter
        $params = self::validate_parameters(self::get_essay_question_options_parameters(),
            array('questionid' => $questionid));
        // Get essay question options.
        $options = $DB->get_record('qtype_essay_options',
            array('questionid' => $questionid), '*', MUST_EXIST);
        return $options;
    }

    /**
     * Describes a single attempt structure.
     *
     * @return external_single_structure the attempt structure
     */
    public static function get_essay_question_options_returns() {
        return new external_single_structure(
            array(
                'id' => new external_value(PARAM_RAW, 'Attempt id.', VALUE_OPTIONAL),
                'questionid' => new external_value(PARAM_RAW, 'questionid', VALUE_OPTIONAL),
                'responseformat' => new external_value(PARAM_RAW, 'responseformat.', VALUE_OPTIONAL),
                'responserequired' => new external_value(PARAM_RAW, 'responserequired.', VALUE_OPTIONAL),
                'responsefieldlines' => new external_value(PARAM_RAW, 'responsefieldlines.', VALUE_OPTIONAL),
                'attachments' => new external_value(PARAM_RAW, 'attachments.', VALUE_OPTIONAL),
                'attachmentsrequired' => new external_value(PARAM_RAW, 'attachmentsrequired.', VALUE_OPTIONAL),
                'graderinfo' => new external_value(PARAM_RAW, 'graderinfo.', VALUE_OPTIONAL),
                'graderinfoformat' => new external_value(PARAM_RAW, 'graderinfoformat.', VALUE_OPTIONAL),
                'responsetemplate' => new external_value(PARAM_RAW, 'responsetemplate.', VALUE_OPTIONAL),
                'responsetemplateformat' => new external_value(PARAM_RAW, 'responsetemplateformat.', VALUE_OPTIONAL),
            )
        );
    }


    /**
     * Hanv 25/07/2016
     * Get question option answer. Don't check for success or failure because some question types do not use the answers table.
     *
     * @return external_function_parameters
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     *
     */
    public static function get_question_options_answer_parameters() {
        return new external_function_parameters(
            array(
                'questionid' => new external_value(PARAM_INT, 'questionid'),
            )
        );
    }

    /**
     * Get question option answer. Don't check for success or failure because some question types do not use the answers table.
     *
     * @return array
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     */
    public static function get_question_options_answer($questionid) {
        global $CFG, $DB;

        //validate parameter
        $params = self::validate_parameters(self::get_question_options_answer_parameters(),
            array('questionid' => $questionid));
        // Get essay question options.
        $answers = $DB->get_records('question_answers', array('question' => $questionid), 'id ASC');
        return $answers;
    }

    /**
     * Describes a single attempt structure.
     *
     * @return external_single_structure the attempt structure
     */
    public static function get_question_options_answer_returns() {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'id' => new external_value(PARAM_RAW, 'id.', VALUE_OPTIONAL),
                    'question' => new external_value(PARAM_RAW, 'question', VALUE_OPTIONAL),
                    'answer' => new external_value(PARAM_RAW, 'answer.', VALUE_OPTIONAL),
                    'answerformat' => new external_value(PARAM_RAW, 'answerformat.', VALUE_OPTIONAL),
                    'fraction' => new external_value(PARAM_RAW, 'fraction.', VALUE_OPTIONAL),
                    'feedback' => new external_value(PARAM_RAW, 'feedback.', VALUE_OPTIONAL),
                    'feedbackformat' => new external_value(PARAM_RAW, 'feedbackformat.', VALUE_OPTIONAL),
                )
            )
        );
    }

    /**
     * Hanv 25/07/2016
     * Get remote question hints.
     *
     * @return external_function_parameters
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     *
     */
    public static function get_question_hints_parameters() {
        return new external_function_parameters(
            array(
                'questionid' => new external_value(PARAM_INT, 'questionid'),
            )
        );
    }

    /**
     * Get remote question hints.
     *
     * @return array
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     */
    public static function get_question_hints($questionid) {
        global $CFG, $DB;

        //validate parameter
        $params = self::validate_parameters(self::get_question_hints_parameters(),
            array('questionid' => $questionid));
        // Get essay question options.
        $hints = $DB->get_records('question_hints', array('questionid' => $questionid), 'id ASC');
        return $hints;
    }

    /**
     * Describes a single attempt structure.
     *
     * @return external_single_structure the attempt structure
     */
    public static function get_question_hints_returns() {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'id' => new external_value(PARAM_RAW, 'id.', VALUE_OPTIONAL),
                    'questionid' => new external_value(PARAM_RAW, 'questionid', VALUE_OPTIONAL),
                    'hint' => new external_value(PARAM_RAW, 'hint.', VALUE_OPTIONAL),
                    'hintformat' => new external_value(PARAM_RAW, 'hintformat.', VALUE_OPTIONAL),
                    'shownumcorrect' => new external_value(PARAM_RAW, 'shownumcorrect.', VALUE_OPTIONAL),
                    'clearwrong' => new external_value(PARAM_RAW, 'clearwrong.', VALUE_OPTIONAL),
                    'options' => new external_value(PARAM_RAW, 'options.', VALUE_OPTIONAL),
                )
            )
        );
    }

    /**
     * Hanv 25/07/2016
     * Return a list of ids, load the basic information about a set of questions from the questions table.
     *
     * @return external_function_parameters
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     *
     */
    public static function get_question_preload_question_parameters() {
        return new external_function_parameters(
            array(
                'questionids' => new external_multiple_structure(new external_value(PARAM_INT, 'questionids')),
                'extrafields' => new external_value(PARAM_RAW, 'extrafields', VALUE_DEFAULT, null),
                'join' => new external_value(PARAM_RAW, 'join', VALUE_DEFAULT, null),
                'extraparams' => new  external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'name'),
                            'value' => new external_value(PARAM_RAW, 'value'),
                        )
                    ), 'Options, used since Moodle 3.1', VALUE_DEFAULT, array()
                ),
                'orderby' => new external_value(PARAM_RAW, 'orderby', VALUE_DEFAULT, null),
            )
        );
    }

    /**
     * Return a list of ids, load the basic information about a set of questions from the questions table.
     *
     * @param int $id quizid
     * @param array $options Options for filtering the results, used since Moodle 3.1
     * @return array
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     */
    public static function get_question_preload_question($questionids = null, $extrafields = '', $join = '',
                                                         $extraparams, $orderby = '') {
        global $CFG, $DB;

        //validate parameter
        $params = self::validate_parameters(self::get_question_preload_question_parameters(),
            array('questionids' => $questionids, 'extrafields' => $extrafields, 'join' => $join,
                'extraparams' => $extraparams, 'orderby' => $orderby));
        $quesvalues = array();
        foreach ($questionids as $question) {
            $quesvalues[$question] = $question;
        }

        $paramdata = array();
        foreach ($params['extraparams'] as $element) {
            $paramdata[$element['name']] = $element['value'];
        }

        $questions = question_preload_questions($quesvalues, $extrafields, $join,
            $paramdata, $orderby);
        return $questions;
    }

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     */
    public static function get_question_preload_question_returns() {
        return  new external_multiple_structure(
            new external_single_structure(
                array(
                    'id' => new external_value(PARAM_INT, 'Standard Moodle primary key.'),
                    'category' => new external_value(PARAM_INT, 'category',VALUE_OPTIONAL),
                    'parent' => new external_value(PARAM_INT, 'parent', VALUE_OPTIONAL),
                    'name' => new external_value(PARAM_RAW, 'Question name'),
                    'questiontext' => new external_value(PARAM_RAW, 'Question introduction text.', VALUE_OPTIONAL),
                    'questiontextformat' => new external_value(PARAM_INT, 'questiontext format.', VALUE_OPTIONAL),
                    'generalfeedback' => new external_value(PARAM_RAW, 'generalfeedback.', VALUE_OPTIONAL),
                    'generalfeedbackformat' => new external_value(PARAM_INT, 'general feedback format.', VALUE_OPTIONAL),
                    'defaultmark' => new external_value(PARAM_FLOAT, 'default mark.', VALUE_OPTIONAL),
                    'penalty' => new external_value(PARAM_FLOAT, 'penalty.', VALUE_OPTIONAL),
                    'qtype' => new external_value(PARAM_RAW, 'qtype', VALUE_OPTIONAL),
                    'length' => new external_value(PARAM_INT, 'length', VALUE_OPTIONAL),
                    'stamp' => new external_value(PARAM_RAW, 'stamp'),
                    'version' => new external_value(PARAM_RAW, 'Question version'),
                    'hidden' => new external_value(PARAM_INT, '	hidden', VALUE_OPTIONAL),
                    'timecreated' => new external_value(PARAM_INT, 'The time when the question was added to the question bank.', VALUE_OPTIONAL),
                    'timemodified' => new external_value(PARAM_INT, 'Last modified time.', VALUE_OPTIONAL),
                    'createdby' => new external_value(PARAM_INT, 'created by.', VALUE_OPTIONAL),
                    'modifiedby' => new external_value(PARAM_INT, 'modified by.', VALUE_OPTIONAL),
                    'contextid' => new external_value(PARAM_INT, 'comtext id.'),
                    '_partiallyloaded' => new external_value(PARAM_BOOL, '_partiallyloaded.'),
                )
            )
        );
    }

    /**
     * Hanv 26/07/2016
     * Get multichoice question options.
     *
     * @return external_function_parameters
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     *
     */
    public static function get_multichoice_question_options_parameters() {
        return new external_function_parameters(
            array(
                'questionid' => new external_value(PARAM_INT, 'questionid'),
            )
        );
    }

    /**
     * Get multichoice question options.
     *
     * @return array
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     */
    public static function get_multichoice_question_options($questionid) {
        global $CFG, $DB;

        //validate parameter
        $params = self::validate_parameters(self::get_multichoice_question_options_parameters(),
            array('questionid' => $questionid));
        // Get essay question options.
        $options = $DB->get_record('qtype_multichoice_options',
            array('questionid' => $questionid), '*', MUST_EXIST);
        return $options;
    }

    /**
     * Describes a single attempt structure.
     *
     * @return external_single_structure the attempt structure
     */
    public static function get_multichoice_question_options_returns() {
        return new external_single_structure(
            array(
                'id' => new external_value(PARAM_RAW, 'Attempt id.', VALUE_OPTIONAL),
                'questionid' => new external_value(PARAM_RAW, 'questionid', VALUE_OPTIONAL),
                'layout' => new external_value(PARAM_RAW, 'responseformat.', VALUE_OPTIONAL),
                'single' => new external_value(PARAM_RAW, 'responserequired.', VALUE_OPTIONAL),
                'shuffleanswers' => new external_value(PARAM_RAW, 'responsefieldlines.', VALUE_OPTIONAL),
                'correctfeedback' => new external_value(PARAM_RAW, 'attachments.', VALUE_OPTIONAL),
                'correctfeedbackformat' => new external_value(PARAM_RAW, 'attachmentsrequired.', VALUE_OPTIONAL),
                'partiallycorrectfeedback' => new external_value(PARAM_RAW, 'graderinfo.', VALUE_OPTIONAL),
                'partiallycorrectfeedbackformat' => new external_value(PARAM_RAW, 'graderinfoformat.', VALUE_OPTIONAL),
                'incorrectfeedback' => new external_value(PARAM_RAW, 'responsetemplate.', VALUE_OPTIONAL),
                'incorrectfeedbackformat' => new external_value(PARAM_RAW, 'responsetemplate.', VALUE_OPTIONAL),
                'answernumbering' => new external_value(PARAM_RAW, 'responsetemplateformat.', VALUE_OPTIONAL),
                'shownumcorrect' => new external_value(PARAM_RAW, 'responsetemplateformat.', VALUE_OPTIONAL),
            )
        );
    }

    /**
     * Hanv 26/07/2016
     * Get the latest step data from the db, from which we will calculate stats.
     *
     * @return external_function_parameters
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     *
     */
    public static function get_statistic_questions_usages_parameters() {
        return new external_function_parameters (
            array(
                'from' => new external_value(PARAM_RAW, 'from'),
                'where' => new external_value(PARAM_RAW, 'where'),
                'fields' => new external_value(PARAM_RAW, 'fields', VALUE_DEFAULT, null),
                'param' => new  external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'name'),
                            'value' => new external_value(PARAM_RAW, 'value'),
                        )
                    )
                ),
                'slots' => new external_multiple_structure(new external_value(PARAM_INT, 'slots')),
            )
        );
    }

    /**
     * Get the latest step data from the db, from which we will calculate stats.
     *
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     */
    public static function get_statistic_questions_usages($from, $where, $fields, $paramdata, $slots) {
        global $CFG, $DB;

        //validate parameter
        $params = self::validate_parameters(self::get_statistic_questions_usages_parameters(),
            array('from' => $from, 'where' => $where, 'fields' => $fields, 'param' => $paramdata, 'slots' => $slots));

        $r_params = array();
        foreach ($paramdata as $element) {
            $r_params[$element['name']] = $element['value'];
        }

        $r_slots = array();
        foreach ($slots as $slot) {
            $r_slots[$slot] = $slot;
        }

        $dm = new question_engine_data_mapper();
        $qubaids = new qubaid_join($from, 'quiza.uniqueid', $where, $r_params);
        $lateststeps = $dm->load_questions_usages_latest_steps($qubaids, array_keys($r_slots), $fields);
        return $lateststeps;
    }

    /**
     * Returns description of method parameters
     *
     * @return external_single_structure
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     */
    public static function get_statistic_questions_usages_returns() {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'id' => new external_value(PARAM_INT, 'id.', VALUE_OPTIONAL),
                    'questionusageid' => new external_value(PARAM_INT, 'questionusageid.', VALUE_OPTIONAL),
                    'questionid' => new external_value(PARAM_INT, 'question id.', VALUE_OPTIONAL),
                    'variant' => new external_value(PARAM_INT, 'variant.', VALUE_OPTIONAL),
                    'slot' => new external_value(PARAM_INT, 'slot.', VALUE_OPTIONAL),
                    'maxmark' => new external_value(PARAM_FLOAT, 'max mark.', VALUE_OPTIONAL),
                    'mark' => new external_value(PARAM_FLOAT, 'mark.', VALUE_OPTIONAL),
                )
            )
        );
    }

    /**
     * Hanv 29/07/2016
     * Get user email by userhub id to get userinfo in host.
     *
     * @return external_function_parameters
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     *
     */
    public static function get_userlocal_by_userhubid_parameters() {
        return new external_function_parameters (
            array(
                'userid' => new external_value(PARAM_INT, 'userid')
            )
        );
    }

    /**
     * Get user email by userhub id to get userinfo in host.
     *
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     */
    public static function get_userlocal_by_userhubid($userhubid) {
        global $CFG, $DB;

        //validate parameter
        $params = self::validate_parameters(self::get_userlocal_by_userhubid_parameters(),
            array('userid' => $userhubid));
        $user = $DB->get_record('user', array('id' => $params['userid']), 'username, email', MUST_EXIST);
        return $user;
    }

    /**
     * Returns description of method parameters
     *
     * @return external_single_structure
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     */
    public static function get_userlocal_by_userhubid_returns() {
        return new external_single_structure(
            array(
                'username' => new external_value(PARAM_RAW, 'username.', VALUE_OPTIONAL),
                'email' => new external_value(PARAM_RAW, 'email.', VALUE_OPTIONAL),
            )
        );
    }

    /**
     * Hanv: 02/08/2016
     * Check this attempt, to see if there are any state transitions that should happen automatically.
     * This function will update the attempt checkstatetime.
     *
     * @return external_external_function_parameters
     * @since Moodle 3.1
     */
    public static function remote_handle_if_time_expired_parameters() {
        return new external_function_parameters (
                array(
                    'quizid' => new external_value(PARAM_INT, 'quiz instance id'),
                    'attemptid' => new external_value(PARAM_INT, 'attempt id, 0 for the user last attempt if exists'),
                    'studentisonline' => new external_value(PARAM_BOOL, 'student is online: true or false', VALUE_DEFAULT, true),
                    'setting' => new external_multiple_structure(
                        new external_single_structure(
                            array(
                                'name' => new external_value(PARAM_RAW, 'data name'),
                                'value' => new external_value(PARAM_RAW, 'data value'),
                            )
                        ), 'Local quiz setting (like: timelimit, timeopen ...)', VALUE_DEFAULT, array()
                    ),
                )
        );
    }

    /**
     * Check this attempt, to see if there are any state transitions that should happen automatically.
     * This function will update the attempt checkstatetime.
     *
     * @param int $quizid quiz instance id
     * @param int $attemptid attempt id, 0 for the user last attempt if exists
     * @return array of warnings and the access information
     * @since Moodle 3.1
     * @throws  moodle_quiz_exception
     */
    public static function remote_handle_if_time_expired($quizid, $attemptid, $studentisonline = true, $setting = array()) {
        global $DB;

        $params = array(
            'quizid' => $quizid,
            'attemptid' => $attemptid,
            'studentisonline' => $studentisonline,
            'setting' => $setting,
        );
        $params = self::validate_parameters(self::remote_handle_if_time_expired_parameters(), $params);

        list($quiz, $course, $cm, $context) = mod_quiz_external::validate_quiz($params['quizid']);
        $attemptobj = quiz_attempt::create($params['attemptid']);

        // Access manager now.
        if($params['setting']){
            $localsetting = array();
            foreach ($params['setting'] as $element) {
                $localsetting[$element['name']] = $element['value'];
            }
        }
        $quizobj = quiz::create($cm->instance, null, $localsetting);

        $attempt = $attemptobj->get_attempt();

        if ($attempt->state == quiz_attempt::IN_PROGRESS || $attempt->state == quiz_attempt::OVERDUE) {
            // Check if the attempt is now overdue. In that case the state will change.
            $quizobj->create_attempt_object($attempt)->handle_if_time_expired(time(), $studentisonline);
        }
        return $attempt;
    }

    /**
     * Describes the get_attempt_access_information return value.
     *
     * @return external_single_structure
     * @since Moodle 3.1
     */
    public static function remote_handle_if_time_expired_returns() {
        return new external_single_structure(
            array(
                'id' => new external_value(PARAM_INT, 'Attempt id.', VALUE_OPTIONAL),
                'quiz' => new external_value(PARAM_INT, 'Foreign key reference to the quiz that was attempted.',
                    VALUE_OPTIONAL),
                'userid' => new external_value(PARAM_INT, 'Foreign key reference to the user whose attempt this is.',
                    VALUE_OPTIONAL),
                'attempt' => new external_value(PARAM_INT, 'Sequentially numbers this students attempts at this quiz.',
                    VALUE_OPTIONAL),
                'uniqueid' => new external_value(PARAM_INT, 'Foreign key reference to the question_usage that holds the
                                                    details of the the question_attempts that make up this quiz
                                                    attempt.', VALUE_OPTIONAL),
                'layout' => new external_value(PARAM_RAW, 'Attempt layout.', VALUE_OPTIONAL),
                'currentpage' => new external_value(PARAM_INT, 'Attempt current page.', VALUE_OPTIONAL),
                'preview' => new external_value(PARAM_INT, 'Whether is a preview attempt or not.', VALUE_OPTIONAL),
                'state' => new external_value(PARAM_ALPHA, 'The current state of the attempts. \'inprogress\',
                                                \'overdue\', \'finished\' or \'abandoned\'.', VALUE_OPTIONAL),
                'timestart' => new external_value(PARAM_INT, 'Time when the attempt was started.', VALUE_OPTIONAL),
                'timefinish' => new external_value(PARAM_INT, 'Time when the attempt was submitted.
                                                    0 if the attempt has not been submitted yet.', VALUE_OPTIONAL),
                'timemodified' => new external_value(PARAM_INT, 'Last modified time.', VALUE_OPTIONAL),
                'timecheckstate' => new external_value(PARAM_INT, 'Next time quiz cron should check attempt for
                                                        state changes.  NULL means never check.', VALUE_OPTIONAL),
                'sumgrades' => new external_value(PARAM_FLOAT, 'Total marks for this attempt.', VALUE_OPTIONAL),
            )
        );
    }

    /**
     * Hanv: 06/08/2016
     * setfield response
     */
    public static function setfield_response_by_mbl_parameters()
    {
        return new external_function_parameters (
            array(
                'tablename' => new external_value(PARAM_TEXT, ' the table name'),
                'field' => new external_value(PARAM_RAW, 'field'),
                'value' => new external_value(PARAM_RAW, 'value'),
                'data' => new external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'data name'),
                            'value' => new external_value(PARAM_RAW, 'data value'),
                        )
                    ), 'the data to be saved'
                )
            )
        );
    }

    /**
     * create new a response
     *
     * @param $data
     * @return array
     * @throws invalid_parameter_exception
     */
    public static function setfield_response_by_mbl($tablename, $field, $value, $data)
    {
        global $DB;

        $warnings = array();

        $params = array(
            'tablename' => $tablename,
            'field' => $field,
            'value' => $value,
            'data' => $data
        );

        $params = self::validate_parameters(self::setfield_response_by_mbl_parameters(), $params);

        $result = array();

        $conditions = array();
        foreach ($params['data'] as $element) {
            $conditions[$element['name']] = $element['value'];
        }

        $transaction = $DB->start_delegated_transaction();

        $DB->set_field($tablename, $field, $value, $conditions);

        $transaction->allow_commit();

        $result['status'] = true;

        return $result;
    }

    /**
     * Returns description of method result value
     *
     * @return external_description
     * @since Moodle 3.0
     */
    public static function setfield_response_by_mbl_returns()
    {
        return new external_single_structure(
            array(
                'status' => new external_value(PARAM_BOOL, 'status: true if success'),
            )
        );
    }

    /**
     * Hanv 09/08/2016
     * Calculating count and mean of marks for first and ALL attempts by students.
     *
     * @return external_function_parameters
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     *
     */
    public static function get_statistic_attempt_counts_and_averages_parameters() {
        return new external_function_parameters (
            array(
                'from' => new external_value(PARAM_RAW, 'from'),
                'where' => new external_value(PARAM_RAW, 'where'),
                'param' => new  external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'name'),
                            'value' => new external_value(PARAM_RAW, 'value'),
                        )
                    )
                ),
            )
        );
    }

    /**
     * Calculating count and mean of marks for first and ALL attempts by students.
     *
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     */
    public static function get_statistic_attempt_counts_and_averages($from, $where, $param) {
        global $CFG, $DB;

        //validate parameter
        $params = self::validate_parameters(self::get_statistic_attempt_counts_and_averages_parameters(),
            array('from' => $from, 'where' => $where, 'param' => $param));

        $r_param = array();
        foreach ($params['param'] as $element) {
            $r_param[$element['name']] = $element['value'];
        }

        $fromdb = $DB->get_record_sql("SELECT COUNT(*) AS rcount, AVG(sumgrades) AS average FROM $from WHERE $where",
            $r_param);
        return $fromdb;
    }

    /**
     * Returns description of method parameters
     *
     * @return external_single_structure
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     */
    public static function get_statistic_attempt_counts_and_averages_returns() {
        return new external_single_structure(
            array(
                'rcount' => new external_value(PARAM_INT, 'rcount'),
                'average' => new external_value(PARAM_FLOAT, 'average'),
            )
        );
    }


    /**
     * Hanv 09/08/2016
     * Get statistic median mark.
     *
     * @return external_function_parameters
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     *
     */
    public static function get_statistic_median_mark_parameters() {
        return new external_function_parameters (
            array(
                'sql' => new external_value(PARAM_RAW, 'sql'),
                'limitoffset' => new external_value(PARAM_FLOAT, 'limitoffset'),
                'limit' => new external_value(PARAM_INT, 'limit'),
                'param' => new  external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'name'),
                            'value' => new external_value(PARAM_RAW, 'value'),
                        )
                    )
                ),
            )
        );
    }

    /**
     * Get statistic median mark.
     *
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     */
    public static function get_statistic_median_mark($sql, $limitoffset, $limit, $param) {
        global $CFG, $DB;

        //validate parameter
        $params = self::validate_parameters(self::get_statistic_median_mark_parameters(),
            array('sql' => $sql, 'limitoffset' => $limitoffset, 'limit' => $limit, 'param' => $param));

        $r_param = array();
        foreach ($params['param'] as $element) {
            $r_param[$element['name']] = $element['value'];
        }

        $records = $DB->get_records_sql_menu($sql, $r_param, $limitoffset, $limit);
        $results = array();
        $i = 0;
        foreach ($records as $key => $value) {
            $results[$i]['key']   = $key;
            $results[$i]['value'] = $value;
            $i++;
        }
        return $results;
    }

    /**
     * Returns description of method parameters
     *
     * @return external_single_structure
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     */
    public static function get_statistic_median_mark_returns() {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'key' => new external_value(PARAM_INT, 'key'),
                    'value' => new external_value(PARAM_FLOAT, 'value'),
                )
            )
        );
    }

    /**
     * Hanv 10/08/2016
     * Get id, category, createdby from question table by category.
     *
     * @return external_function_parameters
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     *
     */
    public static function get_ques_by_category_parameters() {
        return new external_function_parameters (
            array(
                'category' => new external_value(PARAM_INT, 'category'),
            )
        );
    }

    /**
     * Get id, category, createdby from question table by category.
     *
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     */
    public static function get_ques_by_category($category) {
        global $DB;

        //validate parameter
        $params = self::validate_parameters(self::get_ques_by_category_parameters(),
            array('category' => $category));

        $records = $DB->get_records('question', array('category' => $params['category']), '', 'id,category,createdby');
        return $records;
    }

    /**
     * Returns description of method parameters
     *
     * @return external_single_structure
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     */
    public static function get_ques_by_category_returns() {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'id' => new external_value(PARAM_INT, 'question id'),
                    'category' => new external_value(PARAM_INT, 'question category'),
                    'createdby' => new external_value(PARAM_INT, 'createdby'),
                )
            )
        );
    }

    /**
     * Hanv 10/08/2016
     * Get a single database record as an object where all the given conditions met.
     *
     * @return external_function_parameters
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     *
     */
    public static function db_get_record_parameters() {
        return new external_function_parameters (
            array(
                'table' => new external_value(PARAM_RAW, 'table'),
                'fields' => new external_value(PARAM_RAW, 'fields', VALUE_DEFAULT, '*'),
                'strictness' => new external_value(PARAM_INT, 'strictness', VALUE_DEFAULT, 0),
                'conditions' => new  external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'name'),
                            'value' => new external_value(PARAM_RAW, 'value'),
                        )
                    )
                ),
            )
        );
    }

    /**
     * Get a single database record as an object where all the given conditions met.
     *
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     */
    public static function db_get_record($table, $fields, $strictness, $conditions) {
        global $CFG, $DB;

        //validate parameter
        $params = self::validate_parameters(self::db_get_record_parameters(),
            array('table' => $table, 'fields' => $fields, 'strictness' => $strictness, 'conditions' => $conditions));

        $r_conditions = array();
        foreach ($params['conditions'] as $element) {
            $r_conditions[$element['name']] = $element['value'];
        }
        $records = $DB->get_record($table, $r_conditions, $fields, $strictness);
        $results = array();
        $i = 0;
        foreach ($records as $key => $value) {
            $results[$i]['key']   = $key;
            $results[$i]['value'] = $value;
            $i++;
        }
        return $results;
    }

    /**
     * Returns description of method parameters
     *
     * @return external_single_structure
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     */
    public static function db_get_record_returns() {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'key' => new external_value(PARAM_RAW, 'key'),
                    'value' => new external_value(PARAM_RAW, 'value'),
                )
            )
        );
    }

    /**
     * Hanv 29/08/2016
     * Insert a record into a table and return the "id" field if required.
     *
     * @return external_function_parameters
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     *
     */
    public static function db_insert_record_parameters() {
        return new external_function_parameters (
            array(
                'tablename' => new external_value(PARAM_RAW, 'table'),
                'dataencode' => new external_value(PARAM_RAW_TRIMMED, 'data array ison_encode'),
            )
        );
    }

    /**
     * Insert a record into a table and return the "id" field if required.
     *
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     */
    public static function db_insert_record($table, $dataencode) {
        global $CFG, $DB;

        //validate parameter
        $params = self::validate_parameters(self::db_insert_record_parameters(),
            array('tablename' => $table, 'dataencode' => $dataencode));

        $data = json_decode($params['dataencode'], true);

        $transaction = $DB->start_delegated_transaction();

        $id = $DB->insert_record($params['tablename'], $data);

        $transaction->allow_commit();

        return $id;
    }

    /**
     * Returns description of method parameters
     *
     * @return external_single_structure
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     */
    public static function db_insert_record_returns() {
        return new external_value(PARAM_INT, 'Standard Moodle primary key.');
    }

    /**
     * Hanv 29/08/2016
     * Delete the records from a table where all the given conditions met.
     *
     * @return external_function_parameters
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     *
     */
    public static function db_delete_records_parameters() {
        return new external_function_parameters (
            array(
                'table' => new external_value(PARAM_RAW, 'table'),
                'conditions' => new  external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'name'),
                            'value' => new external_value(PARAM_RAW, 'value'),
                        )
                    )
                ),
            )
        );
    }

    /**
     * Delete the records from a table where all the given conditions met.
     *
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     */
    public static function db_delete_records($table, $conditions) {
        global $CFG, $DB;
        $warnings = array();
        //validate parameter
        $params = self::validate_parameters(self::db_delete_records_parameters(),
            array('table' => $table, 'conditions' => $conditions));

        $result = array();
        $r_conditions = array();
        foreach ($params['conditions'] as $element) {
            $r_conditions[$element['name']] = $element['value'];
        }

        $transaction = $DB->start_delegated_transaction();

        $DB->delete_records($table, $r_conditions);

        $transaction->allow_commit();

        $result['status'] = true;
        $result['warnings'] = $warnings;
        return $result;
    }

    /**
     * Returns description of method parameters
     *
     * @return external_single_structure
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     */
    public static function db_delete_records_returns() {
        return new external_single_structure(
            array(
                'status' => new external_value(PARAM_BOOL, 'status: true if success'),
                'warnings' => new external_warnings(),
            )
        );
    }

    /**
     * Hanv 06/09/2016
     * Get a single database record as an object using a SQL statement.
     *
     * @return external_function_parameters
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     *
     */
    public static function db_get_record_sql_parameters() {
        return new external_function_parameters (
            array(
                'sql' => new external_value(PARAM_RAW, 'sql'),
                'strictness' => new external_value(PARAM_INT, 'strictness'),
                'param' => new  external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'name'),
                            'value' => new external_value(PARAM_RAW, 'value'),
                        )
                    )
                ),
            )
        );
    }

    /**
     * Get a single database record as an object using a SQL statement.
     *
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     */
    public static function db_get_record_sql($sql, $strictness, $parameters) {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::db_get_record_sql_parameters(), array(
            'sql' => $sql,
            'strictness' => $strictness,
            'param' => $parameters
        ));
        $r_param = array();
        foreach ($params['param'] as $element) {
            $r_param[$element['name']] = $element['value'];
        }
        $records = $DB->get_record_sql($sql, $r_param, $strictness);
        $results = array();
        $i = 0;
        foreach ($records as $key => $value) {
            $results[$i]['key']   = $key;
            $results[$i]['value'] = $value;
            $i++;
        }
        return $results;
    }

    /**
     * Returns description of method parameters
     *
     * @return external_single_structure
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     */
    public static function db_get_record_sql_returns() {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'key' => new external_value(PARAM_RAW, 'key'),
                    'value' => new external_value(PARAM_RAW, 'value'),
                )
            )
        );
    }

    /**
     * Hanv 06/09/2016
     * Test whether a record exists in a table where all the given conditions met.
     *
     * @return external_function_parameters
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     *
     */
    public static function db_record_exists_parameters() {
        return new external_function_parameters (
            array(
                'sql' => new external_value(PARAM_RAW, 'sql'),
                'strictness' => new external_value(PARAM_INT, 'strictness'),
                'param' => new  external_multiple_structure(
                    new external_single_structure(
                        array(
                            'name' => new external_value(PARAM_RAW, 'name'),
                            'value' => new external_value(PARAM_RAW, 'value'),
                        )
                    )
                ),
            )
        );
    }

    /**
     * Test whether a record exists in a table where all the given conditions met.
     *
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     */
    public static function db_record_exists($sql, $strictness, $parameters) {
        global $DB;
        $warnings = array();

        $params = self::validate_parameters(self::db_record_exists_parameters(), array(
            'sql' => $sql,
            'strictness' => $strictness,
            'param' => $parameters
        ));
        $r_param = array();
        foreach ($params['param'] as $element) {
            $r_param[$element['name']] = $element['value'];
        }
        $records = $DB->get_record_sql($sql, $r_param, $strictness);
        $results = array();
        $i = 0;
        foreach ($records as $key => $value) {
            $results[$i]['key']   = $key;
            $results[$i]['value'] = $value;
            $i++;
        }
        return $results;
    }

    /**
     * Returns description of method parameters
     *
     * @return external_single_structure
     * @since Moodle 3.1 Options available
     * @since Moodle 3.1
     */
    public static function db_record_exists_returns() {
        return new external_multiple_structure(
            new external_single_structure(
                array(
                    'key' => new external_value(PARAM_RAW, 'key'),
                    'value' => new external_value(PARAM_RAW, 'value'),
                )
            )
        );
    }
}
