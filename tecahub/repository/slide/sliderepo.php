<?php

defined('MOODLE_INTERNAL') || die();
class sliderepo
{
    public function get_slides($page = 0) {
        global $DB, $CFG;
        $slides = $DB->get_records('slide_storage', array('visibility' => 1));
        $returns = array();
        if (count($slides) > 0) {
            foreach ($slides as $slide) {
                $url = $CFG->wwwroot . '/contentcreator/view/index.php?id=' . $slide->id;
                $returns[] = array(
                    'id' => $slide->id,
                    'filename' => $slide->filename,
                    'title' => $slide->filename,
                    'url' => $url,
                    'source' => $url,
                    'icon' => '//www.freeiconspng.com/uploads/presentation-icon-9.png',
                    'realicon' => '//www.freeiconspng.com/uploads/presentation-icon-9.png',
                    'realthumbnail' => '//www.freeiconspng.com/uploads/presentation-icon-9.png',
                );
            }
        }
        return $returns;
    }
}
