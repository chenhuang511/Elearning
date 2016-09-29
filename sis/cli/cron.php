<?php
 define(API_TOKEN_EXT, '0dd5b4427a6f29e7a544f7799e55ed21');
 define(API_TOKEN_INT, 'f1d62833ac40c17e6ffb3efbef3df0ce');
 define(HUB_URL_API, "http://192.168.1.252");

global $mysqlconn;
global $conn;
//  error_reporting(ALL_ERROR);
//@todo: need changing
$mysqlconn = new mysqli("localhost", "root", "12345678", "anchor");

// Check connection
if ($mysqlconn->connect_error) {
    die("Connection failed: " . $mysqlconn->connect_error);
}

$conn = oci_connect('moodle', '123456', '192.168.1.250:1521/XE');
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

require_once ('croncourse.php');

require_once ('cronschools.php');

require_once ('cronuser.php');

$funcs = get_defined_functions();
foreach($funcs['user'] as $func)
{
 if (substr($func, 0, 4) == 'sync') {
     $func();
 }
}
$mysqlconn->close();
