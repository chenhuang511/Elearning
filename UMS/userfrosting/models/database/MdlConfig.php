<?php
/**
 * Created by PhpStorm.
 * User: vanhaIT
 * Date: 05/05/2016
 * Time: 10:34 SA
 */

namespace UserFrosting;

use \Illuminate\Database\Capsule\Manager as Capsule;

class MdlConfig extends UFModel{
    protected static $_table_id = "mdl_config";
    protected static $connectName = "moodle";
    public function __construct($properties = []) {
        parent::__construct($properties);
    }
}