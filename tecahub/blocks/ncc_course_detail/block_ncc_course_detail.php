<?php

class block_ncc_course_detail extends block_base {
    public  function init() {
        $this->title = get_string('pluginname', 'block_ncc_course_detail');
    }

    public function get_content()
    {
        if($this->content !== null) {
            return $this->content;
        }

        $this->content = new stdClass();
        $this->content->text = "The content of ncc course detail block";
        $this->content->footer = "Footer here ...";

        return $this->content;
    }
}