<?php

require_once("../config.php");

define('SET_CONTENT', 'setContents');
define('GET_CONTENT', 'getContents');
define('REMOVE_PRESENTATION', 'removePresentations');
define('LIST_PRESENTATION', 'listPresentations');

global $USER, $DB;

require_login();

$cruserid = $USER->id;
$filename = isset($_POST['filename']) ? $_POST['filename']: null;
$rawdata = isset($_POST['data']) ? $_POST['data']: null;
$slidedata = json_decode($rawdata);

switch($_POST['action']){
    case SET_CONTENT:
        // Create object data to store DB.
        $data = new stdClass();
        $data->userid = $cruserid;
        $data->filename = $filename;
        $data->content_json = json_encode($slidedata->contentJSON);
        $data->content_html = $slidedata->contentHTML;
        $slide = $DB->get_record('slide_storage', ['userid' => $cruserid, 'filename' => $filename]);

        if (!$slide) {
            $data->timecreated = time();
            $data->timemodified = $data->timecreated;
            $itemid = $DB->insert_record('slide_storage', $data, true);
            echo json_encode(['message' => 'Create successful', 'slideid' => $itemid]);
        } else {
            $data->id = $slide->id;
            $data->timemodified = time();
            $updated = $DB->update_record('slide_storage', $data);
            if ($updated) {
                echo json_encode(['message' => 'Update successful', 'slideid' => $data->id]);
            } else {
                echo json_encode(['message' => 'Update failue', 'slideid' => $data->id]);
            }
        }
        break;
    case GET_CONTENT:
        $slide = get_record('slide_storage', 'userid', $cruserid, 'filename', $filename);
        if ($slide){
            echo $slide->content;
        } else {
            echo json_encode('Nothing to load!');
        }
        break;
    case REMOVE_PRESENTATION:
        $slide = get_record('slide_storage', 'userid', $cruserid, 'filename', $filename);
        if ($slide){
            db_begin();
            delete_records('slide_storage', 'userid', $cruserid, 'filename', $filename);
            db_commit();
            echo json_encode('Delete Successfull.');
        } else {
            echo json_encode('Nothing to delete.');
        }
        break;
    case LIST_PRESENTATION:
        $lsslides = get_records_array('slide_storage', 'userid', $cruserid);
        $ls = array();
        foreach ($lsslides as $slide){
            $ls[] = $slide->filename;
        }
        echo json_encode($ls);
        break;
    default:
        break;
}
