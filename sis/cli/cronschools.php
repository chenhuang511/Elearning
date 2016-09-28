<?php
function sync_school()
{
    global $mysqlconn;
    global $conn;

    if (!$conn) {
         $e = oci_error();
         trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
    }

    $stid = oci_parse($conn, 'SELECT ID, NAME, WWWROOT FROM m_mnet_host');
    oci_execute($stid);
    while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS))
    {
         $id   = $row['ID'];
         $name = $row['NAME'];
         $wwwroot= $row['WWWROOT'];

         if($name != " ")
         {
             if($mysqlconn->query("SELECT id FROM anchor_schools WHERE remoteid = " . $id)->fetch_row()) {
                 $sql = "UPDATE anchor_schools SET name = '" . $name . "' WHERE remoteid = " . $id;
             } else {
                 $sql = "INSERT INTO anchor_schools (remoteid, name, wwwroot) 
                    VALUES('" . $id . "'" . " ,'" . $name . "', '" . $wwwroot . "')";
             }
             $mysqlconn->query($sql);
         }
    }
}
