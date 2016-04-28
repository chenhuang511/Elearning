<?php
/**
 * Created by PhpStorm.
 * User: vanhaIT
 * Date: 23/04/2016
 * Time: 10:04 SA
 */
namespace UserFrosting;

use \Illuminate\Database\Capsule\Manager as Capsule;

class MdlUserPreferences extends UFModel{
    protected static $_table_id = "mdl_user_preferences";
    protected static $connectName = "moodle";
    public function __construct($properties = []) {
        parent::__construct($properties);
    }
}