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
