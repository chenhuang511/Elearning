<?php
//var_dump('user services.php');
$functions = array(
    'local_host_get_user_link_profile' => array(
        'classname'     => 'local_certificate_external',
        'methodname'    => 'get_user_link_profile',
        'classpath'     => 'local/certificate/externallib.php',
        'description'   => 'Get id user by remote id',
        'type'		    => 'read',
        'ajax'		    => true
    ),
);
