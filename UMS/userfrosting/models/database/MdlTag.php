<?php
/**
 * Created by PhpStorm.
 * User: vanhaIT
 * Date: 23/04/2016
 * Time: 10:05 SA
 */
namespace UserFrosting;

use \Illuminate\Database\Capsule\Manager as Capsule;

class MdlTag extends UFModel{
    protected static $_table_id = "mdl_tag";
    protected static $connectName = "moodle";
    public function __construct($properties = []) {
        parent::__construct($properties);
    }
}