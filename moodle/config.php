<?php  // Moodle configuration file

unset($CFG);
global $CFG;
$CFG = new stdClass();

$CFG->dbtype    = 'mysqli';
$CFG->dblibrary = 'native';
$CFG->dbhost    = 'localhost';
$CFG->dbname    = 'moodle';
$CFG->dbuser    = 'root';
$CFG->dbpass    = '';

$CFG->prefix    = 'mdl_';
$CFG->dboptions = array (
    'dbpersist' => 0,
    'dbport' => '',
    'dbsocket' => '',
);

$CFG->wwwroot   = 'http://192.168.2.187';
$CFG->dataroot  = 'D:\\Works\\NCCSOFT\\Projects\\elearning\\server\\moodledata';
$CFG->admin     = 'admin';

$CFG->directorypermissions = 0777;

$CFG->loginredir = "{$CFG->wwwroot}/my";
$CFG->logoutredir = "{$CFG->wwwroot}";

$CFG->opensslcnf = 'D:\\Works\\NCCSOFT\\Projects\\elearning\\server\\apache\\conf\\openssl.cnf';

//$CFG->debug = 6143;
//$CFG->debugdisplay = 1;

require_once(dirname(__FILE__) . '/lib/setup.php');

// There is no php closing tag in this file,
// it is intentional because it prevents trailing whitespace problems!
