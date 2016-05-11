<?php
/**
 * Created by PhpStorm.
 * User: vanhaIT
 * Date: 07/05/2016
 * Time: 11:57 SA
 */

namespace UserFrosting;

use \Illuminate\Database\Capsule\Manager as Capsule;

class MdlUserEnrolments extends UFModel{
    protected static $_table_id = "mdl_user_enrolments";
    protected static $connectName = "moodle";
    public function __construct($properties = []) {
        parent::__construct($properties);
    }
}