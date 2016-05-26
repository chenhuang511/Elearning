<?php

defined('MOODLE_INTERNAL') || die;

require_once("$CFG->libdir/externallib.php");

/**
 * Course external functions
 *
 * @package    core_course
 * @category   external
 * @copyright  2011 Jerome Mouneyrac
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @since Moodle 2.2
 */
class local_nccsoft_external extends external_api
{
    public static function get_mod_lesson_by_id_parameters()
    {
        return new external_function_parameters(
            array('lessonid' => new external_value(PARAM_INT, 'lesson id'),
                'options' => new external_multiple_structure(
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

    public static function get_mod_lesson_by_id($lessonid, $options = array())
    {
        global $DB;

        //validate parameter
        $params = self::validate_parameters(self::get_mod_lesson_by_id_parameters(),
            array('lessonid' => $lessonid, 'options' => $options));

        //retrieve the page
        return $DB->get_record('lesson', array('id' => $params['lessonid']), '*', MUST_EXIST);
    }

    public static function get_mod_lesson_by_id_returns()
    {
        return new external_single_structure(
            array(
                'id' => new external_value(PARAM_INT, 'lesson id'),
                'course' => new external_value(PARAM_INT, 'course id'),
                'name' => new external_value(PARAM_TEXT, 'lesson name'),
                'intro' => new external_value(PARAM_RAW, 'lesson intro'),
                'introformat' => new external_value(PARAM_INT, 'intro format'),
                'practice' => new external_value(PARAM_INT, 'practice'),
                'modattempts' => new external_value(PARAM_INT, 'mod attempts'),
                'usepassword' => new external_value(PARAM_INT, 'use password'),
                'password' => new external_value(PARAM_TEXT, 'password'),
                'dependency' => new external_value(PARAM_INT, 'dependency'),
                'conditions' => new external_value(PARAM_RAW, 'condition'),
                'grade' => new external_value(PARAM_INT, 'grade'),
                'custom' => new external_value(PARAM_INT, 'custom'),
                'ongoing' => new external_value(PARAM_INT, 'on going'),
                'usemaxgrade' => new external_value(PARAM_INT, 'use max grade'),
                'maxanswers' => new external_value(PARAM_INT, 'max answer'),
                'maxattempts' => new external_value(PARAM_INT, 'max attempts'),
                'review' => new external_value(PARAM_INT, 'review'),
                'nextpagedefault' => new external_value(PARAM_INT, 'next page default'),
                'feedback' => new external_value(PARAM_INT, 'feedback'),
                'minquestions' => new external_value(PARAM_INT, 'min question'),
                'maxpages' => new external_value(PARAM_INT, 'max page'),
                'timelimit' => new external_value(PARAM_INT, 'time limit'),
                'retake' => new external_value(PARAM_INT, 'retake'),
                'activitylink' => new external_value(PARAM_INT, 'activity link'),
                'mediafile' => new external_value(PARAM_TEXT, 'media file'),
                'mediaheight' => new external_value(PARAM_INT, 'media height'),
                'mediawidth' => new external_value(PARAM_INT, 'media width'),
                'mediaclose' => new external_value(PARAM_INT, 'media close'),
                'slideshow' => new external_value(PARAM_INT, 'slideshow'),
                'width' => new external_value(PARAM_INT, 'slideshow width'),
                'height' => new external_value(PARAM_INT, 'slideshow height'),
                'bgcolor' => new external_value(PARAM_TEXT, 'background color'),
                'displayleft' => new external_value(PARAM_INT, 'display left'),
                'displayleftif' => new external_value(PARAM_INT, 'display left if'),
                'progressbar' => new external_value(PARAM_INT, 'progress bar'),
                'available' => new external_value(PARAM_INT, 'available'),
                'deadline' => new external_value(PARAM_INT, 'deadline'),
                'timemodified' => new external_value(PARAM_INT, 'time modified'),
                'completionendreached' => new external_value(PARAM_INT, 'completion end reached'),
                'completiontimespent' => new external_value(PARAM_INT, 'completion time spent')
            )
        );
    }
}