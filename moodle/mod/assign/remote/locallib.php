<?php
defined('MOODLE_INTERNAL') || die;

/** General rendering target, usually normal browser page */
define('RENDERER_TARGET_GENERAL', 'general');

require_once($CFG->dirroot . '/lib/remote/lib.php');
require_once($CFG->dirroot . '/mod/assign/renderable.php');


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

function get_remote_submissions_by_assign_id($assignmentids, $options = array())
{
    return moodle_webservice_client(array_merge($options,
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN_M,
            'function_name' => 'mod_assign_get_submissions',
            'params' => array('assignmentids' => $assignmentids),
        )
    ));
}

function get_list_user_id_from_submissions($submissions = array()) {
    $usersid = array();
    foreach ($submissions as $submission) {
        $usersid[] = $submission->userid;
    }
    return $usersid;
}

class remote_assign_mod
{
    /** @var stdClass the assignment record that contains the global settings for this assign instance */
    private $instance;

    /** @var stdClass the course this assign instance belongs to */
    private $course;

    /** @var assign_renderer the custom renderer for this module */
    private $output;

    /** @var cm_info the course module for this assign instance */
    private $coursemodule;

    public function __construct($coursemodulecontext, $coursemodule, $course)
    {
        $this->context = $coursemodulecontext;
        $this->course = $course;

        $this->coursemodule = $coursemodule;
    }

    /**
     * Get the current course module.
     *
     * @return cm_info|null The course module or null if not known
     */
    public function get_course_module()
    {
        return $this->coursemodule;
    }

    /**
     * Lazy load the page renderer and expose the renderer to plugins.
     *
     * @return assign_renderer
     */
    public function get_renderer() {
        global $PAGE;

        if ($this->output) {
            return $this->output;
        }

        $this->output = $PAGE->get_renderer('mod_assign', null, RENDERER_TARGET_GENERAL);

        return $this->output;
    }

    /**
     * Get the settings for the current instance of this assignment
     *
     * @return stdClass The settings
     */
    public function get_instance()
    {
        $this->instance = get_remote_assign_by_id($this->get_course_module()->instance);

        return $this->instance;
    }


    /**
     * Based on the current assignment settings should we display the intro.
     *
     * @return bool showintro
     */
    public function show_intro() {
        if ($this->get_instance()->alwaysshowdescription ||
            time() > $this->get_instance()->allowsubmissionsfromdate) {
            return true;
        }
        return false;
    }

    /**
     * Display the assignment, used by view.php
     *
     * The assignment is displayed differently depending on your role,
     * the settings for the assignment and the status of the assignment.
     *
     * @param string $action The current action if any.
     * @param array $args Optional arguments to pass to the view (instead of getting them from GET and POST).
     * @return string - The page output.
     */
    public function view($action = '', $args = array())
    {
        global $PAGE;

        $o = '';
        $mform = null;

        if ($action) {

        } else {
            $o .= $this->view_submission_remote_page();
        }
        return $o;
    }

    /**
     * View submissions page (contains details of current submission).
     *
     * @return string
     */
    protected function view_submission_remote_page()
    {
        global $CFG, $DB, $USER, $PAGE;

        $instance = $this->get_instance();

        $o = '';

        $o .= $this->get_renderer()->render( new assign_remote_header($instance,
                                                        $this->context,
                                                        $this->show_intro(),
                                                        $this->get_course_module()->id),
                                                        '','','');

        return $o;
    }
}