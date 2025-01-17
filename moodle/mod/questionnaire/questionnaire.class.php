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

require_once($CFG->dirroot.'/mod/questionnaire/locallib.php');

class questionnaire {

    // Class Properties.

    /**
     * @var \mod_questionnaire\question\base[] $quesitons
     */
    public $questions = [];

    /**
     * The survey record.
     * @var object $survey
     */
     // Todo var $survey; TODO.

    // Class Methods.

    /*
     * The class constructor
     *
     */
    public function __construct($id = 0, $questionnaire = null, &$course, &$cm, $addquestions = true) {
        global $DB, $CFG;
        require_once($CFG->dirroot . '/mod/questionnaire/remote/locallib.php');

        if ($id) {
            if(MOODLE_RUN_MODE === MOODLE_MODE_HOST){
                $questionnaire = $DB->get_record('questionnaire', array('id' => $id));
            } else {
                $questionnaire = get_remote_questionnaire_by_id($id);
            }
        }

        if (is_object($questionnaire)) {
            $properties = get_object_vars($questionnaire);
            foreach ($properties as $property => $value) {
                $this->$property = $value;
            }
        }

        if (!empty($this->sid)) {
            $this->add_survey($this->sid);
        }

        $this->course = $course;
        $this->cm = $cm;
        // When we are creating a brand new questionnaire, we will not yet have a context.
        if (!empty($cm) && !empty($this->id)) {
            $this->context = context_module::instance($cm->id);
        } else {
            $this->context = null;
        }

        if ($addquestions && !empty($this->sid)) {
            $this->add_questions($this->sid);
        }

        // Load the capabilities for this user and questionnaire, if not creating a new one.
        if (!empty($this->cm->id)) {
            $this->capabilities = questionnaire_load_capabilities($this->cm->id);
        }
    }

    /**
     * Adding a survey record to the object.
     *
     */
    public function add_survey($sid = 0, $survey = null) {
        global $DB;

        if ($sid) {
            if(MOODLE_RUN_MODE === MOODLE_MODE_HOST){
                $this->survey = $DB->get_record('questionnaire_survey', array('id' => $sid));
            } else {
                $this->survey = get_remote_questionnaire_survey_by_id($sid);
            }
        } else if (is_object($survey)) {
            $this->survey = clone($survey);
        }
    }

    /**
     * Create question for type
     *
     * @author gthomas
     * @param $typename
     * @param int $id
     * @param null $record
     * @param null $context
     * @param array $params
     * @return \mod_questionnaire\question\base|mixed
     */
    public static function question_factory($typename, $id = 0, $record = null, $context = null, $params = []) {
        global $CFG;
        $questionclass = '\\mod_questionnaire\\question\\'.$typename;
        return new $questionclass($id, $record, $context, $params);
    }

    /**
     * Adding questions to the object.
     */
    public function add_questions($sid = false) {
        global $CFG, $DB;

        if ($sid === false) {
            $sid = $this->sid;
        }

        if (!isset($this->questions)) {
            $this->questions = array();
            $this->questionsbysec = array();
        }
        if(MOODLE_RUN_MODE === MOODLE_MODE_HOST){
            $select = 'survey_id = '.$sid.' AND deleted != \'y\'';
            $records = $DB->get_records_select('questionnaire_question', $select, null, 'position');
        } else {
            $records = get_remote_questionnaire_question_by_sid($sid);
        }
        if ($records) {
            $sec = 1;
            $isbreak = false;
            foreach ($records as $record) {

                $typename = \mod_questionnaire\question\base::qtypename($record->type_id);
                $this->questions[$record->id] = self::question_factory($typename, 0, $record, $this->context);

                if ($record->type_id != QUESPAGEBREAK) {
                    $this->questionsbysec[$sec][$record->id] = &$this->questions[$record->id];
                    $isbreak = false;
                } else {
                    // Sanity check: no section break allowed as first position, no 2 consecutive section breaks.
                    if ($record->position != 1 && $isbreak == false) {
                        $sec++;
                        $isbreak = true;
                    }
                }
            }
        }
    }

    public function view() {
        global $CFG, $USER, $PAGE, $OUTPUT;

        $PAGE->set_title(format_string($this->name));
        $PAGE->set_heading(format_string($this->course->fullname));

        // Initialise the JavaScript.
        $PAGE->requires->js_init_call('M.mod_questionnaire.init_attempt_form', null, false, questionnaire_get_js_module());

        echo $OUTPUT->header();

        $questionnaire = $this;

        if (!$this->cm->visible && !$this->capabilities->viewhiddenactivities) {
                notice(get_string("activityiscurrentlyhidden"));
        }

        if (!$this->capabilities->view) {
            echo('<br/>');
            questionnaire_notify(get_string("noteligible", "questionnaire", $this->name));
            echo('<div><a href="'.$CFG->wwwroot.'/course/view.php?id='.$this->course->id.'">'.
                get_string("continue").'</a></div>');

            exit;
        }

        // Print the main part of the page.

        if (!$this->is_active()) {
            echo '<div class="notifyproblem">'
            .get_string('notavail', 'questionnaire')
            .'</div>';
        } else if (!$this->is_open()) {
                echo '<div class="notifyproblem">'
                .get_string('notopen', 'questionnaire', userdate($this->opendate))
                .'</div>';
        } else if ($this->is_closed()) {
            echo '<div class="notifyproblem">'
            .get_string('closed', 'questionnaire', userdate($this->closedate))
            .'</div>';
        } else if (!$this->user_is_eligible($USER->id)) {
            echo '<div class="notifyproblem">'
            .get_string('noteligible', 'questionnaire')
            .'</div>';
        } else if ($this->user_can_take($USER->id)) {
            $quser = $USER->id;

            if ($this->survey->realm == 'template') {
                print_string('templatenotviewable', 'questionnaire');
                echo $OUTPUT->footer();
                exit();
            }

            $msg = $this->print_survey($USER->id, $quser);

            // If Questionnaire was submitted with all required fields completed ($msg is empty),
            // then record the submittal.
            $viewform = data_submitted($CFG->wwwroot."/mod/questionnaire/complete.php");
            if (!empty($viewform->rid)) {
                $viewform->rid = (int)$viewform->rid;
            }
            if (!empty($viewform->sec)) {
                $viewform->sec = (int)$viewform->sec;
            }
            if (data_submitted() && confirm_sesskey() && isset($viewform->submit) && isset($viewform->submittype) &&
                ($viewform->submittype == "Submit Survey") && empty($msg)) {
                $this->response_delete($viewform->rid, $viewform->sec);
                $this->rid = $this->response_insert($this->survey->id, $viewform->sec, $viewform->rid, $quser);
                $this->response_commit($this->rid);

                // If it was a previous save, rid is in the form...
                if (!empty($viewform->rid) && is_numeric($viewform->rid)) {
                    $rid = $viewform->rid;

                    // Otherwise its in this object.
                } else {
                    $rid = $this->rid;
                }

                questionnaire_record_submission($this, $USER->id, $rid);

                if ($this->grade != 0) {
                    $questionnaire = new stdClass();
                    $questionnaire->id = $this->id;
                    $questionnaire->name = $this->name;
                    $questionnaire->grade = $this->grade;
                    $questionnaire->cmidnumber = $this->cm->idnumber;
                    $questionnaire->courseid = $this->course->id;
                    questionnaire_update_grades($questionnaire, $quser);
                }

                // Update completion state.
                $completion = new completion_info($this->course);
                if ($completion->is_enabled($this->cm) && $this->completionsubmit) {
                    $completion->update_state($this->cm, COMPLETION_COMPLETE);
                }

                // Log this submitted response.
                $context = context_module::instance($this->cm->id);
                $anonymous = $this->respondenttype == 'anonymous';
                $params = array(
                                'context' => $context,
                                'courseid' => $this->course->id,
                                'relateduserid' => $USER->id,
                                'anonymous' => $anonymous,
                                'other' => array('questionnaireid' => $questionnaire->id)
                );
                $event = \mod_questionnaire\event\attempt_submitted::create($params);
                $event->trigger();

                $this->response_send_email($this->rid);
                $this->response_goto_thankyou();
            }

        } else {
            switch ($this->qtype) {
                case QUESTIONNAIREDAILY:
                    $msgstring = ' '.get_string('today', 'questionnaire');
                    break;
                case QUESTIONNAIREWEEKLY:
                    $msgstring = ' '.get_string('thisweek', 'questionnaire');
                    break;
                case QUESTIONNAIREMONTHLY:
                    $msgstring = ' '.get_string('thismonth', 'questionnaire');
                    break;
                default:
                    $msgstring = '';
                    break;
            }
            echo ('<div class="notifyproblem">'.get_string("alreadyfilled", "questionnaire", $msgstring).'</div>');
        }

        // Finish the page.
        echo $OUTPUT->footer();
    }

    /*
    * Function to view an entire responses data.
    *
    */
    public function view_response($rid, $referer= '', $blankquestionnaire = false, $resps = '', $compare = false,
                        $isgroupmember = false, $allresponses = false, $currentgroupid = 0) {
        global $OUTPUT;

        $this->print_survey_start('', 1, 1, 0, $rid, false);

        $data = new stdClass();
        $i = 0;
        $this->response_import_all($rid, $data);
        if ($referer != 'print') {
            $feedbackmessages = $this->response_analysis($rid, $resps, $compare, $isgroupmember, $allresponses, $currentgroupid);

            if ($feedbackmessages) {
                echo $OUTPUT->heading(get_string('feedbackreport', 'questionnaire'), 3);
                foreach ($feedbackmessages as $msg) {
                    echo $msg;
                }
            }

            if ($this->survey->feedbacknotes) {
                $text = file_rewrite_pluginfile_urls($this->survey->feedbacknotes, 'pluginfile.php',
                                $this->context->id, 'mod_questionnaire', 'feedbacknotes', $this->survey->id);
                echo $OUTPUT->box_start();
                echo format_text($text, FORMAT_HTML);
                echo $OUTPUT->box_end();
            }
        }
        foreach ($this->questions as $question) {
            if ($question->type_id < QUESPAGEBREAK) {
                $i++;
            }
            if ($question->type_id != QUESPAGEBREAK) {
                $question->response_display($data, $i);
            }
        }
    }

    /*
    * Function to view an entire responses data.
    *
    * $value is unused, but is needed in order to get the $key elements of the array. Suppress PHPMD warning.
    *
    * @SuppressWarnings(PHPMD.UnusedLocalVariable)
    */
    public function view_all_responses($resps) {
        global $OUTPUT;
        $this->print_survey_start('', 1, 1, 0);

        // If a student's responses have been deleted by teacher while student was viewing the report,
        // then responses may have become empty, hence this test is necessary.
        if ($resps) {
            foreach ($resps as $resp) {
                $data[$resp->id] = new stdClass();
                $this->response_import_all($resp->id, $data[$resp->id]);
            }

            $i = 0;

            foreach ($this->questions as $question) {
                if ($question->type_id < QUESPAGEBREAK) {
                    $i++;
                }
                $qid = preg_quote('q'.$question->id, '/');
                if ($question->type_id != QUESPAGEBREAK) {
                    echo $OUTPUT->box_start('individualresp');
                    $question->questionstart_survey_display($i);
                    foreach ($data as $respid => $respdata) {
                        $hasresp = false;
                        foreach ($respdata as $key => $value) {
                            if ($hasresp = preg_match("/$qid(_|$)/", $key)) {
                                break;
                            }
                        }
                        // Do not display empty responses.
                        if ($hasresp) {
                            echo '<div class="respdate">'.userdate($resps[$respid]->submitted).'</div>';
                            $question->response_display($respdata);
                        }
                    }
                    $question->questionend_survey_display($i);
                    echo $OUTPUT->box_end();
                }
            }
        } else {
            echo (get_string('noresponses', 'questionnaire'));
        }

        $this->print_survey_end(1, 1);
    }

    // Access Methods.
    public function is_active() {
        return (!empty($this->survey));
    }

    public function is_open() {
        return ($this->opendate > 0) ? ($this->opendate < time()) : true;
    }

    public function is_closed() {
        return ($this->closedate > 0) ? ($this->closedate < time()) : false;
    }

    public function user_can_take($userid) {

        if (!$this->is_active() || !$this->user_is_eligible($userid)) {
            return false;
        } else if ($this->qtype == QUESTIONNAIREUNLIMITED) {
            return true;
        } else if ($userid > 0) {
            return $this->user_time_for_new_attempt($userid);
        } else {
            return false;
        }
    }

    public function user_is_eligible($userid) {
        return ($this->capabilities->view && $this->capabilities->submit);
    }

    public function user_time_for_new_attempt($userid) {
        global $DB;
        if(MOODLE_RUN_MODE === MOODLE_MODE_HOST){
            $select = 'qid = '.$this->id.' AND userid = '.$userid;
            $attempts = $DB->get_records_select('questionnaire_attempts', $select, null, 'timemodified DESC');
        } else {
            $sql_select = 'qid = '.$this->id.' AND userid = ' . get_remote_mapping_user($userid)[0]->id;
            $sql_sort = 'timemodified DESC';
            $attempts = get_remote_questionnaire_attempts($sql_select, $sql_sort);
        }
        if (!($attempts)) {
            return true;
        }

        $attempt = reset($attempts);
        $timenow = time();

        switch ($this->qtype) {

            case QUESTIONNAIREUNLIMITED:
                $cantake = true;
                break;

            case QUESTIONNAIREONCE:
                $cantake = false;
                break;

            case QUESTIONNAIREDAILY:
                $attemptyear = date('Y', $attempt->timemodified);
                $currentyear = date('Y', $timenow);
                $attemptdayofyear = date('z', $attempt->timemodified);
                $currentdayofyear = date('z', $timenow);
                $cantake = (($attemptyear < $currentyear) ||
                            (($attemptyear == $currentyear) && ($attemptdayofyear < $currentdayofyear)));
                break;

            case QUESTIONNAIREWEEKLY:
                $attemptyear = date('Y', $attempt->timemodified);
                $currentyear = date('Y', $timenow);
                $attemptweekofyear = date('W', $attempt->timemodified);
                $currentweekofyear = date('W', $timenow);
                $cantake = (($attemptyear < $currentyear) ||
                            (($attemptyear == $currentyear) && ($attemptweekofyear < $currentweekofyear)));
                break;

            case QUESTIONNAIREMONTHLY:
                $attemptyear = date('Y', $attempt->timemodified);
                $currentyear = date('Y', $timenow);
                $attemptmonthofyear = date('n', $attempt->timemodified);
                $currentmonthofyear = date('n', $timenow);
                $cantake = (($attemptyear < $currentyear) ||
                            (($attemptyear == $currentyear) && ($attemptmonthofyear < $currentmonthofyear)));
                break;

            default:
                $cantake = false;
                break;
        }

        return $cantake;
    }

    public function is_survey_owner() {
		if(MOODLE_RUN_MODE === MOODLE_MODE_HOST){
 			return (!empty($this->survey->owner) && ($this->course->id == $this->survey->owner));
		} else {
			return (!empty($this->survey->owner) && ($this->course->remoteid == $this->survey->owner));
		}
    }

    public function can_view_response($rid) {
        global $USER, $DB;

        if (!empty($rid)) {
            if(MOODLE_RUN_MODE === MOODLE_MODE_HOST){
                $response = $DB->get_record('questionnaire_response', array('id' => $rid));
            } else {
                $response = get_remote_questionnaire_response_by_rid($rid);
            }

            // If the response was not found, can't view it.
            if (empty($response)) {
                return false;
            }

            // If the response belongs to a different survey than this one, can't view it.
            if ($response->survey_id != $this->survey->id) {
                return false;
            }

            // If you can view all responses always, then you can view it.
            if ($this->capabilities->readallresponseanytime) {
                return true;
            }

            // If you are allowed to view this response for another user.
            if ($this->capabilities->readallresponses &&
                ($this->resp_view == QUESTIONNAIRE_STUDENTVIEWRESPONSES_ALWAYS ||
                 ($this->resp_view == QUESTIONNAIRE_STUDENTVIEWRESPONSES_WHENCLOSED && $this->is_closed()) ||
                 ($this->resp_view == QUESTIONNAIRE_STUDENTVIEWRESPONSES_WHENANSWERED  && !$this->user_can_take($USER->id)))) {
                return true;
            }

             // If you can read your own response.
            if (($response->username == $USER->id) && $this->capabilities->readownresponses &&
                            ($this->count_submissions($USER->id) > 0)) {
                return true;
            }

        } else {
            // If you can view all responses always, then you can view it.
            if ($this->capabilities->readallresponseanytime) {
                return true;
            }

            // If you are allowed to view this response for another user.
            if ($this->capabilities->readallresponses &&
                ($this->resp_view == QUESTIONNAIRE_STUDENTVIEWRESPONSES_ALWAYS ||
                 ($this->resp_view == QUESTIONNAIRE_STUDENTVIEWRESPONSES_WHENCLOSED && $this->is_closed()) ||
                 ($this->resp_view == QUESTIONNAIRE_STUDENTVIEWRESPONSES_WHENANSWERED  && !$this->user_can_take($USER->id)))) {
                return true;
            }

             // If you can read your own response.
            if ($this->capabilities->readownresponses && ($this->count_submissions($USER->id) > 0)) {
                return true;
            }
        }
    }

    public function can_view_all_responses($usernumresp = null) {
        global $USER, $DB, $SESSION;
        if(MOODLE_RUN_MODE === MOODLE_MODE_HOST){
            if ($owner = $DB->get_field('questionnaire_survey', 'owner', array('id' => $this->sid))) {
                $owner = (trim($owner) == trim($this->course->id));
            } else {
                $owner = true;
            }
        } else {
            if ($owner = get_remote_field_owner_questionnaire_by_id($this->sid)) {
                $owner = (trim($owner) == trim($this->course->remoteid));
            } else {
                $owner = true;
            }
        }
        $numresp = $this->count_submissions();
        if (is_null($usernumresp)) {
            $usernumresp = $questionnaire->count_submissions($USER->id);
        }

        // Number of Responses in currently selected group (or all participants etc.).
        if (isset($SESSION->questionnaire->numselectedresps)) {
            $numselectedresps = $SESSION->questionnaire->numselectedresps;
        } else {
            $numselectedresps = $numresp;
        }

        // If questionnaire is set to separate groups, prevent user who is not member of any group
        // to view All responses.
        $canviewgroups = true;
        $groupmode = groups_get_activity_groupmode($this->cm, $this->course);
        if ($groupmode == 1) {
            $canviewgroups = groups_has_membership($this->cm, $USER->id);;
        }

        $canviewallgroups = has_capability('moodle/site:accessallgroups', $this->context);
        return (( // Teacher or non-editing teacher (if can view all groups).
                 ($canviewallgroups ||
                  // Non-editing teacher (with canviewallgroups capability removed), if member of a group.
                  ($canviewgroups && $this->capabilities->readallresponseanytime)) &&
                 $numresp > 0 && $owner && $numselectedresps > 0) ||
                ($this->capabilities->readallresponses && ($numresp > 0) && $canviewgroups &&
                 ($this->resp_view == QUESTIONNAIRE_STUDENTVIEWRESPONSES_ALWAYS ||
                  ($this->resp_view == QUESTIONNAIRE_STUDENTVIEWRESPONSES_WHENCLOSED && $this->is_closed()) ||
                  ($this->resp_view == QUESTIONNAIRE_STUDENTVIEWRESPONSES_WHENANSWERED && $usernumresp > 0)) &&
                 $this->is_survey_owner()));
    }

    public function count_submissions($userid=false) {
        global $DB;
        if(MOODLE_RUN_MODE === MOODLE_MODE_HOST){
            if (!$userid) {
                // Provide for groups setting.
                return $DB->count_records('questionnaire_response', array('survey_id' => $this->sid, 'complete' => 'y'));
            } else {
                return $DB->count_records('questionnaire_response', array('survey_id' => $this->sid, 'username' => $userid,
                    'complete' => 'y'));
            }
        } else {
            if (!$userid) {
                // Provide for groups setting.
                $select = 'survey_id = '.$this->sid.' AND complete=\'y\'';
            } else {
                $select = 'R.survey_id = '.$this->sid.' AND R.username = \'' . get_remote_mapping_user($userid)[0]->id . '\' AND R.complete=\'y\'';
            }
            $resps = get_remote_questionnaire_response($select);
            return count($resps);
        }
    }

    private function has_required($section = 0) {
        if (empty($this->questions)) {
            return false;
        } else if ($section <= 0) {
            foreach ($this->questions as $question) {
                if ($question->required == 'y') {
                    return true;
                }
            }
        } else {
            foreach ($this->questionsbysec[$section] as $question) {
                if ($question->required == 'y') {
                    return true;
                }
            }
        }
        return false;
    }

    // Display Methods.

    public function print_survey($userid=false, $quser) {
        global $SESSION, $DB, $CFG;

        $formdata = new stdClass();
        if (data_submitted() && confirm_sesskey()) {
            $formdata = data_submitted();
        }
        $formdata->rid = $this->get_response($quser);
        // If student saved a "resume" questionnaire OR left a questionnaire unfinished
        // and there are more pages than one find the page of the last answered question.
        if (!empty($formdata->rid) && (empty($formdata->sec) || intval($formdata->sec) < 1)) {
            $formdata->sec = $this->response_select_max_sec($formdata->rid);
        }
        if (empty($formdata->sec)) {
            $formdata->sec = 1;
        } else {
            $formdata->sec = (intval($formdata->sec) > 0) ? intval($formdata->sec) : 1;
        }

        $numsections = isset($this->questionsbysec) ? count($this->questionsbysec) : 0;    // Indexed by section.
        $msg = '';
        $action = $CFG->wwwroot.'/mod/questionnaire/complete.php?id='.$this->cm->id;

        // TODO - Need to rework this. Too much crossover with ->view method.

        // Skip logic :: if this is page 1, it cannot be the end page with no questions on it!
        if ($formdata->sec == 1) {
            $SESSION->questionnaire->end = false;
        }
        // Skip logic: reset this just in case.
        $SESSION->questionnaire->nbquestionsonpage = '';

        if (!empty($formdata->submit)) {
            // Skip logic: we have reached the last page without any questions on it.
            if (isset($SESSION->questionnaire->end) && $SESSION->questionnaire->end == true) {
                return;
            }

            $msg = $this->response_check_format($formdata->sec, $formdata);
            if (empty($msg)) {
                return;
            }
        }
        if (!empty($formdata->resume) && ($this->resume)) {
            $this->response_delete($formdata->rid, $formdata->sec);
            $formdata->rid = $this->response_insert($this->survey->id, $formdata->sec, $formdata->rid, $quser, $resume = true);
            $this->response_goto_saved($action);
            return;
        }

        // Save each section 's $formdata somewhere in case user returns to that page when navigating the questionnaire.
        if (!empty($formdata->next)) {
            $this->response_delete($formdata->rid, $formdata->sec);
            $formdata->rid = $this->response_insert($this->survey->id, $formdata->sec, $formdata->rid, $quser);
            $msg = $this->response_check_format($formdata->sec, $formdata);
            if ( $msg ) {
                $formdata->next = '';
            } else {
                // Skip logic.
                $formdata->sec++;
                if (questionnaire_has_dependencies($this->questions)) {
                    $nbquestionsonpage = questionnaire_nb_questions_on_page($this->questions,
                                    $this->questionsbysec[$formdata->sec], $formdata->rid);
                    while (count($nbquestionsonpage) == 0) {
                        $this->response_delete($formdata->rid, $formdata->sec);
                        $formdata->sec++;
                        // We have reached the end of questionnaire on a page without any question left.
                        if ($formdata->sec > $numsections) {
                            $SESSION->questionnaire->end = true; // End of questionnaire reached on a no questions page.
                            break;
                        }
                        $nbquestionsonpage = questionnaire_nb_questions_on_page($this->questions,
                                        $this->questionsbysec[$formdata->sec], $formdata->rid);
                    }
                    $SESSION->questionnaire->nbquestionsonpage = $nbquestionsonpage;
                }
            }
        }

        if (!empty($formdata->prev)) {
            $this->response_delete($formdata->rid, $formdata->sec);

            // If skip logic and this is last page reached with no questions,
            // unlock questionnaire->end to allow navigate back to previous page.
            if (isset($SESSION->questionnaire->end) && $SESSION->questionnaire->end == true) {
                $SESSION->questionnaire->end = false;
                $formdata->sec --;
            }

                $formdata->rid = $this->response_insert($this->survey->id, $formdata->sec, $formdata->rid, $quser);
            // Prevent navigation to previous page if wrong format in answered questions).
            $msg = $this->response_check_format($formdata->sec, $formdata, $checkmissing = false, $checkwrongformat = true);
            if ( $msg ) {
                $formdata->prev = '';
            } else {
                $formdata->sec--;
                // Skip logic.
                if (questionnaire_has_dependencies($this->questions)) {
                    $nbquestionsonpage = questionnaire_nb_questions_on_page($this->questions,
                                    $this->questionsbysec[$formdata->sec], $formdata->rid);
                    while (count($nbquestionsonpage) == 0) {
                        $formdata->sec--;
                        $nbquestionsonpage = questionnaire_nb_questions_on_page($this->questions,
                                        $this->questionsbysec[$formdata->sec], $formdata->rid);
                    }
                    $SESSION->questionnaire->nbquestionsonpage = $nbquestionsonpage;
                }
            }
        }

        if (!empty($formdata->rid)) {
            $this->response_import_sec($formdata->rid, $formdata->sec, $formdata);
        }

        $formdatareferer = !empty($formdata->referer) ? htmlspecialchars($formdata->referer) : '';
        $formdatarid = isset($formdata->rid) ? $formdata->rid : '0';
        echo '<div class="generalbox">';
        echo '
                <form id="phpesp_response" method="post" action="'.$action.'">
                <div>
                <input type="hidden" name="referer" value="'.$formdatareferer.'" />
                <input type="hidden" name="a" value="'.$this->id.'" />
                <input type="hidden" name="sid" value="'.$this->survey->id.'" />
                <input type="hidden" name="rid" value="'.$formdatarid.'" />
                <input type="hidden" name="sec" value="'.$formdata->sec.'" />
                <input type="hidden" name="sesskey" value="'.sesskey().'" />
                </div>
            ';
        if (isset($this->questions) && $numsections) { // Sanity check.
            $this->survey_render($formdata->sec, $msg, $formdata);
            echo '<div class="notice" style="padding: 0.5em 0 0.5em 0.2em;"><div class="buttons">';
            if ($formdata->sec > 1) {
                echo '<input type="submit" name="prev" value="<<&nbsp;'.get_string('previouspage', 'questionnaire').'" />';
            }
            if ($this->resume) {
                echo '<input type="submit" name="resume" value="'.get_string('save', 'questionnaire').'" />';
            }

            // Add a 'hidden' variable for the mod's 'view.php', and use a language variable for the submit button.

            if ($formdata->sec == $numsections) {
                echo '
                    <div><input type="hidden" name="submittype" value="Submit Survey" />';
                    echo '<input type="submit" name="submit" value="'.get_string('submitsurvey', 'questionnaire').'" /></div>';
            } else {
                echo '&nbsp;<div><input type="submit" name="next" value="'.
                                get_string('nextpage', 'questionnaire').'&nbsp;>>" /></div>';
            }
            echo '</div></div>'; // Divs notice & buttons.
            echo '</form>';
            echo '</div>'; // Div class="generalbox".

            return $msg;
        } else {
            echo '<p>'.get_string('noneinuse', 'questionnaire').'</p>';
            echo '</form>';
            echo '</div>';
        }
    }

    private function survey_render($section = 1, $message = '', &$formdata) {

        $this->usehtmleditor = null;

        if (empty($section)) {
            $section = 1;
        }
        $numsections = isset($this->questionsbysec) ? count($this->questionsbysec) : 0;
        if ($section > $numsections) {
            $formdata->sec = $numsections;
            echo '<div class=warning>'.get_string('finished', 'questionnaire').'</div>';
            return(false);  // Invalid section.
        }

        // Check to see if there are required questions.
        $hasrequired = $this->has_required($section);

        // Find out what question number we are on $i New fix for question numbering.
        $i = 0;
        if ($section > 1) {
            for ($j = 2; $j <= $section; $j++) {
                foreach ($this->questionsbysec[$j - 1] as $question) {
                    if ($question->type_id < QUESPAGEBREAK) {
                        $i++;
                    }
                }
            }
        }

        $this->print_survey_start($message, $section, $numsections, $hasrequired, '', 1);
        foreach ($this->questionsbysec[$section] as $question) {
            if ($question->type_id != QUESSECTIONTEXT) {
                $i++;
            }
            $question->survey_display($formdata, $descendantsdata = '', $i, $this->usehtmleditor);
            // Bug MDL-7292 - Don't count section text as a question number.
            // Process each question.
        }
        // End of questions.

        $this->print_survey_end($section, $numsections);

        return;
    }

    private function print_survey_start($message, $section, $numsections, $hasrequired, $rid='', $blankquestionnaire=false) {
        global $CFG, $DB, $OUTPUT;
        require_once($CFG->libdir.'/filelib.php');
        $userid = '';
        $resp = '';
        $groupname = '';
        $currentgroupid = 0;
        $timesubmitted = '';
        // Available group modes (0 = no groups; 1 = separate groups; 2 = visible groups).
        if ($rid) {
            $courseid = $this->course->id;
            if(MOODLE_RUN_MODE === MOODLE_MODE_HOST){
                $resp = $DB->get_record('questionnaire_response', array('id' => $rid));
            } else {
                $resp = get_remote_questionnaire_response_by_rid($rid);
            }
            if ($resp) {
                if ($this->respondenttype == 'fullname') {
                    $userid = $resp->username;
                    // Display name of group(s) that student belongs to... if questionnaire is set to Groups separate or visible.
                    if (groups_get_activity_groupmode($this->cm, $this->course)) {
                        if ($groups = groups_get_all_groups($courseid, $resp->username)) {
                            if (count($groups) == 1) {
                                $group = current($groups);
                                $currentgroupid = $group->id;
                                $groupname = ' ('.get_string('group').': '.$group->name.')';
                            } else {
                                $groupname = ' ('.get_string('groups').': ';
                                foreach ($groups as $group) {
                                    $groupname .= $group->name.', ';
                                }
                                $groupname = substr($groupname, 0, strlen($groupname) - 2).')';
                            }
                        } else {
                            $groupname = ' ('.get_string('groupnonmembers').')';
                        }
                    }

                    $params = array(
                                    'objectid' => $this->survey->id,
                                    'context' => $this->context,
                                    'courseid' => $this->course->id,
                                    'relateduserid' => $userid,
                                    'other' => array('action' => 'vresp', 'currentgroupid' => $currentgroupid, 'rid' => $rid)
                    );
                    $event = \mod_questionnaire\event\response_viewed::create($params);
                    $event->trigger();
                }
            }
        }
        $ruser = '';
        if ($resp && !$blankquestionnaire) {
            if ($userid) {
                if(MOODLE_RUN_MODE === MOODLE_MODE_HUB){
                    $userid = get_remote_mapping_localuserid($userid);
                }
                $user = $DB->get_record('user', array('id' => $userid));
                if ($user) {
                    $ruser = fullname($user);
                }
            }
            if ($this->respondenttype == 'anonymous') {
                $ruser = '- '.get_string('anonymous', 'questionnaire').' -';
            } else {
                // JR DEV comment following line out if you do NOT want time submitted displayed in Anonymous surveys.
                if ($resp->submitted) {
                    $timesubmitted = '&nbsp;'.get_string('submitted', 'questionnaire').'&nbsp;'.userdate($resp->submitted);
                }
            }
        }
        if ($ruser) {
            echo (get_string('respondent', 'questionnaire').': <strong>'.$ruser.'</strong>');
            if ($this->survey->realm == 'public') {
                // For a public questionnaire, look for the course that used it.
                $coursename = '';
                $sql = 'SELECT q.id, q.course, c.fullname '.
                       'FROM {questionnaire} q, {questionnaire_attempts} qa, {course} c '.
                       'WHERE qa.rid = ? AND q.id = qa.qid AND c.id = q.course';
                $sql_select = " qa.rid = $rid AND q.id = qa.qid AND c.id = q.course";
                if(MOODLE_RUN_MODE === MOODLE_MODE_HOST){
                    $record = $DB->get_record_sql($sql, array($rid));
                } else {
                    $record = get_remote_questionnaire_attempts_course($sql_select);
                }
                if ($record) {
                    $coursename = $record->fullname;
                }
                echo (' '.get_string('course'). ': '.$coursename);
            }
            echo ($groupname);
            echo ($timesubmitted);
        }
        echo '<h3 class="surveyTitle">'.format_text($this->survey->title, FORMAT_HTML).'</h3>';

        // We don't want to display the print icon in the print popup window itself!
        if ($this->capabilities->printblank && $blankquestionnaire && $section == 1) {
            // Open print friendly as popup window.
            $linkname = '&nbsp;'.get_string('printblank', 'questionnaire');
            $title = get_string('printblanktooltip', 'questionnaire');
            $url = '/mod/questionnaire/print.php?qid='.$this->id.'&amp;rid=0&amp;'.'courseid='.$this->course->id.'&amp;sec=1';
            $options = array('menubar' => true, 'location' => false, 'scrollbars' => true, 'resizable' => true,
                    'height' => 600, 'width' => 800, 'title' => $title);
            $name = 'popup';
            $link = new moodle_url($url);
            $action = new popup_action('click', $link, $name, $options);
            $class = "floatprinticon";
            echo $OUTPUT->action_link($link, $linkname, $action, array('class' => $class, 'title' => $title),
                    new pix_icon('t/print', $title));
        }
        if ($section == 1) {
            if ($this->survey->subtitle) {
                echo '<h4 class="surveySubtitle">'.(format_text($this->survey->subtitle, FORMAT_HTML)).'</h4>';
            }
            if ($this->survey->info) {
                $infotext = file_rewrite_pluginfile_urls($this->survey->info, 'pluginfile.php',
                                $this->context->id, 'mod_questionnaire', 'info', $this->survey->id);
                echo '<div class="addInfo">'.format_text($infotext, FORMAT_HTML).'</div>';
            }
        }

        if ($message) {
            echo '<div class="notifyproblem">'.$message.'</div>';
        }
    }

    private function print_survey_end($section, $numsections) {
        $autonum = $this->autonum;
        // If no questions autonumbering.
        if ($autonum < 3) {
            return;
        }
        if ($numsections > 1) {
            $a = new stdClass();
            $a->page = $section;
            $a->totpages = $numsections;
            echo ('<div class="surveyPage">');
            echo get_string('pageof', 'questionnaire', $a).'&nbsp;&nbsp;';
            echo '</div>';
        }
    }

    // Blankquestionnaire : if we are printing a blank questionnaire.
    public function survey_print_render($message = '', $referer='', $courseid, $rid=0, $blankquestionnaire=false) {
        global $USER, $DB, $OUTPUT, $CFG;

        if (! $course = $DB->get_record("course", array("id" => $courseid))) {
            print_error('incorrectcourseid', 'questionnaire');
        }

        $this->course = $course;

        if (!empty($rid)) {
            // If we're viewing a response, use this method.
            $this->view_response($rid, $referer, $blankquestionnaire);
            return;
        }

        if (empty($section)) {
            $section = 1;
        }

        if (isset($this->questionsbysec)) {
            $numsections = count($this->questionsbysec);
        } else {
            $numsections = 0;
        }

        if ($section > $numsections) {
            return(false);  // Invalid section.
        }

        $hasrequired = $this->has_required();

        // Find out what question number we are on $i.
        $i = 1;
        for ($j = 2; $j <= $section; $j++) {
            $i += count($this->questionsbysec[$j - 1]);
        }

        $action = $CFG->wwwroot.'/mod/questionnaire/preview.php?id='.$this->cm->id;
        echo '<form id="phpesp_response" method="post" action="'.$action.'">
                                ';
        // Print all sections.
        $formdata = new stdClass();
        $errors = 1;
        if (data_submitted()) {
            $formdata = data_submitted();
            $pageerror = '';
            $s = 1;
            $errors = 0;
            foreach ($this->questionsbysec as $section) {
                $errormessage = $this->response_check_format($s, $formdata);
                if ($errormessage) {
                    if ($numsections > 1) {
                        $pageerror = get_string('page', 'questionnaire').' '.$s.' : ';
                    }
                    echo '<div class="notifyproblem">'.$pageerror.$errormessage.'</div>';
                    $errors++;
                }
                $s ++;
            }
        }

        echo $OUTPUT->box_start();

        $this->print_survey_start($message, $section = 1, 1, $hasrequired, $rid = '');

        $descendantsandchoices = array();

        if ($referer == 'preview' && questionnaire_has_dependencies($this->questions) ) {
                $descendantsandchoices = questionnaire_get_descendants_and_choices($this->questions);
        }
        if ($errors == 0) {
            echo '<div class="message">'.get_string('submitpreviewcorrect', 'questionnaire').'</div>';
        }

        $page = 1;
        foreach ($this->questionsbysec as $section) {
            if ($numsections > 1) {
                echo ('<div class="surveyPage">'.get_string('page', 'questionnaire').' '.$page.'</div>');
                $page++;
            }
            foreach ($section as $question) {
                $descendantsdata = array();
                if ($question->type_id == QUESSECTIONTEXT) {
                    $i--;
                }
                if ($referer == 'preview' && $descendantsandchoices && ($question->type_id == QUESYESNO
                                || $question->type_id == QUESRADIO || $question->type_id == QUESDROP) ) {
                    if (isset ($descendantsandchoices['descendants'][$question->id])) {
                        $descendantsdata['descendants'] = $descendantsandchoices['descendants'][$question->id];
                        $descendantsdata['choices'] = $descendantsandchoices['choices'][$question->id];
                    }
                }

                $question->survey_display($formdata, $descendantsdata, $i++, $usehtmleditor = null, $blankquestionnaire, $referer);
            }
        }
        // End of questions.
        if ($referer == 'preview' && !$blankquestionnaire) {
            $url = $CFG->wwwroot.'/mod/questionnaire/preview.php?id='.$this->cm->id;
            echo '
                    <div>
                        <input type="submit" name="submit" value="'.get_string('submitpreview', 'questionnaire').'" />
                        <a href="'.$url.'">'.get_string('reset').'</a>
                    </div>
                ';
        }
        echo $OUTPUT->box_end();
        return;
    }

    public function survey_update($sdata) {
        global $DB;

        $errstr = ''; // TODO: notused!

        // New survey.
        if (empty($this->survey->id)) {
            // Create a new survey in the database.
            $fields = array('name', 'realm', 'title', 'subtitle', 'email', 'theme', 'thanks_page', 'thank_head',
                            'thank_body', 'feedbacknotes', 'info', 'feedbacksections', 'feedbackscores', 'chart_type');
            // Theme field deprecated.
            $record = new stdClass();
            $record->id = 0;
            $record->owner = $sdata->owner;
            foreach ($fields as $f) {
                if (isset($sdata->$f)) {
                    $record->$f = $sdata->$f;
                }
            }

            $this->survey = new stdClass();
            $this->survey->id = $DB->insert_record('questionnaire_survey', $record);
            $this->add_survey($this->survey->id);

            if (!$this->survey->id) {
                $errstr = get_string('errnewname', 'questionnaire') .' [ :  ]'; // TODO: notused!
                return(false);
            }
        } else {
            if (empty($sdata->name) || empty($sdata->title)
                    || empty($sdata->realm)) {
                return(false);
            }
            if (!isset($sdata->chart_type)) {
                $sdata->chart_type = '';
            }

            $fields = array('name', 'realm', 'title', 'subtitle', 'email', 'theme', 'thanks_page',
                    'thank_head', 'thank_body', 'feedbacknotes', 'info', 'feedbacksections', 'feedbackscores', 'chart_type');
            $name = $DB->get_field('questionnaire_survey', 'name', array('id' => $this->survey->id));

            // Trying to change survey name.
            if (trim($name) != trim(stripslashes($sdata->name))) {  // $sdata will already have slashes added to it.
                $count = $DB->count_records('questionnaire_survey', array('name' => $sdata->name));
                if ($count != 0) {
                    $errstr = get_string('errnewname', 'questionnaire');  // TODO: notused!
                    return(false);
                }
            }

            // UPDATE the row in the DB with current values.
            $surveyrecord = new stdClass();
            $surveyrecord->id = $this->survey->id;
            foreach ($fields as $f) {
                $surveyrecord->$f = trim($sdata->{$f});
            }

            $result = $DB->update_record('questionnaire_survey', $surveyrecord);
            if (!$result) {
                $errstr = get_string('warning', 'questionnaire').' [ :  ]';  // TODO: notused!
                return(false);
            }
        }

        return($this->survey->id);
    }

    /* Creates an editable copy of a survey. */
    public function survey_copy($owner) {
        global $DB;

        // Clear the sid, clear the creation date, change the name, and clear the status.
        $survey = clone($this->survey);

        unset($survey->id);
        $survey->owner = $owner;
        // Make sure that the survey name is not larger than the field size (CONTRIB-2999). Leave room for extra chars.
        $survey->name = core_text::substr($survey->name, 0, (64 - 10));

        $survey->name .= '_copy';
        $survey->status = 0;

        // Check for 'name' conflict, and resolve.
        $i = 0;
        $name = $survey->name;
        while ($DB->count_records('questionnaire_survey', array('name' => $name)) > 0) {
            $name = $survey->name.(++$i);
        }
        if ($i) {
            $survey->name .= $i;
        }

        // Create new survey.
        if (!($newsid = $DB->insert_record('questionnaire_survey', $survey))) {
            return(false);
        }

        // Make copies of all the questions.
        $pos = 1;
        // Skip logic: some changes needed here for dependencies down below.
        $qidarray = array();
        $cidarray = array();
        foreach ($this->questions as $question) {
            // Fix some fields first.
            $oldid = $question->id;
            unset($question->id);
            $question->survey_id = $newsid;
            $question->position = $pos++;

            // Copy question to new survey.
            if (!($newqid = $DB->insert_record('questionnaire_question', $question))) {
                return(false);
            }
            $qidarray[$oldid] = $newqid;
            foreach ($question->choices as $key => $choice) {
                $oldcid = $key;
                unset($choice->id);
                $choice->question_id = $newqid;
                if (!$newcid = $DB->insert_record('questionnaire_quest_choice', $choice)) {
                    return(false);
                }
                $cidarray[$oldcid] = $newcid;
            }
        }
        // Skip logic: now we need to set the new values for dependencies.
        if ($newquestions = $DB->get_records('questionnaire_question', array('survey_id' => $newsid), 'id')) {
            foreach ($newquestions as $question) {
                if ($question->dependquestion != 0) {
                    $dependqtypeid = $this->questions[$question->dependquestion]->type_id;
                    $record = new stdClass();
                    $record->id = $question->id;
                    $record->dependquestion = $qidarray[$question->dependquestion];
                    if ($dependqtypeid != 1) {
                        $record->dependchoice = $cidarray[$question->dependchoice];
                    }
                    $DB->update_record('questionnaire_question', $record);
                }
            }
        }

        return($newsid);
    }

    // RESPONSE LIBRARY.

    private function response_check_format($section, $formdata, $checkmissing = true, $checkwrongformat = true) {
        global $PAGE, $OUTPUT;
        $missing = 0;
        $strmissing = '';     // Missing questions.
        $wrongformat = 0;
        $strwrongformat = ''; // Wrongly formatted questions (Numeric, 5:Check Boxes, Date).
        $i = 1;
        for ($j = 2; $j <= $section; $j++) {
            // ADDED A SIMPLE LOOP FOR MAKING SURE PAGE BREAKS (type 99) AND LABELS (type 100) ARE NOT ALLOWED.
            foreach ($this->questionsbysec[$j - 1] as $sectionrecord) {
                $tid = $sectionrecord->type_id;
                if ($tid < QUESPAGEBREAK) {
                    $i++;
                }
            }
        }
        $qnum = $i - 1;

        foreach ($this->questionsbysec[$section] as $question) {
            $qid = $question->id;
            $tid = $question->type_id;
            $lid = $question->length;
            $pid = $question->precise;
            if ($tid != QUESSECTIONTEXT) {
                $qnum++;
            }
            if (!$question->response_complete($formdata)) {
                $missing++;
                $strmissing .= get_string('num', 'questionnaire').$qnum.'. ';
            }
            if (!$question->response_valid($formdata)) {
                $wrongformat++;
                $strwrongformat .= get_string('num', 'questionnaire').$qnum.'. ';
            }
        }
        $message = '';
        $nonumbering = false;
        $autonum = $this->autonum;
        // If no questions autonumbering do not display missing question(s) number(s).
        if ($autonum != 1 && $autonum != 3) {
            $nonumbering = true;
        }
        if ($checkmissing && $missing) {
            if ($nonumbering) {
                $strmissing = '';
            }
            if ($missing == 1) {
                $message = get_string('missingquestion', 'questionnaire').$strmissing;
            } else {
                $message = get_string('missingquestions', 'questionnaire').$strmissing;
            }
            if ($wrongformat) {
                $message .= '<br />';
            }
        }
        if ($checkwrongformat && $wrongformat) {
            if ($nonumbering) {
                $message .= get_string('wronganswers', 'questionnaire');
            } else {
                if ($wrongformat == 1) {
                    $message .= get_string('wrongformat', 'questionnaire').$strwrongformat;
                } else {
                    $message .= get_string('wrongformats', 'questionnaire').$strwrongformat;
                }
            }
        }
        return ($message);
    }

    private function response_delete($rid, $sec = null) {
        global $DB;

        if (empty($rid)) {
            return;
        }

        if ($sec != null) {
            if ($sec < 1) {
                return;
            }

            // Skip logic.
            $numsections = isset($this->questionsbysec) ? count($this->questionsbysec) : 0;
            $sec = min($numsections , $sec);

            /* get question_id's in this section */
            $qids = array();
            foreach ($this->questionsbysec[$sec] as $question) {
                $qids[] = $question->id;
            }
            if (empty($qids)) {
                return;
            } else {
                if(MOODLE_RUN_MODE === MOODLE_MODE_HOST){
                    list($qsql, $params) = $DB->get_in_or_equal($qids);
                    $qsql = ' AND question_id ' . $qsql;
                } else {
                    $qsql = implode(',', $qids);
                    $qsql = ' AND question_id IN (' . $qsql . ')';
                }
            }

        } else {
            /* delete all */
            $qsql = '';
            $params = array();
        }

        /* delete values */
        $select = 'response_id = \'' . $rid . '\' ' . $qsql;
        foreach (array('response_bool', 'resp_single', 'resp_multiple', 'response_rank', 'response_text',
                       'response_other', 'response_date') as $tbl) {
            if(MOODLE_RUN_MODE === MOODLE_MODE_HOST){
                $DB->delete_records_select('questionnaire_'.$tbl, $select, $params);
            } else {
                delete_remote_response_by_tbl('questionnaire_'.$tbl, $select);
            }
        }
    }

    private function response_import_sec($rid, $sec, &$varr) {
        if ($sec < 1 || !isset($this->questionsbysec[$sec])) {
            return;
        }
        $vals = $this->response_select($rid, 'content');
        reset($vals);
        foreach ($vals as $id => $arr) {
            if (isset($arr[0]) && is_array($arr[0])) {
                // Multiple.
                $varr->{'q'.$id} = array_map('array_pop', $arr);
            } else {
                $varr->{'q'.$id} = array_pop($arr);
            }
        }
    }

    private function response_import_all($rid, &$varr) {

        $vals = $this->response_select($rid, 'content');
        reset($vals);
        foreach ($vals as $id => $arr) {
            if (strstr($id, '_') && isset($arr[4])) { // Single OR multiple with !other choice selected.
                $varr->{'q'.$id} = $arr[4];
            } else {
                if (isset($arr[0]) && is_array($arr[0])) { // Multiple.
                    $varr->{'q'.$id} = array_map('array_pop', $arr);
                } else { // Boolean, rate and other.
                    $varr->{'q'.$id} = array_pop($arr);
                }
            }
        }
    }

    private function response_commit($rid) {
        global $DB;

        if(MOODLE_RUN_MODE === MOODLE_MODE_HOST){
            $record = new stdClass();
            $record->id = $rid;
            $record->complete = 'y';
            $record->submitted = time();

            if ($this->grade < 0) {
                $record->grade = 1;  // Don't know what to do if its a scale...
            } else {
                $record->grade = $this->grade;
            }
            return $DB->update_record('questionnaire_response', $record);
        } else {
            $data = array();
            $data['data[0][name]'] = 'complete';
            $data['data[0][value]'] = 'y';
            $data['data[1][name]'] = 'submitted';
            $data['data[1][value]'] = time();

            if ($this->grade < 0) {
                // Don't know what to do if its a scale...
                $data['data[2][name]'] = 'grade';
                $data['data[2][value]'] = 1;
            } else {
                $data['data[2][name]'] = 'grade';
                $data['data[2][value]'] = $this->grade;
            }

            return update_remote_response_by_tbl('questionnaire_response', $rid, $data);
        }
    }

    private function get_response($username, $rid = 0) {
        global $DB;
        $isremote = MOODLE_RUN_MODE === MOODLE_MODE_HOST ? true : false;
        $rid = intval($rid);
        if ($rid != 0) {
            // Check for valid rid.
            $fields = 'id, username';
            $select = 'id = '.$rid.' AND survey_id = '.$this->sid.' AND username = \''.$username.'\' AND complete = \'n\'';
            if($isremote){
                return ($DB->get_record_select('questionnaire_response', $select, null, $fields) !== false) ? $rid : '';
            } else {
                $select = 'R.id = '.$rid.' AND R.survey_id = '.$this->sid.' AND R.username = \''.get_remote_mapping_user($username)[0]->id.'\' AND R.complete = \'n\'';
                return (get_remote_questionnaire_response($select) !== false) ? $rid : '';
            }
        } else {
            // Find latest in progress rid.
            $select = 'survey_id = '.$this->sid.' AND complete = \'n\' AND username = \''.$username.'\'';
            if($isremote){
                $records = $DB->get_records_select('questionnaire_response', $select, null, 'submitted DESC',
                    'id,survey_id', 0, 1);
            } else {
                $select = 'R.survey_id = '.$this->sid.' AND R.complete = \'n\' AND R.username = \''.get_remote_mapping_user($username)[0]->id.'\'';
                $records = get_remote_questionnaire_response($select, 'R.submitted DESC');
            }
            if ($records) {
                $rec = reset($records);
                return $rec->id;
            } else {
                return '';
            }
        }
    }

    // Returns the number of the section in which questions have been answered in a response.
    private function response_select_max_sec($rid) {
        global $DB;

        $pos = $this->response_select_max_pos($rid);
        $select = 'survey_id = \''.$this->sid.'\' AND type_id = 99 AND position < '.$pos.' AND deleted = \'n\'';
        $max = $DB->count_records_select('questionnaire_question', $select) + 1;

        return $max;
    }

    // Returns the position of the last answered question in a response.
    private function response_select_max_pos($rid) {
        global $DB;

        $max = 0;

        foreach (array('response_bool', 'resp_single', 'resp_multiple', 'response_rank', 'response_text',
                       'response_other', 'response_date') as $tbl) {
            $sql = 'SELECT MAX(q.position) as num FROM {questionnaire_'.$tbl.'} a, {questionnaire_question} q '.
                   'WHERE a.response_id = ? AND '.
                   'q.id = a.question_id AND '.
                   'q.survey_id = ? AND '.
                   'q.deleted = \'n\'';
            if ($record = $DB->get_record_sql($sql, array($rid, $this->sid))) {
                $newmax = (int)$record->num;
                if ($newmax > $max) {
                    $max = $newmax;
                }
            }
        }
        return $max;
    }

    /* {{{ proto array response_select_name(int survey_id, int response_id, array question_ids)
       A wrapper around response_select(), that returns an array of
       key/value pairs using the field name as the key.
       $csvexport = true: a parameter to return a different response formatting for CSV export from normal report formatting
     */
    private function response_select_name($rid, $choicecodes, $choicetext) {
        $res = $this->response_select($rid, 'position, type_id, name', true, $choicecodes, $choicetext);
        $nam = array();
        reset($res);
        $subqnum = 0;
        $oldpos = '';
        while (list($qid, $arr) = each($res)) {
            // Question position (there may be "holes" in positions list).
            $qpos = $arr[0];
            // Question type (1-bool,2-text,3-essay,4-radio,5-check,6-dropdn,7-rating(not used),8-rate,9-date,10-numeric).
            $qtype = $arr[1];
            // Variable name; (may be empty); for rate questions: 'variable group' name.
            $qname = $arr[2];
            // Modality; for rate questions: variable.
            $qchoice = $arr[3];

            // Strip potential html tags from modality name.
            if (!empty($qchoice)) {
                $qchoice = strip_tags($arr[3]);
                $qchoice = preg_replace("/[\r\n\t]/", ' ', $qchoice);
            }
            // For rate questions: modality; for multichoice: selected = 1; not selected = 0.
            $q4 = '';
            if (isset($arr[4])) {
                $q4 = $arr[4];
            }
            if (strstr($qid, '_')) {
                if ($qtype == QUESRADIO) {     // Single.
                    $nam[$qpos][$qname.'_'.get_string('other', 'questionnaire')] = $q4;
                    continue;
                }
                // Multiple OR rank.
                if ($oldpos != $qpos) {
                    $subqnum = 1;
                    $oldpos = $qpos;
                } else {
                        $subqnum++;
                }
                if ($qtype == QUESRATE) {     // Rate.
                    $qname .= "->$qchoice";
                    if ($q4 == -1) {
                        // Here $q4 = get_string('notapplicable', 'questionnaire'); DEV JR choose one solution please.
                        $q4 = '';
                    } else {
                        if (is_numeric($q4)) {
                            $q4++;
                        }
                    }
                } else {     // Multiple.
                    $qname .= "->$qchoice";
                }
                $nam[$qpos][$qname] = $q4;
                continue;
            }
            $val = $qchoice;
            $nam[$qpos][$qname] = $val;
        }
        return $nam;
    }

    private function response_send_email($rid, $userid=false) {
        global $CFG, $USER, $DB;

        require_once($CFG->libdir.'/phpmailer/class.phpmailer.php');

        $name = s($this->name);
        if ($record = $DB->get_record('questionnaire_survey', array('id' => $this->survey->id))) {
            $email = $record->email;
        } else {
            $email = '';
        }

        if (empty($email)) {
            return(false);
        }
        $answers = $this->generate_csv($rid, $userid = '', null, 1, $groupid = 0);

        // Line endings for html and plaintext emails.
        $endhtml = "\r\n<br>";
        $endplaintext = "\r\n";

        $subject = get_string('surveyresponse', 'questionnaire') .": $name [$rid]";
        $url = $CFG->wwwroot.'/mod/questionnaire/report.php?action=vresp&amp;sid='.$this->survey->id.
                '&amp;rid='.$rid.'&amp;instance='.$this->id;

        // Html and plaintext body.
        $bodyhtml        = '<a href="'.$url.'">'.$url.'</a>'.$endhtml;
        $bodyplaintext   = $url.$endplaintext;
        $bodyhtml       .= get_string('surveyresponse', 'questionnaire') .' "'.$name.'"'.$endhtml;
        $bodyplaintext  .= get_string('surveyresponse', 'questionnaire') .' "'.$name.'"'.$endplaintext;

        reset($answers);

        for ($i = 0; $i < count($answers[0]); $i++) {
            $sep = ' : ';

            switch($i) {
                case 1:
                    $sep = ' ';
                    break;
                case 4:
                    $bodyhtml        .= get_string('user').' ';
                    $bodyplaintext   .= get_string('user').' ';
                    break;
                case 6:
                    if ($this->respondenttype != 'anonymous') {
                        $bodyhtml         .= get_string('email').$sep.$USER->email. $endhtml;
                        $bodyplaintext    .= get_string('email').$sep.$USER->email. $endplaintext;
                    }
            }
            $bodyhtml         .= $answers[0][$i].$sep.$answers[1][$i]. $endhtml;
            $bodyplaintext    .= $answers[0][$i].$sep.$answers[1][$i]. $endplaintext;
        }

        // Use plaintext version for altbody.
        $altbody = "\n$bodyplaintext\n";

        $return = true;
        $mailaddresses = preg_split('/,|;/', $email);
        foreach ($mailaddresses as $email) {
            $userto = new stdClass();
            $userto->email = $email;
            $userto->mailformat = 1;
            // Dummy userid to keep email_to_user happy in moodle 2.6.
            $userto->id = -10;
            $userfrom = $CFG->noreplyaddress;
            if (email_to_user($userto, $userfrom, $subject, $altbody, $bodyhtml)) {
                $return = $return && true;
            } else {
                $return = false;
            }
        }
        return $return;
    }

    public function response_insert($sid, $section, $rid, $userid, $resume=false) {
        global $DB, $USER;
        if(MOODLE_RUN_MODE === MOODLE_MODE_HOST){
            $record = new stdClass();
            $record->submitted = time();

            if (empty($rid)) {
                // Create a uniqe id for this response.
                $record->survey_id = $sid;
                $record->username = $userid;
                $rid = $DB->insert_record('questionnaire_response', $record);
            } else {
                $record->id = $rid;
                $DB->update_record('questionnaire_response', $record);
            }
        } else {
            $data = array();
            $data['data[0][name]'] = 'submitted';
            $data['data[0][value]'] = time();

            if (empty($rid)) {
                $data['data[1][name]'] = 'survey_id';
                $data['data[1][value]'] = $sid;
                $data['data[2][name]'] = 'username';
                $data['data[2][value]'] = get_remote_mapping_user($userid)[0]->id;

                $rid = save_remote_response_by_tbl('questionnaire_response', $data);
            } else {
                update_remote_response_by_tbl('questionnaire_response',$rid, $data);
            }
        }

        if ($resume) {
            // Log this saved response.
            // Needed for the event logging.
            $context = context_module::instance($this->cm->id);
            $anonymous = $this->respondenttype == 'anonymous';
            $params = array(
                            'context' => $context,
                            'courseid' => $this->course->id,
                            'relateduserid' => $userid,
                            'anonymous' => $anonymous,
                            'other' => array('questionnaireid' => $this->id)
            );
            $event = \mod_questionnaire\event\attempt_saved::create($params);
            $event->trigger();
        }

        if (!empty($this->questionsbysec[$section])) {
            foreach ($this->questionsbysec[$section] as $question) {
                // NOTE *** $val really should be a value obtained from the caller or somewhere else.
                // Note that "optional_param" accepting arrays is deprecated for optional_param_array.
                if ($question->response_table == 'resp_multiple') {
                    $val = optional_param_array('q'.$question->id, '', PARAM_RAW);
                } else {
                    $val = optional_param('q'.$question->id, '', PARAM_RAW);
                }
                $question->insert_response($rid, $val);
            }
        }
        return($rid);
    }

    private function response_select($rid, $col = null, $csvexport = false, $choicecodes=0, $choicetext=1) {
        global $DB;

        $sid = $this->survey->id;
        $values = array();
        $stringother = get_string('other', 'questionnaire');
        if ($col == null) {
            $col = '';
        }
        if (!is_array($col) && !empty($col)) {
            $col = explode(',', preg_replace("/\s/", '', $col));
        }
        if (is_array($col) && count($col) > 0) {
            $col = ',' . implode(',', array_map(create_function('$a', 'return "q.$a";'), $col));
        }

        // Response_bool (yes/no).
		if(MOODLE_RUN_MODE === MOODLE_MODE_HOST){
			$sql = 'SELECT q.id '.$col.', a.choice_id '.
               'FROM {questionnaire_response_bool} a, {questionnaire_question} q '.
               'WHERE a.response_id= ? AND a.question_id=q.id ';
			$records = $DB->get_records_sql($sql, array($rid));
		} else {
			$select = 'a.response_id='.$rid.'  AND a.question_id=q.id';
			$records = get_remote_questionnaire_bool_question($select);
		}
        if ($records) {
            foreach ($records as $qid => $row) {
				if(MOODLE_RUN_MODE !== MOODLE_MODE_HOST){
                	$qid = $row->id;
				}
                $choice = $row->choice_id;
                if (isset ($row->name) && $row->name == '') {
                    $noname = true;
                }
                unset ($row->id);
                unset ($row->choice_id);
                $row = (array)$row;
                $newrow = array();
                foreach ($row as $key => $val) {
                    if (!is_numeric($key)) {
                        $newrow[] = $val;
                    }
                }
                $values[$qid] = $newrow;
                array_push($values["$qid"], ($choice == 'y') ? '1' : '0');
                if (!$csvexport) {
                    array_push($values["$qid"], $choice); // DEV still needed for responses display.
                }
            }
        }

        // Response_single (radio button or dropdown).
		if(MOODLE_RUN_MODE === MOODLE_MODE_HOST){
			$sql = 'SELECT q.id '.$col.', q.type_id as q_type, c.content as ccontent,c.id as cid '.
               'FROM {questionnaire_resp_single} a, {questionnaire_question} q, {questionnaire_quest_choice} c '.
               'WHERE a.response_id = ? AND a.question_id=q.id AND a.choice_id=c.id ';
			$records = $DB->get_records_sql($sql, array($rid));
		} else {
			$select = 'a.response_id = '.$rid.' AND a.question_id=q.id AND a.choice_id=c.id';
			$records = get_remote_questionnaire_single_question_choice($select);
		}
        if ($records) {
            foreach ($records as $row) {
                $qid = $row->id;
                $cid = $row->cid;
                $qtype = $row->q_type;
                if ($csvexport) {
                    static $i = 1;
                    $qrecords = $DB->get_records('questionnaire_quest_choice', array('question_id' => $qid));
                    foreach ($qrecords as $value) {
                        if ($value->id == $cid) {
                            $contents = questionnaire_choice_values($value->content);
                            if ($contents->modname) {
                                $row->ccontent = $contents->modname;
                            } else {
                                $content = $contents->text;
                                if (preg_match('/^!other/', $content)) {
                                    $row->ccontent = get_string('other', 'questionnaire');
                                } else if (($choicecodes == 1) && ($choicetext == 1)) {
                                    $row->ccontent = "$i : $content";
                                } else if ($choicecodes == 1) {
                                    $row->ccontent = "$i";
                                } else {
                                    $row->ccontent = $content;
                                }
                            }
                            $i = 1;
                            break;
                        }
                        $i++;
                    }
                }
                unset($row->id);
                unset($row->cid);
                unset($row->q_type);
                $arow = get_object_vars($row);
                $newrow = array();
                foreach ($arow as $key => $val) {
                    if (!is_numeric($key)) {
                        $newrow[] = $val;
                    }
                }
                if (preg_match('/^!other/', $row->ccontent)) {
                    $newrow[] = 'other_' . $cid;
                } else {
                    $newrow[] = (int)$cid;
                }
                $values[$qid] = $newrow;
            }
        }

        // Response_multiple.
		if(MOODLE_RUN_MODE === MOODLE_MODE_HOST){
			$sql = 'SELECT a.id as aid, q.id as qid '.$col.',c.content as ccontent,c.id as cid '.
               'FROM {questionnaire_resp_multiple} a, {questionnaire_question} q, {questionnaire_quest_choice} c '.
               'WHERE a.response_id = ? AND a.question_id=q.id AND a.choice_id=c.id '.
               'ORDER BY a.id,a.question_id,c.id';
            $records = $DB->get_records_sql($sql, array($rid));
		} else {
	        $select = 'a.response_id = '.$rid.' AND a.question_id=q.id AND a.choice_id=c.id';
	        $sort = 'a.id,a.question_id,c.id';
	        $records = get_remote_questionnaire_multiple_question_choice($select, $sort);
		}
        if ($csvexport) {
            $tmp = null;
            if (!empty($records)) {
                $qids2 = array();
                $oldqid = '';
                foreach ($records as $qid => $row) {
                    if ($row->qid != $oldqid) {
                        $qids2[] = $row->qid;
                        $oldqid = $row->qid;
                    }
                }
                list($qsql, $params) = $DB->get_in_or_equal($qids2);
                if(MOODLE_RUN_MODE === MOODLE_MODE_HOST){
                    $sql = 'SELECT * FROM {questionnaire_quest_choice} WHERE question_id ' . $qsql . ' ORDER BY id';
                    $records2 = $DB->get_records_sql($sql, $params);
                } else {
                    $sql_select = 'question_id ' . $qsql;
                    $sql_sort = 'id';
                    $records2 = get_remote_questionnaire_quest_choice_by_condition($sql_select, $sql_sort);
                }
                if ($records2) {
                    foreach ($records2 as $qid => $row2) {
                        $selected = '0';
                        $qid2 = $row2->question_id;
                        $cid2 = $row2->id;
                        $c2 = $row2->content;
                        $otherend = false;
                        if ($c2 == '!other') {
                            $c2 = '!other='.get_string('other', 'questionnaire');
                        }
                        if (preg_match('/^!other/', $c2)) {
                            $otherend = true;
                        } else {
                            $contents = questionnaire_choice_values($c2);
                            if ($contents->modname) {
                                $c2 = $contents->modname;
                            } else if ($contents->title) {
                                $c2 = $contents->title;
                            }
                        }

                        if(MOODLE_RUN_MODE === MOODLE_MODE_HOST){
                            $sql = 'SELECT a.name as name, a.type_id as q_type, a.position as pos ' .
                                'FROM {questionnaire_question} a WHERE id = ?';
                            $currentquestion = $DB->get_records_sql($sql, array($qid2));
                        } else {
                            $sql_select = "id = $qid2";
                            $currentquestion = get_remote_questionnaire_question($sql_select);
                        }
                        if ($currentquestion) {
                            foreach ($currentquestion as $question) {
                                $name1 = $question->name;
                                $type1 = $question->q_type;
                            }
                        }
                        $newrow = array();
                        foreach ($records as $qid => $row1) {
                            $qid1 = $row1->qid;
                            $cid1 = $row1->cid;
                            // If available choice has been selected by student.
                            if ($qid1 == $qid2 && $cid1 == $cid2) {
                                $selected = '1';
                            }
                        }
                        if ($otherend) {
                            $newrow2 = array();
                            $newrow2[] = $question->pos;
                            $newrow2[] = $type1;
                            $newrow2[] = $name1;
                            $newrow2[] = '['.get_string('other', 'questionnaire').']';
                            $newrow2[] = $selected;
                            $tmp2 = $qid2.'_other';
                            $values["$tmp2"] = $newrow2;
                        }
                        $newrow[] = $question->pos;
                        $newrow[] = $type1;
                        $newrow[] = $name1;
                        $newrow[] = $c2;
                        $newrow[] = $selected;
                        $tmp = $qid2.'_'.$cid2;
                        $values["$tmp"] = $newrow;
                    }
                }
            }
            unset($tmp);
            unset($row);

        } else {
                $arr = array();
                $tmp = null;
            if (!empty($records)) {
                foreach ($records as $aid => $row) {
                    $qid = $row->qid;
                    $cid = $row->cid;
                    unset($row->aid);
                    unset($row->qid);
                    unset($row->cid);
                    $arow = get_object_vars($row);
                    $newrow = array();
                    foreach ($arow as $key => $val) {
                        if (!is_numeric($key)) {
                            $newrow[] = $val;
                        }
                    }
                    if (preg_match('/^!other/', $row->ccontent)) {
                        $newrow[] = 'other_' . $cid;
                    } else {
                        $newrow[] = (int)$cid;
                    }
                    if ($tmp == $qid) {
                        $arr[] = $newrow;
                        continue;
                    }
                    if ($tmp != null) {
                        $values["$tmp"] = $arr;
                    }
                    $tmp = $qid;
                    $arr = array($newrow);
                }
            }
            if ($tmp != null) {
                $values["$tmp"] = $arr;
            }
            unset($arr);
            unset($tmp);
            unset($row);
        }

            // Response_other.
            // This will work even for multiple !other fields within one question
            // AND for identical !other responses in different questions JR.
		if(MOODLE_RUN_MODE === MOODLE_MODE_HOST){
			$sql = 'SELECT c.id as cid, c.content as content, a.response as aresponse, q.id as qid, q.position as position,
                                    q.type_id as type_id, q.name as name '.
               'FROM {questionnaire_response_other} a, {questionnaire_question} q, {questionnaire_quest_choice} c '.
               'WHERE a.response_id= ? AND a.question_id=q.id AND a.choice_id=c.id '.
               'ORDER BY a.question_id,c.id ';
			$records = $DB->get_records_sql($sql, array($rid));
		} else {
	        $select = 'a.response_id= '.$rid.' AND a.question_id=q.id AND a.choice_id=c.id ';
	        $sort = 'a.question_id,c.id';
			$records = get_remote_questionnaire_other_question_choice($select, $sort);
		}
        if ($records) {
            foreach ($records as $record) {
                $newrow = array();
                $position = $record->position;
                $typeid = $record->type_id;
                $name = $record->name;
                $cid = $record->cid;
                $qid = $record->qid;
                $content = $record->content;

                // The !other modality with no label.
                if ($content == '!other') {
                    $content = '!other='.$stringother;
                }
                $content = substr($content, 7);
                $aresponse = $record->aresponse;
                // The first two empty values are needed for compatibility with "normal" (non !other) responses.
                // They are only needed for the CSV export, in fact.
                $newrow[] = $position;
                $newrow[] = $typeid;
                $newrow[] = $name;
                $content = $stringother;
                $newrow[] = $content;
                $newrow[] = $aresponse;
                $values["${qid}_${cid}"] = $newrow;
            }
        }

        // Response_rank.
		if(MOODLE_RUN_MODE === MOODLE_MODE_HOST){ 
			$sql = 'SELECT a.id as aid, q.id AS qid, q.precise AS precise, c.id AS cid '.$col.', c.content as ccontent,
                                a.rank as arank '.
               'FROM {questionnaire_response_rank} a, {questionnaire_question} q, {questionnaire_quest_choice} c '.
               'WHERE a.response_id= ? AND a.question_id=q.id AND a.choice_id=c.id '.
               'ORDER BY aid, a.question_id, c.id';
			$records = $DB->get_records_sql($sql, array($rid));
		} else {
	        $select = 'a.response_id= '.$rid.' AND a.question_id=q.id AND a.choice_id=c.id';
	        $sort = 'aid, a.question_id, c.id';
			$records = get_remote_questionnaire_rank_question_choice($select, $sort);
		}
        if ($records) {
            foreach ($records as $row) {
                // Next two are 'qid' and 'cid', each with numeric and hash keys.
                $osgood = false;
                if ($row->precise == 3) {
                    $osgood = true;
                }
                $qid = $row->qid.'_'.$row->cid;
                unset($row->aid); // Get rid of the answer id.
                unset($row->qid);
                unset($row->cid);
                unset($row->precise);
                $row = (array)$row;
                $newrow = array();
                foreach ($row as $key => $val) {
                    if ($key != 'content') { // No need to keep question text - ony keep choice text and rank.
                        if ($key == 'ccontent') {
                            if ($osgood) {
                                list($contentleft, $contentright) = array_merge(preg_split('/[|]/', $val), array(' '));
                                $contents = questionnaire_choice_values($contentleft);
                                if ($contents->title) {
                                    $contentleft = $contents->title;
                                }
                                $contents = questionnaire_choice_values($contentright);
                                if ($contents->title) {
                                    $contentright = $contents->title;
                                }
                                $val = strip_tags($contentleft.'|'.$contentright);
                                $val = preg_replace("/[\r\n\t]/", ' ', $val);
                            } else {
                                $contents = questionnaire_choice_values($val);
                                if ($contents->modname) {
                                    $val = $contents->modname;
                                } else if ($contents->title) {
                                    $val = $contents->title;
                                } else if ($contents->text) {
                                    $val = strip_tags($contents->text);
                                    $val = preg_replace("/[\r\n\t]/", ' ', $val);
                                }
                            }
                        }
                        $newrow[] = $val;
                    }
                }
                $values[$qid] = $newrow;
            }
        }

        // Response_text.
		if(MOODLE_RUN_MODE === MOODLE_MODE_HOST){
			$sql = 'SELECT q.id '.$col.', a.response as aresponse '.
               'FROM {questionnaire_response_text} a, {questionnaire_question} q '.
               'WHERE a.response_id=\''.$rid.'\' AND a.question_id=q.id ';
			$records = $DB->get_records_sql($sql);
		} else {
	        $select = 'a.response_id=\''.$rid.'\' AND a.question_id=q.id';
			$records = get_remote_questionnaire_text_question($select);
		}
        if ($records) {
            foreach ($records as $row) {
                $qid = $row->id;
                unset($row->id);
                $row = (array)$row;
                $newrow = array();
                foreach ($row as $key => $val) {
                    if (!is_numeric($key)) {
                        $newrow[] = $val;
                    }
                }
                $values["$qid"] = $newrow;
                $val = array_pop($values["$qid"]);
                array_push($values["$qid"], $val, $val);
            }
        }

        // Response_date.
		if(MOODLE_RUN_MODE === MOODLE_MODE_HOST){
        	$sql = 'SELECT q.id '.$col.', a.response as aresponse '.
               'FROM {questionnaire_response_date} a, {questionnaire_question} q '.
               'WHERE a.response_id=\''.$rid.'\' AND a.question_id=q.id ';
			$records = $DB->get_records_sql($sql);
		} else {
	        $select = 'a.response_id=\''.$rid.'\' AND a.question_id=q.id';
			$records = get_remote_questionnaire_date_question($select);
		}
        if ($records) {
            $dateformat = get_string('strfdate', 'questionnaire');
            foreach ($records as $row) {
                $qid = $row->id;
                unset ($row->id);
                $row = (array)$row;
                $newrow = array();
                foreach ($row as $key => $val) {
                    if (!is_numeric($key)) {
                        $newrow[] = $val;
                        // Convert date from yyyy-mm-dd database format to actual questionnaire dateformat.
                        // does not work with dates prior to 1900 under Windows.
                        if (preg_match('/\d\d\d\d-\d\d-\d\d/', $val)) {
                            $dateparts = preg_split('/-/', $val);
                            $val = make_timestamp($dateparts[0], $dateparts[1], $dateparts[2]); // Unix timestamp.
                            $val = userdate ( $val, $dateformat);
                            $newrow[] = $val;
                        }
                    }
                }
                $values["$qid"] = $newrow;
                $val = array_pop($values["$qid"]);
                array_push($values["$qid"], '', '', $val);
            }
        }
        return($values);
    }

    private function response_goto_thankyou() {
        global $CFG, $USER, $DB;

        $select = 'id = '.$this->survey->id;
        $fields = 'thanks_page, thank_head, thank_body';
        if ($result = $DB->get_record_select('questionnaire_survey', $select, null, $fields)) {
            $thankurl = $result->thanks_page;
            $thankhead = $result->thank_head;
            $thankbody = $result->thank_body;
        } else {
            $thankurl = '';
            $thankhead = '';
            $thankbody = '';
        }
        if (!empty($thankurl)) {
            if (!headers_sent()) {
                header("Location: $thankurl");
                exit;
            }
            echo '
                <script language="JavaScript" type="text/javascript">
                <!--
                window.location="'.$thankurl.'"
                //-->
                </script>
                <noscript>
                <h2 class="thankhead">Thank You for completing this survey.</h2>
                <blockquote class="thankbody">Please click
                <a href="'.$thankurl.'">here</a> to continue.</blockquote>
                </noscript>
            ';
            exit;
        }
        if (empty($thankhead)) {
            $thankhead = get_string('thank_head', 'questionnaire');
        }
        $message = '<h3>'.$thankhead.'</h3>'.format_text(file_rewrite_pluginfile_urls($thankbody, 'pluginfile.php',
                        $this->context->id, 'mod_questionnaire', 'thankbody', $this->survey->id), FORMAT_HTML);
        echo ($message);
        // Default set currentgroup to view all participants.
        // TODO why not set to current respondent's groupid (if any)?
        $currentgroupid = 0;
        $currentgroupid = groups_get_activity_group($this->cm);
        if (!groups_is_member($currentgroupid, $USER->id)) {
            $currentgroupid = 0;
        }
        if ($this->capabilities->readownresponses) {
                echo('<a href="'.$CFG->wwwroot.'/mod/questionnaire/myreport.php?id='.
                    $this->cm->id.'&amp;instance='.$this->cm->instance.'&amp;user='.$USER->id.'&byresponse=0&action=vresp">'.
                    get_string("continue").'</a>');

        } else {
            echo('<a href="'.$CFG->wwwroot.'/course/view.php?id='.$this->course->id.'">'.
                get_string("continue").'</a>');
        }
        return;
    }

    private function response_goto_saved($url) {
        global $CFG;
        $resumesurvey = get_string('resumesurvey', 'questionnaire');
        $savedprogress = get_string('savedprogress', 'questionnaire', '<strong>'.$resumesurvey.'</strong>');

        echo '
                <div class="thankbody">'.$savedprogress.'</div>
                <div class="homelink"><a href="'.$CFG->wwwroot.'/course/view.php?id='.$this->course->id.'">&nbsp;&nbsp;'
                    .get_string("backto", "moodle", $this->course->fullname).'&nbsp;&nbsp;</a></div>
             ';
        return;
    }

    // Survey Results Methods.

    public function survey_results_navbar_alpha($currrid, $currentgroupid, $cm, $byresponse) {
        global $CFG, $DB, $OUTPUT;
        // Is this questionnaire set to fullname or anonymous?
        $isfullname = $this->respondenttype != 'anonymous';
        if ($isfullname) {
            $selectgroupid = '';
            $gmuserid = ', GM.userid ';
            $groupmembers = ', {groups_members} GM ';
            $castsql = $DB->sql_cast_char2int('R.username');
            switch ($currentgroupid) {
                case 0:     // All participants.
                    $gmuserid = '';
                    $groupmembers = '';
                    break;
                default:     // Members of a specific group.
                    $selectgroupid = ' AND GM.groupid='.$currentgroupid.' AND '.$castsql.' = GM.userid ';
            }
            $sql = 'SELECT R.id AS responseid, R.submitted AS submitted, R.username, U.username AS username,
                            U.id as userid '.$gmuserid.
            'FROM {questionnaire_response} R,
                  {user} U
                '.$groupmembers.
            'WHERE R.survey_id='.$this->survey->id.
            ' AND complete = \'y\''.
            ' AND U.id = '.$castsql.
            $selectgroupid.
            'ORDER BY U.lastname, U.firstname, R.submitted DESC';

            $sql_remote = 'R.survey_id='.$this->survey->id. ' AND complete = \'y\' AND U.id = R.username AND M.WWWROOT = \'' . $CFG->wwwroot . '\' ORDER BY U.lastname, U.firstname, R.submitted DESC';
            $anonymous = false;
        } else {
            $sql = 'SELECT R.id AS responseid, R.submitted
                   FROM {questionnaire_response} R
                   WHERE R.survey_id = ?
                   AND complete = ?
                   ORDER BY R.submitted DESC';
            $sql_remote = "R.survey_id = " . $this->survey->id . " AND R.complete = 'y' ";
            $anonymous = true;
        }
        if($ishost = MOODLE_RUN_MODE === MOODLE_MODE_HOST) {
            $responses = $DB->get_records_sql ($sql, array('survey_id' => $this->survey->id, 'complete' => 'y'));
        } else {
            $responses = $anonymous ? get_remote_questionnaire_response($sql_remote, "R.submitted DESC") : get_remote_questionnaire_response_user($sql_remote);
        }
        if (!$responses) {
            return;
        }
        $total = count($responses);
        if ($total === 0) {
            return;
        }
        $rids = array();
        if ($isfullname) {
            $ridssub = array();
            $ridsuserfullname = array();
            $ridsuserid = array();
        }
        $i = 0;
        $currpos = -1;
        foreach ($responses as $response) {

            if(!$ishost ) {
                if($anonymous === true) {
                    $response->responseid = $response->id;
                    $response->userid = $response->username;
                }
                $response->userid = get_remote_mapping_localuserid($response->userid);
            }
            array_push($rids, $response->responseid);
            if ($isfullname) {
                $user = $DB->get_record('user', array('id' => $response->userid));// mapping
                $userfullname = fullname($user);
                array_push($ridssub, $response->submitted);
                array_push($ridsuserfullname, fullname($user));
                array_push($ridsuserid, $response->userid);
            }
            if ($response->responseid == $currrid) {
                $currpos = $i;
            }
            $i++;
        }

        $url = $CFG->wwwroot.'/mod/questionnaire/report.php?action=vresp&amp;group='.$currentgroupid;
        $linkarr = array();
        if (!$byresponse) {     // Display navbar.
            // Build navbar.
            $prevrid = ($currpos > 0) ? $rids[$currpos - 1] : null;
            $nextrid = ($currpos < $total - 1) ? $rids[$currpos + 1] : null;
            $firstrid = $rids[0];
            $lastrid = $rids[$total - 1];
            $displaypos = 1;
            if ($prevrid != null) {
                $pos = $currpos - 1;
                if ($isfullname) {
                    $responsedate = userdate($ridssub[$pos]);
                    $title = $ridsuserfullname[$pos];
                    // Only add date if more than one response by a student.
                    if ($ridsuserid[$pos] == $ridsuserid[$currpos]) {
                        $title .= ' | '.$responsedate;
                    }
                    $firstuserfullname = $ridsuserfullname[0];
                    array_push($linkarr, '<b><<</b> <a href="'.$url.'&amp;rid='.$firstrid.'&amp;individualresponse=1" title="'.
                                    $firstuserfullname.'">'.
                                    get_string('firstrespondent', 'questionnaire').'</a>');
                    array_push($linkarr, '<b><&nbsp;</b><a href="'.$url.'&amp;rid='.$prevrid.'&amp;individualresponse=1"
                                    title="'.$title.'">'.get_string('previous').'</a>');
                } else {
                    $title = '';
                    array_push($linkarr, '<b><<</b> <a href="'.$url.'&amp;rid='.$firstrid.'&amp;individualresponse=1" title="'.
                        $title.'">'.get_string('firstrespondent', 'questionnaire').'</a>');
                    array_push($linkarr, '<b><&nbsp;</b><a href="'.$url.'&amp;rid='.$prevrid.'&amp;individualresponse=1"
                                title="'.$title.'">'.get_string('previous').'</a>');
                }
            }
            array_push($linkarr, '<b>'.($currpos + 1).' / '.$total.'</b>');
            if ($nextrid != null) {
                $pos = $currpos + 1;
                $responsedate = '';
                $title = '';
                $lastuserfullname = '';
                if ($isfullname) {
                    $responsedate = userdate($ridssub[$pos]);
                    $title = $ridsuserfullname[$pos];
                    // Only add date if more than one response by a student.
                    if ($ridsuserid[$pos] == $ridsuserid[$currpos]) {
                        $title .= ' | '.$responsedate;
                    }
                    $lastuserfullname = $ridsuserfullname[$total - 1];
                }
                array_push($linkarr, '<a href="'.$url.'&amp;rid='.$nextrid.'&amp;individualresponse=1"
                                title="'.$title.'">'.get_string('next').'</a>&nbsp;<b>></b>');
                array_push($linkarr, '<a href="'.$url.'&amp;rid='.$lastrid.'&amp;individualresponse=1"
                                title="'.$lastuserfullname .'">'.
                                get_string('lastrespondent', 'questionnaire').'</a>&nbsp;<b>>></b>');
            }
            $url = $CFG->wwwroot.'/mod/questionnaire/report.php?action=vresp&byresponse=1&group='.$currentgroupid;
            // Display navbar.
            echo $OUTPUT->box_start('respondentsnavbar');
            echo implode(' | ', $linkarr);
            echo '<br /><b><<< <a href="'.$url.'">'.get_string('viewbyresponse', 'questionnaire').'</a></b>';

            // Display a "print this response" icon here in prevision of total removal of tabs in version 2.6.
            $linkname = '&nbsp;'.get_string('print', 'questionnaire');
            $url = '/mod/questionnaire/print.php?qid='.$this->id.'&amp;rid='.$currrid.
            '&amp;courseid='.$this->course->id.'&amp;sec=1';
            $title = get_string('printtooltip', 'questionnaire');
            $options = array('menubar' => true, 'location' => false, 'scrollbars' => true,
                            'resizable' => true, 'height' => 600, 'width' => 800);
            $name = 'popup';
            $link = new moodle_url($url);
            $action = new popup_action('click', $link, $name, $options);
            $actionlink = $OUTPUT->action_link($link, $linkname, $action, array('title' => $title),
                    new pix_icon('t/print', $title));
            echo '&nbsp;|&nbsp;'.$actionlink;

            echo $OUTPUT->box_end();

        } else { // Display respondents list.
            for ($i = 0; $i < $total; $i++) {
                if ($isfullname) {
                    $responsedate = userdate($ridssub[$i]);
                    array_push($linkarr, '<a title = "'.$responsedate.'" href="'.$url.'&amp;rid='.
                        $rids[$i].'&amp;individualresponse=1" >'.$ridsuserfullname[$i].'</a>'.'&nbsp;');
                } else {
                    $responsedate = '';
                    array_push($linkarr, '<a title = "'.$responsedate.'" href="'.$url.'&amp;rid='.
                        $rids[$i].'&amp;individualresponse=1" >'.
                        get_string('response', 'questionnaire').($i + 1).'</a>'.'&nbsp;');
                }
            }
            // Table formatting from http://wikkawiki.org/PageAndCategoryDivisionInACategory.
            $total = count($linkarr);
            $entries = count($linkarr);
            // Default max 3 columns, max 25 lines per column.
            // TODO make this setting customizable.
            $maxlines = 20;
            $maxcols = 3;
            if ($entries >= $maxlines) {
                $colnumber = min (intval($entries / $maxlines), $maxcols);
            } else {
                $colnumber = 1;
            }
            $lines = 0;
            $a = 0;
            $str = '';
            // How many lines with an entry in every column do we have?
            while ($entries / $colnumber > 1) {
                $lines++;
                $entries = $entries - $colnumber;
            }
            // Prepare output.
            for ($i = 0; $i < $colnumber; $i++) {
                $str .= '<div id="respondentscolumn">'."\n";
                for ($j = 0; $j < $lines; $j++) {
                    $str .= $linkarr[$a].'<br />'."\n";
                    $a++;
                }
                // The rest of the entries (less than the number of cols).
                if ($entries) {
                    $str .= $linkarr[$a].'<br />'."\n";
                    $entries--;
                    $a++;
                }
                $str .= "</div>\n";
            }
            $str .= '<div style="clear: both;">'."</div>\n";
            echo $OUTPUT->box_start();
            echo ($str);
            echo $OUTPUT->box_end();
        }
    }

    // Display responses for current user (your responses).
    public function survey_results_navbar_student($currrid, $userid, $instance, $resps, $reporttype='myreport', $sid='') {
        global $DB, $OUTPUT;
        $stranonymous = get_string('anonymous', 'questionnaire');

        $total = count($resps);
        $rids = array();
        $ridssub = array();
        $ridsusers = array();
        $i = 0;
        $currpos = -1;
        $title = '';
        foreach ($resps as $response) {
            array_push($rids, $response->id);
            array_push($ridssub, $response->submitted);
            $ruser = '';
            if ($reporttype == 'report') {
                if ($this->respondenttype != 'anonymous') {
                    if(MOODLE_RUN_MODE === MOODLE_MODE_HUB){
                        $response->username = get_remote_mapping_localuserid($response->username);
                    }
                    $user = $DB->get_record('user', array('id' => $response->username));
                    if ($user) {
                        $ruser = ' | ' .fullname($user);
                    }
                } else {
                    $ruser = ' | ' . $stranonymous;
                }
            }
            array_push($ridsusers, $ruser);
            if ($response->id == $currrid) {
                $currpos = $i;
            }
            $i++;
        }
        $prevrid = ($currpos > 0) ? $rids[$currpos - 1] : null;
        $nextrid = ($currpos < $total - 1) ? $rids[$currpos + 1] : null;
        $rowsperpage = 1;

        if ($reporttype == 'myreport') {
            $url = 'myreport.php?instance='.$instance.'&amp;user='.$userid.'&amp;action=vresp&amp;byresponse=1';
        } else {
            $url = 'report.php?instance='.$instance.'&amp;user='.$userid.'&amp;action=vresp&amp;byresponse=1&amp;sid='.$sid;
        }
        $linkarr = array();
        $displaypos = 1;
        if ($prevrid != null) {
            $title = userdate($ridssub[$currpos - 1].$ridsusers[$currpos - 1]);
            array_push($linkarr, '<a href="'.$url.'&amp;rid='.$prevrid.'" title="'.$title.'">'.get_string('previous').'</a>');
        }
        for ($i = 0; $i < $currpos; $i++) {
            $title = userdate($ridssub[$i]).$ridsusers[$i];
            array_push($linkarr, '<a href="'.$url.'&amp;rid='.$rids[$i].'" title="'.$title.'">'.$displaypos.'</a>');
            $displaypos++;
        }
        array_push($linkarr, '<b>'.$displaypos.'</b>');
        for (++$i; $i < $total; $i++) {
            $displaypos++;
            $title = userdate($ridssub[$i]).$ridsusers[$i];
            array_push($linkarr, '<a href="'.$url.'&amp;rid='.$rids[$i].'" title="'.$title.'">'.$displaypos.'</a>');
        }
        if ($nextrid != null) {
            $title = userdate($ridssub[$currpos + 1]).$ridsusers[$currpos + 1];
            array_push($linkarr, '<a href="'.$url.'&amp;rid='.$nextrid.'" title="'.$title.'">'.get_string('next').'</a>');
        }
        echo $OUTPUT->box_start('respondentsnavbar');
        echo implode(' | ', $linkarr);
        echo $OUTPUT->box_end('respondentsnavbar');
    }

    /* {{{ proto string survey_results(int survey_id, int precision, bool show_totals, int question_id,
     * array choice_ids, int response_id)
        Builds HTML for the results for the survey. If a
        question id and choice id(s) are given, then the results
        are only calculated for respodants who chose from the
        choice ids for the given question id.
        Returns empty string on sucess, else returns an error
        string. */

    public function survey_results($precision = 1, $showtotals = 1, $qid = '', $cids = '', $rid = '',
                $uid=false, $currentgroupid='', $sort='') {
        global $SESSION, $DB;

        $SESSION->questionnaire->noresponses = false;
        if (empty($precision)) {
            $precision  = 1;
        }
        if ($showtotals === '') {
            $showtotals = 1;
        }

        if (is_int($cids)) {
            $cids = array($cids);
        }
        if (is_string($cids)) {
            $cids = preg_split("/ /", $cids); // Turn space seperated list into array.
        }

        // Build associative array holding whether each question
        // type has answer choices or not and the table the answers are in
        // TO DO - FIX BELOW TO USE STANDARD FUNCTIONS.
        $haschoices = array();
        $responsetable = array();
        if (!($types = $DB->get_records('questionnaire_question_type', array(), 'typeid', 'typeid, has_choices, response_table'))) {
            $errmsg = sprintf('%s [ %s: question_type ]',
                    get_string('errortable', 'questionnaire'), 'Table');
            return($errmsg);
        }
        foreach ($types as $type) {
            $haschoices[$type->typeid] = $type->has_choices; // TODO is that variable actually used?
            $responsetable[$type->typeid] = $type->response_table;
        }

        // Load survey title (and other globals).
        if (empty($this->survey)) {
            $errmsg = get_string('erroropening', 'questionnaire') ." [ ID:${sid} R:";
            return($errmsg);
        }

        if (empty($this->questions)) {
            $errmsg = get_string('erroropening', 'questionnaire') .' '. 'No questions found.';
            return($errmsg);
        }

        // Find total number of survey responses and relevant response ID's.
        if (!empty($rid)) {
            $rids = $rid;
            if (is_array($rids)) {
                $navbar = false;
            } else {
                $navbar = true;
            }
            $total = 1;
        } else {
            $navbar = false;
            $sql = "";
            $castsql = $DB->sql_cast_char2int('r.username');

            if(MOODLE_RUN_MODE === MOODLE_MODE_HOST){
                if ($uid !== false) { // One participant only.
                    $sql = "SELECT r.id, r.survey_id
                          FROM {questionnaire_response} r
                         WHERE r.survey_id='{$this->survey->id}' AND
                               r.username = $uid AND
                               r.complete='y'
                         ORDER BY r.id";
                    // All participants or all members of a group.
                } else if ($currentgroupid == 0) {
                    $sql = "SELECT r.id, r.survey_id, r.username as userid
                          FROM {questionnaire_response} r
                         WHERE r.survey_id='{$this->survey->id}' AND
                               r.complete='y'
                         ORDER BY r.id";
                } else { // Members of a specific group.
                    $sql = "SELECT r.id, r.survey_id
                          FROM {questionnaire_response} r,
                                {groups_members} gm
                         WHERE r.survey_id='{$this->survey->id}' AND
                               r.complete='y' AND
                               gm.groupid=".$currentgroupid." AND
                               ".$castsql."=gm.userid
                         ORDER BY r.id";
                }
                $rows = $DB->get_records_sql($sql);
            } else {
                if ($uid !== false) { // One participant only.
                    $sql_select = "R.survey_id='{$this->survey->id}' AND R.username = $uid AND R.complete='y'";
                    $sql_sort = 'R.id';
                } else if ($currentgroupid == 0) {
                    $sql_select = "R.survey_id='{$this->survey->id}' AND R.complete='y'";
                    $sql_sort = 'R.id';
                } else { // Members of a specific group. // not yet
                    $sql = "SELECT r.id, r.survey_id
                          FROM {questionnaire_response} r,
                                {groups_members} gm
                         WHERE r.survey_id='{$this->survey->id}' AND
                               r.complete='y' AND
                               gm.groupid=".$currentgroupid." AND
                               ".$castsql."=gm.userid
                         ORDER BY r.id";
                }
                $rows = get_remote_questionnaire_response($sql_select, $sql_sort);
            }

            if (!($rows)) {
                echo (get_string('noresponses', 'questionnaire'));
                $SESSION->questionnaire->noresponses = true;
                return;
            }
            $total = count($rows);
            echo (' '.get_string('responses', 'questionnaire').": <strong>$total</strong>");
            if (empty($rows)) {
                $errmsg = get_string('erroropening', 'questionnaire') .' '. get_string('noresponsedata', 'questionnaire');
                    return($errmsg);
            }

            $rids = array();
            foreach ($rows as $row) {
                array_push($rids, $row->id);
            }
        }

        if ($navbar) {
            // Show response navigation bar.
            $this->survey_results_navbar($rid);
        }

        echo '<h3 class="surveyTitle">'.s($this->survey->title).'</h3>';
        if ($this->survey->subtitle) {
            echo('<h4>'.$this->survey->subtitle.'</h4>');
        }
        if ($this->survey->info) {
            $infotext = file_rewrite_pluginfile_urls($this->survey->info, 'pluginfile.php',
                $this->context->id, 'mod_questionnaire', 'info', $this->survey->id);
            echo '<div class="addInfo">'.format_text($infotext, FORMAT_HTML).'</div>';
        }

        $qnum = 0;

        foreach ($this->questions as $question) {
            if ($question->type_id == QUESPAGEBREAK) {
                continue;
            }
            echo html_writer::start_tag('div', array('class' => 'qn-container'));
            if ($question->type_id != QUESSECTIONTEXT) {
                $qnum++;
                echo html_writer::start_tag('div', array('class' => 'qn-info'));
                if ($question->type_id != QUESSECTIONTEXT) {
                    echo html_writer::tag('h2', $qnum, array('class' => 'qn-number'));
                }
                echo html_writer::end_tag('div'); // End qn-info.
            }
            echo html_writer::start_tag('div', array('class' => 'qn-content'));
            // If question text is "empty", i.e. 2 non-breaking spaces were inserted, do not display any question text.
            if ($question->content == '<p>  </p>') {
                $question->content = '';
            }
            echo html_writer::start_tag('div', array('class' => 'qn-question'));
            echo format_text(file_rewrite_pluginfile_urls($question->content, 'pluginfile.php',
                $question->context->id, 'mod_questionnaire', 'question', $question->id), FORMAT_HTML);
            echo html_writer::end_tag('div'); // End qn-question.

            $question->display_results($rids, $sort);
            echo html_writer::end_tag('div'); // End qn-content.

            echo html_writer::end_tag('div'); // End qn-container.
        }

        return;
    }

    /**
     * Get unique list of question types used in the current survey.
     *
     * @author: Guy Thomas
     * @param int $surveyid
     * @param bool $uniquebytable
     * @return array
     * @throws moodle_exception
     */
    protected function get_survey_questiontypes($uniquebytable = false) {

        $uniquetypes = [];
        $uniquetables = [];

        foreach ($this->questions as $question) {
            $type = $question->type_id;
            $responsetable = $question->response_table;
            // Build SQL for this question type if not already done.
            if (!$uniquebytable || !in_array($responsetable, $uniquetables)) {
                if (!in_array($type, $uniquetypes)) {
                    $uniquetypes[] = $type;
                }
                if (!in_array($responsetable, $uniquetables)) {
                    $uniquetables[] = $responsetable;
                }
            }
        }

        return $uniquetypes;
    }

    /**
     * Return array of all types considered to be choices.
     *
     * @return array
     */
    protected function choice_types() {
        return [QUESRADIO, QUESDROP, QUESCHECK, QUESRATE];
    }

    /**
     * Return all the fields to be used for users in questionnaire sql.
     *
     * @author: Guy Thomas
     * @return array|string
     */
    protected function user_fields() {
        $userfieldsarr = get_all_user_name_fields();
        $userfieldsarr = array_merge($userfieldsarr, ['username', 'department', 'institution']);
        return $userfieldsarr;
    }

    /**
     * Get all survey responses in one go.
     *
     * @author: Guy Thomas
     * @param string $rid
     * @param string $userid
     * @return array
     */
    protected function get_survey_all_responses($rid = '', $userid = '') {
        global $DB;
        $uniquetypes = $this->get_survey_questiontypes(true);
        $allresponsessql = "";
        $allresponsesparams = [];

        foreach ($uniquetypes as $type) {
            $typename = \mod_questionnaire\question\base::qtypename($type);
            $question = self::question_factory($typename);
            if (!isset($question->response)) {
                continue;
            }
            $allresponsessql .= $allresponsessql == '' ? '' : ' UNION ALL ';
            list ($sql, $params) = $question->response->get_bulk_sql($this->survey->id, $rid, $userid);
            $allresponsesparams = array_merge($allresponsesparams, $params);
            $allresponsessql .= $sql;
        }

        $allresponsessql .= " ORDER BY userid, id";
        $allresponses = $DB->get_recordset_sql($allresponsessql, $allresponsesparams);
        return $allresponses;
    }

    /**
     * Process individual row for csv output
     * @param array $outputrow output row
     * @param stdClass $resprow resultset row
     * @param int $currentgroupid
     * @param array $questionsbyposition
     * @param int $nbinfocols
     * @param int $numrespcols
     * @return array
     * @throws Exception
     * @throws coding_exception
     * @throws dml_exception
     * @throws dml_missing_record_exception
     * @throws dml_multiple_records_exception
     */
    protected function process_csv_row(array &$row,
                                       stdClass $resprow,
                                       $currentgroupid,
                                       array &$questionsbyposition,
                                       $nbinfocols,
                                       $numrespcols) {
        global $DB;

        static $config = null;

        if ($config === null) {
            $config = get_config('questionnaire', 'downloadoptions');
        }
        $options = empty($config) ? array() : explode(',', $config);

        $positioned = [];
        $user = new stdClass();
        foreach ($this->user_fields() as $userfield) {
            $user->$userfield = $resprow->$userfield;
        }
        $user->id = $resprow->userid;
        $isanonymous = $this->respondenttype == 'anonymous';

        // Moodle:
        // Get the course name that this questionnaire belongs to.
        if ($this->survey->realm != 'public') {
            $courseid = $this->course->id;
            $coursename = $this->course->fullname;
        } else {
            // For a public questionnaire, look for the course that used it.
            $sql = 'SELECT q.id, q.course, c.fullname '.
                'FROM {questionnaire} q, {questionnaire_attempts} qa, {course} c '.
                'WHERE qa.rid = ? AND q.id = qa.qid AND c.id = q.course';
            if ($record = $DB->get_record_sql($sql, [$resprow->rid])) {
                $courseid = $record->course;
                $coursename = $record->fullname;
            } else {
                $courseid = $this->course->id;
                $coursename = $this->course->fullname;
            }
        }

        // Moodle:
        // Determine if the user is a member of a group in this course or not.
        // TODO - review for performance.
        $groupname = '';
        if (groups_get_activity_groupmode($this->cm, $this->course)) {
            if ($currentgroupid > 0) {
                $groupname = groups_get_group_name($currentgroupid);
            } else {
                if ($user->id) {
                    if ($groups = groups_get_all_groups($courseid, $user->id)) {
                        foreach ($groups as $group) {
                            $groupname .= $group->name.', ';
                        }
                        $groupname = substr($groupname, 0, strlen($groupname) - 2);
                    } else {
                        $groupname = ' ('.get_string('groupnonmembers').')';
                    }
                }
            }
        }

        if ($isanonymous) {
            $fullname = get_string('anonymous', 'questionnaire');
            $username = '';
            $uid = '';
        } else {
            $uid = $user->id;
            $fullname = fullname($user);
            $username = $user->username;
        }

        if (in_array('response', $options)) {
            array_push($positioned, $resprow->rid);
        }
        if (in_array('submitted', $options)) {
            // For better compabitility & readability with Excel.
            $submitted = date(get_string('strfdateformatcsv', 'questionnaire'), $resprow->submitted);
            array_push($positioned, $submitted);
        }
        if (in_array('institution', $options)) {
            array_push($positioned, $user->institution);
        }
        if (in_array('department', $options)) {
            array_push($positioned, $user->department);
        }
        if (in_array('course', $options)) {
            array_push($positioned, $coursename);
        }
        if (in_array('group', $options)) {
            array_push($positioned, $groupname);
        }
        if (in_array('id', $options)) {
            array_push($positioned, $uid);
        }
        if (in_array('fullname', $options)) {
            array_push($positioned, $fullname);
        }
        if (in_array('username', $options)) {
            array_push($positioned, $username);
        }

        for ($c = $nbinfocols; $c < $numrespcols; $c++) {
            if (isset($row[$c])) {
                $positioned[] = $row[$c];
            } else if (isset($questionsbyposition[$c])) {
                $question = $questionsbyposition[$c];
                $qtype = intval($question->type_id);
                if ($qtype === QUESCHECK) {
                    $positioned[] = '0';
                } else {
                    $positioned[] = null;
                }
            } else {
                $positioned[] = null;
            }
        }
        return $positioned;
    }

    /* {{{ proto array survey_generate_csv(int survey_id)
    Exports the results of a survey to an array.
    */
    public function generate_csv($rid='', $userid='', $choicecodes=1, $choicetext=0, $currentgroupid) {
        global $DB;

        ini_set('memory_limit', '1G');

        $output = array();
        $stringother = get_string('other', 'questionnaire');

        $config = get_config('questionnaire', 'downloadoptions');
        $options = empty($config) ? array() : explode(',', $config);
        $columns = array();
        $types = array();
        foreach ($options as $option) {
            if (in_array($option, array('response', 'submitted', 'id'))) {
                $columns[] = get_string($option, 'questionnaire');
                $types[] = 0;
            } else {
                $columns[] = get_string($option);
                $types[] = 1;
            }
        }
        $nbinfocols = count($columns);

        $idtocsvmap = array(
            '0',    // 0: unused
            '0',    // 1: bool -> boolean
            '1',    // 2: text -> string
            '1',    // 3: essay -> string
            '0',    // 4: radio -> string
            '0',    // 5: check -> string
            '0',    // 6: dropdn -> string
            '0',    // 7: rating -> number
            '0',    // 8: rate -> number
            '1',    // 9: date -> string
            '0'     // 10: numeric -> number.
        );

        if(MOODLE_RUN_MODE === MOODLE_MODE_HOST){
            $survey = $DB->get_record('questionnaire_survey', array('id' => $this->survey->id));
        } else {
            $survey = get_remote_questionnaire_survey_by_id($this->survey->id);
        }
        if (!$survey) {
            print_error ('surveynotexists', 'questionnaire');
        }

        // Get all responses for this survey in one go.
        $allresponsesrs = $this->get_survey_all_responses($rid, $userid);

        // Do we have any questions of type RADIO, DROP, CHECKBOX OR RATE? If so lets get all their choices in one go.
        $choicetypes = $this->choice_types();

        // Get unique list of question types used in this survey.
        $uniquetypes = $this->get_survey_questiontypes();

        if (count(array_intersect($choicetypes, $uniquetypes) > 0 )) {
            $choiceparams = [$this->survey->id];
            $choicesql = "
                SELECT DISTINCT c.id as cid, q.id as qid, q.precise AS precise, q.name, c.content
                  FROM {questionnaire_question} q
                  JOIN {questionnaire_quest_choice} c ON question_id = q.id
                 WHERE q.survey_id = ? ORDER BY cid ASC
            ";
            $choicerecords = $DB->get_records_sql($choicesql, $choiceparams);
            $choicesbyqid = [];
            if (!empty($choicerecords)) {
                // Hash the options by question id.
                foreach ($choicerecords as $choicerecord) {
                    if (!isset($choicesbyqid[$choicerecord->qid])) {
                        // New question id detected, intialise empty array to store choices.
                        $choicesbyqid[$choicerecord->qid] = [];
                    }
                    $choicesbyqid[$choicerecord->qid][$choicerecord->cid] = $choicerecord;
                }
            }
        }

        $num = 1;

        $questionidcols = [];

        foreach ($this->questions as $question) {
            // Skip questions that aren't response capable.
            if (!isset($question->response)) {
                continue;
            }
            // Establish the table's field names.
            $qid = $question->id;
            $qpos = $question->position;
            $col = $question->name;
            $type = $question->type_id;
            if (in_array($type, $choicetypes)) {
                /* single or multiple or rate */
                if (!isset($choicesbyqid[$qid])) {
                    throw new coding_exception('Choice question has no choices!', 'question id '.$qid.' of type '.$type);
                }
                $choices = $choicesbyqid[$qid];

                $subqnum = 0;
                switch ($type) {

                    case QUESRADIO: // Single.
                    case QUESDROP:
                        $columns[][$qpos] = $col;
                        $questionidcols[][$qpos] = $qid;
                        array_push($types, $idtocsvmap[$type]);
                        $thisnum = 1;
                        foreach ($choices as $choice) {
                            $content = $choice->content;
                            // If "Other" add a column for the actual "other" text entered.
                            if (preg_match('/^!other/', $content)) {
                                $col = $choice->name.'_'.$stringother;
                                $columns[][$qpos] = $col;
                                $questionidcols[][$qpos] = null;
                                array_push($types, '0');
                            }
                        }
                        break;

                    case QUESCHECK: // Multiple.
                        $thisnum = 1;
                        foreach ($choices as $choice) {
                            $content = $choice->content;
                            $modality = '';
                            $contents = questionnaire_choice_values($content);
                            if ($contents->modname) {
                                $modality = $contents->modname;
                            } else if ($contents->title) {
                                $modality = $contents->title;
                            } else {
                                $modality = strip_tags($contents->text);
                            }
                            $col = $choice->name.'->'.$modality;
                            $columns[][$qpos] = $col;
                            $questionidcols[][$qpos] = $qid.'_'.$choice->cid;
                            array_push($types, '0');
                            // If "Other" add a column for the "other" checkbox. Then add a column for the actual "other" text entered.
                            if (preg_match('/^!other/', $content)) {
                                $content = $stringother;
                                $col = $choice->name.'->['.$content.']';
                                $columns[][$qpos] = $col;
                                $questionidcols[][$qpos] = null;
                                array_push($types, '0');
                            }
                        }
                        break;

                    case QUESRATE: // Rate.
                        foreach ($choices as $choice) {
                            $nameddegrees = 0;
                            $modality = '';
                            $content = $choice->content;
                            $osgood = false;
                            if ($choice->precise == 3) {
                                $osgood = true;
                            }
                            if (preg_match("/^[0-9]{1,3}=/", $content, $ndd)) {
                                $nameddegrees++;
                            } else {
                                if ($osgood) {
                                    list($contentleft, $contentright) = array_merge(preg_split('/[|]/', $content), array(' '));
                                    $contents = questionnaire_choice_values($contentleft);
                                    if ($contents->title) {
                                        $contentleft = $contents->title;
                                    }
                                    $contents = questionnaire_choice_values($contentright);
                                    if ($contents->title) {
                                        $contentright = $contents->title;
                                    }
                                    $modality = strip_tags($contentleft.'|'.$contentright);
                                    $modality = preg_replace("/[\r\n\t]/", ' ', $modality);
                                } else {
                                    $contents = questionnaire_choice_values($content);
                                    if ($contents->modname) {
                                        $modality = $contents->modname;
                                    } else if ($contents->title) {
                                        $modality = $contents->title;
                                    } else {
                                        $modality = strip_tags($contents->text);
                                        $modality = preg_replace("/[\r\n\t]/", ' ', $modality);
                                    }
                                }
                                $col = $choice->name.'->'.$modality;
                                $columns[][$qpos] = $col;
                                $questionidcols[][$qpos] = $qid.'_'.$choice->cid;
                                array_push($types, $idtocsvmap[$type]);
                            }
                        }
                        break;
                }
            } else {
                $columns[][$qpos] = $col;
                $questionidcols[][$qpos] = $qid;
                array_push($types, $idtocsvmap[$type]);
            }
            $num++;
        }

        array_push($output, $columns);
        $numrespcols = count($output[0]); // Number of columns used for storing question responses.

        // Flatten questionidcols.
        $tmparr = [];
        for ($c = 0; $c < $nbinfocols; $c++) {
            $tmparr[] = null; // Pad with non question columns.
        }
        foreach ($questionidcols as $i => $positions) {
            foreach ($positions as $position => $qid) {
                $tmparr[] = $qid;
            }
        }
        $questionidcols = $tmparr;

        // Create array of question positions hashed by question / question + choiceid.
        // And array of questions hashed by position.
        $questionpositions = [];
        $questionsbyposition = [];
        $p = 0;
        foreach ($questionidcols as $qid) {
            if ($qid === null) {
                // This is just padding, skip.
                $p++;
                continue;
            }
            $questionpositions[$qid] = $p;
            if (strpos($qid, '_') !== false) {
                $tmparr = explode ('_', $qid);
                $questionid = $tmparr[0];
            } else {
                $questionid = $qid;
            }
            $questionsbyposition[$p] = $this->questions[$questionid];
            $p++;
        }

        $formatoptions = new stdClass();
        $formatoptions->filter = false;  // To prevent any filtering in CSV output.

        // Get textual versions of responses, add them to output at the correct col position.
        $prevresprow = false; // Previous response row.
        $row = [];
        foreach ($allresponsesrs as $responserow) {
            $rid = $responserow->rid;
            $qid = $responserow->question_id;
            $question = $this->questions[$qid];
            $qtype = intval($question->type_id);
            $questionobj = $this->questions[$qid];

            if ($prevresprow !== false && $prevresprow->rid !== $rid) {
                $output[] = $this->process_csv_row($row, $prevresprow, $currentgroupid, $questionsbyposition,
                    $nbinfocols, $numrespcols);
                $row = [];
            }

            if ($qtype === QUESRATE || $qtype === QUESCHECK) {
                $key = $qid.'_'.$responserow->choice_id;
                $position = $questionpositions[$key];
                if ($qtype === QUESRATE) {
                    $choicetxt = $responserow->rank + 1;
                } else {
                    $content = $choicesbyqid[$qid][$responserow->choice_id]->content;
                    if (preg_match('/^!other/', $content)) {
                        // If this is an "other" column, put the text entered in the next position.
                        $row[$position + 1] = $responserow->response;
                        $choicetxt = empty($responserow->choice_id) ? '0' : '1';
                    } else if (!empty($responserow->choice_id)) {
                        $choicetxt = '1';
                    } else {
                        $choicetxt = '0';
                    }
                }
                $responsetxt = $choicetxt;
                $row[$position] = $responsetxt;
            } else {
                $position = $questionpositions[$qid];
                if ($questionobj->has_choices()) {
                    // This is choice type question, so process as so.
                    $c = 0;
                    if (in_array(intval($question->type_id), $choicetypes)) {
                        $choices = $choicesbyqid[$qid];
                        // Get position of choice.
                        foreach ($choices as $choice) {
                            $c++;
                            if ($responserow->choice_id === $choice->cid) {
                                break;
                            }
                        }
                    }

                    $content = $choicesbyqid[$qid][$responserow->choice_id]->content;
                    if (preg_match('/^!other/', $content)) {
                        // If this has an "other" text, use it.
                        $responsetxt = get_string('other', 'questionnaire');
                        $responsetxt1 = $responserow->response;
                    } else if (($choicecodes == 1) && ($choicetext == 1)) {
                        $responsetxt = $c.' : '.$content;
                    } else if ($choicecodes == 1) {
                        $responsetxt = $c;
                    } else {
                        $responsetxt = $content;
                    }
                } else if (intval($qtype) === QUESYESNO) {
                    $responsetxt = $responserow->choice_id === 'y' ? "1" : "0";
                } else {
                    // Strip potential html tags from modality name.
                    $responsetxt = $responserow->response;
                    if (!empty($responsetxt)) {
                        $responsetxt = $responserow->response;
                        $responsetxt = strip_tags($responsetxt);
                        $responsetxt = preg_replace("/[\r\n\t]/", ' ', $responsetxt);
                    }
                }
                $row[$position] = $responsetxt;
                // Check for "other" text and set it to the next position if present.
                if (!empty($responsetxt1)) {
                    $row[$position + 1] = $responsetxt1;
                    unset($responsetxt1);
                }
            }

            $prevresprow = $responserow;
        }
        // Add final row to output.
        $output[] = $this->process_csv_row($row, $prevresprow, $currentgroupid, $questionsbyposition, $nbinfocols, $numrespcols);

        // Change table headers to incorporate actual question numbers.
        $numcol = 0;
        $numquestion = 0;
        $out = '';
        $oldkey = 0;

        for ($i = $nbinfocols; $i < $numrespcols; $i++) {
            $sep = '';
            $thisoutput = current($output[0][$i]);
            $thiskey = key($output[0][$i]);
            // Case of unnamed rate single possible answer (full stop char is used for support).
            if (strstr($thisoutput, '->.')) {
                $thisoutput = str_replace('->.', '', $thisoutput);
            }
            // If variable is not named no separator needed between Question number and potential sub-variables.
            if ($thisoutput == '' || strstr($thisoutput, '->.') || substr($thisoutput, 0, 2) == '->'
                || substr($thisoutput, 0, 1) == '_') {
                $sep = '';
            } else {
                $sep = '_';
            }
            if ($thiskey > $oldkey) {
                $oldkey = $thiskey;
                $numquestion++;
            }
            // Abbreviated modality name in multiple or rate questions (COLORS->blue=the color of the sky...).
            $pos = strpos($thisoutput, '=');
            if ($pos) {
                $thisoutput = substr($thisoutput, 0, $pos);
            }
            $other = $sep.$stringother;
            $out = 'Q'.sprintf("%02d", $numquestion).$sep.$thisoutput;
            $output[0][$i] = $out;
        }
        return $output;
    }

    /* {{{ proto bool survey_export_csv(int survey_id, string filename)
        Exports the results of a survey to a CSV file.
        Returns true on success.
        */

    private function export_csv($filename) {
        $umask = umask(0077);
        $fh = fopen($filename, 'w');
        umask($umask);
        if (!$fh) {
            return 0;
        }

        $data = survey_generate_csv($rid = '', $userid = '', $currentgroupid = '');

        foreach ($data as $row) {
            fputs($fh, join(', ', $row) . "\n");
        }

        fflush($fh);
        fclose($fh);

        return 1;
    }


    /**
     * Function to move a question to a new position.
     * Adapted from feedback plugin.
     *
     * @param int $moveqid The id of the question to be moved.
     * @param int $movetopos The position to move question to.
     *
     */

    public function move_question($moveqid, $movetopos) {
        global $DB;

        $questions = $this->questions;
        $movequestion = $this->questions[$moveqid];

        if (is_array($questions)) {
            $index = 1;
            foreach ($questions as $question) {
                if ($index == $movetopos) {
                    $index++;
                }
                if ($question->id == $movequestion->id) {
                    $movequestion->position = $movetopos;
                    $DB->update_record("questionnaire_question", $movequestion);
                    continue;
                }
                $question->position = $index;
                $DB->update_record("questionnaire_question", $question);
                $index++;
            }
            return true;
        }
        return false;
    }

    public function response_analysis ($rid, $resps, $compare, $isgroupmember, $allresponses, $currentgroupid) {
        global $DB, $CFG, $OUTPUT, $SESSION, $USER;
        $action = optional_param('action', 'vall', PARAM_ALPHA);

        require_once($CFG->libdir.'/tablelib.php');
        require_once($CFG->dirroot.'/mod/questionnaire/drawchart.php');
		if(MOODLE_RUN_MODE === MOODLE_MODE_HOST){ 
			$resp = $DB->get_record('questionnaire_response', array('id' => $rid));
		} else {
			$resp = get_remote_questionnaire_response_by_rid($rid);
		}
        if ($resp) {
            $userid = $resp->username;
            if(MOODLE_RUN_MODE === MOODLE_MODE_HOST){
                $user = $DB->get_record('user', array('id' => $userid));
            } else {
                $user = $DB->get_record('user', array('id' => $USER->id));
            }
            if ($user) {
                $ruser = fullname($user);
            }
        }
        // Available group modes (0 = no groups; 1 = separate groups; 2 = visible groups).
        $groupmode = groups_get_activity_groupmode($this->cm, $this->course);
        $groupname = get_string('allparticipants');
        if ($groupmode > 0) {
            if ($currentgroupid > 0) {
                $groupname = groups_get_group_name($currentgroupid);
            } else {
                $groupname = get_string('allparticipants');
            }
        }
        if ($this->survey->feedbackscores) {
            $table = new html_table();
            $table->size = array(null, null);
            $table->align = array('left', 'right', 'right');
            $table->head = array();
            $table->wrap = array();
            if ($compare) {
                $table->head = array(get_string('feedbacksection', 'questionnaire'), $ruser, $groupname);
            } else {
                $table->head = array(get_string('feedbacksection', 'questionnaire'), $groupname);
            }
        }

        $feedbacksections = $this->survey->feedbacksections;
        $feedbackscores = $this->survey->feedbackscores;
        $sid = $this->survey->id;
        $questions = $this->questions;

        // Find if there are any feedbacks in this questionnaire.
        if(MOODLE_RUN_MODE === MOODLE_MODE_HOST){
            $sql = "SELECT * FROM {questionnaire_fb_sections} WHERE survey_id = $sid AND section IS NOT NULL";
            $fbsections = $DB->get_records_sql($sql);
        } else {
            $sql_select = "survey_id = $sid AND section IS NOT NULL";
            $fbsections = get_remote_questionnaire_fb_sections($sql_select);
        }
        if (!$fbsections) {
            return null;
        }
        if(MOODLE_RUN_MODE === MOODLE_MODE_HOST){
            $fbsectionsnb = array_keys($fbsections);
        } else {
            $fbsectionsnb = array_map(function ($ar) {return $ar->id;}, $fbsectionsnb);
        }
        // Calculate max score per question in questionnaire.
        $qmax = array();
        $totalscore = 0;
        $maxtotalscore = 0;
        foreach ($questions as $question) {
            $qid = $question->id;
            $qtype = $question->type_id;
            $required = $question->required;
            if (($qtype == QUESRADIO || $qtype == QUESDROP || $qtype == QUESRATE) and $required == 'y') {
                if (!isset($qmax[$qid])) {
                    $qmax[$qid] = 0;
                }
                $nbchoices = 1;
                if ($qtype == QUESRATE) {
                    $nbchoices = 0;
                }
                foreach ($question->choices as $choice) {
                    // Testing NULL and 'NULL' because I changed the automatic null value, must be fixed later... TODO.
                    if (isset($choice->value) && $choice->value != null && $choice->value != 'NULL') {
                        if ($choice->value > $qmax[$qid]) {
                            $qmax[$qid] = $choice->value;
                        }
                    } else {
                        $nbchoices ++;
                    }
                }
                $qmax[$qid] = $qmax[$qid] * $nbchoices;
                $maxtotalscore += $qmax[$qid];
            }
            if ($qtype == QUESYESNO and $required == 'y') {
                $qmax[$qid] = 1;
                $maxtotalscore += 1;
            }
        }
        // Just in case no values have been entered in the various questions possible answers field.
        if ($maxtotalscore === 0) {
            return;
        }
        $feedbackmessages = array();

        // Get individual scores for each question in this responses set.
        $qscore = array();
        $allqscore = array();

        // Get all response ids for all respondents.
        $castsql = $DB->sql_cast_char2int('r.username');

        $rids = array();
        foreach ($resps as $key => $resp) {
            $rids[] = $key;
        }
        $nbparticipants = count($rids);

        if (!$allresponses && $groupmode != 0) {
            $nbparticipants = max(1, $nbparticipants - !$isgroupmember);
        }
        foreach ($rids as $rrid) {
            if(MOODLE_RUN_MODE === MOODLE_MODE_HOST){
                $sql = 'SELECT q.id, q.type_id as q_type, a.choice_id as cid '.
                    'FROM {questionnaire_response_bool} a, {questionnaire_question} q '.
                    'WHERE a.response_id = ? AND a.question_id=q.id ';
                $responses = $DB->get_records_sql($sql, array($rrid));
            } else {
                $sql_select = "a.response_id = '$rrid' AND a.question_id=q.id";
                $responses = get_remote_questionnaire_bool_question($sql_select);
            }
            if ($responses) {
                foreach ($responses as $response) {
                    $qid = $response->id;
                    $responsescore = ($response->cid == 'y' ? 1 : 0);
                    // Individual score.
                    // If this is current user's response OR if current user is viewing another group's results.
                    if ($rrid == $rid || $allresponses) {
                        if (!isset($qscore[$qid])) {
                            $qscore[$qid] = 0;
                        }
                        $qscore[$qid] = $responsescore;
                    }
                    // Course score.
                    if (!isset($allqscore[$qid])) {
                        $allqscore[$qid] = 0;
                    }
                    // Only add current score if conditions below are met.
                    if ($groupmode == 0 || $isgroupmember || (!$isgroupmember && $rrid != $rid) || $allresponses) {
                        $allqscore[$qid] += $responsescore;
                    }
                }
            }

            // Get responses for single (Radio or Dropbox).
            if(MOODLE_RUN_MODE === MOODLE_MODE_HOST){
                $sql = 'SELECT q.id, q.type_id as q_type, c.content as ccontent,c.id as cid, c.value as score  '.
                    'FROM {questionnaire_resp_single} a, {questionnaire_question} q, {questionnaire_quest_choice} c '.
                    'WHERE a.response_id = ? AND a.question_id=q.id AND a.choice_id=c.id ';
                $responses = $DB->get_records_sql($sql, array($rrid));
            } else {
                $sql_select = "a.response_id = '$rrid' AND a.question_id=q.id AND a.choice_id=c.id";
                $responses = get_remote_questionnaire_single_question_choice($sql_select);
            }
            if ($responses) {
                foreach ($responses as $response) {
                    $qid = $response->id;
                    // Individual score.
                    // If this is current user's response OR if current user is viewing another group's results.
                    if ($rrid == $rid || $allresponses) {
                        if (!isset($qscore[$qid])) {
                            $qscore[$qid] = 0;
                        }
                        $qscore[$qid] = $response->score;
                    }
                    // Course score.
                    if (!isset($allqscore[$qid])) {
                        $allqscore[$qid] = 0;
                    }
                    // Only add current score if conditions below are met.
                    if ($groupmode == 0 || $isgroupmember || (!$isgroupmember && $rrid != $rid) || $allresponses) {
                        $allqscore[$qid] += $response->score;
                    }
                }
            }

            // Get responses for response_rank (Rate).
            if(MOODLE_RUN_MODE === MOODLE_MODE_HOST){
                $sql = 'SELECT a.id as aid, q.id AS qid, c.id AS cid, a.rank as arank '.
                    'FROM {questionnaire_response_rank} a, {questionnaire_question} q, {questionnaire_quest_choice} c '.
                    'WHERE a.response_id= ? AND a.question_id=q.id AND a.choice_id=c.id '.
                    'ORDER BY aid, a.question_id,c.id';
                $responses = $DB->get_records_sql($sql, array($rrid));
            } else {
                $sql_select = "a.response_id= '$rrid' AND a.question_id=q.id AND a.choice_id=c.id";
                $sql_sort = "aid, a.question_id,c.id";
                $responses = get_remote_questionnaire_rank_question_choice($sql_select, $sql_sort);
            }
            if ($responses) {
                // We need to store the number of sub-questions for each rate questions.
                $rank = array();
                $firstcid = array();
                foreach ($responses as $response) {
                    $qid = $response->qid;
                    $rank = $response->arank;
                    if (!isset($qscore[$qid])) {
                        $qscore[$qid] = 0;
                        $allqscore[$qid] = 0;
                    }
                    if(MOODLE_RUN_MODE === MOODLE_MODE_HOST){
                        $firstcid[$qid] = $DB->get_record('questionnaire_quest_choice',
                            array('question_id' => $qid), 'id', IGNORE_MULTIPLE);
                        $firstcidid = $firstcid[$qid]->id;
                        $cidvalue = $firstcidid + $rank;
                        $sql = "SELECT * FROM {questionnaire_quest_choice} WHERE id = $cidvalue";
                        $value = $DB->get_record_sql($sql);
                    } else {
                        $firstcid[$qid] = get_remote_questionnaire_quest_choice_by_question_id($qid)[0];
                        $firstcidid = $firstcid[$qid]->id;
                        $cidvalue = $firstcidid + $rank;
                        $sql_select = "id = $cidvalue";
                        $value = get_remote_questionnaire_quest_choice_by_condition($sql_select);
                    }

                    if ($value) {
                        // Individual score.
                        // If this is current user's response OR if current user is viewing another group's results.
                        if ($rrid == $rid || $allresponses) {
                            $qscore[$qid] += $value->value;
                        }
                        // Only add current score if conditions below are met.
                        if ($groupmode == 0 || $isgroupmember || (!$isgroupmember && $rrid != $rid) || $allresponses) {
                            $allqscore[$qid] += $value->value;
                        }
                    }
                }
            }
        }
        $totalscore = array_sum($qscore);
        $scorepercent = round($totalscore / $maxtotalscore * 100);
        $oppositescorepercent = 100 - $scorepercent;
        $alltotalscore = array_sum($allqscore);
        $allscorepercent = round($alltotalscore / $nbparticipants / $maxtotalscore * 100);

        // No need to go further if feedback is global, i.e. only relying on total score.
        if ($feedbacksections == 1) {
            $sectionid = $fbsectionsnb[0];
            $sectionlabel = $fbsections[$sectionid]->sectionlabel;

            $sectionheading = $fbsections[$sectionid]->sectionheading;
            if(MOODLE_RUN_MODE === MOODLE_MODE_HOST){
                $feedbacks = $DB->get_records('questionnaire_feedback', array('section_id' => $sectionid));
            } else {
                $sql_select = "section_id = $sectionid";
                $feedbacks = get_remote_questionnaire_feedback($sql_select);
            }
            $labels = array();
            foreach ($feedbacks as $feedback) {
                if ($feedback->feedbacklabel != '') {
                    $labels[] = $feedback->feedbacklabel;
                }
            }
            if(MOODLE_RUN_MODE === MOODLE_MODE_HOST){
                $feedback = $DB->get_record_select('questionnaire_feedback',
                    'section_id = ? AND minscore <= ? AND ? < maxscore', array($sectionid, $scorepercent, $scorepercent));
            } else {
                $sql_select = "section_id = $sectionid AND minscore <= $scorepercent AND $scorepercent < maxscore";
                $feedback = get_remote_questionnaire_feedback($sql_select)[0];
            }

            // To eliminate all potential % chars in heading text (might interfere with the sprintf function).
            $sectionheading = str_replace('%', '', $sectionheading);
            // Replace section heading placeholders with their actual value (if any).
            $original = array('$scorepercent', '$oppositescorepercent');
            $result = array('%s%%', '%s%%');
            $sectionheading = str_replace($original, $result, $sectionheading);
            $sectionheading = sprintf($sectionheading , $scorepercent, $oppositescorepercent);
            $sectionheading = file_rewrite_pluginfile_urls($sectionheading, 'pluginfile.php',
                            $this->context->id, 'mod_questionnaire', 'sectionheading', $sectionid);
            $feedbackmessages[] = $OUTPUT->box_start();
            $feedbackmessages[] = format_text($sectionheading, FORMAT_HTML);
            $feedbackmessages[] = $OUTPUT->box_end();

            if (!empty($feedback->feedbacktext)) {
                // Clean the text, ready for display.
                $formatoptions = new stdClass();
                $formatoptions->noclean = true;
                $feedbacktext = file_rewrite_pluginfile_urls($feedback->feedbacktext, 'pluginfile.php',
                                $this->context->id, 'mod_questionnaire', 'feedback', $feedback->id);
                $feedbacktext = format_text($feedbacktext, $feedback->feedbacktextformat, $formatoptions);
                $feedbackmessages[] = $OUTPUT->box_start();
                $feedbackmessages[] = $feedbacktext;
                $feedbackmessages[] = $OUTPUT->box_end();
            }
            $score = array($scorepercent, 100 - $scorepercent);
            $allscore = null;
            if ($compare  || $allresponses) {
                $allscore = array($allscorepercent, 100 - $allscorepercent);
            }
            $usergraph = get_config('questionnaire', 'usergraph');
            if ($usergraph && $this->survey->chart_type) {
                draw_chart ($feedbacktype = 'global', $this->survey->chart_type, $labels,
                                    $score, $allscore, $sectionlabel, $groupname, $allresponses);
            }
            // Display class or group score. Pending chart library decision to display?
            // Find out if this feedback sectionlabel has a pipe separator.
            $lb = explode("|", $sectionlabel);
            $oppositescore = '';
            $oppositeallscore = '';
            if (count($lb) > 1) {
                $sectionlabel = $lb[0].' | '.$lb[1];
                $oppositescore = ' | '.$score[1].'%';
                $oppositeallscore = ' | '.$allscore[1].'%';
            }
            if ($this->survey->feedbackscores) {
                if ($compare) {
                    $table->data[] = array($sectionlabel, $score[0].'%'.$oppositescore, $allscore[0].'%'.$oppositeallscore);
                } else {
                    $table->data[] = array($sectionlabel, $allscore[0].'%'.$oppositeallscore);
                }

                echo html_writer::table($table);
            }

            return $feedbackmessages;
        }

        // Now process scores for more than one section.

        // Initialize scores and maxscores to 0.
        $score = array(); $allscore = array(); $maxscore = array(); $scorepercent = array();
        $allscorepercent = array(); $oppositescorepercent = array(); $alloppositescorepercent = array();
        $chartlabels = array(); $chartscore = array();
        for ($i = 1; $i <= $feedbacksections; $i++) {
            $score[$i] = 0; $allscore[$i] = 0; $maxscore[$i] = 0; $scorepercent[$i] = 0;
        }

        for ($section = 1; $section <= $feedbacksections; $section++) {
            foreach ($fbsections as $key => $fbsection) {
                if ($fbsection->section == $section) {
                    $feedbacksectionid = $key;
                    $scorecalculation = unserialize($fbsection->scorecalculation);
                    $sectionheading = $fbsection->sectionheading;
                    $imageid = $fbsection->id;
                    $chartlabels [$section] = $fbsection->sectionlabel;
                }
            }
            foreach ($scorecalculation as $qid => $key) {
                // Just in case a question pertaining to a section has been deleted or made not required
                // after being included in scorecalculation.
                if (isset($qscore[$qid])) {
                    $score[$section] += $qscore[$qid];
                    $maxscore[$section] += $qmax[$qid];
                    if ($compare  || $allresponses) {
                        $allscore[$section] += $allqscore[$qid];
                    }
                }
            }

            $scorepercent[$section] = round($score[$section] / $maxscore[$section] * 100);
            $oppositescorepercent[$section] = 100 - $scorepercent[$section];

            if (($compare || $allresponses) && $nbparticipants != 0) {
                $allscorepercent[$section] = round( ($allscore[$section] / $nbparticipants) / $maxscore[$section] * 100);
                $alloppositescorepercent[$section] = 100 - $allscorepercent[$section];
            }

            if (!$allresponses) {
                // To eliminate all potential % chars in heading text (might interfere with the sprintf function).
                $sectionheading = str_replace('%', '', $sectionheading);

                // Replace section heading placeholders with their actual value (if any).
                $original = array('$scorepercent', '$oppositescorepercent');
                $result = array("$scorepercent[$section]%", "$oppositescorepercent[$section]%");
                $sectionheading = str_replace($original, $result, $sectionheading);
                $formatoptions = new stdClass();
                $formatoptions->noclean = true;
                $sectionheading = file_rewrite_pluginfile_urls($sectionheading, 'pluginfile.php',
                                $this->context->id, 'mod_questionnaire', 'sectionheading', $imageid);
                $sectionheading = format_text($sectionheading, 1, $formatoptions);
                $feedbackmessages[] = $OUTPUT->box_start('reportQuestionTitle');
                $feedbackmessages[] = format_text($sectionheading, FORMAT_HTML);
                if(MOODLE_RUN_MODE === MOODLE_MODE_HOST){
                    $feedback = $DB->get_record_select('questionnaire_feedback',
                        'section_id = ? AND minscore <= ? AND ? < maxscore',
                        array($feedbacksectionid, $scorepercent[$section], $scorepercent[$section]),
                        'id,feedbacktext,feedbacktextformat');
                } else {
                    $sql_select = "section_id = $feedbacksectionid AND minscore <= $scorepercent[$section] AND $scorepercent[$section] < maxscore";
                    $feedback = get_remote_questionnaire_feedback($sql_select)[0];
                }
                $feedbackmessages[] = $OUTPUT->box_end();
                if (!empty($feedback->feedbacktext)) {
                    // Clean the text, ready for display.
                    $formatoptions = new stdClass();
                    $formatoptions->noclean = true;
                    $feedbacktext = file_rewrite_pluginfile_urls($feedback->feedbacktext, 'pluginfile.php',
                                    $this->context->id, 'mod_questionnaire', 'feedback', $feedback->id);
                    $feedbacktext = format_text($feedbacktext, $feedback->feedbacktextformat, $formatoptions);
                    $feedbackmessages[] = $OUTPUT->box_start('feedbacktext');
                    $feedbackmessages[] = $feedbacktext;
                    $feedbackmessages[] = $OUTPUT->box_end();
                }
            }
        }

        // Display class or group score.
        switch ($action) {
            case 'vallasort':
                asort($allscore);
                break;
            case 'vallarsort':
                arsort($allscore);
                break;
            default:
        }

        foreach ($allscore as $key => $sc) {
            $lb = explode("|", $chartlabels[$key]);
            $oppositescore = '';
            $oppositeallscore = '';
            if (count($lb) > 1) {
                $sectionlabel = $lb[0].' | '.$lb[1];
                $oppositescore = ' | '.$oppositescorepercent[$key].'%';
                $oppositeallscore = ' | '.$alloppositescorepercent[$key].'%';
            } else {
                $sectionlabel = $chartlabels[$key];
            }
            if ($compare) {
                $table->data[] = array($sectionlabel, $scorepercent[$key].'%'.$oppositescore,
                                $allscorepercent[$key].'%'.$oppositeallscore);
            } else {
                $table->data[] = array($sectionlabel, $allscorepercent[$key].'%'.$oppositeallscore);
            }
        }
        $usergraph = get_config('questionnaire', 'usergraph');
        if ($usergraph && $this->survey->chart_type) {
            draw_chart($feedbacktype = 'sections', $this->survey->chart_type, array_values($chartlabels),
                array_values($scorepercent), array_values($allscorepercent), $sectionlabel, $groupname, $allresponses);
        }
        if ($this->survey->feedbackscores) {
            echo html_writer::table($table);
        }

        return $feedbackmessages;
    }

}
