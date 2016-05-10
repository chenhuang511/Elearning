<?php
ini_set('display_errors', true);
error_reporting(1);
defined('APP_PATH') || define('APP_PATH', realpath('.'));

return new \Phalcon\Config(array(
    'database' => array(
        'adapter'     => 'Mysql',
        'host'        => 'localhost',
        'username'    => 'root',
        'password'    => '123456789$$',
        'dbname'      => 'usermanagement',
        'charset'     => 'utf8',
    ),
    'application' => array(
        'controllersDir' => APP_PATH . '/app/controllers/',
        'modelsDir'      => APP_PATH . '/app/models/',
        'migrationsDir'  => APP_PATH . '/app/migrations/',
        'viewsDir'       => APP_PATH . '/app/views/',
        'pluginsDir'     => APP_PATH . '/app/plugins/',
        'libraryDir'     => APP_PATH . '/app/library/',
        'cacheDir'       => APP_PATH . '/app/cache/',
        'vendorDir'       => APP_PATH . '/app/vendor/',
        'cultureDir'       => APP_PATH . '/app/config/i18n/',
        'baseUri'        => '/',
        'baseUrl' => 'http://ums.dev/'
    ),
    "media"=>array(
        'dir'=>'/home/ums.dev/public_html/public/',
        "host"=>"http://ums.dev/"
    )
));
