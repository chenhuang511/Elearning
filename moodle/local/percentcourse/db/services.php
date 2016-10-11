<?php
//var_dump('user services.php');
$functions = array(
    'local_host_get_percent_course' => array(
        'classname'     => 'local_percent_course_external',
        'methodname'    => 'get_percent_course',
        'classpath'     => 'local/percentcourse/externallib.php',
        'description'   => 'Get percent course',
        'type'		    => 'read',
        'ajax'		    => true
    ),
);
