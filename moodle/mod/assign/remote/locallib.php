<?php
defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot . '/lib/remote/lib.php');

class assign_mod
{
    private $cmid;
    private $assignid;
    private $coursemodule;
    private $option;
    private $OUTPUT;
    private $PAGE;
    private $course;
    private $cm;

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


    public function __construct($cmid, $option = [])
    {
        $this->cmid = $cmid;
        $this->option = $option;
        $cmd = get_remote_course_module($cmid);
        $this->assignid = $cmd->instance;
        $this->coursemodule = $cmd;
    }


    //View
    public function view_summary()
    {
        $responedata = moodle_webservice_client(array_merge($this->option, array('domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_assign_get_submission_status',
            'params' => array('assignid' => $this->assignid, "ip_address" => "10.0.0.254", "username" => "admin"),
        )));
        $gradingsummary = $responedata->gradingsummary;
        $OUTPUT = $this->getOUTPUT();
        $html = "";
        $html .= $OUTPUT->box_start('assign-detail', "assign_{$this->assignid}");
        $html .= html_writer::tag('h3', $this->coursemodule->name, array('class' => 'gradingsummary'));
        $table = new html_table();
        $table->head = array('Name', 'Value');
        $table->data[] = array("Participants", $gradingsummary->participantcount);
        $table->data[] = array("submissiondraftscount", $gradingsummary->submissiondraftscount);
        $table->data[] = array("Submitted", $gradingsummary->submissionssubmittedcount);
        $table->data[] = array("Needs grading", $gradingsummary->submissionsneedgradingcount);
        $html .= html_writer::table($table);
        $html .= $OUTPUT->box_end();
        $html .= html_writer::tag('a', "View all submissions", array("href" => "/mod/assign/remote/view.php?action=grading&modid={$this->assignid}", 'class' => 'btn'));
        $html .= "&nbsp;";
        $html .= html_writer::tag('a', "Grade", array('class' => 'btn btn-primary'));
        return $html;
    }
    public function view_grading(){

    }
}