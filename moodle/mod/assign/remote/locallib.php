<?php
defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot . '/lib/remote/lib.php');

class assign_mod
{
    private $courseid;
    private $assignid;
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


    public function __construct($courseid, $assignid, $option = [])
    {
        $this->courseid = $courseid;
        $this->assignid = $assignid;
        $this->option = $option;
    }

    public function get_assign_summary_remote()
    {
        // Get course from last parameter if supplied.
        $responedata = moodle_webservice_client(array_merge($this->option, array('domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_get_assignments',
            'params' => array('courseids[0]' => $this->courseid, "ip_address" => "10.0.0.254", "username" => "admin"),
        )));
        if (isset($responedata->courses)) $course = $responedata->courses[0];
        $listassignment = $course->assignments;
        $assignobject = new stdClass();
        foreach ($listassignment as $assignment) {
            if ($assignment->cmid == $this->assignid) {
                $assignobject = $assignment;
                break;
            }
        }
        $this->course = $course;
        $this->cm = $assignobject;
        return array($course, $assignobject);

    }

    //View
    public function view_summary()
    {
        $assignobject = $this->cm;
        $OUTPUT = $this->getOUTPUT();
        $html = "";
        $html .= $OUTPUT->box_start('assign-detail', "assign_{$this->assignid}");
        $html .= html_writer::tag('div', $assignobject->intro, array('class' => 'gradingsummary'));
        $table = new html_table();
        $table->head = array('Name', 'Value');
        $table->data[] = array("No Submissions", $assignobject->nosubmissions);
        $table->data[] = array("Submission drafts", $assignobject->submissiondrafts);
        $table->data[] = array("Send notifications", $assignobject->sendnotifications);
        $table->data[] = array("Grade", $assignobject->grade);
        $table->data[] = array("Teamsubmission", $assignobject->teamsubmission);
        $table->data[] = array("Due date", date("l, d M Y h:i A", $assignobject->duedate));
        $table->data[] = array("Time remaining", $assignobject->cutoffdate);
        $html .= html_writer::table($table);
        $html .= $OUTPUT->box_end();
        $html .= html_writer::tag('a', "View all submissions", array("href" => "/mod/assign/remote/view.php?action=grading&courseid={$this->courseid}&modid={$this->assignid}", 'class' => 'btn'));
        $html .= "&nbsp;";
        $html .= html_writer::tag('a', "Grade", array('class' => 'btn btn-primary'));
        return $html;
    }
    public function view_grading(){

    }
}