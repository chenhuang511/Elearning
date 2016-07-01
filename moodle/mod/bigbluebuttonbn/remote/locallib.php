
<?php
/**
 * Created by PhpStorm.
 * User: vanhaIT
 * Date: 30/05/2016
 * Time: 3:50 CH
 */

defined('MOODLE_INTERNAL') || die;
require_once($CFG->dirroot . '/lib/dml/json_moodle_recordset.php');
require_once($CFG->dirroot . '/lib/dml/mysqli_native_moodle_recordset.php');

require_once($CFG->dirroot . '/lib/remote/lib.php');
require_once($CFG->dirroot . '/lib/additionallib.php');
require_once($CFG->dirroot . '/mnet/service/enrol/locallib.php');


function get_remote_bigbluebuttonbn_by_id($id) {
    $resp = moodle_webservice_client(
        array(
            'domain' => HUB_URL,
            'token' => HOST_TOKEN,
            'function_name' => 'local_mod_bigbluebuttonbn_get_bigbluebuttonbn_by_id',
            'params' => array('id' => $id)
        )
    );
    return $resp;
}
