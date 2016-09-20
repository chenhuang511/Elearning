<?php

//require_once('../thirdparty/php-rest/rest.inc.php');
require_once('../data.php');

define(API_TOKEN_EXT, '0dd5b4427a6f29e7a544f7799e55ed21');
define(API_TOKEN_INT, 'f1d62833ac40c17e6ffb3efbef3df0ce');
define(HUB_URL_API, "http://192.168.1.252");

global $mysqlconn;

function ws_client($params, $funcname, $type='json', $token=API_TOKEN_EXT)
{
   $serverUrl = HUB_URL_API . '/webservice/rest/server.php' . '?wstoken=' .
       $token . '&wsfunction=' .
       $funcname . '&moodlewsrestformat=json';

   $retval = RestCurl::post($serverUrl, $params);
   switch($type) {
       case 'json':
           $retval = json_decode($retval);
           break;
       default:
           break;
   }
   return $retval;
}

function get_remote_network_peer()
{
   global $mysqlconn;
   // Connects to the XE service (i.e. database) on the "localhost" machine
   $conn = oci_connect('moodle', '123456', '192.168.1.250:1521/XE');
   if (!$conn) {
       $e = oci_error();
       trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
   }

   $stid = oci_parse($conn, 'SELECT id, ip_address, name FROM m_mnet_host');
   oci_execute($stid);
   while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
       $title = $row["NAME"];
       $www_address = $row["IP_ADDRESS"];
       if($www_address != null && $row["ID"] != 1) {
           $sql = "INSERT INTO schools(title, www_address) VALUES('" . $title . "'" . " ,'" .  $www_address . "')";
           $mysqlconn->query($sql);
       }
       echo "<br>";
   }
}

function sync_school()
{

}

// sync course
/*| courses | CREATE TABLE `courses` (
`syear` decimal(4,0) NOT NULL,
 `course_id` int(8) NOT NULL AUTO_INCREMENT,
 `subject_id` decimal(10,0) NOT NULL,
 `school_id` decimal(10,0) NOT NULL,
 `grade_level` decimal(10,0) DEFAULT NULL,
 `title` varchar(100) DEFAULT NULL,
 `short_name` varchar(25) DEFAULT NULL,
 `rollover_id` decimal(10,0) DEFAULT NULL,
 PRIMARY KEY (`course_id`),
 KEY `courses_ind1` (`course_id`,`syear`) USING BTREE,
 KEY `courses_ind2` (`subject_id`) USING BTREE
) ENGINE=MyISAM AUTO_INCREMENT=80 DEFAULT CHARSET=utf8 |
*/
function sync_course()
{
   get_remote_network_peer();
}

// sync course
function sync_course_details()
{

}

// sync course
function sync_course_subjects()
{

}

// sync course periods
function sync_course_periods()
{

}


//error_reporting(ALL_ERROR);
//$funcs = get_defined_functions();
//$mysqlconn = new mysqli("localhost", "root", "vannhuthe", "opensis-ml");
//$mysqlconn = new mysqli($DatabaseServer, $DatabaseUsername, $DatabasePassword, $DatabaseName);

// Check connection
//if ($mysqlconn->connect_error) {
//   die("Connection failed: " . $mysqlconn->connect_error);
//}

foreach($funcs['user'] as $func)
{
   if (substr($func, 0, 4) == 'sync') {
       $func();
   }
}
//$mysqlconn->close();