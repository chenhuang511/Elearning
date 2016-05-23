<?php
/**
 * Created by PhpStorm.
 * User: Minh Nguyen
 * Date: 5/20/2016
 * Time: 2:28 PM
 */
defined('MOODLE_INTERNAL') || die;

require_once($CFG->libdir.'/formslib.php');
require_once($CFG->libdir.'/completionlib.php');
require_once($CFG->libdir. '/coursecatlib.php');

class course_edit_hub_form extends moodleform
{
    /**
     * Form definition.
     */
    function definition()
    {
        global $CFG, $PAGE ;
        $mform = $this->_form; // Don't forget the underscore!

        $PAGE->requires->yui_module('moodle-course-formatchooser', 'M.course.init_formatchooser',
            array(array('formid' => $mform->getAttribute('id'))));

        $mform->addElement('header','general', get_string('general', 'form'));

        // full name
        $mform->addElement('text','fullname', get_string('fullnamecourse'),'maxlength="254" size="50"');
        $mform->addHelpButton('fullname', 'fullnamecourse');
        $mform->addRule('fullname', get_string('missingfullname'), 'required', null, 'client');
        $mform->setType('fullname', PARAM_TEXT);
        // short name
        $mform->addElement('text', 'shortname', get_string('shortnamecourse'), 'maxlength="100" size="20"');
        $mform->addHelpButton('shortname', 'shortnamecourse');
        $mform->addRule('shortname', get_string('missingshortname'), 'required', null, 'client');
        $mform->setType('shortname', PARAM_TEXT);
        // categoryid
        $mform->addElement('text', 'category', get_string('coursecategory'),'maxlength="10" size="20"' );
        $mform->addHelpButton('category', 'coursecategory');
        $mform->addRule('category', get_string('missingcategory'), 'required', null, 'client');
        $mform->setType('category', PARAM_INT);
        $mform->setDefault('category', 2);
        // visible
        $choices = array();
        $choices['0'] = get_string('hide');
        $choices['1'] = get_string('show');
        $mform->addElement('select', 'visible', get_string('visible'), $choices);
        $mform->addHelpButton('visible', 'visible');
        $mform->setDefault('visible', 1);

        // startdate
        $mform->addElement('date_selector', 'startdate', get_string('startdate'));
        $mform->addHelpButton('startdate', 'startdate');
        $mform->setDefault('startdate', time() + 3600 * 24);
        // idnumber
        $mform->addElement('text','idnumber', get_string('idnumbercourse'),'maxlength="100"  size="10"');
        $mform->addHelpButton('idnumber', 'idnumbercourse');
        $mform->setType('idnumber', PARAM_RAW);

        $mform->addElement('header', 'descriptionhdr', get_string('description'));
        $mform->setExpanded('descriptionhdr');
        // editor
        $mform->addElement('editor','summary_editor', get_string('coursesummary'), null);
        $mform->addHelpButton('summary_editor', 'coursesummary');
        $mform->setType('summary_editor', PARAM_RAW);

        $mform->addElement('header', 'courseformathdr', get_string('type_format', 'plugin'));

        $courseformats = get_sorted_course_formats(true);
        $formcourseformats = array();
        foreach ($courseformats as $courseformat) {
            $formcourseformats[$courseformat] = get_string('pluginname', "format_$courseformat");
        }

        $mform->addElement('select', 'format', get_string('format'), $formcourseformats);
        $mform->addHelpButton('format', 'format');
        $mform->setDefault('format', 'weeks');

        // Button to update format-specific options on format change (will be hidden by JavaScript).
        $mform->registerNoSubmitButton('updatecourseformat');
        $mform->addElement('submit', 'updatecourseformat', get_string('courseformatudpate'));

        // Just a placeholder for the course format options.
        $mform->addElement('hidden', 'addcourseformatoptionshere');
        $mform->setType('addcourseformatoptionshere', PARAM_BOOL);
        
        // Appearance.
        $mform->addElement('header', 'appearancehdr', get_string('appearance'));

        if (!empty($CFG->allowcoursethemes)) {
            $themeobjects = get_list_of_themes();
            $themes=array();
            $themes[''] = get_string('forceno');
            foreach ($themeobjects as $key=>$theme) {
                if (empty($theme->hidefromselector)) {
                    $themes[$key] = get_string('pluginname', 'theme_'.$theme->name);
                }
            }
            $mform->addElement('select', 'theme', get_string('forcetheme'), $themes);
        }

//        $languages=array();
//        $languages[''] = get_string('forceno');
//        $languages += get_string_manager()->get_list_of_translations();
//        $mform->addElement('select', 'lang', get_string('forcelanguage'), $languages);

        // Multi-Calendar Support - see MDL-18375.
        $calendartypes = \core_calendar\type_factory::get_list_of_calendar_types();
        // We do not want to show this option unless there is more than one calendar type to display.
        if (count($calendartypes) > 1) {
            $calendars = array();
            $calendars[''] = get_string('forceno');
            $calendars += $calendartypes;
            $mform->addElement('select', 'calendartype', get_string('forcecalendartype', 'calendar'), $calendars);
        }

        $options = range(0, 10);
        $mform->addElement('select', 'newsitems', get_string('newsitemsnumber'), $options);
        $mform->addHelpButton('newsitems', 'newsitemsnumber');

        $mform->addElement('selectyesno', 'showgrades', get_string('showgrades'));
        $mform->addHelpButton('showgrades', 'showgrades');

        $mform->addElement('selectyesno', 'showreports', get_string('showreports'));
        $mform->addHelpButton('showreports', 'showreports');

        // Completion tracking.
        if (completion_info::is_enabled_for_site()) {
            $mform->addElement('header', 'completionhdr', get_string('completion', 'completion'));
            $mform->addElement('selectyesno', 'enablecompletion', get_string('enablecompletion', 'completion'));
            $mform->addHelpButton('enablecompletion', 'enablecompletion', 'completion');
        } else {
            $mform->addElement('hidden', 'enablecompletion');
            $mform->setType('enablecompletion', PARAM_INT);
            $mform->setDefault('enablecompletion', 0);
        }

        // Files and uploads
        $mform->addElement('header', 'filehdr', get_string('filesanduploads'));
        $choices = get_max_upload_sizes($CFG->maxbytes, 0, 0, null);
        $mform->addElement('select', 'maxbytes', get_string('maximumupload'), $choices);
        $mform->addHelpButton('maxbytes', 'maximumupload');
        $mform->setDefault('maxbytes', 104857600);


        // Group
        $mform->addElement('header','groups', get_string('groupsettingsheader', 'group'));

        $choices = array();
        $choices[NOGROUPS] = get_string('groupsnone', 'group');
        $choices[SEPARATEGROUPS] = get_string('groupsseparate', 'group');
        $choices[VISIBLEGROUPS] = get_string('groupsvisible', 'group');
        $mform->addElement('select', 'groupmode', get_string('groupmode', 'group'), $choices);
        $mform->addHelpButton('groupmode', 'groupmode', 'group');

        $mform->addElement('selectyesno', 'groupmodeforce', get_string('groupmodeforce', 'group'));
        $mform->addHelpButton('groupmodeforce', 'groupmodeforce', 'group');

        //default groupings selector
        $options = array();
        $options[0] = get_string('none');
        $mform->addElement('select', 'defaultgroupingid', get_string('defaultgrouping', 'group'), $options);

        $buttonarray = array();
        $classarray = array('class' => 'form-submit');

        $buttonarray[] = &$mform->createElement('submit', 'saveandreturn', 'Create course to hub' , $classarray);
        $buttonarray[] = &$mform->createElement('cancel');

        $mform->addGroup($buttonarray, 'buttonar', '', array(' '), false);
        $mform->closeHeaderBefore('buttonar');

    }

    function definition_after_data() {
        $mform = $this->_form;

        // add course format options
        $formatvalue = $mform->getElementValue('format');
        if (is_array($formatvalue) && !empty($formatvalue)) {
            $courseformat = course_get_format((object)array('format' => $formatvalue[0]));

            $elements = $courseformat->create_edit_form_elements($mform);
            for ($i = 0; $i < count($elements); $i++) {
                $mform->insertElementBefore($mform->removeElement($elements[$i]->getName(), false),
                    'addcourseformatoptionshere');
            }
        }
    }


    function validation($data, $files)
    {
        return array(); //TODO: Change the autogenerated stub
    }
}