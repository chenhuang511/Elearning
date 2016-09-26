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
    /**
     * Check user cloned
     */
    $sql_raw = "SELECT U.ID, U.USERNAME, CONCAT(CONCAT(U.FIRSTNAME, ' '), U.LASTNAME) AS FULLNAME, U.EMAIL, E.ROLEID, E.COURSEID, GG.FINALGRADE
                FROM M_USER U 
                LEFT JOIN M_USER_ENROLMENTS UE ON U.ID = UE.USERID
                LEFT JOIN M_ENROL E ON E.ID = UE.ENROLID
                LEFT JOIN M_GRADE_GRADES GG ON U.ID = GG.USERID
                LEFT JOIN M_GRADE_ITEMS GI ON GI.ID = GG.ITEMID
                WHERE E.ROLEID IN(4, 5) AND GI.ITEMTYPE = 'course'";
    $sql_users = oci_parse($conn, $sql_raw);
    oci_execute($sql_users);
    $flag = $action = false;
    $hostStudent = $mysqlconn->query("select remoteid from anchor_students")->fetch_all(MYSQLI_ASSOC);
    foreach ($hostStudent as &$value) {
        $value = $value['remoteid'];
    }
    $hostUser = $mysqlconn->query("select remoteid from anchor_users")->fetch_array(MYSQLI_ASSOC);
    foreach ($hostUser as &$value) {
        $value = $value['remoteid'];
    }

    $array = [];
    // not check case user unenrole, delete user, course in hub
    while ($users = oci_fetch_array($sql_users, OCI_ASSOC+OCI_RETURN_NULLS))
    {
        $roleremote = $users['ROLEID'];
        if(!in_array($users['ID'], $array)) {
            //fitter by user
            if($roleremote != 4) {
                if(is_array($hostStudent) && in_array($users['ID'], $hostStudent)) {
                    $sql = "UPDATE anchor_students SET fullname = '" . $users['FULLNAME'] . "' WHERE remoteid = " . $users['ID'];
                    $action = 1;
                } else {
                    $sql = "INSERT INTO anchor_students(remoteid, fullname, email) 
                     VALUES(" . $users['ID'] . "" . " ,'" . $users['FULLNAME'] . "'" . " ,'" .  $users['EMAIL'] . "')";
                    $action = 2;
                }
            } else {
                if(is_array($hostUser) && in_array($users['ID'], $hostUser)) {
                    $sql = "UPDATE anchor_users SET username = '" . $users['USERNAME'] . "', real_name = '" . $users['FULLNAME'] . "' WHERE remoteid = " . $users['ID'];
                    $action = 3;
                } else {
                    $role = 'administrator';
                    $status = 'active';
                    $auth = 'remote';
                    $sql = "INSERT INTO anchor_users(remoteid, username, email, real_name , status, role, auth) 
                         VALUES(" . $users['ID'] . ", '" . $users['USERNAME'] . "', '" . $users['EMAIL'] . "', '" . $users['FULLNAME'] . "', '" . $status . "', '" . $role . "', '" . $auth . "')";
                    $action = 4;
                }
            }
            $mysqlconn->query($sql);
            $newid = $mysqlconn->insert_id ? $mysqlconn->insert_id : 0;
            array_push($array, $users['ID']);
        }

        // check courseid to update
        if ($users['COURSEID']) {
            $courseid = $mysqlconn->query("SELECT id FROM anchor_courses WHERE remoteid = " . $users['COURSEID'])->fetch_row()[0];

            switch ($action) {
                case 1:
                    $id = $mysqlconn->query("SELECT id FROM anchor_students WHERE remoteid = " . $users['ID'])->fetch_row()[0];
                    $sql = "UPDATE anchor_student_course SET grade = '" . $users['FINALGRADE'] . "' WHERE studentid = " . $id . " AND courseid =" . $courseid;
                    break;
                case 2:
                    $sql = "INSERT INTO anchor_student_course(studentid, courseid, grade) 
                     VALUES(" . $newid . "" . " ," . $courseid . ",'" . $users['FINALGRADE'] . "')";
                    break;
                case 3:
                    //if user be unenroll.
                    $id = $mysqlconn->query("SELECT id FROM anchor_users WHERE remoteid = " . $users['ID'])->fetch_row()[0];
                    $sql = "SELECT courseid FROM anchor_user_course WHERE userid = " . $id . " AND courseid =" . $courseid;
                    break;
                case 4:
                    $sql = "INSERT INTO anchor_user_course(userid, courseid) 
                     VALUES(" . $newid . " ," . $courseid . ")";
                    break;
            }

            $mysqlconn->query($sql);
        }
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
$mysqlconn = new mysqli("localhost", "root", "vannhuthe", "anchor");


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