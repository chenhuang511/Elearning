
<?php
/**
 * Created by PhpStorm.
 * User: vanhaIT
 * Date: 30/05/2016
 * Time: 3:50 CH
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/lib/remote/lib.php');
require_once($CFG->dirroot . '/lib/additionallib.php');

function get_remote_certificate_by_id($id, $merge = true) {
    global $DB;
    /**
     *  get certificate setting local
     */
    if($merge == true) {
        $fields = ' id,
                remoteid,
                emailteachers,
                emailothers,
                savecert,
                reportcert,
                delivery,
                requiredtime,
                orientation,
                borderstyle,
                bordercolor,
                printwmark,
                printdate,
                datefmt,
                printnumber,
                printgrade,
                gradefmt,
                printoutcome,
                printhours,
                printteacher,
                customtext,
                printsignature,
                printseal,
                timecreated,
                timemodified';
        $local_certificate_data = $DB->get_record('certificate', array('id' => $id), $fields);
        $remoteid = $local_certificate_data->remoteid;
    } else {
        $remoteid = $id;
    }

    $resp = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_certificate_get_certificate_by_id',
            'params' => array('id' => $remoteid)
        ), false
    );

    if (isset($resp->exception)) {
        return 0;
    }
    /**
     *  override certificate setting hub
     */

    if($merge == true) {
        foreach ($local_certificate_data as $key => $value){
            $resp->$key = $value;
        }
    }
    return $resp;
}


