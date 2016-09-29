<?php
 function sync_course()
 {
     global $mysqlconn;
     global $conn;

     if (!$conn) {
         $e = oci_error();
         trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
     }
             $sql_courses = oci_parse($conn, 'SELECT * FROM m_course');
             oci_execute($sql_courses);
             while ($courses = oci_fetch_array($sql_courses, OCI_ASSOC+OCI_RETURN_NULLS))
             {
                 $remoteid  = $mysqlconn->real_escape_string($courses['ID']);
                 $fullname  = $mysqlconn->real_escape_string($courses['FULLNAME']);
                 $shortname = $mysqlconn->real_escape_string($courses['SHORTNAME']);
                 $summary   = $mysqlconn->real_escape_string($courses['SUMMARY']->load());
                 if($mysqlconn->query("SELECT id FROM anchor_courses WHERE remoteid = " . $remoteid)->fetch_row()) {
                     $sql = "UPDATE anchor_courses SET fullname = '" . $fullname . "', shortname = '" . $shortname . "', summary = '" . $summary . "' WHERE remoteid = " . $remoteid;
                 } else {
                     $sql = "INSERT INTO anchor_courses(remoteid, fullname, shortname, summary) 
                         VALUES('" . $remoteid . "'" . " ,'" . $fullname . "'" . " ,'" .  $shortname . "'" . " ,'" . $summary . "')";
                 }
                  $mysqlconn->query($sql);
             }
 }
