<?php
//defined('INTERNAL') || die();

define('SET_CONTENT', 'setContents');
define('GET_CONTENT', 'getContents');
define('REMOVE_PRESENTATION', 'removePresentations');
define('LIST_PRESENTATION', 'listPresentations');
define('INTERNAL', 1);

require('init.php');

$cruserid = $_SESSION['user/id'];
$rmuserid = isset($_POST['userid']) ? $_POST['userid']: null;
$filename = isset($_POST['filename']) ? $_POST['filename']: null;
$rawdata = isset($_POST['data']) ? $_POST['data']: null;

if(empty($rmuserid)){
    echo json_encode('Empty userid. We can do nothing');
    die;
}
if($cruserid != $rmuserid) {
    echo json_encode('Different userid. We can do nothing');
    die;
}

switch($_POST['action']){
    case SET_CONTENT:
        // Create object data to store DB.
        $data = new stdClass();
        $data->userid = $rmuserid;
        $data->filename = $filename;
        $data->content = $rawdata;
        $slide = get_record('slide_storage', 'userid', $rmuserid, 'filename', $filename);
        db_begin();
        if (!$slide) {
            $data->ctime = db_format_timestamp(time());
            $data->mtime = $data->ctime;
            insert_record('slide_storage', $data);
            echo 'Create successful';
        } else {
            $data->id = $slide->id;
            $data->mtime = db_format_timestamp(time());
            update_record('slide_storage', $data);
            echo 'Update successful';
        }
        db_commit();
        break;
    case GET_CONTENT:
        $slide = get_record('slide_storage', 'userid', $rmuserid, 'filename', $filename);
        if ($slide){
            echo $slide->content;
        } else {
            echo 'Nothing to load!';
        }
        break;
    case REMOVE_PRESENTATION:
        $slide = get_record('slide_storage', 'userid', $rmuserid, 'filename', $filename);
        if ($slide){
            db_begin();
            delete_records('slide_storage', 'userid', $rmuserid, 'filename', $filename);
            db_commit();
            echo "Delete Successfull.";
        } else {
            echo "Nothing to delete.";
        }
        break;
    case LIST_PRESENTATION:
        $lsslides = get_records_array('slide_storage', 'userid', $rmuserid);
        $ls = array();
        foreach ($lsslides as $slide){
            $ls[] = $slide->filename;
        }
        echo json_encode($ls);
        break;
    default:
        break;
}
