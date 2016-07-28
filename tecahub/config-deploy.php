<?php

$CFG->dbtype    = 'oci';      // 'pgsql', 'mariadb', 'mysqli', 'mssql', 'sqlsrv' or 'oci'
$CFG->dblibrary = 'native';     // 'native' only at the moment
$CFG->dbhost    = '';  // eg 'localhost' or 'db.isp.com' or IP
$CFG->dbname    = '192.168.1.250:1521/XE';     // database name, eg moodle
$CFG->dbuser    = 'moodle';   // your database username
$CFG->dbpass    = '123456';   // your database password
$CFG->prefix    = 'm_';       // prefix to use for all table names
$CFG->dboptions = array(
    'dbpersist' => false,       // should persistent database connections be
                                //  used? set to 'false' for the most stable
                                //  setting, 'true' can improve performance
                                //  sometimes
    'dbsocket'  => false,       // should connection via UNIX socket be used?
                                //  if you set it to 'true' or custom path
                                //  here set dbhost to 'localhost',
                                //  (please note mysql is always using socket
                                //  if dbhost is 'localhost' - if you need
                                //  local port connection use '127.0.0.1')
    'dbport'    => '',          // the TCP port number to use when connecting
                                //  to the server. keep empty string for the
                                //  default port
);



$CFG->wwwroot   = 'http://192.168.1.252';
$CFG->dataroot  = '/home/nccsoft/moodledata';



$CFG->directorypermissions = 02777;

$CFG->loginredir = "{$CFG->wwwroot}/my";
$CFG->logoutredir = "{$CFG->wwwroot}";


//define('MDL_PERF', true);
//define('MDL_PERFDB', true);
//define('MDL_PERFTOLOG', true);
//define('MDL_PERFTOFOOT', true);

$CFG->admin = 'admin';

$CFG->lang = 'en';

$CFG->langlocalroot    = dirname(__FILE__) . "/../moodle/lang";
$CFG->langotherroot    = dirname(__FILE__) . "/../moodle/lang";
$CFG->skiplangupgrade  = true;
$CFG->allowdownloadresource = true;

require_once(dirname(__FILE__) . '/lib/setup.php'); // Do not edit


