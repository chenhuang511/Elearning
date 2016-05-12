<?php
/**
 * Created by PhpStorm.
 * User: Minh Nguyen
 * Date: 5/6/2016
 * Time: 3:36 PM
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