<?php
/**
 * Created by PhpStorm.
 * User: Minh Nguyen
 * Date: 6/7/2016
 * Time: 10:14 AM
 */
defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir.'/formslib.php');

/**
 * The form for handling editing a course.
 */
class course_info_form extends moodleform{
    function definition()
    {
        global $CFG;
        
        $courseinfo = $this->_customdata['courseinfo'];
        $editoroptions = $this->_customdata['editoptions'];

        $mform = $this->_form;
        
        $mform->addElement('header', 'General', get_string('general', 'form'));

        $mform->addElement('hidden', 'id');
        $mform->setType('id', PARAM_INT);

        $mform->addElement('hidden', 'course');
        $mform->setType('course', PARAM_INT);
        
        $mform->addElement('editor', 'info_editor', 'Information Course', null, $editoroptions);
        $mform->setType('info_editor', PARAM_RAW);

        $mform->addElement('date_time_selector', 'validatetime', 'Validate Time', array('optional'=>true));

        $mform->addElement('text', 'note', 'Note', 'maxlength="254" size="50"');
        $mform->setType('note', PARAM_RAW );

        // Create button form
        $this->add_action_buttons(true);
        
        //Finally set the current form data
        $this->set_data($courseinfo);
    }

    //Custom validation should be added here
    function validation($data, $files) {
        return array();
    }
}

