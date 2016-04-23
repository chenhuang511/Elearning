<?php
/**
 * Created by PhpStorm.
 * User: Minh Nguyen
 * Date: 3/29/2016
 * Time: 3:41 PM
 */

namespace UserFrosting;

use \Illuminate\Database\Capsule\Manager as Capsule;

class MdlContext extends UFModel{
    protected static $_table_id = "mdl_context";
    protected static $connectName = "moodle";
    public function __construct($properties = []) {
        parent::__construct($properties);
    }
}