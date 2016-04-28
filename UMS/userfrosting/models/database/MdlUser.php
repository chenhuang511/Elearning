<?php
/**
 * Created by PhpStorm.
 * User: Minh Nguyen
 * Date: 3/23/2016
 * Time: 5:47 PM
 */

namespace UserFrosting;

use \Illuminate\Database\Capsule\Manager as Capsule;

class MdlUser extends UFModel{
    protected static $_table_id = "mdl_user";
    protected static $connectName = "moodle";
    public function __construct($properties = []) {
        parent::__construct($properties);
    }
}