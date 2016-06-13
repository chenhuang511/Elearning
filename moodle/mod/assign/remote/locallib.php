<?php
defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot . '/lib/remote/lib.php');

/**
 * get lesson by id
 *
 * @param int $lessonid . the id of lesson
 * @param array $options . the options
 *
 * @return stdClass $lesson
 */
function get_remote_assign_by_id($assignid, $options = array())
{
    return moodle_webservice_client(array_merge($options,
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_assign_get_assign_by_id',
            'params' => array('assignid' => $assignid),
        )
    ));
}

function get_remote_assign_submission_status($assignid) {
    global $CFG, $USER;

    require_once($CFG->dirroot . '/mnet/lib.php');
    $hostname = mnet_get_hostname_from_uri($CFG->wwwroot);
    // Get the IP address for that host - if this fails, it will return the hostname string
    $hostip = gethostbyname($hostname);

    return moodle_webservice_client(array('domain' => HUB_URL,
        'token' => HOST_TOKEN,
        'function_name' => 'local_mod_assign_get_submission_status',
        'params' => array('assignid' => $assignid, "ip_address" => $hostip, "username" => $USER->username),
    ));
}

class remote_assign_mod
{
    private $cmid;
    private $assignid;
    private $coursemodule;
    private $option;
    private $OUTPUT;
    private $PAGE;
    private $course;

    /**
     * @return mixed
     */
    public function getPAGE()
    {
        return $this->PAGE;
    }

    /**
     * @param mixed $PAGE
     */
    public function setPAGE($PAGE)
    {
        $this->PAGE = $PAGE;
    }

    /**
     * @return mixed
     */
    public function getOUTPUT()
    {
        return $this->OUTPUT;
    }

    /**
     * @param mixed $OUTPUT
     */
    public function setOUTPUT($OUTPUT)
    {
        $this->OUTPUT = $OUTPUT;
    }

    public function __construct($cm, $option = [])
    {
        $this->cmid = $cm->id;
        $this->option = $option;
        $this->assignid = $cm->instance;
        $this->coursemodule = $cm;
    }

    //View
    public function view_summary()
    {
        global $CFG;
        $responedata = get_remote_assign_submission_status($this->assignid);
        $gradingsummary = $responedata->gradingsummary;
        $OUTPUT = $this->getOUTPUT();
        $html = "";
        $html .= $OUTPUT->box_start('assign-detail', "assign_{$this->assignid}");
        $html .= html_writer::tag('h3', $this->coursemodule->name, array('class' => 'gradingsummary'));
        $table = new html_table();
        $table->head = array('Name', 'Value');
        $table->data[] = array("Participants", $gradingsummary->participantcount);
        $table->data[] = array("Submission drafts count", $gradingsummary->submissiondraftscount);
        $table->data[] = array("Submitted", $gradingsummary->submissionssubmittedcount);
        $table->data[] = array("Needs grading", $gradingsummary->submissionsneedgradingcount);
        $html .= html_writer::table($table);
        $html .= $OUTPUT->box_end();
        $html .= html_writer::tag('a', "View all submissions", array(
            "href" => "#",
            'class' => 'btn remote-link-action',
            'data-module' => json_encode(array(
                'url' => $CFG->wwwroot . '/mod/assign/remote/api-view.php',
                'params' => array(
                    'id' => $this->cmid,
                    'action' => 'grading',
                ),
                'method' => 'get',
            )),
        ));
        $html .= "&nbsp;";
        $html .= html_writer::tag('a', "Grade", array('class' => 'btn btn-primary'));
        $html .= $this->view_submission_status($responedata);
        return $html;
    }

    public function view_submission_status($responedata){
        global $CFG;
        $lastattempt = $responedata->lastattempt;
        $OUTPUT = $this->getOUTPUT();
        $html = "";
        $html .= $OUTPUT->box_start('assign-detail', "assign_{$this->assignid}");
        $html .= html_writer::tag('h3', "Submission status", array('class' => 'gradingsummary'));
        $table = new html_table();
        $table->head = array('Name', 'Value');
        $table->data[] = array("Submission status", $lastattempt->participantcount);
        $table->data[] = array("Grading status", $lastattempt->submissiondraftscount);
        $table->data[] = array("Due date", $lastattempt->submissionssubmittedcount);
        $table->data[] = array("Time remaining", $lastattempt->submissionssubmittedcount);
        $table->data[] = array("Last modified", $lastattempt->submissionssubmittedcount);
        $table->data[] = array("Online text", $lastattempt->submissionssubmittedcount);
        $html .= html_writer::table($table);
        $html .= $OUTPUT->box_end();
        $html .= html_writer::tag('a', "Add submissions", array(
            "href" => "#",
            'class' => 'btn remote-link-action assign-action-edit',
            'data-module' => json_encode(array(
                'url' => $CFG->wwwroot . '/mod/assign/remote/api-view.php',
                'params' => array(
                    'id' => $this->cmid,
                    'action' => 'editsubmission',
                ),
                'method' => 'get',
            )),
        ));
        return $html;
    }
}
