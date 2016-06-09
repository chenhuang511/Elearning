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
require_once($CFG->dirroot . '/mod/quiz/locallib.php');

/**
 * Course external functions
 *
 * @package    core_course
 * @category   external
 * @copyright  2011 Jerome Mouneyrac
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since Moodle 2.2
 */
class local_mod_quiz_external extends external_api {
    /**
     * Hanv 24/05/2016
     * Return all the information about a quiz by quizid or by cm->instance from course_module
     *
     * @return external_function_parameters
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
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
                    ), 'Options, used since Moodle 2.9', VALUE_DEFAULT, array())
            )
        );
    }

    /**
     * Get Quiz object
     *
     * @param int $id id
     * @param array $options Options for filtering the results, used since Moodle 2.9
     * @return array
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
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
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
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
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
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
                    ), 'Options, used since Moodle 2.9', VALUE_DEFAULT, array())
            )
        );
    }

    /**
     * Get Question object
     *
     * @param int $id id
     * @param array $options Options for filtering the results, used since Moodle 2.9
     * @return array
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
     */
    public static function get_mod_questions_by_quizid($id, $options = array()) {
        global $CFG, $DB;

        //validate parameter
        $params = self::validate_parameters(self::get_mod_questions_by_quizid_parameters(),
            array('id' => $id,'options' => $options));
        // Thiet lap cac thong so ban dau cua lib/questionlib.php -> question_preload_questions
        $questionids = null;
        $extrafields = 'slot.maxmark, slot.id AS slotid, slot.slot, slot.page';
        $join = '{quiz_slots} slot ON slot.quizid = :quizid AND q.id = slot.questionid';
        $extraparams = array('quizid' => $params['id']);
        $orderby = 'slot.slot';

        if ($questionids === null) {
            $where = '';
            $params = array();
        } else {
            if (empty($questionids)) {
                return array();
            }

            list($questionidcondition, $params) = $DB->get_in_or_equal(
                $questionids, SQL_PARAMS_NAMED, 'qid0000');
            $where = 'WHERE q.id ' . $questionidcondition;
        }

        if ($join) {
            $join = 'JOIN ' . $join;
        }

        if ($extrafields) {
            $extrafields = ', ' . $extrafields;
        }

        if ($orderby) {
            $orderby = 'ORDER BY ' . $orderby;
        }

        $sql = "SELECT q.*, qc.contextid{$extrafields}
              FROM {question} q
              JOIN {question_categories} qc ON q.category = qc.id
              {$join}
             {$where}
          {$orderby}";

        // Load the questions.
        $questions = $DB->get_records_sql($sql, $extraparams + $params);
        foreach ($questions as $question) {
            $question->_partiallyloaded = true;
        }
        return $questions;
    }

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
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
                    ), 'Options, used since Moodle 2.9', VALUE_DEFAULT, array()
                )
            )
        );
    }

    /**
     * Get Attempt object
     *
     * @param int $attemptid attemptidid
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
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
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
     * Get quba object
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

        $qubadata = question_engine::load_questions_usage_by_activity($params['unique']);
        $quba = new stdClass();
        $quba->id = $qubadata->get_id();
        $quba->preferredbehaviour = $qubadata->get_preferred_behaviour();
        $quba->context = $qubadata->get_owning_context();
        $quba->owningcomponent = $qubadata->get_owning_component();
//        $quba->questionattempts = $qubadata->get_attempt_iterator();
        $quba->observer = $qubadata->get_observer();
        return $quba;
    }

    /**
     * Describes a single attempt structure.
     *
     * @return external_single_structure the attempt structure
     */
    public static function get_mod_load_questions_usage_by_activity_returns() {
        return new external_single_structure(
            array(
                "current" => new external_single_structure(
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
            )
        );
    }

    /**
     * Hanv 07/06/2016
     * Return all the information about slots by quizid
     *
     * @return external_function_parameters
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
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
                    ), 'Options, used since Moodle 2.9', VALUE_DEFAULT, array())
            )
        );
    }

    /**
     * Get slots object
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
     * @since Moodle 2.9 Options available
     * @since Moodle 2.2
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
                    ), 'Options, used since Moodle 2.9', VALUE_DEFAULT, array())
            )
        );
    }

    /**
     * Get slots object
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
     * @return external_single_structure the attempt structure
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
}
