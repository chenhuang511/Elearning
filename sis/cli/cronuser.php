<?php
function sync_user()
{
    global $mysqlconn;
    global $conn;

    if (!$conn) {
        $e = oci_error();
        trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }

    $sql_raw = "SELECT R.ID, R.MNETHOSTID, R.USERNAME, CONCAT(CONCAT(R.FIRSTNAME, ' '), R.LASTNAME) AS FULLNAME, R.EMAIL, R.ROLEID, R.INSTANCEID AS COURSEID, G.FINALGRADE
                FROM (SELECT U.ID, U.MNETHOSTID, U.USERNAME, U.FIRSTNAME, U.LASTNAME, U.EMAIL, C.INSTANCEID, RA.ROLEID
                      FROM M_USER U 
                      INNER JOIN M_ROLE_ASSIGNMENTS RA ON RA.USERID = U.ID AND RA.COMPONENT = 'enrol_mnet'
                      INNER JOIN M_CONTEXT C ON RA.CONTEXTID = C.ID AND C.CONTEXTLEVEL = 50
                      ) R
                      LEFT JOIN 
                      (SELECT GG.USERID, GI.COURSEID, GG.FINALGRADE FROM M_GRADE_ITEMS GI
                      INNER JOIN M_GRADE_GRADES GG ON GI.ID = GG.ITEMID
                      WHERE GI.ITEMTYPE = 'course') G ON G.USERID = R.ID AND G.COURSEID = R.INSTANCEID";

    $sql_users = oci_parse($conn, $sql_raw);
    oci_execute($sql_users);

    $hostStudent = $mysqlconn->query("select remoteid from anchor_students")->fetch_all(MYSQLI_ASSOC);
    foreach ($hostStudent as &$value) {
        $value = $value['remoteid'];
    }
    $hostUser = $mysqlconn->query("select remoteid from anchor_users")->fetch_all(MYSQLI_ASSOC);
    foreach ($hostUser as &$value) {
        $value = $value['remoteid'];
    }

    $abc = Array();
    $hubstudents = Array();
    $hubusers = Array();
    // not check case user unenrole, delete user, course in hub
    while ($users = oci_fetch_array($sql_users, OCI_ASSOC+OCI_RETURN_NULLS))
    {
        $roleremote = $users['ROLEID'];
        $fullname = $mysqlconn->real_escape_string($users['FULLNAME']);
        if(!in_array($users['ID'].$users['COURSEID'].$users['ROLEID'], $abc)) {
            //fitter by user
            $schoolid = $mysqlconn->query("SELECT id FROM anchor_schools WHERE remoteid = " . $users['MNETHOSTID'])->fetch_row()[0];
            if($roleremote >= 5) {
                if(!empty($mysqlconn->query("SELECT id FROM anchor_students WHERE remoteid = " . $users['ID'])->fetch_all())) {
                    $sql = "UPDATE anchor_students SET fullname = '" . $fullname . "' WHERE remoteid = " . $users['ID'];
                } else {
                    $sql = "INSERT INTO anchor_students(remoteid, fullname, email, schoolid) 
                     VALUES(" . $users['ID'] . "" . " ,'" . $fullname . "'" . " ,'" .  $users['EMAIL'] . "', " . $schoolid . ")";
                }
                array_push($hubstudents,$users['ID']);
            } else {
                if(!empty($mysqlconn->query("SELECT id FROM anchor_users WHERE remoteid = " . $users['ID'])->fetch_all())) {
                    $sql = "UPDATE anchor_users SET username = '" . $users['USERNAME'] . "', real_name = '" . $fullname . "' WHERE remoteid = " . $users['ID'];
                } else {
                    $role = 'administrator';
                    $status = 'active';
                    $auth = 'remote';
                    $sql = "INSERT INTO anchor_users(remoteid, username, email, real_name , status, role, auth, schoolid) 
                         VALUES(" . $users['ID'] . ", '" . $users['USERNAME'] . "', '" . $users['EMAIL'] . "', '" . $fullname . "', '" . $status . "', '" . $role . "', '" . $auth . "', " . $schoolid . ")";
                }
                array_push($hubusers,$users['ID']);
            }
            $mysqlconn->query($sql);
            array_push($abc, $users['ID'].$users['COURSEID'].$users['ROLEID']);
        }

        // check courseid to update
        $courseid = $mysqlconn->query("SELECT id FROM anchor_courses WHERE remoteid = " . $users['COURSEID'])->fetch_row()[0];
        if($roleremote >= 5) {
            $studentid = $mysqlconn->query("SELECT id FROM anchor_students WHERE remoteid = " . $users['ID'])->fetch_row()[0];
            $remotegrade = $users['FINALGRADE'] ? $users['FINALGRADE'] : 0;
            if (!empty($grade = $mysqlconn->query("SELECT grade FROM anchor_student_course WHERE studentid = " . $studentid . " AND courseid =" . $courseid)->fetch_all())) {
                $grade = $grade[0];
                if ($remotegrade != $grade) {
                    $sql = "UPDATE anchor_student_course SET grade = '" . $remotegrade . "' WHERE studentid = " . $studentid . " AND courseid = " . $courseid;
                }
            } else {
                $sql = "INSERT INTO anchor_student_course(studentid, courseid, grade, remoterole) 
                         VALUES(" . $studentid . ", '" . $courseid . "', '" . $remotegrade . "', " . $roleremote . ")";
            }
        } else {
                $userid = $mysqlconn->query("SELECT id FROM anchor_users WHERE remoteid = " . $users['ID'])->fetch_row()[0];
                if(empty($mysqlconn->query("SELECT userid FROM anchor_user_course WHERE userid = " . $userid . " AND courseid =" . $courseid)->fetch_all())) {
                    $sql = "INSERT INTO anchor_user_course(userid, courseid, remoterole) 
                         VALUES(" . $userid . ", '" . $courseid . "', " . $roleremote . ")";
                }
        }
        $mysqlconn->query($sql);
    }
    //delete
    $studentrole = $mysqlconn->query("SELECT studentid, courseid, remoterole FROM anchor_student_course")->fetch_all(MYSQLI_ASSOC);
    $userroles = $mysqlconn->query("SELECT userid, courseid, remoterole FROM anchor_user_course")->fetch_all(MYSQLI_ASSOC);
    $userroles = array_merge($userroles, $studentrole);
    $oldstudents = $mysqlconn->query("SELECT remoteid FROM anchor_students")->fetch_all(MYSQLI_ASSOC);
    $oldusers = $mysqlconn->query("SELECT remoteid FROM anchor_users")->fetch_all(MYSQLI_ASSOC);
    foreach ($oldstudents as $oldstudent) {
        if(!in_array($oldstudent['remoteid'], $hubstudents)) {
            $mysqlconn->query("DELETE FROM anchor_students WHERE remoteid = " . $oldstudent['remoteid']);
        }
    }

    foreach ($oldusers as $olduser) {
        if(!in_array($olduser['remoteid'], $hubusers)) {
            $mysqlconn->query("DELETE FROM anchor_users WHERE remoteid = " . $olduser['remoteid']);
        }
    }

    foreach ($userroles as $userrole) {
        $role = $userrole['remoterole'];
        if($role >= 5) {
            $userid = $mysqlconn->query("SELECT remoteid FROM anchor_students WHERE id = " . $userrole['studentid'])->fetch_row()[0];
        } else {
            $userid = $mysqlconn->query("SELECT remoteid FROM anchor_users WHERE id = " . $userrole['userid'])->fetch_row()[0];
        }
        $courseid = $mysqlconn->query("SELECT remoteid FROM anchor_courses WHERE id = " . $userrole['courseid'])->fetch_row()[0];

        if(!in_array($userid.$courseid.$role, $abc)) {
            if($role >= 5) {
                $mysqlconn->query("DELETE FROM anchor_student_course WHERE studentid = " . $userrole['studentid'] . " AND courseid =" .$userrole['courseid'] . " AND remoterole =" . $role);
            } else {
                var_dump("DELETE FROM anchor_user_course WHERE userid = " . $userid . " AND courseid =" .$courseid . " AND remoterole =" . $role);
                $mysqlconn->query("DELETE FROM anchor_user_course WHERE userid = " . $userrole['userid'] . " AND courseid =" .$userrole['courseid'] . " AND remoterole =" . $role);
            }
        }
    }
}
