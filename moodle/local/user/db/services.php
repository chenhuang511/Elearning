<?php
//var_dump('user services.php');
$functions = array(
    'local_host_get_user_link_profile' => array(
        'classname'     => 'local_user_external',
        'methodname'    => 'get_user_link_profile',
        'classpath'     => 'local/user/externallib.php',
        'description'   => 'Get id user by remote id',
        'type'		    => 'read',
        'ajax'		    => true
    ),
);
