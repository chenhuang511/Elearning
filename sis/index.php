<?php

/*
               /   \
              |  o  |
               \   /
        ________) (________
       |                   |
       '------.     .------'
               |   |
               |   |
               |   |
               |   |
    /\         |   |         /\
   /_ \        /   \        / _\
     \ '.    .'     '.    .' /
      \  '--'         '--'  /
       '.                 .'
         '._           _.'
            `'-.   .-'`
                \ /
*/
define('PENDING', 1);
define('APPROVED', 2);
define('PUBLISHED', 3);

define('DS', DIRECTORY_SEPARATOR);
define('ENV', getenv('APP_ENV'));
define('VERSION', '0.12.1');
define('MIGRATION_NUMBER', 211);

define('PATH', dirname(__FILE__) . DS);
define('APP', PATH . 'anchor' . DS);
define('SYS', PATH . 'system' . DS);
define('EXT', '.php');

require APP . 'composer_check' . EXT;
require SYS . 'start' . EXT;
