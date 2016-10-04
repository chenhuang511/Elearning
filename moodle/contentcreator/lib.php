<?php
defined('MOODLE_INTERNAL') || die();

/**
 * get data from database
 * check if slide id public
 * if not, check slide owner
 * or throw new exception
 */
function getslidesbyid($id) {
    global $DB, $USER;
    $slideobj = $DB->get_record('slide_storage', ['id' => $id]);
    if ($slideobj === false) {
        throw new dml_missing_record_exception('slide_storage');
    }
    if ($slideobj->userid !== $USER->id && $slideobj->visibility != true) {
        throw new moodle_exception('nopermissions', 'error', '', 'view slide');
    }
    return $slideobj;
}

function getcontenthtmlbyslideid($id) {
    $slideobj = getslidesbyid($id);
    if ($slideobj)
        return $slideobj->content_html;
    return '';
}

function printscriptpresentationdata($str, $id) {
    $return = "<script type=\"text/javascript\">
//<![CDATA[";
    $return .= "
    var contentJSON = {$str};
    try {
        localStorage.setItem('contentJSON', contentJSON);
        localStorage.setItem('presentationId', {$id});
    } catch(e) {}
    ";

    $return .= "//]]>
</script>";
    return $return;
}

function printscriptnewpresentation() {
    $return = "<script type=\"text/javascript\">
//<![CDATA[";
    $return .= "
    try {
        localStorage.setItem('contentJSON', '');
        localStorage.setItem('presentationId', -1);
    } catch(e) {}
    ";

    $return .= "//]]>
</script>";
    return $return;
}

function printallslidebelongtouser($userid = null) {
    global $DB, $USER;
    $output = '';
    if (!$userid) {
        $userid = $USER->id;
    }
    /**
     * if user can view slide belong to other user
     * of course he can view his own slide
     */
    if (true) {
        $slides = $DB->get_records('slide_storage', ['userid' => $userid]);
        $output .= gethtmlcontentforprintslide($slides);
    }

    return $output;
}

function gethtmlcontentforprintslide($slides) {
    global $CFG;
    if (!$slides || count($slides) <= 0) {
        return html_writer::tag('div', get_string('nothingtodisplay'));
    }
    $out = html_writer::start_tag('div', ['class' => 'row']);

    $index = 1;
    foreach ($slides as $slide) {
        $linkpage = $CFG->wwwroot . '/contentcreator/';
        $out .= html_writer::start_tag('div', ['class' => 'col-sm-4']);
        $out .= html_writer::start_tag('div', ['class' => 'list-slide-content']);
        $out .= html_writer::start_tag('h4', ['class' => 'slide-title', 'data-slide' => $slide->id]);
        $hasfileex = strrpos($slide->filename, '.strut');
        $title = '';
        if($hasfileex === false) {
            $title = $slide->filename;
        } else {
            $title = substr($slide->filename, 0, $hasfileex);
        }
        $out .= $title;
        $out .= html_writer::end_tag('h4');
        $out .= html_writer::start_tag('div', ['class' => 'update-input-value']);
        $out .= html_writer::tag('input', '', ['type' => 'text', 'class' => 'input-update-title', 'value' => $title]);
        $out .= html_writer::end_tag('div');
        $out .= html_writer::start_tag('div', ['class' => 'wrap-btn']);
        $out .= html_writer::tag('button', '', ['class' => 'btn btn-update-title']);
        $out .= html_writer::start_tag('div', ['class' => 'update-do-action']);
        $out .= html_writer::tag('button', 'Save', ['class' => 'btn btn-success btn-save-title']);
        $out .= html_writer::tag('button', 'Cancel', ['class' => 'btn btn-danger btn-cancel-title']);
        $out .= html_writer::end_tag('div');
        $out .= html_writer::end_tag('div');
        $out .= html_writer::start_tag('div', ['class' => 'slide-btn-action']);
        $out .= html_writer::link($linkpage . 'edit.php?id=' . $slide->id,
            get_string('edit'), ['class' => 'btn btn-edit-slide']);
        $out .= html_writer::link($linkpage . 'view/index.php?id=' . $slide->id,
            get_string('view'), ['class' => 'btn btn-view-slide', 'target' => '_blank']);
        $out .= html_writer::end_tag('div');
        $out .= html_writer::end_tag('div');
        $out .= html_writer::end_tag('div');
        if ($index % 3 == 0) {
            $out .= html_writer::start_tag('div', ['class' => 'clearfix hidden-xs']);
            $out .= html_writer::end_tag('div');
        }
        ++$index;
    }
    $out .= html_writer::end_tag('div');
    return $out;
}
