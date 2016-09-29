
<?php
/**
 * Created by PhpStorm.
 * User: vanhaIT
 * Date: 30/05/2016
 * Time: 3:50 CH
 */

defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot . '/lib/remote/lib.php');
require_once($CFG->dirroot . '/lib/additionallib.php');

function get_remote_bigbluebuttonbn_by_id($id, $merge = true) {
    global $DB;
    /**
     *  get setting local
     */
    if($merge == true) {
        $fields = 'id, remoteid, course, record';
        $local_bigbluebuttonbn_data = $DB->get_record('bigbluebuttonbn', array('id' => $id), $fields);
        $remoteid = $local_bigbluebuttonbn_data->remoteid;
    } else {
        $remoteid = $id;
    }

    $resp = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_bigbluebuttonbn_get_bigbluebuttonbn_by_id',
            'params' => array('id' => $remoteid)
        )
    );

    if (isset($resp->exception)) {
        return 0;
    }

    /**
     *  override setting hub
     */
    if($merge == true) {
        foreach ($local_bigbluebuttonbn_data as $key => $value){
            $resp->$key = $value;
        }
    }
    return $resp;
}
