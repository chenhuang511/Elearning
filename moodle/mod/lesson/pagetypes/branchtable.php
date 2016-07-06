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
 * Branch Table
 *
 * @package mod_lesson
 * @copyright  2009 Sam Hemelryk
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 **/

defined('MOODLE_INTERNAL') || die();

/** Branch Table page */
define("LESSON_PAGE_BRANCHTABLE", "20");

require_once($CFG->dirroot . '/mod/lesson/remote/locallib.php');

class lesson_page_type_branchtable extends lesson_page
{

    protected $type = lesson_page::TYPE_STRUCTURE;
    protected $typeid = LESSON_PAGE_BRANCHTABLE;
    protected $typeidstring = 'branchtable';
    protected $string = null;
    protected $jumpto = null;

    public function get_typeid()
    {
        return $this->typeid;
    }

    public function get_typestring()
    {
        if ($this->string === null) {
            $this->string = get_string($this->typeidstring, 'lesson');
        }
        return $this->string;
    }

    /**
     * Gets an array of the jumps used by the answers of this page
     *
     * @return array
     */
    public function get_jumps()
    {
        global $DB;
        $jumps = array();
        $params = array("lessonid" => $this->lesson->id, "pageid" => $this->properties->id);
        if ($answers = $this->get_answers()) {
            foreach ($answers as $answer) {
                if ($answer->answer === '') {
                    // show only jumps for real branches (==have description)
                    continue;
                }
                $jumps[] = $this->get_jump_name($answer->jumpto);
            }
        } else {
            // We get here is the lesson was created on a Moodle 1.9 site and
            // the lesson contains question pages without any answers.
            $jumps[] = $this->get_jump_name($this->properties->nextpageid);
        }
        return $jumps;
    }

    public static function get_jumptooptions($firstpage, lesson $lesson)
    {
        global $DB, $PAGE;
        $jump = array();
        $jump[0] = get_string("thispage", "lesson");
        $jump[LESSON_NEXTPAGE] = get_string("nextpage", "lesson");
        $jump[LESSON_PREVIOUSPAGE] = get_string("previouspage", "lesson");
        $jump[LESSON_EOL] = get_string("endoflesson", "lesson");
        $jump[LESSON_UNSEENBRANCHPAGE] = get_string("unseenpageinbranch", "lesson");
        $jump[LESSON_RANDOMPAGE] = get_string("randompageinbranch", "lesson");
        $jump[LESSON_RANDOMBRANCH] = get_string("randombranch", "lesson");

        if (!$firstpage) {
            $params = array();
            $params['parameters[0][name]'] = "lessonid";
            $params['parameters[0][value]'] = $lesson->id;
            $params['parameters[1][name]'] = "prevpageid";
            $params['parameters[1][value]'] = 0;
            if (!$apageid = get_remote_field_by("lesson_pages", $params, "id")) {
                print_error('cannotfindfirstpage', 'lesson');
            }
            while (true) {
                if ($apageid) {
                    $params = array();
                    $params['parameters[0][name]'] = "id";
                    $params['parameters[0][value]'] = $apageid;
                    $title = get_remote_field_by("lesson_pages", $params, "title");
                    $jump[$apageid] = $title;
                    $apageid = intval(get_remote_field_by("lesson_pages", $params, "nextpageid"));
                } else {
                    // last page reached
                    break;
                }
            }
        }
        return $jump;
    }

    public function get_idstring()
    {
        return $this->typeidstring;
    }

    public function display($renderer, $attempt)
    {
        global $PAGE, $CFG;

        $output = '';
        $options = new stdClass;
        $options->para = false;
        $options->noclean = true;

        if ($this->lesson->slideshow) {
            $output .= $renderer->slideshow_start($this->lesson);
        }
        // We are using level 3 header because the page title is a sub-heading of lesson title (MDL-30911).
        $output .= $renderer->heading(format_string($this->properties->title), 3);
        $output .= $renderer->box($this->get_contents(), 'contents');

        $buttons = array();
        $i = 0;
        foreach ($this->get_answers() as $answer) {
            if ($answer->answer === '') {
                // not a branch!
                continue;
            }
            $params = array();
            $params['id'] = $PAGE->cm->id;
            $params['pageid'] = $this->properties->id;
            $params['sesskey'] = sesskey();
            $params['jumpto'] = $answer->jumpto;
            $url = new moodle_url('/mod/lesson/remote/continue.php', $params);
            $buttons[] = $renderer->single_button($url, strip_tags(format_text($answer->answer, FORMAT_MOODLE, $options)));
            $i++;
        }
        // Set the orientation
        if ($this->properties->layout) {
            $buttonshtml = $renderer->box(implode("\n", $buttons), 'branchbuttoncontainer horizontal');
        } else {
            $buttonshtml = $renderer->box(implode("\n", $buttons), 'branchbuttoncontainer vertical');
        }
        $output .= $buttonshtml;

        if ($this->lesson->slideshow) {
            $output .= $renderer->slideshow_end();
        }

        // Trigger an event: content page viewed.
        $eventparams = array(
            'context' => context_module::instance($PAGE->cm->id),
            'objectid' => $this->properties->id
        );

        $event = \mod_lesson\event\content_page_viewed::create($eventparams);
        $event->trigger();

        return $output;
    }

    public function check_answer()
    {
        global $USER, $DB, $PAGE, $CFG;

        require_sesskey();
        $newpageid = optional_param('jumpto', null, PARAM_INT);
        // going to insert into lesson_branch
        if ($newpageid == LESSON_RANDOMBRANCH) {
            $branchflag = 1;
        } else {
            $branchflag = 0;
        }
        $grades = get_remote_list_lesson_grades_by_lessonid_and_userid($this->lesson->id, $USER->id);

        if ($grades) {
            $retries = count($grades);
        } else {
            $retries = 0;
        }

        //  this is called when jumping to random from a branch table
        $context = context_module::instance($PAGE->cm->id);
        if ($newpageid == LESSON_UNSEENBRANCHPAGE) {
            if (has_capability('mod/lesson:manage', $context)) {
                $newpageid = LESSON_NEXTPAGE;
            } else {
                $newpageid = lesson_unseen_question_jump($this->lesson, $USER->id, $this->properties->id);  // this may return 0
            }
        }
        // convert jumpto page into a proper page id
        if ($newpageid == 0) {
            $newpageid = $this->properties->id;
        } elseif ($newpageid == LESSON_NEXTPAGE) {
            if (!$newpageid = $this->nextpageid) {
                // no nextpage go to end of lesson
                $newpageid = LESSON_EOL;
            }
        } elseif ($newpageid == LESSON_PREVIOUSPAGE) {
            $newpageid = $this->prevpageid;
        } elseif ($newpageid == LESSON_RANDOMPAGE) {
            $newpageid = lesson_random_question_jump($this->lesson, $this->properties->id);
        } elseif ($newpageid == LESSON_RANDOMBRANCH) {
            $newpageid = lesson_unseen_branch_jump($this->lesson, $USER->id);
        }

        // Record this page in lesson_branch.
        $data = array();

        $data['data[0][name]'] = 'lessonid';
        $data['data[0][value]'] = $this->lesson->id;
        $data['data[1][name]'] = 'userid';
        $data['data[1][value]'] = $USER->id;
        $data['data[2][name]'] = 'pageid';
        $data['data[2][value]'] = $this->properties->id;
        $data['data[3][name]'] = 'retry';
        $data['data[3][value]'] = $retries;
        $data['data[4][name]'] = 'flag';
        $data['data[4][value]'] = $branchflag;
        $data['data[5][name]'] = 'timeseen';
        $data['data[5][value]'] = time();
        $data['data[6][name]'] = 'nextpageid';
        $data['data[6][value]'] = $newpageid;

        $result = save_remote_lesson_branch($data);

        redirect(new moodle_url('/mod/lesson/remote/view.php', array('id' => $PAGE->cm->id, 'pageid' => $newpageid)));
    }

    public function display_answers(html_table $table)
    {
        $answers = $this->get_answers();
        $options = new stdClass;
        $options->noclean = true;
        $options->para = false;
        $i = 1;
        foreach ($answers as $answer) {
            if ($answer->answer === '') {
                // not a branch!
                continue;
            }
            $cells = array();
            $cells[] = "<span class=\"label\">" . get_string("branch", "lesson") . " $i<span>: ";
            $cells[] = format_text($answer->answer, $answer->answerformat, $options);
            $table->data[] = new html_table_row($cells);

            $cells = array();
            $cells[] = "<span class=\"label\">" . get_string("jump", "lesson") . " $i<span>: ";
            $cells[] = $this->get_jump_name($answer->jumpto);
            $table->data[] = new html_table_row($cells);

            if ($i === 1) {
                $table->data[count($table->data) - 1]->cells[0]->style = 'width:20%;';
            }
            $i++;
        }
        return $table;
    }

    public function get_grayout()
    {
        return 1;
    }

    public function report_answers($answerpage, $answerdata, $useranswer, $pagestats, &$i, &$n)
    {
        $answers = $this->get_answers();
        $formattextdefoptions = new stdClass;
        $formattextdefoptions->para = false;  //I'll use it widely in this page
        $formattextdefoptions->context = $answerpage->context;

        foreach ($answers as $answer) {
            $data = "<input type=\"button\" name=\"$answer->id\" value=\"" . s(strip_tags(format_text($answer->answer, FORMAT_MOODLE, $formattextdefoptions))) . "\" disabled=\"disabled\"> ";
            $data .= get_string('jumpsto', 'lesson', $this->get_jump_name($answer->jumpto));
            $answerdata->answers[] = array($data, "");
            $answerpage->answerdata = $answerdata;
        }
        return $answerpage;
    }

    public function update($properties, $context = null, $maxbytes = null)
    {
        if (empty($properties->display)) {
            $properties->display = '0';
        }
        if (empty($properties->layout)) {
            $properties->layout = '0';
        }
        return parent::update($properties);
    }

    public function add_page_link($previd)
    {
        global $PAGE, $CFG;
        $addurl = new moodle_url('/mod/lesson/remote/editpage.php', array('id' => $PAGE->cm->id, 'pageid' => $previd, 'qtype' => LESSON_PAGE_BRANCHTABLE));
        return array('addurl' => $addurl, 'type' => LESSON_PAGE_BRANCHTABLE, 'name' => get_string('addabranchtable', 'lesson'));
    }

    protected function get_displayinmenublock()
    {
        return true;
    }

    public function is_unseen($param)
    {
        global $USER, $DB;
        if (is_array($param)) {
            $seenpages = $param;
            $branchpages = $this->lesson->get_sub_pages_of($this->properties->id, array(LESSON_PAGE_BRANCHTABLE, LESSON_PAGE_ENDOFBRANCH));
            foreach ($branchpages as $branchpage) {
                if (array_key_exists($branchpage->id, $seenpages)) {  // check if any of the pages have been viewed
                    return false;
                }
            }
            return true;
        } else {
            $nretakes = $param;
            if (!$DB->count_records("lesson_attempts", array("pageid" => $this->properties->id, "userid" => $USER->id, "retry" => $nretakes))) {
                return true;
            }
            return false;
        }
    }
}

class lesson_add_page_form_branchtable extends lesson_add_page_form_base
{

    public $qtype = LESSON_PAGE_BRANCHTABLE;
    public $qtypestring = 'branchtable';
    protected $standard = false;

    public function custom_definition()
    {
        global $PAGE;

        $mform = $this->_form;
        $lesson = $this->_customdata['lesson'];

        $firstpage = optional_param('firstpage', false, PARAM_BOOL);

        $jumptooptions = lesson_page_type_branchtable::get_jumptooptions($firstpage, $lesson);

        $mform->setDefault('qtypeheading', get_string('addabranchtable', 'lesson'));

        $mform->addElement('hidden', 'firstpage');
        $mform->setType('firstpage', PARAM_BOOL);
        $mform->setDefault('firstpage', $firstpage);

        $mform->addElement('hidden', 'qtype');
        $mform->setType('qtype', PARAM_INT);

        $mform->addElement('text', 'title', get_string("pagetitle", "lesson"), array('size' => 70));
        $mform->setType('title', PARAM_TEXT);
        $mform->addRule('title', null, 'required', null, 'server');

        $this->editoroptions = array('noclean' => true, 'maxfiles' => EDITOR_UNLIMITED_FILES, 'maxbytes' => $PAGE->course->maxbytes);
        $mform->addElement('editor', 'contents_editor', get_string("pagecontents", "lesson"), null, $this->editoroptions);
        $mform->setType('contents_editor', PARAM_RAW);

        $mform->addElement('checkbox', 'layout', null, get_string("arrangebuttonshorizontally", "lesson"));
        $mform->setDefault('layout', true);

        $mform->addElement('checkbox', 'display', null, get_string("displayinleftmenu", "lesson"));
        $mform->setDefault('display', true);

        for ($i = 0; $i < $lesson->maxanswers; $i++) {
            $mform->addElement('header', 'headeranswer' . $i, get_string('branch', 'lesson') . ' ' . ($i + 1));
            $this->add_answer($i, get_string("description", "lesson"), $i == 0);

            $mform->addElement('select', 'jumpto[' . $i . ']', get_string("jump", "lesson"), $jumptooptions);
            if ($i === 0) {
                $mform->setDefault('jumpto[' . $i . ']', 0);
            } else {
                $mform->setDefault('jumpto[' . $i . ']', LESSON_NEXTPAGE);
            }
        }
    }
}
