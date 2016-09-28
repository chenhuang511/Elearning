<?php
 //require_once('../thirdparty/php-rest/rest.inc.php');
 //require_once('../database.inc.php');
 //require_once('../data.php');
 
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
             $sql_courses = oci_parse($conn, 'SELECT * FROM m_course');
             oci_execute($sql_courses);
             while ($courses = oci_fetch_array($sql_courses, OCI_ASSOC+OCI_RETURN_NULLS))
             {
                 $remoteid  = $courses['ID'];
                 $fullname  = $courses['FULLNAME'];
                 $shortname = $courses['SHORTNAME'];
                 $summary   = $courses['SUMMARY']->load();
                 if($mysqlconn->query("SELECT id FROM anchor_courses WHERE remoteid = " . $remoteid)->fetch_row()) {
                     $sql = "UPDATE anchor_courses SET fullname = '" . $fullname . "', shortname = '" . $shortname . "', summary = '" . $summary . "' WHERE remoteid = " . $remoteid;
                 } else {
                     $sql = "INSERT INTO anchor_courses(remoteid, fullname, shortname, summary) 
                         VALUES('" . $remoteid . "'" . " ,'" . $fullname . "'" . " ,'" .  $shortname . "'" . " ,'" . $summary . "')";
                 }
                  $mysqlconn->query($sql);
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
 
//  error_reporting(ALL_ERROR);
  //$mysqlconn = new mysqli($DatabaseServer, $DatabaseUsername, $DatabasePassword, $DatabaseName);
  $mysqlconn = new mysqli("127.0.0.1", "root", "12345678", "anchor");

 
 // Check connection
  if ($mysqlconn->connect_error) {
      die("Connection failed: " . $mysqlconn->connect_error);
  }
 

 $funcs = get_defined_functions();
 foreach($funcs['user'] as $func)
 {
     if (substr($func, 0, 4) == 'sync') {
         $func();
     }
 }
 $mysqlconn->close();